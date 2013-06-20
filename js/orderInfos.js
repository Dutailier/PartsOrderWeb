var storeId;

$(document).ready(function () {

    //noinspection FallthroughInSwitchStatementJS
    switch ($.QueryString['tab']) {
        case 'logs' :
            selectTabLogs();
            break;
        case 'order' :
        default:
            selectTabOrder();
    }

    updateOrderDetails();
    updateComments();
    updateLogs();

    $('#btnTabOrder').click(function () {
        selectTabOrder();
    });

    $('#btnTabLogs').click(function () {
        selectTabLogs();
    });

    $('#btnBackOrders').click(function () {
        window.location = 'orders.php?storeId=' + storeId;
    });

    $('#btnBackManager').click(function () {
        window.location = 'manager.php?tab=orders';
    });

    $('#confirmDialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {
                confirmOrder();
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
                cancelOrder();
            },
            "No": function () {
                $(this).dialog('close');
            }
        }
    });

    $('#addCommentDialog').dialog({
        autoOpen: false,
        draggable: true,
        modal: true,
        width: '500px',
        dialogClass: 'dialog',
        buttons: {
            "Submit": function () {
                addComment();

                $(this).dialog('close');
            },
            "Cancel": function () {
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

$(document).on('click', '#btnAddComment', function () {
    $('#addCommentDialog').dialog('open');
});

/**
 * Affiche le contenu de l'onglet : informations de la commande.
 */
function selectTabOrder() {
    $('#tabs').find('li').removeClass('selected');
    $('div.tab').hide();

    $('#btnTabOrder').addClass('selected');
    $('#tabOrder').show();
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

function addComment() {
    var parameters = {
        'text': $('#comment').val(),
        'orderId': $.QueryString['orderId']
    };

    $.post('ajax/addComment.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {

                $('#comment').val('');
                updateComments();

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
}

function updateOrderDetails() {
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
                        default:
                            $summary.append('<input id="btnAddComment" name="btnAddComment" type="button" value="Add comment"/>');
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
}

function updateComments() {
    var parameters = {
        'orderId': $.QueryString['orderId']
    };

    $.post('ajax/getComments.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('comments')) {

                $('div.comment').remove();
                var comments = data['comments'];

                for (var i in comments) {
                    if (comments.hasOwnProperty(i)) {
                        var comment = comments[i];

                        if (comment.hasOwnProperty('id') &&
                            comment.hasOwnProperty('orderId') &&
                            comment.hasOwnProperty('creationDate') &&
                            comment.hasOwnProperty('text') &&
                            comment.hasOwnProperty('user') &&
                            comment['user'].hasOwnProperty('username')) {
                            addCommentInfos(comment);
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

function updateLogs() {
    var parameters = {
        'orderId': $.QueryString['orderId']
    };

    $.post('ajax/getLogsByOrderId.php', parameters)
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
                            log.hasOwnProperty('username')) {
                            addLogInfos(log);
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

function confirmOrder() {
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
}

function cancelOrder() {
    var parameters = {
        "orderId": $.QueryString['orderId']
    };

    $.post('ajax/cancelOrder.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {

                window.location = 'index.php';

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

function addCommentInfos(comment) {
    $('#comments').append(
        '<div class="comment" data-id="' + comment['id'] + '">' +
            '<label class="text">' + comment['text'] + '</label>' +
            '<div class="date">' +
            'By <label class="username">' + comment['user']['username'] + '</label> at <label class="creationDate">' + dateFormat(comment['creationDate']) + '</label>' +
            '</div>' +
            '</div>'
    );
}

function addLogInfos(log) {
    $('#logs').append(
        '<div class="log" data-id="' + log['id'] + '">' +
            '<label class="event">' + log['event'] + '</label>' +
            '<div class="date">' +
            'By <label class="username">' + log['username'] + '</label> at <label class="creationDate">' + dateFormat(log['datetime']) + '</label>' +
            '</div>' +
            '</div>'
    );
}

/**
 * Affiche les informations relatives à la commande.
 * @param order
 */
function updateOrderInfos(order) {
    var cssClass = order['status']
        .split(' ')
        .join('')
        .toLowerCase();

    $('#number').text(order['number']);
    $('#creationDate').text(dateFormat(order['creationDate']));
    $('#status').text(order['status']).addClass(cssClass);
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
 * @param store
 */
function updateStoreInfos(store) {
    storeId = store['id'];
    $('#storeName').text(store['name']);
    $('#storePhone').text(phoneFormat(store['phone']));
    $('#storeEmail').text(store['email']);
    $('#storeAddress').text(addressFormat(store['address']));
}

/**
 * Affiche les informations relatives au client.
 * @param receiver
 */
function updateReceiverInfos(receiver) {
    $('#receiverName').text(receiver['name']);
    $('#receiverPhone').text(phoneFormat(receiver['phone']));
    $('#receiverEmail').text(receiver['email']);
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