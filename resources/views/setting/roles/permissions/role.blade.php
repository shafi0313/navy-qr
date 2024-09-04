<div class="row my-5">
    <div class="col-sm-3">
        <label for="title">{{ $name }}</label>
    </div>
    <div class="col-sm-9">
        <p>Do you want to allow members of this role to manage {{ $name }} plugin.</p>
        <div>
            <input type="checkbox" value="{{ $permission }}-manage" class="flat-red hasChildOptions" data-child_id="childOfManage{{ $permission }}"
                name="permissions[]" id="Manage{{ $permission }}" @if ($permissions[$permission.'-manage'] == 1) checked @endif>
            <label class="chk-label-margin mx-1" for="Manage{{ $permission }}">
                Yes, allow members in this role to manage {{ $name }}.
            </label>
        </div>
        <div style="@if ($permissions[$permission.'-manage'] == 1) display:block @else display:none @endif" id="childOfManage{{ $permission }}">
            <div>
                <input type="checkbox" value="{{ $permission }}-add" class="flat-red " name="permissions[]" id="create{{ $permission }}"
                    @if ($permissions[$permission.'-add'] == 1) checked @endif>
                <label class="chk-label-margin mx-1" for="create{{ $permission }}">
                    Create
                </label>
            </div>
            <div>
                <input type="checkbox" value="{{ $permission }}-edit" class="flat-red " name="permissions[]" id="edit{{ $permission }}"
                    @if ($permissions[$permission.'-edit'] == 1) checked @endif>
                <label class="chk-label-margin mx-1" for="edit{{ $permission }}">
                    Edit
                </label>
            </div>
            <div>
                <input type="checkbox" value="{{ $permission }}-delete" class="flat-red " name="permissions[]" id="delete{{ $permission }}"
                    @if ($permissions[$permission.'-delete'] == 1) checked @endif>
                <label class="chk-label-margin mx-1" for="delete{{ $permission }}">
                    Delete
                </label>
            </div>
        </div>
    </div>
</div>
