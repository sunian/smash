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
        $conn = DbUtil::connect();
        $stmt = $conn->stmt_init();
        $sql_string = "SELECT identity_id FROM character_identity WHERE name = ? AND universe_id = ?" .
            (($this->nick) ? " AND nickname = ?" : " AND nickname is null")
            ;
        $stmt->prepare($sql_string);
        $stmt->bind_param("sis", $this->name, $this->universe, $this->nick);
        $stmt->execute();
        $stmt->bind_result($identity_id);
        if ($stmt->fetch()) {
            echo $identity_id;
            return "character identity already exists!";
        }
        $sql_string = ($this->nick) ? "INSERT INTO character_identity (name, universe_id, nickname) VALUES (?,?,?)" :
            "INSERT INTO character_names (name, universe_id) VALUES (?,?)";
        $stmt->prepare($sql_string);
        $stmt->execute();
        $stmt->close();
        return false;
    }
}