$(document).on('click', 'div.destination', function () {
    var parameters = {
        "destinationId": $(this).data('id')
    };

    $.post('ajax/setDestination.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('customerInfosAreRequired')) {

                if (data['customerInfosAreRequired']) {
                    window.location = 'receiverInfos.php';
                } else {
                    window.location = 'shippingInfos.php';
                }

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

$(document).ready(function () {

    $.post('ajax/getDestinations.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('destinations')) {
                var destinations = data['destinations'];

                for (var i in destinations) {
                    if (destinations.hasOwnProperty(i)) {
                        var destination = destinations[i];

                        if (destination.hasOwnProperty('id') &&
                            destination.hasOwnProperty('name')) {
                            addDestinationsInfos(destination);
                        }
                    }
                }

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

function addDestinationsInfos(destination) {
    var $destination = $(
        '<div class="destination" data-id="' + destination['id'] + '" >' +
            '<span class="name">' + destination['name'] + '</span>' +
            '<div class="image"></div>' +
            '</div>');

    $destination.find('div.image').css({
        'background-image': 'url(img/destinations/' + destination['id'] + '.png)',
        'background-repeat': 'no-repeat',
        'background-size': 'contain',
        'background-position': 'center'
    });

    $('#destinations').append($destination);
}
