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

                // Vérifie que les propriétés de l'objet JSON ont bien été créés et
                // vérifié si la requête fut un succès.
                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('partTypes')) {

                    // Parcours tous les types de pièce retournés par la requête.
                    for (var i in data['partTypes']) {

                        // // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                        if (data['partTypes'].hasOwnProperty(i) &&
                            data['partTypes'][i].hasOwnProperty('partType_id') &&
                            data['partTypes'][i].hasOwnProperty('partType_name') &&
                            data['partTypes'][i].hasOwnProperty('partType_description') &&
                            data['partTypes'][i].hasOwnProperty('partType_quantity')) {

                            // Ajoute le type de pièce à la liste.
                            addPartType(
                                data['partTypes'][i]['partType_id'],
                                data['partTypes'][i]['partType_name'],
                                data['partTypes'][i]['partType_description'],
                                data['partTypes'][i]['partType_quantity']);
                        }
                    }
                } else {
                    alert(data['message']);
                }
            },
            error: function () {
                alert('Communication with the server failed.');
            }
        });

    } else {
        txtSerialGlider.focus();
    }

    // Retient le postback automatique.
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

    var partTypes = $('#partTypes');
    // Ajout du type de pièce.
    partTypes.append(
        '<div class="partType" data-partType_id="' + id + '">' +
            '<div class="details">' +
                '<span class="name">' + name + '</span>' +
                '<span class="description">' + (description ? description : '') + '</span>' +
            '</div>' +
            '<div class="buttons"> ' +
                '<span class="quantity">' + quantity + '</span>' +
            '</div>' +
        '</div>'
    );

    // Ajoute les bouttons appropriés au dernier éléments ajouté.
    changeButtons(quantity, partTypes.find('.partType').last().find('.buttons'));
}

/**
 * Ajoute les bouttons appropriés à l'éléments spécifié.
 * @param quantity
 * @param element
 */
function changeButtons(quantity, element) {

    var btnRemoveFromCart = $('<input class="removeCart" type="button" />');
    var btnAddToCart = $('<input class="addCart" type="button" />');
    var spanQuantity = $(element).find('.quantity');

    // Change la quantité affichée.
    spanQuantity.text(quantity);

    // Supprimer les bouttons déjà ajoutés.
    $(element).find('input').remove();

    $(element).append(btnAddToCart);

    if (quantity > 0) {
        $(element).append(btnRemoveFromCart);
    }

    // Prends en charge les clics.
    handlerClick();
}

function handlerClick() {
    $('.addCart').click(function () {

        // Récupère le partType sélectionné.
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
                    changeButtons(data['partType_quantity'], partType.find('.buttons'));
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
                    changeButtons(data['partType_quantity'], partType.find('.buttons'));
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