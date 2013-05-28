$(document).ready(function () {
    $.post('ajax/getOrderInfos.php')
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('transaction')) {

                if (data['transaction'].hasOwnProperty('order') &&
                    data['transaction']['order'].hasOwnProperty('status') &&
                    data['transaction']['order'].hasOwnProperty('creationDate')) {
                    $('#status').text(data['transaction']['order']['status']);
                    $('#creationDate').text(data['transaction']['order']['creationDate']);
                }

                if (data['transaction'].hasOwnProperty('shippingAddress') &&
                    data['transaction'].hasOwnProperty('retailer')) {
                    UpdateShippingInfos(data['transaction']['shippingAddress']);
                    UpdateRetailerInfos(data['transaction']['retailer']);
                }

                if (data['transaction'].hasOwnProperty('customer')) {
                    UpdateCustomerInfos(data['transaction']['customer']);
                } else {
                    UpdateCustomerInfos(data['transaction']['retailer']);
                }

                if (data['transaction'].hasOwnProperty('lines')) {
                    for (var i in data['transaction']['lines']) {

                        if (data['transaction']['lines'].hasOwnProperty(i) &&
                            data['transaction']['lines'][i].hasOwnProperty('serial') &&
                            data['transaction']['lines'][i].hasOwnProperty('quantity') &&
                            data['transaction']['lines'][i].hasOwnProperty('product') &&
                            data['transaction']['lines'][i]['product'].hasOwnProperty('id') &&
                            data['transaction']['lines'][i]['product'].hasOwnProperty('name')) {
                            addLine(data['transaction']['lines'][i]);
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
        })

    $('#btnConfirm').click(function () {
        $.post('ajax/confirmTransaction.php')
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
    });

    $('#dialog').dialog({
        autoOpen: false,
        modal: true,
        dialogClass: 'dialog',
        buttons: {
            "Yes": function () {
                $.get('ajax/cancelTransaction.php')
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
        $('#dialog').dialog('open');
    });
})
;


/**
 * Ajoute une ligne à la commande.
 */
function addLine(line) {
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
        $('#customerName').text(infos['firstname'] + ' ' + infos['lastname']);
    } else if (infos.hasOwnProperty('name')) {
        $('#customerName').text(infos['name']);
    }

    if (infos.hasOwnProperty('phone') &&
        infos.hasOwnProperty('email') &&
        infos.hasOwnProperty('address')) {

        // 12345678901 => 1-234-567-8910
        $('#customerPhone').text(
            infos['phone'].substring(0, 1) + '-' +
                infos['phone'].substring(1, 4) + '-' +
                infos['phone'].substring(4, 7) + '-' +
                infos['phone'].substring(7));

        $('#customerEmail').text(infos['email']);

        var address = infos['address'];

        if (address.hasOwnProperty('details') &&
            address.hasOwnProperty('city') &&
            address.hasOwnProperty('zip') &&
            address.hasOwnProperty('state') &&
            address['state'].hasOwnProperty('name')) {

            $('#customerAddress').text(
                address['details'] + ', ' +
                    address['city'] + ', ' +
                    address['zip'] + ', ' +
                    address['state']['name']
            );
        }
    }
}

function UpdateShippingInfos(address) {
    $('#shippingAddress').text(
        address['details'] + ', ' +
            address['city'] + ', ' +
            address['zip'] + ', ' +
            address['state']['name']
    );
}

/**
 * Permet d'obtenir les valeurs passés en GET.
 */
(function ($) {
    $.queryString = (function (string) {

        if (string == "") return {};

        var params = {};

        for (var i = 0; i < string.length; ++i) {
            var p = string[i].split('=');
            if (p.length != 2) continue;
            params[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return params;
    })(window.location.search.substr(1).split('&'))
})(jQuery);