$(document).ready(function () {
    $('#btnProducts').click(function () {
        window.location = 'products.php';
    });

    $('#btnOrders').click(function () {
        $('#btnOrders').attr('disabled', 'disabled');
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
                    noty({layout: 'topRight', type: 'error', text: data['message']});

                } else {
                    noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                }
            })
            .fail(function () {
                noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
            })
            .always(function() {
                $('#btnOrders').removeAttr('disabled');
            })
    });

    $('#btnManager').click(function () {
        window.location = 'manager.php?tab=orders';
    });

    $('#btnLogout').click(function () {
        $.post('ajax/logout.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {
                    window.location = 'login.php';

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
});