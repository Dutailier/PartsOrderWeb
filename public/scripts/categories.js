$(document).on('click', 'div.category', function () {
    window.location = 'partTypes.php?category_id=' + $(this).data('category_id');
});

$(document).ready(function () {

    $.get('protected/getCategories.php')
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créées et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('categories')) {

                // Parcours tous les categories retournées par la requête.
                for (var i in data['categories']) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                    if (data['categories'].hasOwnProperty(i) &&
                        data['categories'][i].hasOwnProperty('category_id') &&
                        data['categories'][i].hasOwnProperty('category_name')) {

                        // Ajouter la catégorie à la liste.
                        add_category(
                            data['categories'][i]['category_id'],
                            data['categories'][i]['category_name']);
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

/**
 * Ajouter une catégorie.
 * @param id
 * @param name
 */
function add_category(id, name) {
    $('#categories').append(
        '<div class="category" data-category_id="' + id + '" >' +
            '<span>' + name + '</span>' +
            '<img src="../images/categories/' + id + '.png" />' +
        '</div>'
    )
}