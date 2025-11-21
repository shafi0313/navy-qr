function showLoadingAnimation() {
    $(".loading-overlay").show();
}

function hideLoadingAnimation() {
    $(".loading-overlay").hide();
}

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

function ajaxDelete(arg, type) {
    let args = $(arg);
    swal({
        title: "Are you sure?",
        text: "This action will delete this record and cannot be undone!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            showLoadingAnimation();
            $.ajax({
                url: args.data("route"),
                type: "delete",
                data: {
                    id: args.data("value"),
                },
                success: (res) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    }).then((confirm) => {
                        if (confirm) {
                            if (type == "dt") {
                                $("#data_table").DataTable().ajax.reload();
                            } else {
                                window.location.reload();
                            }
                        }
                    });
                },
                error: (err) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

function ajaxEdit(arg, type) {
    showLoadingAnimation();
    let args = $(arg);
    $.ajax({
        url: args.data("route"),
        type: "get",
        data: {
            id: args.data("value"),
        },
        success: (res) => {
            hideLoadingAnimation();
            $("#ajax_modal_container").html(res.modal);
            $("#editModal").modal("show");
        },
        error: (err) => {
            hideLoadingAnimation();
            swal({
                icon: "error",
                title: "Oops...",
                text: err.responseJSON.message,
            });
        },
    });
}

// app instruction show modal
function insShow(arg, type) {
    showLoadingAnimation();
    let args = $(arg);
    $.ajax({
        url: args.data("route"),
        type: "get",
        data: {
            id: args.data("value"),
        },
        success: (res) => {
            hideLoadingAnimation();
            $("#ajax_modal_container").html(res.modal);
            $("#insShowModal").modal("show");
        },
        error: (err) => {
            hideLoadingAnimation();
            swal({
                icon: "error",
                title: "Oops...",
                text: err.responseJSON.message,
            });
        },
    });
}

function ajaxStoreModal(e, form, modal) {
    e.preventDefault();
    // let formData = $(form).serialize();
    let formData = new FormData(form);
    showLoadingAnimation();
    $.ajax({
        url: $(form).attr("action"),
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: (res) => {
            $("#data_table").DataTable().ajax.reload(null, false);
            $("#" + modal).modal("hide");
            $(form).trigger("reset");
            hideLoadingAnimation();
            swal({
                icon: "success",
                title: "Success",
                text: res.message,
            });
        },
        error: (err) => {
            hideLoadingAnimation();
            swal({
                icon: "error",
                title: "Oops...",
                text: err.responseJSON.message,
            });
        },
    });
}
function ajaxStore(e, form) {
    e.preventDefault();
    // let formData = $(form).serialize();
    let formData = new FormData(form);
    showLoadingAnimation();
    $.ajax({
        url: $(form).attr("action"),
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: (res) => {
            hideLoadingAnimation();
            swal({
                icon: "success",
                title: "Success",
                text: res.message,
            });
            // .then((confirm) => {
            //     if (confirm) {
            //         $("#data_table").DataTable().ajax.reload();
            //         $("#" + modal).modal("hide");
            //         $(form).trigger("reset");
            //     }
            // });
        },
        error: (err) => {
            hideLoadingAnimation();
            swal({
                icon: "error",
                title: "Oops...",
                text: err.responseJSON.message,
            });
        },
    });
}

function changeStatus(arg) {
    let status = $(arg);
    swal({
        title: "Are you sure?",
        text: "This change will affect all records!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            showLoadingAnimation();
            $.ajax({
                url: status.data("route"),
                type: "PATCH",
                data: {
                    status: status.data("value"),
                },
                success: (res) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    });
                    $("#data_table").DataTable().ajax.reload();
                },
                error: (err) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

function changeStatusPatch(arg) {
    let status = $(arg);
    swal({
        title: "Are you sure?",
        text: "This change will affect all records!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            showLoadingAnimation();
            $.ajax({
                url: status.data("route"),
                type: "PATCH",
                data: {
                    status: status.data("value"),
                },
                success: (res) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    });
                    $("#data_table").DataTable().ajax.reload();
                },
                error: (err) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

function accept(arg) {
    let status = $(arg);
    swal({
        title: "Are you sure?",
        text: "This change will affect this record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            showLoadingAnimation();
            $.ajax({
                url: status.data("route"),
                type: "GET",
                data: {
                    status: status.data("value"),
                },
                success: (res) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    });
                    $("#data_table").DataTable().ajax.reload();
                },
                error: (err) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}

function reject(arg) {
    let status = $(arg);
    swal({
        title: "Are you sure?",
        text: "This change will affect this record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            showLoadingAnimation();
            $.ajax({
                url: status.data("route"),
                type: "GET",
                data: {
                    status: status.data("value"),
                },
                success: (res) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "success",
                        title: "Success",
                        text: res.message,
                    });
                    $("#data_table").DataTable().ajax.reload();
                },
                error: (err) => {
                    hideLoadingAnimation();
                    swal({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message,
                    });
                },
            });
        }
    });
}
