<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Add Written Marks</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')" action="{{ route('admin.exam-marks.store') }}"
                method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $applicant->id }}">
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-12 text-center">
                            <h5>{{ $applicant->candidate_designation }}</h5>
                            <h5>{{ $applicant->name }}</h5>
                        </div>
                        @if (in_array(user()->role_id, [1,4]))
                                <div class="col-md-2">
                                    <label for="bangla" class="form-label required">Bangla </label>
                                    <input type="number" step="any" name="bangla" value="{{ $applicant->examMark?->bangla }}" id="bangla" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="english" class="form-label required">English </label>
                                    <input type="number" step="any" name="english" value="{{ $applicant->examMark?->english }}" id="english" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="math" class="form-label required">math </label>
                                    <input type="number" step="any" name="math" value="{{ $applicant->examMark?->math }}" id="math" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="science" class="form-label required">science </label>
                                    <input type="number" step="any" name="science" value="{{ $applicant->examMark?->science }}" id="science" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="general_knowledge" class="form-label required">general knowledge </label>
                                    <input type="number" step="any" name="general_knowledge" value="{{ $applicant->examMark?->general_knowledge }}" id="general_knowledge"
                                        class="form-control" required>
                                </div>
                            @endif
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
