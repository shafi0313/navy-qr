<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Add Admin User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')"
                action="{{ route('admin.admin-users.update', $admin_user->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    @bind($admin_user)
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <x-form-input name="name" label="Name *" />
                            </div>
                            <div class="col-md-6">
                                <x-form-input type="email" name="email" label="Email *" />
                            </div>
                            <div class="col-md-6">
                                <x-form-input name="user_name" label="user name " />
                            </div>
                            <div class="col-md-6">
                                <x-form-input name="phone" label="phone *" oninput="phoneIn(event)" />
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select" name="gender">
                                    <option selected disabled value="">Choose...</option>
                                    @foreach ($genders as $gender)
                                        <option value="{{ $gender['id'] }}" @selected($gender['id'] == $admin_user->gender)>
                                            {{ $gender['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <x-form-input name="address" label="address *" />
                            </div>
                            <div class="col-md-6">
                                <x-form-input type="file" name="image" label="image " />
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Old Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control">
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
