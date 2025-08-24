<div class="modal fade" id="insShowModal" tabindex="-1" aria-labelledby="insShowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="insShowModalLabel">User Guide</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-2">
                    <h4 class="card-title">{{ config('var.menuNames')[$instruction->menu_name] }}</h4>
                    {!! $instruction->instruction !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('.instruction').summernote({
        height: 350,
    });
</script>
