<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DataTable.php');
require_once('libs/Character.php');
if (strlen($json_input) > 0) {
    $character = new Character($json_input);
    $error = $character->createIdentity();
    if ($error) echo $error;
    exit();
}
?>

<html>
<head>
    <title>Characters</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newName;

        function init() {
            newName = $("#newName");
            newName.keyup( function () {
                Helper.displayBtnAdd(newName.val().length > 0);
            });

            Helper.setupDataTable("Chars");

            newName.focus();
        }

        function createChars() {
            var newObj = {};
            newObj.name = newName.val();
            newObj.nick = $("#newNick").val();
            if (newObj.name.length == 0) {
                alert("Please enter a name to create a new character.");
                newName.focus();
                return;
            }
            if (newObj.nick.length == 0) newObj.nick = undefined;
            newObj.universe = $("#newChar").val();
            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php

include('libs/navheader.php');

$table = new DataTable("Chars", array(
    new TableColumn("Name", "newName", "input", "New name"),
    new TableColumn("Nickname", "newNick", "input", "New nickname"),
    new TableColumn("Universe", "newChar", "select", "createUniverseSelector")
));
$table->setData("SELECT i.name, i.nickname, u.name as universe
                    FROM character_identity AS i INNER JOIN universe AS u on i.universe_id = u.universe_id
                    ORDER BY u.universe_id, i.nickname, i.name", null);
$table->renderData = function ($row) {
    echo "<tr>";
    echo "<td>", $row["name"], "</td>";
    echo "<td>", $row["nickname"], "</td>";
    echo "<td>", $row["universe"], "</td>";
    echo "</tr>";
};
$table->render();

include('libs/universes.php');
?>

</body>
</html>