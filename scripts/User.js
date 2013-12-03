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
        self.password = h.substring(29);
        if (passwordCallback != null) passwordCallback.call(self);
    }

    var passwordCallback;

    this.generateServerPassword = function (callback) {
        if (!this.verifyPassword()) return;
        if (!hasher.ready()) return;
        if (this.username == null) return;
        passwordCallback = callback;
        hasher.hashpw(plaintext + md5(this.username.toLowerCase()), "$2a$31$KenCombo.RedLikeRoses.", setPassword, null);
    };

    this.getLoginCount = function (callback) {

    }

    this.authenticateWithServer = function (callback) {
        this.generateServerPassword(function () {
            passwordCallback = callback;
            hasher.hashpw(md5(this.login_count) + this.password, hasher.gensalt(8), setPassword, null);
        });
    }

    this.verifyPassword = function () {
        return true;
    };

}

User.checkUsernameIsAvailable = function (username, callback) {
    if (username == null || username.length < 4) return false;
    Helper.postJSON(username, "u", callback);
    return true;
}