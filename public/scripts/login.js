/**
 * Validation des champs de connexion.
 * @returns {boolean}
 */
function login() {

    // Récupère les différents éléments HTML.
    var $txtUsername = $('#txtUsername');
    var $txtPassword = $('#txtPassword');
    var isValid = true;

    // Valide le champ 'txtUsername'.
    if (!$txtUsername.val()) {
        $txtUsername.addClass('warning');
        isValid = false;
    } else {
        $txtUsername.removeClass('warning');
    }

    // Valide le champ 'txtPassword'.
    if (!$txtPassword.val()) {
        $txtPassword.addClass('warning');
        isValid = false;
    } else {
        $txtPassword.removeClass('warning');
    }

    if (isValid) {
        var credentials = {
            "username": $txtUsername.val(),
            "password": $txtPassword.val()
        };

        $.post('protected/ajax/tryLogin.php', credentials)
            .done(function (data) {

                // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                // vérifie si la requête fut un succès.
                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('role_name')) {

                    // Redirige l'utilisateur selon son rôle.
                    switch (data['role_name']) {

                        // L'utilisateur est administrateur.
                        case 'administrateur' :
                            window.location = ' categories.php';
                            break;

                        // Tous autres utilisateurs.
                        case 'retailer':
                        default:
                            window.location = 'categories.php';
                    }

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

    // Empêche le postback automatique.
    return false;
}