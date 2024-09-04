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
                        hideLoadingAnimation();
                        if (confirm) {
                            if (type == "dt") {
                                $(".table").DataTable().ajax.reload();
                            } else {
                                window.location.reload();
                            }
                        }
                    });
                },
                error: (err) => {
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

function ajaxAllDelete(arg, type) {
    let args = $(arg);
    var del_ids = [];
    // Read all checked checkboxes
    $("input:checkbox[class=check]:checked").each(function () {
        del_ids.push($(this).val());
    });
    // Check checkbox checked or not
    if (del_ids.length > 0) {
        swal({
            title: "Are you sure?",
            text: "This action will delete all records and cannot be undone!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: args.data("route"),
                    type: "delete",
                    data: {
                        ids: del_ids,
                    },
                    success: (res) => {
                        swal({
                            icon: "success",
                            title: "Success",
                            text: res.message,
                        }).then((confirm) => {
                            if (confirm) {
                                if (type == "dt") {
                                    $(".table").DataTable().ajax.reload();
                                } else {
                                    window.location.reload();
                                }
                            }
                        });
                    },
                    error: (err) => {
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
}

function ajaxEdit(arg, type) {
    let args = $(arg);
    $.ajax({
        url: args.data("route"),
        type: "get",
        data: {
            id: args.data("value"),
        },
        success: (res) => {
            $("#ajax_modal_container").html(res.modal);
            $("#editModal").modal("show");
        },
        error: (err) => {
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
            hideLoadingAnimation();
            swal({
                icon: "success",
                title: "Success",
                text: res.message,
            }).then((confirm) => {
                if (confirm) {
                    $(".table").DataTable().ajax.reload();
                    $("#" + modal).modal("hide");
                    $(form).trigger("reset");
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
            //         $(".table").DataTable().ajax.reload();
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
                    $(".table").DataTable().ajax.reload();
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
                    $(".table").DataTable().ajax.reload();
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
                    $(".table").DataTable().ajax.reload();
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
                    $(".table").DataTable().ajax.reload();
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
