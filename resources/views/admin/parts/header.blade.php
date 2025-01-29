<nav class="navbar fixed-top">
    <div class="d-flex align-items-center navbar-left">
        <a href="#" class="menu-button d-none d-md-block">
            <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                <rect x="0.48" y="0.5" width="7" height="1" />
                <rect x="0.48" y="7.5" width="7" height="1" />
                <rect x="0.48" y="15.5" width="7" height="1" />
            </svg>
            <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                <rect x="1.56" y="0.5" width="16" height="1" />
                <rect x="1.56" y="7.5" width="16" height="1" />
                <rect x="1.56" y="15.5" width="16" height="1" />
            </svg>
        </a>

        <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                <rect x="0.5" y="0.5" width="25" height="1" />
                <rect x="0.5" y="7.5" width="25" height="1" />
                <rect x="0.5" y="15.5" width="25" height="1" />
            </svg>
        </a>

        <form class="search d-none align-content-center justify-content-center d-md-flex" method="POST"
            action="{{ route('admin.certificates.search') }}">
            @csrf
            <div class="form-row w-100">
                <div class="form-group col-md-12 mb-0">
                    <input autofocus name="q" required type="text" class="form-control" id="inputEmail4"
                        placeholder="Search certificate no / test / item / lot ">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2 mb-0">
                    <button class="border-0" type="submit">
                        <span class="search-icon">
                            <i class="simple-icon-magnifier"></i>
                        </span>
                    </button>
                </div>
            </div>
        </form>

        {{-- <div class="search" data-search-path="?q=">
            
            

            <input placeholder="Search..." />
            <span class="search-icon">
                <i class="simple-icon-magnifier"></i>
            </span>
        </div> --}}
    </div>

    <a class="navbar-logo" href="{{ route('admin.dashboard') }}">
        <span class="logo d-block"></span>
    </a>

    <div class="navbar-right">
        <div class="user d-inline-block">
            <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="name">{{ auth()->user()->name }}</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right mt-3">
                <a class="dropdown-item" href="{{ route('admin.user.profile') }}">Account</a>
                <a class="dropdown-item" href="#"
                    onclick="document.getElementById('logoutForm').submit();">Logout</a>
            </div>
        </div>
    </div>
</nav>
