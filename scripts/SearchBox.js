/**
 * Created by Sun on 10/30/13.
 */
var SearchBoxList = [];
function setupSearchBox() {
    $("div.search-box").each(function (i, elem) {
        SearchBoxList.push(new SearchBox($(elem)));
    });
}

function SearchBox(parent) {
    this.parent = parent;
    var obj = JSON.parse(this.parent.html());
    for (var prop in obj) this[prop] = obj[prop];
    for (var i in this.fields) this.fields[i] = new QueryField(this, this.fields[i]);

    this.render = function () {
        this.parent.html(this.title);
        for (var i in this.fields) {
            this.fields[i].render();
        }
    };

    this.render();
}

function QueryField(myParent, obj) {
    this.mySearchBox = myParent;
    for (var prop in obj) this[prop] = obj[prop];

    this.render = function () {
        switch (this.count) {
            case "1":
                this.renderNew();
                break;
            case "+":
                this.renderNew();
                this.renderInsert();
                break;
            case "*":
                this.renderInsert();
                break;
        }

    };

    this.renderNew = function () {
        switch (this.type) {
            case "input":
                this.mySearchBox.parent.appendChild(document.createElement('br'));
                var newInput = $(document.createElement('input'));
                newInput.attr("id", this.id);
                newInput.attr("placeholder", this.placeholder);
                this.mySearchBox.parent.appendChild(newInput);
                break;
            case "select":
                break;
        }
    };

    this.renderInsert = function () {
        return "<br><a><img src='images/plus.png' class='smallBtn'>" + this.placeholder + "</a>";
    }
}