/**
 * Created by Sun on 10/30/13.
 */
function setupSearchBox(){
    $("div.search-box").each(function (i, elem) {
        $(elem).html(new SearchBox($(elem).html()).render());
    });
}

function SearchBox(json) {
    var obj = JSON.parse(json);
    for (var prop in obj) this[prop] = obj[prop];

    this.render = function () {
        return this.title;
    };
}