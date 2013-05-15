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

                    // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                    // vérifie si la requête fut un succès.
                    if (data.hasOwnProperty('success') &&
                        data['success']) {

                            window.location = 'categories.php';

                        // Vérifie que la propriété de l'objet JSON a bien été créée.
                    } else if (data.hasOwnProperty('message')) {

                        // Affiche un message d'erreur expliquant l'échec de la requête.
                        alert(data['message']);
                    } else {
                        alert('Communication with the server failed.');
                    }
                })
                .fail(function () {
                    alert('Communication with the server failed.');
                })
        }
    });
});