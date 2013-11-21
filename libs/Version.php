<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/19/13
 * Time: 5:34 PM
 */

require_once('DbUtil.php');
require_once('JSONObject.php');

class Version extends JSONObject{
    var $title;
    var $release_date = null;
    var $version_number = null;
    var $abbreviation = null;

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
}