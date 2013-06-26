// Évènements définis une fois le document HTML complètement généré.

$(document).ready(function () {

    $('#btnTabOrders').click(function () {
        selectTabOrders();
    });

    $('#btnTabStores').click(function () {
        selectTabStores();
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
        window.location = 'storeInfos.php?' +
            'bannerId=' + $('#banners').find('option:selected').val();
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

    $('#orderFrom, #logFrom').datepicker('setDate', '-1w');
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
                    $(this).dialog('close');
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
                    $(this).dialog('close');
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
});

// Évènements liés à des éléments générés.

$(document).on('click', 'div.order > div.infos', function () {
    addDetailsToOrder($(this).closest('div.order'));
});

$(document).on('click', 'div.store > div.infos', function () {
    addDetailsToStore($(this).closest('div.store'));
});

$(document).on('click', 'input.btnStoreOrders', function () {
    window.location = 'orders.php?' +
        'storeId=' + $(this).closest('div.store').data('id');
});

$(document).on('click', 'input.btnDetails', function () {
    window.location = 'orderInfos.php?' +
        'orderId=' + $(this).closest('div.order').data('id');
});

$(document).on('click', 'input.btnStoreEdit', function () {
    window.location = 'storeInfos.php?' +
        'storeId=' + $(this).closest('div.store').data('id') +
        '&bannerId=' + $('#banners').find('option:selected').val();
});

$(document).on('click', 'div.log > label.orderNumber', function () {
    window.location = 'orderInfos.php?' +
        'orderId=' + $(this).closest('div.log').data('order-id');
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

    if ($('div.order').length == 0 &&
        $('#ordersLoader').is(':hidden')) {
        updateOrdersByRangeOfDates();
    }
}

/**
 * Affiche le contenu de l'onglet : magasins.
 */
function selectTabStores() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabStores').addClass('selected');
    $('#tabStores').show();

    if ($('div.store').length == 0 &&
        $('#storesLoader').is(':hidden')) {
        updateBanners();
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
 * Met à jour les différentes bannières disponibles.
 */
function updateBanners() {
    $('#storesFilters').find('input').attr('disabled', 'disabled');
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

/**
 * Met à jour les logs par interval de dates.
 */
function updateLogsByRangeOfDates() {

    var parameters = {
        'from': $('#logFrom').val(),
        'to': $('#logTo').val()
    };

    $('div.log').hide();
    $('#logsFilters').find('input').attr('disabled', 'disabled');
    $('#logsLoader').show();
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
                            log.hasOwnProperty('username')) {
                            addLog(log);
                        }
                    }
                }

                filterLogsByKeyWords();

            } else if (data.hasOwnProperty('message')) {
                alert(data['message']);
                $('div.log').show();

            } else {
                alert('The result of the server is unreadable.');
                $('div.log').show();
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
            $('div.log').show();
        })
        .always(function () {
            $('#logsLoader').hide();
            $('#logsFilters').find('input').removeAttr('disabled');
        })
}

/**
 * Filtre les commandes par les mots clés recherchés.
 */
function filterOrdersByKeyWords() {
    var $orders = $('div.order');

    $orders.hide();
    $orders.children('div.infos').find('label').each(function (index, lbl) {
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

    var $ordersVisibled = $orders.filter(':visible');

    if ($ordersVisibled.length > 0) {
        $('#ordersEmpty').hide();
    } else {
        $('#ordersEmpty').show();
    }

    paginate($('#orders'), $ordersVisibled, 10);
}

/**
 * Filtre les magasins par mots clés recherchés.
 */
function filterStoresByKeyWords() {
    var $stores = $('div.store');

    $stores.hide();
    $stores.children('div.infos').find('label').each(function (index, lbl) {
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

    var $storesVisibled = $stores.filter(':visible');

    if ($storesVisibled.length > 0) {
        $('#storesEmpty').hide();
    } else {
        $('#storesEmpty').show();
    }

    paginate($('#stores'), $storesVisibled, 10);
}

/**
 * Filtre les logs par mots clés recherchés.
 */
function filterLogsByKeyWords() {
    var $logs = $('div.log');

    $logs.hide();

    $logs.find('label').each(function (index, lbl) {
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

    var $logsVisibled = $logs.filter(':visible');

    if ($logsVisibled.length > 0) {
        $('#logsEmpty').hide();
    } else {
        $('#logsEmpty').show();
    }

    paginate($('#logs'), $logsVisibled, 10);
}

/**
 * Met à jour les magasins par bannière.
 */
function updateStoresByBannerId() {
    var parameters = {
        'bannerId': $('#banners').find('option:selected').val()
    };

    $('div.store').hide();
    $('#storesLoader').show();
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
                $('div.store').show();

            } else {
                alert('The result of the server is unreadable.');
                $('div.store').show();
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
            $('div.store').show();
        })
        .always(function () {
            $('#storesLoader').hide();
            $('#storesFilters').find('input').removeAttr('disabled');
        })
}

/**
 * Met à jout les commandes par interval de dates.
 */
function updateOrdersByRangeOfDates() {
    var parameters = {
        'from': $('#orderFrom').val(),
        'to': $('#orderTo').val()
    };

    $('div.order').hide();
    $('#ordersFilters').find('input').attr('disabled', 'disabled');
    $('#ordersLoader').show();
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
                alert(data['message']);
                $('div.order').show();

            } else {
                alert('The result of the server is unreadable.');
                $('div.order').show();
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
            $('div.order').show();
        })
        .always(function () {
            $('#ordersLoader').hide();
            $('#ordersFilters').find('input').removeAttr('disabled');
        })
}

/**
 * Ajoute les détails d'un magasin.
 * @param $store
 */
function addDetailsToStore($store) {

    var $infos = $store.children('div.infos');

    var parameters = {
        "storeId": $store.data('id')
    };

    $infos.click(false);
    $infos.animate({'opacity': 0.5});
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
        .always(function () {
            $infos.animate({'opacity': 1});
            $infos.click(function () {
                $store.children('div.details').stop().slideToggle();
            })
        })
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
        .always(function () {
            $infos.animate({'opacity': 1});
            $infos.click(function () {
                $order.children('div.details').stop().slideToggle();
            })
        })
}

/**
 * Paginer une liste d'item.
 * @param $container
 * @param $items
 * @param countItemsByPage
 */
function paginate($container, $items, countItemsByPage) {

    if ($container.next().first().is('ul.pagination')) {
        $container.next().first().remove();
    }

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
        $pagination.insertAfter($container);
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
 * Ajoute les informations relatives au magasin aux détails du magasin.
 * @param $details
 * @param store
 */
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
 * Ajoute les informations relatives au magasin aux détails de la commande.
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
 * Ajoute les informations relatives à la commandes aux informations sommaires de la commande.
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
 * Ajoute une ligne à la commande aux détails de la commande.
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
 * Ajoute les informations relatives au magasin aux informations sommaires du magasin.
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
            'By <label class="username">' + log['username'] + '</label> at <label class="datetime">' + dateFormat(log['datetime']) + '</label>' +
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

/**
 * Ajoute une function de recherche des valeurs passées en GET à l'objet JQuery.
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