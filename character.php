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

//$character = new Character($json_input);
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
        function createChar(){
            var newChar = {};
            newChar.name = $("#newName").val();
            newChar.nick = $("#newNick").val();
            if (newChar.nick.length == 0) newChar.nick = undefined;
            newChar.universe = $(select_universe).val();
            console.log(JSON.stringify(newChar));
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
                    FROM character_identity as i natural join universe as u
                    order by u.universe_id, i.nickname, i.name");
    $stmt->execute();
    $stmt->bind_result($name, $nick, $universe);
    while ($stmt->fetch()){
        echo "<tr>";
        echo "<tr>";
        echo "<td>", $name, "</td>";
        echo "<td>", $nick, "</td>";
        echo "<td>", $universe, "</td>";
        echo "</tr>";
    }
    ?>
</table>
<p>Create new character identity:</p>
<div id="newChar">
    <input id="newName" placeholder="Name"><input id="newNick" placeholder="Nickname">
    &nbsp;&nbsp;&nbsp;Universe:
</div>
<input type="button" value="Create" onclick="createChar();">
<?php include('libs/universes.php'); ?>
</body>
</html>