/**
 * Validation des champs de connexion.
 * @returns {boolean}
 */
function login() {

    // Récupère les différents éléments HTML.
    var txtUsername = $('#txtUsername');
    var txtPassword = $('#txtPassword');
    var lblError = $('#lblError');
    var isValid = true;

    // Valide le champ 'txtUsername'.
    if (!txtUsername.val()) {
        txtUsername.addClass('warning');
        isValid = false;
    } else {
        txtUsername.removeClass('warning');
    }

    // Valide le champ 'txtPassword'.
    if (!txtPassword.val()) {
        txtPassword.addClass('warning');
        isValid = false;
    } else {
        txtPassword.removeClass('warning');
    }

    if (isValid) {
        var credentials = {
            "username": txtUsername.val(),
            "password": txtPassword.val()
        };

        $.ajax({
            type: 'POST',
            url: 'protected/tryLogin.php',
            data: credentials,
            async: false,
            dataType: 'json',
            success: function (data) {
                if (data['success']) {
                    if (data['role_name'] == 'administrator') {
                        window.location = ' categories.php';
                    } else {
                        window.location = ' categories.php';
                    }
                } else {
                    alert(data['message']);
                }
            },
            error: function () {
                alert('Communication with the server failed.');
            }
        });
    }
    return false;
}