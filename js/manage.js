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

    selectTabOrders();
    updateOrdersInfosByRangeOfDates();
    updateBanners();

    $('#btnTabOrders').click(function () {
        selectTabOrders();
    });

    $('#btnTabStores').click(function () {
        selectTabStores();
    });

    $('input.date').change(function () {
        updateOrdersInfosByRangeOfDates();
    });

    $('#number').change(function () {
        updateOrderInfosByNumber();
    });

    $('#username').change(function () {
        updateStoresByUsername();
    });

    $('#banners').change(function () {
        updateStoresByBannerId();
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
                CancelOrder(id);
            },
            "No": function () {
                $(this).dialog('close');
            }
        }
    });
});

$(document).on('click', 'div.order', function () {

    var $order = $(this);
    var $next = $order.next();

    if ($next.is('div.details')) {
        $next.stop().slideToggle();
    } else {
        addOrderDetails($order);
    }
});

$(document).on('click', 'div.store', function () {

    var $store = $(this);
    var $next = $store.next();

    if ($next.is('div.details')) {
        $next.stop().slideToggle();
    } else {
        addStoreDetails($store);
    }
});

$(document).on('click', 'input.btnConfirm', function () {

    var $details = $(this).closest('div.details');
    var $order = $details.prev();
    var $dialog = $('#confirmDialog');
    var $number = $dialog.find('label.orderNumber');

    $number.text($order.find('label.number').text());
    $number.data('order-id', $order.data('id'));
    $dialog.dialog('open');
});

$(document).on('click', 'input.btnCancel', function () {

    var $details = $(this).closest('div.details');
    var $order = $details.prev();
    var $dialog = $('#cancelDialog');
    var $number = $dialog.find('label.orderNumber');

    $number.text($order.find('label.number').text());
    $number.data('order-id', $order.data('id'));
    $dialog.dialog('open');
});

$(document).on('click', 'input.btnStoreOrders', function () {

    var $details = $(this).closest('div.details');
    var $store = $details.find('fieldset.contactInfos');

    window.location = 'orders.php?storeId=' + $store.data('id');
});

$(document).on('click', 'input.btnDetails', function () {

    var $details = $(this).closest('div.details');
    var $order = $details.prev();

    window.location = 'orderInfos.php?orderId=' + $order.data('id');
});

/**
 * Affiche le contenu de l'onglet : dernières commandes.
 */
function selectTabOrders() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabOrders').addClass('selected');
    $('#tabOrders').show();
}

/**
 * Affiche le contenu de l'onglet : magasins.
 */
function selectTabStores() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabStores').addClass('selected');
    $('#tabStores').show();
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

function CancelOrder(id) {
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

function updateBanners() {
    $.post('ajax/getBanners.php')
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data['banners']) {

                var banners = data['banners'];
                var $banners = $('#banners');

                $banners.find('option').remove();

                for (var i in banners) {
                    if (banners.hasOwnProperty(i)) {
                        var banner = banners[i];

                        if (banner.hasOwnProperty('id') &&
                            banner.hasOwnProperty('name')) {
                            $banners.append(
                                $('<option></option>')
                                    .val(banner['id'])
                                    .text(banner['name']));
                        }
                    }
                }

                $banners.trigger('change');

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

    $.post('ajax/getOrdersByNumber.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orders')) {

                $('div.details').remove();
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

function updateStoresByUsername() {
    var parameters = {
        'username': $('#username').val()
    };

    $.post('ajax/getStoresByUsername.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('stores')) {

                $('div.details').remove();
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
                                    addStoreInfos(store);
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
}
function updateStoresByBannerId() {
    var parameters = {
        'bannerId': $('#banners').find('option:selected').val()
    };

    $.post('ajax/getStoresByBannerId.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('stores')) {

                $('div.store').remove();
                $('div.details').remove();

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
                                    addStoreInfos(store);
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
}

function updateOrdersInfosByRangeOfDates() {
    var parameters = {
        'from': $('#from').val(),
        'to': $('#to').val()
    };

    $.post('ajax/getOrdersByRangeOfDates.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orders')) {

                $('div.details').remove();
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

/**
 * Ajoute les détails d'un magasin.
 * @param $store
 */
function addStoreDetails($store) {
    var parameters = {
        "storeId": $store.data('id')
    };

    $.post('ajax/getStoreDetails.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('store')) {

                var store = data['store'];
                var $details = $('<div class="details"></div>');
                var $buttons = $('<div class="buttons"></div>');

                if (store.hasOwnProperty('id') &&
                    store.hasOwnProperty('name') &&
                    store.hasOwnProperty('phone') &&
                    store.hasOwnProperty('email') &&
                    store.hasOwnProperty('user') &&
                    store.hasOwnProperty('address')) {
                    var user = store['user'];
                    var address = store['address'];

                    if (address.hasOwnProperty('details') &&
                        address.hasOwnProperty('city') &&
                        address.hasOwnProperty('zip') &&
                        address.hasOwnProperty('state') &&
                        address['state'].hasOwnProperty('name') &&
                        user.hasOwnProperty('id') &&
                        user.hasOwnProperty('username')) {
                        addStoreInfosToStoreDetails($details, store);
                    }
                }

                $buttons.append('<input class="btnStoreOrders" type="button" value="Store Orders"/>');

                $details.append($buttons);
                $details.hide().insertAfter($store).slideDown();

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
 * Ajoute les détails d'une commande.
 * @param $order
 */
function addOrderDetails($order) {
    var parameters = {
        "orderId": $order.data('id')
    };

    $.post('ajax/getOrderDetails.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('order')) {

                var order = data['order'];
                var $details = $('<div class="details"></div>');
                var $lines = $('<div class="lines"></div>');
                var $buttons = $('<div class="buttons"></div>');

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
                            addStoreInfosToOrderDetails($details, store);
                        }
                    }
                }

                if (order.hasOwnProperty('receiver')) {
                    var receiver = order['receiver'];

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        addReceiverInfosToOrderDetails($details, receiver);
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
                                addLineInfosToOrderDetails($lines, line);
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
                        default:
                            $buttons.append('<input class="btnDetails" type="button" value="More Details"/>');
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

function addStoreInfosToStoreDetails($details, store) {
    $details.append(
        '<fieldset class="contactInfos" data-id="' + store['id'] + '">' +
            '<legend>Contact Informations</legend>' +
            '<p>' +
            '<label class="properties">Phone : </label>' +
            '<label class="values">' + phoneFormat(store['phone']) + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Email : </label>' +
            '<label class="values">' + store['email'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Address : </label>' +
            '<label class="values">' + addressFormat(store['address']) + '</label>' +
            '</p>' +
            '</fieldset>'
    );
}

/**
 * Affiche les informations relatives au magasin.
 * @param $details
 * @param store
 */
function addStoreInfosToOrderDetails($details, store) {
    $details.append(
        '<fieldset class="storeInfos" data-id="' + store['id'] + '">' +
            '<legend>Store informations</legend>' +
            '<p>' +
            '<label class="properties">Name : </label>' +
            '<label class="values">' + store['name'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Phone : </label>' +
            '<label class="values">' + phoneFormat(store['phone']) + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Email : </label>' +
            '<label class="values">' + store['email'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Address : </label>' +
            '<label class="values">' + addressFormat(store['address']) + '</label>' +
            '</p>' +
            '</fieldset>'
    );
}

/**
 * Ajoute les informations relatives au receveur aux détails de la commande.
 * @param $details
 * @param receiver
 */
function addReceiverInfosToOrderDetails($details, receiver) {
    $details.append(
        '<fieldset class="receiverInfos" data-id="' + receiver['id'] + '">' +
            '<legend>Receiver informations</legend>' +
            '<p>' +
            '<label class="properties">Name : </label>' +
            '<label class="values">' + receiver['name'] + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Phone : </label>' +
            '<label class="values">' + phoneFormat(receiver['phone']) + '</label>' +
            '</p>' +
            '<p>' +
            '<label class="properties">Email : </label>' +
            '<label class="values">' + receiver['email'] + '</label>' +
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
        '<fieldset class="shippingAddressInfos" data-id="' + shippingAddress['id'] + '">' +
            '<legend>Shipping informations</legend>' +
            '<p>' +
            '<label class="properties">Address : </label>' +
            '<label class="values">' + addressFormat(shippingAddress) + '</label>' +
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
            '<label class="lastModification"> ' +
            'By <b>' + order['lastModificationByUser']['username'] + '</b> ' +
            'at <i>' + dateFormat(order['lastModificationDate']) +
            '</i></label>' +
            '</div>'
    );
}

/**
 * Ajoute une ligne à la commande.
 */
function addLineInfosToOrderDetails($list, line) {
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
function addStoreInfos(store) {
    $('#stores').append(
        '<div class="store" data-id="' + store['id'] + '">' +
            '<label class="name">' + store['name'] + '</label>' +
            '<label class="username"> - ' + store['user']['username'] + '</label>' +
            '</div>'

    );
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