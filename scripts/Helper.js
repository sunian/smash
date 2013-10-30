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

function Helper (){
//nothing here, just a Helper class with static methods
}

Helper.setupTables = function (tableID) {
    btnAdd = $("a.btnPlus").parent();
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

Helper.uploadObj = function (newObj){
    $.ajax({
        type: "POST",
        data: JSON.stringify(newObj),
//                dataType: "json",
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            if (data.length > 0) {
                alert(data);
            } else {
                location.reload();
            }
        }

    });
}

Helper.alignCellWidths = function (rowSource, rowTarget) {
    for (var i in rowTarget) {
        $(rowTarget[i]).css("width", $(rowSource[i]).css("width"));
        console.log($(rowSource[i]).css("width") + " " + $(rowTarget[i]).css("width"));
    }
}

Helper.sortTable = function (table, col, dir) {
    var rows = $.makeArray(table.find("tr"));
    rows.sort(function (a, b) {
        return $(a.cells[col]).text().localeCompare($(b.cells[col]).text()) * dir;
    });
    table.append(rows);
}