/**
 * Created by Sun on 10/30/13.
 */
var SearchBoxList = [];
function setupSearchBox() {
    $("div.search-box").each(function (i, elem) {
        SearchBoxList[i] = new SearchBox($(elem).html());
        $(elem).html(SearchBoxList[i].render());
    });
}

function SearchBox(json) {
    var obj = JSON.parse(json);
    for (var prop in obj) this[prop] = obj[prop];
    for (var i in this.fields) this.fields[i] = new QueryField(this.fields[i]);

    this.render = function () {
        var output = this.title;
        for (var i in this.fields) {
            output += this.fields[i].render();
        }
        return output;
    };
}

function QueryField(obj) {
    for (var prop in obj) this[prop] = obj[prop];

    this.render = function () {
        switch (this.count) {
            case "1":
                return this.renderNew();
                break;
            case "+":
                return this.renderNew();
                break;
            case "*":
                break;
        }

    };

    this.renderNew = function () {
        switch (this.type) {
            case "input":
                return "<br><input id='" + this.id + "' placeholder='" + this.placeholder + "'/>";
            case "select":
                break;
        }
    };
}