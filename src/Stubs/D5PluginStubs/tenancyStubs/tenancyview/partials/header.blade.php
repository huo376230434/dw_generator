<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ admin_base_path('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{!! config('admin.logo-mini', config('admin.name')) !!}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{!! config('admin.logo', config('admin.name')) !!}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        {!! Tenancy::getNavbar()->render('left') !!}

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                {!! Tenancy::getNavbar()->render() !!}

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
{{--                        <img src="{{ Tenancy::user()->avatar }}" class="user-image" alt="User Image">--}}
                        <!-- hidden-xs hides the account on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Tenancy::user()->name  ?? "unknown"}} </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                          <li class="user-header">
                            {{--<img src="{{ Tenancy::user()->avatar }}" class="img-circle" alt="User Image">--}}

                            <p>
                                {{ Tenancy::user()->name }}
                                <small>上次修改时间{{ Tenancy::user()->updated_at }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ admin_base_path('auth/setting') }}" class="btn btn-default btn-flat">{{ trans('admin.setting') }}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('tenancy/auth/logout') }}" class="btn btn-default btn-flat">{{ trans('admin.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                {{--<li>--}}
                    {{--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
                {{--</li>--}}
            </ul>
        </div>
    </nav>
</header>
