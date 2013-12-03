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

    public static function nu($username)
    {
        $instance = new self();
        $instance->username = $username;
        $instance->populateFieldsFromUsername();
        return $instance;
    }

    public function populateFieldsFromUsername()
    {
//        try {
//            $conn = DbUtil::connect();
//            $sqlString = "SELECT title, version_number, release_date, abbreviation FROM version WHERE version_id = :version_id";
//            $params = array("version_id" => $this->version_id);
//            $stmt = $conn->prepare($sqlString);
//            $stmt->execute($params);
//            $stmt->setFetchMode(PDO::FETCH_ASSOC);
//            $row = clean($stmt->fetch());
//            $this->title = $row["title"];
//            $this->abbreviation = $row["abbreviation"];
//            $this->release_date = $row["release_date"];
//            $this->version_number = $row["version_number"];
//
//            $stmt = $conn->prepare("SELECT name FROM pretty_version WHERE version_id = :version_id");
//            $stmt->execute($params);
//            $stmt->setFetchMode(PDO::FETCH_ASSOC);
//            $row = clean($stmt->fetch());
//            $this->pretty_name = $row["name"];
//
//            $sqlString = "SELECT c.character_id AS id, i.name AS name, u.name AS universe, c.weight, c.height,
//                c.falling_speed_rank AS falling_speed, c.air_speed_rank AS air_speed, i.nickname AS nick, :version_name AS version
//                FROM character_identity AS i INNER JOIN `character` AS c ON i.identity_id = c.identity_id
//                INNER JOIN universe AS u ON i.universe_id = u.universe_id INNER JOIN version AS v ON v.version_id = c.version_id
//                WHERE v.version_id = :version_id";
//            $stmt = $conn->prepare($sqlString);
//            $params["version_name"] = $this->pretty_name;
//            $stmt->execute($params);
//            $this->characters = clean($stmt->fetchAll(PDO::FETCH_CLASS, "Character"));
//        } catch (PDOException $e) {
//            return $e->getMessage();
//        }
    }

    public function getAccessToken() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT concat(CAST(login_count as CHAR(20)), password) FROM user WHERE username = :username";
            $params = array("username" => $this->username);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
//            $password = crypt(clean($stmt->fetchColumn()), $this->password);
            $column = $stmt->fetchColumn();
            $stmt->closeCursor();
//            return strcmp($password, $this->password) == 0 ? "good" : "bad";
            return $column;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getLoginCount() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT login_count FROM user WHERE username = :username";
            $params = array("username" => $this->username);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $this->login_count = clean($stmt->fetchColumn());
            $stmt->closeCursor();
            return $this->login_count;

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
            $sql_string = "INSERT INTO user (username, password, name, email, date_created)
                VALUES (:username, :password, :name, :email, CURDATE())";
            $params["password"] = $this->password;
            $params["name"] = $this->name;
            $params["email"] = $this->email;
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
}