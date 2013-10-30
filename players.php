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
    <title>Players</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newDate, newVenue;
        var newName, newRegion;
        var btnAdd;
        $(function () {
            newName = $("#newName");
            newDate = $("#newDate");
            newVenue = $("#newVenue");
            newRegion = $("#newRegion");
            newName.keyup(function () {
                btnAdd.css("display", newName.val().length > 0 ? "inline-block" : "none")
            });

            Helper.setupTables("Players");

            newName.focus();
        });

        function createPlayers() {
            var newObj = {};
            newObj.name = newName.val();
            newObj.venue = $("#newVenue").val();
            if (newObj.name.length == 0) {
                alert("Please enter a name to create a new tournament.");
                newName.focus();
                return;
            }
            if (newObj.venue.length == 0) newObj.venue = undefined;
            newObj.date = newDate.val();
            newObj.region = 0;
            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php
include('libs/navheader.php');
$table = new DataTable("Players", array(
    new TableColumn("Tag", "newTag", "input", "New tag"),
    new TableColumn("Region", "newRegion", "input", "New region"),
    new TableColumn("Name", "newName", "input", "New name")
));
$table->sqlQuery = "SELECT p.name, p.tag, r.name as region
                    FROM player as p INNER JOIN region as r on p.region_id = r.region_id
                    ORDER BY r.name, p.tag, p.name";
$table->renderData = function ($row) {
    echo "<tr>";
    echo "<td>", $row["tag"], "</td>";
    echo "<td>", $row["region"], "</td>";
    echo "<td>", $row["name"], "</td>";
    echo "</tr>";
};
$table->render();
?>

</body>
</html>