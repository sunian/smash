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
        var types = this.type.split(" ");
        this.mySearchBox.parent.append(document.createElement('br'));
        for (var i in types) {
            var typeData = types[i].split(":");
            switch (typeData[0]) {
                case "input":
                    var newInput = $(document.createElement('input'));
                    newInput.attr("id", this.id + "-" + i);
                    newInput.attr("placeholder", this.placeholder);
                    this.mySearchBox.parent.append(newInput);
                    break;
                case "select":
                    var newSelect = $(window[typeData[1]].call(this, true));
                    newSelect.attr("id", this.id + "-" + i);
                    this.mySearchBox.parent.append(newSelect);
                    break;
            }
        }
    };

    this.renderInsert = function () {
        this.mySearchBox.parent.append(document.createElement('br'));
        var newAnchor = $(document.createElement('a'));
        var newImage = $(document.createElement('img'));
        newImage.attr("src", 'images/plus.png');
        newImage.addClass("smallBtn");
        newAnchor.append(newImage);
        newAnchor.append(this.placeholder);
        newAnchor.attr("href", "javascript:void(0)");
        newAnchor.bind("click", [this], function (e) {
            e.data[0].renderNew();
        })
        this.mySearchBox.parent.append(newAnchor);
    }
}