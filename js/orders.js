$(document).ready(function () {

    $.post('ajax/getStoreConnected.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('store')) {

                var store = data['store'];

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

            } else if (data.hasOwnProperty('message')) {
                alert(data['message']);

            } else {
                alert('The result of the server is unreadable.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })

    $.post('ajax/getOrders.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orders')) {

                var orders = data['orders'];

                for (var i in orders) {
                    if (orders.hasOwnProperty(i)) {
                        var order = orders[i];

                        if (order.hasOwnProperty('status') &&
                            order.hasOwnProperty('id') &&
                            order.hasOwnProperty('number') &&
                            order.hasOwnProperty('lastModifiedDate')) {
                            AddOrder(order);
                        }
                    }
                }

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

$(document).on('click', 'div.order', function () {

    var $order = $(this);

    if ($order.next().is('div.details')) {
        $order.next().slideToggle();
    } else {
        AddDetails($order);
    }
});

/**
 * Retourne les détails d'une commande.
 * @param $order
 * @constructor
 */
function AddDetails($order) {
    var parameters = {
        "orderId": $order.data('id')
    };

    $.post('ajax/getOrderInfos.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('order')) {

                var order = data['order'];
                var $details = $('<div class="details"></div>');
                var $lines = $('<div class="lines"></div>');
                var $buttons = $('<div class="buttons"></div>');

                if (order.hasOwnProperty('receiver')) {
                    var receiver = order['receiver'];

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        AddReceiverInfos($details, receiver);
                    }
                }

                if (order.hasOwnProperty('shippingAddress')) {
                    var shippingAddress = order['shippingAddress'];

                    if (shippingAddress.hasOwnProperty('details') &&
                        shippingAddress.hasOwnProperty('city') &&
                        shippingAddress.hasOwnProperty('zip') &&
                        shippingAddress.hasOwnProperty('state') &&
                        shippingAddress['state'].hasOwnProperty('name')) {
                        AddShippingAddressInfos($details, shippingAddress);
                    }
                }

                if (order.hasOwnProperty('lines')) {
                    var lines = order['lines'];

                    for (var i in lines) {
                        if (lines.hasOwnProperty(i)) {
                            var line = lines[i];

                            if (line.hasOwnProperty('product') &&
                                line['product'].hasOwnProperty('id') &&
                                line['product'].hasOwnProperty('name') &&
                                line.hasOwnProperty('quantity') &&
                                line.hasOwnProperty('serial'))
                                AddLine($lines, line);
                        }
                    }
                }

                if (order.hasOwnProperty('status')) {
                    if (order['status'] == 'Ordered') {
                        $buttons.append('<input class="btnCancel" type="button" value="Cancel"/>');
                        $buttons.append('<input class="btnConfirm" type="button" value="Confirm"/>');
                    } else if (order['status'] == 'Confirmed') {
                        $buttons.append('<input class="btnCancel" type="button" value="Cancel"/>');
                    }
                }

                $details.append($lines);
                $details.append($buttons);
                $details.insertAfter($order).hide().slideDown();

            } else if (data.hasOwnProperty('message')) {
                alert(data['message']);

            } else {
                alert('The result of the server is unreadable.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })
}

$(document).on('click', 'input.btnConfirm', function () {

    var $details = $(this).closest('div.details');
    var $order = $details.prev();

    var parameters = {
        "orderId" : $order.data('id')
    };

    $.post('ajax/confirmOrder.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {

                window.location.reload();

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

$(document).on('click', 'input.btnCancel', function () {

    var $details = $(this).closest('div.details');
    var $order = $details.prev();

    var parameters = {
        "orderId" : $order.data('id')
    };

    $.post('ajax/cancelOrder.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {

                window.location.reload();

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

/**
 * Ajoute les informations relatives au receveur aux détails de la commande.
 * @param $details
 * @param receiver
 * @constructor
 */
function AddReceiverInfos($details, receiver) {
    $details.append(
        '<fieldset>' +
            '<legend>Receiver informations</legend>' +
            '<p>' +
            '<label class="properties">Name : </label>' +
            '<label id="receiverName" class="values">' + receiver['name'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Phone : </label>' +
            '<label id="receiverPhone" class="values">' + PhoneFormat(receiver['phone']) + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Email : </label>' +
            '<label id="receiverEmail" class="values">' + receiver['email'] + '</label>' +
            '</p>' +
            '</fieldset>'
    );
}

/**
 * Ajoute les informations relatives à l'adresse de livraison aux détails de la commande.
 * @param $details
 * @param shippingAddress
 * @constructor
 */
function AddShippingAddressInfos($details, shippingAddress) {
    $details.append(
        '<fieldset>' +
            '<legend>Shipping informations</legend>' +
            '<p>' +
            '<label class="properties">Address : </label>' +
            '<label id="shippingAddress" class="values">' + AddressFormat(shippingAddress) + '</label>' +
            '</p>' +
            '</fieldset>'
    );
}

/**
 * Ajoute une commande à la liste de commandes.
 * @param order
 * @constructor
 */
function AddOrder(order) {
    $('#orders').append(
        '<div class="order ' + order['status'].toLowerCase() + '" data-id="' + order['id'] + '">' +
            '<label class="number">' + order['number'] + '</label>' +
            '<label class="status">' + order['status'] + '</label>' +
            '<label class="lastModifiedDate">' + order['lastModifiedDate'] + '</label>' +
            '</div>'
    );
}

/**
 * Ajoute une ligne à la commande.
 */
function AddLine($list, line) {
    $list.append(
        '<div class="line">' +
            '<div class="details">' +
            '<label class="quantity">' + line['quantity'] + '</label>' +
            '<label class="name">' + line['product']['name'] + '</label>' +
            '<label class="serial">' + line['serial'] + '</label>' +
            '</div>' +
            '</div>'
    );
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