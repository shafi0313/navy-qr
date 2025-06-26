<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Final Medical Unfit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')"
                action="{{ route('admin.final_medicals.unfit_store', $application->id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="id" value="{{ $application->id }}">
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-12 text-center">
                            <h4>{{ $application->name  }}</h4>
                            <h4>{{ $application->serial_no  }}</h4>
                        </div>
                        <div class="col-md-12">
                            <label for="name" class="form-label">Remark </label>
                            <input type="text" name="f_m_remark" class="form-control">
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
