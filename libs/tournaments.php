<div id="div_tournaments" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/18/13
     * Time: 2:55 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("SELECT tournament_id as id, name
                FROM tournament
                ORDER BY name");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode(clean($stmt->fetchAll()));
    $stmt->closeCursor();
    ?></div>
<script type="text/javascript">
    function createTournamentSelector(includeBlank) {
        var select_tournament = document.createElement("select");
        var tournaments = JSON.parse($("#div_tournaments").text());
        if (includeBlank !== false) select_tournament.options[0] = new Option("Tournament", -1);
        for (var i in tournaments) {
            select_tournament.options[select_tournament.options.length] = new Option(tournaments[i].name, tournaments[i].id);
        }
        return select_tournament;
    }
</script>
