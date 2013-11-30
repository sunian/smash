<div id="div_regions" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/18/13
     * Time: 2:55 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select region_id as id, name from region order by region_id");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode(clean($stmt->fetchAll()));
    $stmt->closeCursor();
    ?></div>
<script type="text/javascript">
    function createRegionSelector() {
        var select_region = document.createElement("select");
        var regions = JSON.parse($("#div_regions").text());
        for (var i in regions) {
            select_region.options[i] = new Option(regions[i].name, regions[i].id);
        }
        return select_region;
    }
</script>
