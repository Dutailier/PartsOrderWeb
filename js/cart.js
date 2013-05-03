$(document).ready(function () {

    $.get('ajax/getItems.php')
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créées et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('items')) {

                // Parcours tous les pièces contenu dans le panier d'achats.
                for (var i in data['items']) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                    if (data['items'].hasOwnProperty(i) &&
                        data['items'][i].hasOwnProperty('typeId') &&
                        data['items'][i].hasOwnProperty('name') &&
                        data['items'][i].hasOwnProperty('serialGlider') &&
                        data['items'][i].hasOwnProperty('quantity')) {

                        // Ajoute la pièce à la liste.
                        addItem(
                            data['items'][i]['typeId'],
                            data['items'][i]['name'],
                            data['items'][i]['serialGlider'],
                            data['items'][i]['quantity']);
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

    $('#btnClear').click(function () {
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


    $('#btnOrder').click(function () {
        //Vérifie que des pièces sont présentement en commande.
        if ($('#items > div.item').length > 0) {

            // Redirige l'utilisateur vers le formulaire de commande.
            window.location = 'order.php';
        }
    });
});

$(document).on('click', '.addCart', function () {

    var $item = $(this).closest('div.item');

    var parameters = {
        'typeId': $item.data('typeid'),
        'name': $item.find('label.name').text(),
        'serialGlider': $item.find('label.serialGlider').text()
    };

    $.get('ajax/addItem.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Met à jour à quantité de la pièce.
                $item.find('label.quantity').text(data['quantity']);

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

    var $item = $(this).closest('div.item');

    var parameters = {
        'typeId': $item.data('typeid'),
        'name': $item.find('label.name').text(),
        'serialGlider': $item.find('label.serialGlider').text()
    };

    $.get('ajax/removeItem.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('quantity')) {

                // Si la quantité résultante est nulle,
                // on supprime la pièce du panier d'achats
                if (data['quantity'] <= 0) {
                    $item.remove();

                    // Met à jour à quantité de la pièce.
                } else {
                    $item.find('label.quantity').text(data['quantity']);
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
 * Ajoute un item à la liste du panier d'achats.
 * @param typeId
 * @param name
 * @param serialGlider
 * @param quantity
 */
function addItem(typeId, name, serialGlider, quantity) {
    $('#items').append(
        '<div class="item" data-typeId="' + typeId + '">' +
            '<div class="details">' +
            '<label class="quantity">' + quantity + '</label>' +
            '<label class="name">' + name + '</label>' +
            '<label class="serialGlider">' + serialGlider + '</label>' +
            '</div>' +
            '<div class="buttons">' +
            '<input class="removeCart" type="button"/>' +
            '<input class="addCart" type="button"/>' +
            '</div>' +
            '</div>'
    );
}