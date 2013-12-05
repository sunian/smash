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

class Technique extends JSONObject
{
    public $technique_id = null;
    public $name = null;
    public $abbreviation = null;
    public $player = null;

    public function createTechnique()
    {
        if ($GLOBALS['authenticatedUser'] == null) return;
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT technique_id FROM technique WHERE name = :name " .
                (is_null($this->abbreviation) ? " AND abbreviation is null" : " AND abbreviation = :abbrev");
            $params = array("name" => $this->name);
            if (!is_null($this->abbreviation)) $params["abbrev"] = $this->abbreviation;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                return "That technique already exists!";
            }
            $stmt->closeCursor();
            $params["abbrev"] = $this->abbreviation;
            $sql_string = "INSERT INTO technique (name, abbreviation) VALUES (:name, :abbrev)";
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}