<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/18/13
 * Time: 2:53 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
//require_once('libs/Character.php');
if (strlen($json_input) > 0) {
    exit();
}
?>

<html>
<head>
    <title>Videos</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
    </script>
    <form action="welcome.php" method="post">
        Video URL: <input type="text" name="URL"><br>
        <input type="submit">
    </form>
</head>
<body>
<?php include('libs/navheader.php'); ?>

</body>
</html>