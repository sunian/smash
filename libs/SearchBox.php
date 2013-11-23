<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 10/29/13
 * Time: 12:39 PM
 */
require_once('JSONObject.php');
require_once('DbUtil.php');

class QueryField extends JSONObject
{
    public $id;
    public $placeholder;
    public $type;
    public $count;//1 or + or *
    public $values;

    public static function nu($id, $placeholder, $type, $count) {
        $instance = new self();
        $instance->id = $id;
        $instance->placeholder = $placeholder;
        $instance->type = $type;
        $instance->count = $count;
        return $instance;
    }

}

class SearchBox extends JSONObject
{
    public $title = null;
    public $fields = null;
    public $renderData; //$printData(rows)

    public static function nu($title, $fields) {
        $instance = new self();
        $instance->title = $title;
        $instance->fields = $fields;
        return $instance;
    }

    public function getFieldType($fieldName) {
        switch ($fieldName) {
            case "fields": return "QueryField[]";
        }
        return parent::getFieldType($fieldName);
    }

    public function render()
    {
        echo "<div class='search-box'>", json_encode($this),
        "</div>";
    }

}

?>
<script type="text/javascript" src="scripts/SearchBox.js"></script>