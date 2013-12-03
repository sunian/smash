/**
 * Created by Sun on 11/30/13.
 */

function User(obj) {
    var self = this;
    this.username = null;
    this.name = null;
    this.email = null;
    this.login_count = 0;
    this.role = 0;
    for (var prop in obj) this[prop] = obj[prop];
    var plaintext = this.password;
    this.password = null;

    function setPassword(h) {
        self.password = h;
        if (passwordCallback != null) passwordCallback.call(self);
    }

    function setServerPassword(h) {
        setPassword(h.substring(29));
    }

    var passwordCallback;

    this.generateServerPassword = function (callback) {
        if (!this.verifyPassword()) return;
        if (!hasher.ready()) return;
        if (this.username == null) return;
        passwordCallback = callback;
        hasher.hashpw(plaintext + md5(this.username.toLowerCase()), "$2a$31$KenCombo.RedLikeRoses.", setServerPassword, null);
    };

    this.getLoginCount = function (callback) {
        Helper.postJSON(this, "c", function (data, textStatus, jqXHR) {
            if (data.length > 0) {
                self.login_count = data;
                callback.call(self);
            } else {
                alert("Incorrect username or password!");
            }
        });
    }

    this.authenticateWithServer = function (callback) {
        this.getLoginCount(function () {
            this.generateServerPassword(function () {
                passwordCallback = callback;
                hasher.hashpw(md5(this.login_count) + this.password, hasher.gensalt(8), setPassword, null);
            });
        })
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