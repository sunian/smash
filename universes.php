<div id="div_universes" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/12/13
     * Time: 3:28 PM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $stmt = $conn->stmt_init();
    if ($stmt->prepare("select name from universe")) {
        $stmt->execute();
        $stmt->bind_result($col1);
        $first = true;
        while ($stmt->fetch()) {
            if (!$first) echo "]|[";
            echo $col1;
            $first = false;
        }
    }
    $stmt->close();
    ?></div>
<script type="text/javascript">
    function createUniverseSelector() {
        var select_universe = document.createElement("select");
        var universes = $("#div_universes").text().split("]|[");
        for (var i in universes) {
            select_universe.options[i] = new Option(universes[i], universes[i]);
        }
        return select_universe;
    }
</script>
