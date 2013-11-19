<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 10/29/13
 * Time: 12:39 PM
 */
require_once('JSONObject.php');
require_once('DbUtil.php');

class QueryField
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
        echo "<input id='$this->id' placeholder='$this->placeholder'/>";
    }
}

class SearchBox extends JSONObject
{
    public $title = null;
    public $fields = null;
    public $renderData; //$printData(rows)

    function __construct($title, $fields)
    {
        $this->title = $title;
        $this->fields = $fields;
    }

    public function render()
    {
        echo "<div class='search-box'>$this->title", $this->renderFields(),
        "</div>";
    }

    private function renderFields() {
        foreach ($this->fields as $field) {
            $field->render();
        }

    }

}