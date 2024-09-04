
<div class="row my-5">
    <div class="col-sm-3">
        <label for="title">{{ $name }}</label>
    </div>
    <div class="col-sm-9">
        <p>Do you want to allow members of this role to manage {{ $name }} plugin.</p>
        <div>
            <input type="checkbox" value="user-manage" class="flat-red hasChildOptions"
                data-child_id="childOfManageUser" name="permissions[]" id="ManageUser"
                @if($permissions['user-manage']==1) checked @endif>
            <label class="chk-label-margin mx-1" for="ManageUser">
                Yes, allow members in this role to manage {{ $name }}.
            </label>
        </div>
        <div style="@if($permissions['user-manage'] == 1) display:block @else display:none @endif"
            id="childOfManageUser">
            <div>
                <input type="checkbox" value="user-add" class="flat-red " name="permissions[]" id="createUser"
                    @if($permissions['user-add']==1) checked @endif>
                <label class="chk-label-margin mx-1" for="createUser">
                    Create
                </label>
            </div>
            <div>
                <input type="checkbox" value="user-edit" class="flat-red " name="permissions[]" id="editUser"
                    @if($permissions['user-edit']==1) checked @endif>
                <label class="chk-label-margin mx-1" for="editUser">
                    Edit
                </label>
            </div>
            {{-- <div>
                <input type="checkbox" value="user-status" class="flat-red " name="permissions[]" id="statusUser"
                    @if($permissions['user-status']==1) checked @endif>
                <label class="chk-label-margin mx-1" for="statusUser">
                    Status
                </label>
            </div> --}}
            <div>
                <input type="checkbox" value="user-delete" class="flat-red " name="permissions[]" id="deleteUser"
                    @if($permissions['user-delete']==1) checked @endif>
                <label class="chk-label-margin mx-1" for="deleteUser">
                    Delete
                </label>
            </div>
        </div>
    </div>
</div>
