$(document).ready(function () {
    var moretext = `<h6><i class="mdi mdi-18px mdi-home"></i> Xem thêm ></h6>`;
    var lesstext = `<h6>< Thu gọn</h6>`;

    $(".morelink").click(function () {
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        // $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
if (document.getElementById("sa-basic"))
    document.getElementById("sa-basic").addEventListener("click", function () {
        Swal.fire({
            title: 'Any fool can use a computer',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
            showCloseButton: true
        })
    });

editFilter('.filter-country')
editFilter('.filter-status')
editFilter('.filter-payment')
editFilter('.filter-level')
editFilter('.filter-admin')
editFilter('.filter-month')
editFilter('.filter-projects')

function arraysHaveSameElements(arr1, arr2) {
    const sortedArr1 = arr1.slice().sort();
    const sortedArr2 = arr2.slice().sort();

    return JSON.stringify(sortedArr1) === JSON.stringify(sortedArr2);
}

function isSubset(array1, array2) {
    return array1.every(element => array2.includes(element));
}

$('body').on('click', '#select2-filter-admin-results strong.select2-results__group', function (e) {

    e.preventDefault();
    let $select = $('#filter-admin')
    let arr = [];
    let selectedUSer = $select.val();
    $('#filter-admin optgroup[data-label=' + $(this).text().replace(/[^\w]/gi, '') + '] option').each(function () {
        arr.push($(this).val())
    });
    if (isSubset(arr,selectedUSer)) {

        $select.val(selectedUSer.filter(item => !arr.includes(item))).change();
    } else {

        $select.val($.unique($select.select2('val').concat(arr))).change();
    }
    $select.select2('close');
})

$(".filter-project").on('select2:selecting', function (e) {
    let data = e.params.args.data;
    selectCurrency(".select-currency-create option", ".select-currency-create", project_js[data['id']]['currency'], levels['currency'])
    selectPayment(".select-package-create option", ".select-package-create", packages[data['id']])
});

//search filter
$(".button-filter").on("click", function () {
    let project = $(".filter-projects").val();
    let country = $(".filter-country").val();
    let level = $(".filter-level").val();
    let status = $(".filter-status").val();
    let payment = $(".filter-payment").val();
    let admin = $(".filter-admin").val();
    let url = new URL(link);
    url.searchParams.delete('month');
    url.searchParams.delete('year');
    url.searchParams.delete('project');
    url.searchParams.delete('country');
    url.searchParams.delete('admin');
    url.searchParams.delete('level');
    url.searchParams.delete('status');
    url.searchParams.delete('payment');

    // url.searchParams.set('day', day)
    if ($("#year-month").hasClass('active')) {
        let month = $(".filter-month").val();
        let year = $(".filter-year").val();
        if (month[0] != 'all') {
            url.searchParams.set('month', month);
        }

        url.searchParams.set('year', year);
        url.searchParams.delete('day');
    } else {
        let day = $("#daterange").val();
        url.searchParams.set('day', day);
        url.searchParams.delete('month');
        url.searchParams.delete('year');
    }
    if (project[0] != 'all') {
        url.searchParams.set('project', project);
    }

    if (country[0] != 'all') {
        url.searchParams.set('country', country);
    }

    if (level[0] != 'all') {
        url.searchParams.set('level', level);
    }
    if (status[0] != 'all') {
        url.searchParams.set('status', status);
    }
    if (payment[0] != 'all') {
        url.searchParams.set('payment', payment);
    }
    if (admin[0] != 'all') {
        url.searchParams.set('admin', admin);
    }
    window.location.href = url;
});

$("#create-btn").on("click", function () {
    $("#form-customer")[0].reset()
    $(".app-search")[0].reset()
});


//Thêm đơn hàng
$('#form-customer').on("click", "button.add-btn", function () {
    $("#form-customer").validate({
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
            let formData = new FormData($('#form-customer')[0]);
            $.ajax({
                url: orderUrl,
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

});

//Tìm kiếm
$("#search-options").on("keyup", function () {
    let text = $(this).val();
    $("#table-search tbody").empty();
    $.ajax({
        url: searchUrl,
        type: 'POST',
        data: {'search': text},
        success: function (response) {
            $("#table-search tbody").empty();
            let currentHTML = $("#table-search tbody").html();
            $.each(response['message'], function (index, element) {
                let name = element["name"] ?? ""
                let email = element["email"] ?? ""
                let phone = element["phone"] ?? ""
                let address = element["address"] ?? ""
                let link = element["link"] ?? ""
                let project = element["project"]['id'] ?? ""
                let phone_show = element["phone"] ?? ""

                let newRowHTML = `<tr><td><button type="button" data-project = "` + project + `"
                                            data-id="` + element['id'] + `" data-name="` + name + `" data-email="` + email + `"
                                            data-phone="` + phone + `"  data-address="` + address + `" data-link="` + link + `"
                                            class="btn btn-primary btn-sm add-order-for-customer">Thêm</button></td>
                                            <td>` + element['name'] + `</td>
                                            <td>` + element["project"]['name'] + `</td>
                                            <td>` + element["email"] + `</td>
                                            <td>` + phone_show + `</td>
                                            <td>` + element["created_at"] + `</td>
                                        </tr>`;
                currentHTML = currentHTML + newRowHTML;
            });
            $("#table-search tbody").html(currentHTML);
        },

    });
});

//Tìm kiếm được customer và ấn thêm đơn theo customer
$('#search-dropdown').on("click", "button.add-order-for-customer", function () {
    let id = $(this).attr('data-id') ?? ""
    let name = $(this).attr('data-name') ?? ""
    let email = $(this).attr('data-email') ??  ""
    let phone = $(this).attr('data-phone') ??  ""
    let address = $(this).attr('data-address')??  ""
    let link = $(this).attr('data-link') ?? ""
    let project = $(this).attr('data-project') ??  ""

    selectCurrency(".select-currency-create option", ".select-currency-create", project_js[project]['currency'], levels['currency'])
    selectPayment(".select-package-create option", ".select-package-create", packages[project])

    $("#project-field").val(project);
    $("#id").val(id);
    $("#name").val(name);
    $("#email").val(email);
    $("#phone").val(phone);
    $("#address").val(address);
    $("#link").val(link);
    $("#project").val(project).change();

    $(".app-search")[0].reset()
});

//Hoàn tác đơn hàng
$(".undo-first").on("click", "button.undo-item-btn", function () {
    let id = $(this).attr('data-id');
    let email = $(this).attr('data-email');
    let data = {
        "id": id,
        "email": email,
    }

    Swal.fire({
        title: "Bạn chắc chắn muốn hoàn tác đơn hàng?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",

    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: undoUrl,
                type: 'PUT',
                data: data,
                success: function (response) {
                    if (response.code == 200) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Hoàn tác đơn hàng thành công",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reloadPage(1500);
                    }
                },
                error: function (response) {
                    toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                }

            });
        }
    });
});

// show User dự án khi hoàn thành đơn hàng
function doneOrder(id, email, project) {
    let data = {
        "id": id,
        "email": email,
        "project": project,
    }

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
                url: doneUrl,
                type: 'PUT',
                data: data,
                success: function (response) {
                    if (response.code == 200) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Thành công",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reloadPage(1500);
                    }
                },
                error: function (response) {
                    toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                }

            });
        }
    });
}

//Hoàn thành đơn hàng
$("#table-show-user").on("click", "button.add-premium", function () {
    let id_user = $(this).attr('data-id');
    let id_order = $(this).attr('data-order');
    let email = $(this).attr('data-email');
    let data = {
        "id_user": id_user,
        "id_order": id_order,
    }

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
                url: doneUrl,
                type: 'PUT',
                data: data,
                success: function (response) {
                    if (response.code == 200) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Kích hoạt thành công tài khoản: " + email,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#modalShowSearch').modal("hide");
                        reloadPage(1500);
                    }
                },
                error: function (response) {
                    toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                }

            });
        }
    });
});

//Chỉnh sửa đơn hàng
$(".edit").on("click", "button.edit-item-btn", function () {
    let id = $(this).attr('data-id')

    let data = {
        "id": id,
        "level": $('.level-order_' + id).val(),
        "payment": $(".payment-order_" + id).val(),
        "currency": $(".currency-order_" + id).val(),
        "package_id": $(".package-order_" + id).val(),
        "sale": $("#id-field_" + id).val(),
        "price": $("#price_order_" + id).val(),
        "code": $("#code_" + id).val(),
        "type_sale": $(".type_sale-order_" + id).val(),
        "gift": $(".gift-order_" + id).val()
    }
    Swal.fire({
        title: "Bạn chắc chắn muốn thay đổi đơn hàng?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",

    }).then((result) => {
        if (result.isConfirmed) {
            // preloader.style.visibility = "hidden";
            $.ajax({
                url: editUrl,
                type: 'PUT',
                data: data,
                success: function (response) {

                    Swal.fire({
                        title: "Thay đổi thành công!",
                        icon: "success",
                        showConfirmButton: false,
                    });
                    reloadPage();
                },
                error: function (response) {
                    toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                }

            });
        }
    });
});
//Hủy đơn hàng
$(".cancel").on("click", "button.cancel-item-btn", function () {
    let id = $(this).attr('data-id');
    let email = $(this).attr('data-email');

    let currentHTML = '';
    $.each(levels_config, function (index, element) {
        let newRowHTML = `<option value="` + index + `">` + element + `</option>`
        currentHTML = currentHTML + newRowHTML;
    })
    let html = `<select class="js-example-basic-single form-control filter-level-cancale" name="state">` +
        currentHTML
        + `</select>`;


    Swal.fire({
        title: "Chọn lí do hủy đơn",
        html: html,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",

    }).then((result) => {
        let level = $('.filter-level-cancale').val();
        let data = {
            "id": id,
            "email": email,
            "level": level,
        }
        if (result.isConfirmed) {
            $.ajax({
                url: cancelUrl,
                type: 'PUT',
                data: data,
                success: function (response) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Hủy đơn thành công",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    reloadPage();
                },
                error: function (response) {
                    toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                }

            });
        }
    });
});
//Xóa đơn hàng
$(".remove").on("click", "button.remove-item-btn", function () {
    let id = $(this).attr('data-id');
    let currentHTML = '';
    $.each(level_remove, function (index, element) {
        let newRowHTML = `<option value="` + index + `">` + element + `</option>`
        currentHTML = currentHTML + newRowHTML;
    })
    let html = `<select class="js-example-basic-single form-control filter-level-remove" name="state">` +
        currentHTML
        + `</select>`;


    Swal.fire({
        title: "Chọn lí do xóa đơn",
        html: html,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",

    }).then((result) => {
        let level = $('.filter-level-remove').val();
        let data = {
            "id": id,
            "level": level,
        }
        if (result.isConfirmed) {
            $.ajax({
                url: removeUrl,
                type: 'DELETE',
                data: data,
                success: function (response) {
                    Swal.fire({
                        title: "Xóa thành công!",
                        icon: "success",
                        showConfirmButton: false,
                    });
                    reloadPage();
                },
                error: function (response) {
                    toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                }

            });
        }
    });
});

//Thêm ghi chú
function addNote() {
    // Lấy thứ tự của hàng cuối cùng
    let lastRowIndex = $("#table-note tbody tr:last").index();
    let key = $('.show-note').attr('data-key')
    // Tạo HTML cho hàng mới
    let newRowHTML = `<tr data-key =` + key + `>
                                <td style="text-align: center; padding-top: 20px"></td>
                                <td><input type="text" class="form-control" id="note" name="note" placeholder=""></td>
                                <td class="admin"></td>
                                <td style="padding-top: 20px" class="create"></td>
                                <td>
                                <button type="button" class="btn btn-primary edit-note"><i class="las la-plus"></i></button>
                                <button type="button" class="btn btn-danger remove-note"><i class="ri-delete-bin-line"></i></button>
                                </td>
                             </tr>`;

    // Nếu không có hàng nào trong bảng, thêm vào tbody
    if (lastRowIndex === -1) {
        $("#table-note tbody").append(newRowHTML);
    } else {
        // Nếu có ít nhất một hàng, thêm vào sau hàng cuối cùng
        $("#table-note tbody tr:last").after(newRowHTML);
    }
}

//Show note by id
function showNote(id, key) {
    $("#table-note tbody tr").remove();
    $("#exampleModalgrid").modal("show");

    $("#table-note").attr("data-id", id);
    let currentHTML = $("#table-note tbody").html();
    $.each(order[key]['notes'], function (index, element) {
        let adminName = element["admin"] == null ? 'Hệ thống' : element["admin"]["name"];
        let content = element["content"] ? '"' + element["content"] + '"' : null
        let newRowHTML = `<tr data-id = ` + element["id"] + ` data-key = ` + key + ` data-key-note = ` + index + `>
                                <td style="text-align: center; padding-top: 20px"></td>
                                <td><input type="text" class="form-control"  value= ` + content + ` id="note" name="note" placeholder=""></td>
                                <td style="padding-top: 20px" class="admin">` + adminName + `</td>
                                <td style="padding-top: 20px" class="create">` + element["created_at"] + `</td>
                                <td>
                                <button type="button" class="btn btn-primary edit-note"><i class="ri-pencil-line"></i></button>
                                <button type="button" class="btn btn-danger remove-note"><i class="ri-delete-bin-line"></i></button>
                                </td>
                             </tr>`;

        currentHTML = currentHTML + newRowHTML;
    });
    $("#table-note tbody").html(currentHTML);
}

// Xóa note
$("#table-note").on('click', '.remove-note', function () {
    let id = $("#table-note").attr("data-id")
    let row = $(this).closest('tr');
    let id_note = row.attr("data-id")
    let key = row.attr("data-key")
    let key_note = row.attr("data-key-note")
    if (key_note) {
        $.ajax({
            url: deleteNoteUrl,
            type: 'POST',
            data: {id_note: id_note},
            success: function (response) {
                // console.log(order[key]['notes'].splice(key_note, 1))
                order[key]['notes'].splice(key_note, 1);
                row.remove()
                $('.count-note-' + id).text('(' + order[key]['notes'].length + ')')
                toastNoti(response['message'], "top", "center", "success", "3000", "close", "", "")
            },
            error: function (response) {
                toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
            }
        });
    } else {
        row.remove()
    }
});
// Sua note
$("#table-note").on('click', 'button.edit-note', function () {
    let row = $(this).closest('tr');
    let id = $("#table-note").attr("data-id")
    let id_note = row.attr("data-id")
    let key = row.attr("data-key")
    let key_note = row.attr("data-key-note")
    let inputContent = row.find('#note').val()
    $.ajax({
        url: createNoteUrl,
        type: 'POST',
        data: {id: id, content: inputContent, id_note: id_note},
        success: function (response) {
            if (response['message']['status'] == 1) {
                order[key]['notes'][key_note]['content'] = inputContent;
            } else if (response['message']['status'] == 2) {
                let note = {
                    'id': response['message']['id'],
                    'content': response['message']['content'],
                    'created_at': response['message']['created_at'],
                    'order_id': response['message']['order_id'],
                    'admin': {
                        'name': response['message']['admin_id']
                    }
                }
                order[key]['notes'].push(note)
                row.find('.create').text(response['message']['created_at'])
                row.find('.admin').text(response['message']['admin_id'])
                row.find('.la-plus').removeClass('las la-plus').addClass('ri-pencil-line')
                row.attr("data-key-note", order[key]['notes'].length - 1)
                row.attr("data-id", response['message']['id'])
                $('.count-note-' + id).text('(' + order[key]['notes'].length + ')')
            }
            toastNoti('Thành công!!!', "top", "center", "success", "3000", "close", "", "")
        },
        error: function (response) {
            toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
        }

    });
});

//sửa admin sup
$('.filter-admin_sup').on('change', function () {
    let id = $(this).attr("data-id")
    let admin_id = $(this).attr("data-admin-id")
    let selectedValue = $(this).val();
    Swal.fire({
        title: "Bạn chắc chắn muốn thay đổi nhân viên hỗ trợ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",

    }).then((result) => {
        if (result.isConfirmed) {
            // preloader.style.visibility = "hidden";
            $.ajax({
                url: editAdminSup,
                type: 'PUT',
                data: {id: id, admin_sup: selectedValue},
                success: function (response) {

                    Swal.fire({
                        title: "Thay đổi thành công!",
                        icon: "success",
                        showConfirmButton: false,
                    });
                    reloadPage();
                },
                error: function (response) {
                    toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
                }

            });
        } else {
            $(this).val(admin_id);
            $(this).select2();
        }
    });
});


function showCodeMazii(day, packageName) {
    $.ajax({
        type: "POST",
        url: showCodeUrl,
        data: {
            day: day
        },
        success: function (res) {
            if (res.code == 200) {
                let code = res.message;
                $('#show-code-order').text(code);
                $('.btn-send-email').attr('data-code', code);
                $('.time-code').html(packageName);
                $('.current_lang').attr('data-package-name', packageName);

                // update display time code for mail content
                let currentLang = $(".current_lang").val();
                let dataByLang = mailLangs[currentLang];
                let transPackageName = dataByLang.packages[packageName];
                $('.time-code').text(transPackageName);
            }
        },
        error: function (response) {
            toastNoti(response.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
        }
    });
}

//chọn gói mua trong phần gửi mail
$('.btn-pick').on('click', function () {
    let parent = $(this).parent();
    let day = $(this).attr('data-day');
    let packageName = $(this).attr('data-name');
    parent.find('button.active').removeClass('active');
    $(this).addClass('active');
    showCodeMazii(day, packageName);
});

$('.btn-modal-mail').on('click', function () {
    let id = $(this).attr('data-id');
    let btnActive = $(".btn-pick-code.active");
    let day = btnActive.attr('data-day');
    let packageName = btnActive.attr('data-name');
    let name = $(this).attr('data-name');
    let email = $(this).attr('data-email');
    let modal = $("#modalSendEmail");

    $('.current_lang').attr('data-name', name);
    $('.btn-send-email').attr('data-id', id);

    updateContentMailSendCode(name, packageName);
    showCodeMazii(day, packageName);
    modal.modal('show');
});

// Chuyển ngôn ngữ nội dung mail mã code
$('.current_lang').change(function () {
    let name = $(this).attr('data-name');
    let packageName = $(this).attr('data-package-name');
    updateContentMailSendCode(name, packageName);
});

$('.btn-send-email').on('click', function () {
    let id = $(this).attr('data-id');
    let code = $(this).attr('data-code');
    let content = $('#temp-print-pur-code').html();
    let modal = $("#modalSendEmail");
    if (confirm('Đồng ý gửi mã ?')) {
        $.ajax({
            type: "POST",
            url: sendCodeUrl,
            data: {
                id: id,
                code: code,
                content: content
            },
            success: function (res) {
                if (res.code == 200) {
                    modal.modal('hide');
                    toastNoti('Gửi thành công! Đơn hàng đã hoàn thành.', "top", "center", "success", "3000", "close", "", "");
                    reloadPage();
                }
            },
            error: function (res) {
                toastNoti(res.responseJSON['message'], "top", "center", "danger", "3000", "close", "", "")
            }
        });
    }
});

$('#import-btn').on('click', function (e) {
    if (!confirm('Đồng ý xuất dữ liệu theo bộc lọc đã sử dụng ?')) {
        e.preventDefault();
    }
});

$(".select-currency-create").on("change", function() {
    let currency = $(this).val();

    if (currencyToCountry.hasOwnProperty(currency)) {
        let countryMap = currencyToCountry[currency];
        $(".select-country-create").val(countryMap).change();
    }
});

//search email
$('#search').keypress(function (e) {
    if (e.which === 13) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của phím Enter (tải lại trang)
        let url = new URL(link);
        url.searchParams.delete('month');
        url.searchParams.delete('year');
        url.searchParams.delete('project');
        url.searchParams.delete('country');
        url.searchParams.delete('admin');
        url.searchParams.delete('level');
        url.searchParams.delete('status');
        url.searchParams.delete('payment');
        url.searchParams.set('search', this.value);
        window.location.href = url;
    }
});
