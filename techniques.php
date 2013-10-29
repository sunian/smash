<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/Technique.php');
if (strlen($json_input) > 0) {
    $technique = new Technique($json_input);
    $error = $technique->createTechnique();
    if ($error) echo $error;
    exit();
}
?>

<html>
<head>
    <title>Techniques</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newName;
        var btnAdd;
        $(function () {
            newName = $("#newName");
            btnAdd = $("a.btnPlus");
            alignCellWidths($.makeArray($("table#tableTechs tr th")),
                $.makeArray($("div#fixedHeader table tr th")));
            alignCellWidths($.makeArray($("table#tableTechs tfoot tr td")),
                $.makeArray($("div#fixedFooter table.content tr td")));
            $("div#fixedHeader table tr th").each(function (i, elem) {
                $(elem).attr("dir", "1").css("cursor", "pointer");
                $(elem).click(function () {
                    var dir = $(elem).attr("dir") * 1;
                    sortTable($("table#tableTechs tbody.sortable"), i, dir);
                    $(elem).attr("dir", "" + (dir * -1));
                })
            });
            newName.keyup(function () {
                btnAdd.css("display", newName.val().length > 0 ? "inline-block" : "none")
            });
            newName.focus();
            $("div#scrollContainer").css("maxHeight", "10%").animate({
                maxHeight: "85%"
            }, 666, function () {
                // Animation complete.
                $("div#fixedFooter table.content, div#fixedHeader table")
                    .css("width", $("table#tableTechs").css("width"));
            });
        });

        function createTech() {
            var newTech = {};
            newTech.name = newName.val();
            newTech.abbrev = $("#newAbbrev").val();
            if (newTech.name.length == 0) {
                alert("Please enter a name to create a new technique.");
                newName.focus();
                return;
            }
            if (newTech.abbrev.length == 0) newTech.abbrev = undefined;
//            console.log(JSON.stringify(newChar));
            $.ajax({
                type: "POST",
                data: JSON.stringify(newTech),
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
<?php include('libs/navheader.php'); ?>
<div class="body">
    <div id="fixedHeader" class="fixedHeader">
        <table class="solid">
            <tr>
                <th class="clickable">Name</th>
                <th class="clickable">Abbreviation</th>
                <th class="clickable">Abbreviation</th>
            </tr>
        </table>
    </div>
    <div id="scrollContainer" class="scrollable">
        <table id="tableTechs">
            <tr>
                <th>Name</th>
                <th>Abbreviation</th>
                <th>Abbreviation</th>
            </tr>
            <tfoot>
            <tr>
                <td><input placeholder="New name" disabled="disabled"></td>
                <td><input placeholder="New abbrev" disabled="disabled"></td>
                <td><select id="select_universe"><option value="1">The Legend of Zelda</option><option value="2">Mario</option><option value="3">Yoshi</option><option value="4">Donkey Kong</option><option value="5">Metroid</option><option value="6">Kirby</option><option value="7">Star Fox</option><option value="8">Pokemon</option></select></td>
            </tr>
            </tfoot>
            <tbody class="sortable">
            <?php
            $conn = DbUtil::connect();
            $stmt = $conn->prepare("SELECT t.name, t.abbreviation, t.technique_id
                    FROM technique AS t
                    ORDER BY t.name, t.abbreviation");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_BOTH);
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td><a href='techniques.php?t=", $row["technique_id"], "'>", $row["abbreviation"], "</a></td>";
                echo "<td>", $row["abbreviation"], "</td>";
                echo "<td>", $row["abbreviation"], "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div id="fixedFooter" class="fixedFooter">
        <table class="layout">
            <tr class="layout">
                <td style="vertical-align: bottom;" class="layout">
                    <table class="content solid">
                        <tfoot>
                        <tr>
                            <td><input id="newName" placeholder="New name"></td>
                            <td><input id="newAbbrev" placeholder="New abbrev"></td>
                            <td><select id="select_universe"><option value="1">The Legend of Zelda</option><option value="2">Mario</option><option value="3">Yoshi</option><option value="4">Donkey Kong</option><option value="5">Metroid</option><option value="6">Kirby</option><option value="7">Star Fox</option><option value="8">Pokemon</option></select></td>
                        </tr>
                        </tfoot>
                    </table>
                </td>
                <td class="layout" style="padding-left: 20px;">
                    <a href="javascript:void(0);" style="display: none;" class="btnPlus" onclick="createTech();"></a>
                    <!--                <input type="button" value="Create New&#x00A;Character" onclick="createTech();">-->
                </td>
            </tr>
        </table>

    </div>
</div>
</body>
</html>