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
    public $height = null;
    public $falling_speed = null;
    public $air_speed = null;

    public $identity_id = null;
    public $version_id = null;
    public $falling_speed_rank = null;
    public $air_speed_rank = null;

    public function createIdentity()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT identity_id FROM character_identity WHERE name = :name AND universe_id = :universe" .
                (is_null($this->nick) ? " AND nickname is null" : " AND nickname = :nick");
            $params = array("name" => $this->name, "universe" => $this->universe);
            if (!is_null($this->nick)) $params["nick"] = $this->nick;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                return "That character already exists!";
            }
            $stmt->closeCursor();
            $params["nick"] = $this->nick;
            $sql_string = "INSERT INTO character_identity (name, universe_id, nickname) VALUES (:name, :universe, :nick)";
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function createCharacter() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT identity_id FROM `character` WHERE identity_id = :identity_id AND version_id = :version_id";
            $params = array("version_id" => $this->version_id, "identity_id" => $this->identity_id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                return "That character already exists!";
            }
            $stmt->closeCursor();
            $sql_string = "INSERT INTO `character` (identity_id, version_id" . $this->weight?", weight":"" . $this->height?", height":"" .
                $this->falling_speed_rank?", falling_speed_rank":"" . $this->air_speed_rank?", air_speed_rank)":")" .
                "VALUES (:identity_id, :version_id" . $this->weight?", :weight":"" . $this->height?", :height":"" .
                $this->falling_speed_rank?", :falling_speed_rank":"" . $this->air_speed_rank?", :air_speed_rank)":")";
            $stmt = $conn->prepare($sql_string);
            $params = array("identity_id"=>$this->identity_id, "version_id"=>$this->version_id, "weight"=>$this->weight,
                "height"=>$this->height, "falling_speed_rank"=>$this->falling_speed_rank, "air_speed_rank"=>$this->air_speed_rank);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}