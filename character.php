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
        var select_universe;
        $(function () {
            select_universe = createUniverseSelector();
            select_universe.id = "_select_universe";
            select_universe.disabled = true;
            $("#_newChar")[0].appendChild(select_universe);
            select_universe = createUniverseSelector();
            select_universe.id = "select_universe";
            $("#newChar")[0].appendChild(select_universe);
            alignCellWidths($.makeArray($("table#tableChars tr th")),
                $.makeArray($("div#fixedHeader table tr th")));
            alignCellWidths($.makeArray($("table#tableChars tfoot tr td")),
                $.makeArray($("div#fixedFooter table tr td")));
            $("div#fixedFooter").css("bottom", $(document.body).css("margin") + "px");
            $("#newName").focus();
        });

        function createChar() {
            var newChar = {};
            newChar.name = $("#newName").val();
            newChar.nick = $("#newNick").val();
            if (newChar.name.length == 0) {
                alert("Please enter a name to create a new character.");
                $("#newName").focus();
                return;
            }
            if (newChar.nick.length == 0) newChar.nick = undefined;
            newChar.universe = $(select_universe).val();
//            console.log(JSON.stringify(newChar));
            $.ajax({
                type: "POST",
                data: JSON.stringify(newChar),
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
    </script>
</head>
<body>
<div id="fixedHeader" class="fixedHeader">
    <table>
        <tr>
            <th>Name</th>
            <th>Nickname</th>
            <th>Universe</th>
        </tr>
    </table>
</div>
<table id="tableChars" style="text-align: center">
    <tr>
        <th>Name</th>
        <th>Nickname</th>
        <th>Universe</th>
    </tr>
    <tfoot>
    <tr>
        <td><input id="_newName" placeholder="New name" disabled="disabled"></td>
        <td><input id="_newNick" placeholder="New Nickname" disabled="disabled"></td>
        <td id="_newChar"></td>
    </tr>
    </tfoot>
    <?php
    $conn = DbUtil::connect();
    $stmt = $conn->prepare("SELECT i.name, i.nickname, u.name as universe
                    FROM character_identity AS i INNER JOIN universe AS u on i.universe_id = u.universe_id
                    ORDER BY u.universe_id, i.nickname, i.name");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_BOTH);
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<tr>";
        echo "<td>", $row["name"], "</td>";
        echo "<td>", $row["nickname"], "</td>";
        echo "<td>", $row["universe"], "</td>";
        echo "</tr>";
    }
    ?>

</table>

<div id="fixedFooter" class="fixedFooter">
    <table class="layout">
        <tr class="layout">
            <td style="vertical-align: bottom;" class="layout">
                <table>
                    <tr>
                        <td><input id="newName" placeholder="New name"></td>
                        <td><input id="newNick" placeholder="New Nickname"></td>
                        <td id="newChar"></td>
                    </tr>
                </table>
            </td>
            <td class="layout">
                <input type="button" value="Create New&#x00A;Character" onclick="createChar();">
            </td>
        </tr>
    </table>

</div>
<?php include('libs/universes.php'); ?>
</body>
</html>