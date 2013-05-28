$(document).ready(function () {

    $('#login > form').validate({

        // Définit les règles de validations.
        rules: {
            username: { required: true },
            password: { required: true }
        },

        // Se produit losrque tous les champs sont valides.
        submitHandler: function () {

            // Crée un objet composé des informations de connexion.
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
        }
    });
});