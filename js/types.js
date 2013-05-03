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

            $.get('ajax/getTypes.php', parameters)
                .done(function (data) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés et
                    // vérifié si la requête fut un succès.
                    if (data.hasOwnProperty('success') &&
                        data['success'] &&
                        data.hasOwnProperty('types')) {

                        $('div.type').remove();

                        // Parcours tous les types de pièce retournés par la requête.
                        for (var i in data['types']) {

                            // // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                            if (data['types'].hasOwnProperty(i) &&
                                data['types'][i].hasOwnProperty('id') &&
                                data['types'][i].hasOwnProperty('name') &&
                                data['types'][i].hasOwnProperty('description') &&
                                data['types'][i].hasOwnProperty('quantity')) {

                                // Ajoute le type de pièce à la liste.
                                addType(
                                    data['types'][i]['id'],
                                    data['types'][i]['name'],
                                    data['types'][i]['description'],
                                    data['types'][i]['quantity']);
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

    //Récupère le type sélectionné.
    var $type = $(this).closest('div.type');

    var parameters = {
        "typeId": $type.data('id'),
        "name": $type.find('span.name').text(),
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
                updateType($type, data['quantity']);

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

    // Récupère le type sélectionné.
    var $type = $(this).closest('div.type');

    var parameters = {
        "typeId": $type.data('id'),
        "name": $type.find('span.name').text(),
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
                updateType($type, data['quantity']);

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
 * Ajoute un type de pièce à la liste.
 * @param id
 * @param name
 * @param description
 * @param quantity
 */
function addType(id, name, description, quantity) {

    // Ajout du type de pièce.
    var $type = $(
        '<div class="type" data-id="' + id + '">' +
            '<div class="details">' +
                '<span class="name">' + name + '</span>' +
                '<span class="description">' + description + '</span>' +
            '</div>' +
            '<div class="buttons"> ' +
                '<span class="quantity"></span>' +
            '</div>' +
        '</div>');

    $('#types').append($type);

    // Ajoute les bouttons appropriés au dernier éléments ajouté.
    updateType($type, quantity);
}

/**
 * Ajoute les bouttons appropriés à l'éléments spécifié.
 * @param $type
 * @param quantity
 */
function updateType($type, quantity) {

    var $buttons = $type.find('div.buttons');

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