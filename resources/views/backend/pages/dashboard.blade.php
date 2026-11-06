@extends('backend.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')

@endsection

@section('content')
    <!-- WRAPPER CONTENT ----------------------------------------------------------------------------->
    <div id="wrapper-content">

        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                        <a class="breadcrumb-item text-white" href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                        <span class="breadcrumb-item active">{{ __($title) }}</span>
                        <span class="breadcrumb-info" id="time"></span>
                    </nav>
                </div>
            </div>

            <!-- PRACTICE FINANCIAL & OPERATIONAL KPIs -->
            <div class="row mb-3">
                <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                    <div class="card card-dark bg-success text-white shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-dollar-sign display-4 opacity-75 mr-2"></i>
                            <div class="ml-auto text-right">
                                <span class="card-title mb-1 text-white-50 text-uppercase small font-weight-bold">{{ __('Practice Revenue') }}</span>
                                <h3 class="card-title font-montserrat mb-0 text-white font-weight-bold">${{ number_format($totalPaidRevenue ?? 0, 2) }}</h3>
                                <small class="text-white-50">{{ __('Total Settled Fees') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                    <div class="card card-dark bg-warning text-dark shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-file-invoice-dollar display-4 opacity-75 mr-2"></i>
                            <div class="ml-auto text-right">
                                <span class="card-title mb-1 text-dark text-uppercase small font-weight-bold">{{ __('Pending Invoices') }}</span>
                                <h3 class="card-title font-montserrat mb-0 font-weight-bold">{{ $pendingInvoicesCount ?? 0 }}</h3>
                                <a href="{{ route('admin.invoices.index') }}" class="small text-dark font-weight-bold text-decoration-underline">{{ __('Review Proofs &rarr;') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                    <div class="card card-dark bg-primary text-white shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-balance-scale display-4 opacity-75 mr-2"></i>
                            <div class="ml-auto text-right">
                                <span class="card-title mb-1 text-white-50 text-uppercase small font-weight-bold">{{ __('Active Cases') }}</span>
                                <h3 class="card-title font-montserrat mb-0 text-white font-weight-bold">{{ $activeCasesCount ?? 0 }}</h3>
                                <a href="{{ route('admin.cases.index') }}" class="small text-white text-decoration-underline">{{ __('Case Directory &rarr;') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                    <div class="card card-dark bg-info text-white shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-users display-4 opacity-75 mr-2"></i>
                            <div class="ml-auto text-right">
                                <span class="card-title mb-1 text-white-50 text-uppercase small font-weight-bold">{{ __('Total Clients') }}</span>
                                <h3 class="card-title font-montserrat mb-0 text-white font-weight-bold">{{ $clientCount ?? 0 }}</h3>
                                <a href="{{ route('admin.user.client.index') }}" class="small text-white text-decoration-underline">{{ __('Manage Clients &rarr;') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-dark bg-danger">

                        <div class="card-body d-flex">
                            <i class="display-2 fas fa-user-tie"></i>
                            <div class="ml-auto align-self-center text-right">
                                <span class="card-title mb-1">{{ __('Attorney') }}</span>
                                <h3 class="card-title font-montserrat mb-0">{{$AttorneyCount}}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card card-dark bg-dark">

                        <div class="card-body d-flex">
                            <i class="display-2 fas fa-atom"></i>
                            <div class="ml-auto align-self-center text-right">
                                <span class="card-title mb-1">{{ __('Services') }}</span>
                                <h3 class="card-title font-montserrat mb-0">{{$ServiceCount}}</h3>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card card-dark bg-info">

                        <div class="card-body d-flex">
                            <i class="display-2 fas fa-blog"></i>
                            <div class="ml-auto align-self-center text-right">
                                <span class="card-title mb-1">{{ __('Blogs') }}</span>
                                <h3 class="card-title font-montserrat mb-0">{{$BlogCount}}</h3>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card card-dark bg-primary">

                        <div class="card-body d-flex">
                            <i class="display-2 material-icons">perm_contact_calendar</i>
                            <div class="ml-auto align-self-center text-right">
                                <span class="card-title mb-1">{{ __('Appointment') }}</span>
                                <h3 class="card-title font-montserrat mb-0">{{$AppointmentCount}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <div class="card card-dark card-justify bg-dark">

                        <div class="card-header">
                            <h6 class="card-title">{{__('activity')}}</h6>

                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between pt-1 pb-3">
                                <div>
                                    <span class="card-title mb-0 text-capitalize ml-2">{{__('Last 12 Month Statement')}}</span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <span class="badge badge-legend badge-danger ml-2"></span>
                                    <span class="card-title mb-0 text-capitalize ml-2">{{__('Appointments')}}</span>
                                    <span class="badge badge-legend badge-success ml-3" style="background-color: #28a745;"></span>
                                    <span class="card-title mb-0 text-capitalize ml-2">{{__('Revenue ($)')}}</span>
                                </div>
                            </div>
                            <canvas class="maxh-310px" data-style="dark" id="chart-line-activity"></canvas>
                        </div>


                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-justify">
                        <div id="calendar_dark"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
@endsection

@section('page-script')
    @include('backend.pages.appointment-dashboard.dashboard-js')
@endsection
