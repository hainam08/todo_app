//Show multijs
function loadMulti(select){
    let multiSelect = document.querySelectorAll(".multiselect");
    if (multiSelect) {
        [...multiSelect].forEach(select => {
            multi(select, {
                enable_search: false
            });
        })
    }
}
loadMulti()
//reset form khi đóng modal
$('#create-btn').on('click', function () {
    let val = $(this).attr('data-id')
    $('#id-team').val(val)
});
//Thêm user vào team
$('.tablelist-form').on("click",'button.create',  function () {
    let formData = new FormData($('.tablelist-form')[0]);
    $.ajax({
        url: createUrl,
        type: 'POST',
        processData: false, // Không xử lý dữ liệu
        contentType: false, // Không đặt header Content-Type
        data: formData,
        success: function (response) {
            toastNoti(response['message'], "top", "center", "success", "3000", "close", "", "")
            $("#createUser").modal("hide")
            reloadPage(2000);
        },
        error: function (response) {
            toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
        }
    });
});

function addIdRemove(team_id, id){
    let remove = $('#delete-user')
    remove.attr('data-team_id', team_id)
    remove.attr('data-id', id)
}


$('#delete-user').on("click", function () {
    let data = {
        'team_id': $(this).attr('data-team_id'),
        'id': $(this).attr('data-id'),
    }
    $.ajax({
        url: deleteUrl,
        type: 'DELETE',
        data: data,
        success: function (response) {
            toastNoti(response['message'], "top", "center", "success", "3000", "close", "", "")
            $("#remove").modal("hide")
            reloadPage(2000);
        },
        error: function (response) {
            toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
        }
    });
});

