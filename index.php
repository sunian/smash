<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/12/13
 * Time: 3:28 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DbUtil.php');

?>

<html>
<head>
    <title>SMASH!</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        $(function () {
            var select_universe = createUniverseSelector();
            select_universe.id = "select_universe";
            document.body.appendChild(select_universe);
        });
    </script>
</head>
<body>
<?php
$_GET['enclose'] = 1;
include('libs/versions.php');
?>
<?php include('libs/universes.php'); ?>
</body>
</html>