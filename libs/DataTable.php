<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 10/29/13
 * Time: 12:39 PM
 */

class DataTable {
    public $id = null;
    public $headers = null;
    public $canInsert = false;

    public function render(){
        echo '<div class="body">
    <div id="fixedHeader" class="fixedHeader">
        <table class="solid">
            <tr>
                <th class="clickable">Name</th>
                <th class="clickable">Nickname</th>
                <th class="clickable">Universe</th>
            </tr>
        </table>
    </div>
    <div id="scrollContainer" class="scrollable">
        <table id="tableChars">
            <tr>
                <th>Name</th>
                <th>Nickname</th>
                <th>Universe</th>
            </tr>
            <tfoot>
            <tr>
                <td><input id="_newName" placeholder="New name" disabled="disabled"></td>
                <td><input id="_newNick" placeholder="New nickname" disabled="disabled"></td>
                <td id="_newChar"></td>
            </tr>
            </tfoot>
            <tbody class="sortable">';
    }
}