<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 2:37 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('DbUtil.php');
require_once('JSONObject.php');

class Player extends JSONObject
{
    public $id = null;
    public $name = null;
    public $tag = null;
    public $region = null;

    public function createPlayer()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT player_id FROM player WHERE region = :region " .
                (is_null($this->tag) ? " AND tag is null" : " AND tag = :tag") .
                (is_null($this->name) ? " AND name is null" : " AND name = :name");
            $params = array("region" => $this->region);
            if (!is_null($this->tag)) $params["tag"] = $this->tag;
            if (!is_null($this->name)) $params["name"] = $this->name;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                return "That player already exists!";
            }
            $stmt->closeCursor();
            $params["tag"] = $this->tag;
            $params["name"] = $this->name;
            $sql_string = "INSERT INTO player (name, tag, region) VALUES (:name, :tag, :region)";
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}