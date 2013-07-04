// Évènements définis une fois le document HTML complètement généré.

$(document).ready(function () {

    $('#btnTabOrders').click(function () {
        selectTabOrders();
    });

    $('#btnTabLogs').click(function () {
        selectTabLogs();
    });

    $('#btnExport').click(function () {
        var from = $('#orderFrom').val();
        var to = $('#orderTo').val();

        window.open('export.php?from=' + from + '&to=' + to);
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

    $('#confirmDialog').dialog({
        title: 'Order confirmation',
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        width: 360,
        height: 200,
        buttons: [
            {
                id: 'confirmYes',
                text: 'Yes',
                click: function () {
                    $('#confirmYes, #confirmNo').button('disable');
                    confirmOrder();
                }
            },
            {
                id: 'confirmNo',
                text: 'No',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ]
    });

    $('#cancelDialog').dialog({
        title: 'Order cancelation',
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        width: 360,
        height: 200,
        buttons: [
            {
                id: 'cancelYes',
                text: 'Yes',
                click: function () {
                    $('#cancelYes, #cancelNo').button('disable');
                    cancelOrder();
                }
            },
            {
                id: 'cancelNo',
                text: 'No',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ]
    });

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
});

// Évènements liés à des éléments générés.

$(document).on('click', 'div.order > div.infos', function () {

    var $order = $(this).closest('div.order');
    addDetailsToOrder($order);
});

$(document).on('click', 'div.log > label.orderNumber', function () {

    var $log = $(this).closest('div.log');

    window.location = 'orderInfos.php?orderId=' + $log.data('order-id');
});

$(document).on('click', 'input.btnDetails', function () {

    var $order = $(this).closest('div.order');

    window.location = 'orderInfos.php?orderId=' + $order.data('id');
});

$(document).on('click', 'input.btnConfirm', function () {
    var $order = $(this).closest('div.order');
    var $dialog = $('#confirmDialog');
    var $orderNumber = $dialog.find('label.orderNumber');

    $('#orders').children('div.order').removeAttr('selected');
    $order.attr('selected', 'selected');
    $orderNumber.text($order.find('label.number').text());
    $dialog.dialog('open');
});

$(document).on('click', 'input.btnCancel', function () {
    var $order = $(this).closest('div.order');
    var $dialog = $('#cancelDialog');
    var $orderNumber = $dialog.find('label.orderNumber');

    $('#orders').children('div.order').removeAttr('selected');
    $order.attr('selected', 'selected');
    $orderNumber.text($order.find('label.number').text());
    $dialog.dialog('open');
});

/**
 * Affiche le contenu de l'onglet : commandes.
 */
function selectTabOrders() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabOrders').addClass('selected');
    $('#tabOrders').show();

    if ($('div.order').length == 0 &&
        $('#ordersLoader').is(':hidden')) {
        updateOrdersByRangeOfDates();
    }
}

/**
 * Affiche le contenu de l'onglet : logs.
 */
function selectTabLogs() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabLogs').addClass('selected');
    $('#tabLogs').show();

    if ($('div.log').length == 0 &&
        $('#logsLoader').is(':hidden')) {
        updateLogsByRangeOfDates();
    }
}

/**
 * Ajoute les détails d'une commande.
 * @param $order
 */
function addDetailsToOrder($order) {

    var $infos = $order.children('div.infos');

    var parameters = {
        "orderId": $order.data('id')
    };

    $infos.click(false);
    $infos.animate({'opacity': 0.5});
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
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
        .always(function () {
            $infos.animate({'opacity': 1});
            $infos.click(function () {
                $order.children('div.details').stop().slideToggle();
            })
        })
}

/**
 * Met à jour les commandes par interval de dates.
 */
function updateOrdersByRangeOfDates() {
    var parameters = {
        'from': $('#orderFrom').val(),
        'to': $('#orderTo').val(),
        'storeId': $.QueryString['storeId']
    };

    $('div.order').hide();
    $('#ordersEmpty').hide();
    $('#ordersLoader').show();
    $('#tabOrders').find('ul.pagination').remove();
    $('#ordersFilters').find('input').attr('disabled', 'disabled');

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
                            order.hasOwnProperty('lastModificationByUsername') &&
                            order.hasOwnProperty('lastModificationDate')) {
                            var $order = $('<div class="order" data-id="' + order['id'] + '"></div>');
                            addInfosToOrder(order, $order);
                            $order.appendTo('#orders');
                        }
                    }
                }

                filterOrdersByKeyWords();

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});
                $('div.order').show();

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                $('div.order').show();
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
            $('div.order').show();
        })
        .always(function () {
            $('#ordersLoader').hide();
            $('#ordersFilters').find('input').removeAttr('disabled');
        })
}

/**
 * Filtre les commandes par les mots clés recherchés.
 */
function filterOrdersByKeyWords() {
    var $orders = $('div.order');

    $orders.hide();
    $orders.removeClass('found');

    $orders.children('div.infos').find('label').each(function (index, lbl) {
        var $lbl = $(lbl);
        var keyWords = '(' + $('#orderKeyWords').val() + ')';

        $lbl.html(
            $lbl.text().replace(
                new RegExp(keyWords, "gi"),
                function (match) {
                    $lbl.closest('div.order').addClass('found');

                    return '<span class="highlight">' + match + '</span>';
                }
            )
        );
    });

    var $found = $('div.order.found');
    $found.show();

    if ($found.length > 0) {
        $('#ordersEmpty').hide();
    } else {
        $('#ordersEmpty').show();
    }

    paginate($('#orders'), $found, 10);
}

/**
 * Filtre les logs par mots clés recherchés.
 */
function filterLogsByKeyWords() {
    var $logs = $('div.log');

    $logs.hide();
    $logs.removeClass('found');

    $logs.find('label').each(function (index, lbl) {
        var $lbl = $(lbl);
        var keyWords = '(' + $('#logKeyWords').val() + ')';

        $lbl.html(
            $lbl.text().replace(
                new RegExp(keyWords, "gi"),
                function (match) {
                    $lbl.closest('div.log').addClass('found');
                    return '<span class="highlight">' + match + '</span>';
                }
            )
        );
    });

    var $found = $('div.log.found');
    $found.show();

    if ($found.length > 0) {
        $('#logsEmpty').hide();
    } else {
        $('#logsEmpty').show();
    }

    paginate($('#logs'), $found, 10);
}

/**
 * Filtre les logs par interval de dates.
 */
function updateLogsByRangeOfDates() {

    var parameters = {
        'from': $('#logFrom').val(),
        'to': $('#logTo').val(),
        'storeId': $.QueryString['storeId']
    };

    $('div.log').hide();
    $('#logsEmpty').hide();
    $('#logsLoader').show();
    $('#tabLogs').find('ul.pagination').remove();
    $('#logsFilters').find('input').attr('disabled', 'disabled');

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
                            log.hasOwnProperty('username')) {
                            addLog(log);
                        }
                    }
                }

                filterLogsByKeyWords();

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});
                $('div.log').show();

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                $('div.log').show();
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
            $('div.log').show();
        })
        .always(function () {
            $('#logsLoader').hide();
            $('#logsFilters').find('input').removeAttr('disabled');
        })
}

/**
 * Récupère les informations relatives au magasin.
 */
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
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        });
}

/**
 * Confirme une commande.
 */
function confirmOrder() {

    var $order = $('#orders').children('div.order[selected]');

    var parameters = {
        "orderId": $order.data('id')
    };

    $.post('ajax/confirmOrder.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {
                updateOrdersByRangeOfDates();

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
        .always(function() {
            $('#confirmDialog').dialog('close');
        })
}

/**
 * Annule une commande.
 */
function cancelOrder() {
    var $order = $('#orders').children('div.order[selected]');

    var parameters = {
        "orderId": $order.data('id')
    };

    $.post('ajax/cancelOrder.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {
                updateOrdersByRangeOfDates();

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
        .always(function() {
            $('#cancelDialog').dialog('close');
        })
}

/**
 * Paginer une liste d'item.
 * @param $container
 * @param $items
 * @param countItemsByPage
 */
function paginate($container, $items, countItemsByPage) {

    $container.find('ul.pagination').remove();

    var countItems = $items.length;
    var countPages = Math.ceil(countItems / countItemsByPage);

    $($items).hide();
    $items.slice(0, countItemsByPage).show();

    var $pagination = $('<ul class="pagination"></ul>');

    var currentPage = 0;
    while (currentPage < countPages) {
        $pagination.append($('<li>' + ++currentPage + '</li>'))
    }

    if (countItems > countItemsByPage) {
        $pagination.appendTo($container);
    }

    var $links = $pagination.children('li');

    $links.first().addClass('selected');

    $links.click(function () {
        $links.removeClass('selected');
        $(this).addClass('selected');

        var currentPage = parseInt($(this).text());
        var firstItem = --currentPage * countItemsByPage;

        $items.hide();
        $items.slice(firstItem, firstItem + countItemsByPage).show();
    });
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
 * Ajoute les informations relatives à la commande aux informations sommaires de la commande.
 * @param order
 * @param $order
 */
function addInfosToOrder(order, $order) {
    var cssClass = order['status']
        .split(' ')
        .join('')
        .toLowerCase();

    $order.append(
        '<div class="infos ' + cssClass + '">' +
            '<label class="number">' + order['number'] + '</label>' +
            '<label class="status">' + order['status'] + '</label>' +
            '<div class="date"> ' +
            'Last modifications by <label class="username">' + order['lastModificationByUsername'] + '</label> ' +
            'at <label class="datetime">' + dateFormat(order['lastModificationDate']) + '</label>' +
            '</div>' +
            '</div>'
    );
}

/**
 * Ajoute un log.
 * @param log
 */
function addLog(log) {
    $('#logs').append(
        '<div class="log" data-id="' + log['id'] + '" data-order-id="' + log['order']['id'] + '">' +
            '<label class="orderNumber">' + log['order']['number'] + '</label>' +
            '<label class="event">' + log['event'] + '</label>' +
            '<div class="date">' +
            'By <label class="username">' + log['username'] + '</label> at <label class="creationDate">' + dateFormat(log['datetime']) + '</label>' +
            '</div>' +
            '</div>'
    );
}

/**
 * Ajoute une ligne aux détails de la commande.
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
 * Met à jour les informations du magasin.
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

/**
 * Ajoute une fonction de recherche des valeurs passées en GET à l'objet JQuery.
 */
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