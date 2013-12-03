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
            this.parent.append(this.fields[i].myDiv);
        }
        this.parent.append(document.createElement('br'));
        var btnGo = $(document.createElement('input'));
        btnGo.attr("type", "button");
        btnGo.attr("value", "Filter");
        btnGo.bind("click", [this], function (e) {
            e.data[0].clickedGo();
        });
        this.parent.append(btnGo);
    };

    this.clickedGo = function () {
        for (var i in this.fields) {
            this.fields[i].populateValues();
        }
        Helper.makeQuery(this);
    }

    this.render();
}

function QueryField(myParent, obj) {
    for (var prop in obj) this[prop] = obj[prop];

    this._SearchBox = myParent;
    this.myDiv = $(document.createElement('div'));
    if (this.count != "1") this.myDiv.addClass("query-field")
    this.values = [];

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

    this.renderNew = function (anchor) {
        var types = this.type.split(" ");
        var newField = $(document.createElement('div'));
        newField.addClass("field");

        for (var i in types) {
            var typeData = types[i].split(":");
            switch (typeData[0]) {
                case "input":
                    var newInput = $(document.createElement('input'));
                    newInput.attr("id", this.id + "-" + i);
                    newInput.attr("placeholder", this.placeholder);
                    newField.append(newInput);
                    break;
                case "select":
                    var newSelect = $(window[typeData[1]].call(this, true));
                    newSelect.attr("id", this.id + "-" + i);
                    newField.append(newSelect);
                    break;
                default :
                    newField.append(types[i]);
                    break;
            }
        }
        if (anchor)
            anchor.before(newField);
        else
            this.myDiv.append(newField);
        this.values.push(newField);
    };

    this.renderInsert = function () {
        var newAnchor = $(document.createElement('a'));
        var newImage = $(document.createElement('img'));
        newImage.attr("src", 'images/plus.png');
        newImage.addClass("smallBtn");
        newAnchor.append(newImage);
        newAnchor.append(this.placeholder);
        newAnchor.attr("href", "javascript:void(0)");
        newAnchor.bind("click", [this], function (e) {
            e.data[0].renderNew(newAnchor);
        });
        this.myDiv.append(newAnchor);
    }

    this.populateValues = function () {
        var newValues = [];
        $(this.myDiv).find("div.field").each(function (i, elem) {
            var values = [];
            var hasValue = false;
            $(elem).find("input, select").each(function (i, elem) {
                var value = $(elem).val();
                if (value.length > 0 && value != -1) hasValue = true;
                values.push(value);
            });
            if (hasValue) newValues.push(values);
        });
        this.values = newValues;
    }
}