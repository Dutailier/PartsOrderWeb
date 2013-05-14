$(document).ready(function () {

    var parameters = {
        "orderHeaderId": $.queryString['orderHeaderId']
    };

    $.get('ajax/getOrderInfos.php', parameters)
        .done(function (data) {

            // Vérifie que les propriétés de l'objet JSON ont bien été créées et
            // vérifie si la requête fut un succès.
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orderHeader')) {

                if (data['orderHeader'].hasOwnProperty('retailer') &&
                    data['orderHeader'].hasOwnProperty('customer') &&
                    data['orderHeader'].hasOwnProperty('shipmentAddress')) {
                    UpdateRetailerInfos(data['orderHeader']['retailer']);
                    UpdateCustomerInfos(data['orderHeader']['customer']);
                    UpdateShippingInfos(data['orderHeader']['shipmentAddress']);
                }

                for (var i in data['orderLines']) {

                    // Vérifie que les propriétés de l'objet JSON ont bien été créés.
                    if (data['orderLines'].hasOwnProperty(i) &&
                        data['orderLines'][i].hasOwnProperty('partId') &&
                        data['orderLines'][i].hasOwnProperty('name') &&
                        data['orderLines'][i].hasOwnProperty('serialGlider') &&
                        data['orderLines'][i].hasOwnProperty('quantity')) {

                        // Ajoute la pièce à la liste.
                        AddParts(
                            data['orderLines'][i]['partId'],
                            data['orderLines'][i]['name'],
                            data['orderLines'][i]['serialGlider'],
                            data['orderLines'][i]['quantity']);
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

    $('#btnConfirm').click(function () {
        var parameters = {
            "orderHeaderId": $.queryString['orderHeaderId']
        };

        $.get('ajax/confirmOrder.php', parameters)
            .done(function (data) {

                // Vérifie que les propriétés de l'objet JSON ont bien été créées et
                // vérifie si la requête fut un succès.
                if (data.hasOwnProperty('success') &&
                    data['success']) {

                    window.location = 'thanks.php';

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
});


/**
 * Ajoute un item à la liste.
 * @param partId
 * @param categoryId
 * @param name
 * @param serialGlider
 * @param quantity
 */
function AddItem(partId, name, serialGlider, quantity) {
    $('#items').append(
        '<div class="item" data-part-id="' + partId + '">' +
            '<div class="details">' +
            '<label class="quantity">' + quantity + '</label>' +
            '<label class="name">' + name + '</label>' +
            '<label class="serialGlider">' + serialGlider + '</label>' +
            '</div>' +
            '</div>'
    );
}

function UpdateRetailerInfos(infos) {

    if (infos.hasOwnProperty('name') &&
        infos.hasOwnProperty('phone') &&
        infos.hasOwnProperty('email') &&
        infos.hasOwnProperty('address')) {

        $('#retailerName').text(infos['name']);

        // 12345678901 => 1-234-567-8910
        $('#retailerPhone').text(
            infos['phone'].substring(0, 1) + '-' +
                infos['phone'].substring(1, 4) + '-' +
                infos['phone'].substring(4, 7) + '-' +
                infos['phone'].substring(7));

        $('#retailerEmail').text(infos['email']);

        var address = infos['address'];

        if (address.hasOwnProperty('details') &&
            address.hasOwnProperty('city') &&
            address.hasOwnProperty('zip') &&
            address.hasOwnProperty('state') &&
            address['state'].hasOwnProperty('name')) {

            $('#retailerAddress').text(
                address['details'] + ', ' +
                    address['city'] + ', ' +
                    address['zip'] + ', ' +
                    address['state']['name']
            );
        }
    }
}

function UpdateCustomerInfos(infos) {

    if (infos.hasOwnProperty('firstname') &&
        infos.hasOwnProperty('lastname')) {
        $('#customerName').text(infos['firstname'] + ' ' + infos['lastname']);
    } else if (infos.hasOwnProperty('name')) {
        $('#customerName').text(infos['name']);
    }

    if (infos.hasOwnProperty('phone') &&
        infos.hasOwnProperty('email') &&
        infos.hasOwnProperty('address')) {

        // 12345678901 => 1-234-567-8910
        $('#customerPhone').text(
            infos['phone'].substring(0, 1) + '-' +
                infos['phone'].substring(1, 4) + '-' +
                infos['phone'].substring(4, 7) + '-' +
                infos['phone'].substring(7));

        $('#customerEmail').text(infos['email']);

        var address = infos['address'];

        if (address.hasOwnProperty('details') &&
            address.hasOwnProperty('city') &&
            address.hasOwnProperty('zip') &&
            address.hasOwnProperty('state') &&
            address['state'].hasOwnProperty('name')) {

            $('#customerAddress').text(
                address['details'] + ', ' +
                    address['city'] + ', ' +
                    address['zip'] + ', ' +
                    address['state']['name']
            );
        }
    }
}

function UpdateShippingInfos(address) {
    $('#shippingAddress').text(
        address['details'] + ', ' +
            address['city'] + ', ' +
            address['zip'] + ', ' +
            address['state']['name']
    );
}

/**
 * Permet d'obtenir les valeurs passés en GET.
 */
(function ($) {
    $.queryString = (function (string) {

        if (string == "") return {};

        var params = {};

        for (var i = 0; i < string.length; ++i) {
            var p = string[i].split('=');
            if (p.length != 2) continue;
            params[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return params;
    })(window.location.search.substr(1).split('&'))
})(jQuery);