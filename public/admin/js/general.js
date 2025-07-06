var link = window.location.href;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
// $( document ).on( "ajaxStart", function() {
//     let preload = $("#preloader");
//     preload.css({opacity : "0.5", visibility : "visible"});
// } );
//
// $( document ).on( "ajaxComplete", function() {
//     let preload = $("#preloader");
//     preload.css({opacity : "0", visibility : "hidden"});
// } );



function reloadPage(time = 1500) {
    setTimeout(() => {
        window.location.reload(true);
    }, time);
}

function editFilter(classFilter) {
    $(classFilter).on('select2:selecting', function (e) {
        let data = e.params.args.data;

        if (data['id'] === 'all'){
            $(classFilter).val('all').trigger("change")
        }else {
            $(classFilter + " option[value='all']").prop('selected', false);
        }
    });
}

function toastNoti(text, gravity, position, className, duration, close, style, offset){
    Toastify({
        newWindow: true,
        text: text,
        gravity: gravity,
        position: position,
        className: "bg-" + className,
        stopOnFocus: true,
        offset: {
            x: offset ? 50 : 0,
            y: offset ? 10 : 0
        },
        duration: duration,
        close: "close" == close,
        style: "style" == style ? {
            background: "linear-gradient(to right, #0AB39C, #405189)"
        } : ""
    }).showToast()
}

function selectCurrency(nameOption, nameClass, data, level){
    $(nameOption).remove();
    let currentCurrency = $(nameClass).html();

    if (data.length > 0) {
        $.each(data, function (index, element) {
            let newRow = `<option value="`+ element +`">` + element + `</option>`
            currentCurrency = currentCurrency + newRow;
        })
    }else {
        $.each(level, function (index, element) {
            let newRow = `<option value="`+ element +`">` + element + `</option>`
            currentCurrency = currentCurrency + newRow;
        })
    }

    $(nameClass).html(currentCurrency);
}

function selectPayment(nameOption, nameClass, data){
    $(nameOption).remove();
    let currentHTML = $(nameClass).html();

    $.each(data, function (index, element) {
        if (element['status'] == 1){
            let newRowHTML = `<option value="`+ element['id'] +`">` + element['display'] + `</option>`
            currentHTML = currentHTML + newRowHTML;
        }
    })
    $(nameClass).html(currentHTML);
}

// Jquery logic
$('.modal').on('hidden.bs.modal', function () {
    $.each( $(".form"), function (_, element) {
        element.reset()
    });

});
$(document).ready(function () {
    $('.js-example-basic-multiple').select2();
    $('.js-example-basic-single').each(function() {
        $(this).select2({
          dropdownParent: $(this).parent(), // fix select2 search input focus bug
        })
    })
});


