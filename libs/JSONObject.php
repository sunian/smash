<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 4:23 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('browser.php');
require_once('JSONList.php');

class JSONObject {
    public function __construct($json = false) {
        if ($json) $this->set(json_decode($json, true));
    }

    public function set($data) {
        foreach ($data AS $key => $value) {
            if (is_array($value)) {
                $fieldType = $this->getFieldType($key);
                try {
                    $refClass = new ReflectionClass(str_replace("[]","", $fieldType));
                } catch (Exception $ex) {
                }

                if (endsWith($fieldType, "[]")){
                    if (sizeof($value) > 0 && is_array($value[0]) && $refClass != null) {
                        foreach ($value AS $i => $val) {
                            $sub = $refClass->newInstance();
                            $sub->set($val);
                            $value[$i] = $sub;
                        }
                    }
                } else if ($refClass != null) {
                    $sub = $refClass->newInstance();
                    $sub->set($value);
                    $value = $sub;
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
$input_type = "";
if ($json_input[0] == '_') {
    $input_type .= $json_input[1];
    $json_input = substr($json_input, 2, strlen($json_input) - 2);
}