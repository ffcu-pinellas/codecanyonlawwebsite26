@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'laravel'). ' | '.$title)

@section('page-css')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }
    .icon-relief { background: rgba(30, 215, 96, 0.1); color: #1ed760; }
    .icon-msg { background: rgba(54, 162, 235, 0.1); color: #36a2eb; }
    .icon-appt { background: rgba(255, 99, 132, 0.1); color: #ff6384; }
    
    .quick-action-btn {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 12px;
        background: #f8f9fa;
        color: #333;
        margin-bottom: 10px;
        transition: all 0.2s;
        border: 1px solid #eee;
    }
    .quick-action-btn:hover {
        background: #fff;
        border-color: #36a2eb;
        color: #36a2eb;
        transform: translateX(5px);
    }
    .quick-action-btn i {
        margin-right: 15px;
        font-size: 1.2rem;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .section-title {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 20px;
        font-family: 'Montserrat', sans-serif;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1 text-white">Welcome back, {{ Auth::user()->name }}</h2>
            <p class="mb-0 opacity-75">Your legal and financial portal is up to date.</p>
        </div>
        <div class="text-right d-none d-md-block">
            <h5 class="mb-0 text-white">{{ date('l, F jS') }}</h5>
            <small class="opacity-75">Standard Time</small>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon icon-relief"><i class="fas fa-hand-holding-usd"></i></div>
                <h6 class="text-muted text-uppercase small font-weight-bold">Assistance Requests</h6>
                <h3 class="font-weight-bold mb-0">{{ $reliefRequestCount }}</h3>
                <small class="text-success"><i class="fas fa-check-circle"></i> Active Matters</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon icon-msg"><i class="fas fa-envelope-open-text"></i></div>
                <h6 class="text-muted text-uppercase small font-weight-bold">Unread Messages</h6>
                <h3 class="font-weight-bold mb-0">{{ $unreadMessageCount }}</h3>
                <small class="text-primary"><i class="fas fa-clock"></i> New Updates</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon icon-appt"><i class="fas fa-calendar-check"></i></div>
                <h6 class="text-muted text-uppercase small font-weight-bold">Total Conversations</h6>
                <h3 class="font-weight-bold mb-0">{{ $conversationCount }}</h3>
                <small class="text-dark"><i class="fas fa-user-tie"></i> Legal Team</small>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content: Relief Tracker -->
        <div class="col-lg-8 mb-4">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="section-title">Case & Relief Status</h5>
                    @forelse(Auth::user()->reliefRequests()->latest()->take(3)->get() as $relief)
                    <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-light rounded border-left border-primary" style="border-left-width: 5px !important;">
                        <div>
                            <h6 class="mb-1 font-weight-bold">{{ $relief->reason }}</h6>
                            <small class="text-muted"><i class="far fa-calendar-alt"></i> Filed on {{ $relief->created_at->format('M d, Y') }}</small>
                        </div>
                        <div>
                            @if($relief->viewed)
                                <span class="badge badge-success status-badge">Under Review</span>
                            @else
                                <span class="badge badge-warning status-badge">Pending</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice-dollar display-4 text-light mb-3"></i>
                        <p class="text-muted">No active relief requests found.</p>
                        <a href="{{ route('client.financial-relief') }}" class="btn btn-primary btn-sm px-4">Apply Now</a>
                    </div>
                    @endforelse
                    
                    @if($reliefRequestCount > 3)
                    <div class="text-center mt-3">
                        <a href="javascript:void(0)" class="text-primary small font-weight-bold">View All Requests</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- CPA / Legal Assistance Tracker -->
            <div class="card stat-card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="section-title mb-4"><i class="fas fa-tasks mr-2 text-primary"></i> CPA / Legal Assistance Tracker</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(Auth::user()->reliefRequests()->latest()->take(5)->get() as $doc)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-file-pdf text-danger fa-lg mr-3"></i>
                                            <span class="font-weight-medium">{{ $doc->file_name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $doc->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($doc->viewed)
                                            <span class="badge badge-pill badge-soft-success" style="background: #e8f5e9; color: #2e7d32; font-size: 0.7rem;">Reviewed</span>
                                        @else
                                            <span class="badge badge-pill badge-soft-warning" style="background: #fff3e0; color: #ef6c00; font-size: 0.7rem;">Processing</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ asset($doc->file) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">No documents synced yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Quick Actions & Appointments -->
        <div class="col-lg-4">
            <div class="card stat-card shadow-sm border-0 mb-4 text-center p-3" style="background: #f0f7ff;">
                <h5 class="section-title text-left">Quick Actions</h5>
                <a href="{{ route('client.financial-relief') }}" class="quick-action-btn">
                    <i class="fas fa-file-signature text-success"></i>
                    <span>Request CPA / Legal Assistance</span>
                </a>
                <a href="{{ route('client.conversation.index') }}" class="quick-action-btn">
                    <i class="fas fa-comment-dots text-primary"></i>
                    <span>Message Attorney</span>
                </a>
                <a href="{{ route('view-contact-page') }}" class="quick-action-btn">
                    <i class="fas fa-calendar-plus text-danger"></i>
                    <span>Book Appointment</span>
                </a>
            </div>

            <div class="card stat-card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="section-title">Upcoming Meetings</h5>
                    @forelse($recentAppointments as $appt)
                    <div class="p-3 mb-2 bg-white rounded border border-light shadow-sm">
                        <div class="d-flex justify-content-between">
                            <span class="badge badge-info mb-2">Legal Team</span>
                            <small class="text-muted">{{ $appt->created_at->format('H:i') }}</small>
                        </div>
                        <h6 class="mb-0 font-weight-bold">{{ $appt->name }}</h6>
                        <small class="text-muted">Ref: Consultation #{{ $appt->id }}</small>
                    </div>
                    @empty
                    <p class="text-muted small text-center py-4">No scheduled meetings.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')

@endsection
