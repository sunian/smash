<div id="div_versions" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/18/13
     * Time: 2:55 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("SELECT version_id as id,
                concat(abbreviation, coalesce(concat(' ', version_number),'')) AS name
                FROM version
                ORDER BY name");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode($stmt->fetchAll());
    $stmt->closeCursor();
    ?></div>
<script type="text/javascript">
    function createVersionSelector(includeBlank) {
        var select_version = document.createElement("select");
        var versions = JSON.parse($("#div_versions").text());
        if (includeBlank) select_character.options[0] = new Option("Character", -1);
        for (var i in versions) {
            select_version.options[select_version.options.length] = new Option(versions[i].name, versions[i].id);
        }
        return select_version;
    }
</script>
