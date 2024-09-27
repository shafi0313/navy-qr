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
        <!-- Leftbar User -->
        <div class="leftbar-user p-3 text-white">
            <a href="{{ route('admin.my-profiles.index') }}" class="d-flex align-items-center text-reset">
                <div class="flex-shrink-0">
                    <img src="{{ profileImg() }}" alt="user-image" height="42" class="rounded-circle shadow">
                </div>
                <div class="flex-grow-1 ms-2">
                    <span class="fw-semibold fs-15 d-block">{{ user()->name }}</span>
                    {{-- <span class="fs-13">Founder</span> --}}
                </div>
                <div class="ms-auto">
                    <i class="ri-arrow-right-s-fill fs-20"></i>
                </div>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">
            <li class="side-nav-title mt-1"> Main</li>
            <li class="side-nav-item">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <i class="fa-solid fa-gauge-simple-high"></i>
                    <span> Dashboard </span>
                </a>
            </li>

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
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('admin.applications.index') }}" class="side-nav-link">
                    <i class="fa-solid fa-id-card"></i>
                    <span> Applications </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.primary_medicals.index') }}" class="side-nav-link">
                    <i class="fa-solid fa-list"></i>
                    <span> Primary Medical Entry </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.final_medicals.index') }}" class="side-nav-link">
                    <i class="fa-solid fa-list"></i>
                    <span> Final Medical Entry </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.exam-marks.create') }}" class="side-nav-link">
                    <i class="fa-solid fa-list"></i>
                    <span> Mark Entry </span>
                </a>
            </li>

            <li class="side-nav-title mt-2">Settings</li>
            {{-- settings --}}
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
            </li>



            {{-- <li class="side-nav-item">
                <a href="" class="side-nav-link">
                    <i class="ri-calendar-2-fill"></i>
                    <span> Calendar </span>
                </a>
            </li> --}}

            {{-- <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarEmail" aria-expanded="false" aria-controls="sidebarEmail"
                    class="side-nav-link">
                    <i class="ri-mail-fill"></i>
                    <span> Email </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarEmail">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="#">Inbox</a>
                        </li>
                        <li>
                            <a href="#">Read Email</a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- <li class="side-nav-title mt-2">Custom</li> --}}

            {{-- <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false"
                    aria-controls="sidebarMultiLevel" class="side-nav-link">
                    <i class="ri-share-fill"></i>
                    <span> Multi Level </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMultiLevel">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false"
                                aria-controls="sidebarSecondLevel">
                                <span> Second Level </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarSecondLevel">
                                <ul class="side-nav-third-level">
                                    <li>
                                        <a href="javascript: void(0);">Item 1</a>
                                    </li>
                                    <li>
                                        <a href="javascript: void(0);">Item 2</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarThirdLevel" aria-expanded="false"
                                aria-controls="sidebarThirdLevel">
                                <span> Third Level </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarThirdLevel">
                                <ul class="side-nav-third-level">
                                    <li>
                                        <a href="javascript: void(0);">Item 1</a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarFourthLevel" aria-expanded="false"
                                            aria-controls="sidebarFourthLevel">
                                            <span> Item 2 </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarFourthLevel">
                                            <ul class="side-nav-forth-level">
                                                <li>
                                                    <a href="javascript: void(0);">Item 2.1</a>
                                                </li>
                                                <li>
                                                    <a href="javascript: void(0);">Item 2.2</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li> --}}


        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
