<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 10/29/13
 * Time: 12:39 PM
 */
require_once('DbUtil.php');

class QueryField
{
    public $id;
    public $placeholder;

    function __construct($id, $placeholder)
    {
        $this->id = $id;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        echo "<p>$this->id : $this->placeholder</p>";
    }
}

class SearchBox
{
    public $id = null;
    public $fields = null;
    public $renderData; //$printData(rows)

    function __construct($id, $fields)
    {
        $this->id = $id;
        $this->fields = $fields;
    }

    public function render()
    {
        echo "<div class='search-box'>", $this->renderFields(),
        "</div>";
    }

    private function renderFields() {
        foreach ($this->fields as $field) {
            $field->render();
        }

    }

}