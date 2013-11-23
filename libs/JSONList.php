<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 10/29/13
 * Time: 12:39 PM
 */
require_once('JSONObject.php');

class JSONList extends JSONObject
{
    public $list = null;
    public $elemType = null;

    public static function nu($elemType, $obj = false) {
        $instance = new self();
        $instance->elemType = $elemType;
        if ($obj) {
            $data["list"] = $obj;
//            echo "_ ";
//            print_r($data);
//            echo " _";
            $instance->set($data);
        }
        return $instance->list;
    }

    public function getFieldType($fieldName) {
        switch ($fieldName) {
            case "list": return elemType . "[]";
        }
        return parent::getFieldType($fieldName);
    }

}
