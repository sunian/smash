<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/15/13
 * Time: 11:55 PM
 */
require_once('DbUtil.php');
require_once('Technique.php');

class Video extends JSONObject {
    public $video_id = null;
    public $title = null;
    public $url = null;
    public $date_added = null;
    public $players = null;
    public $techniques = null;
    public $characters = null;

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

    public function getIDFromURL() {
        $query = substr($this->url, strpos($this->url, "?"));
        $query = substr($query, strpos($query, "v=")+2);
        if(strpos($query, "&")>-1) $query = substr($query, 0, strpos($query, "&"));
        return $query;
    }

//    public function getPlayers() {
//        try {
//            $conn = DbUtil::connect();
//            $sql_string = "SELECT tag FROM video NATURAL JOIN video_player NATURAL JOIN player WHERE video_id = :video_id";
//            $stmt = $conn->prepare($sql_string);
//            $stmt->setFetchMode(PDO::FETCH_ASSOC);
//            $players = $stmt->fetchAll();
//            $counter = 0;
//            while($row = $stmt->fetch()) {
//                $this->players[$counter++] = $row["tag"];
//            }
//
//            return $players;
//        }
//        catch(PDOException $e) {
//            return $e->getMessage();
//        }
//    }
}
