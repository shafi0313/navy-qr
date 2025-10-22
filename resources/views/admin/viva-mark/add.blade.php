<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Add Viva Marks</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')" action="{{ route('admin.viva-marks.store') }}"
                method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $applicant->id }}">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12 text-center">
                            <h5>{{ $applicant->candidate_designation }}</h5>
                            <h5>{{ $applicant->name }}</h5>
                        </div>                        
                    </div>
                    <div class="row gy-2 justify-content-center">
                        <div class="col-md-4">
                            <label for="viva" class="form-label">Viva/Final Selection </label>
                            <input type="number" step="any" name="viva"
                                value="{{ $applicant->examMark?->viva }}" id="viva" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="dup_test" class="form-label">HBsAG/Dope Test Report </label>
                            <select name="dup_test" id="dup_test" class="form-select">
                                <option value="" >Select</option>
                                <option value="yes" @selected($applicant->examMark?->dup_test == 'yes')>Pos</option>
                                <option value="no" @selected($applicant->examMark?->dup_test == 'no')>Neg</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="viva_remark" class="form-label">Remark </label>
                            <textarea name="viva_remark" id="viva_remark" class="form-control" rows="3">{{ $applicant->examMark?->viva_remark }}</textarea>
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
