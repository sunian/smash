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
    echo json_encode(clean($stmt->fetchAll()));
    $stmt->closeCursor();
    ?></div>
<div id="div_version_titles" style="display: none;"><?php
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select distinct title, abbreviation from version order by title");
    $stmt->execute();
    $names = clean($stmt->fetchAll(PDO::FETCH_ASSOC));
    $stmt->closeCursor();

    echo json_encode($names);
    ?></div>
<script type="text/javascript">
    function getVersionTitles() {
        var titles_abbrevs = JSON.parse($("#div_version_titles").text());
        var titles = [];
        for(var i=0; i<titles_abbrevs.length; i++) {
            var row = titles_abbrevs[i];
            titles[i] = row["title"];
        }
        return titles;
    }
    function getAbbreviationForTitle(title) {
        var titles_abbrevs = JSON.parse($("#div_version_titles").text());
        for(var i=0; i<titles_abbrevs.length; i++) {
            var row = titles_abbrevs[i];
            if(row["title"]==title) {
                return row["abbreviation"];
            }
        }
        return "";
    }
</script>
<script type="text/javascript">
    function createVersionSelector(includeBlank) {
        var select_version = document.createElement("select");
        var versions = JSON.parse($("#div_versions").text());
        if (includeBlank) select_version.options[0] = new Option("Version", -1);
        for (var i in versions) {
            select_version.options[select_version.options.length] = new Option(versions[i].name, versions[i].id);
        }
        return select_version;
    }
</script>
