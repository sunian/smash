<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/Character.php');
if (strlen($json_input) > 0) {
    $character = new Character($json_input);
    print_r($character);
    exit();
}
?>

<html>
<head>
    <title>Characters</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var select_universe;
        $(function () {
            select_universe = createUniverseSelector();
            select_universe.id = "select_universe";
            $("#newChar")[0].appendChild(select_universe);
        });
        function createChar() {
            var newChar = {};
            newChar.name = $("#newName").val();
            newChar.nick = $("#newNick").val();
            if (newChar.nick.length == 0) newChar.nick = undefined;
            newChar.universe = $(select_universe).val();
//            console.log(JSON.stringify(newChar));
            $.post("characters.php", JSON.stringify(newChar), function (data) {
                    console.log("success");
                    console.log(data);
                }
                , "json"
            );
        }
    </script>
</head>
<body>
<table>
    <tr>
        <th>Name</th>
        <th>Nickname</th>
        <th>Universe</th>
    </tr>
    <?php
    $conn = DbUtil::connect();
    $stmt = $conn->stmt_init();
    $stmt->prepare("SELECT i.name, i.nickname, u.name
                    FROM character_identity AS i NATURAL JOIN universe AS u
                    ORDER BY u.universe_id, i.nickname, i.name");
    $stmt->execute();
    $stmt->bind_result($name, $nick, $universe);
    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<tr>";
        echo "<td>", $name, "</td>";
        echo "<td>", $nick, "</td>";
        echo "<td>", $universe, "</td>";
        echo "</tr>";
    }
    ?>
    <tr>
        <td><input id="newName" placeholder="New name"></td>
        <td><input id="newNick" placeholder="New Nickname"></td>
        <td id="newChar"></td>
    </tr>
</table>
<input type="button" value="Create New&#x00A;Character" onclick="createChar();">
<?php include('libs/universes.php'); ?>
</body>
</html>