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
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="row gy-2 mb-2">
                                <div class="col-md-12">
                                    <label for="serial_no" class="form-label">Roll No</label>
                                    <input type="text" name="serial_no" value="{{ $applicant->serial_no }}"
                                        class="form-control" readonly>
                                </div>

                                <div class="col-md-12">
                                    <label for="name" class="form-label required">Name</label>
                                    <input type="text" name="name" value="{{ $applicant->name }}" required
                                        class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <label for="permanent_phone" class="form-label required">Phone</label>
                                    <input type="text" name="permanent_phone"
                                        value="{{ $applicant->permanent_phone }}" required class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label required" for="eligible_district">District</label>
                                    <select name="eligible_district" class="form-control eligible_district"
                                        id="eligible_district" required>
                                        <option value="{{ $applicant->eligible_district }}" selected>
                                            {{ $applicant->eligible_district }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- SSC Exam -->
                            <div class="row gy-2 mb-2">
                                <div class="col">
                                    <label for="ssc_bangla" class="form-label required">SSC Bangla</label>
                                    <input type="text" name="ssc_bangla" value="{{ $applicant->ssc_bangla ?? null }}"
                                        class="form-control" required>
                                </div>
                                <div class="col">
                                    <label for="ssc_english" class="form-label required">SSC English</label>
                                    <input type="text" name="ssc_english" value="{{ $applicant->ssc_english ?? null }}"
                                        class="form-control" required>
                                </div>
                                <div class="col">
                                    <label for="ssc_math" class="form-label required">SSC Math</label>
                                    <input type="text" name="ssc_math" value="{{ $applicant->ssc_math ?? null }}"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="row gy-2 mb-2">
                                <div class="col">
                                    <label for="ssc_physics" class="form-label required">SSC Physics</label>
                                    <input type="text" name="ssc_physics" value="{{ $applicant->ssc_physics ?? null }}"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="ssc_biology" class="form-label">SSC Biology</label>
                                    <input type="text" name="ssc_biology" value="{{ $applicant->ssc_biology ?? null }}"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="ssc_gpa" class="form-label required">SSC GPA</label>
                                    <input type="text" name="ssc_gpa" value="{{ $applicant->ssc_gpa ?? null }}"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="row gy-2">
                                <div class="col-md-6">
                                    <label for="hsc_dip_gpa" class="form-label {{ $applicant->hsc_dip_group != null ? ' required' : '' }}">HSC GPA</label>
                                    <input type="text" name="hsc_dip_gpa" value="{{ $applicant->hsc_dip_gpa ?? null }}"
                                        class="form-control" {{ $applicant->hsc_dip_group != null ? 'required' : '' }}>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="row gy-2">
                                <div class="col-md-12">
                                    <label for="local_no" class="form-label required">Local No</label>
                                    <input type="text" name="local_no" value="{{ $applicant->local_no ?? '' }}"
                                        required class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Documents Submitted</label>
                                    <textarea name="doc_submitted" rows="7" class="form-control">{{ $applicant->doc_submitted ?? '' }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Documents to be Submitted to BNS SHER-E-BANGLA</label>
                                    <textarea name="doc_submitted_to_bns" rows="9" class="form-control">{{ $applicant->doc_submitted_to_bns ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row gy-2">
                        <div class="col-md-3">
                            <label for="serial_no" class="form-label">Roll No </label>
                            <input type="text" name="serial_no" value="{{ $applicant->serial_no }}"
                                class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="local_no" class="form-label required">Local No </label>
                            <input type="text" name="local_no" value="{{ $applicant->local_no ?? '' }}" required
                                class="form-control">
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


                    </div>

                    <div class="row gy-2 mt-2">
                        <div class="col">
                            <label for="ssc_bangla" class="form-label required">SSC Bangla </label>
                            <input type="text" step="any" name="ssc_bangla"
                                value="{{ $applicant->ssc_bangla ?? 0 }}" id="ssc_bangla" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="ssc_english" class="form-label required">SSC English </label>
                            <input type="text" step="any" name="ssc_english"
                                value="{{ $applicant->ssc_english ?? 0 }}" id="ssc_english" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="ssc_math" class="form-label required">SSC Math </label>
                            <input type="text" step="any" name="ssc_math"
                                value="{{ $applicant->ssc_math ?? 0 }}" id="ssc_math" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="ssc_physics" class="form-label required">SSC Physics </label>
                            <input type="text" step="any" name="ssc_physics"
                                value="{{ $applicant->ssc_physics ?? 0 }}" id="ssc_physics" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="ssc_biology" class="form-label required">SSC Biology </label>
                            <input type="text" step="any" name="ssc_biology"
                                value="{{ $applicant->ssc_biology ?? 0 }}" id="ssc_biology" class="form-control"
                                required>
                        </div>
                        <div class="col">
                            <label for="ssc_gpa" class="form-label required">SSC GPA </label>
                            <input type="text" name="ssc_gpa" value="{{ $applicant->ssc_gpa ?? 0 }}" required
                                class="form-control">
                        </div>
                    </div>
                    <div class="row gy-2 mt-2">
                        <div class="col-md-12">
                            <label for="doc_submitted" class="form-label">Documents Submitted </label>
                            <textarea name="doc_submitted" id="doc_submitted" rows="3" class="form-control">{{ $applicant->doc_submitted ?? '' }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="doc_submitted" class="form-label">Documents to be Submitted to BNS
                                SHER-E-BANGLA </label>
                            <textarea name="doc_submitted" id="doc_submitted" rows="3" class="form-control">{{ $applicant->doc_submitted ?? '' }}</textarea>
                        </div>
                    </div> --}}
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
