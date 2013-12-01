/**
 * Created by Sun on 11/30/13.
 */

function User(username, password) {
    this.username = username;
    this.password = null;
    var plaintext = password;
    this.name = null;
    this.email = null;

    function setPassword(h) {
        this.password = h;
    }

    this.generateServerPassword = function () {
        if (!this.verifyPassword()) return;
        if (!hasher.ready()) return;
        hasher.hashpw(plaintext, hasher.gensalt(8), setPassword, null);
    };

    this.verifyPassword = function () {

    };

}

User.checkUsernameIsAvailable = function (callback) {
    return true;
}