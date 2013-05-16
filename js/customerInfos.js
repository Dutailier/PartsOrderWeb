$(document).ready(function () {

    // Définit la règle de validation pour le numéro de téléphone.
    $.validator.addMethod('phone', function (value, element) {
        return /^[1]?[-. ]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/g.test(value)
    }, 'The phone number must be standard (ex: 123-456-7878).');

    $('#frmOrder').validate({
        rules: {
            firstname: { required: true },
            lastname: { required: true },
            email1: {
                required: true,
                email: true
            },
            email2: {
                required: true,
                email: true,
                equalTo: '#email1'
            },
            phone: {
                required: true,
                phone: true
            },
            address: { required: true },
            city: { required: true },
            zip: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 5
            }
        },
        messages: {
            firstname: { required: 'The first name is required.' },
            lastname: { required: 'The last name is required.' },
            email1: {
                required: 'The email is required.',
                email: 'The email must be standard (ex: user@domain.com).'
            },
            email2: {
                required: 'You must confirm your email.',
                equalTo: 'The emails must be the same.'
            },
            phone: {
                required: 'The phone number is required.',
                phone: 'The phone number must be standard (ex: 123-456-7890).'
            },
            address: { required: 'The address is required.' },
            city: { required: 'The city is required.' },
            zip: {
                required: 'The zip code is required.',
                digits: 'The zip code must be standard.',
                minlength: 'The zip code must be standard.',
                maxlength: 'The zip code must be standard.'
            }
        },

        wrapper: 'li',
        errorPlacement: function (error) {
            $('#summary').append(error);
        },

        submitHandler: function () {

            var informations = {
                "firstname": $('#firstname').val(),
                "lastname": $('#lastname').val(),
                "email": $('#email1').val(),
                "phone": $('#phone').val(),
                "address": $('#address').val(),
                "city": $('#city').val(),
                "zip": $('#zip').val(),
                "stateId": $('#states > option:selected').val(),
                "countryId": $('#countries > option:selected').val()
            };

            $.post('ajax/placeOrderForCustomer.php', informations)
                .done(function (data) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                    // vérifie si la requête fut un succès.
                    if (data.hasOwnProperty('success') &&
                        data['success'] &&
                        data.hasOwnProperty('orderId') &&
                        data['orderId']) {

                        window.location = 'orderInfos.php?orderId=' + data['orderId'];

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

    $('#countries').change(function () {

        var parameters = {
            "countryId": $('#countries > option:selected').val()
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

                        $('#countries > option[value="1"]')
                            .attr('selected', 'selected');
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

    $('#clear').click(function () {
        window.location.reload();
    });
});