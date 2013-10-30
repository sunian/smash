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

class Tournament extends JSONObject
{
    public $id = null;
    public $name = null;
    public $date = null;
    public $venue = null;
    public $region = null;

    public function createTournament()
    {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT tournament_id FROM tournament WHERE name = :name AND date = :date" .
                " AND region_id = :region " .
                (is_null($this->venue) ? " AND venue is null" : " AND venue = :venue");
            $params = array("name" => $this->name, "date" => $this->date, "region" => $this->region);
            if (!is_null($this->venue)) $params["venue"] = $this->venue;
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                return "That tournament already exists!";
            }
            $stmt->closeCursor();
            $params["venue"] = $this->venue;
            $sql_string = "INSERT INTO technique (name, date, venue, region_id) VALUES (:name, :date, :venue, :region)";
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}