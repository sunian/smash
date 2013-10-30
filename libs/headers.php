<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/13/13
 * Time: 11:13 PM
 * To change this template use File | Settings | File Templates.
 */

?>
<meta http-equiv="X-UA-Compatible" content="IE=9"/>
<meta name="viewport" content="user-scalable=false"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://datejs.googlecode.com/files/date.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/eggplant/jquery-ui.min.css" rel="stylesheet"
      type="text/css"/>
<link href="styles/default.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
    var btnAdd;
    $(function () {
        $("input.date").datepicker({
            onSelect: function (dateText, inst) {
                this.focus();
            }});
    });

    function setupTables(tableID) {
        btnAdd = $("a.btnPlus").parent();
        alignCellWidths($.makeArray($("table#table" + tableID + " tr th")),
            $.makeArray($("div#fixedHeader table tr th")));
        alignCellWidths($.makeArray($("table#table" + tableID + " tfoot tr td")),
            $.makeArray($("div#fixedFooter table.content tr td")));
        $("div#fixedHeader table tr th").each(function (i, elem) {
            $(elem).attr("dir", "1").css("cursor", "pointer");
            $(elem).click(function () {
                var dir = $(elem).attr("dir") * 1;
                sortTable($("table#table" + tableID + " tbody.sortable"), i, dir);
                $(elem).attr("dir", "" + (dir * -1));
            })
        });

        $("div#scrollContainer").css("maxHeight", "10%").animate({
            maxHeight: "85%"
        }, 666, function () {
            // Animation complete.
            $("div#fixedFooter table.content, div#fixedHeader table")
                .css("width", $("table#table" + tableID).css("width"));
        });
    }

    function uploadObj(newObj){
        $.ajax({
            type: "POST",
            data: JSON.stringify(newObj),
//                dataType: "json",
            success: function (data, textStatus, jqXHR) {
                console.log(data);
                if (data.length > 0) {
                    alert(data);
                } else {
                    location.reload();
                }
            }

        });
    }

    function alignCellWidths(rowSource, rowTarget) {
        for (var i in rowTarget) {
            $(rowTarget[i]).css("width", $(rowSource[i]).css("width"));
            console.log($(rowSource[i]).css("width") + " " + $(rowTarget[i]).css("width"));
        }
    }

    function sortTable(table, col, dir) {
        var rows = $.makeArray(table.find("tr"));
        rows.sort(function (a, b) {
            return $(a.cells[col]).text().localeCompare($(b.cells[col]).text()) * dir;
        });
        table.append(rows);
    }
</script>