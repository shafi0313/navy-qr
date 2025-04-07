<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')"
                action="{{ route('admin.admin-users.update', $admin_user->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="role_id" class="form-label required">Role </label>
                            <select name="role_id" id="role_id" class="form-select" required>
                                <option value="">Select</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected($role->id==$admin_user->role_id)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="team" class="form-label">Team </label>
                            <select name="team" id="team" class="form-select">
                                <option value="">Select</option>
                                @foreach (config('var.teams') as $k => $v)
                                    <option value="{{ $k }}" @selected($k==$admin_user->team)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="is_2fa" class="form-label required">2 Factor Authentication (OTP Login) </label>
                            <select name="is_2fa" id="is_2fa" class="form-select" required>
                                <option value="">Select</option>
                                <option value="0" @selected($admin_user->is_2fa == 1)>No</option>
                                <option value="1" @selected($admin_user->is_2fa == 2)>Yes</option>
                            </select>
                        </div>
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
                            <label for="mobile" class="form-label">mobile </label>
                            <input type="text" name="mobile" value="{{ old('mobile') ?? $admin_user->mobile }}"
                                class="form-control" oninput="phoneIn(event)">
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">address </label>
                            <input type="text" name="address" value="{{ old('address') ?? $admin_user->address }}"
                                class="form-control">
                        </div>
                        {{-- <div class="col-md-3">
                            <label class="form-label">Old Image </label>
                            <img src="{{ imagePath('user', $admin_user->image) }}" width="100px">
                        </div>
                        <div class="col-md-3">
                            <label for="image" class="form-label">image </label>
                            <input type="file" name="image" class="form-control">
                        </div> --}}
                        {{-- <div class="col-md-6">
                            <label for="password" class="form-label">Old Password</label>
                            <input type="password" name="old_password" class="form-control">
                        </div> --}}
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
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
