let apparelPriceUrl = document.location.origin + '/api/auth/apparel-price'
let apparels = []
const JERSEY_ID = 4

function addApparel(isDiscountFull = false)
{
    // apparel product should unique per transaction
    if(apparels.some(apparel => apparel.apparel_id === parseInt($('#apparel').val()))) {
        $('#apparel-error-text').text(`${$('#apparel option:selected').text()} already listed`).show()
        return false
    } else {
        $('#apparel-error-text').hide()
    }

    if(!$('#variants').val() || $('#variants').val() === 'null') {
        $('#apparel-type-error-text').text(`Pilih Size Apparel`).show()
        return false
    } else {
        $('#apparel-type-error-text').hide()
    }
    // Check variant requested quantity
    if ($('#variants option:selected').data('quantity') < $('#quantity').val())
    {
        $('#apparel-type-error-text').text(`Stock barang kurang dari quantity pembelian`).show()
        return  false
    } else if ($('#quantity').val() <= 0) {
        $('#quantity-error-text').text(`Quantity pembelian minimal 1`).show()
        return  false
    } else {
        $('#quantity-error-text').hide()
    }

    let add = {
        'apparel_id': parseInt($('#apparel').val()),
        'apparel_name': $('#apparel option:selected').text(),
        'variant_id': $('#variants').val(),
        'type': $('#variants option:selected').text().split('-')[0],
        'quantity': $('#quantity').val(),
        'unit_price': parseInt($('#variants option:selected').data('amount')),
        'amount': isDiscountFull ?  0 : parseInt($('#amount').text()),
        'discount': isDiscountFull ? parseInt($('#amount').text()) : $('#discount').val()
    }
    if (add.apparel_id == JERSEY_ID) {
        add.jersey_number = $('#jersey_number').val()
    }
    apparels.push(add)

    $('#apparel').val(null)
    $('#variants').val('')
    $('#discount').val(null)
    $('#select_discount').val(null)
    $("#jersey_number_area").hide()
    $('#quantity').val(null)
    $('#amount').text(0)
    $('#stage').attr('readonly', true)

    let tdApparel = ``
    if (add.hasOwnProperty('jersey_number'))
    {
        tdApparel = `<td>${add.apparel_name} (No: ${add.jersey_number})</td>`
    } else
    {
        tdApparel = `<td>${add.apparel_name}</td>`
    }

    $('#apparel-table>tbody').prepend(
        `<tr id='apparel${add.apparel_id}'> ${tdApparel} <td>${add.type}</td> ` +
        `<td>${add.quantity}</td> ` + (isDiscountFull ? `` : `<td>${add.discount}</td> <td>${add.amount}</td>`) +
        `<td><button type="button" class="btn btn-danger" onclick="removeApparel(${add.apparel_id})">
                                                <i class="fa fa-trash"></i> Remove
                                            </button></td>` +
        `</tr>`

    )

    $('#apparel-table').append(`<input id="input-apparel${add.apparel_id}" type="text" name="apparels[]" value='${JSON.stringify(add)}' hidden>`)

    $('#variants').attr('disabled', true)

    sumFinalAmount();
}

function removeApparel(id)
{
    let index = apparels.findIndex(apparel => parseInt(apparel.apparel_id) === id)
    apparels.splice(index, 1)
    $('tr#apparel'+id).remove()
    $('input#input-apparel'+id).remove()

    sumFinalAmount()

    if(apparels.length === 0)
    {
        $('#stage').removeAttr('readonly')
    }
}

function fieldChange(branch_id, stage_id)
{
    // hide error apparel select and type select
    $('#apparel-error-text').hide()
    $('#apparel-type-error-text').hide()

    let product_id = $('#apparel').val()
    let apparel_price = $('#variants')

    if (!branch_id)
    {
        $('#apparel-error-text').text('Choose student branch first')
        $('#apparel-error-text').show()
    }

    // set empty apparel price select
    apparel_price.empty()
    apparel_price.val(null)
    $('#discount').val(0)
    $('#select_discount').val(null)
    $('#quantity').val(null)
    $('#amount').text('')

    // append Empty option select
    let opt = document.createElement('option');
    opt.value = null;
    opt.innerHTML = "Select Type";
    apparel_price.append(opt)

    if (product_id && branch_id)
    {
        $.ajax({
            type: 'get',
            url: apparelPriceUrl,
            data: {
                product_id, branch_id
            },
            success: function(res) {
                res.data.forEach((apparelPrice) => {
                    let opt = document.createElement('option');
                    opt.value = apparelPrice.id;
                    opt.innerHTML = apparelPrice.type.toUpperCase() + (apparelPrice.size ? ' (' + apparelPrice.size.name + ')' : '') +  '-(Stock :' + apparelPrice.quantity + ')' ;
                    opt.dataset.amount = apparelPrice.price
                    opt.dataset.stage = apparelPrice.stage_id
                    opt.dataset.quantity = apparelPrice.quantity
                    apparel_price.append(opt)
                    apparel_price.removeAttr("disabled")
                    $('#quantity').removeAttr("disabled")
                })

                changeSizeApparel(stage_id)
            },
            error: function(error){
                console.log(error.responseJSON.message);
                swal({
                        title:"Error!",
                        text: "Failed to change data",
                        type:"error",
                        html:true,
                        closeOnClickOutside: false,
                    },
                    function(){
                        location.reload();
                    });
            }
        });
    }
}

function changeSizeApparel(stage_id) {
    if (parseInt($('#apparel').val()) === JERSEY_ID && stage_id) {
        $('#variants option').hide()
        $('#variants option[data-stage="'+stage_id+'"]').show()
        $('#variants').removeAttr('disabled')
        $('#apparel-size-error-text').hide();
    } else {
        if (parseInt($('#apparel').val()) === JERSEY_ID) {
            $('#apparel-size-error-text').text('Choose Stage first');
            $('#apparel-size-error-text').show();
        } else {
            $('#apparel-size-error-text').hide();
        }
    }
}