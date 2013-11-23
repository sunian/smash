<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/19/13
 * Time: 5:34 PM
 */

require_once('DbUtil.php');
require_once('Character.php');

class Version extends JSONObject{
    var $title;
    var $release_date = null;
    var $version_number = null;
    var $abbreviation = null;
    var $version_id = null;
    var $characters;
    var $pretty_name;

    public function createIdentity() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT title FROM version WHERE title = :title" . $this->version_number?" AND version_number = :version_number":"";
            $params = array("title"=>$this->title, "version_number"=>$this->version_number, "abbreviation"=>$this->abbreviation,
                "release_date"=>$this->release_date);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                // Error message!!
                return "That version already exists!";
            }
            $stmt->closeCursor();
            $sql_string = "INSERT INTO version (title" . $this->release_date?", release_date":"" .
                $this->version_number?", version_number":"" . $this->abbreviation?", abbreviation)":")";
            $sql_string = $sql_string . "VALUES(:title" . $this->release_date?", :release_date":"" .
                $this->version_number?", :version_number":"" . $this->abbreviation?", :abbreviation)":")";
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function populateFieldsFromID() {
        try {
            $conn = DbUtil::connect();
            $sqlString = "SELECT title, version_number, release_date, abbreviation FROM version WHERE version_id = :version_id";
            $params = array("version_id"=>$this->version_id);
            $stmt = $conn->prepare($sqlString);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            $this->title = $row["title"];
            $this->abbreviation = $row["abbreviation"];
            $this->release_date = $row["release_date"];
            $this->version_number = $row["version_number"];

            $stmt = $conn->prepare("SELECT name FROM pretty_version WHERE version_id = :version_id");
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            $this->pretty_name = $row["name"];

            $sqlString = "SELECT c.character_id AS id, i.name AS name, u.name AS universe, c.weight, c.height,
                c.falling_speed_rank AS falling_speed, c.air_speed_rank AS air_speed, i.nickname AS nick, :version_name AS version
                FROM character_identity as i INNER JOIN `character` as c on i.identity_id = c.identity_id
                INNER JOIN universe as u on i.universe_id = u.universe_id INNER JOIN version as v on v.version_id = c.version_id
                WHERE v.version_id = :version_id";
            $stmt = $conn->prepare($sqlString);
            $params["version_name"] = $this->pretty_name;
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $characters = $stmt->fetchAll();
            echo "hello james....";
            $this->characters = JSONList::nu("Character", $characters);
            echo "hello again james....";
            print_r($this->characters);
//            $character_count = 0;
//            foreach($characters as $row) {
//                $row["version"] = $this->pretty_name;
//                $character = new Character();
//                $character->set($row);
//                $this->characters[$character_count++] = $character;
//            }
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }
}