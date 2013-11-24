<div id="div_characters" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/18/13
     * Time: 2:55 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select identity_id as id, name from character_identity order by universe_id");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode($stmt->fetchAll());
    $stmt->closeCursor();
    ?></div>
<script type="text/javascript">
    function createCharacterSelector(includeBlank) {
        var select_character = document.createElement("select");
        var characters = JSON.parse($("#div_characters").text());
        if (includeBlank) select_character.options[0] = new Option("Character", -1);
        for (var i in characters) {
            select_character.options[select_character.options.length] = new Option(characters[i].name, characters[i].id);
        }
        return select_character;
    }
</script>
