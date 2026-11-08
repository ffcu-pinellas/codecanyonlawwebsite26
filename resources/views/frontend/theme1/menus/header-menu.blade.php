<!-- Header Section Start -->
<header class="header clearfix transparent-dark">

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
                        <img id="logo_img" src="{{ $logoFavicon?$logoFavicon->logo:'' }}" alt="" style="max-height: 50px; width: auto; max-width: 100%; object-fit: contain;"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-1" aria-controls="navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation" style="border: 1.5px solid #fecc56 !important; background: rgba(17, 21, 30, 0.95) !important; padding: 6px 10px; border-radius: 8px;">
                        <span class="navbar-toggler-icon" style="filter: invert(85%) sepia(50%) saturate(1000%) hue-rotate(350deg) brightness(105%);"></span>
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

<style>
@media (max-width: 991px) {
    #navbar-collapse-1 {
        background: #0e131d !important;
        border: 1px solid #28303f !important;
        border-radius: 12px !important;
        padding: 16px 20px !important;
        margin-top: 12px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.85) !important;
    }
    #navbar-collapse-1 ul.navbar-nav > li > a {
        color: #f1f5f9 !important;
        padding: 10px 0 !important;
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
        font-weight: 600 !important;
    }
    #navbar-collapse-1 ul.navbar-nav > li > a:hover {
        color: #fecc56 !important;
    }
    #navbar-collapse-1 .nav-active {
        background: linear-gradient(135deg, #fecc56, #f0a500) !important;
        color: #000 !important;
        font-weight: 700 !important;
        border-radius: 8px !important;
        text-align: center !important;
        margin-top: 10px !important;
        padding: 8px 16px !important;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggler = document.querySelector('.navbar-toggler');
    var collapseTarget = document.getElementById('navbar-collapse-1');
    if (toggler && collapseTarget) {
        toggler.addEventListener('click', function(e) {
            if (window.jQuery && typeof jQuery(collapseTarget).collapse === 'function') {
                jQuery(collapseTarget).collapse('toggle');
            } else {
                collapseTarget.classList.toggle('show');
            }
        });
    }
});
</script>

</header>
<!-- Header Section End -->
