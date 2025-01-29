<div class="menu">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">

                @if (auth()->user()->can('dashboard'))
                    <li class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="iconsminds-digital-drawing"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasCertificatePrivilages())
                    <li class="{{ request()->routeIs('admin.certificates*') ? 'active' : '' }}">
                        <a href="#layouts">
                            <i class="iconsminds-paper"></i>
                            Certificates
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRolesPrivilages() ||
                        auth()->user()->hasUserPrivilages())
                    <li
                        class="{{ request()->routeIs('admin.roles*') | request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="#ui">
                            <i class="iconsminds-administrator"></i> Admin
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="sub-menu">
        <div class="scroll">
            <ul class="list-unstyled" data-link="layouts" id="layouts">
                <div id="collapseAuthorization" class="collapse show">
                    <ul class="list-unstyled inner-level-menu">
                        @if (auth()->user()->can('certificates-list'))
                            <li class="{{ request()->routeIs('admin.certificates.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.certificates.index') }}">
                                    <i class="iconsminds-files"></i> <span class="d-inline-block">Certificates</span>
                                </a>
                            </li>
                        @endif

                        <li class="{{ request()->routeIs('admin.certificates.search') ? 'active' : '' }}">
                            <a href="{{ route('admin.certificates.search') }}">
                                <i class="simple-icon-magnifier"></i> <span class="d-inline-block">Search
                                    Certificate</span>
                            </a>
                        </li>

                        @if (auth()->user()->can('certificates-add'))
                            <li class="{{ request()->routeIs('admin.certificates.uploadauto') ? 'active' : '' }}">
                                <a href="{{ route('admin.certificates.uploadauto') }}">
                                    <i class="iconsminds-qr-code"></i> <span class="d-inline-block ">Upload(QR Code
                                        Auto)</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.certificates.uploadmanual') ? 'active' : '' }}">
                                <a href="{{ route('admin.certificates.uploadmanual') }}">
                                    <i class="simple-icon-cloud-upload"></i> <span class="d-inline-block">Upload(QR Code
                                        Manual)</span>
                                </a>
                            </li>
                        @endif
                        <li class="{{ request()->routeIs('admin.certificates.visitors') ? 'active' : '' }}">
                            <a href="{{ route('admin.certificates.visitors') }}">
                                <i class="simple-icon-clock"></i> <span class="d-inline-block">
                                    Visitor History
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
            <ul class="list-unstyled" data-link="ui" id="ui">
                <div id="collapseAuthorization" class="collapse show">
                    <ul class="list-unstyled inner-level-menu">
                        @if (auth()->user()->hasRolesPrivilages())
                            <li class="{{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                                <a href="{{ route('admin.roles.index') }}">
                                    <i class="iconsminds-profile"></i> <span class="d-inline-block">Admin Roles</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasUserPrivilages())
                            <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.index') }}">
                                    <i class="iconsminds-business-mens"></i> <span class="d-inline-block active">Admin
                                        Users</span>
                                </a>
                            </li>
                        @endif
                        <li class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.index') }}">
                                <i class="simple-icon-settings"></i> <span class="d-inline-block">
                                    Settings
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                </li>
            </ul>
        </div>
    </div>
</div>
