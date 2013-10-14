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
        if ($json) $this->set(json_decode($json, true));
    }

    public function set($data) {
        foreach ($data AS $key => $value) {
            if (is_array($value)) {
                $sub = new JSONObject;
                $sub->set($value);
                $value = $sub;
            }
            $this->{$key} = $value;
        }
    }
}

$json_input = file_get_contents("php://input");