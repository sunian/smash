<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/19/13
 * Time: 5:34 PM
 */

require_once('DbUtil.php');

class Version {
    var $name;
    var $release_date;

    public function createIdentity() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT  FROM video WHERE url = :url";
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
}