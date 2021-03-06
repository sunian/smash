<div id="div_techniques" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Student
     * Date: 11/23/13
     * Time: 3:30 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select technique_id as id, name from technique order by name");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode(clean($stmt->fetchAll()));
    $stmt->closeCursor();
    ?>
</div>
<script type="text/javascript">
    function createTechniqueSelector(includeBlank) {
        var select_technique = document.createElement("select");
        var techniques = JSON.parse($("#div_techniques").text());
        if (includeBlank) select_technique.options[0] = new Option("Technique", -1);
        for (var i in techniques) {
            select_technique.options[select_technique.options.length] = new Option(techniques[i].name, techniques[i].id);
            //select_technique.options[i] = new Option(techniques[i].name, techniques[i].id);
        }
        return select_technique;
    }
</script>
