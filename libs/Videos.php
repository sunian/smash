<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/15/13
 * Time: 11:55 PM
 */
require_once('DbUtil.php');
require_once('JSONObject.php');
require_once('Technique.php');

class Video extends JSONObject {
    public $id = null;
    public $title = null;
    public $url = null;

    public function createIdentity()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT video_id FROM video WHERE title = :title AND url = :url";
            $params = array("title" => $this->title, "url" => $this->url);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                // Error message!!
                return "That video already exists!";
            }
            $stmt->closeCursor();
            $sql_string = "INSERT INTO video (title, url, date_added) VALUES (:title, :url, NOW())";
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getTechniques() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT technique_id, name, abbreviation FROM video NATURAL JOIN video_player NATURAL JOIN technique_usage NATURAL JOIN technique" .
                " WHERE title = :title AND url = :url";
            $params = array("title" => $this->title, "url" => $this->url);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $techniques = $stmt->fetchAll();
            $jsonArray = array();
            $counter = 0;
            foreach($techniques as $i => $technique) {
                $tech = new Technique();
                $tech->id = $technique["technique_id"];
                $tech->name = $technique["name"];
                $tech->abbrev = $technique["abbreviation"];
                $jsonArray[$counter++] = $tech;
            }

            return $jsonArray;
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }
}
