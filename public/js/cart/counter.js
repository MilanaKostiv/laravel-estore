/**
 * Cast quantity to int.
 *
 * @param {string} quantity
 * @returns {number}
 */
function format(quantity) {
    let quantityInt = parseInt(quantity);
    quantityInt = isNaN(quantityInt) ? 0 : quantityInt;
    return quantityInt;
}

/**
 * Increment quantity.
 *
 * @param {object} quantity
 * @returns {number}
 */
function increment(quantity) {
    let quantityInt = format(quantity.value);
    quantityInt++;

    return quantityInt;
}

/**
 * Decrement quantity.
 *
 * @param {object} quantity
 * @returns {number}
 */
function decrement(quantity) {
    let quantityInt = format(quantity.value);
    quantityInt = ( (quantityInt == 0) || ( (quantityInt - 1) == 0) ) ? 1 : quantityInt - 1;

    return quantityInt;
}

/**
 * Sends ajax request to update product's quantity in cart.
 *
 * @param {object} element
 * @param {number} quantityInt
 */
function updateQuantity(element, quantityInt) {
    let dataId = $(element).attr('data-id');
    let productId = $(element).attr('product-id');

    let cartTableRow = $(element).closest('.cart-table-row');
    let overlay = $(element).closest('.cart-table-row-wrapper').find('.overlay');
    overlay.css('display', 'block');

    $('.alert-success, .alert-danger').slideUp( "slow", function() {
        $('.alert-success, .alert-danger').text('');
    });

    axios.patch(`/cart/${dataId}`, {
        quantity: quantityInt,
        productId: productId
    })
        .then(function (response) {
            overlay.css('display', 'none');

            if (response.data.itemQty) {
                element.value = response.data.itemQty;
            }

            if (response.data.success) {
                if(response.data.itemSubtotal) {
                    cartTableRow.find('.product-subtotal').text(response.data.itemSubtotal);
                }

                $('div.cart-totals-subtotal span.result-total').text(response.data.total);
                $('div.cart-totals-subtotal span.result-subtotal').text(response.data.subtotal);
                $('div.cart-totals-subtotal span.result-tax').text(response.data.tax);

                $('header ul span.cart-count span').text(response.data.quantity);
            }

            if (response.data.errors.length != 0) {
                console.log('error');
                let html = '<ul>';
                response.data.errors.forEach(function(element) {
                    html += '<li>' + element + '</li>';
                });

                html += '</ul>';

                $(".alert-danger").text('');
                $(".alert-danger").slideDown( "slow", function() {
                  $(".alert-danger").append(html);
                });
            }
        })
        .catch(function (error) {
            overlay.css('display', 'none');
            $(".alert-danger").text(error);
            $(".alert-danger" ).slideDown();
        });
}

$('.quantity-container .plus').on('click', function() {
    let quantity = $(this).closest('.quantity-container').find('.quantity')[0];
    let quantityInt = increment(quantity);
    updateQuantity(quantity, quantityInt);
});

$('.quantity-container .minus').on('click', function() {
    let quantity = $(this).closest('.quantity-container').find('.quantity')[0];
    let quantityInt = decrement(quantity);
    updateQuantity(quantity, quantityInt);
});

$('.quantity').on('keypress', function(event){
    if (event.which < 48 || event.which > 57) {
        event.preventDefault();
    }
});

let timeout;

$('.quantity').on('keyup', function(event){
    clearTimeout(timeout);
    let element = this;
    let value = this.value;
    timeout = setTimeout(updateQuantity.bind(element, element, value),1000);
});
