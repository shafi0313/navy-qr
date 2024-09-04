<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Slider</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')"
                action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    @bind($slider)
                        <div class="row gy-2">
                            <div class="col-md-12">
                                <x-form-textarea name="content" label="content" class="note_content" />
                            </div>
                            <div class="col-md-6">
                                <img src="{{ imagePath('slider', $slider->image) }}" alt="" width="200px">
                            </div>
                            <div class="col-md-6">
                                <x-form-input type="file" name="image" label="image (Width: 1920 px, Height: 1080 px)" />
                            </div>
                            <div class="col-md-4 form-check form-switch">
                                <label for="is_active" class="form-label status_label d-block required">Status </label>
                                <input class="form-check-input" type="checkbox" id="is_active_input" value="1"
                                    name="is_active" checked>
                                <label class="form-check-label" for="is_active_input" id="is_active_label">Active</label>
                            </div>
                        </div>
                    @endbind
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
        $('.note_content').summernote({
            height: 150,
        });
    })
</script>
