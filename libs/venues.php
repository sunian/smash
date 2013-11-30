<div id="div_venues" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/12/13
     * Time: 3:28 PM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select distinct venue from tournament order by venue");
    $stmt->execute();
    $venues = clean($stmt->fetchAll(PDO::FETCH_COLUMN, 0));
    $stmt->closeCursor();

    echo json_encode($venues);
    ?></div>
<script type="text/javascript">
    function getVenues() {
        var venues = JSON.parse($("#div_venues").text());
        return venues;
    }
</script>
