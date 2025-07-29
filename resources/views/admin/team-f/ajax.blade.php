<script>
    function application() {
        $('#application_id').select2({
            width: '100%',
            placeholder: 'Select...',
            allowClear: true,
            ajax: {
                url: window.location.origin + '/dashboard/select-2-ajax',
                dataType: 'json',
                delay: 250,
                cache: true,
                data: function(params) {
                    return {
                        q: $.trim(params.term),
                        type: 'getApplicant',
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });
    }

    $(document).ready(function() {
        application();
    });

    function ajaxEdit() {
        let applicationId = $('#application_id').select2().val();
        if (!applicationId) {
            swal({
                icon: "error",
                title: "Oops...",
                text: "Please select an application!",
            });
            application();
            return;
        }

        $.ajax({
            url: '{{ route('admin.team_f.data_imports.single_store_view') }}',
            type: "get",
            data: {
                id: applicationId,
            },
            success: (res) => {
                $("#ajax_modal_container").html(res.modal);
                $("#teamFSingleStoreModal").modal("show");
                application();
            },
            error: (err) => {
                application();
                swal({
                    icon: "error",
                    title: "Oops...",
                    text: err.responseJSON.message,
                });
            },
        });
    }
</script>
