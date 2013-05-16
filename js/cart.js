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
                        data['items'][i].hasOwnProperty('part') &&
                        data['items'][i].hasOwnProperty('categoryId') &&
                        data['items'][i].hasOwnProperty('serial') &&
                        data['items'][i].hasOwnProperty('quantity')) {

                        // Ajoute la pièce à la liste.
                        addItem(
                            data['items'][i]['part'],
                            data['items'][i]['categoryId'],
                            data['items'][i]['serial'],
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

    $('#dialog').dialog({
       autoOpen: false,
        modal: true,
        buttons: {
            'Yes' : function() {
                $.get('ajax/clearCart.php')
                    .done(function (data) {

                        // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                        // vérifie si la requête fut un succès.
                        if (data.hasOwnProperty('success') &&
                            data['success']) {

                            // Rafraichi la page actuelle.
                            window.location = 'categories.php';

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
            },
            'No' : function() {
                $(this).dialog('close');
            }
        }
    });

    $('#btnClear').click(function () {
        $('#dialog').dialog('open');
    });


    $('#btnOrder').click(function () {
        //Vérifie que des pièces sont présentement en commande.
        if ($('#items > div.item').length > 0) {

            $.get('ajax/customerInfosAreRequired.php')
                .done(function (data) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés et
                    // vérifie si la requête fut un succès.
                    if (data.hasOwnProperty('success') &&
                        data['success']) {

                        if (data.hasOwnProperty('customerInfosAreRequired') &&
                            data['customerInfosAreRequired']) {
                            window.location = 'customerInfos.php';
                        } else {

                            $.post('ajax/placeOrderForRetailer.php')
                                .done(function (data) {

                                    // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                                    // vérifie si la requête fut un succès.
                                    if (data.hasOwnProperty('success') &&
                                        data['success'] &&
                                        data.hasOwnProperty('orderId') &&
                                        data['orderId']) {

                                        window.location = 'orderInfos.php?orderId=' + data['orderId'];

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
    });
});

$(document).on('click', '.addCart', function () {

    var $item = $(this).closest('div.item');

    var parameters = {
        'partId': $item.data('type-id'),
        'categoryId': $item.data('category-id'),
        'serial': $item.find('label.serial').text()
    };

    $.get('ajax/AddItem.php', parameters)
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
        'partId': $item.data('type-id'),
        'categoryId': $item.data('category-id'),
        'serial': $item.find('label.serial').text()
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
 * Ajoute un item à la liste.
 * @param part
 * @param categoryId
 * @param name
 * @param serial
 * @param quantity
 */
function addItem(part, categoryId, serial, quantity) {
    $('#items').append(
        '<div class="item" data-type-id="' + part['id'] + '" data-category-id="' + categoryId + '">' +
            '<div class="details">' +
            '<label class="quantity">' + quantity + '</label>' +
            '<label class="name">' + part['name'] + '</label>' +
            '<label class="serial">' + serial + '</label>' +
            '</div>' +
            '<div class="buttons">' +
            '<input class="removeCart" type="button"/>' +
            '<input class="addCart" type="button"/>' +
            '</div>' +
            '</div>'
    );
}