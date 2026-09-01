<!-- WRAPPER LEFT -------------------------------------------------------------------------------->
<div id="wrapper-left">
    <!-- SIDEBAR -->
    <div class="sidebar sidebar-dark sidebar-danger bg-dark">
        <!-- SIDEBAR HEADER -->
        <div class="sidebar-header border-fade">
            <!-- SIDEBAR BRAND -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <img class="sidebar-brand-img" src="{{ $logoFavicon ? $logoFavicon->logo : ' ' }}" />
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
                    <h6 class="text-white">{{ auth()->user()->name }}</h6>
                    <div class="sidebar-actions">
                        <a href="{{ route('admin.profile') }}" title="{{ __('Profile') }}"><i class="material-icons">person_outline</i></a>
                        <a href="{{ route('admin.contact.index') }}" title="{{ __('Messages') }}"><i class="material-icons">mail_outline</i></a>
                        <a href="{{ route('admin.appointment.index') }}" title="{{ __('Appointments') }}"><i class="material-icons">notifications_none</i></a>
                        <a href="{{ route('admin.settings.general') }}" title="{{ __('Settings') }}"><i class="material-icons">settings</i></a>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR NAV -->
            <ul class="sidebar-nav">
                <!-- 1. DASHBOARD -->
                <li class="nav-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt text-warning"></i>
                        <span class="link-text">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <!-- 2. CLIENT MANAGEMENT & PORTAL ACCOUNTS -->
                @role('admin')
                <li class="nav-item has-dropdown {{ (request()->is('admin/user/client*') || request()->is('admin/user*')) && !request()->is('admin/user/role*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="fas fa-users text-warning"></i>
                        <span class="link-text">{{ __('Client Accounts') }}</span>
                        <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                    </a>
                    <ul class="dropdown-list">
                        <li>
                            <a href="{{ route('admin.user.client.index') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Clients Directory') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.index') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Administrators') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.role.index') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Roles & Permissions') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                <!-- 3. LEGAL & CPA CASE OPERATIONS -->
                <li class="nav-item has-dropdown {{ (request()->is('admin/cases*') || request()->is('admin/invoices*') || request()->is('admin/financial-relief*') || request()->is('admin/document*') || request()->is('admin/activity-logs*')) ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="fas fa-briefcase text-warning"></i>
                        <span class="link-text">{{ __('Legal & CPA Operations') }}</span>
                        <span class="badge badge-md">
                            @if(isset($pendingCasesCount) && $pendingCasesCount > 0)
                                <span class="badge badge-danger px-1" style="font-size: 0.7rem;">{{ $pendingCasesCount }}</span>
                            @else
                                <span class="material-icons h6">chevron_right</span>
                            @endif
                        </span>
                    </a>
                    <ul class="dropdown-list">
                        <li>
                            <a href="{{ route('admin.cases.index') }}" class="nav-link d-flex justify-content-between align-items-center">
                                <div><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Cases & Vault') }}</span></div>
                                @if(isset($pendingCasesCount) && $pendingCasesCount > 0)
                                    <span class="badge badge-danger mr-3 px-1">{{ $pendingCasesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.financial-relief.index') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Client Case Intakes') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.invoices.index') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Billing & Invoices') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.document-generator') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Document Builder') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.document-templates.index') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Document Templates') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.document-templates.history') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('Sent & Tracking History') }}</span>
                            </a>
                        </li>
                        @role('admin')
                        <li>
                            <a href="{{ route('admin.activity-logs') }}" class="nav-link">
                                <i class="material-icons">chevron_right</i>
                                <span class="link-text">{{ __('System Activity Logs') }}</span>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>

                <!-- 4. LIVE CLIENT MESSAGING & CHAT -->
                <li class="nav-item {{ request()->is('admin/conversation*') ? 'active' : '' }}">
                    <a href="{{ route('admin.conversation.index') }}" class="nav-link d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-comment-dots text-warning"></i>
                            <span class="link-text">{{ __('Live Counsel Chat') }}</span>
                        </div>
                        @php
                            $unread = 0;
                            try {
                                $conversations = Auth::user()->conversation;
                                if ($conversations) {
                                    foreach ($conversations as $conv) {
                                        $unread += $conv->unreadMessages->where('user_id', '!=', Auth::id())->count();
                                    }
                                }
                            } catch (\Throwable $e) {}
                        @endphp
                        @if ($unread > 0)
                            <span class="badge badge-warning text-dark font-weight-bold mr-2">{{ $unread }}</span>
                        @endif
                    </a>
                </li>

                <!-- 5. LEADS & INQUIRIES -->
                <li class="nav-item has-dropdown {{ (request()->is('admin/appointment*') || request()->is('admin/contact*')) ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="fas fa-address-book text-warning"></i>
                        <span class="link-text">{{ __('Leads & Inquiries') }}</span>
                        <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                    </a>
                    <ul class="dropdown-list">
                        @can('get_appointment')
                        <li>
                            <a href="{{ route('admin.appointment.index') }}" class="nav-link d-flex justify-content-between align-items-center">
                                <div><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Appointments') }}</span></div>
                                @if(isset($appointmentMassage) && $appointmentMassage->count() > 0)
                                    <span class="badge badge-info mr-3 px-1">{{ $appointmentMassage->count() }}</span>
                                @endif
                            </a>
                        </li>
                        @endcan
                        @can('contact')
                        <li>
                            <a href="{{ route('admin.contact.index') }}" class="nav-link d-flex justify-content-between align-items-center">
                                <div><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Contact Forms') }}</span></div>
                                @if(isset($contactMassage) && $contactMassage->count() > 0)
                                    <span class="badge badge-info mr-3 px-1">{{ $contactMassage->count() }}</span>
                                @endif
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>

                <!-- 6. STAFF & ATTORNEYS -->
                @role('admin')
                <li class="nav-item has-dropdown {{ (request()->is('admin/staff*') || request()->is('admin/attorney*') || request()->is('admin/designation*')) ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="fas fa-user-tie text-warning"></i>
                        <span class="link-text">{{ __('Staff & Attorneys') }}</span>
                        <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                    </a>
                    <ul class="dropdown-list">
                        <li><a href="{{ route('admin.staff.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Staff Directory') }}</span></a></li>
                        <li><a href="{{ route('admin.staff.tasks.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Staff Tasks') }}</span></a></li>
                        <li><a href="{{ route('admin.staff.payouts.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Staff Payouts') }}</span></a></li>
                        @can('attorney')
                            <li><a href="{{ route('admin.attorney.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Attorneys Directory') }}</span></a></li>
                        @endcan
                        @can('designation')
                            <li><a href="{{ route('admin.designation.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Designations') }}</span></a></li>
                        @endcan
                    </ul>
                </li>
                @endrole

                <!-- 7. WEBSITE CONTENT & BLOGS -->
                <li class="nav-item has-dropdown {{ (request()->is('admin/service*') || request()->is('admin/blog*') || request()->is('admin/testimonial*') || request()->is('admin/slider*') || request()->is('admin/faq*') || request()->is('admin/partner*') || request()->is('admin/casestudy*') || request()->is('admin/dynamic-page*')) ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="fas fa-desktop text-warning"></i>
                        <span class="link-text">{{ __('Website Content') }}</span>
                        <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                    </a>
                    <ul class="dropdown-list">
                        @can('services')
                            <li><a href="{{ route('admin.service.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Practice Services') }}</span></a></li>
                        @endcan
                        @can('case_study')
                            <li><a href="{{ route('admin.casestudy.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Case Studies') }}</span></a></li>
                        @endcan
                        @can('blog')
                            <li><a href="{{ route('admin.blog.weblog.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Articles & Blogs') }}</span></a></li>
                        @endcan
                        @can('testimonial')
                            <li><a href="{{ route('admin.testimonial.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Testimonials') }}</span></a></li>
                        @endcan
                        @can('slider_settings')
                            <li><a href="{{ route('admin.slider.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Hero Sliders') }}</span></a></li>
                        @endcan
                        @can('faq')
                            <li><a href="{{ route('admin.faq.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('FAQs') }}</span></a></li>
                        @endcan
                        @can('partner')
                            <li><a href="{{ route('admin.partner.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Partners & Accreditations') }}</span></a></li>
                        @endcan
                        @can('dynamic_page')
                            <li><a href="{{ route('admin.dynamic-page.page-index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Custom Dynamic Pages') }}</span></a></li>
                        @endcan
                    </ul>
                </li>

                <!-- 8. PAGE SETTINGS (TOTAL CONTROL OF SECTIONS & TEXT) -->
                @can('page_settings')
                <li class="nav-item has-dropdown {{ request()->is('admin/page-settings*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="material-icons text-warning">pages</i>
                        <span class="link-text">{{ __('Page Layouts') }}</span>
                        <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                    </a>
                    <ul class="dropdown-list">
                        <li><a href="{{ route('admin.page-settings.home-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Home Page') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.client-dashboard-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Client Portal Page') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.about-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('About Us') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.services-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Services Page') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.cases-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Cases Page') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.teams-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Teams Page') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.contact-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Contact Page') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.faq-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('FAQ Page') }}</span></a></li>
                        <li><a href="{{ route('admin.page-settings.blogs-page.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Blogs Page') }}</span></a></li>
                    </ul>
                </li>
                @endcan

                <!-- 9. FIRM SYSTEM SETTINGS -->
                @can('settings')
                <li class="nav-item has-dropdown {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="fas fa-cogs text-warning"></i>
                        <span class="link-text">{{ __('Firm Settings') }}</span>
                        <span class="badge badge-md"><span class="material-icons h6">chevron_right</span></span>
                    </a>
                    <ul class="dropdown-list">
                        <li><a href="{{ route('admin.settings.general') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('General Practice Info') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.logo-favicon') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Logo & Branding') }}</span></a></li>
                        <li><a href="{{ route('admin.menu.category.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Public Navigation Menus') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.topHeader.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Top Header Bar') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.footer.index') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Footer Sections') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.social-media') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Social Links') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.smtp') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('SMTP Email Dispatcher') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.seo') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Global SEO') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.chat') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Live Counsel Chat Settings') }}</span></a></li>
                        <li><a href="{{ route('admin.settings.insert-header-footer') }}" class="nav-link"><i class="material-icons">chevron_right</i> <span class="link-text">{{ __('Custom Scripts & CSS') }}</span></a></li>
                    </ul>
                </li>
                @endcan
            </ul>

            <div class="f-div"></div>
        </div>
    </div>
</div>
<!-- END WRAPPER LEFT ---------------------------------------------------------------------------->
