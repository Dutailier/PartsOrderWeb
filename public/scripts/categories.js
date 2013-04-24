$(document).ready(function () {

    // Affichage des catégories.
    $.ajax({
        type: 'GET',
        url: 'protected/getCategories.php',
        dataType: 'json',
        success: function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créés et
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

                // Gère le clic sur une catégorie.
                $('.category').click(function () {
                    window.location = 'partTypes.php?category_id=' + $(this).data('category_id');
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
        '<div class="category" data-category_id="' + id + '" >' +
            '<span>' + name + '</span>' +
            '<img src="public/images/categories/' + id + '.png" />' +
            '</div>'
    )
}