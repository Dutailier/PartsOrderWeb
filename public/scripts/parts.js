function validSerialGlider() {

}

function addPart(id, name, sku, description) {
    $('#parts').append(
        '<div class="part" id="' + id + '">' +
            '<div class="details">' +
                '<span class="name">' + name + '</span>' +
                '<span class="sku">' + sku + '</span>' +
                '<span class="description">' + description + '</span>' +
            '</div>' +
            '<div class="buttons"> ' +
                '<img class="btnAddToCart" src="public/images/buttons/add_to_cart.png"/>' +
            '</div>' +
        '</div>');
}