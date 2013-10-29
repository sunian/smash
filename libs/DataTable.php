<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 10/29/13
 * Time: 12:39 PM
 */

class DataTable
{
    public $id = null;
    public $headers = null;
    public $canInsert = false;

    function __construct($id, $headers)
    {
        $this->id = $id;
        $this->headers = $headers;
    }

    public function render()
    {
        echo '<div class="body">
    <div id="fixedHeader" class="fixedHeader">
        <table class="solid">
            <tr>';
        foreach ($this->headers as $text) {
            echo "<th class='clickable'>$text</th>";
        }
        echo "</tr>
        </table>
    </div>
    <div id='scrollContainer' class='scrollable'>
        <table id='table$this->id'>
            <tr>
                <th>Name</th>
                <th>Nickname</th>
                <th>Universe</th>
            </tr>
            <tfoot>
            <tr>
                <td><input id='_newName' placeholder='New name' disabled='disabled'></td>
                <td><input id='_newNick' placeholder='New nickname' disabled='disabled'></td>
                <td id='_newChar'></td>
            </tr>
            </tfoot>
            <tbody class='sortable'>";
    }
}