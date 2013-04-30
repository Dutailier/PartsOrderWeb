$(document).ready(function () {

    $.get('ajax/getAllPartFromCart.php')
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créées et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('parts')) {

                // Parcours tous les pièces contenu dans le panier d'achats.
                for (var i in data['parts']) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                    if (data['parts'].hasOwnProperty(i) &&
                        data['parts'][i].hasOwnProperty('type') &&
                        data['parts'][i].hasOwnProperty('name') &&
                        data['parts'][i].hasOwnProperty('serial') &&
                        data['parts'][i].hasOwnProperty('quantity')) {

                        // Ajoute la pièce à la liste.
                        addPart(
                            data['parts'][i]['type'],
                            data['parts'][i]['name'],
                            data['parts'][i]['serial'],
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

        $('#btnClear').click(function() {
            $.get('ajax/clearCart.php')
                .done(function (data) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                    // vérifie si la requête fut un succès.
                    if (data.hasOwnProperty('success') &&
                        data['success']) {

                        // Rafraichi la page actuelle.
                        window.location.reload();

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
});

$(document).on('click', '.addCart', function () {

    var $part = $(this).closest('div.part');

    var parameters = {
        'type': $part.data('type'),
        'name': $part.find('label.name').text(),
        'serial': $part.find('label.serial').text()
    };

    $.get('ajax/addPartToCart.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Met à jour à quantité de la pièce.
                $part.find('label.quantity').text(data['quantity']);

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

$(document).on('click', '.removeCart', function () {

    var $part = $(this).closest('div.part');

    var parameters = {
        'type': $part.data('type'),
        'name': $part.find('label.name').text(),
        'serial': $part.find('label.serial').text()
    };

    $.get('ajax/removePartFromCart.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Si la quantité résultante est nulle,
                // on supprime la pièce du panier d'achats
                if (data['quantity'] <= 0) {
                    $part.remove();

                    // Met à jour à quantité de la pièce.
                } else {
                    $part.find('label.quantity').text(data['quantity']);
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
});

/**
 * Ajoute une pièce à la liste de pièces à affichée.
 * @param type
 * @param name
 * @param serial
 * @param quantity
 */
function addPart(type, name, serial, quantity) {
    $('#parts').append(
        '<div class="part" data-type="' + type + '">' +
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