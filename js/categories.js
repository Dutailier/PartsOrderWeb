$(document).ready(function () {

    $.post('ajax/getCategories.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('categories')) {
                var categories = data['categories'];

                for (var i in categories) {
                    if (categories.hasOwnProperty(i)) {
                        var category = categories[i];

                        if (category.hasOwnProperty('id') &&
                            category.hasOwnProperty('name')) {
                            addCategory(category);
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

$(document).on('click', 'div.category', function () {
    var parameters = {
        "categoryId": $(this).data('id')
    };

    $.post('ajax/setCategory.php', parameters)
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

function addCategory(category) {
    var $category = $(
        '<div class="category" data-id="' + category['id'] + '" >' +
            '<span class="name">' + category['name'] + '</span>' +
            '</div>');

    $category.append('<div class="img"></div>').css({
        'background-image': 'url(img/categories/' + $category['id'] + '.png)',
        'background-repeat': 'no-repeat',
        'background-size': 'contain',
        'background-position': 'center'
    });

    $('#categories').append($category);
}
