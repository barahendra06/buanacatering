let courts = []

function calculateRow()
{
    $('#court-error-text').text('')
    $('#court-error-text').hide()
    let amount = 0;
    $('#court_type').find(':selected').each(function () {
        amount += $(this).data('amount')
    })
    let discount = $('#discount').val()
    let managerRole = $('#isManagerRole').val()

    if (!managerRole) {
        let select_discount = $('#select_discount').find(':selected').data('percentage')

        if (select_discount > -1)
        {
            discount = amount * (select_discount / 100)
        }

        $('#discount').val(discount)
    }
    let final_amount = amount - discount;
    $('#amount').text(final_amount)
}

function sumFinalAmount()
{
    let duration = $('#duration').val()
    let dates = $('#multi-date-picker').val();

    let countDates = 0;
    if (dates) {
        let dateArray = dates.split(',');

        countDates = dateArray.length;
    }

    console.log(countDates);

    let final_amount = 0
    if(courts.length > 0)
    {
        final_amount = courts.map(court => parseInt(court.amount)).reduce((prev, next) => prev + next)
    }

    $('#final_amount').val((final_amount * duration/2) * countDates)
}

function removeCourt(id)
{
    let index = courts.findIndex(court => parseInt(court.court_id) === id)
    $('tr#court'+id).remove()
    $('input#input-court'+id).remove()

    sumFinalAmount()
}

function addCourt()
{
    if(!$('#court_type').val() || $('#court_type').val() === 'null') {
        $('#court-error-text').text(`Choose Court`).show()
        return false
    } else {
        $('#court-error-text').hide()
    }

    if(courts.some(court => court.court_id === $('#court_type').val())) {
        $('#court-error-text').text(`${$('#court_type option:selected').text()} already listed`).show()
        return false
    }

    let add = {
        'court_id': $('#court_type').val(),
        'court_name': $('#court_type option:selected').text(),
        'amount': $('#amount').text(),
        'discount': $('#discount').val()
    }
    courts.push(add)

    $('#court_type').val('')
    $('#amount').text(0)
    $('#discount').val(0)
    $('#select_discount').val(null)

    sumFinalAmount();

    $('#court-table>tbody').prepend(
        `<tr id='court${add.court_id}'> <td>${add.court_name}</td>` +
        `<td>${add.discount}</td> <td>${add.amount}</td>` +
        `<td><button type="button" class="btn btn-danger" onclick="removeCourt(${add.court_id})">
                                                <i class="fa fa-trash"></i> Remove
                                            </button></td>` +
        `</tr>`
    )

    $('#court-table').append(`<input id='input-court${add.court_id}' type="text" name="courts[]" value='${JSON.stringify(add)}' hidden>`)
}

function availableDiscounts(branch_id)
{
    $('#discount').val(0)

    // Change available discount
    $('#select_discount option').hide()
    let discounts = branch_id == 3 ? [50, 70] : [10, 20];
    discounts.unshift(0)
    discounts.forEach(function (discount) {
        let opt = document.createElement('option');
        opt.value = '';
        opt.dataset.percentage = discount;
        opt.innerHTML = discount + '%'
        $('#select_discount').append(opt)
    })
}