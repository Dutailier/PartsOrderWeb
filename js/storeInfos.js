$(document).ready(function () {
    //noinspection JSUnresolvedVariable,JSUnresolvedFunction
    $.validator.addMethod('phone', function (value) {
        return /^[1]?[-. ]?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/g.test(value)
    }, 'The phone number must be standard (ex: 123-456-7878).');

    //noinspection JSUnresolvedFunction
    $('#frmOrder').validate({
        rules: {
            username: {required: true },
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
            username: { required: 'The username is required.' },
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

            if ($.QueryString.hasOwnProperty('storeId')) {
                informations["storeId"] = $.QueryString['storeId'];
                informations["userId"] = $('#userInfos').data('id');
                informations["addressId"] = $('#addressInfos').data('id');

                $.post('ajax/updateStore.php', informations)
                    .done(function (data) {

                        if (data.hasOwnProperty('success') &&
                            data['success']) {
                            window.location = 'manage.php';

                        } else if (data.hasOwnProperty('message')) {
                            alert(data['message']);

                        } else {
                            alert('The result of the server is unreadable.');
                        }
                    })
                    .fail(function () {
                        alert('Communication with the server failed.');
                    })

            } else if ($.QueryString.hasOwnProperty('bannerId')) {
                informations["username"] = $('#username').val();
                informations["bannerId"] = $.QueryString['bannerId'];

                $.post('ajax/addStore.php', informations)
                    .done(function (data) {

                        if (data.hasOwnProperty('success') &&
                            data['success']) {
                            window.location = 'manage.php';

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

                    updateStoreInfosAndStoreAddressInfos();

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
                alert(data['message']);

            } else {
                alert('The result of the server is unreadable.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        });

    $('#btnClear').click(function () {
        $('fieldset').filter('input, textarea').val('');
    });

    $('#btnCancel').click(function () {
        window.location = 'manage.php';
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
function updateStoreInfosAndStoreAddressInfos() {
    var parameters = {
        'storeId': $.QueryString['storeId']
    };

    $.post('ajax/getStoreById.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('store')) {

                var store = data['store'];

                if (store.hasOwnProperty('name') &&
                    store.hasOwnProperty('email') &&
                    store.hasOwnProperty('phone') &&
                    store.hasOwnProperty('address') &&
                    store.hasOwnProperty('user')) {
                    var address = store['address'];
                    var user = store['user'];

                    if (address.hasOwnProperty('details') &&
                        address.hasOwnProperty('city') &&
                        address.hasOwnProperty('zip') &&
                        address.hasOwnProperty('state') &&
                        address['state'].hasOwnProperty('id') &&
                        address['state'].hasOwnProperty('name')) {

                        if (user.hasOwnProperty('id') &&
                            user.hasOwnProperty('username')) {
                            $('#userInfos').data('id', user['id']);

                            var $username = $('#username');
                            $username.val(user['username']);
                            $username.attr('disabled', 'disabled');

                            $('#name').val(store['name']);
                            $('#email1').val(store['email']);
                            $('#email2').val(store['email']);
                            $('#phone').val(store['phone']);

                            $('#addressInfos').data('id', address['id']);
                            $('#address').val(address['details']);
                            $('#city').val(address['city']);
                            $('#zip').val(address['zip']);
                            $('#states').val(address['state']['id']);
                            $('#countries').val(address['state']['countryId']);
                        }
                    }
                }
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        });
}

(function ($) {
    $.QueryString = (function (a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i) {
            var p = a[i].split('=');
            if (p.length != 2) continue;
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'))
})(jQuery);