$(document).ready(function () {

    $("#from").datepicker({
        dateFormat: 'yy-mm-dd',
        onClose: function (selectedDate) {
            $("#to").datepicker("option", "minDate", selectedDate);
        }
    });

    $("#to").datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: '+1d',
        onClose: function (selectedDate) {
            $("#from").datepicker("option", "maxDate", selectedDate);
        }
    });

    $('#from').datepicker('setDate', '-1w');
    $('#to').datepicker('setDate', '+1d');

    SelectTabLastOrders();

    $('#btnTabOrders').click(function () {
        SelectTabLastOrders();
    });

    $('#btnRangeOfDates').click(function () {
        UpdateOrdersByRangeOfDates();
    });

    $('#btnSearchNumber').click(function () {
        var parameters = {
            'number': $('#number').val()
        };

        $.post('ajax/getOrdersByNumber.php', parameters)
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
                                AddOrderInfos(order);
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

    $('#btnSearchUsername').click(function () {
        var parameters = {
            'username': $('#username').val()
        };

        $.post('ajax/getStoresByUsername.php', parameters)
            .done(function (data) {
                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('stores')) {

                    $('div.storeDetails').remove();
                    $('div.store').remove();

                    var stores = data['stores'];

                    for (var i in stores) {
                        if (stores.hasOwnProperty(i)) {
                            var store = stores[i];

                            if (store.hasOwnProperty('id') &&
                                store.hasOwnProperty('name') &&
                                store.hasOwnProperty('phone') &&
                                store.hasOwnProperty('email') &&
                                store.hasOwnProperty('address') &&
                                store.hasOwnProperty('user')) {
                                var address = store['address'];
                                var user = store['user'];

                                if (address.hasOwnProperty('details') &&
                                    address.hasOwnProperty('city') &&
                                    address.hasOwnProperty('zip') &&
                                    address.hasOwnProperty('state') &&
                                    address['state'].hasOwnProperty('name')) {

                                    if (user.hasOwnProperty('id') &&
                                        user.hasOwnProperty('username')) {
                                        AddStore(store);
                                    }
                                }
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

    $('#btnTabStores').click(function () {
        SelectTabStores();
    });

    $('#banner').change(function () {
        var parameters = {
            'bannerId': $('#banner > option:selected').val()
        };

        $.post('ajax/getStoresByBannerId.php', parameters)
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('stores')) {

                    $('#stores').show();
                    $('div.store').remove();

                    var stores = data['stores'];

                    for (var i in stores) {
                        if (stores.hasOwnProperty(i)) {
                            var store = stores[i];

                            if (store.hasOwnProperty('id') &&
                                store.hasOwnProperty('name') &&
                                store.hasOwnProperty('phone') &&
                                store.hasOwnProperty('email') &&
                                store.hasOwnProperty('address') &&
                                store.hasOwnProperty('user')) {
                                var address = store['address'];
                                var user = store['user'];

                                if (address.hasOwnProperty('details') &&
                                    address.hasOwnProperty('city') &&
                                    address.hasOwnProperty('zip') &&
                                    address.hasOwnProperty('state') &&
                                    address['state'].hasOwnProperty('name')) {

                                    if (user.hasOwnProperty('id') &&
                                        user.hasOwnProperty('username')) {
                                        AddStore(store);
                                    }
                                }
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

    $('#confirmDialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {
                var parameters = {
                    "orderId": $(this).find('label.orderNumber').data('order-id')
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
                    "orderId": $(this).find('label.orderNumber').data('order-id')
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
            },
            "No": function () {
                $(this).dialog('close');
            }
        }
    });
});

/**
 * Affiche le contenu de l'onglet : magasins.
 * @constructor
 */
function SelectTabStores() {
    $('#btnTabOrders').removeClass('selected');
    $('#tabOrders').hide();

    $('#btnTabStores').addClass('selected');
    $('#tabStores').show();
    $('#banners').show();
    $('#stores').hide();

    $.post('ajax/getBanners.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data['banners']) {

                $('#banner > option').remove();

                var banners = data['banners'];

                for (var i in banners) {
                    if (banners.hasOwnProperty(i)) {
                        var banner = banners[i];

                        if (banner.hasOwnProperty('id') &&
                            banner.hasOwnProperty('name')) {
                            $('#banner').append(
                                $('<option></option>')
                                    .val(banner['id'])
                                    .text(banner['name']));

                            $('#banner').trigger('change');
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

function UpdateOrdersByRangeOfDates() {
    var parameters = {
        'from': $('#from').val(),
        'to': $('#to').val()
    };

    $.post('ajax/getOrdersByRangeOfDates.php', parameters)
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
                            AddOrderInfos(order);
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
/**
 * Affiche le contenu de l'onglet : dernières commandes.
 * @constructor
 */
function SelectTabLastOrders() {
    $('#btnTabOrders').addClass('selected');
    $('#tabOrders').show();

    $('#btnTabStores').removeClass('selected');
    $('#tabStores').hide();

    UpdateOrdersByRangeOfDates();
}

$(document).on('click', 'div.order', function () {

    var $order = $(this);

    if ($order.next().is('div.orderDetails')) {
        $order.next().stop().slideToggle();
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
                var $details = $('<div class="orderDetails"></div>');
                var $lines = $('<div class="lines"></div>');
                var $buttons = $('<div class="buttons"></div>');

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

                if (order.hasOwnProperty('store')) {
                    var store = order['store'];

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
                            AddStoreInfos($details, store);
                        }
                    }
                }

                if (order.hasOwnProperty('receiver')) {
                    var receiver = order['receiver'];

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        AddReceiverInfos($details, receiver);
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
                                AddLineInfos($lines, line);
                        }
                    }
                }

                if (order.hasOwnProperty('status')) {
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

/**
 * Affiche les informations relatives au magasin.
 * @param $details
 * @param store
 * @constructor
 */
function AddStoreInfos($details, store) {
    $details.append(
        '<fieldset class="storeInfos">' +
            '<legend>Store informations</legend>' +
            '<p>' +
            '<label class="properties">Name : </label>' +
            '<label id="receiverName" class="values">' + store['name'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Phone : </label>' +
            '<label id="receiverPhone" class="values">' + PhoneFormat(store['phone']) + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Email : </label>' +
            '<label id="receiverEmail" class="values">' + store['email'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Address : </label>' +
            '<label id="storeAddress" class="values">' + AddressFormat(store['address']) + '</label>' +
            '</p>' +
            '</fieldset>'
    );
}

/**
 * Ajoute les informations relatives au receveur aux détails de la commande.
 * @param $details
 * @param receiver
 * @constructor
 */
function AddReceiverInfos($details, receiver) {
    $details.append(
        '<fieldset class="receiverInfos">' +
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
        '<fieldset class="shippingAddressInfos">' +
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
function AddOrderInfos(order) {
    $('#orders').append(
        '<div class="order ' + order['status'].toLowerCase() + '" data-id="' + order['id'] + '">' +
            '<label class="number">' + order['number'] + '</label>' +
            '<label class="status">' + order['status'] + '</label>' +
            '<label class="lastModification"> By <b>' + order['lastModificationByUser']['username'] + '</b> at <i>' + DateFormat(order['lastModificationDate']) + '</i></label>' +
            '</div>'
    );
}

/**
 * Ajoute une ligne à la commande.
 */
function AddLineInfos($list, line) {
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
 * @constructor
 */
function AddStore(store) {
    $('#stores').append(
        '<div class="store" data-id="' + store['id'] + '">' +
            '<div class="storeDetails">' +
            '<label class="name">' + store['name'] + '</label>' +
            '<label class="username"> - ' + store['user']['username'] + '</label>' +
            '</div>' +
            '</div>'

    );
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
 * Transforme 2013-06-07 10:24:15.227 pour 2013-06-07 10:24:15.
 * @param date
 * @returns {string}
 * @constructor
 */
function DateFormat(date) {
    return date.substring(0, 19);
}