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

class Character extends JSONObject {
    public $id = null;
    public $name = null;
    public $nick = null;
    public $universe = null;
    public $version = null;
    public $weight = null;
    public $falling_speed = null;
    public $air_speed = null;

    public function create(){
        $conn = DbUtil::connect();
        $stmt = $conn->stmt_init();
        $stmt->prepare("");
    }
}