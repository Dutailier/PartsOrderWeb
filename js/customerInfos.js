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
                "details": $('#address').val(),
                "city": $('#city').val(),
                "zip": $('#zip').val(),
                "stateId": $('#states > option:selected').val(),
                "countryId": $('#countries > option:selected').val(),
                "useStoreAddress": $('#checkUseStoreAddress').is(':checked')
            };

            $.post('ajax/setCustomer.php', informations)
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

                            // Ajouter l'état/province à la liste.
                            $('#states').append(
                                $('<option></option>')
                                    .val(data['states'][i]['id'])
                                    .text(data['states'][i]['name']));
                        }
                    }

                    UpdateCustomerInfos();

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

    $('#checkUseStoreAddress').click(function () {
        if ($(this).is(':checked')) {
            $.post('ajax/getRetailerAddress.php')
                .done(function (data) {

                    if (data.hasOwnProperty('success') &&
                        data['success'] &&
                        data.hasOwnProperty('address')) {

                        if (data['address'].hasOwnProperty('details') &&
                            data['address'].hasOwnProperty('city') &&
                            data['address'].hasOwnProperty('zip') &&
                            data['address'].hasOwnProperty('state') &&
                            data['address']['state'].hasOwnProperty('id') &&
                            data['address']['state'].hasOwnProperty('countryId')) {

                            $('#address').val(data['address']['details']);
                            $('#city').val(data['address']['city']);
                            $('#zip').val(data['address']['zip']);

                            $('#countries').val(data['address']['state']['countryId']);
                            $('#states').val(data['address']['state']['id']);

                            $('#addressInfos > p > *').attr('disabled', 'disabled');
                        }

                    } else if (data.hasOwnProperty('message')) {
                        alert(data['message']);

                    } else {
                        alert('The result of the server is unreadable.');
                    }
                })
                .fail(function () {
                    alert('Communication with the server failed.');
                })

        } else {
            $('#addressInfos > p > *').removeAttr('disabled');
            $('#addressInfos textarea, #addressInfos input[type=text]').val('');
        }
    })
});

function UpdateCustomerInfos() {
    $.post('ajax/getCurrentCustomer.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('customer')) {

                if (data['customer'].hasOwnProperty('firstname') &&
                    data['customer'].hasOwnProperty('lastname') &&
                    data['customer'].hasOwnProperty('email') &&
                    data['customer'].hasOwnProperty('phone') &&
                    data['customer'].hasOwnProperty('address') &&
                    data['customer']['address'] &&
                    data['customer']['address'].hasOwnProperty('details') &&
                    data['customer']['address'].hasOwnProperty('city') &&
                    data['customer']['address'].hasOwnProperty('zip') &&
                    data['customer']['address'].hasOwnProperty('state') &&
                    data['customer']['address']['state'].hasOwnProperty('id') &&
                    data['customer']['address']['state'].hasOwnProperty('countryId')) {

                    $('#firstname').val(data['customer']['firstname']);
                    $('#lastname').val(data['customer']['lastname']);
                    $('#email1').val(data['customer']['email']);
                    $('#email2').val(data['customer']['email']);
                    $('#phone').val(data['customer']['phone']);

                    $('#address').val(data['customer']['address']['details']);
                    $('#city').val(data['customer']['address']['city']);
                    $('#zip').val(data['customer']['address']['zip']);

                    $('#countries').val(data['customer']['address']['state']['countryId']);
                    $('#states').val(data['customer']['address']['state']['id']);
                }
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })
}