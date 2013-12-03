<div id="div_roles" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/18/13
     * Time: 2:55 AM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select role_id as id, name from user_role order by role_id");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode(clean($stmt->fetchAll()));
    $stmt->closeCursor();
    ?></div>
<script type="text/javascript">
    function createRoleSelector(includeBlank) {
        var select_role = document.createElement("select");
        var roles = JSON.parse($("#div_roles").text());
        if (includeBlank) select_role.options[0] = new Option("User Type", -1);
        for (var i in roles) {
            select_role.options[select_role.options.length] = new Option(roles[i].name, roles[i].id);
        }
        return select_role;
    }
</script>
