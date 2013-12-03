<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/19/13
 * Time: 5:34 PM
 */

require_once('DbUtil.php');
require_once('Character.php');

class Version extends JSONObject
{
    var $title;
    var $release_date = null;
    var $version_number = null;
    var $abbreviation = null;
    var $version_id = null;
    var $characters;
    var $pretty_name;
    var $pretty_abbrev;

    public function createIdentity()
    {
        if ($GLOBALS['authenticatedUser'] == null) return;
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT title FROM version WHERE title = :title" . ($this->version_number ? " AND version_number =
                :version_number" : "");
            $params = array("title" => $this->title);
            if($this->version_number) $params["version_number"] = $this->version_number;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                // Error message!!
                return "That version already exists!";
            }
            $stmt->closeCursor();

            $sql_string = "INSERT INTO version (title" . ($this->release_date ? ", release_date" : "") .
                ($this->version_number ? ", version_number" : "") . ($this->abbreviation ? ", abbreviation)" : ")");
            $sql_string = $sql_string . "VALUES(:title" . ($this->release_date ? ", :release_date" : "") .
                ($this->version_number ? ", :version_number" : "") . ($this->abbreviation ? ", :abbreviation)" : ")");
            if($this->abbreviation) $params["abbreviation"] = $this->abbreviation;
            if($this->release_date) $params["release_date"] = $this->release_date;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function populateFieldsFromID()
    {
        try {
            $conn = DbUtil::connect();
            $sqlString = "SELECT title, version_number, release_date, abbreviation FROM version WHERE version_id = :version_id";
            $params = array("version_id" => $this->version_id);
            $stmt = $conn->prepare($sqlString);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = clean($stmt->fetch());
            $this->title = $row["title"];
            $this->abbreviation = $row["abbreviation"];
            $this->release_date = $row["release_date"];
            $this->version_number = $row["version_number"];

            $stmt = $conn->prepare("SELECT name, abbrev_name FROM pretty_version WHERE version_id = :version_id");
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = clean($stmt->fetch());
            $this->pretty_name = $row["name"];
            $this->pretty_abbrev = $row["abbrev_name"];

            $sqlString = "SELECT c.character_id AS id, i.name AS name, u.name AS universe, c.weight, c.height,
                c.falling_speed_rank AS falling_speed, c.air_speed_rank AS air_speed, i.nickname AS nick, :version_name AS version
                FROM character_identity AS i INNER JOIN `character` AS c ON i.identity_id = c.identity_id
                INNER JOIN universe AS u ON i.universe_id = u.universe_id INNER JOIN version AS v ON v.version_id = c.version_id
                WHERE v.version_id = :version_id";
            $stmt = $conn->prepare($sqlString);
            $params["version_name"] = $this->pretty_name;
            $stmt->execute($params);
            $this->characters = clean($stmt->fetchAll(PDO::FETCH_CLASS, "Character"));
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function render($expanded = false)
    {
        echo "<a href='version.php?t=$this->version_id'>", $expanded ? $this->pretty_name : $this->pretty_abbrev, "</a>";
    }
}