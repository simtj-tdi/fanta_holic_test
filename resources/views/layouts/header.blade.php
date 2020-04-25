<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="/admin">
        <img class="navbar-brand-minimized" src="/images/fanta_logo_min.png" width="45px" height="45px" alt="RankO Logo">
        <img class="navbar-brand-full pl-4" src="/images/fanta_logo.png" width="200px" alt="RankO Logo">
    </a>

    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="icon-user"></i>
{{--                <img class="img-avatar" src="/images/fanta_logo.png" width="100" height="30" alt="RankO Logo">--}}
{{--                <img class="img-avatar" src="/images/nsmg_logo.svg" alt="">--}}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    {{--<strong> {{auth()->user()->name}}님</strong>--}}
                </div>
                <a class="dropdown-item" href="/admin/logout"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fa fa-lock"></i> Logout</a>
                </a>
            </div>
            <form id="logout-form" action="/admin/logout" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</header>
