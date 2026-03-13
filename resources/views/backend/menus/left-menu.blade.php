<!-- WRAPPER LEFT -------------------------------------------------------------------------------->
<div id="wrapper-left">
    <!-- SIDEBAR -->
    <div class="sidebar sidebar-dark sidebar-danger bg-dark">
        <!-- SIDEBAR HEADER -->
        <div class="sidebar-header border-fade">
            <!-- SIDEBAR BRAND -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <!-- SIDEBAR BRAND IMG -->
                <img class="sidebar-brand-img" src="{{ $logoFavicon ? $logoFavicon->logo : ' ' }}" />
                <!-- SIDEBAR BRAND TEXT -->
            </a>
            <!-- SIDEBAR CLOSE -->
            <a href="javascript:void(0);" class="sidebar-close d-md-none" data-toggle="class" data-target="#wrapper"
                toggle-class="toggled">
                <i class="material-icons icon-sm">close</i>
            </a>
        </div>
        <!-- SIDEBAR CONTAINER -->
        <div class="sidebar-container style-scroll-dark">
            <!-- SIDEBAR PROFILE -->
            <div class="sidebar-profile border-fade">
                <!-- SIDEBAR PROFILE IMG -->
                <div class="d-flex align-items-center">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img src="{{ Storage::url(Auth::user()?->profile_photo_path) }}" alt="{{ Auth::user()?->name }}"
                            class="img-fluid img-thumbnail sidebar-profile-img" />
                    @else
                        <img src="{{ Auth::user()->gender == 'male' ? asset('backend/assets/img/profile/male.jpg') : (Auth::user()->gender == 'female' ? asset('backend/assets/img/profile/female.jpg') : asset('backend/assets/img/profile/other.png')) }}"
                            class="img-fluid img-thumbnail sidebar-profile-img" />
                    @endif
                </div>

                <!-- SIDEBAR PROFILE INFO -->
                <div class="sidebar-profile-info">
                    <h6>{{ auth()->user()->name }}</h6>
                    <!-- SIDEBAR ACTIONS -->
                    <div class="sidebar-actions">
                        <a href="{{ route('admin.profile') }}" class="keep"><i
                                class="material-icons">person_outline</i></a>
                        <a href="{{ route('admin.contact.index') }}"><i class="material-icons"
                                class="m-icon">mail_outline</i></a>
                        <a href="{{ route('admin.appointment.index') }}"><i
                                class="material-icons">notifications_none</i></a>
                        <a href="{{ route('admin.profile') }}"><i class="material-icons"
                                class="m-icon">settings</i></a>
                    </div>
                </div>
            </div>
            <!-- SIDEBAR NAV -->
            <ul class="sidebar-nav">
                <!-- NAV ITEM dashboard -->
                <li class="nav-item {{ strpos(request()->path(), '/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="link-text">{{ __('dashboard') }}</span>
                    </a>
                </li>

                <!-- NAV ITEM Settings  -->
                @can('settings')
                    <li class="nav-item has-dropdown {{ strpos(request()->path(), '/settings/') ? 'active' : '' }}"
                        id="settingsMenuTab">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-cogs"></i>
                            <span class="link-text">{{ __('Settings') }}</span>
                            <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                        </a>
                        <ul class="dropdown-list">
                            <li><a href="{{ route('admin.settings.general') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('General Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.menu.category.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Menu Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.settings.topHeader.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Header Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.settings.footer.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Footer Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.settings.social-media') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Social Media') }}</span></a></li>
                            <li><a href="{{ route('admin.settings.logo-favicon') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Logo Favicon') }}</span></a></li>
                            <li><a href="{{ route('admin.settings.seo') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Seo Settings') }}</span></a>
                            </li>
                            <li><a href="{{ route('admin.settings.smtp') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('smtp settings') }}</span></a>
                            </li>

                            <li><a href="{{ route('admin.settings.insert-header-footer') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('insert header footer') }}</span></a>
                            </li>

                        </ul>
                    </li>
                @endcan

                <!-- NAV ITEM page settings  -->
                @can('page_settings')
                    <li class="nav-item has-dropdown {{ strpos(request()->path(), '/page-settings/') ? 'active' : '' }}"
                        id="settingsMenuTab">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="material-icons">pages</i>
                            <span class="link-text">{{ __('Page Settings') }}</span>
                            <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                        </a>
                        <ul class="dropdown-list">
                            <li><a href="{{ route('admin.page-settings.home-page.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Home Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.contact-page.index') }}" class="nav-link">
                                    <i class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Contact Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.about-page.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('About Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.services-page.index') }}" class="nav-link">
                                    <i class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Services Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.cases-page.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Cases Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.blogs-page.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Blogs Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.teams-page.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Teams Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.faq-page.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('FAQ Page Settings') }}</span></a></li>
                            <li><a href="{{ route('admin.page-settings.client-dashboard-page.index') }}"
                                    class="nav-link"> <i class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Client Dashboard Settings') }}</span></a></li>
                        </ul>
                    </li>
                @endcan

                <!-- NAV ITEM dynamic page -->
                @can('dynamic_page')
                    <li class="nav-item has-dropdown {{ request()->is('admin/dynamic-page*') ? 'active' : '' }}"
                        id="settingsMenuTab">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="material-icons">pages</i>
                            <span class="link-text">{{ __('Dynamic Pages') }}</span>
                            <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                        </a>
                        <ul class="dropdown-list">
                            <li><a href="{{ route('admin.dynamic-page.page-index') }}" class="nav-link"> <i
                                        class="material-icons">add</i> <span
                                        class="link-text">{{ __('Add New Page') }}</span></a></li>
                            @foreach ($systemPages as $systemPage)
                                <li><a href="{{ route('admin.dynamic-page.page-index', $systemPage->slug) }}"
                                        class="nav-link"> <i class="material-icons">chevron_right</i> <span
                                            class="link-text">{{ __(str_replace('_', ' ', $systemPage->name)) }}</span></a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endcan

                <!-- NAV ITEM Slider  -->
                @can('slider_settings')
                    <li class="nav-item {{ request()->is('admin/slider*') ? 'active' : '' }}" id="settingsMenuTab">
                        <a href="{{ route('admin.slider.index') }}" class="nav-link">
                            <i class="material-icons">collections</i>
                            <span class="link-text">{{ __('Slider') }}</span>
                        </a>
                    </li>
                @endcan

                <!-- NAV ITEM Testimonial  -->
                @can('testimonial')
                    <li class="nav-item {{ request()->is('admin/testimonial*') ? 'active' : '' }}" id="settingsMenuTab">
                        <a href="{{ route('admin.testimonial.index') }}" class="nav-link">
                            <i class="material-icons">perm_identity</i>
                            <span class="link-text">{{ __('Testimonial') }}</span>
                        </a>
                    </li>
                @endcan

                <!-- NAV ITEM Service  -->
                @can('services')
                    <li class="nav-item {{ request()->is('admin/service*') ? 'active' : '' }}" id="settingsMenuTab">
                        <a href="{{ route('admin.service.index') }}" class="nav-link">
                            <i class="fas fa-atom"></i>
                            <span class="link-text">{{ __('Services') }}</span>
                        </a>
                    </li>
                @endcan

                <!-- NAV ITEM case study  -->
                @can('case_study')
                    <li class="nav-item {{ request()->is('admin/casestudy*') ? 'active' : '' }}" id="settingsMenuTab">
                        <a href="{{ route('admin.casestudy.index') }}" class="nav-link">
                            <i class="fas fa-book"></i>
                            <span class="link-text">{{ __('Case Study') }}</span>
                        </a>
                    </li>
                @endcan

                <!-- NAV ITEM Partner  -->
                @can('partner')
                    <li class="nav-item {{ request()->is('admin/partner*') ? 'active' : '' }}">
                        <a href="{{ route('admin.partner.index') }}" class="nav-link">
                            <i class="material-icons">people</i>
                            <span class="link-text">{{ __('Partner') }}</span>
                        </a>
                    </li>
                @endcan
                @can('attorney')
                <!-- NAV ITEM Designation & attorney  -->
                <li class="nav-item has-dropdown {{ request()->is('admin/designation*') ? 'active' : (request()->is('admin/attorney*') ? 'active' : '') }}"
                    id="settingsMenuTab">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="fas fa-user-friends"></i>
                        <span class="link-text">{{ __('Teams') }}</span>
                        <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                    </a>
                    <ul class="dropdown-list">
                        @can('designation')
                            <li><a href="{{ route('admin.designation.index') }}" class="nav-link">
                                    <i class="material-icons">chevron_right</i>
                                    <span class="link-text">{{ __('Designation') }}</span>
                                </a>
                            </li>
                        @endcan

                        @can('attorney')
                            <li><a href="{{ route('admin.attorney.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Attorney') }}</span></a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <li class="nav-item {{ request()->is('admin/conversation*') ? 'active' : '' }}">
                    <a href="{{ route('admin.conversation.index') }}" class="nav-link">
                        <i class="fas fa-comment-dots"></i>
                        <span class="link-text">{{ __('Message') }}</span>
                        @php
                            $unread = 0;
                            $conversations = Auth::user()->conversation;
                            foreach ($conversations as $conversation) {
                                $unread += $conversation->unreadMessages->where('user_id','!=',Auth::id())->count();
                            }
                        @endphp
                        @if ($unread > 0)
                            <i class="badge badge-info">{{ $unread }}</i>
                        @endif
                    </a>
                </li>

                <!-- NAV ITEM blog  -->
                @can('blog')
                    <li class="nav-item has-dropdown {{ request()->is('admin/blog/*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-blog"></i>
                            <span class="link-text">{{ __('Blogs') }}</span>
                            <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                        </a>
                        <ul class="dropdown-list">
                            <li><a href="{{ route('admin.blog.tag.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Tag') }}</span></a></li>
                            <li><a href="{{ route('admin.blog.category.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Category') }}</span></a></li>
                            <li><a href="{{ route('admin.blog.weblog.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Blogs') }}</span></a></li>
                            <li><a href="{{ route('admin.blog.comment-settings') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Comments') }}</span></a></li>

                        </ul>
                    </li>
                @endcan

                <!-- NAV ITEM Appointments  -->
                @can('get_appointment')
                    <li class="nav-item {{ request()->is('admin/appointment*') ? 'active' : '' }}">
                        <a href="{{ route('admin.appointment.index') }}" class="nav-link">
                            <i class="material-icons">perm_contact_calendar</i>
                            <span class="link-text">{{ __('Appointment') }}</span>
                            <span class="badge badge-md">{{ $appointmentMassage->count() }}</span>
                        </a>
                <!-- NAV ITEM Financial Relief  -->
                <li class="nav-item {{ request()->is('admin/financial-relief*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.financial-relief.index') }}">
                    <i class="material-icons">handholding_usd</i>
                    <span>{{ __('Assistance Requests') }}</span>
                    <span class="badge badge-danger float-right">{{ $newReliefRequestCount }}</span>
                </a>
                </li>

                <!-- NAV ITEM user  -->
                @role('admin')
                    <li class="nav-item has-dropdown {{ request()->is('admin/user/*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-user"></i>
                            <span class="link-text">{{ __('User') }}</span>
                            <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                        </a>
                        <ul class="dropdown-list">
                            <li><a href="{{ route('admin.user.index') }}" class="nav-link"> <i
                                class="material-icons">chevron_right</i> <span
                                class="link-text">{{ __('Admin') }}</span></a></li>

                            <li><a href="{{ route('admin.user.client.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Client') }}</span></a></li>
                        </ul>
                    </li>

                    <li class="nav-item has-dropdown {{ request()->is('admin/user/*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-user"></i>
                            <span class="link-text">{{ __('Roles & Permission') }}</span>
                            <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                        </a>
                        <ul class="dropdown-list">
                            <li><a href="{{ route('admin.user.role.index') }}" class="nav-link"> <i
                                        class="material-icons">chevron_right</i> <span
                                        class="link-text">{{ __('Roles') }}</span></a></li>
                        </ul>
                    </li>
                @endrole

                <!-- NAV ITEM Faq  -->
                @can('faq')
                    <li class="nav-item {{ request()->is('admin/faq*') ? 'active' : '' }}">
                        <a href="{{ route('admin.faq.index') }}" class="nav-link">
                            <i class="material-icons">question_answer</i>
                            <span class="link-text">{{ __('Faq') }}</span>
                        </a>
                    </li>
                @endcan

                <!-- NAV ITEM Contacts  -->
                @can('contact')
                    <li class="nav-item {{ request()->is('admin/contact*') ? 'active' : '' }}">
                        <a href="{{ route('admin.contact.index') }}" class="nav-link">
                            <i class="material-icons">contacts</i>
                            <span class="link-text">{{ __('Contacts') }}</span>
                            <span class="badge badge-md">{{ $contactMassage->count() }}</span>
                        </a>
                    </li>
                @endcan
            </ul>

            <div class="f-div"></div>
        </div>
    </div>
</div>
<!-- END WRAPPER LEFT ---------------------------------------------------------------------------->
