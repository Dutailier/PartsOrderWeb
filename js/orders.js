$(document).ready(function () {

    $("#from").datepicker({
        maxDate: '0',
        onClose: function (selectedDate) {
            $("#to").datepicker("option", "minDate", selectedDate);
        }
    });

    $("#to").datepicker({
        maxDate: '+1d',
        onClose: function (selectedDate) {
            $("#from").datepicker("option", "maxDate", selectedDate);
        }
    });

    $('#from').datepicker('setDate', '0');
    $('#to').datepicker('setDate', '+1d');

    getStoreInfos();
    updateOrdersInfosByRangeOfDates();

    $('input.date').change(function () {
        updateOrdersInfosByRangeOfDates();
    });

    $('#number').change(function () {
        updateOrderInfosByNumber();
    });

    $('#confirmDialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {
                var $orderNumber = $(this).find('label.orderNumber');
                var id = $orderNumber.data('order-id');
                confirmOrder(id);
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
                var $orderNumber = $(this).find('label.orderNumber');
                var id = $orderNumber.data('order-id');
                cancelOrder(id);
            },
            "No": function () {
                $(this).dialog('close');
            }
        }
    });
});

$(document).on('click', 'div.order', function () {

    var $order = $(this);

    if ($order.next().is('div.orderDetails')) {
        $order.next().stop().slideToggle();
    } else {
        addOrderDetails($order);
    }
});

/**
 * Retourne les détails d'une commande.
 * @param $order
 */
function addOrderDetails($order) {
    var parameters = {
        "orderId": $order.data('id')
    };

    $.post('ajax/getOrderInfos.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('order')) {

                var order = data['order'];
                var $details = $('<div class="orderDetails"></div>');
                var $lines = $('<div class="lines"></div>');
                var $buttons = $('<div class="buttons"></div>');

                if (order.hasOwnProperty('receiver')) {
                    var receiver = order['receiver'];

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        addReceiverInfosToOrderDetails($details, receiver);
                    }
                }

                if (order.hasOwnProperty('shippingAddress')) {
                    var shippingAddress = order['shippingAddress'];

                    if (shippingAddress.hasOwnProperty('details') &&
                        shippingAddress.hasOwnProperty('city') &&
                        shippingAddress.hasOwnProperty('zip') &&
                        shippingAddress.hasOwnProperty('state') &&
                        shippingAddress['state'].hasOwnProperty('name')) {
                        addShippingAddressInfosToOrderDetails($details, shippingAddress);
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
                                addLineInfosToLinesOfOrderDetails($lines, line);
                        }
                    }
                }

                if (order.hasOwnProperty('status')) {
                    //noinspection FallthroughInSwitchStatementJS
                    switch (order['status']) {
                        case 'Pending' :
                            $buttons.append('<input class="btnConfirm" type="button" value="Confirm"/>');
                        case 'Confirmed':
                            $buttons.append('<input class="btnCancel" type="button" value="Cancel"/>');
                            break;
                    }
                }

                $details.append($lines);
                $details.append($buttons);
                $details.hide().insertAfter($order).slideDown();

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

    var $details = $(this).closest('div.orderDetails');
    var $order = $details.prev();
    var $dialog = $('#confirmDialog');
    var $number = $dialog.find('label.orderNumber');

    $number.text($order.find('label.number').text());
    $number.data('order-id', $order.data('id'));
    $dialog.dialog('open');
});

$(document).on('click', 'input.btnCancel', function () {

    var $details = $(this).closest('div.orderDetails');
    var $order = $details.prev();
    var $dialog = $('#cancelDialog');
    var $number = $dialog.find('label.orderNumber');

    $number.text($order.find('label.number').text());
    $number.data('order-id', $order.data('id'));
    $dialog.dialog('open');
});

function updateOrdersInfosByRangeOfDates() {
    var parameters = {
        'from': $('#from').val(),
        'to': $('#to').val()
    };

    $.post('ajax/getOrdersByRangeOfDatesStoreConnected.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orders')) {

                $('div.orderDetails').remove();
                $('div.order').remove();

                var orders = data['orders'];

                for (var i in orders) {
                    if (orders.hasOwnProperty(i)) {
                        var order = orders[i];

                        if (order.hasOwnProperty('status') &&
                            order.hasOwnProperty('id') &&
                            order.hasOwnProperty('number') &&
                            order.hasOwnProperty('lastModificationByUser') &&
                            order['lastModificationByUser'].hasOwnProperty('username') &&
                            order.hasOwnProperty('lastModificationDate')) {
                            addOrderInfos(order);
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
}

function updateOrderInfosByNumber() {
    var parameters = {
        'number': $('#number').val()
    };

    $.post('ajax/getOrdersByNumberStoreConnected.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orders')) {

                $('div.orderDetails').remove();
                $('div.order').remove();

                var orders = data['orders'];

                for (var i in orders) {
                    if (orders.hasOwnProperty(i)) {
                        var order = orders[i];

                        if (order.hasOwnProperty('status') &&
                            order.hasOwnProperty('id') &&
                            order.hasOwnProperty('number') &&
                            order.hasOwnProperty('lastModificationByUser') &&
                            order['lastModificationByUser'].hasOwnProperty('username') &&
                            order.hasOwnProperty('lastModificationDate')) {
                            addOrderInfos(order);
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
}

function getStoreInfos() {
    $.post('ajax/getStoreConnected.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('store')) {

                var store = data['store'];

                if (store.hasOwnProperty('id') &&
                    store.hasOwnProperty('name') &&
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

            } else if (data.hasOwnProperty('message')) {
                alert(data['message']);

            } else {
                alert('The result of the server is unreadable.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        });
}
function confirmOrder(id) {
    var parameters = {
        "orderId": id
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
}
function cancelOrder(id) {
    var parameters = {
        "orderId": id
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
}

/**
 * Ajoute les informations relatives au receveur aux détails de la commande.
 * @param $details
 * @param receiver
 */
function addReceiverInfosToOrderDetails($details, receiver) {
    $details.append(
        '<fieldset class="receiverInfos">' +
            '<legend>Receiver informations</legend>' +
            '<p>' +
            '<label class="properties">Name : </label>' +
            '<label id="receiverName" class="values">' + receiver['name'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Phone : </label>' +
            '<label id="receiverPhone" class="values">' + phoneFormat(receiver['phone']) + '</label>' +
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
 */
function addShippingAddressInfosToOrderDetails($details, shippingAddress) {
    $details.append(
        '<fieldset class="shippingAddressInfos">' +
            '<legend>Shipping informations</legend>' +
            '<p>' +
            '<label class="properties">Address : </label>' +
            '<label id="shippingAddress" class="values">' + addressFormat(shippingAddress) + '</label>' +
            '</p>' +
            '</fieldset>'
    );
}

/**
 * Ajoute une commande à la liste de commandes.
 * @param order
 */
function addOrderInfos(order) {
    $('#orders').append(
        '<div class="order ' + order['status'].toLowerCase() + '" data-id="' + order['id'] + '">' +
            '<label class="number">' + order['number'] + '</label>' +
            '<label class="status">' + order['status'] + '</label>' +
            '<label class="lastModification"> By <b>' + order['lastModificationByUser']['username'] + '</b> at <i>' + dateFormat(order['lastModificationDate']) + '</i></label>' +
            '</div>'
    );
}

/**
 * Ajoute une ligne à la commande.
 */
function addLineInfosToLinesOfOrderDetails($list, line) {
    $list.append(
        '<div class="line">' +
            '<div class="lineDetails">' +
            '<label class="quantity">' + line['quantity'] + '</label>' +
            '<label class="name">' + line['product']['name'] + '</label>' +
            '<label class="serial">' + line['serial'] + '</label>' +
            '</div>' +
            '</div>'
    );
}

/**
 * Affiche les informations relatives au magasin.
 * @param store
 */
function updateStoreInfos(store) {
    $('#storeInfos').data('id', store['id']);
    $('#storeName').text(store['name']);
    $('#storePhone').text(phoneFormat(store['phone']));
    $('#storeEmail').text(store['email']);
    $('#storeAddress').text(addressFormat(store['address']));
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
 * Transforme 2013-06-07 10:24:15.227 pour 2013-06-07 10:24.
 * @param date
 * @returns {string}
 */
function dateFormat(date) {
    return date.substring(0, 16);
}