/**
 * Created by Sun on 10/30/13.
 */

var btnAdd;
$(function () {
    $("input.date").datepicker({
        onSelect: function (dateText, inst) {
            this.focus();
        },
        constrainInput: false
    });
});

function Helper() {
//nothing here, just a Helper class with static methods
}

Helper.setupDataTable = function (tableID) {
    btnAdd = $("a.btnPlus").parent();
    Helper.makeSelectors();
    Helper.alignCellWidths($.makeArray($("table#table" + tableID + " tr th")),
        $.makeArray($("div#fixedHeader table tr th")));
    Helper.alignCellWidths($.makeArray($("table#table" + tableID + " tfoot tr td")),
        $.makeArray($("div#fixedFooter table.content tr td")));
    $("div#fixedHeader table tr th").each(function (i, elem) {
        $(elem).attr("dir", "1").css("cursor", "pointer");
        $(elem).click(function () {
            var dir = $(elem).attr("dir") * 1;
            Helper.sortTable($("table#table" + tableID + " tbody.sortable"), i, dir);
            $(elem).attr("dir", "" + (dir * -1));
        })
    });

    $("div#scrollContainer").css("maxHeight", "10%").animate({
        maxHeight: "85%"
    }, 666, function () {
        // Animation complete.
        $("div#fixedFooter table.content, div#fixedHeader table")
            .css("width", $("table#table" + tableID).css("width"));
    });
}

Helper.makeSelectors = function () {
    $("td.select").each(function (i, elem) {
        var select = window[$(elem).text()].call(this);
        $(elem).html("");
        select.id = elem.id;
        select.disabled = $(elem).hasClass("disabled");
        $(elem).append(select);
        $(elem).removeClass("select disabled");
        $(elem).removeAttr("id");
    });
}

Helper.uploadObj = function (newObj) {
    Helper.postJSON(newObj, "n");
}

Helper.makeQuery = function (searchBox) {
    Helper.postJSON(searchBox, "q");
}

Helper.postJSON = function (json, type) {
    $.ajax({
        type: "POST",
        data: "_" + type + Helper.stringify(json),
        success: function (data, textStatus, jqXHR) {
            if (data.length > 0) {
                alert(data);
                console.log(data);
            } else {
                location.reload();
            }
        }

    });
}

Helper.alignCellWidths = function (rowSource, rowTarget) {
    for (var i in rowTarget) {
        $(rowTarget[i]).css("width", $(rowSource[i]).css("width"));
//        console.log($(rowSource[i]).css("width") + " " + $(rowTarget[i]).css("width"));
    }
}

Helper.sortTable = function (table, col, dir) {
    var rows = $.makeArray(table.find("tr"));
    rows.sort(function (a, b) {
        var aText = $(a.cells[col]).attr("raw") ? $(a.cells[col]).attr("raw") : $(a.cells[col]).text();
        var bText = $(b.cells[col]).attr("raw") ? $(b.cells[col]).attr("raw") : $(b.cells[col]).text();
        return aText.localeCompare(bText) * dir;
    });
    table.append(rows);
}

Helper.displayBtnAdd = function (bool) {
    btnAdd.css("display", bool ? "inline-block" : "none");
}

Helper.stringify = function (json) {
    return JSON.stringify(Helper.cleanForJSON(json));
}

Helper.cleanForJSON = function (obj) {
    switch ($.type(obj)) {
        case "array":
            var clean = [];
            break;
        case "object":
            if (obj instanceof $) return undefined;//don't serialize jQuery objects
            var clean = {};
            break;
        case "function":
            return undefined;
        default :
            return obj;
    }
    for (var i in obj) {
        if ((i + "").substring(0, 1) !== "_") {//don't serialize fields starting with underscore
            var value = Helper.cleanForJSON(obj[i]);
            if (value !== undefined)
                clean[i] = value;
        }
    }
    return clean;
}
