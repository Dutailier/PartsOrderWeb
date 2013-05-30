var serial = '';

$('div.infos').hide();
$('a.btnLessDetails').hide();

$('a.btnMoreDetails').click(function () {
    $(this).siblings('a.btnLessDetails').show();
    $(this).siblings('div.infos').slideDown();
    $(this).hide();
});

$('a.btnLessDetails').click(function () {
    $(this).siblings('div.infos').slideUp();
    $(this).siblings('a.btnMoreDetails').show();
    $(this).hide();
});

$(document).ready(function () {
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
            error.appendTo(element.parent());
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

    $.post('ajax/getShippingInfos.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('transaction') &&
                data['transaction'].hasOwnProperty('shippingAddress') &&
                data['transaction'].hasOwnProperty('retailer')) {

                UpdateShippingInfos(data['transaction']['shippingAddress']);
                UpdateRetailerInfos(data['transaction']['retailer']);

                if (data['transaction'].hasOwnProperty('customer')) {
                    UpdateCustomerInfos(data['transaction']['customer']);
                } else {
                    UpdateCustomerInfos(data['transaction']['retailer']);
                }

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

    $.post('ajax/getFilters.php')
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

    $('#btnProceed').click(function () {
        if ($('#items > div.item').length > 0) {
            window.location = 'orderInfos.php';
        }
    });
});

$(document).on('click', 'input.addCart', function () {

    var $product = $(this).closest('div.product');

    var parameters = {
        "productId": $product.data('id'),
        "serial": serial
    };

    $.get('ajax/addItem.php', parameters)
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

    $.get('ajax/removeItem.php', parameters)
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

function UpdateShippingInfos(address) {

    if (address.hasOwnProperty('details') &&
        address.hasOwnProperty('city') &&
        address.hasOwnProperty('zip') &&
        address.hasOwnProperty('state') &&
        address['state'].hasOwnProperty('name')) {

        $('#shippingAddress').text(
            address['details'] + ', ' +
                address['city'] + ', ' +
                address['zip'] + ', ' +
                address['state']['name']
        );
    }
}

function UpdateRetailerInfos(infos) {

    if (infos.hasOwnProperty('name') &&
        infos.hasOwnProperty('phone') &&
        infos.hasOwnProperty('email') &&
        infos.hasOwnProperty('address')) {

        $('#retailerName').text(infos['name']);

        // 12345678901 => 1-234-567-8910
        $('#retailerPhone').text(
            infos['phone'].substring(0, 1) + '-' +
                infos['phone'].substring(1, 4) + '-' +
                infos['phone'].substring(4, 7) + '-' +
                infos['phone'].substring(7));

        $('#retailerEmail').text(infos['email']);

        var address = infos['address'];

        if (address.hasOwnProperty('details') &&
            address.hasOwnProperty('city') &&
            address.hasOwnProperty('zip') &&
            address.hasOwnProperty('state') &&
            address['state'].hasOwnProperty('name')) {

            $('#retailerAddress').text(
                address['details'] + ', ' +
                    address['city'] + ', ' +
                    address['zip'] + ', ' +
                    address['state']['name']
            );
        }
    }

}

function UpdateCustomerInfos(infos) {

    if (infos.hasOwnProperty('firstname') &&
        infos.hasOwnProperty('lastname')) {
        $('#receiverName').text(infos['firstname'] + ' ' + infos['lastname']);
    } else if (infos.hasOwnProperty('name')) {
        $('#receiverName').text(infos['name']);
    }
    if (infos.hasOwnProperty('phone') &&
        infos.hasOwnProperty('email') &&
        infos.hasOwnProperty('address')) {

        // 12345678901 => 1-234-567-8910
        $('#receiverPhone').text(
            infos['phone'].substring(0, 1) + '-' +
                infos['phone'].substring(1, 4) + '-' +
                infos['phone'].substring(4, 7) + '-' +
                infos['phone'].substring(7));

        $('#receiverEmail').text(infos['email']);

        var address = infos['address'];

        if (address.hasOwnProperty('details') &&
            address.hasOwnProperty('city') &&
            address.hasOwnProperty('zip') &&
            address.hasOwnProperty('state') &&
            address['state'].hasOwnProperty('name')) {

            $('#receiverAddress').text(
                address['details'] + ', ' +
                    address['city'] + ', ' +
                    address['zip'] + ', ' +
                    address['state']['name']
            );
        }
    }
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