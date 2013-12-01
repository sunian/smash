/**
 * Created by Sun on 11/30/13.
 */

function User(username, password) {
    var self = this;
    this.username = username;
    this.password = null;
    var plaintext = password;
    this.name = null;
    this.email = null;

    function setPassword(h) {
        self.password = h;
    }

    this.generateServerPassword = function () {
        if (!this.verifyPassword()) return;
        if (!hasher.ready()) return;
        hasher.hashpw(plaintext, hasher.gensalt(8), setPassword, null);
    };

    this.verifyPassword = function () {
        return true;
    };

}

User.checkUsernameIsAvailable = function (callback) {
    return true;
}