<!-- Header Section Start -->
<header class="header clearfix {{ request()->is('client/*') ? 'bg-dark navbar-dark position-relative' : 'transparent-dark' }}" style="{{ request()->is('client/*') ? 'background: #0b1d37;' : '' }}">

    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="contact-info">
                        <ul>
                            <li>
                                <i class="flaticon-flash"></i>
                                {{$headerSetting?$headerSetting->left_content:''}}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-sm-6  col-lg-6">
                    <div class="social-icons top-header-cell">
                        <ul>
                            <li>
                                {{$headerSetting?$headerSetting->right_content:''}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-style menu-style-1  clearfix">
        <!-- main-navigation start -->
        <div class="main-navigation main-mega-menu animated">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                    <!-- header dropdown buttons end-->
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img id="logo_img" src="{{ $logoFavicon?$logoFavicon->logo:'' }}" alt=""></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-1" aria-controls="navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>


                    <div class="collapse navbar-collapse" id="navbar-collapse-1">

                        <!-- main-menu -->
                        {!! clean($headerMenu) !!}

                        <!-- header auth button -->
                        <ul class="nav">
                            @if(Auth::user())
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link nav-active">{{ __('Dashboard') }}</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link nav-active">{{ __('Login') }}</a>
                                </li>
                            @endif
                        </ul>
                        <!-- main-menu end -->

                    </div>
                </div>
            </nav>
        </div>
        <!-- main-navigation end -->
    </div>

</header>
<!-- Header Section End -->
