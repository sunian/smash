<div id="div_techniques" style="display: none;">
    <?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Student
     * Date: 11/23/13
     * Time: 3:30 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');
    require_once('Techniques.php');

    $conn = DbUtil::connect();
    echo "weee";
    $stmt = $conn->prepare("select technique_id as id, name from technique order by name");
    $stmt->execute();
    echo "executed";
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode($stmt->fetchAll());
    $stmt->closeCursor();
    ?>
</div>
<script type="text/javascript">
    function createTechniqueSelector() {
        var select_technique = document.createElement("select");
        var techniques = JSON.parse($("#div_techniques").text());
        for (var i in techniques) {
            select_technique.options[i] = new Option(techniques[i].name, techniques[i].id);
        }
        return select_technique;
    }
</script>
