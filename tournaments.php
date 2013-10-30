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
        var newDate, newVenue;
        var newName, newRegion;
        var selectRegion;
        $(function () {
            newName = $("#newName");
            newDate = $("#newDate");
            newVenue = $("#newVenue");
            newRegion = $("#newRegion");
            selectRegion = createRegionSelector();
            selectRegion.id = "_selectRegion";
            selectRegion.disabled = true;
            $("#_newRegion")[0].appendChild(selectRegion);
            selectRegion = createRegionSelector();
            selectRegion.id = "selectRegion";
            newRegion[0].appendChild(select_universe);
            newName.keyup(function () {
                btnAdd.css("display", newName.val().length > 0 ? "inline-block" : "none")
            });
            newVenue.autocomplete({
                source: getVenues()
            });

            setupTables("Tournys");

            newName.focus();
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
    new TableColumn("Date", "newDate", "date", "New date"),
    new TableColumn("Venue", "newVenue", "input", "New venue"),
    new TableColumn("Region", "newRegion", "select", "New region")
));
$table->setData("SELECT t.name, t.venue, t.date, r.name AS region
                FROM tournament AS t INNER JOIN region AS r ON t.region_id = r.region_id
                ORDER BY t.region_id, t.date, t.name", null);
$table->renderData = function ($row) {
    $leDate = explode("-", $row["date"]);
    echo "<tr>";
    echo "<td>", $row["name"], "</td>";
    echo "<td>", date("M jS, Y", mktime(0, 0, 0, $leDate[1], $leDate[2], $leDate[0])), "</td>";
    echo "<td>", $row["venue"], "</td>";
    echo "<td>", $row["region"], "</td>";
    echo "</tr>";
};
$table->render();

include('libs/venues.php');
include('libs/regions.php');

?>

</body>
</html>