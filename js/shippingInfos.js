$(document).ready(function () {
    $.post('ajax/getTransactionInfos.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('transaction')) {

                var transaction = data['transaction'];

                if (transaction.hasOwnProperty('shippingAddress') &&
                    transaction.hasOwnProperty('receiver') &&
                    transaction.hasOwnProperty('store')) {

                    var shippingAddress = transaction['shippingAddress'];
                    var receiver = transaction['receiver'];
                    var store = transaction['store'];

                    if (shippingAddress.hasOwnProperty('details') &&
                        shippingAddress.hasOwnProperty('city') &&
                        shippingAddress.hasOwnProperty('zip') &&
                        shippingAddress.hasOwnProperty('state') &&
                        shippingAddress['state'].hasOwnProperty('id') &&
                        shippingAddress['state'].hasOwnProperty('name')) {
                        updateShippingAddressinfos(shippingAddress);
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
                            address['state'].hasOwnProperty('id') &&
                            address['state'].hasOwnProperty('name')) {
                            updateStoreInfos(store);
                        }
                    }

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        updateReceiverInfos(receiver);
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

    $('#btnCancel').click(function () {
        $('#cancelDialog').dialog('open');
    });

    $('#btnEdit').click(function () {
        window.location = 'receiverInfos.php';
    });

    $('#btnConfirm').click(function () {
        $.post('ajax/openTransaction.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {
                    window.location = 'categories.php';

                } else if (data.hasOwnProperty('message')) {
                    noty({layout: 'topRight', type: 'error', text: data['message']});

                } else {
                    noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                }
            })
            .fail(function () {
                noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
            })
    });

    $('#cancelDialog').dialog({
        title: 'Order cancelation',
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: [
            {
                id: 'cancelYes',
                text: 'Yes',
                click: function () {
                    $('#cancelYes, #cancelNo').button('disable');

                    $.post('ajax/cancelTransaction.php')
                        .done(function (data) {

                            if (data.hasOwnProperty('success') &&
                                data['success']) {
                                window.location = 'destinations.php';

                            } else if (data.hasOwnProperty('message')) {
                                noty({layout: 'topRight', type: 'error', text: data['message']});

                            } else {
                                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                            }
                        })
                        .fail(function () {
                            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
                        })
                }},
            {
                id: 'cancelNo',
                text: 'No',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ]
    });
});

/**
 * Affiche les informations relatives à l'adresse d'expédition.
 * @param address
 */
function updateShippingAddressinfos(address) {
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