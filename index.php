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
            var select_universe = document.createElement("select");
            select_universe.options.empty();
            var universes = $("#div_universes")[0].text().split("]|[");
            for (var i in universes){
                select_universe.options[i] = new Option(universes[i], universes[i]);
            }
            document.body.add(select_universe);
        });
    </script>
</head>
<body>
<?php


?>
<?php include('universes.php'); ?>
</body>
</html>