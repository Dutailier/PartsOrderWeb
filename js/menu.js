$(document).ready(function () {
    $('#btnProducts').click(function () {
        window.location = 'products.php';
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