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
                alert(data['message']);

            } else {
                alert('The result of the server is unreadable.');
            }
        })
        .fail(function () {
            alert('Communication with the server failed.');
        })
});

$(document).ready(function () {

    $.post('ajax/getDestinations.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('destinations')) {

                for (var i in data['destinations']) {
                    if (data['destinations'].hasOwnProperty(i) &&
                        data['destinations'][i].hasOwnProperty('id') &&
                        data['destinations'][i].hasOwnProperty('name')) {
                        AddDestination(data['destinations'][i]);
                    }
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
});

function AddDestination(destination) {
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
