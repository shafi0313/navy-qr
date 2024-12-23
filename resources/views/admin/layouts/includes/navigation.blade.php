@php $roleId = user()->role_id @endphp
<div class="leftside-menu">
    <style>
        .logo-lg img {
            width: 50px !important;
            height: auto;
        }
    </style>
    <!-- Brand Logo Light -->
    <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ imagePath('logo', 'navy.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ imagePath('logo', 'navy.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ imagePath('logo', 'navy.png') }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ imagePath('logo', 'navy.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!--- Sidemenu -->
        <ul class="side-nav">
            <li class="side-nav-title mt-1"> Main</li>
            <li class="side-nav-item">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <i class="fa-solid fa-gauge-simple-high"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            @if (in_array($roleId, [1, 2]))
            @php
                $admin = ['admin.admin-users.*', 'admin.role.*'];
            @endphp
            <li class="side-nav-item {{ activeNav($admin) }}">
                <a data-bs-toggle="collapse" href="#sidebarAdmin" aria-expanded="false" aria-controls="sidebarAdmin"
                    class="side-nav-link">
                    <i class="fa-solid fa-user-shield"></i>
                    <span> Admin </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ openNav($admin) }}" id="sidebarAdmin">
                    <ul class="side-nav-second-level">
                        <li class="{{ activeNav('admin.roles.*') }}">
                            <a href="{{ route('admin.roles.index') }}">User Role</a>
                        </li>
                        <li class="{{ activeNav('admin.admin-users.*') }}">
                            <a href="{{ route('admin.admin-users.index') }}">User</a>
                        </li>
                        <li class="{{ activeNav('admin.specialities.*') }}">
                            <a href="{{ route('admin.specialities.index') }}">Speciality</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <li class="side-nav-item">
                <a href="{{ route('admin.applications.index') }}" class="side-nav-link">
                    <i class="fa-solid fa-id-card"></i>
                    <span> Applicants </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.application-search.index') }}" class="side-nav-link">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span> Applicant Search</span>
                </a>
            </li>
            @if (in_array($roleId, [1, 2, 3, 4, 5, 6]))
                <li class="side-nav-item">
                    <a href="{{ route('admin.primary_medicals.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-stethoscope"></i>
                        <span> Primary Medical Selection </span>
                    </a>
                </li>
            @endif
            @if (in_array($roleId, [1, 2, 3, 4, 5]))
                <li class="side-nav-item">
                    <a href="{{ route('admin.exam-marks.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-file-pen"></i>
                        <span>Written Exam </span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="{{ route('admin.written-mark-imports.index') }}" class="side-nav-link">
                        <i class="fa-regular fa-newspaper"></i>
                        <span>Written Exam Import</span>
                    </a>
                </li>
            @endif

            @if (in_array($roleId, [1, 2, 3, 4]))
                <li class="side-nav-item">
                    <a href="{{ route('admin.final_medicals.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-user-doctor"></i>
                        <span>Final Medical</span>
                    </a>
                </li>
            @endif
            @if (in_array($roleId, [1, 2, 3]))
                <li class="side-nav-item">
                    <a href="{{ route('admin.viva-marks.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-list"></i>
                        <span>Final Viva</span>
                    </a>
                </li>
            @endif
            @if (in_array($roleId, [1]))
                <li class="side-nav-item">
                    <a href="{{ route('admin.important-applications.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-user-check"></i>
                        <span>All documents held</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="{{ route('admin.important-application-imports.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-list"></i>
                        <span>All documents held Import</span>
                    </a>
                </li>
            @endif
            @if (in_array($roleId, [1, 2]))
                <li class="side-nav-item">
                    <a href="{{ route('admin.results.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Results</span>
                    </a>
                </li>
            @endif
            {{-- <li class="side-nav-title mt-2">Settings</li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSettings" aria-expanded="false"
                    aria-controls="sidebarSettings" class="side-nav-link">
                    <i class="fa-solid fa-gear"></i>
                    <span> @lang('Settings') </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSettings">
                    <ul class="side-nav-second-level">
                        <li class="{{ activeNav('admin.backup.*') }}">
                            <a href="{{ route('admin.backup.password') }}">@lang('App DB Backup')</a>
                        </li>
                    </ul>
                </div>
            </li> --}}
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
