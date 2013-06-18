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

    $('#orderFrom, #logFrom').datepicker('setDate', '-1m');
    $('#orderTo, #logTo').datepicker('setDate', '0');

    //noinspection FallthroughInSwitchStatementJS
    switch ($.QueryString['tab']) {
        case 'logs' :
            selectTabLogs();
            break;
        case 'orders' :
        default:
            selectTabOrders();
    }

    getStoreInfos();
    updateOrdersByRangeOfDates();
    updateLogsByRangeOfDates();

    $('#btnTabOrders').click(function () {
        selectTabOrders();
    });

    $('#btnTabLogs').click(function () {
        selectTabLogs();
    });

    $('#ordersFilters').find('input.date').change(function () {
        updateOrdersByRangeOfDates();
    });

    $('#logsFilters').find('input.date').change(function () {
        updateLogsByRangeOfDates();
    });

    $('#orderKeyWords').keyup(function () {
        filterOrdersByKeyWords();
    });

    $('#logKeyWords').keyup(function () {
        filterLogsByKeyWords();
    });

    $('#btnBackManager').click(function () {
        window.location = 'manager.php?tab=stores';
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

$(document).on('click', 'div.order > div.infos', function () {

    var $order = $(this).closest('div.order');
    var $details = $order.children('div.details');

    if ($details.length > 0) {
        $details.stop().slideToggle();
    } else {
        addDetailsToOrder($order);
    }
});

$(document).on('click', 'div.log > label.orderNumber', function () {

    var $log = $(this).closest('div.log');

    window.location = 'orderInfos.php?orderId=' + $log.data('order-id');
});

/**
 * Affiche le contenu de l'onglet : informations de la commande.
 */
function selectTabOrders() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabOrders').addClass('selected');
    $('#tabOrders').show();
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

/**
 * Retourne les détails d'une commande.
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

$(document).on('click', 'input.btnDetails', function () {

    var $order = $(this).closest('div.order');

    window.location = 'orderInfos.php?orderId=' + $order.data('id');
});

function updateOrdersByRangeOfDates() {
    var parameters = {
        'from': $('#orderFrom').val(),
        'to': $('#orderTo').val(),
        'storeId': $.QueryString['storeId']
    };

    $.post('ajax/getOrdersByRangeOfDatesAndStoreId.php', parameters)
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

function updateLogsByRangeOfDates() {

    var parameters = {
        'from': $('#logFrom').val(),
        'to': $('#logTo').val(),
        'storeId': $.QueryString['storeId']
    };

    $.post('ajax/getLogsByRangeOfDatesAndStoreId.php', parameters)
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

function getStoreInfos() {
    var parameters = {
        'storeId': $.QueryString['storeId']
    };

    $.post('ajax/getStoreInfos.php', parameters)
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

function addLog(log) {
    $('#logs').append(
        '<div class="log" data-id="' + log['id'] + '" data-order-id="' + log['order']['id'] + '">' +
            '<label class="orderNumber">' + log['order']['number'] + '</label>' +
            '<label class="event">' + log['event'] + '</label>' +
            '<div class="date">' +
            'By <label class="username">' + log['user']['username'] + '</label> at <label class="creationDate">' + dateFormat(log['datetime']) + '</label>' +
            '</div>' +
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