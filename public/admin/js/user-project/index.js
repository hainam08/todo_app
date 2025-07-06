
let project = $('.filter-project').val();
$(".filter-project").change(function () {
    project = $(this).val()
    let url = new URL(link);
    url.searchParams.set('project', project);
    window.location.href = url;
});
//Tạo tabble
$('div#table-search').Grid({
    columns: ['ID', 'Email', 'Name', 'Premium', 'Created at', 'Action',],
    search: {
        server: {
            url: (prev, keyword) => `${prev}?search=${keyword}&project=${project}`
        },
        debounceTimeout: 2000
    },
    pagination: {
        limit: 10,
        server: {
            url: (prev, page, limit) => `${prev}${prev.includes('?') ? '&' : '?'}limit=${limit}&page=${page +1}&project=${project}`
        }
    },

    server: {
        url: url_show_user,
        then: data => data.result.map(function(value) {

            // Get premium info
            let premiumInfo = value.premium_info;
            let premiumHtml = '<div class="text-center">';
            if (premiumInfo.is_premium) {
                if (premiumInfo.lifetime) {
                    premiumHtml += '<span class="badge badge-gradient-info">Lifetime</span><br/>';
                } else {
                    premiumHtml += `<p>${premiumInfo.premium_expire}</p>`;
                }
            }
            premiumHtml += '</div';
            let projectHtml = `<div class="d-flex gap-2">
                                                <div div class= "edit" >
                                                    <button type="button" class="btn btn-sm btn-success edit-item-btn edit-user" data-id="${value.id}" data-email="${value.email}"> Đổi mật khẩu</button>
                                                </div>
                                                <div class="remove">
                                                    <button type="button" class="btn btn-sm btn-danger remove-item-btn remove-device" data-id="${value.id}">Xóa thiết bị</button>
                                                </div>`;
            if (value.project != 1){
                projectHtml += `<div class="active-user">
                                                    <button type="button" class="btn btn-sm btn-info active-item-btn active-user" data-id="${value.id}" data-name="${value.name}" data-email="${value.email}">Tạo đơn</button>
                                                </div>`
            }
            projectHtml += '</div';
            return [
                value.id,
                value.email,
                value.name,
                gridjs.html(premiumHtml),
                value.created_at,
                gridjs.html(projectHtml)
            ]
        }),
        total: data => data.count
    }
});
//Show modal edit pass and add value to input and validation
$("#table-search").on("click", "button.edit-user", function () {
    let id = $(this).attr("data-id");
    let email = $(this).attr("data-email");
    $("#showModal").modal("show");
    $("form#editPassword span#email_title").text(email);
    $("form#editPassword span#id_title").text(id);
    $("#id-field").val(id);
    $("#project-field").val($('.filter-project').val());
    $("#editPassword").validate({
        rules: {
            password: {
                required: true,
                minlength: 6  // You can set your own minimum length
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Vui lòng nhập mật khẩu",
                minlength: "Mật khẩu tối thiểu dài 6 ký tự"
            },
            confirm_password: {
                required: "Vui lòng nhập mật khẩu",
                equalTo: "Mật khẩu nhập lại không đúng"
            }
        }
    });

})
//Show modal delete device
$("#table-search").on("click", "button.remove-device", function () {
    let modal = $("#removeDeviceModal");
    modal.modal("show");
    modal.attr('data-project', $('.filter-project').val());
    modal.attr('data-id', $(this).attr("data-id"));
})

//Edit password
$('form#editPassword').on('submit', function (e) {
    e.preventDefault();
    let _this = $(this);
    if (_this.valid()) { // Pass validate
        let password = $("#password").val();
        let projectId = $("#project-field").val();
        let userId = $("#id-field").val();
        $.ajax({
            url: url_change_pass,
            type: 'PUT',
            data: {
                project_id: projectId,
                user_id: userId,
                password: password,
            },
            success: function (response) {
                toastNoti("Thành công", "top", "center", "success", "3000", "close", "", "")
                $("#showModal").modal("hide");
                _this[0].reset()

            },
            error: function (response) {
                toastNoti("Thất bại! Có lỗi xảy ra.", "top", "center", "danger", "3000", "close", "", "")
            }
        });
    }
});

$('#remove-device').on('click', function () {
    let modal = $("#removeDeviceModal");
    let id = modal.attr('data-id');
    let project = modal.attr('data-project');

    $.ajax({
        url: url_remove_device,
        type: 'DELETE',
        data: {
            project_id: project,
            user_id: id
        },
        success: function (response) {
            modal.modal("hide");
            toastNoti("Thành công", "top", "center", "success", "3000", "close", "", "");
        },
        error: function (response) {
            // console.log(response)
            // console.log(response.responseJSON['message'])
            modal.modal("hide");
            toastNoti("Thất bại! Có lỗi xảy ra", "top", "center", "danger", "3000", "close", "", "")
        }
    });
})

//show model active
$("#table-search").on("click", "button.active-user", function () {
    let modal = $("#createModal");
    let email = $(this).attr('data-email');
    let id = $(this).attr('data-id');
    let name = $(this).attr('data-name');
    let input_id = $("form#form-active input#id");
    let input_email = $("form#form-active input#email");
    let input_name = $("form#form-active input#name");
    let input_phone = $("form#form-active input#phone");
    let input_address = $("form#form-active input#address");


    modal.modal("show");
    $.ajax({
        url: url_check_customer,
        type: 'GET',
        data: {
            project_id: project,
            email: email
        },
        success: function (response) {
            if (response != 1){
                input_id.val(response.data.id)
                input_email.val(response.data.email)
                input_name.val(response.data.name)
                input_phone.val(response.data.phone)
                input_address.val(response.data.address)
                toastNoti(response.message, "top", "center", "success", "3000", "close", "", "");
            }else {
                input_email.val(email)
                input_name.val(name)
            }
        },
        error: function (response) {
            // console.log(response)
            // console.log(response.responseJSON['message'])
            // modal.modal("hide");
            toastNoti("Thất bại! Có lỗi xảy ra", "top", "center", "danger", "3000", "close", "", "")
        }
    })

    // modal.attr('data-project', $('.filter-project').val());
    // modal.attr('data-id', $(this).attr("data-id"));
})

//Thêm đơn hàng
$('#form-active').on("click", "button.add-btn", function () {
    $("#form-active").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            price: {
                required: true,
                number: true,
                max: 10000000,
            },
            sale: {
                max: 100,
                number: true,
            },
            name: {
                required: true,
            },

        },
        messages: {
            email: {
                required: "Vui lòng nhập email",
                email: "Đây phải là email"
            },
            price: {
                required: "Vui lòng nhập giá",
                number: "Giá tiền phải là số",
                max: "Giá tiền chưa đúng",
            },
            sale: {
                number: "% sale phải là số",
                max: "% sale không hợp lệ",
            },
            name: {
                required: "Vui lòng nhập tên",
            }
        },
        submitHandler: function (form) {
            let formData = new FormData($('#form-active')[0]);
            let email =  $("form#form-active input#email").val()
            Swal.fire({
                title: "Bạn chắc chắn kích hoạt tài khoản: " + email,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: activeUrl,
                        type: 'POST',
                        processData: false, // Không xử lý dữ liệu
                        contentType: false, // Không đặt header Content-Type
                        data: formData,
                        success: function (response) {
                            toastNoti(response['message'], "top", "center", "success", "3000", "close", "", "")
                            $("#createModal").modal("hide");
                            reloadPage(2000);
                        },
                        error: function (response) {
                            toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                        }
                    });
                }
            });

        }
    });

});
