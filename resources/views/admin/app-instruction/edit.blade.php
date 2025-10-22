<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit App Instruction</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')"
                action="{{ route('admin.app-instructions.update', $appInstruction->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="menu_name" class="form-label">Menu Name </label>
                            <select name="menu_name" id="menu_name" class="form-select">
                                <option value="">Select</option>
                                @foreach (config('var.menuNames') as $k => $v)
                                    <option value="{{ $k }}" @selected($k == $appInstruction->menu_name)>{{ $v }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="instruction" class="form-label required">Instructions </label>
                            <textarea name="instruction" id="instruction" class="form-control instruction">{!! $appInstruction->instruction !!}</textarea>
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
    $('.instruction').summernote({
        height: 350,
    });
</script>
