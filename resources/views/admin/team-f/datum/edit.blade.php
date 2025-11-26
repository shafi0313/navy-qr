<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Applicant</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')"
                action="{{ route('admin.team-f-datum.update', $applicant->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="serial_no" class="form-label required">Roll No </label>
                            <input type="text" name="serial_no" value="{{ $applicant->serial_no }}"
                                class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label required">Name </label>
                            <input type="text" name="name" value="{{ $applicant->name }}" required
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="permanent_phone" class="form-label required">Phone </label>
                            <input type="text" name="permanent_phone" value="{{ $applicant->permanent_phone }}"
                                required class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="eligible_district" required>District</label>
                            <select name="eligible_district" class="form-control eligible_district"
                                id="eligible_district" required>
                                <option value="{{ $applicant->eligible_district }}" selected>
                                    {{ $applicant->eligible_district }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="br_code" class="form-label required">Final Branch </label>
                            <select name="br_code" id="br_code" class="form-select" required>
                                <option value="">Select Branch</option>
                                @foreach (config('var.brCodes') as $k => $branch)
                                    <option value="{{ $k }}" @selected($k == $applicant->br_code)>{{ $branch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="height" class="form-label required">Height </label>
                            <input type="text" name="height" value="{{ $applicant->height }}" required
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="ssc_gpa" class="form-label required">SSC GPA </label>
                            <input type="text" name="ssc_gpa" value="{{ $applicant->ssc_gpa }}" required
                                class="form-control">
                        </div>
                    </div>
                    {{-- Written Exam --}}
                    <div class="row gy-2 mt-2">
                        <div class="col">
                            <label for="bangla" class="form-label required">Bangla </label>
                            <input type="number" step="any" name="bangla"
                                value="{{ $applicant->examMark?->bangla }}" id="bangla" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="english" class="form-label required">English </label>
                            <input type="number" step="any" name="english"
                                value="{{ $applicant->examMark?->english }}" id="english" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="math" class="form-label required">math </label>
                            <input type="number" step="any" name="math"
                                value="{{ $applicant->examMark?->math }}" id="math" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="science" class="form-label required">science </label>
                            <input type="number" step="any" name="science"
                                value="{{ $applicant->examMark?->science }}" id="science" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="general_knowledge" class="form-label required">general knowledge </label>
                            <input type="number" step="any" name="general_knowledge"
                                value="{{ $applicant->examMark?->general_knowledge }}" id="general_knowledge"
                                class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('.eligible_district').select2({
        width: '100%',
        placeholder: 'Select...',
        dropdownParent: $('#editModal'),
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
</script>
