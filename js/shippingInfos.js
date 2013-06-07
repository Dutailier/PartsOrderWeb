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

                    var shippingAddress = data['transaction']['shippingAddress'];
                    var receiver = data['transaction']['receiver'];
                    var store = data['transaction']['store'];

                    if (shippingAddress.hasOwnProperty('details') &&
                        shippingAddress.hasOwnProperty('city') &&
                        shippingAddress.hasOwnProperty('zip') &&
                        shippingAddress.hasOwnProperty('state') &&
                        shippingAddress['state'].hasOwnProperty('name')) {
                        UpdateShippingAddress(shippingAddress);
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
                            address['state'].hasOwnProperty('name')) {
                            UpdateStoreInfos(store);
                        }
                    }

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        UpdateReceiverInfos(receiver);
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

    $('#btnCancel').click(function () {
        $.post('ajax/cancelTransaction.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {
                    window.location = 'destinations.php';

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

    $('#btnEdit').click(function () {
        window.location = 'receiverInfos.php';
    });

    $('#btnConfirm').click(function () {
        window.location = 'products.php';
    });
});

/**
 * Affiche les informations relatives à l'adresse d'expédition.
 * @param address
 * @constructor
 */
function UpdateShippingAddress(address) {
    $('#shippingAddress').text(AddressFormat(address));
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
 * Affiche les informations relatives au client.
 * @param infos
 * @constructor
 */
function UpdateReceiverInfos(infos) {
    $('#receiverName').text(infos['name']);
    $('#receiverPhone').text(PhoneFormat(infos['phone']));
    $('#receiverEmail').text(infos['email']);
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