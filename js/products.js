var serial = '';

$('div.infos').hide();
$('#load').hide();
$('#btnProceed').attr('disabled', 'disabled');

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
                        updateShippingAddressInfos(shippingAddress);
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
                            updateStoreInfos(store);
                        }
                    }

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        updateReceiverInfos(receiver);
                    }

                    updateItems();
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
                'serial': serial
            };

            $('div.product').remove();
            $('#help').hide();

            $('#load').show();
            $('#search').attr('disabled', 'disabled');

            $.post('ajax/getProducts.php', parameters)
                .done(function (data) {

                    if (data.hasOwnProperty('success') &&
                        data['success'] &&
                        data.hasOwnProperty('products')) {
                        var products = data['products'];

                        for (var i in products) {
                            if (products.hasOwnProperty(i)) {
                                var product = products[i];

                                if (product.hasOwnProperty('id') &&
                                    product.hasOwnProperty('name') &&
                                    product.hasOwnProperty('description')) {
                                    addProduct(product);
                                }
                            }
                        }

                    } else if (data.hasOwnProperty('message')) {
                        alert(data['message']);
                        $('#serial').focus();
                        $('#help').show();

                    } else {
                        alert('The result of the server is unreadable.');
                        $('#serial').focus();
                        $('#help').show();
                    }
                })
                .fail(function () {
                    alert('Communication with the server failed.');
                    $('#serial').focus();
                    $('#help').show();
                })
                .always(function () {
                    $('#load').hide();
                    $('#search').removeAttr('disabled');
                })
        }
    });

    $('#btnClear').click(function () {
        $.post('ajax/clearCart.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {

                    updateItems();

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
                            data['success'] &&
                            data.hasOwnProperty('orderId')) {
                            window.location = 'orderInfos.php?orderId=' + data['orderId'];

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
        $('#proceedDialog').dialog('open');
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
                updateItems();

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
                updateItems();

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
            "serial": serial,
            "filterIds": [$filter.data('id')]
        };

        $('div.product').hide();
        $('#load').show();

        $.post('ajax/getProducts.php', parameters)
            .done(function (data) {
                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('products')) {
                    var products = data['products'];

                    $('div.product').remove();

                    for (var i in products) {
                        if (products.hasOwnProperty(i)) {
                            var product = products[i];

                            if (product.hasOwnProperty('id') &&
                                product.hasOwnProperty('name') &&
                                product.hasOwnProperty('description')) {
                                addProduct(product);
                            }
                        }
                    }
                } else if (data.hasOwnProperty('message')) {
                    alert(data['message']);
                    $('div.product').show();

                } else {
                    alert('The result of the server is unreadable.');
                    $('div.product').show();
                }
            })
            .fail(function () {
                alert('Communication with the server failed.');
            })
            .always(function () {
                $('#load').hide();
            })
    }
});

/**
 * Affiche les informations relatives à l'adresse d'expédition.
 * @param address
 */
function updateShippingAddressInfos(address) {
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

function updateItems() {
    $.post('ajax/getItems.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('items')) {
                var items = data['items'];
                var $lblProducts = $('#lblProducts');

                $('div.item').remove();

                if (items.length > 0) {
                    $lblProducts.show();
                } else {
                    $lblProducts.hide();
                }

                for (var i in items) {
                    if (items.hasOwnProperty(i)) {
                        var item = items[i];

                        if (item.hasOwnProperty('quantity') &&
                            item.hasOwnProperty('serial') &&
                            item.hasOwnProperty('product') &&
                            item['product'].hasOwnProperty('id') &&
                            item['product'].hasOwnProperty('name')) {
                            addItemInfos(item);
                        }
                    }
                }

                if ($('div.item').length > 0) {
                    $('#btnProceed').removeAttr('disabled');
                } else {
                    $('#btnProceed').attr('disabled', 'disabled');
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

function addItemInfos(item) {
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

function addProduct(product) {

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