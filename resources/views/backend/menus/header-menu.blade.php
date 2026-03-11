<!-- WRAPPER HEADER ------------------------------------------------------------------------------>
<div id="wrapper-header">
    <!-- NAVABR -->
    <nav class="navbar navbar-expand navbar-dark navbar-danger bg-dark">
        <!-- NAVABR NAV - LEFT -->
        <ul class="navbar-nav">
            <!-- NAV ITEM - SIDEBARTOGGLE -->
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" data-toggle="class" data-target="#wrapper"
                   toggle-class="toggled">
                    <i data-toggle="switch" data-iconFirst="menu" data-iconSecond="close"
                       class="material-icons">menu</i>
                </a>
            </li>
        </ul>

        <!-- NAVABR NAV - RIGHT -->
        <ul class="navbar-nav ml-auto mr-5">

            <!-- NAV ITEM - MESSAGES -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-caret" href="javascript:void(0);" id="messages"
                   data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons ">mail_outline</i>
                    <span class="badge badge-md">{{ $contactMassage->count() }}</span>
                </a>

                @if($contactMassage->count() > 0)
                    <div class="dropdown-menu">

                        <div class="dropdown-header py-2">
                            <h6 class="dropdown-title">{{ __('New Contact message') }}</h6>
                            <a href="javascript:void(0);" class="dropdown-link ml-auto"><i class="material-icons">more_horiz</i></a>
                        </div>


                        <div class="dropdown-block p-0 style-scroll" class="c-massage">
                            <div class="box-message">
                                <ul class="message-list">
                                    @foreach($contactMassage as $complain)
                                        <li class="message-item">
                                            <div class="message-img">
                                                <img src="{{ asset('backend/assets/img/profile/blank.png') }}"
                                                     class=" img-fluid">
                                                <span class="badge badge-state bg-danger"></span>
                                            </div>
                                            <div class="message-content">
                                                <a href="{{ route('admin.contact.view',$complain->id) }}"
                                                   class="message-link">{{ $complain->f_name }}</a>
                                                <p class="message-text">{{ $complain->email }}</p>
                                                <span
                                                    class="message-time">{{ date('d-M-y h:i:s a', strtotime($complain->created_at)) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
            <!-- NAV ITEM - NOTIFICATIONS -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-caret" href="http://example.com" id="messages"
                   data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons ">notifications_none</i>
                    <span class="badge badge-md">{{ $appointmentMassage->count() }}</span>
                </a>

                @if($appointmentMassage->count() > 0)
                    <div class="dropdown-menu">

                        <div class="dropdown-header py-2">
                            <h6 class="dropdown-title">{{ __('New Appointment') }}</h6>
                            <a href="javascript:void(0);" class="dropdown-link ml-auto"><i class="material-icons">more_horiz</i></a>
                        </div>

                        <div class="dropdown-block p-0 style-scroll" class=".c-massage">
                            <div class="box-message">
                                <ul class="message-list">
                                    @foreach($appointmentMassage as $complain)
                                        <li class="message-item">
                                            <div class="message-img">
                                                <img src="{{ asset('backend/assets/img/profile/blank.png') }}"
                                                     class=" img-fluid">
                                                <span class="badge badge-state bg-danger"></span>
                                            </div>
                                            <div class="message-content">
                                                <a href="{{ route('admin.appointment.view',$complain->id) }}"
                                                   class="message-link">{{ $complain->name }}</a>
                                                <p class="message-text">{{ $complain->email }}</p>
                                                <span
                                                    class="message-time">{{ date('d-M-y h:i:s a', strtotime($complain->created_at)) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
            <!-- NAV ITEM - PARAMETRES -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-caret d-flex align-items-center" href="javascript:void(0);"
                   id="settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())

                        <img class="rounded-circle" width="32px" src="{{ Storage::url(Auth::user()?->profile_photo_path) }}"
                             alt="{{ Auth::user()?->name }}"/>
                    @else
                        <img
                            src="{{ Auth::user()->gender == 'male' ? asset('backend/assets/img/profile/male.jpg'):(Auth::user()->gender == 'female' ? asset('backend/assets/img/profile/female.jpg'):asset('backend/assets/img/profile/other.png'))  }}"
                            class="rounded-circle " width="32px">
                    @endif

                </a>
                <div class="dropdown-menu" class="languse">
                    <p class="text-center my-1"><small class="text-muted">{{ __('Manage Accounts') }}</small></p>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}"><i
                            class="material-icons">person</i> {{ __('Profile') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" id="logOut" href="javascript:void(0);"><i
                            class="material-icons">power_settings_new</i> {{ __('Log Out') }}
                        <form action="{{ route('logout') }}" method="post" id="logOutForm">
                            @csrf
                        </form>
                    </a>
                </div>
            </li>
        </ul>

    </nav>
</div>
<!-- END WRAPPER HEADER -------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------------>
