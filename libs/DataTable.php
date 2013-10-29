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
    public $sqlQuery = null;
    public $renderData = null; //$printData(rows)

    function __construct($id, $headers)
    {
        $this->id = $id;
        $this->headers = $headers;
    }

    public function render()
    {
        echo
        "<div class='body'>
            <div id='fixedHeader' class='fixedHeader'>
                <table class='solid'>
                    <tr>", $this->printHeaders(true), "</tr>
                </table>
            </div>
            <div id='scrollContainer' class='scrollable'>
                <table id='table$this->id' class='no-break'>
                    <tr>", $this->printHeaders(false), "</tr>
                    <tfoot>
                    <tr>
                        <td><input id='_newName' placeholder='New name' disabled='disabled'></td>
                        <td><input id='_newNick' placeholder='New nickname' disabled='disabled'></td>
                        <td id='_newChar'></td>
                    </tr>
                    </tfoot>
                    <tbody class='sortable'>", $this->printData($this->renderData), "</tbody>
                </table>
            </div>";
    }

    private function printHeaders($clickable)
    {
        foreach ($this->headers as $text) {
            echo "<th ", $clickable ? "class='clickable'" : "", ">$text</th>";
        }
    }

    private function printData($callback)
    {
//        $conn = DbUtil::connect();
//        $stmt = $conn->prepare($this->sqlQuery);
//        $stmt->execute();
//        $stmt->setFetchMode(PDO::FETCH_BOTH);
        print_r($callback);
        $callback();
//        while ($row = $stmt->fetch()) {
//            $callback();
//        }
    }
}