<div class="row">
    <div class="col-12">
        <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
            <h4 class="page-title">{{ $title ?? null }}</h4>
            <ol class="breadcrumb m-0">
                @if (!empty($id))
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.app-instructions.show', $id) }}" target="_blank">Instruction</a>
                    </li>
                @endif
                {{-- @if ($title[0])
                    <li class="breadcrumb-item {{ !$title[1] ? 'active' : '' }}"><a
                            href="javascript: void(0);">{{ $title[0] }}</a></li>
                @endif
                @if ($title[1])
                    <li class="breadcrumb-item {{ !$title[2] ? 'active' : '' }}"><a
                            href="javascript: void(0);">{{ $title[1] }}</a></li>
                @endif
                @if ($title[2])
                    <li class="breadcrumb-item active"><a href="javascript: void(0);">{{ $title[2] }}</a></li>
                @endif --}}
            </ol>
        </div>
    </div>
</div>
