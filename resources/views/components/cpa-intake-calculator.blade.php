<section class="cpa-intake-calculator-section py-5" style="background: linear-gradient(180deg, #0b1120 0%, #0f172a 100%); color: #f8fafc; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge badge-warning text-dark font-weight-bold px-3 py-2 text-uppercase" style="letter-spacing: 1px; font-size: 11px;">Instant Case Assessment</span>
            <h2 class="font-weight-bold mt-2 text-white">Confidential Tax & Legal Consultation Estimator</h2>
            <p class="text-muted mx-auto" style="max-width: 650px;">Determine your resolution options, settlement eligibility, and connect directly with our founding CPA and Attorney team in less than 60 seconds.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card bg-dark border-secondary shadow-lg" style="border-radius: 16px; overflow: hidden; border: 1px solid #334155;">
                    <!-- Stepper Header -->
                    <div class="p-3 bg-black border-bottom border-secondary d-flex justify-content-around text-center" id="calcStepperHeader">
                        <div class="step-indicator active font-weight-bold small text-warning" data-step="1">
                            <span class="badge badge-pill badge-warning mr-1">1</span> Issue Type
                        </div>
                        <div class="step-indicator text-muted font-weight-bold small" data-step="2">
                            <span class="badge badge-pill badge-secondary mr-1">2</span> Liability Size
                        </div>
                        <div class="step-indicator text-muted font-weight-bold small" data-step="3">
                            <span class="badge badge-pill badge-secondary mr-1">3</span> Notice Urgency
                        </div>
                        <div class="step-indicator text-muted font-weight-bold small" data-step="4">
                            <span class="badge badge-pill badge-secondary mr-1">4</span> Direct Intake
                        </div>
                    </div>

                    <form action="{{ route('store-appointment') }}" method="POST" id="cpaIntakeForm">
                        @csrf
                        <div class="card-body p-4 p-md-5">

                            <!-- STEP 1 -->
                            <div class="calc-step-panel active" id="stepPanel1">
                                <h5 class="text-light mb-3 font-weight-bold"><i class="fas fa-balance-scale mr-2 text-warning"></i> What primary legal or tax matter requires representation?</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="service_type" value="IRS Tax Audit Defense & Investigation" checked>
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-warning mb-1 font-weight-bold">IRS & State Tax Audit Defense</h6>
                                                <p class="small text-muted mb-0">Audits, tax examinations, CP2000 notices, and revenue agent representation.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="service_type" value="Unfiled Returns & Back Taxes Debt Relief">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-warning mb-1 font-weight-bold">Back Taxes & Unfiled Returns</h6>
                                                <p class="small text-muted mb-0">Multi-year unfiled returns, penalty abatement, and Offer in Compromise (OIC).</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="service_type" value="Business Payroll & 941 Trust Fund Taxes">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-warning mb-1 font-weight-bold">Business 941 Payroll Tax Recovery</h6>
                                                <p class="small text-muted mb-0">Trust Fund Recovery Penalties (TFRP), corporate audits, and state sales tax.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="service_type" value="Corporate Asset Protection & Escrow Retainer">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-warning mb-1 font-weight-bold">Asset Protection & Legal Trust Escrow</h6>
                                                <p class="small text-muted mb-0">IOLTA legal trust custody, partner dispute accounting, and asset defense.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-warning font-weight-bold px-4 next-step-btn" data-next="2">Next Step <i class="fas fa-arrow-right ml-1"></i></button>
                                </div>
                            </div>

                            <!-- STEP 2 -->
                            <div class="calc-step-panel d-none" id="stepPanel2">
                                <h5 class="text-light mb-3 font-weight-bold"><i class="fas fa-dollar-sign mr-2 text-warning"></i> What is the approximate disputed balance or claim amount?</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="amount_bracket" value="$10,000 – $50,000" checked>
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-white mb-1 font-weight-bold">$10,000 – $50,000</h6>
                                                <p class="small text-muted mb-0">Individual filings, state liens, basic examination defense.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="amount_bracket" value="$50,000 – $250,000">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-white mb-1 font-weight-bold">$50,000 – $250,000</h6>
                                                <p class="small text-muted mb-0">Significant back liabilities, revenue officer assignment, OIC qualification.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="amount_bracket" value="$250,000 – $1,000,000">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-white mb-1 font-weight-bold">$250,000 – $1,000,000</h6>
                                                <p class="small text-muted mb-0">Corporate payroll liabilities, multi-year business audits, escrow holdbacks.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="amount_bracket" value="$1,000,000+ (High Net Worth / Enterprise)">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-white mb-1 font-weight-bold">$1,000,000+ (High Net Worth)</h6>
                                                <p class="small text-muted mb-0">Complex civil litigation, federal tax court petition, offshore disclosures.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-outline-secondary prev-step-btn" data-prev="1"><i class="fas fa-arrow-left mr-1"></i> Back</button>
                                    <button type="button" class="btn btn-warning font-weight-bold px-4 next-step-btn" data-next="3">Next Step <i class="fas fa-arrow-right ml-1"></i></button>
                                </div>
                            </div>

                            <!-- STEP 3 -->
                            <div class="calc-step-panel d-none" id="stepPanel3">
                                <h5 class="text-light mb-3 font-weight-bold"><i class="fas fa-exclamation-triangle mr-2 text-warning"></i> Do you have an active enforcement deadline or urgent notice?</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="urgency" value="Critical: Active Bank Levy or Wage Garnishment" checked>
                                            <div class="card-content p-3 border border-danger rounded bg-dark">
                                                <h6 class="text-danger mb-1 font-weight-bold">🚨 Urgent: Bank Levy or Wage Garnishment</h6>
                                                <p class="small text-muted mb-0">Immediate stay of collection required to freeze IRS action within 48h.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="urgency" value="Notice Received: Deadline in 30 Days">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-warning mb-1 font-weight-bold">⚠️ Notice of Deficiency / 30-Day Letter</h6>
                                                <p class="small text-muted mb-0">Statutory deadline approaching to petition tax appeals or tax court.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="urgency" value="Proactive: Voluntary Compliance & Audit Preparation">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-info mb-1 font-weight-bold">📋 Proactive Filing & Audit Prep</h6>
                                                <p class="small text-muted mb-0">No active levies yet; aiming for unfiled compliance or audit structuring.</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="calc-choice-card">
                                            <input type="radio" name="urgency" value="Other / Confidential Review">
                                            <div class="card-content p-3 border border-secondary rounded">
                                                <h6 class="text-white mb-1 font-weight-bold">🔒 Confidential Retainer Review</h6>
                                                <p class="small text-muted mb-0">Privileged CPA-Attorney initial case briefing.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-outline-secondary prev-step-btn" data-prev="2"><i class="fas fa-arrow-left mr-1"></i> Back</button>
                                    <button type="button" class="btn btn-warning font-weight-bold px-4 next-step-btn" data-next="4">Final Step: Get Counsel Assessment <i class="fas fa-arrow-right ml-1"></i></button>
                                </div>
                            </div>

                            <!-- STEP 4 -->
                            <div class="calc-step-panel d-none" id="stepPanel4">
                                <h5 class="text-light mb-2 font-weight-bold"><i class="fas fa-user-shield mr-2 text-warning"></i> Confidential Direct Counsel Contact</h5>
                                <p class="text-muted small mb-4">Your intake summary is ready for attorney review under privileged legal & CPA confidence.</p>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-light">Full Legal / Corporate Name *</label>
                                        <input type="text" name="name" class="form-control bg-black text-white border-secondary" required placeholder="e.g. Johnathan Davis / Apex Holdings LLC">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-light">Confidential Phone Number *</label>
                                        <input type="tel" name="phone" class="form-control bg-black text-white border-secondary" required placeholder="(555) 000-0000">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-light">Email Address *</label>
                                        <input type="email" name="email" class="form-control bg-black text-white border-secondary" required placeholder="name@domain.com">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-light">Preferred Appointment Date & Time</label>
                                        <input type="datetime-local" name="date" class="form-control bg-black text-white border-secondary" value="{{ now()->addDay()->format('Y-m-d\T10:00') }}">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="small text-light">Additional Case Background & Details (Optional)</label>
                                    <textarea name="description" class="form-control bg-black text-white border-secondary" rows="3" placeholder="Provide any notice codes, tax years involved, or immediate questions for our legal team..."></textarea>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-outline-secondary prev-step-btn" data-prev="3"><i class="fas fa-arrow-left mr-1"></i> Back</button>
                                    <button type="submit" class="btn btn-warning btn-lg font-weight-bold px-4" style="box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Confidential Case File
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .calc-choice-card {
        display: block;
        cursor: pointer;
        margin-bottom: 0;
    }
    .calc-choice-card input[type="radio"] {
        display: none;
    }
    .calc-choice-card .card-content {
        transition: all 0.2s ease;
        background: #111827;
        height: 100%;
    }
    .calc-choice-card input[type="radio"]:checked + .card-content {
        border-color: #f59e0b !important;
        background: rgba(245, 158, 11, 0.08);
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.2);
    }
    .step-indicator {
        transition: color 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var steps = [1, 2, 3, 4];

        function showStep(targetStep) {
            steps.forEach(function (s) {
                var panel = document.getElementById('stepPanel' + s);
                var ind = document.querySelector('.step-indicator[data-step="' + s + '"]');
                if (panel) {
                    if (s === targetStep) {
                        panel.classList.remove('d-none');
                        panel.classList.add('active');
                    } else {
                        panel.classList.add('d-none');
                        panel.classList.remove('active');
                    }
                }
                if (ind) {
                    var badge = ind.querySelector('.badge');
                    if (s === targetStep) {
                        ind.className = 'step-indicator active font-weight-bold small text-warning';
                        if (badge) badge.className = 'badge badge-pill badge-warning mr-1';
                    } else if (s < targetStep) {
                        ind.className = 'step-indicator font-weight-bold small text-success';
                        if (badge) badge.className = 'badge badge-pill badge-success mr-1';
                    } else {
                        ind.className = 'step-indicator text-muted font-weight-bold small';
                        if (badge) badge.className = 'badge badge-pill badge-secondary mr-1';
                    }
                }
            });
        }

        document.querySelectorAll('.next-step-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var next = parseInt(this.getAttribute('data-next'));
                showStep(next);
            });
        });

        document.querySelectorAll('.prev-step-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var prev = parseInt(this.getAttribute('data-prev'));
                showStep(prev);
            });
        });
    });
</script>

