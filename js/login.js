$(document).ready(function () {

    $('#login').find('form').validate({

        // Définit les règles de validations.
        rules: {
            username: { required: true },
            password: { required: true }
        },

        submitHandler: function () {

            $('#login').find('form').filter().attr('disabled', 'disabled');

            var credentials = {
                "username": $('#username').val(),
                "password": $('#password').val()
            };

            $.post('ajax/tryLogin.php', credentials)
                .done(function (data) {

                    if (data.hasOwnProperty('success') &&
                        data['success'] &&
                        data.hasOwnProperty('valid') &&
                        data['valid']) {

                        window.location = 'index.php';

                    } else if (data.hasOwnProperty('message')) {
                        alert(data['message']);

                    } else {
                        alert('The result of the server is unreadable.');
                    }
                })
                .fail(function () {
                    alert('Communication with the server failed.');
                })
                .always(function () {
                    $('#login').find('form').filter().removeAttr('disabled');
                })
        }
    });
});