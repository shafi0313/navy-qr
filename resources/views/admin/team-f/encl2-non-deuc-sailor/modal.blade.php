<div class="modal fade" id="encl2EditModal" tabindex="-1" aria-labelledby="encl2EditModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="encl2EditModalLabel">Edit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form onsubmit="ajaxStoreModal(event, this, 'encl2EditModal')"
                action="{{ route('admin.team_f.encl2_non_deuc_sailor.update_encl_remark') }}" method="POST"> --}}
            <form action="{{ route('admin.team_f.encl2_non_deuc_sailor.update_encl_remark') }}" method="POST">
                @csrf @method('POST')
                <input type="hidden" name="application_id" value="{{ $applicant->id }}">
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <h5>{{ config('var.brCodes')[$applicant->br_code] ?? '' }}</h5>
                            <h5>{{ $applicant->name }} ({{ $applicant->serial_no }})</h5>
                        </div>
                        <div class="col-md-12">
                            <label for="br_code" class="form-label required">Remark </label>
                            <input type="text" name="encl_remark" value="{{ $applicant->encl_remark ?? '' }}"
                                class="form-control">
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
