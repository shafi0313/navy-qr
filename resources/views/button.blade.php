@if ($type == 'ajax-edit')
    <button data-route="{{ $route }}" data-value="{{ $row->id }}" onclick="ajaxEdit(this)"
        class='text-primary _btn' title="@lang('Edit')">
        <i class='fa fa-edit'></i>
    </button>
@endif

@if ($type == 'ajax-add-by-id')
    <button data-route="{{ $route }}" data-value="{{ $row->id }}" onclick="ajaxEdit(this)"
        class='text-primary _btn' title="@lang('Add or Edit')">
        {{-- <i class="fa-solid fa-plus"></i>  --}}
        <i class="fa-solid fa-pen-to-square"></i>
    </button>
@endif
@if ($type == 'unfit')
    <button data-route="{{ $route }}" data-value="{{ $row->id }}" onclick="ajaxEdit(this)"
        class='btn btn-danger btn-rem' title="@lang('Unfit')">
        Unfit
    </button>
@endif
@if ($type == 'fit')
    <button data-route="{{ $route }}" data-value="{{ $row->id }}" onclick="ajaxEdit(this)"
        class='btn btn-primary btn-rem' title="@lang('Fit')">
        Fit
    </button>
@endif

@if ($type == 'edit')
    <a href="{{ route($route . '.edit', $row->id) }}" class='text-primary _btn' title="@lang('Edit')">
        <i class='fa fa-edit'></i>
    </a>
@endif

@if ($type == 'delete')
    <button data-route="{{ route($route . '.destroy', $row->id) }}" class='_delete text-danger _btn'
        title="@lang('Delete')">
        <i class='fa fa-trash'></i>
    </button>
@endif

@if ($type == 'ajax-delete')
    <button data-route="{{ $route }}" data-value="{{ $row->id }}"
        onclick="ajaxDelete(this, '{{ $src }}')" class='text-danger _btn' title="@lang('Delete')">
        <i class='fa fa-trash' style="vertical-align: middle;"></i>
    </button>
@endif

@if ($type == 'view')
    <a href="{{ route($route . '.show', $row->id) }}" class='text-secondary _btn' title="@lang('Show-details')">
        <i class='fa fa-eye'></i>
    </a>
@endif

@if ($type == 'is_active')
    <span data-route="{{ $route }}"
        style="font-size: 25px;line-height: 1;vertical-align: middle;cursor: pointer;" data-value="{{ $row }}"
        onclick="changeStatusPatch(this)">
        @if ($row == 1)
            <i class="fa fa-toggle-on text-success" title="Active"></i>
        @else
            <i class="fa fa-toggle-off text-danger" title="Inactive"></i>
        @endif
    </span>
@endif

@if ($type == 'toggle-btn')
    <label class="custom-switch form-switch mb-0" data-route="{{ $route }}" data-value="{{ $row->status }}"
        onclick="changeStatus(this)">
        <input {{ $row->status == 1 ? 'checked' : '' }} type="checkbox" name="switch" class="custom-switch-input">
        <span class="custom-switch-indicator custom-switch-indicator-md"></span>
    </label>
@endif
