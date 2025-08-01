<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Primary Medical Fit/Unfit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')" action="{{ route('admin.primary_medicals.store') }}"
                method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $applicant->id }}">
                <div class="modal-body">
                    <div class="row gy-2 justify-content-center">
                        <div class="col-md-12 text-center">
                            <h5>{{ $applicant->candidate_designation }}</h5>
                            <h5>{{ $applicant->name }} ({{ $applicant->serial_no }})</h5>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="primary_medical" value="1" id="fit" @if (isset($applicant->is_medical_pass) && $applicant->is_medical_pass == 1) checked @endif>
                                <label class="form-check-label" for="fit">
                                    Fit
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="primary_medical" value="0" id="unfit" @if (isset($applicant->is_medical_pass) && $applicant->is_medical_pass == 0) checked @endif>
                                <label class="form-check-label" for="unfit">
                                    Unfit
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="name" class="form-label">Remark </label>
                            <input type="text" name="p_m_remark" class="form-control" value="{{ $applicant->p_m_remark ?? old('p_m_remark') }}">
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
