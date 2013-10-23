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

class Character extends JSONObject
{
    public $id = null;
    public $name = null;
    public $nick = null;
    public $universe = null;
    public $version = null;
    public $weight = null;
    public $falling_speed = null;
    public $air_speed = null;

    public function createIdentity()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT identity_id FROM character_identity WHERE name = :name AND universe_id = :universe" .
                (is_null($this->nick) ? " AND nickname is null" : " AND nickname = :venue");
            $params = array("name" => $this->name, "universe" => $this->universe);
            if (!is_null($this->nick)) $params["venue"] = $this->nick;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                return "That character already exists!";
            }
            $stmt->closeCursor();
            $params["venue"] = $this->nick;
            $sql_string = "INSERT INTO character_identity (name, universe_id, nickname) VALUES (:name, :universe, :venue)";
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}