<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/18/13
 * Time: 2:53 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/Character.php');
if (strlen($json_input) > 0) {
    exit();
}
?>

<html>
<head>
    <title>Tournaments</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
    </script>
</head>
<body>
<?php include('libs/navheader.php'); ?>

<?php include('libs/regions.php'); ?>
</body>
</html>