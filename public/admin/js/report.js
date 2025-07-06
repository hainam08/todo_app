
editFilter('.filter-country')
editFilter('.filter-admin')
editFilter('.filter-month')
editFilter('.filter-projects')
$('.change').on('click', function(){
    let filter = $('.filter')
    if (filter.hasClass("less")) {
        filter.removeClass("less");
        $(this).html('Tháng');
        $('#daterange').val('');
    } else {
        filter.addClass("less");
        $(this).html('Ngày');
    }
})
$(".button-filter").on("click", function () {
    let project = $(".filter-projects").val();
    let country = $(".filter-country").val();
    let admin = $(".filter-admin").val();
    let currency = $("#crrency_jp").val();
    let search = $("#search_order").val();

    let url = new URL(link);
    url.searchParams.delete('month');
    url.searchParams.delete('year');
    url.searchParams.delete('project');
    url.searchParams.delete('country');
    url.searchParams.delete('admin');
    url.searchParams.delete('day');
    url.searchParams.delete('currency');
    url.searchParams.delete('search');
    url.searchParams.delete('page');


    if ($(".filter").hasClass('less')){
        let day = $("#daterange").val();
        url.searchParams.set('day', day);
    }else {
        let month = $(".filter-month").val();
        let year = $(".filter-year").val();
        if (month[0] != 'all') {
            url.searchParams.set('month', month);
        }
        url.searchParams.set('year', year);
    }

    if (currency) {
        url.searchParams.set('currency', currency);
    }

    if (project[0] != 'all') {
        url.searchParams.set('project', project);
    }

    if (country[0] != 'all') {
        url.searchParams.set('country', country);
    }

    if (admin[0] != 'all') {
        url.searchParams.set('admin', admin);
    }

    if (search) {
        url.searchParams.delete('month');
        url.searchParams.delete('year');
        url.searchParams.delete('project');
        url.searchParams.delete('country');
        url.searchParams.delete('admin');
        url.searchParams.delete('level');
        url.searchParams.delete('status');
        url.searchParams.delete('payment');
        url.searchParams.set('search', search);
    }
    window.location.href = url;
});

$('#search-options').keypress(function (e) {
    if (e.which === 13) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của phím Enter (tải lại trang)
        let url = new URL(link);
        url.searchParams.set('search', this.value);
        window.location.href = url;
    }
});
