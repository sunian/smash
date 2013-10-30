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
    </script>
</head>
<body>
<?php
include('libs/navheader.php');
$table = new DataTable("Tournaments", array(
    new TableColumn("Name", "newName", "input", "New name"),
    new TableColumn("Venue", "newVenue", "input", "New venue"),
    new TableColumn("Date", "newDate", "input", "New date")
));
$table->sqlQuery = "SELECT name, venue, date
                    FROM tournament
                    ORDER BY tournament_id, date, name";
$table->renderData = function ($row) {
    echo "<tr>";
    echo "<td>", $row["name"], "</td>";
    echo "<td>", $row["venue"], "</td>";
    echo "<td>", $row["date"], "</td>";
    echo "</tr>";
};
$table->render();
?>

</body>
</html>