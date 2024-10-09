<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
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
                    <div class="row gy-2">
                        <div class="col-md-12 text-center">
                            <h5>{{ $applicant->candidate_designation }}</h5>
                            <h5>{{ $applicant->name }}</h5>
                        </div>
                        <div class="row gy-2 justify-content-center">
                            <div class="col-md-4">
                                <label for="viva" class="form-label">Viva/Final Selection </label>
                                <input type="number" step="any" name="viva"
                                    value="{{ $applicant->examMark?->viva }}" id="viva" class="form-control">
                            </div>
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
