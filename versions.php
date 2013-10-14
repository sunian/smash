<div id="div_universes" style="display: none;"><?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Sun
     * Date: 10/12/13
     * Time: 3:28 PM
     * To change this template use File | Settings | File Templates.
     */
    require_once('DbUtil.php');

    $stmt = $conn->stmt_init();
    if ($stmt->prepare("select version_id,
                concat(abbreviation, coalesce(concat(' ', version_number),'')) as name
                from version
                order by name")
    ) {
        $stmt->execute();
        $stmt->bind_result($version_id, $name);
        while ($stmt->fetch()) {
            echo "<p><input type='checkbox' />",$name,"</p>";
        }
    }
    $stmt->close();
    ?></div>
<!--<script type="text/javascript">-->
<!--</script>-->
