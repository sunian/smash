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
        function signin() {

        }

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
            newPassword.keyup(function () {
                newConfirm.val("")
                if (newPassword.val().length > 7) {
                    newConfirm.removeAttr("disabled");
                } else {
                    newConfirm.attr("disabled", "disabled");
                }
            })
                .blur(function () {
                    if (newPassword.val().length < 8)
                        Helper.makeToast($("div.body"), $("#newPassword"),
                            "Password must be at least 8 characters long!");
                });
            newConfirm.keyup(function () {
                if (newPassword.val() === newConfirm.val()) {
                    newName.removeAttr("disabled");
                    newEmail.removeAttr("disabled");
                } else {
                    newName.attr("disabled", "disabled");
                    newEmail.attr("disabled", "disabled");
                }
            })
                .blur(function () {
                    if (newPassword.val() !== newConfirm.val())
                        Helper.makeToast($("div.body"), $("#newConfirm"),
                            "The passwords you entered don't match!");
                });
            newEmail.keyup(function () {
                if (Helper.validateEmail(newEmail.val())) {
                    btnSignUp.removeAttr("disabled");
                } else {
                    btnSignUp.attr("disabled", "disabled");
                }
            })
                .blur(function () {
                    if (!Helper.validateEmail(newEmail.val()))
                        Helper.makeToast($("div.body"), $("#newEmail"),
                            "You must enter a valid email!");
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
        <input id="newPassword" placeholder="password" type="password" disabled><br>
        <input id="newConfirm" placeholder="confirm password" type="password" disabled><br>
        <input id="newName" placeholder="full name" disabled><br>
        <input id="newEmail" placeholder="email address" disabled><br>
        <input id="btnSignUp" type="button" value="Sign Up" onclick="signup();" disabled>
    </div>
</div>
    <div id="sign_in" class="container">
        <p>Sign up:</p>
        <input id="username" placeholder="username"><br>
        <input id="password" placeholder="password" type="password" disabled><br>
        <input id="btnSignIn" type="button" value="Sign Up" onclick="signin();" disabled>
    </div>
</div>
<?php
?>
</body>
</html>