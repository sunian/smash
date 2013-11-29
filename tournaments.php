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
require_once('libs/Tournament.php');
if (strlen($json_input) > 0) {
    $tournament = new Tournament($json_input);
    $error = $tournament->createTournament();
    if ($error) echo $error;
    exit();
}
?>

<html>
<head>
    <title>Tournaments</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newDate, newVenue;
        var newName;

        function init() {
            newName = $("#newName");
            newDate = $("#newDate");
            newVenue = $("#newVenue");
            newName.keyup(function () {
                Helper.displayBtnAdd(newName.val().length > 0);
            });
            newName.autocomplete({
                source: getTournyNames()
            });
            newVenue.autocomplete({
                source: getVenues()
            });

            Helper.setupDataTable("Tournys");

            newName.focus();
        }

        function createTournys() {
            var newObj = {};
            newObj.name = newName.val();
            newObj.venue = newVenue.val();
            newObj.date = newDate.val();
            if (newObj.name.length == 0) {
                alert("Please enter a name to create a new tournament.");
                newName.focus();
                return;
            }
            if (newObj.venue.length == 0) newObj.venue = undefined;
            if (newObj.date.length == 0) {
                newObj.date = undefined;
            } else {
                newObj.date = Date.parse(newObj.date).toString("yyyy-MM-dd");
            }
            newObj.region = $("#newRegion").val();
            Helper.uploadObj(newObj);
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
    new TableColumn("Region", "newRegion", "select", "createRegionSelector")
));
$table->setData("SELECT t.name, t.venue, t.date, r.name AS region
                FROM tournament AS t INNER JOIN region AS r ON t.region_id = r.region_id
                ORDER BY t.region_id, t.date, t.name", null);
$table->renderData = function ($row) {
    $leDate = $row["date"];
    echo "<tr>";
    echo "<td>", $row["name"], "</td>";
    echo "<td raw='$leDate'>", DataTable::prettyDate($leDate), "</td>";
    echo "<td>", $row["venue"], "</td>";
    echo "<td>", $row["region"], "</td>";
    echo "</tr>";
};
$table->render();

include('libs/venues.php');
include('libs/tournament_names.php');
include('libs/regions.php');

?>

</body>
</html>