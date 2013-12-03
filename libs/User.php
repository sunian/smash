<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/19/13
 * Time: 5:34 PM
 */

require_once('DbUtil.php');
require_once('JSONObject.php');

class User extends JSONObject
{
    var $user_id;
    var $username;
    var $password;
    var $name;
    var $email;
    var $login_count;
    var $date_created;
    var $date_changed_username;
    var $access_token;
    var $role;

    public static function nu($username)
    {
        $instance = new self();
        $instance->username = $username;
        $instance->populateFieldsFromUsername();
        return $instance;
    }

    public function populateFieldsFromUsername()
    {
        try {
            $conn = DbUtil::connect();
            $sqlString = "SELECT name, email, role_id FROM user WHERE username = :username";
            $params = array("username" => $this->username);
            $stmt = $conn->prepare($sqlString);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = clean($stmt->fetch());
            $this->name = $row["name"];
            $this->email = $row["email"];
            $this->role = $row["role_id"];
            $stmt->closeCursor();

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getAccessToken()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT concat(md5(CAST(login_count AS CHAR(20))), password) FROM user WHERE username = :username";
            $params = array("username" => $this->username);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $password = crypt(clean($stmt->fetchColumn()), $this->password);
            $stmt->closeCursor();
            if (strcmp($password, $this->password) == 0) {
                $sql_string = "select getAccessToken(:username)";
                $stmt = $conn->prepare($sql_string);
                $stmt->execute($params);
                $token = clean($stmt->fetchColumn());
                $stmt->closeCursor();
                return $token;
            }

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getLoginCount()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT login_count FROM user WHERE username = :username";
            $params = array("username" => $this->username);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $this->login_count = clean($stmt->fetchColumn());
            $stmt->closeCursor();
            return $this->login_count ? $this->login_count : mt_rand(3, 666);

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function createUser()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT user_id FROM user WHERE username = :username";
            $params = array("username" => $this->username);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                // Error message!!
                return "That username has already been taken!";
            }
            $stmt->closeCursor();
            $sql_string = "INSERT INTO user (username, password, name, email, date_created, role_id)
                VALUES (:username, :password, :name, :email, CURDATE(), :role)";
            $params["password"] = $this->password;
            $params["name"] = $this->name;
            $params["email"] = $this->email;
            $params["role"] = $this->role;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function render($expanded = false)
    {
        echo "not implemented!\n";
    }

    public function authenticateWithToken($user_token)
    {
        $this->access_token = $user_token;
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT user_id FROM user WHERE username = :username AND access_token = :token
                            AND DATE_ADD(token_set_date,INTERVAL 14 DAY) > CURDATE()";
            $params = array("username" => $this->username, "token" => $this->access_token);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $this->user_id = clean($stmt->fetchColumn());
            $stmt->closeCursor();
            if ($this->user_id) {
                return $this;
            } else {
                return null;
            }

        } catch (PDOException $e) {
            return $e->getMessage();
        }

    }
}