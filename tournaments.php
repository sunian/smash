<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/18/13
 * Time: 2:53 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DataTable.php');
//require_once('libs/Character.php');
if (strlen($json_input) > 0) {
    exit();
}
?>

<html>
<head>
    <title>Tournaments</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newDate;
        var newName;
        var btnAdd;
        $(function () {
            newName = $("#newName");
            newDate = $("#newDate");
            btnAdd = $("a.btnPlus");
            alignCellWidths($.makeArray($("table#tableTournys tr th")),
                $.makeArray($("div#fixedHeader table tr th")));
            alignCellWidths($.makeArray($("table#tableTournys tfoot tr td")),
                $.makeArray($("div#fixedFooter table.content tr td")));
            $("div#fixedHeader table tr th").each(function (i, elem) {
                $(elem).attr("dir", "1").css("cursor", "pointer");
                $(elem).click(function () {
                    var dir = $(elem).attr("dir") * 1;
                    sortTable($("table#tableTournys tbody.sortable"), i, dir);
                    $(elem).attr("dir", "" + (dir * -1));
                })
            });
            newName.keyup(function () {
                btnAdd.css("display", newName.val().length > 0 ? "inline-block" : "none")
            }).autocomplete({
                    source: getVenues()
                });
            newName.focus();
            $("div#scrollContainer").css("maxHeight", "10%").animate({
                maxHeight: "85%"
            }, 666, function () {
                // Animation complete.
                $("div#fixedFooter table.content, div#fixedHeader table")
                    .css("width", $("table#tableTournys").css("width"));
            });
        });

        function createTournys() {
            var newTourny = {};
            newTourny.name = newName.val();
            newTourny.venue = $("#newVenue").val();
            if (newTourny.name.length == 0) {
                alert("Please enter a name to create a new tournament.");
                newName.focus();
                return;
            }
            if (newTourny.venue.length == 0) newTourny.venue = undefined;
            newTourny.date = newDate.val();
            newTourny.region = 0;
            $.ajax({
                type: "POST",
                data: JSON.stringify(newTourny),
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
    </script>
</head>
<body>
<?php

include('libs/navheader.php');

$table = new DataTable("Tournys", array(
    new TableColumn("Name", "newName", "input", "New name"),
    new TableColumn("Venue", "newVenue", "input", "New Venue"),
    new TableColumn("Date", "newDate", "date", "New date")
));
$table->setData("SELECT name, venue, date
                FROM tournament
                ORDER BY tournament_id, date, name", null);
$table->renderData = function ($row) {
    $leDate = explode("-", $row["date"]);
    echo "<tr>";
    echo "<td>", $row["name"], "</td>";
    echo "<td>", $row["venue"], "</td>";
    echo "<td>", date("M jS, Y", mktime(0, 0, 0, $leDate[1], $leDate[2], $leDate[0])), "</td>";
    echo "</tr>";
};
$table->render();

include('libs/venues.php');
include('libs/regions.php');

?>

</body>
</html>