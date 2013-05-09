$(document).ready(function () {


});


/**
 * Ajoute un item à la liste.
 * @param typeId
 * @param categoryId
 * @param name
 * @param serialGlider
 * @param quantity
 */
function addItem(typeId, categoryId, name, serialGlider, quantity) {
    $('#items').append(
        '<div class="item" data-typeId="' + typeId + '" data-categoryId="' + categoryId + '">' +
            '<div class="details">' +
            '<label class="quantity">' + quantity + '</label>' +
            '<label class="name">' + name + '</label>' +
            '<label class="serialGlider">' + serialGlider + '</label>' +
            '</div>' +
            '<div class="buttons">' +
            '<input class="removeCart" type="button"/>' +
            '<input class="addCart" type="button"/>' +
            '</div>' +
            '</div>'
    );
}

/**
 * Permet d'obtenir les valeurs passés en GET.
 */
(function ($) {
    $.queryString = (function (string) {

        if (string == "") return {};

        var params = {};

        for (var i = 0; i < string.length; ++i) {
            var p = string[i].split('=');
            if (p.length != 2) continue;
            params[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return params;
    })(window.location.search.substr(1).split('&'))
})(jQuery);