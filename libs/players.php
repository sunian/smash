<div id="div_players" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/18/13
     * Time: 2:55 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select player_id as id, COALESCE(tag, name) as name from player order by player_id");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode($stmt->fetchAll());
    $stmt->closeCursor();
    ?></div>
<script type="text/javascript">
    function createPlayerSelector() {
        var select_player = document.createElement("select");
        var players = JSON.parse($("#div_players").text());
        for (var i in players) {
            select_player.options[i] = new Option(players[i].name, players[i].id);
        }
        return select_player;
    }
</script>
