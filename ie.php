
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 1:30 PM
 * To change this template use File | Settings | File Templates.
 */

?>
<html>
<head>
    <title>You're using IE</title>
<script type="text/javascript">
    function ignoreWarnings(){

    }
</script>
</head>
<body>
<h1>Error!</h1>
<h2>Error code: 34042</br>Error type: User</h2>
<h3>Error description: User is currently using Internet Explorer.</h3>
<?php
if ($_GET["ignore"]){
    echo "You have been notified of this error and will not be warned again.";
} else {
    echo "<input type='button' value='Do not show this in the future' onclick='ignoreWarnings'/>";
}
?>
</body>
</html>