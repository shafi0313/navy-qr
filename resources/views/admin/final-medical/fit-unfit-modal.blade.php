<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Final Medical Fit/Unfit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')" action="{{ route('admin.final_medicals.store') }}"
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
                                <input class="form-check-input" type="radio" name="final_medical" value=""
                                    id="pending" @if ($applicant->is_final_pass == '') checked @endif>
                                <label class="form-check-label" for="pending">
                                    Pending
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="final_medical" value="1"
                                    id="fit" @if (isset($applicant->is_final_pass) && $applicant->is_final_pass == 1) checked @endif>
                                <label class="form-check-label" for="fit">
                                    Fit
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="final_medical" value="0"
                                    id="unfit" @if (isset($applicant->is_final_pass) && $applicant->is_final_pass == 0) checked @endif>
                                <label class="form-check-label" for="unfit">
                                    Unfit
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2 justify-content-center mt-2">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Height (Fit) </label>
                            <select name="height" class="form-control" disabled>
                                <option value="5">5 Fit</option>
                                <option value="6">6 Fit</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="height2" class="form-label">Height (Inch) </label>
                            <input type="number" name="height2" class="form-control" disabled>
                        </div>
                        <div class="col-md-12">
                            <label for="name" class="form-label">Remark </label>
                            <input type="text" name="f_m_remark" class="form-control"
                                value="{{ $applicant->f_m_remark ?? old('f_m_remark') }}">
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
    $(document).ready(function() {
        const isFinalPass = '{{ $applicant->is_final_pass }}';
        if (isFinalPass == 1) {
            $('select[name="height"]').prop('disabled', false);
            $('input[name="height2"]').prop('disabled', false);
        }
        $('input[name="final_medical"]').on('change', function() {
            const value = $(this).val();
            if (value === '1') { // Fit
                $('select[name="height"]').prop('disabled', false);
                $('input[name="height2"]').prop('disabled', false);
            } else { // Pending or Unfit
                $('select[name="height"]').prop('disabled', true);
                $('input[name="height2"]').prop('disabled', true);
            }
        });
    })
</script>
