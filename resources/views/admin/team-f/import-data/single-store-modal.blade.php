<div class="modal fade" id="teamFSingleStoreModal" tabindex="-1" aria-labelledby="teamFSingleStoreModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="teamFSingleStoreModalLabel">Team F Single Add</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'teamFSingleStoreModal')"
                action="{{ route('admin.team_f.data_imports.single_store') }}" method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $applicant->id }}">
                <div class="modal-body">
                    <div class="row justify-content-center gy-2">
                        <div class="col-md-12 text-center">
                            <h5>{{ $applicant->candidate_designation }}</h5>
                            <h5>{{ $applicant->name }} ({{ $applicant->serial_no }})</h5>
                        </div>
                        <div class="col-md-2" style="margin-top: 35px; display: flex; align-items: center;">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="team_f" value="1"
                                    id="yes">
                                <label class="form-check-label" for="yes">
                                    Yes
                                </label>
                            </div>
                            {{-- </div>
                        <div class="col-md-2" style="margin-top: 35px;"> --}}
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="team_f" value="0"
                                    id="no">
                                <label class="form-check-label" for="no">
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="br_code" class="form-label required">Branch </label>
                            <select name="br_code" id="br_code" class="form-select">
                                <option value="">Select Branch</option>
                                @foreach (config('var.brCodes') as $k => $branch)
                                    <option value="{{ $k }}">{{ $branch }}</option>
                                @endforeach
                            </select>
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
