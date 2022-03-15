<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">5</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>

        <!-- Company Name -->
        <li class="nav-item dropdown nav-margin">

            <a class="dropdown-toggle profile-pic login_profile mr-2" data-toggle="dropdown" href="#">
                <img src="{{ asset('img/company.png') }}" alt="user-img" width="36" class="img-circle">
                <b id="ambitious-user-name-id" class="hidden-xs">{{ \Illuminate\Support\Str::limit($company_full_name, 20, '...') }}</b>
                <span class="caret"></span>
            </a>


            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                @foreach ($companySwitchingInfo as $key => $value)
                    <a href="{{ route('company.companyAccountSwitch', ['company_switch' => $key]  ) }}" class="dropdown-item" @if ($key == Session::get('companyInfo')) style="background-color : #ddd" @endif>
                        <i class="fas fa-building mr-2"></i> {{ \Illuminate\Support\Str::limit($value, 20, '...') }}
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach

                <div class="dropdown-divider"></div>
                <a href="{{ route('company.index') }}" class="dropdown-item"><i class="fa fa-sliders-h mr-2"></i> @lang('Manage Company')</a>
            </div>
        </li>
        <!-- Company Name // -->

        <!-- flag -->

        <li class="nav-item dropdown">

            @php
                $locale = App::getLocale();
            @endphp
            <a class="nav-link dropdown-toggle" href="#" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @foreach ($getLang as $key => $value)
                    @if($locale == $key)
                        <span  class="flag-icon {{ $flag[$key] }}"> </span> <span id="ambitious-flag-name-id">{{ $value }}</span> </a>
                    @endif
            @endforeach
            <div class="dropdown-menu" aria-labelledby="dropdown09">
                @foreach ($getLang as $key => $value)
                       <a class="dropdown-item" href="{{ route('lang.index', ['language' => $key]) }}" @if ($key == $locale) style="background-color : #ddd" @endif><span class="flag-icon {{ $flag[$key] }}"> </span>  {{ $value }}</a>
                @endforeach
            </div>
        </li>

        <!-- flag -->

        <li class="nav-item dropdown">
            <?php
                if(Auth::user()->photo == NULL)
                {
                    $photo = "img/profile/male.png";
                } else {
                    $photo = Auth::user()->photo;
                }
            ?>

            <a class="dropdown-toggle profile-pic login_profile" data-toggle="dropdown" href="#">
                <img src="{{ asset($photo) }}" alt="user-img" width="36" class="img-circle">
                <b id="ambitious-user-name-id" class="hidden-xs">{{  strtok(Auth::user()->name, " ") }}</b>
                <span class="caret"></span>
            </a>


            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <div class="dw-user-box">
                    <div class="u-img"><img src="{{ asset($photo) }}" alt="user" /></div>
                    <div class="u-text">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p class="text-muted" style="padding-bottom: 5px;">{{ Auth::user()->email }}</p>
                        <a href="{{ route('profile.view') }}" class="btn btn-rounded btn-danger btn-sm">@lang('View Profile')</a>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('profile.view') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> @lang('My Profile')
                </a>
                <a href="{{ route('profile.setting') }}" class="dropdown-item">
                    <i class="fas fa-cogs mr-2"></i> @lang('Account Setting')
                </a>
                <a href="{{ route('profile.password') }}" class="dropdown-item">
                    <i class="fa fa-key mr-2"></i></i> @lang('Change Password')
                </a>
                <div class="dropdown-divider"></div>

                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off mr-2"></i> @lang('Logout')</a>

                <form id="logout-form" class="ambitious-display-none" action="{{ route('logout') }}" method="POST">@csrf</form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
