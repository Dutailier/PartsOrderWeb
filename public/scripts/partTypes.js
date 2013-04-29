$(document).on('click', 'input.addCart', function () {
    //Récupère le partType sélectionné.
    var $partType = $(this).closest('div.partType');

    var parameters = {
        "type": $partType.data('id'),
        "name": $partType.find('span.name').text(),
        "serial": serial
    };

    $.get('protected/ajax/cart/addPartToCart.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Met à jour à quantité de la pièce et ses bouttons.
                updatePartType($partType, data['quantity']);

                // Vérifie que la propriété de l'objet JSON a bien été créée.
            } else if (data.hasOwnProperty('message')) {

                // Affiche un message d'erreur expliquant l'échec de la requête.
                alert(data['message']);
            } else {
                alert('Communication with the server failed.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })
});

$(document).on('click', 'input.removeCart', function () {
    // Récupère le partType sélectionné.
    var $partType = $(this).closest('div.partType');

    var parameters = {
        "type": $partType.data('id'),
        "name": $partType.find('span.name').text(),
        "serial": serial
    };

    $.get('protected/ajax/cart/removePartFromCart.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Met à jour à quantité de la pièce et ses bouttons.
                updatePartType($partType, data['quantity']);

                // Vérifie que la propriété de l'objet JSON a bien été créée.
            } else if (data.hasOwnProperty('message')) {

                // Affiche un message d'erreur expliquant l'échec de la requête.
                alert(data['message']);
            } else {
                alert('Communication with the server failed.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })
});


// Définit le numéro de série de chaise inscrit.
var serial = '';

/**
 * Valide le numéro de série de la chaise et envoie une requête au serveur
 * si celui-ci est valide.
 * @returns {boolean}
 */
function validSerialGlider() {
    // Récupère les champs à valider.
    var txtSerialGlider = $('#txtSerialGlider');

    // Crée une expression régulière à comparée.
    var serialGliderRegex = /^\d{11}$/;

    // Flag de validation.
    var isValid = true;

    // Efface la liste de type de pièce.
    $('.partType').remove();

    if (txtSerialGlider.val()) {
        if (serialGliderRegex.test(txtSerialGlider.val())) {
            serial = txtSerialGlider.val();
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
            "serial": txtSerialGlider.val(),
            "category_id": $.QueryString['category_id']
        };

        $.get('protected/ajax/partTypes/getPartTypes.php', parameters)
            .done(function (data) {

                // Vérifie que les propriétés de l'objet JSON ont bien été créés et
                // vérifié si la requête fut un succès.
                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('partTypes')) {

                    // Parcours tous les types de pièce retournés par la requête.
                    for (var i in data['partTypes']) {

                        // // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                        if (data['partTypes'].hasOwnProperty(i) &&
                            data['partTypes'][i].hasOwnProperty('id') &&
                            data['partTypes'][i].hasOwnProperty('name') &&
                            data['partTypes'][i].hasOwnProperty('description') &&
                            data['partTypes'][i].hasOwnProperty('quantity')) {

                            // Ajoute le type de pièce à la liste.
                            addPartType(
                                data['partTypes'][i]['id'],
                                data['partTypes'][i]['name'],
                                data['partTypes'][i]['description'],
                                data['partTypes'][i]['quantity']);
                        }
                    }

                    // Vérifie que la propriété de l'objet JSON a bien été créée.
                } else if (data.hasOwnProperty('message')) {

                    // Affiche un message d'erreur expliquant l'échec de la requête.
                    alert(data['message']);
                } else {
                    alert('Communication with the server failed.');
                }
            })
            .fail(function () {
                alert('Communication with the server failed.');
            })

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

    var $partType = $('#partTypes');
    // Ajout du type de pièce.
    $partType.append(
        '<div class="partType" data-id="' + id + '">' +
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
    updatePartType($partType, quantity);
}

/**
 * Ajoute les bouttons appropriés à l'éléments spécifié.
 * @param $partType
 * @param quantity
 */
function updatePartType($partType, quantity) {

    var $buttons = $partType.find('div.buttons');

    // Change la quantité affichée.
    $buttons.children('.quantity').text(quantity);

    // Supprimer les bouttons déjà ajoutés.
    $buttons.children('input').remove();

    $buttons.append('<input class="addCart" type="button" />');

    if (quantity > 0) {
        $buttons.append('<input class="removeCart" type="button" />');
    }
}


/**
 * Permet d'obtenir les valeurs passés en GET.
 */
(function ($) {
    $.QueryString = (function (string) {

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