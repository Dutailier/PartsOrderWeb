$(document).ready(function (){

});

$(document).on('click', '.addCart', function () {
    var parameters = {

    };

    $.get('protected/ajax/addPartToCart.php', parameters);
});

$(document).on('click', '.removeCart', function () {

});

/**
 * Ajoute une pièce à la liste de pièces à affichée.
 * @param id
 * @param name
 * @param serial
 * @param quantity
 */
function addPart(id, name, serial, quantity) {
    $('#parts').append(
        '<div class="part" data-id="' + id + '">' +
            '<div class="details">' +
            '<label class="quantity">' + quantity + '</label>' +
            '<label class="name">' + name + '</label>' +
            '<label class="serial">' + serial + '</label>' +
            '</div>' +
            '<div class="buttons">' +
            '<input class="removeCart" type="button"/>' +
            '<input class="addCart" type="button"/>' +
            '</div>' +
            '</div>'
    );
}