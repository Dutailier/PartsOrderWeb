$(document).ready(function () {
    //noinspection JSUnresolvedVariable,JSUnresolvedFunction
    $.validator.addMethod('phone', function (value) {
        return /^[1]?[-. ]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/g.test(value)
    }, 'The phone number must be standard (ex: 123-456-7878).');

    //noinspection JSUnresolvedFunction
    $('#frmOrder').validate({
        rules: {
            name: { required: true },
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
            name: { required: 'The name is required.' },
            email1: {
                required: 'The email is required.',
                email: 'The email must be standard (i.e. user@domain.com).'
            },
            email2: {
                required: 'You must confirm your email.',
                equalTo: 'The emails must be the same.'
            },
            phone: {
                required: 'The phone number is required.',
                phone: 'The phone number must be standard (i.e. 123-456-7890).'
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
                "name": $('#name').val(),
                "email": $('#email1').val(),
                "phone": $('#phone').val(),
                "details": $('#address').val(),
                "city": $('#city').val(),
                "zip": $('#zip').val(),
                "stateId": $('#states').find('option:selected').val(),
                "countryId": $('#countries').find('option:selected').val(),
                "useStoreAddress": $('#checkUseStoreAddress').is(':checked')
            };

            $.post('ajax/setTransactionInfos.php', informations)
                .done(function (data) {

                    if (data.hasOwnProperty('success') &&
                        data['success']) {
                        window.location = 'shippingInfos.php';

                    } else if (data.hasOwnProperty('message')) {
                        noty({layout: 'topRight', type: 'error', text: data['message']});

                    } else {
                        noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                    }
                })
                .fail(function () {
                    noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
                })
        }
    });

    $('#countries').change(function () {

        var parameters = {
            "countryId": $('#countries').find('option:selected').val()
        };

        $.post('ajax/getStates.php', parameters)
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success'] &&
                    data.hasOwnProperty('states')) {
                    var states = data['states'];

                    $('#states').find('option').remove();

                    for (var i in states) {
                        if (states.hasOwnProperty(i)) {
                            var state = states[i];

                            if (state.hasOwnProperty('id') &&
                                state.hasOwnProperty('name')) {
                                addStateInfos(state);
                            }
                        }
                    }

                    updateReceiverInfosAndShippindAddressInfos();

                } else if (data.hasOwnProperty('message')) {
                    noty({layout: 'topRight', type: 'error', text: data['message']});

                } else {
                    noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                }
            })
            .fail(function () {
                noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
            })
    });

    $.post('ajax/getCountries.php')
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('countries')) {

                var $countries = $('#countries');
                var countries = data['countries'];

                $countries.find('option').remove();

                for (var i in countries) {
                    if (countries.hasOwnProperty(i)) {
                        var country = countries[i];

                        if (country.hasOwnProperty('id') &&
                            country.hasOwnProperty('name')) {
                            addCountryInfos(country);
                        }
                    }
                }
                $countries.trigger('change');

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        });

    $('#btnClear').click(function () {
        $('fieldset').find('input, textarea').val('');
    });

    $('#btnCancel').click(function () {
        $.post('ajax/cancelTransaction.php')
            .done(function (data) {

                if (data.hasOwnProperty('success') &&
                    data['success']) {
                    window.location = 'destinations.php';

                } else if (data.hasOwnProperty('message')) {
                    noty({layout: 'topRight', type: 'error', text: data['message']});

                } else {
                    noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                }
            })
            .fail(function () {
                noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
            })
    });
});

function addStateInfos(state) {
    $('#states').append(
        $('<option></option>')
            .val(state['id'])
            .text(state['name']));
}

function addCountryInfos(country) {
    $('#countries').append(
        $('<option></option>')
            .val(country['id'])
            .text(country['name']));
}

/**
 * Met à jour les informations du receveur ainsi que l'adresse d'expédition.
 */
function updateReceiverInfosAndShippindAddressInfos() {
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
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        });

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
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
}