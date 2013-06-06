var serial = '';

$('div.infos').hide();

$('a.btnMoreDetails').click(function () {
    $(this).siblings('a.btnLessDetails').show();
    $(this).siblings('div.infos').slideDown();
    $(this).hide();
});

$('a.btnLessDetails').hide().click(function () {
    $(this).siblings('div.infos').slideUp();
    $(this).siblings('a.btnMoreDetails').show();
    $(this).hide();
});

$(document).ready(function () {
    $.post('ajax/getShippingInfos.php')
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

                    UpdateItems();
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

    $('#frmSearch').validate({
        rules: {
            serial: {
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 11
            }
        },

        errorPlacement: function (error, element) {
            error.appendTo(element.element());
        },
        submitHandler: function () {

            // Récupère le numéro de série afin de le garder valide.
            serial = $('#serial').val();

            var parameters = {
                "serial": serial
            };

            $.post('ajax/getProducts.php')
                .done(function (data) {

                    if (data.hasOwnProperty('success') &&
                        data['success'] &&
                        data.hasOwnProperty('products')) {

                        $('#help').remove();
                        $('div.product').remove();

                        for (var i in data['products']) {
                            if (data['products'].hasOwnProperty(i) &&
                                data['products'][i].hasOwnProperty('id') &&
                                data['products'][i].hasOwnProperty('name') &&
                                data['products'][i].hasOwnProperty('description')) {
                                AddProduct(data['products'][i]);
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
    });

    $.post('ajax/getTypes.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('filters')) {

                for (var i in data['filters']) {
                    if (data['filters'].hasOwnProperty(i) &&
                        data['filters'][i].hasOwnProperty('id') &&
                        data['filters'][i].hasOwnProperty('name')) {
                        AddFilter(data['filters'][i]);
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

    $('#btnClear').click(function () {
        $.post('ajax/clearCart.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {

                    $('div.item').remove();
                    $('#lblProducts').hide();

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

    $('#cancelDialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {
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
            },
            "No": function () {
                $(this).dialog('close');
            }
        }
    });

    $('#btnCancel').click(function () {
        $('#cancelDialog').dialog('open');
    });

    $('#proceedDialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {
                $.post('ajax/proceedTransaction.php')
                    .done(function (data) {

                        if (data.hasOwnProperty('success') &&
                            data['success']) {
                            window.location = 'transactionInfos.php';

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

    $('#btnProceed').click(function () {
        if ($('div.item').length > 0) {
            $('#proceedDialog').dialog('open');
        }
    });
});

$(document).on('click', 'input.addCart', function () {

    var $product = $(this).closest('div.product');

    var parameters = {
        "productId": $product.data('id'),
        "serial": serial
    };

    $.post('ajax/addItem.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {
                UpdateItems();

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

$(document).on('click', 'input.removeCart', function () {

    var $item = $(this).closest('div.item');

    var parameters = {
        "productId": $item.data('product-id'),
        "serial": $item.find('label.serial').text()
    };

    $.post('ajax/removeItem.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {
                UpdateItems();

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

$(document).on('click', 'label.filter', function () {

    if (serial != '') {
        var $filter = $(this).closest('label.filter');

        var parameters = {
            "filterIds": [$filter.data('id')]
        };

        $.post('ajax/getProducts.php', parameters)
            .done(function (data) {
                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('products')) {

                    $('div.product').remove();

                    for (var i in data['products']) {
                        if (data['products'].hasOwnProperty(i) &&
                            data['products'][i].hasOwnProperty('id') &&
                            data['products'][i].hasOwnProperty('name') &&
                            data['products'][i].hasOwnProperty('description')) {
                            AddProduct(data['products'][i]);
                        }
                    }
                }
            })
            .fail(function () {
                alert('Communication with the server failed.');
            })
    }
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

function UpdateItems() {
    $.post('ajax/getItems.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('items')) {

                $('div.item').remove();
                $('#lblProducts').hide();

                for (var i in data['items']) {
                    if (data['items'].hasOwnProperty(i) &&
                        data['items'][i].hasOwnProperty('quantity') &&
                        data['items'][i].hasOwnProperty('serial') &&
                        data['items'][i].hasOwnProperty('product') &&
                        data['items'][i]['product'].hasOwnProperty('id') &&
                        data['items'][i]['product'].hasOwnProperty('name')) {
                        $('#lblProducts').show();
                        AddItem(data['items'][i]);
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

function AddItem(item) {
    var $item = $(
        '<div class="item" data-product-id="' + item['product']['id'] + '">' +
            '<label class="quantity">' + item['quantity'] + '</label>' +
            '<label class="name">' + item['product']['name'] + '</label>' +
            '<label class="serial">' + item['serial'] + '</label>' +

            '<div class="buttons">' +
            '<input class="removeCart" type="button"/>' +
            '</div>' +
            '</div>)');

    $('#items').append($item);
}

function AddProduct(product) {

    var $product = $(
        '<div class="product" data-id="' + product['id'] + '">' +
            '<label class="name">' + product['name'] + '</label>' +
            '<label class="description">' + product['description'] + '</label>' +

            '<div class="buttons">' +
            '<input class="addCart" type="button"/>' +
            '</div>' +
            '</div>)');

    $('#products').append($product);
}

function AddFilter(filter) {
    var $filter = $(
        '<label class="filter" data-id="' + filter['id'] + '">' + filter['name'] + '</label>');

    $('#filters').append($filter);
}