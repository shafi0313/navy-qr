<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Exam Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.exams.update', $exam->id) }}" method="post"
                onsubmit="ajaxStore(event, this, 'POST', 'editModal')" class="form-horizontal">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="name">Name <span class="t_r">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ $exam->name }}">
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
