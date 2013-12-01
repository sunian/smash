/**
 * Created by Sun on 11/30/13.
 */

function User(obj) {
    var self = this;
    this.username = null;
    this.name = null;
    this.email = null;
    this.login_count = 0;
    for (var prop in obj) this[prop] = obj[prop];
    var plaintext = this.password;
    this.password = null;

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