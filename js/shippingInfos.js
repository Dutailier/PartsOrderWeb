$(document).ready(function () {
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
                    $('#btnEdit').show();
                } else {
                    UpdateCustomerInfos(data['transaction']['retailer']);
                    $('#btnEdit').hide();
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

    $('#btnEdit').click(function () {
        window.location = 'customerInfos.php';
    });

    $('#btnConfirm').click(function() {
       window.location = 'products.php';
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