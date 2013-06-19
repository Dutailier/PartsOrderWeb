$(document).on('click', 'div.type', function () {
    var parameters = {
        "typeId": $(this).data('id')
    };

    $.post('ajax/setType.php', parameters)
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success']) {
                window.location = 'products.php';

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

    $.post('ajax/getTypes.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('types')) {
                var types = data['types'];

                for (var i in types) {
                    if (types.hasOwnProperty(i)) {
                        var type = types[i];

                        if (type.hasOwnProperty('id') &&
                            type.hasOwnProperty('name')) {
                            addType(type);
                        }
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

function addType(type) {
    var $type = $(
        '<div class="type" data-id="' + type['id'] + '" >' +
            '<span class="name">' + type['name'] + '</span>' +
            '<div class="image"></div>' +
            '</div>');

    $type.find('div.image').css({
        'background-image': 'url(img/types/' + $type['id'] + '.png)',
        'background-repeat': 'no-repeat',
        'background-size': 'contain',
        'background-position': 'center'
    });

    $('#types').append($type);
}
