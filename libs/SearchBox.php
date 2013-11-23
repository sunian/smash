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

    function __construct($id, $placeholder)
    {
        $this->id = $id;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        echo "<br><input id='$this->id' placeholder='$this->placeholder'/>";
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
<script type="text/javascript">
    var hello = $("body");
</script>