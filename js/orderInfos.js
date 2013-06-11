$(document).ready(function () {
    var parameters = {
        "orderId": $.QueryString['orderId']
    };

    $.post('ajax/getOrderDetails.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('order')) {

                var order = data['order'];

                if (order.hasOwnProperty('number') &&
                    order.hasOwnProperty('creationDate') &&
                    order.hasOwnProperty('status')) {
                    updateOrderInfos(order);

                    var $summary = $('#summary');
                    //noinspection FallthroughInSwitchStatementJS
                    switch (order['status']) {
                        case 'Pending':
                            $summary.append('<input id="btnConfirm" name="btnConfirm" type="button" value="Confirm"/>');
                        case 'Confirmed':
                            $summary.append('<input id="btnCancel" name="btnCancel" type="button" value="Cancel"/>');
                            break;
                    }
                }

                if (order.hasOwnProperty('shippingAddress') &&
                    order.hasOwnProperty('receiver') &&
                    order.hasOwnProperty('store') &&
                    order.hasOwnProperty('lines')) {

                    var shippingAddress = order['shippingAddress'];
                    var receiver = order['receiver'];
                    var store = order['store'];
                    var lines = order['lines'];

                    if (shippingAddress.hasOwnProperty('details') &&
                        shippingAddress.hasOwnProperty('city') &&
                        shippingAddress.hasOwnProperty('zip') &&
                        shippingAddress.hasOwnProperty('state') &&
                        shippingAddress['state'].hasOwnProperty('name')) {
                        updateShippingAddressInfos(shippingAddress);
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
                            updateStoreInfos(store);
                        }
                    }

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        updateReceiverInfos(receiver);
                    }

                    for (var i in lines) {
                        if (lines.hasOwnProperty(i)) {
                            var line = lines[i];

                            if (line.hasOwnProperty('product') &&
                                line['product'].hasOwnProperty('id') &&
                                line['product'].hasOwnProperty('name') &&
                                line.hasOwnProperty('quantity') &&
                                line.hasOwnProperty('serial'))
                                addLineInfos(line);
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
        });

    $('#confirmDialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {

                var parameters = {
                    "orderId": $.QueryString['orderId']
                };

                $.post('ajax/confirmOrder.php', parameters)
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
                    .always(function () {
                        $('#btnConfirm').removeAttr('disabled');
                    })
            },
            "No": function () {
                $(this).dialog('close');
            }
        }
    });

    $('#cancelDialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {

                var parameters = {
                    "orderId": $.QueryString['orderId']
                };

                $.post('ajax/cancelOrder.php', parameters)
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
});

$(document).on('click', '#btnConfirm', function () {
    $('#confirmDialog').dialog('open');
});

$(document).on('click', '#btnCancel', function () {
    $('#cancelDialog').dialog('open');
});

/**
 * Affiche les informations relatives à la commande.
 * @param infos
 */
function updateOrderInfos(infos) {
    $('#number').text(infos['number']);
    $('#creationDate').text(dateFormat(infos['creationDate']));
    $('#status').text(infos['status']);
}

/**
 * Affiche les informations relatives à l'adresse d'expédition.
 * @param address
 */
function updateShippingAddressInfos(address) {
    $('#shippingAddress').text(addressFormat(address));
}

/**
 * Affiche les informations relatives au magasin.
 * @param infos
 */
function updateStoreInfos(infos) {
    $('#storeName').text(infos['name']);
    $('#storePhone').text(phoneFormat(infos['phone']));
    $('#storeEmail').text(infos['email']);
    $('#storeAddress').text(addressFormat(infos['address']));
}

/**
 * Affiche les informations relatives au client.
 * @param infos
 */
function updateReceiverInfos(infos) {
    $('#receiverName').text(infos['name']);
    $('#receiverPhone').text(phoneFormat(infos['phone']));
    $('#receiverEmail').text(infos['email']);
}

/**
 * Concatonne les détails de l'adresse en une seule chaîne de caractères.
 * @param address
 * @returns {string}
 */
function addressFormat(address) {
    return address['details'] + ', ' +
        address['city'] + ', ' +
        address['zip'] + ', ' +
        address['state']['name'];
}

/**
 * Transforme 12345678901 pour 1-234-567-8910.
 * @param phone
 * @returns {string}
 */
function phoneFormat(phone) {
    return phone.substring(0, 1) + '-' +
        phone.substring(1, 4) + '-' +
        phone.substring(4, 7) + '-' +
        phone.substring(7);
}

/**
 * Ajoute une ligne à la commande.
 */
function addLineInfos(line) {
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

/**
 * Transforme 2013-06-07 10:24:15.227 pour 2013-06-07 10:24:15.
 * @param date
 * @returns {string}
 */
function dateFormat(date) {
    return date.substring(0, 19);
}

(function ($) {
    $.QueryString = (function (a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i) {
            var p = a[i].split('=');
            if (p.length != 2) continue;
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'))
})(jQuery);