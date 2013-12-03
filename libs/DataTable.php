<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 10/29/13
 * Time: 12:39 PM
 */
require_once('DbUtil.php');
require_once('browser.php');

class TableColumn
{
    public $id;
    public $header;
    public $inputType;
    public $placeholder;

    function __construct($header, $id, $inputType, $placeholder)
    {
        $this->header = $header;
        $this->id = $id;
        $this->inputType = $inputType;
        $this->placeholder = $placeholder;
    }

}

class DataTable
{
    public $id = null;
    public $columns = null;
    public $canInsert = false;
    public $sqlQuery = null;
    public $sqlParams = null;
    public $renderData; //$printData(rows)
    public $hasFooter = false;

    function __construct($id, $columns)
    {
        $this->id = $id;
        $this->columns = $columns;
        foreach ($columns as $column) {
            if ($GLOBALS['authenticatedUser'] == null) $column->inputType = "none";
            if (strcmp($column->inputType, "none") != 0) {
                $this->hasFooter = true;
                break;
            }
        }
    }

    function setData($sqlQuery, $sqlParams){
        $this->sqlQuery = $sqlQuery;
        $this->sqlParams = $sqlParams;
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
                    <tr>", $this->printFooters(false), "</tr>
                    </tfoot>
                    <tbody class='sortable'>", $this->printData(), "</tbody>
                </table>
            </div>", $this->hasFooter ? $this->printFooter() :
            "<a href='javascript:void(0);' class='btnPlus' onclick='create$this->id();'></a>",
        "</div>";
    }

    private function printFooter() {
        echo "<div id='fixedFooter' class='fixedFooter'>
                <table class='layout'>
                    <tr class='layout'>
                        <td style='vertical-align: bottom;' class='layout'>
                            <table class='content solid'>
                                <tfoot>
                                <tr>", $this->printFooters(true), "</tr>
                                </tfoot>
                            </table>
                        </td>
                        <td class='layout' style='padding-left: 20px; display: none'>"
                            ,($GLOBALS['authenticatedUser'] == null) ? "" : "<a href='javascript:void(0);' class='btnPlus' onclick='create$this->id();'></a>",
                        "</td>
                    </tr>
                </table>
            </div>";
    }

    private function printHeaders($clickable)
    {
        foreach ($this->columns as $col) {
            echo "<th ", $clickable ? "class='clickable'" : "", ">$col->header</th>";
        }
    }

    private function printFooters($enabled)
    {
        foreach ($this->columns as $col) {
            switch ($col->inputType) {
                case "input":
                    echo "<td>";
                    if ($enabled) {
                        echo "<input id='$col->id' placeholder='$col->placeholder'>";
                    } else {
                        echo "<input disabled='disabled'>";
                    }
                    echo "</td>";
                    break;
                case "select":
                    if ($enabled) {
                        echo "<td id='$col->id' class='select'>$col->placeholder</td>";
                    } else {
                        echo "<td id='_$col->id' class='select disabled'>$col->placeholder</td>";
                    }
                    break;
                case "date":
                    echo "<td>";
                    if ($enabled) {
                        echo "<input id='$col->id' placeholder='$col->placeholder' class='date'>";
                    } else {
                        echo "<input disabled='disabled'>";
                    }
                    echo "</td>";
                    break;
                default:
                    echo "<td></td>";
                    break;
            }
        }
    }

    private function printData()
    {
        try {
            $conn = DbUtil::connect();
            $stmt = $conn->prepare($this->sqlQuery);
            $stmt->execute($this->sqlParams);
            $stmt->setFetchMode(PDO::FETCH_BOTH);
            $callback = $this->renderData;
            while ($row = $stmt->fetch()) {
                $callback(clean($row));
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function prettyDate($date){
        $leDate = explode("-", $date);
        if (sizeof($leDate) != 3) return "";
        return date("M jS, Y", mktime(0, 0, 0, $leDate[1], $leDate[2], $leDate[0]));
    }
}