$(function() {
    $('.js-example-basic-single').select2();
});

$('.btn_filter').on('click', function() {
    let adminId = $(".admin-filter").val();
    let method = $(".method-filter").val();

    let url = new URL(url_index);
    url.searchParams.set('adminId', adminId);
    url.searchParams.set('method', method);

    window.location.href = url;
})
