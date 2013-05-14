// Définit le numéro de série de chaise inscrit.
var serialGlider = '';

$(document).ready(function () {

    $('#frmSearch').validate({

        // Définit les règles de validations.
        rules: {
            serial: {
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 11
            }
        },

        errorPlacement: function (error, element) {
            error.appendTo(element.parent());
        },

        // Se produit losrque tous les champs sont valides.
        submitHandler: function () {

            // Récupère le numéro de série afin de le garder valide.
            serialGlider = $('#serialGlider').val();

            var parameters = {
                "serialGlider": serialGlider,
                "categoryId": $.queryString['categoryId']
            };

            $.get('ajax/getPartsByCategoryId.php', parameters)
                .done(function (data) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés et
                    // vérifié si la requête fut un succès.
                    if (data.hasOwnProperty('success') &&
                        data['success'] &&
                        data.hasOwnProperty('parts')) {

                        $('div.part').remove();

                        // Parcours tous les pièces retournées par la requête.
                        for (var i in data['parts']) {

                            // // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                            if (data['parts'].hasOwnProperty(i) &&
                                data['parts'][i].hasOwnProperty('id') &&
                                data['parts'][i].hasOwnProperty('name') &&
                                data['parts'][i].hasOwnProperty('description') &&
                                data['parts'][i].hasOwnProperty('quantity')) {

                                // Ajoute la pièce à la liste.
                                addPart(
                                    data['parts'][i]['id'],
                                    data['parts'][i]['name'],
                                    data['parts'][i]['description'],
                                    data['parts'][i]['quantity']);
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
        }
    })
});

$(document).on('click', 'input.addCart', function () {

    //Récupère la pièce sélectionnée.
    var $part = $(this).closest('div.part');

    var parameters = {
        "partId": $part.data('id'),
        "name": $part.find('span.name').text(),
        "categoryId" : $.queryString['categoryId'],
        "serialGlider": serialGlider
    };

    $.get('ajax/addItem.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Met à jour à quantité de la pièce et ses bouttons.
                updateType($part, data['quantity']);

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

    // Récupère la pièce sélectionnée.
    var $part = $(this).closest('div.part');

    var parameters = {
        "partId": $part.data('id'),
        "name": $part.find('span.name').text(),
        "categoryId" : $.queryString['categoryId'],
        "serialGlider": serialGlider
    };

    $.get('ajax/removeItem.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Met à jour à quantité de la pièce et ses bouttons.
                updateType($part, data['quantity']);

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

/**
 * Ajoute une pièce à la liste.
 * @param id
 * @param name
 * @param description
 * @param quantity
 */
function addPart(id, name, description, quantity) {

    // Ajout du pièce.
    var $part = $(
        '<div class="part" data-id="' + id + '">' +
            '<div class="details">' +
                '<span class="name">' + name + '</span>' +
                '<span class="description">' + description + '</span>' +
            '</div>' +
            '<div class="buttons"> ' +
                '<span class="quantity"></span>' +
            '</div>' +
        '</div>');

    $('#parts').append($part);

    // Ajoute les bouttons appropriés au dernier éléments ajouté.
    updateType($part, quantity);
}

/**
 * Ajoute les bouttons appropriés à l'éléments spécifié.
 * @param $part
 * @param quantity
 */
function updateType($part, quantity) {

    var $buttons = $part.find('div.buttons');

    // Change la quantité affichée.
    $buttons.children('span.quantity').text(quantity);
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