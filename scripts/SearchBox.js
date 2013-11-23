/**
 * Created by Sun on 10/30/13.
 */
function setupSearchBox(){
    $("div.search-box").each(function (i, elem) {
        console.log(elem.html);
    });
}

function SearchBox(json) {
    var obj = JSON.parse(json);
    for (var prop in obj) this[prop] = obj[prop];

    this.render = function () {

    };
}