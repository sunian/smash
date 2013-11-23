<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/15/13
 * Time: 11:55 PM
 */
require_once('DbUtil.php');
require_once('Technique.php');
require_once('Player.php');
require_once('Character.php');
require_once('Version.php');

class Video extends JSONObject {
    public $video_id = null;
    public $title = null;
    public $url = null;
    public $date_added = null;
    public $players = null;
    public $techniques = null;
    public $characters = null;
    public $versions = null;

    public function createIdentity()
    {
        try {
            if($this->url{strlen($this->url)-1}=='/') $this->url = substr($this->url, 0, strlen($this->url)-1);
            $conn = DbUtil::connect();
            $sql_string = "SELECT video_id FROM video WHERE url = :url";
            $params = array("url" => $this->url);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                // Error message!!
                return "That video already exists!";
            }
            $stmt->closeCursor();
            $sql_string = "INSERT INTO video (title, url, date_added) VALUES (:title, :url, NOW())";
            $stmt = $conn->prepare($sql_string);
            $params["title"] = $this->title;
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function populateFieldsFromID() {
        $conn = DbUtil::connect();
        $sqlString = "SELECT title, url, date_added FROM video WHERE video_id = :video_id";
        $params = array("video_id"=>$this->video_id);
        $stmt = $conn->prepare($sqlString);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        $this->date_added = $row["date_added"];
        $this->url = $row["url"];
        $this->title = $row["title"];

        $this->populateTechniques();
        $this->populatePlayers();
        $this->populateCharacters();
        $this->populateVersions();
    }

    private function populateTechniques() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT technique_id, name, abbreviation FROM video NATURAL JOIN video_player NATURAL JOIN technique_usage NATURAL JOIN technique" .
                " WHERE video_id = :video_id";
            $params = array("video_id" => $this->video_id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $this->techniques = $stmt->fetchAll(PDO::FETCH_CLASS, "Technique");
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    private function populateVersions() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT v.title AS title, v.version_number, p.name AS pretty_name
                FROM video NATURAL JOIN video_version NATURAL JOIN version AS v NATURAL JOIN pretty_version AS p" .
                " WHERE video_id = :video_id";
            $params = array("video_id" => $this->video_id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $this->techniques = $stmt->fetchAll(PDO::FETCH_CLASS, "Technique");
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getIDFromURL() {
        $query = substr($this->url, strpos($this->url, "?"));
        $query = substr($query, strpos($query, "v=")+2);
        if(strpos($query, "&")>-1) $query = substr($query, 0, strpos($query, "&"));
        return $query;
    }

    private function populatePlayers() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT player.player_id, player.region_id, player.name AS name, tag, region.name AS region_name
                FROM video INNER JOIN video_player ON (video.video_id = video_player.video_id) INNER JOIN player ON
                video_player.player_id = player.player_id INNER JOIN region ON region.region_id = player.region_id WHERE
                video.video_id = :video_id";
            $stmt = $conn->prepare($sql_string);
            $params = array("video_id"=>$this->video_id);
            $stmt->execute($params);
            echo $stmt->rowCount();
            $this->players = $stmt->fetchAll(PDO::FETCH_CLASS, "Player");
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    private function populateCharacters() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT c.character_id AS id, i.name AS name, u.name AS universe, c.weight, c.height,
                c.falling_speed_rank AS falling_speed, c.air_speed_rank AS air_speed, i.nickname AS nick
                FROM character_identity as i INNER JOIN `character` as c on i.identity_id = c.identity_id
                INNER JOIN universe as u on i.universe_id = u.universe_id INNER JOIN version as v on v.version_id = c.version_id
                INNER JOIN video_player as vp on vp.character_id=c.character_id AND vp.player_id=p.player_id INNER JOIN
                video ON vp.video_id = video.video_id WHERE video.video_id = :video_id";
            $stmt = $conn->prepare($sql_string);
            $params = array("video_id"=>$this->video_id);
            $stmt->execute($params);
            echo $stmt->rowCount();
            $this->players = $stmt->fetchAll(PDO::FETCH_CLASS, "Character");
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }
}
