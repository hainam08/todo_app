let perPage = 8;
let editlist = false;

//Table
let options = {
    valueNames: [
        "id",
        "company",
        "designation",
        "date",
        "contacts",
        "type",
        "status",
    ],
    page: perPage,
    pagination: true,
    plugins: [
        ListPagination({
            left: 2,
            right: 2,
        }),
    ],
};


let applicationList = new List("applicationList", options).on("updated", function (list) {

});
$.each(result_content, function (_, element) {
    applicationList.add({
        id: '<a href="#" class="fw-medium link-primary">#VZ' + element.id + '</a>',
        company: '<div class="d-flex align-items-center">\
                    <div class="flex-shrink-0">\
                        <img src="' + element.company[0] + '" alt="" class="avatar-xxs rounded-circle image_src object-fit-cover">\
                    </div>\
                    <div class="flex-grow-1 ms-2 name">' + element.company[1] + '</div>\
                </div>',
        designation: element.designation,
        date: element.date,
        contacts: element.contacts,
        type: element.type,
        status: isStatus(element.status)
    });

    applicationList.sort('id', { order: "desc" });
});

applicationList.remove("id", '<a href="#" class="fw-medium link-primary">#VZ001</a>');


function isStatus(val) {
    switch (val) {
        case "Approved":
            return ('<span class="badge bg-success-subtle text-success text-uppercase">' + val + "</span>");
        case "New":
            return ('<span class="badge bg-info-subtle text-info text-uppercase">' + val + "</span>");
        case "Pending":
            return ('<span class="badge bg-warning-subtle text-warning text-uppercase">' + val + "</span>");
        case "Rejected":
            return ('<span class="badge bg-danger-subtle text-danger text-uppercase">' + val + "</span>");
    }
}
function filterOrder(isValue) {

    let values_status = isValue;
    applicationList.filter(function (data) {
        let statusFilter = false;
        let matchData = $($.parseHTML(data.values().status));

        let status = matchData.text();
        if (status === "All" || values_status === "All") {
            statusFilter = true;
        } else {
            statusFilter = status === values_status;
        }

        return statusFilter;
    });

    applicationList.update();
}

$(document).ready(function () {
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (event) {
        filterOrder(event.target.id);
    });
});
