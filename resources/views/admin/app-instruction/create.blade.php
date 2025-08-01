<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalLabel">Add App Instruction</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'createModal')"
                action="{{ route('admin.app-instructions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="menu_name" class="form-label required">Menu Name </label>
                            <select name="menu_name" id="menu_name" class="form-select" required>
                                <option value="">Select</option>
                                @foreach (config('var.menuNames') as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="instruction" class="form-label required">Instructions </label>
                            <textarea name="instruction" id="instruction" class="form-control instruction" required></textarea>
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

