<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 4:23 PM
 * To change this template use File | Settings | File Templates.
 */

class JSONObject {
    public function __construct($json = false) {
        echo "jsonData=", $json;
        if ($json) $this->set(json_decode($json, true));
    }

    public function set($data) {
        foreach ($data AS $key => $value) {
            if (is_object($value)) {
                $refClass = new ReflectionClass($this->getFieldType($key));
                $sub = $refClass->newInstance();
                $sub->set($value);
                $value = $sub;
            } else if (is_array($value)) {
                if (sizeof($value) > 0 && is_object($value[0])) {
                    $refClass = new ReflectionClass($this->getFieldType($key));
                    echo "refClass=", $refClass, "  ";
                    foreach ($value AS $i => $val) {
                        $sub = $refClass->newInstance();
                        $sub->set($val);
                        $value[$i] = $sub;
                    }
                }
            }
            $this->{$key} = $value;
        }
    }

    public function getFieldType($fieldName) {
        return "JSONObject";
    }
}

$json_input = file_get_contents("php://input");