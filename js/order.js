$(document).ready(function () {

    $('#countries').change(function () {

        var parameters = {
            "country": $('#countries > option:selected').val()
        };

        $.get('ajax/getStates.php', parameters)
            .done(function (data) {

                // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                // vérifie si la requête fut un succès.
                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('states')) {

                    //Efface la liste actuelle.
                    $('#states > option').remove();

                    // Parcours tous les états/provinces retournées par la requête.
                    for (var i in data['states']) {

                        // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                        if (data['states'].hasOwnProperty(i) &&
                            data['states'][i].hasOwnProperty('id') &&
                            data['states'][i].hasOwnProperty('name')) {

                            // Ajouter l'état/province à la liste.
                            $('#states').append(
                                $('<option></option>')
                                    .val(data['states'][i]['id'])
                                    .text(data['states'][i]['name']));
                        }
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
    });

    $.get('ajax/getCountries.php')
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créées et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('countries')) {

                //Efface la liste actuelle.
                $('#countries > option').remove();

                // Parcours tous les pays retournées par la requête.
                for (var i in data['countries']) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                    if (data['countries'].hasOwnProperty(i) &&
                        data['countries'][i].hasOwnProperty('id') &&
                        data['countries'][i].hasOwnProperty('name')) {

                        // Ajoute le pays à la liste.
                        $('#countries').append(
                            $('<option></option>')
                                .val(data['countries'][i]['id'])
                                .text(data['countries'][i]['name']));
                    }
                }

                // Déclenche l'évènement 'change' afin que la liste d'états/provinces sont populée.
                $('#countries').trigger('change');

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
});