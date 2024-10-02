// Checkbox checked
// function checkcheckbox() {
//     // Total checkboxes
//     var length = $('.check').length;
//     // Total checked checkboxes
//     var totalchecked = 0;
//     $('.check').each(function() {
//         if ($(this).is(':checked')) {
//             totalchecked += 1;
//         }
//     });

//     // Checked unchecked checkbox
//     if (totalchecked == length) {
//         $("#checkAll").prop('checked', true);
//     } else {
//         $('#checkAll').prop('checked', false);
//     }
// }

// function changeStatus(arg) {
//     let status = $(arg);
//     swal({
//             title: "Are you sure?",
//             text: "This change will affect all records!",
//             icon: "warning",
//             buttons: true,
//             dangerMode: true,
//         })
//         .then((willDelete) => {
//             if (willDelete) {
//                 $.ajax({
//                     url: status.data('route'),
//                     type: 'post',
//                     data: {
//                         status: status.data('value'),
//                     },
//                     success: res => {
//                         swal({
//                             icon: 'success',
//                             title: 'Success',
//                             text: res.message
//                         });
//                         $('.table').DataTable().ajax.reload();
//                     },
//                     error: err => {
//                         swal({
//                             icon: 'error',
//                             title: 'Oops...',
//                             text: err.responseJSON.message
//                         });
//                     }
//                 });
//             }
//         });
// }

// function select2Ajax(id, placeholder, route, dropdown = 'body') {
//     $('#' + id).select2({
//         placeholder: placeholder,
//         minimumInputLength: 2,
//         dropdownParent: $(dropdown),
//         ajax: {
//             url: route,
//             dataType: 'json',
//             delay: 250,
//             cache: true,
//             data: function(params) {
//                 return {
//                     q: $.trim(params.term)
//                 };
//             },
//             processResults: function(data) {
//                 return {
//                     results: data
//                 };
//             }
//         }
//     });
// }

// $(document).ready(function () {
//     // $(".select2single").select2();
//     // $(".select2singleModel").select2({
//     //     dropdownParent: $("#createModal").closest("div"),
//     // });

//     var fullDate = new Date();
//     var twoDigitMonth =
//         (fullDate.getMonth() + 1 < 10 ? "0" : "") + (fullDate.getMonth() + 1);
//     var currentDate =
//         (fullDate.getDate() < 10 ? "0" : "") +
//         fullDate.getDate() +
//         "/" +
//         twoDigitMonth +
//         "/" +
//         fullDate.getFullYear();
//     $(".bDP input").val(currentDate);
//     // $('#order_date').val(currentDate);
// });

// $(function () {
//     // var date = new Date();
//     // var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
//     $(".bDP input").datepicker({
//         format: "dd/mm/yyyy",
//         autoclose: true,
//         clearBtn: true,
//         todayHighlight: true,
//         container: ".bDP",
//         defaultViewDate: "today",
//         orientation: "auto",
//     });
// });

function toast(type, message) {
    cuteToast({
        type: type,
        title: type.toUpperCase(),
        message: message,
        timer: 5000,
    });
}
function digitInput(event) {
    event.target.value = event.target.value.replace(/[^\d]/g, "");
}

function floatInput(event) {
    event.target.value = event.target.value.replace(/[^\d.]/g, "");
}
function phoneIn(event) {
    event.target.value = event.target.value.replace(/[^\d.+-]/g, "");
}

// For Active Inactive
$(function () {
    document.addEventListener("DOMContentLoaded", function () {
        document
            .getElementById("is_active_input")
            .addEventListener("click", function () {
                document.getElementById("is_active_label").innerHTML = this
                    .checked
                    ? "Active"
                    : "Inactive";
            });
    });
});

// Label Asterisk Color Red
$(function () {
    // Get all <label> elements
    const labels = document.getElementsByTagName("label");
    // Iterate through each <label> element
    for (let i = 0; i < labels.length; i++) {
        const label = labels[i];
        // Get the label's text content
        const labelText = label.textContent;
        // Check if the label's text content contains '*'
        if (labelText.includes("*")) {
            // Replace the asterisk (*) with a span element
            const updatedText = labelText.replace(
                /\*/g,
                '<span style="color: red">*</span>'
            );
            // Update the label's HTML with the updated text
            label.innerHTML = updatedText;
        }
    }
});
