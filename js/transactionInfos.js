$(document).ready(function () {
    $.post('ajax/getTransactionInfos.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('transaction')) {

                var transaction = data['transaction'];

                if (transaction.hasOwnProperty('order') &&
                    transaction.hasOwnProperty('shippingAddress') &&
                    transaction.hasOwnProperty('receiver') &&
                    transaction.hasOwnProperty('store') &&
                    transaction.hasOwnProperty('lines')) {

                    var order = transaction['order'];
                    var shippingAddress = transaction['shippingAddress'];
                    var receiver = transaction['receiver'];
                    var store = transaction['store'];
                    var lines = transaction['lines'];

                    if (order.hasOwnProperty('creationDate') &&
                        order.hasOwnProperty('status')) {
                        UpdateOrderInfos(order);
                    }

                    if (shippingAddress.hasOwnProperty('details') &&
                        shippingAddress.hasOwnProperty('city') &&
                        shippingAddress.hasOwnProperty('zip') &&
                        shippingAddress.hasOwnProperty('state') &&
                        shippingAddress['state'].hasOwnProperty('name')) {
                        UpdateShippingAddress(shippingAddress);
                    }

                    if (store.hasOwnProperty('name') &&
                        store.hasOwnProperty('phone') &&
                        store.hasOwnProperty('email') &&
                        store.hasOwnProperty('address')) {
                        var address = store['address'];

                        if (address.hasOwnProperty('details') &&
                            address.hasOwnProperty('city') &&
                            address.hasOwnProperty('zip') &&
                            address.hasOwnProperty('state') &&
                            address['state'].hasOwnProperty('name')) {
                            UpdateStoreInfos(store);
                        }
                    }

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        UpdateReceiverInfos(receiver);
                    }

                    for (var i in lines) {
                        if (lines.hasOwnProperty(i)) {
                            var line = lines[i];

                            if (line.hasOwnProperty('product') &&
                                line['product'].hasOwnProperty('id') &&
                                line['product'].hasOwnProperty('name') &&
                                line.hasOwnProperty('quantity') &&
                                line.hasOwnProperty('serial'))
                                AddLine(line);
                        }
                    }
                }
            }
            else if (data.hasOwnProperty('message')) {
                alert(data['message']);

            } else {
                alert('The result of the server is unreadable.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })

    $('#btnConfirm').click(function () {
        $.post('ajax/confirmTransaction.php')
            .done(function (data) {
                if (data.hasOwnProperty('success') &&
                    data['success']) {

                    window.location = 'confirmation.php';

                } else if (data.hasOwnProperty('message')) {
                    alert(data['message']);

                } else {
                    alert('The result of the server is unreadable.');
                }
            })
            .fail(function () {
                alert('Communication with the server failed.');
            })
    });

    $('#dialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {
                $.get('ajax/cancelTransaction.php')
                    .done(function (data) {
                        if (data.hasOwnProperty('success') &&
                            data['success']) {
                            window.location = 'destinations.php';

                        } else if (data.hasOwnProperty('message')) {
                            alert(data['message']);

                        } else {
                            alert('The result of the server is unreadable.');
                        }
                    })
                    .fail(function () {
                        alert('Communication with the server failed.');
                    })
            },
            "No": function () {
                $(this).dialog('close');
            }
        }
    });

    $('#btnCancel').click(function () {
        $('#dialog').dialog('open');
    });
});

/**
 * Affiche les informations relatives à la commande.
 * @param infos
 * @constructor
 */
function UpdateOrderInfos(infos) {
    $('#creationDate').text(infos['creationDate']);
    $('#status').text(infos['status']);
}

/**
 * Affiche les informations relatives à l'adresse d'expédition.
 * @param address
 * @constructor
 */
function UpdateShippingAddress(address) {
    $('#shippingAddress').text(AddressFormat(address));
}

/**
 * Affiche les informations relatives au magasin.
 * @param infos
 * @constructor
 */
function UpdateStoreInfos(infos) {
    $('#storeName').text(infos['name']);
    $('#storePhone').text(PhoneFormat(infos['phone']));
    $('#storeEmail').text(infos['email']);
    $('#storeAddress').text(AddressFormat(infos['address']));
}

/**
 * Affiche les informations relatives au client.
 * @param infos
 * @constructor
 */
function UpdateReceiverInfos(infos) {
    $('#receiverName').text(infos['name']);
    $('#receiverPhone').text(PhoneFormat(infos['phone']));
    $('#receiverEmail').text(infos['email']);
}

/**
 * Concatonne les détails de l'adresse en une seule chaîne de caractères.
 * @param address
 * @returns {string}
 * @constructor
 */
function AddressFormat(address) {
    return address['details'] + ', ' +
        address['city'] + ', ' +
        address['zip'] + ', ' +
        address['state']['name'];
}

/**
 * Transforme 12345678901 pour 1-234-567-8910.
 * @param phone
 * @returns {string}
 * @constructor
 */
function PhoneFormat(phone) {
    return phone.substring(0, 1) + '-' +
        phone.substring(1, 4) + '-' +
        phone.substring(4, 7) + '-' +
        phone.substring(7);
}

/**
 * Ajoute une ligne à la commande.
 */
function AddLine(line) {
    $('#lines').append(
        '<div class="line" data-product-id="' + line['product']['id'] + '">' +
            '<div class="details">' +
            '<label class="quantity">' + line['quantity'] + '</label>' +
            '<label class="name">' + line['product']['name'] + '</label>' +
            '<label class="serial">' + line['serial'] + '</label>' +
            '</div>' +
            '</div>'
    );
}