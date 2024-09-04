<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalLabel">Add Admin User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'createModal')" action="{{ route('admin.admin-users.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="name" class="form-label required">Name </label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label required">Email </label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label required">phone </label>
                            <input type="text" name="phone" class="form-control" oninput="phoneIn(event)" required>
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select" name="gender">
                                <option selected disabled value="">Choose...</option>
                                @foreach (config('var.genders') as $key => $gender)
                                    <option value="{{ $key }}">{{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label">image </label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label for="address" class="form-label required">address </label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label required">password </label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label required">confirmation password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-check form-switch">
                            <label for="is_active" class="form-label status_label d-block required">Status </label>
                            <input class="form-check-input" type="checkbox" id="is_active_input" value="1"
                                name="is_active" checked>
                            <label class="form-check-label" for="is_active_input" id="is_active_label">Active</label>
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
