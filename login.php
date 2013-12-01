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

        }
        var newUsername, newPassword, newConfirm, newName, newEmail, btnSignUp;
        function init() {
            newUsername = $("input#newUsername");
            newPassword = $("input#newPassword");
            newConfirm = $("input#newConfirm");
            newName = $("input#newName");
            newEmail = $("input#newEmail");
            btnSignUp = $("input#btnSignUp");
            newUsername.keyup(function () {
                if (newUsername.val().length > 3) {
                    newPassword.removeAttr("disabled");
                } else {
                    newPassword.val("").attr("disabled", "disabled");
                }
            })
                .blur(function () {
                    if (newUsername.val().length < 4)
                        Helper.makeToast($("div.body"), $("#newUsername"),
                            "Username must be at least 4 characters long!");
                });
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
        <input id="newPassword" placeholder="password" disabled><br>
        <input id="newConfirm" placeholder="confirm password" disabled><br>
        <input id="newName" placeholder="full name" disabled><br>
        <input id="newEmail" placeholder="email address" disabled><br>
        <input id="btnSignUp" type="button" value="Sign Up" onclick="signup();" disabled>
    </div>
</div>
<?php
?>
</body>
</html>