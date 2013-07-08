var typeId = '';

$(document).ready(function () {

    // Évènements liés aux éléments générés initialement.

    $('a.btnMoreDetails').click(function () {
        $(this).siblings('a.btnLessDetails').show();
        $(this).siblings('div.infos').slideDown();
        $(this).hide();
    });

    $('a.btnLessDetails').hide().click(function () {
        $(this).siblings('div.infos').slideUp();
        $(this).siblings('a.btnMoreDetails').show();
        $(this).hide();
    });

    $('#changeSerial').click(function () {
        $('input.addCart').attr('disabled', true);
        $('#btnSubmit').removeAttr('disabled');
        $('#serial').removeAttr('disabled').focus();
    });

    $('#btnCancel').click(function () {
        $('#cancelDialog').dialog('open');
    });

    $('#btnClear').click(function () {
        clearCart();
    });

    $('#btnProceed').click(function () {
        $('#proceedDialog').dialog('open');
    });

    // Définition des boîtes de dialogue.

    $('#frmSerial').validate({
        rules: {
            serial: {
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 11
            }
        },

        errorPlacement: function (error, element) {
            error.appendTo(element.element());
        },
        submitHandler: function () {
            getTypeIdBySerial();
        }
    });

    $('#frmValidation').validate({
        rules: {
            purchaseDate: { required: true }
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.element());
        },
        submitHandler: function () {
            updateCategories();
        }
    });

    $('#anotherItem').dialog({
        title: 'Anything else?',
        autoOpen: false,
        modal: true,
        buttons: [
            {
                id: 'continue',
                text: 'Continue',
                click: function () {
                    $('#sameSerial').dialog('open');
                    $(this).dialog('close');
                }},
            {
                id: 'proceed',
                text: 'Proceed',
                click: function () {
                    $('#continue, #proceed').button('disable');
                    proceedTransaction();
                }
            }
        ]
    });

    $('#sameSerial').dialog({
        title: 'Same serial?',
        autoOpen: false,
        modal: true,
        buttons: [
            {
                text: 'Same',
                click: function () {
                    $(this).dialog('close');
                }},
            {
                text: 'Different',
                click: function () {
                    $(this).dialog('close');
                    var $serial = $('#serial');

                    $serial.removeAttr('disabled');
                    $('#btnSubmit').removeAttr('disabled');
                    $('input.addCart').attr('disabled', true);

                    $serial.focus();
                }
            }
        ]
    });

    $('#cancelDialog').dialog({
        title: 'Order cancelation',
        autoOpen: false,
        modal: true,
        buttons: [
            {
                id: 'cancelYes',
                text: 'Yes',
                click: function () {
                    $('#cancelYes, #cancelNo').button('disable');
                    cancelTransaction();
                }},
            {
                id: 'cancelNo',
                text: 'No',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ]
    });

    $('#proceedDialog').dialog({
        title: 'Confirmation required',
        autoOpen: false,
        modal: true,
        buttons: [
            {
                id: 'proceedYes',
                text: 'Yes',
                click: function () {
                    $('#proceedYes, #proceedNo').button('disable');
                    proceedTransaction();
                }},
            {
                id: 'proceedNo',
                text: 'No',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ]
    });

    $('#validationDialog').dialog({
        title: 'Validation',
        autoOpen: false,
        modal: true,
        width: "500px",
        resizable: false,
        buttons: [
            {
                text: 'Cancel',
                click: function () {
                    $(this).dialog('close');
                }
            },
            {
                id: 'validationNext',
                text: 'Next',
                click: function () {
                    updateCategories();
                }
            }
        ]
    });

    // Initialisation de la page.

    updateTransactionInfos();
});

// Évènements liés aux éléments générés dynamiquement.

$(document).on('click', 'input.addCart', function () {
    addItem($(this).closest('div.product'));
});

$(document).on('click', 'input.removeCart', function () {
    removeItem($(this).closest('div.item'));
});

$(document).on('click', '#categories div.infos', function () {
    var $category = $(this).closest('div.category');
    updateProductsToCategory($category);
});


/**
 * Met à jour les informations de la transaction.
 */
function updateTransactionInfos() {
    $("#purchaseDate").datepicker({
        changeMonth: true,
        changeYear: true,
        maxDate: '0'
    });

    $.post('ajax/getTransactionInfos.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('transaction')) {

                var transaction = data['transaction'];

                if (transaction.hasOwnProperty('shippingAddress') &&
                    transaction.hasOwnProperty('receiver') &&
                    transaction.hasOwnProperty('store')) {

                    var shippingAddress = data['transaction']['shippingAddress'];
                    var receiver = data['transaction']['receiver'];
                    var store = data['transaction']['store'];

                    if (shippingAddress.hasOwnProperty('details') &&
                        shippingAddress.hasOwnProperty('city') &&
                        shippingAddress.hasOwnProperty('zip') &&
                        shippingAddress.hasOwnProperty('state') &&
                        shippingAddress['state'].hasOwnProperty('name')) {
                        updateShippingAddressInfos(shippingAddress);
                    }

                    if (store.hasOwnProperty('name') &&
                        store.hasOwnProperty('phone') &&
                        store.hasOwnProperty('email') &&
                        store.hasOwnProperty('address')) {
                        var address = store['address'];

                        if (address.hasOwnProperty('details') &&
                            address.hasOwnProperty('city') &&
                            address.hasOwnProperty('zip') &&
                            address.hasOwnProperty('state') &&
                            address['state'].hasOwnProperty('name')) {
                            updateStoreInfos(store);
                        }
                    }

                    if (receiver.hasOwnProperty('name') &&
                        receiver.hasOwnProperty('phone') &&
                        receiver.hasOwnProperty('email')) {
                        updateReceiverInfos(receiver);
                    }

                    updateItems();
                }
            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        });
}


/**
 * Vérifie le numéro de série et obtient le type associé.
 */
function getTypeIdBySerial() {

    var $help = $('#help');
    var $load = $('#load');
    var $serial = $('#serial');
    var $submit = $('#btnSubmit');

    var parameters = {
        'serial': $serial.val()
    };

    $help.hide();
    $load.show();
    $('div.category').hide();
    $serial.attr('disabled', true);
    $submit.attr('disabled', true);

    $.post('ajax/getTypeIdBySerial.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('typeId')) {

                typeId = data['typeId'];
                $('#validationDialog').dialog('open');

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});
                $serial.removeAttr('disabled');
                $submit.removeAttr('disabled');
                $serial.focus();
                $help.show();
                $load.hide();

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
                $serial.removeAttr('disabled');
                $submit.removeAttr('disabled');
                $serial.focus();
                $help.show();
                $load.hide();
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
            $serial.removeAttr('disabled');
            $submit.removeAttr('disabled');
            $serial.focus();
            $help.show();
            $load.hide();
        })
}


/**
 * Vide le panier d'achats.
 */
function clearCart() {
    $.post('ajax/clearCart.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success']) {

                updateItems();

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
}


/**
 * Annule la transaction courrante.
 */
function cancelTransaction() {
    $.post('ajax/cancelTransaction.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success']) {
                window.location = 'destinations.php';

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
}


/**
 * Exécute le transaction courante.
 */
function proceedTransaction() {
    $.post('ajax/proceedTransaction.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('orderId')) {
                window.location = 'orderInfos.php?orderId=' + data['orderId'];

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
}


/**
 * Ajoute un article au panier d'achats.
 * @param $product
 */
function addItem($product) {
    var parameters = {
        "productId": $product.data('id'),
        "serial": $('#serial').val()
    };

    $.post('ajax/addItem.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {
                updateItems();
                $('#anotherItem').dialog('open');

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
}


/**
 * Retire un article du panier d'achats.
 * @param $item
 */
function removeItem($item) {
    var parameters = {
        "productId": $item.data('product-id'),
        "serial": $item.find('label.serial').text()
    };

    $.post('ajax/removeItem.php', parameters)
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success']) {
                updateItems();

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});
        })
}


/**
 * Met à jour les catégories de produits.
 */
function updateCategories() {

    var $help = $('#help');
    var $load = $('#load');
    var $serial = $('#serial');
    var $submit = $('#btnSubmit');

    $('#validationDialog').dialog('close');

    $.post('ajax/getCategories.php')
        .done(function (data) {
            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('categories')) {

                $('div.category').remove();
                var categories = data['categories'];

                for (var i in categories) {
                    if (categories.hasOwnProperty(i)) {
                        var category = categories[i];

                        if (category.hasOwnProperty('id') &&
                            category.hasOwnProperty('name')) {
                            addCategoryInfosToCategory(category);
                        }
                    }
                }

                $('#purchaseDate').val('');
                $('#damageDescription').val('');

            } else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

                $submit.removeAttr('disabled');
                $serial.removeAttr('disabled');
                $serial.focus();
                $help.show();
            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable.'});

                $submit.removeAttr('disabled');
                $serial.removeAttr('disabled');
                $serial.focus();
                $help.show();
            }
        })
        .fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed.'});

            $submit.removeAttr('disabled');
            $serial.removeAttr('disabled');
            $serial.focus();
            $help.show();
        })
        .always(function () {
            $('div.category').show();
            $load.hide();
        })
}


/**
 * Ajoute une catégories à la liste de catégories.
 */
function addCategoryInfosToCategory(category) {
    $('#categories').append(
        '<div class="category" data-id="' + category['id'] + '">' +
            '<div class="infos">' +
            '<label class="name">' + category['name'] + '</label>' +
            '</div>' +
            '</div>');
}


/**
 * Met à jours la liste de produits disponibles.
 */
function updateProductsToCategory($category) {

    var $infos = $category.children('div.infos');

    var parameters = {
        'typeId': typeId,
        'categoryId': $category.data('id')
    };

    $infos.click(false);
    $infos.animate({'opacity': 0.5});

    $.ajax({
        type: 'POST',
        url: 'ajax/getProductsByCategoryIdAndTypeId.php',
        data: parameters,
        timeout: 45000 // 45 secondes
    }).done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('products')) {

                var products = data['products'];
                var $products = $('<div class="products"></div>');

                for (var i in products) {
                    if (products.hasOwnProperty(i)) {
                        var product = products[i];

                        if (product.hasOwnProperty('id') &&
                            product.hasOwnProperty('name') &&
                            product.hasOwnProperty('description')) {
                            addProductToCategoryProducts(product, $products);
                        }
                    }
                }
                $products.appendTo($category);

                $products.children('div.product').effect('slide');

                $infos.click(function () {
                    $category.children('div.products').stop().toggle('slide');
                })

            }
            else if (data.hasOwnProperty('message')) {
                noty({layout: 'topRight', type: 'error', text: data['message']});

            } else {
                noty({layout: 'topRight', type: 'error', text: 'The result of the server is unreadable. Please try again.'});
            }
        }
    )
        .
        fail(function () {
            noty({layout: 'topRight', type: 'error', text: 'Communication with the server failed. Please try again.'});
        })
        .always(function () {
            $infos.animate({'opacity': 1});
        })
}


/**
 * Met à jour la liste d'artciles du panier d'achats.
 */
function updateItems() {
    $.post('ajax/getItems.php')
        .done(function (data) {

            if (data.hasOwnProperty('success') &&
                data['success'] &&
                data.hasOwnProperty('items')) {
                var items = data['items'];
                var $lblProducts = $('#lblProducts');
                //noinspection JSJQueryEfficiency
                var $items = $('#items').children('div.item');

                $items.remove();

                for (var i in items) {
                    if (items.hasOwnProperty(i)) {
                        var item = items[i];

                        if (item.hasOwnProperty('quantity') &&
                            item.hasOwnProperty('serial') &&
                            item.hasOwnProperty('product') &&
                            item['product'].hasOwnProperty('id') &&
                            item['product'].hasOwnProperty('name')) {
                            addItemInfos(item);
                        }
                    }
                }

                //noinspection JSJQueryEfficiency
                if ($('#items').children('div.item').length > 0) {
                    $lblProducts.show();
                    $('#btnProceed').removeAttr('disabled');
                } else {
                    $lblProducts.hide();
                    $('#btnProceed').attr('disabled', 'disabled');
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
}


/**
 * Affiche les informations relatives à l'adresse d'expédition.
 * @param address
 */
function updateShippingAddressInfos(address) {
    $('#shippingAddress').text(addressFormat(address));
}


/**
 * Affiche les informations relatives au magasin.
 * @param infos
 */
function updateStoreInfos(infos) {
    $('#storeName').text(infos['name']);
    $('#storePhone').text(phoneFormat(infos['phone']));
    $('#storeEmail').text(infos['email']);
    $('#storeAddress').text(addressFormat(infos['address']));
}


/**
 * Affiche les informations relatives au client.
 * @param infos
 */
function updateReceiverInfos(infos) {
    $('#receiverName').text(infos['name']);
    $('#receiverPhone').text(phoneFormat(infos['phone']));
    $('#receiverEmail').text(infos['email']);
}


/**
 * Ajoute les informations d'un article.
 * @param item
 */
function addItemInfos(item) {
    var $item = $(
        '<div class="item" data-product-id="' + item['product']['id'] + '">' +
            '<label class="quantity">' + item['quantity'] + '</label>' +
            '<label class="name">' + item['product']['name'] + '</label>' +
            '<label class="serial">' + item['serial'] + '</label>' +

            '<div class="buttons">' +
            '<input class="removeCart" type="button"/>' +
            '</div>' +
            '</div>)');

    $('#items').append($item);
}


/**
 * Ajoute un produit à la liste de produits.
 * @param product
 * @param $products
 */
function addProductToCategoryProducts(product, $products) {

    var $product = $(
        '<div class="product" data-id="' + product['id'] + '">' +
            '<label class="name">' + product['name'] + '</label>' +
            '<label class="description">' + product['description'] + '</label>' +

            '<div class="buttons">' +
            '<input class="addCart" type="button"/>' +
            '</div>' +
            '</div>)');

    $products.append($product.hide());
}


/**
 * Concatonne les détails de l'adresse en une seule chaîne de caractères.
 * @param address
 * @returns {string}
 */
function addressFormat(address) {
    return address['details'] + ', ' +
        address['city'] + ', ' +
        address['zip'] + ', ' +
        address['state']['name'];
}


/**
 * Transforme 12345678901 pour 1-234-567-8910.
 * @param phone
 * @returns {string}
 */
function phoneFormat(phone) {
    return phone.substring(0, 1) + '-' +
        phone.substring(1, 4) + '-' +
        phone.substring(4, 7) + '-' +
        phone.substring(7);
}