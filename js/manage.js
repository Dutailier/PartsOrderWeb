$(document).ready(function () {

    $("#orderFrom").datepicker({
        maxDate: '0',
        onClose: function (selectedDate) {
            $("#orderTo").datepicker("option", "minDate", selectedDate);
        }
    });

    $("#orderTo").datepicker({
        maxDate: '0',
        onClose: function (selectedDate) {
            $("#orderFrom").datepicker("option", "maxDate", selectedDate);
        }
    });

    $("#logFrom").datepicker({
        maxDate: '0',
        onClose: function (selectedDate) {
            $("#logTo").datepicker("option", "minDate", selectedDate);
        }
    });

    $("#logTo").datepicker({
        maxDate: '0',
        onClose: function (selectedDate) {
            $("#logFrom").datepicker("option", "maxDate", selectedDate);
        }
    });

    $('#orderFrom, #logFrom').datepicker('setDate', '-1w');
    $('#orderTo, #logTo').datepicker('setDate', '0');

    //noinspection FallthroughInSwitchStatementJS
    switch ($.QueryString['tab']) {
        case 'stores' :
            selectTabStores();
            break;
        case 'logs' :
            selectTabLogs();
            break;
        case 'orders' :
        default:
            selectTabOrders();
    }

    updateOrdersByRangeOfDates();
    updateBanners();
    updateLogsByRangeOfDates();

    $('#btnTabOrders').click(function () {
        selectTabOrders();
    });

    $('#btnTabStores').click(function () {
        selectTabStores();
    });

    $('#btnTabLogs').click(function () {
        selectTabLogs();
    });

    $('#orderFilters').find('input.date').change(function () {
        updateOrdersByRangeOfDates();
    });

    $('#logFilter').find('input.date').change(function () {
        updateLogsByRangeOfDates();
    });

    $('#orderKeyWords').keyup(function () {
        filterOrdersByKeyWords();
    });

    $('#storeKeyWords').keyup(function () {
        filterStoresByKeyWords();
    });

    $('#logKeyWords').keyup(function () {
        filterLogsByKeyWords();
    });

    $('#banners').change(function () {
        updateStoresByBannerId();
    });

    $('#btnAddStore').click(function () {
        var bannerId = $('#banners').find('option:selected').val();

        window.location = 'storeInfos.php?bannerId=' + bannerId;
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

$(document).on('click', 'div.order > div.infos', function () {

    var $order = $(this).closest('div.order');
    var $details = $order.children('div.details');

    if ($details.length > 0) {
        $details.stop().slideToggle();
    } else {
        addDetailsToOrder($order);
    }
});

$(document).on('click', 'div.store > div.infos', function () {

    var $store = $(this).closest('div.store');
    var $details = $store.children('div.details');

    if ($details.length > 0) {
        $details.stop().slideToggle();
    } else {
        addDetailsToStore($store);
    }
});

$(document).on('click', 'input.btnConfirm', function () {

    var $order = $(this).closest('div.order');
    var $dialog = $('#confirmDialog');
    var $number = $dialog.find('label.orderNumber');

    $number.text($order.find('label.number').text());
    $number.data('order-id', $order.data('id'));
    $dialog.dialog('open');
});

$(document).on('click', 'input.btnCancel', function () {

    var $order = $(this).closest('div.order');
    var $dialog = $('#cancelDialog');
    var $number = $dialog.find('label.orderNumber');

    $number.text($order.find('label.number').text());
    $number.data('order-id', $order.data('id'));
    $dialog.dialog('open');
});

$(document).on('click', 'input.btnStoreOrders', function () {

    var $store = $(this).closest('div.store');

    window.location = 'orders.php?storeId=' + $store.data('id');
});

$(document).on('click', 'input.btnDetails', function () {

    var $order = $(this).closest('div.order');

    window.location = 'orderInfos.php?orderId=' + $order.data('id');
});

$(document).on('click', 'input.btnStoreEdit', function () {

    var $store = $(this).closest('div.store');
    var bannerId = $('#banners').find('option:selected').val();

    window.location = 'storeInfos.php?storeId=' + $store.data('id') + '&bannerId=' + bannerId;
});

$(document).on('click', 'div.log > label.orderNumber', function () {

    var $log = $(this).closest('div.log');

    window.location = 'orderInfos.php?orderId=' + $log.data('order-id');
});

$(document).on('click', 'input.btnStoreDelete', function () {
    var $store = $(this).closest('div.store');

    var parameters = {
        "storeId": $store.data('id')
    };

    $.post('ajax/deleteStore.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {

                $store.remove();

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

/**
 * Affiche le contenu de l'onglet : logs.
 */
function selectTabLogs() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabLogs').addClass('selected');
    $('#tabLogs').show();
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

function updateLogsByRangeOfDates() {

    var parameters = {
        'from': $('#logFrom').val(),
        'to': $('#logTo').val()
    };

    $.post('ajax/getLogsByRangeOfDates.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('logs')) {

                $('div.log').remove();
                var logs = data['logs'];

                for (var i in logs) {
                    if (logs.hasOwnProperty(i)) {
                        var log = logs[i];

                        if (log.hasOwnProperty('id') &&
                            log.hasOwnProperty('event') &&
                            log.hasOwnProperty('datetime') &&
                            log.hasOwnProperty('order') &&
                            log['order'].hasOwnProperty('number') &&
                            log.hasOwnProperty('user') &&
                            log['user'].hasOwnProperty('username')) {
                            addLog(log);
                        }
                    }
                }

                filterLogsByKeyWords();

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

function filterOrdersByKeyWords() {
    $('div.order').hide();

    $('div.order label').each(function (index, lbl) {
        var $lbl = $(lbl);
        var keyWords = '(' + $('#orderKeyWords').val() + ')';

        $lbl.html(
            $lbl.text().replace(
                new RegExp(keyWords, "gi"),
                function (match) {
                    $lbl.closest('div.order').show();
                    return '<span class="highlight">' + match + '</span>';
                }
            )
        );
    });
}

function filterStoresByKeyWords() {
    $('div.store').hide();

    $('div.store label').each(function (index, lbl) {
        var $lbl = $(lbl);
        var keyWords = '(' + $('#storeKeyWords').val() + ')';

        $lbl.html(
            $lbl.text().replace(
                new RegExp(keyWords, "gi"),
                function (match) {
                    $lbl.closest('div.store').show();
                    return '<span class="highlight">' + match + '</span>';
                }
            )
        );
    });
}

function filterLogsByKeyWords() {
    $('div.log').hide();

    $('div.log label').each(function (index, lbl) {
        var $lbl = $(lbl);
        var keyWords = '(' + $('#logKeyWords').val() + ')';

        $lbl.html(
            $lbl.text().replace(
                new RegExp(keyWords, "gi"),
                function (match) {
                    $lbl.closest('div.log').show();
                    return '<span class="highlight">' + match + '</span>';
                }
            )
        );
    });
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
                                    var $store = $('<div class="store" data-id="' + store['id'] + '"></div>');
                                    addInfosToStore(store, $store);
                                    $store.appendTo('#stores');
                                }
                            }
                        }
                    }
                }

                filterStoresByKeyWords();

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

function updateOrdersByRangeOfDates() {
    var parameters = {
        'from': $('#orderFrom').val(),
        'to': $('#orderTo').val()
    };

    $.post('ajax/getOrdersByRangeOfDates.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orders')) {

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
                            var $order = $('<div class="order" data-id="' + order['id'] + '"></div>');
                            addInfosToOrder(order, $order);
                            $order.appendTo('#orders');
                        }
                    }
                }

                filterOrdersByKeyWords();

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
function addDetailsToStore($store) {
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

                $buttons.append('<input class="btnStoreDelete" type="button" value="Delete"/>');
                $buttons.append('<input class="btnStoreEdit" type="button" value="Edit"/>');
                $buttons.append('<input class="btnStoreOrders" type="button" value="Orders"/>');

                $details.append($buttons);
                $details.hide().appendTo($store).slideDown();

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
function addDetailsToOrder($order) {
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
                $details.hide().appendTo($order).slideDown();

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
 * Ajoute les informations sommaires de la commande.
 * @param order
 * @param $order
 */
function addInfosToOrder(order, $order) {
    $order.append(
        '<div class="infos ' + order['status'].toLowerCase() + '">' +
            '<label class="number">' + order['number'] + '</label>' +
            '<label class="status">' + order['status'] + '</label>' +
            '<div class="date"> ' +
            'By <label class="username">' + order['lastModificationByUser']['username'] + '</label> ' +
            'at <label class="datetime">' + dateFormat(order['lastModificationDate']) + '</label>' +
            '</div>' +
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
 * Ajoute les informations sommaires d'un magasin.
 * @param store
 * @param $store
 */
function addInfosToStore(store, $store) {
    $store.append(
        '<div class="infos">' +
            '<label class="name">' + store['name'] + '</label>' +
            '<label class="username">' + store['user']['username'] + '</label>' +
            '</div>'

    );
}

function addLog(log) {
    $('#logs').append(
        '<div class="log" data-id="' + log['id'] + '" data-order-id="' + log['order']['id'] + '">' +
            '<label class="orderNumber">' + log['order']['number'] + '</label>' +
            '<label class="event">' + log['event'] + '</label>' +
            '<div class="date">' +
            'By <label class="username">' + log['user']['username'] + '</label> at <label class="datetime">' + dateFormat(log['datetime']) + '</label>' +
            '</div>' +
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