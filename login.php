<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 12/1/13
 * Time: 12:55 AM
 */
require_once('libs/browser.php');
require_once('libs/User.php');

setcookie("user_token", "", time()-3600);
$authenticatedUser = null;

if (strlen($json_input) > 0) {
    $user = new User($json_input);
    if (strcmp($input_type, "u") == 0) {//user signed up
        $error = $user->createUser();
        if ($error) echo $error;
    } else if (strcmp($input_type, "i") == 0) {//user signed in
        echo $user->getAccessToken();
    } else if (strcmp($input_type, "c") == 0) {//get login count
        echo $user->getLoginCount();
    }
    exit();
}
?>

<html>
<head>
    <title>Authenticate yo self!</title>
    <?php include('libs/headers.php');
    ?>
    <script type="text/javascript">
        function signin() {
            var newObj = {};
            newObj.username = username.val();
            newObj.password = password.val();
            var newUser = new User(newObj);
            newUser.authenticateWithServer(function () {
//                console.log(JSON.stringify(this));
                Helper.postJSON(this, "i", function (data, textStatus, jqXHR) {
                    if (data.length > 0) {
                        console.log(data);
                        $.cookie('user_name', newUser.username);
                        $.cookie('user_token', data, { expires: 14 });
                        window.location.href='index.php';
                    } else {
                        alert("Incorrect username or password!");
                    }
                });
            });
        }

        function signup() {
            var newObj = {};
            newObj.username = newUsername.val();
            newObj.password = newPassword.val();
            newObj.name = newName.val();
            newObj.email = newEmail.val();
            var newUser = new User(newObj);
            newUser.generateServerPassword(function () {
//                console.log(JSON.stringify(this));
                Helper.postJSON(this, "u", function (data, textStatus, jqXHR) {
                    if (data.length > 0) {
                        alert(data);
                        console.log(data);
                    } else {
                        username.val(newUsername.val());
                        password.val(newPassword.val());
                        signin();
                    }
                });
            });
        }

        var newUsername, newPassword, newConfirm, newName, newEmail, btnSignUp;
        var username, password, btnSignIn;
        function init() {
            newUsername = $("input#newUsername");
            newPassword = $("input#newPassword");
            newConfirm = $("input#newConfirm");
            newName = $("input#newName");
            newEmail = $("input#newEmail");
            btnSignUp = $("input#btnSignUp");
            username = $("input#username");
            password = $("input#password");
            btnSignIn = $("input#btnSignIn");
            newUsername.keyup(function () {
                if (newUsername.val().length > 3) {
                    newPassword.removeAttr("disabled");
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
                }
            })
                .blur(function () {
                    if (!Helper.validateEmail(newEmail.val()))
                        Helper.makeToast($("div.body"), $("#newEmail"),
                            "You must enter a valid email!");
                });
            password.keyup(function () {
                if (username.val().length > 3 && password.val().length > 7) {
                    btnSignIn.removeAttr("disabled");
                }
            });
        }
    </script>
</head>
<body>

<?php

include('libs/navheader.php');

?>
<div id="sign_up" class="body container">
    <p>Sign up</p>
    <input id="newUsername" placeholder="username"><br>
    <input id="newPassword" placeholder="password" type="password" disabled><br>
    <input id="newConfirm" placeholder="confirm password" type="password" disabled><br>
    <input id="newName" placeholder="full name" disabled><br>
    <input id="newEmail" placeholder="email address" disabled><br>
    <select id="newType"></select><br>
    <input id="btnSignUp" type="button" value="Sign Up" onclick="signup();" disabled>
</div>
<div id="sign_in" class="body container">
    <p>Sign in</p>
    <input id="username" placeholder="username"><br>
    <input id="password" placeholder="password" type="password"><br>
    <input id="btnSignIn" type="button" value="Sign In" onclick="signin();" disabled>
</div>
<?php
?>
</body>
</html>