$(document).ready(function() {

    $('.btnAddToCart').click(function() {
        var parameters = {
            "partType_id" : this.parent('.partType').id
        };

        $.ajax({
            type: 'GET',
            url: 'protected/addToCart.php',
            data: parameters,
            dataType: 'json',
            success: function (data) {
                if (data['success']) {
                } else {
                    alert(data['message']);
                }
            },
            error: function () {
                alert('Communication with the server failed.');
            }
        });
    });
});

/**
 * Valide le numéro de série de la chaise et envoie une requête au serveur
 * si celui-ci est valide.
 * @returns {boolean}
 */
function validSerialGlider() {
    var txtSerialGlider = $('#txtSerialGlider');
    var serialGliderRegex = /^\d{11}$/;
    var $_GET = populateGet();
    var isValid = true;

    if (txtSerialGlider.val()) {
        if(serialGliderRegex.test(txtSerialGlider.val())) {
        } else {
            txtSerialGlider.addClass('warning');
            isValid = false;
        }
    } else {
        txtSerialGlider.addClass('warning');
        isValid = false;
    }

    if (isValid) {
        var parameters = {
            "serial_glider" : txtSerialGlider.val(),
            "category_id": $_GET['category_id']
        };

        $.ajax({
            type: 'GET',
            url: 'protected/getPartTypes.php',
            data: parameters,
            dataType: 'json',
            success: function (data) {
                if (data['success']) {

                    for(var i = 0; i < data['lenght']; i++) {
                        addPartType(
                            data[i]['partType_id'],
                            data[i]['partType_name'],
                            data[i]['partType_description']);
                    }
                } else {
                    alert(data['message']);
                }
            },
            error: function () {
                alert('Communication with the server failed.');
            }
        });
    }
    return false;
}

/**
 * Ajoute un type de pièce à la liste.
 * @param id
 * @param name
 * @param description
 */
function addPartType(id, name, description) {
    $('#parts').append(
        '<div class="partType" id="' + id + '">' +
            '<div class="details">' +
                '<span class="name">' + name + '</span>' +
                '<span class="description">' + description + '</span>' +
            '</div>' +
            '<div class="buttons"> ' +
                '<img class="btnAddToCart" src="public/images/buttons/add_to_cart.png"/>' +
            '</div>' +
        '</div>');
}

/**
 * Retourne un tableau contenant les paramètres passés en GET.
 * @returns {{}}
 */
function populateGet() {
    var obj = {},
        params = location.search.slice(1).split('&');

    for (var i = 0, len = params.length; i < len; i++) {
        var keyVal = params[i].split('=');
        obj[decodeURIComponent(keyVal[0])] = decodeURIComponent(keyVal[1]);
    }

    return obj;
}