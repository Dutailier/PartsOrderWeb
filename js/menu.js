$(document).ready(function () {
    $('#btnProducts').click(function () {
        window.location = 'products.php';
    });

    $('#btnOrders').click(function () {
        $.post('ajax/getStoreConnected.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('store')) {
                    var store = data['store'];

                    if (store.hasOwnProperty('id')) {
                        window.location = 'orders.php?storeId=' + store['id'];
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
    });

    $('#btnManager').click(function () {
        window.location = 'manager.php';
    });

    $('#btnLogout').click(function () {
        $.post('ajax/logout.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {
                    window.location = 'login.php';

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