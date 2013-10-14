<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/12/13
 * Time: 3:28 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('DbUtil.php');

?>

<html>
<head>
    <title>SMASH!</title>
    <?php include('headers.php'); ?>
    <script type="text/javascript">
        $(function (){
            var select_universe = createUniverseSelector();
            select_universe.id = "select_universe";
            document.body.appendChild(select_universe);
        });
    </script>
</head>
<body>
<?php


?>
<?php include('universes.php'); ?>
</body>
</html>