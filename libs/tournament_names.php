<div id="div_names" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/12/13
     * Time: 3:28 PM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select distinct name from tournament order by name");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $names = clean($stmt->fetchAll(PDO::FETCH_COLUMN, 0));
    $stmt->closeCursor();

    echo json_encode($names);
    ?></div>
<script type="text/javascript">
    function getTournyNames() {
        var names = JSON.parse($("#div_names").text());
        return names;
    }
</script>
