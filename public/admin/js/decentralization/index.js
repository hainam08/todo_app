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


//Ajax chung
function create(data, url, type, option = null) {
    $.ajax({
        url: url,
        type: type,
        processData: false, // Không xử lý dữ liệu
        contentType: false, // Không đặt header Content-Type
        data: data,
        success: function (response) {
            toastNoti(response['message'], "top", "center", "success", "3000", "close", "", "")
            if (option == 'edit_password') {
                $("#showEditPassword").modal("hide")
            }
            else if (option == 'delete'){
                $("#deleteDecent").modal("hide")
            }
            else {
                $("#showModal").modal("hide")
            }
            reloadPage(2000);
        },
        error: function (response) {
            toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
        }
    });
}
//Thêm route vào link khi ấn thay đổi pass
$('.password-btn').on("click", function () {
    let link = $(this).attr('data-link')
    let password_btn = $('.edit-password-btn')
    password_btn.attr('data-link', link)
});

//thay đổi pass
$('#edit-password').on("click", "button.edit-password-btn", function () {
    $("#edit-password").validate({
        rules: {
            edit_password: {
                required: true,
                minlength: 6  // You can set your own minimum length
            },
            confirm_password: {
                required: true,
                equalTo: "#edit_password"
            }
        },
        messages: {
            edit_password: {
                required: "Vui lòng nhập mật khẩu",
                minlength: "Mật khẩu tối thiểu dài 6 ký tự"
            },
            confirm_password: {
                required: "Vui lòng nhập mật khẩu",
                equalTo: "Mật khẩu nhập lại không đúng"
            }
        },
        submitHandler: function (form) {
            let formData = new FormData($('#edit-password')[0]);
            create(formData, $('.edit-password-btn').attr('data-link'), 'POST',  'edit_password')
        }
    });

});

//Thêm user
$('.user-create-form').on("click",'button.create-btn-user',  function () {
    $('.user-create-form').validate({
        rules: {
            password: {
                required: true,
                minlength: 6  // You can set your own minimum length
            },
            show: {
                required: true,
            },
            mail: {
                required: true,
                mail: true
            }

        },
        messages: {
            email: {
                required: "Vui lòng nhập email",
                email: "Đây phải là email"
            },
            show: {
                required: "Vui lòng nhập tên",
            },
            password: {
                required: "Vui lòng nhập mật khẩu",
                minlength: "Mật khẩu tối thiểu dài 6 ký tự"
            }
        },
        submitHandler: function (form) {
            let formData = new FormData($('.user-create-form')[0]);
            create(formData, createUrl, 'POST', 'create')

        }
    });

});

//Thêm các quyền, chức năng, team
$('.create-btn').on("click", function () {
    let name = $('.modal-body').find('a.active').attr('data-name')
    let form_name = $('.' + name +'-create-form')
    $(form_name).validate({
        rules: {
            display_name: {
                required: true,
            },
        },
        submitHandler: function (form) {
            let formData = new FormData(form_name[0]);
            create(formData, createUrl, 'POST',  'create')
        }
    });
});

//Thêm id vào nút xóa
$('.remove-item-btn').on("click", function () {
    $('#delete-record').attr('data-link',  $(this).attr('data-link'))
});

//Chức năng xóa
$('#delete-record').on("click", function () {
    let link = $(this).attr('data-link')
    let data = {}
    create(data, link, 'DELETE',  'delete')
});
//Thêm dữ liệu của data cần chỉnh sửa
$('.list-edit').on("click", 'a.edit-item-btn',function () {
    let id = $(this).attr('data-id')
    let name = $(this).attr('data-name')
    let data
    if (name == "team") {
        data = team
    }else if (name == "role"){
        data = role
    }else if  (name == "user"){
        data = user
    }else{
        data = permission
    }
    $.each(data, function (_, element) {
        if (element['id'] == id){
            let select  = '.filter-role'
            let select_team  = '.filter-team'
            editFilter(select)
            let parent = $('.'+ name +'-edit-form')
            if(name == 'user'){
                if(element['team']){
                    $(select_team + " option").attr('selected', false);
                    $(select_team).val(element['team']['id']);
                    $(select_team).select2();
                }
                if (element['roles'].length != 0) {
                    $(select + " option").attr('selected', false);
                    let result = element['roles'].map(value => value.id)
                    $(select).val(result);
                    $(select).select2();
                }
                parent.find('#edit-'+ name +'-name-show').val(element['name'])
                parent.find('#edit-'+ name +'-email').val(element['email'])
                parent.find('#edit-'+ name +'-name').val(name)
                parent.find('#edit-'+ name +'-id').val(element['id'])
            }else {
                if (name == 'role'){
                    let select  = '.filter-permission'
                    editFilter(select)
                    if (element['permission'].length != 0) {
                        $(select + " option").attr('selected', false);
                        let result = element['permission'].map(value => value.id)
                        $(select).val(result);
                        $(select).select2();
                    }
                }
                if (name == 'team'){
                    let select  = '.filter-role_team'
                    editFilter(select)
                    if (element['role_team'].length != 0) {
                        $(select + " option").attr('selected', false);
                        let result = element['role_team'].map(value => value.id)
                        $(select).val(result);
                        $(select).select2();
                    }
                }
                parent.find('#edit-'+ name +'-display_name').val(element['display_name'])
                parent.find('#edit-'+ name +'-description').val(element['description'])
                parent.find('#edit-'+ name +'-name').val(name)
                parent.find('#edit-'+ name +'-id').val(element['id'])
            }

            $('.edit-btn').attr('data-name', name)
        }
    })
});
//Chỉnh sửa
$('.edit-btn').on("click", function () {
    let name = $(this).attr('data-name')
    let formData = new FormData($('.' + name +'-edit-form')[0]);
    create(formData, editUrl, 'POST', null)
});


