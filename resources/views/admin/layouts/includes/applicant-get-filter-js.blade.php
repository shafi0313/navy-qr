<script>
    $('#district').select2({
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
                    type: 'getDistricts',
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    })
    $('#exam_date').select2({
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
                    type: 'getExamDates',
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    })
</script>
