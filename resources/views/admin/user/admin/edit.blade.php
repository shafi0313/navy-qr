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
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="name" class="form-label required">Name </label>
                            <input type="text" name="name" value="{{ old('name') ?? $admin_user->name }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label required">Email </label>
                            <input type="email" name="email" value="{{ old('email') ?? $admin_user->email }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="user_name" class="form-label">user name </label>
                            <input type="text" name="user_name" value="{{ old('name_name') ?? $admin_user->name_name }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label required">Phone </label>
                            <input type="text" name="phone" value="{{ old('phone') ?? $admin_user->phone }}"
                                class="form-control" oninput="phoneIn(event)">
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select" name="gender">
                                <option selected disabled value="">Choose...</option>
                                @foreach ($genders as $key => $gender)
                                    <option value="{{ $key }}" @selected($key == $admin_user->gender)>
                                        {{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label required">address </label>
                            <input type="text" name="address" value="{{ old('address') ?? $admin_user->address }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Old Image </label>
                            <img src="{{ imagePath('user', $admin_user->image) }}" width="100px">
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label">image </label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Old Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-12 text-center mt-2">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-warning">Update</button>
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
