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
require_once('libs/Player.php');
if (strlen($json_input) > 0) {
    $technique = new Player($json_input);
    $error = $technique->createPlayer();
    if ($error) echo $error;
    exit();
}
?>

<html>
<head>
    <title>Players</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newTag, newName, newRegion;
        var selectRegion;
        $(function () {
            newName = $("#newName");
            newTag = $("#newTag");
            newRegion = $("#newRegion");
            selectRegion = createRegionSelector();
            selectRegion.id = "_selectRegion";
            selectRegion.disabled = true;
            $("#_newRegion")[0].appendChild(selectRegion);
            selectRegion = createRegionSelector();
            selectRegion.id = "selectRegion";
            newRegion[0].appendChild(selectRegion);
            newTag.keyup(function () {
                Helper.displayBtnAdd(newName.val().length > 0 || newTag.val().length > 0);
            });
            newName.keyup(function () {
                Helper.displayBtnAdd(newName.val().length > 0 || newTag.val().length > 0);
            });

            Helper.setupTables("Players");

            newTag.focus();
        });

        function createPlayers() {
            var newObj = {};
            newObj.name = newName.val();
            newObj.tag = newTag.val();
            if (newObj.name.length == 0 && newObj.tag.length == 0) {
                alert("Please enter a tag and/or a name to create a new player.");
                newTag.focus();
                return;
            }
            if (newObj.tag.length == 0) newObj.tag = undefined;
            if (newObj.name.length == 0) newObj.name = undefined;
            newObj.region = $(selectRegion).val();
            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php
include('libs/navheader.php');
$table = new DataTable("Players", array(
    new TableColumn("Tag", "newTag", "input", "New tag"),
    new TableColumn("Region", "newRegion", "select", "New region"),
    new TableColumn("Name", "newName", "input", "New name")
));
$table->sqlQuery = "SELECT p.name, p.tag, r.name as region
                    FROM player as p INNER JOIN region as r on p.region_id = r.region_id
                    ORDER BY p.player_id";
$table->renderData = function ($row) {
    echo "<tr>";
    echo "<td>", $row["tag"], "</td>";
    echo "<td>", $row["region"], "</td>";
    echo "<td>", $row["name"], "</td>";
    echo "</tr>";
};
$table->render();

include('libs/regions.php');
?>

</body>
</html>