$(document).ready(function () {

    // Affichage des catégories.
    $.ajax({
        type: 'GET',
        url: 'protected/get_categories.php',
        dataType: 'json',
        success: function (data) {
		
			if(data['success']) {
				for (var i = 0; i < data['lenght']; i++) {
					add_category(data[i]['category_id'], data[i]['category_name']);
				}

				// Gère le clic sur une catégorie.
				$('.category').click(function () {
					window.location = 'parts.php?category_id=' + this.id;
				});
			}
			else {
				alert(data['message']);
			}
        },
		error: function() {
			alert('Communication to the database failed.');
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