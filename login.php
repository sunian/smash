<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 12/1/13
 * Time: 12:55 AM
 */

?>

<html>
<head>
    <title>Authenticate yo self!</title>
    <?php include('libs/headers.php');
    ?>
    <script type="text/javascript">
        function signup() {
            var notify = $(document.createElement('p'));
            notify.text("Username must be at least 4 characters long!");
            notify.addClass("triangle-border top");
            $("div#sign_up").append(notify);
        }

        function init() {

        }
    </script>
</head>
<body>

<?php

include('libs/navheader.php');

?>

<div class='body'>
    <div id="sign_up" class="container">
        <p>Sign up:</p>
        <input id="newUsername" placeholder="username"><br>
        <input id="newPassword" placeholder="password"><br>
        <input id="newConfirm" placeholder="confirm password"><br>
        <input id="newName" placeholder="full name"><br>
        <input id="newEmail" placeholder="email address"><br>
        <input id="btnSignUp" type="button" value="Sign Up" onclick="signup();">
    </div>
</div>
<?php
?>
</body>
</html>