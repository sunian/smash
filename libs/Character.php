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
    public $weight = null;
    public $height = null;
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
            // Check if the character already exists
            $conn = DbUtil::connect();
            $sql_string = "SELECT identity_id FROM `character` WHERE identity_id = :identity_id AND version_id = :version_id";
            $params = array("version_id" => $this->version_id, "identity_id" => $this->identity_id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                return "That character already exists!";
            }
            $stmt->closeCursor();

            // Add the character
            $sql_string = "INSERT INTO `character` (identity_id, version_id" . ($this->weight?", weight":"") . ($this->height?", height":"")
                . ($this->falling_speed_rank?", falling_speed_rank":"") . ($this->air_speed_rank?", air_speed_rank)":")") .
                "VALUES (:identity_id, :version_id" . ($this->weight?", :weight":"") . ($this->height?", :height":"") .
                ($this->falling_speed_rank?", :falling_speed_rank":"") . ($this->air_speed_rank?", :air_speed_rank)":")");
            $stmt = $conn->prepare($sql_string);
            $params = array("identity_id"=>$this->identity_id, "version_id"=>$this->version_id);
            if($this->weight) $params["weight"]=$this->weight;
            if($this->height) $params["height"]=$this->height;
            if($this->falling_speed_rank) $params["falling_speed_rank"]=$this->falling_speed_rank;
            if($this->air_speed_rank) $params["air_speed_rank"]=$this->air_speed_rank;
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
            $sql_string = "SELECT c.character_id AS id, nickname AS nick, c.identity_id, ci.name, universe.name AS universe, weight,
                height, air_speed_rank, falling_speed_rank, version_id FROM `character` AS c INNER JOIN character_identity AS ci ON
                c.identity_id = ci.identity_id INNER JOIN universe ON universe.universe_id = ci.universe_id WHERE c.character_id = :character_id";
            $params = array("character_id"=>$this->id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->version_id = $row["version_id"];
            $this->air_speed_rank = $row["air_speed_rank"];
            $this->falling_speed_rank = $row["falling_speed_rank"];
            $this->weight = $row["weight"];
            $this->height = $row["height"];
            $this->nick = $row["nick"];
            $this->identity_id = $row["identity_id"];
            $this->name = $row["name"];
            $this->universe = $row["universe"];
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }
}