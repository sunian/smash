<div id="div_universes" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/12/13
     * Time: 3:28 PM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->stmt_init();
    if ($stmt->prepare("select universe_id, name from universe")) {
        $stmt->execute();
        $stmt->bind_result($id, $name);
        $universes = array();
        $count = 0;
        while ($stmt->fetch()) {
            $universes[$count++] = array(
                "id" => $id,
                "name" => $name
            );
        }
        echo json_encode($universes);
    }
    $stmt->close();
    ?></div>
<script type="text/javascript">
    function createUniverseSelector() {
        var select_universe = document.createElement("select");
        var universes = JSON.parse($("#div_universes").text());
        for (var i in universes) {
            select_universe.options[i] = new Option(universes[i].name, universes[i].id);
        }
        return select_universe;
    }
</script>
