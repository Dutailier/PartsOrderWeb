$(document).ready(function() {

});

function addItem(id, name, description, qty) {
    $('#items').append(
        '<div class="item" data-item_id="' + id + '">' +

        '</div>'
    );
}