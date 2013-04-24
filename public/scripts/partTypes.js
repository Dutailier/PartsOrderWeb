// Définit le numéro de série de chaise inscrit.
var serialGlider = '';

/**
 * Valide le numéro de série de la chaise et envoie une requête au serveur
 * si celui-ci est valide.
 * @returns {boolean}
 */
function validSerialGlider() {
    // Récupère les champs à valider.
    var txtSerialGlider = $('#txtSerialGlider');

    // Récupère les valeurs passées en GET.
    var $_GET = populateGet();

    // Crée une expression régulière à comparée.
    var serialGliderRegex = /^\d{11}$/;

    // Flag de validation.
    var isValid = true;

    // Efface la liste de type de pièce.
    $('.partType').remove();

    if (txtSerialGlider.val()) {
        if (serialGliderRegex.test(txtSerialGlider.val())) {
            serialGlider = txtSerialGlider.val();
            txtSerialGlider.removeClass('warning');
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
            "serial_glider": txtSerialGlider.val(),
            "category_id": $_GET['category_id']
        };

        $.ajax({
            type: 'GET',
            url: 'protected/getPartTypes.php',
            data: parameters,
            dataType: 'json',
            success: function (data) {
                if (data['success']) {

                    // Crée une nouvelle liste de pièces.
                    for (var i = 0; i < data['lenght']; i++) {
                        addPartType(
                            data[i]['partType_id'],
                            data[i]['partType_name'],
                            data[i]['partType_description'],
                            data[i]['partType_quantity']);
                    }

                    // Gère le click sur un bouton ajouter au panier d'achat.
                    handlerClickAddToCart();

                    // Gère le click sur un bouton retirer du panier d'achat.
                    handlerClickRemoveFromCart();

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
 * @param quantity
 */
function addPartType(id, name, description, quantity) {

    // Ajout du type de pièce.
    $('#partTypes').append(
        '<div class="partType" data-partType_id="' + id + '">' +
            '<div class="details">' +
            '<span class="name">' + name + '</span>' +
            '<span class="description">' + (description ? description : '') + '</span>' +
            '</div>' +
            '<div class="buttons"> ' +
            '</div>' +
            '</div>'
    );

    // Récupère l'élément précédemment ajouté et y ajoute les bouttons.
    var last = $('#partTypes').children('.partType').last();
    addButtons(quantity, last.find('.buttons'));
}

function handlerClick() {
    $('.addCart').click(function () {

        // Récupère le partType sélectionné.
        var btn = $(this);
        var partType = $(this).closest('.partType');

        var parameters = {
            "serial_glider": serialGlider,
            "partType_id": partType.data('partType_id')
        };

        $.ajax({
            type: 'GET',
            url: 'protected/addToCart.php',
            data: parameters,
            dataType: 'json',
            success: function (data) {
                if (data['success']) {
                    addButtons(data['partType_quantity'], partType.find('.buttons'));
                } else {
                    alert(data['message']);
                }
            },
            error: function () {
                alert('Communication with the server failed.');
            }
        });
    });

    $('.removeCart').click(function () {

        // Récupère le partType sélectionné.
        var btn = $(this);
        var partType = $(this).closest('.partType');

        var parameters = {
            "serial_glider": serialGlider,
            "partType_id": partType.data('partType_id')
        };

        $.ajax({
            type: 'GET',
            url: 'protected/removeFromCart.php',
            data: parameters,
            dataType: 'json',
            success: function (data) {
                if (data['success']) {
                    addButtons(data['partType_quantity'], partType.find('.buttons'));
                } else {
                    alert(data['message']);
                }
            },
            error: function () {
                alert('Communication with the server failed.');
            }
        });
    });
}

function addButtons(quantity, element) {

    var btnRemoveFromCart = $('<div class="removeCart" />');
    var btnAddToCart = $('<div class="addCart" />');

    // Supprimer les bouttons déjà ajoutés.
    $(element).children().remove();

    $(element).append(btnAddToCart);

    if (quantity > 0) {
        $(element).append(btnRemoveFromCart);
    }

    // Prends en charge les clicks.
    handlerClick();
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