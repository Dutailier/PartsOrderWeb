$(document).ready(function () {

    // Affichage des catégories.
    $.ajax({
        type: 'GET',
        url: 'protected/getCategories.php',
        dataType: 'json',
        success: function (data) {

            if (data['success']) {
                for (var i in data['categories']) {
                    add_category(
                        data['categories'][i]['category_id'],
                        data['categories'][i]['category_name']);
                }

                // Gère le clic sur une catégorie.
                $('.category').click(function () {
                    window.location = 'partTypes.php?category_id=' + this.id;
                });
            }
            else {
                alert(data['message']);
            }
        },
        error: function () {
            alert('Communication to the server failed.');
        }
    });
});

/**
 * Ajouter une catégorie.
 * @param id
 * @param name
 */
function add_category(id, name) {
    $('#categories').append(
        '<div id="' + id + '" class="category">' +
            '<span>' + name + '</span>' +
            '<img src="public/images/categories/' + id + '.png" />' +
            '</div>'
    )
}