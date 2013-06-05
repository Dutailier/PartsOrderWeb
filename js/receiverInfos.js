function AddState(data, i) {
    $('#states').append(
        $('<option></option>')
            .val(data['states'][i]['id'])
            .text(data['states'][i]['name']));
}
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

            // Désactive les champs du formulaire le temps que la requête soit exécutée.
            $('#frmOrder *').attr('disabled', 'disabled');

            var informations = {
                "name": $('#name').val(),
                "email": $('#email1').val(),
                "phone": $('#phone').val(),
                "details": $('#address').val(),
                "city": $('#city').val(),
                "zip": $('#zip').val(),
                "stateId": $('#states > option:selected').val(),
                "countryId": $('#countries > option:selected').val(),
                "useStoreAddress": $('#checkUseStoreAddress').is(':checked')
            };

            $.post('ajax/setTransactionInfos.php', informations)
                .done(function (data) {

                    if (data.hasOwnProperty('success') &&
                        data['success']) {
                        window.location = 'shippingInfos.php';

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
                    $('#frmOrder *').removeAttr('disabled');
                })
        }
    });

    $('#countries').change(function () {

        var parameters = {
            "countryId": $('#countries > option:selected').val()
        };

        $.post('ajax/getStates.php', parameters)
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('states')) {

                    $('#states > option').remove();

                    for (var i in data['states']) {
                        if (data['states'].hasOwnProperty(i) &&
                            data['states'][i].hasOwnProperty('id') &&
                            data['states'][i].hasOwnProperty('name')) {

                            AddState(data, i);
                        }
                    }

                    UpdateInfos();

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

    $.post('ajax/getCountries.php')
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('countries')) {

                $('#countries > option').remove();

                for (var i in data['countries']) {
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
                $('#countries').trigger('change');

            } else if (data.hasOwnProperty('message')) {
                alert(data['message']);

            } else {
                alert('The result of the server is unreadable.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })

    $('#clear').click(function () {
        $('fieldset > p > input, textarea').val('');
    });

    $('#btnCancel').click(function () {
        $.post('ajax/cancelTransaction.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {
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
    });
});

/**
 * Met à jour les informations du receveur ainsi que l'adresse d'expédition.
 * @constructor
 */
function UpdateInfos() {
    $.post('ajax/getReceiver.php')
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('receiver')) {

                var receiver = data['receiver'];

                if (receiver.hasOwnProperty('name') &&
                    receiver.hasOwnProperty('email') &&
                    receiver.hasOwnProperty('phone')) {

                    $('#name').val(receiver['name']);
                    $('#email1').val(receiver['email']);
                    $('#email2').val(receiver['email']);
                    $('#phone').val(receiver['phone']);
                }
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })

    $.post('ajax/getShippingAddress.php')
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('shippingAddress')) {

                var shippingAddress = data['shippingAddress'];

                if (shippingAddress.hasOwnProperty('details') &&
                    shippingAddress.hasOwnProperty('city') &&
                    shippingAddress.hasOwnProperty('zip') &&
                    shippingAddress.hasOwnProperty('state') &&
                    shippingAddress['state'].hasOwnProperty('id') &&
                    shippingAddress['state'].hasOwnProperty('countryId')) {

                    $('#address').val(shippingAddress['details']);
                    $('#city').val(shippingAddress['city']);
                    $('#zip').val(shippingAddress['zip']);
                    $('#states').val(shippingAddress['state']['id']);
                    $('#countries').val(shippingAddress['state']['countryId']);
                }
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })
}