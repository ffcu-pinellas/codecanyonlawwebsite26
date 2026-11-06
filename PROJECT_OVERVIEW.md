# ⚖️ Your CPA Expert — Comprehensive System Architecture & Technical Overview

**Project Name:** Your CPA Expert  
**Domain:** `https://yourcpaexpert.com`  
**Repository Remote:** `https://github.com/ffcu-pinellas/codecanyonlawwebsite26.git`  
**Active Production Branch:** `main`  
**Target Environment:** Hostinger Cloud / Shared Web Hosting (PHP 8.2+, MySQL, Apache/LiteSpeed)  
**Framework & Libraries:** Laravel 11.x, Livewire 3.4, Jetstream 5.0, Spatie Permission v6, Barryvdh DOMPDF  

---

## 1. Executive Summary & Purpose

**Your CPA Expert** is an enterprise-grade Legal & CPA practice management platform, client portal, and dynamic CMS. Originally evolved from a specialized legal practice codebase, it has been substantially enhanced into a high-security, multi-tier CPA and attorney operations suite.

The platform provides complete separation of concerns across three distinct user experiences:
1. **Public Marketing & Client Intake Website**: Dynamic services, attorney profiles, case studies, blogs, contact forms, appointment booking, and hardship/relief intake.
2. **Client Portal (256-Bit Encrypted Client Hub)**: High-security client area with 2FA/OTP gates, PIN verification, case lifecycle tracking, financial audit schedules, IOLTA escrow settlement hub, document vault, invoice payment proof processing, and real-time attorney/CPA messaging.
3. **Staff & Practice Management Portal**: Time clocking with IP geolocation, assigned task tracking, wage/reimbursement financial ledger, payout requests, direct deposit PDF generation, and firm document review.
4. **Administrative Control Center**: Full practice oversight, Spatie RBAC, client impersonation ("Login As"), one-click credential generation, custom welcome emails, KYC field builder, invoice and settlement management, and instant Telegram alert broadcasts.

---

## 2. GitHub Deployment & Hostinger Synchronization (`push.ps1`)

### How Deployments Work Without Manual Hostinger Access
Deployment from your local development machine to production is fully automated via PowerShell and Git webhooks. You **never need to log into Hostinger cPanel or use FTP** to deploy code.

```mermaid
flowchart LR
    A[Local Code / Fixes] -->|.\push.ps1 "Message"| B[Git Commit & Branch Main]
    B -->|git push origin main| C[GitHub Repository\nffcu-pinellas/codecanyonlawwebsite26]
    C -->|Webhook Auto-Deploy Event| D[Hostinger Production Server\nyourcpaexpert.com]
    D --> E[Live Production Updated]
```

### Deployment Runner (`push.ps1`) Mechanics
The deployment script is located in the project root at [`push.ps1`](file:///c:/Users/USER/Downloads/YourCpaExpert%20Last%20Before%20Hostinger%20ID%20verif/push.ps1):
1. **Verification**: Confirms Git repository initialization and verifies the remote origin points to `https://github.com/ffcu-pinellas/codecanyonlawwebsite26.git`.
2. **Staging**: Automatically runs `git add .` to capture all file updates, migrations, assets, and views.
3. **Commit**: Creates an atomic Git commit with your custom deployment message (or auto-generates a timestamped message if omitted).
4. **Push**: Ensures the active branch is `main` (`git branch -M main`) and pushes to `origin main`.
5. **Hostinger Webhook Hook**: Hostinger's Git Auto-Deployment Webhook detects the new commit pushed to GitHub and pulls the fresh code directly into the production directory within seconds.

---

## 3. Remote Database Modifications Without Direct DB Access

### The Headless `/setup` Migration & Maintenance Engine
On Hostinger shared hosting, database ports are typically firewalled from external access, and opening phpMyAdmin every time you change a table is slow and error-prone. 

This project solves this through a dedicated, self-executing maintenance route located in [`routes/web.php`](file:///c:/Users/USER/Downloads/YourCpaExpert%20Last%20Before%20Hostinger%20ID%20verif/routes/web.php#L40-L136):

```php
Route::get('/setup', function () {
    // 1. Run all pending migrations
    Artisan::call('migrate', ['--force' => true]);

    // 2. Link public storage symlinks
    Artisan::call('storage:link');

    // 3. Clear application caches
    Artisan::call('cache:clear');
    Artisan::call('view:clear');

    // 4. Clean storage/settings.json branding
    // 5. Apply data migrations and table sanitation
    // 6. Echo 'done' and redirect to home
});
```

### Step-by-Step Procedure for Database Modifications:
1. **Create Migration Locally**: Add a new Laravel migration file in `database/migrations/` using standard Laravel syntax (e.g., adding columns, altering tables, creating new relations).
2. **Push to Production**: Run:
   ```powershell
   .\push.ps1 "Add new columns to case_settlements"
   ```
3. **Trigger Remote Migration**: In your web browser, navigate to:
   ```
   https://yourcpaexpert.com/setup
   ```
4. **Automatic Execution**: 
   - The server triggers `Artisan::call('migrate', ['--force' => true])`.
   - All unrun migrations execute immediately against the production Hostinger MySQL database (`u664663598_cpaexpert`).
   - Symlinks and compiled view caches are refreshed.
   - The route echoes `done` and redirects to the homepage.

> [!TIP]
> You never need to touch Hostinger phpMyAdmin or open SSH to run database changes. Everything executes safely through the `/setup` web trigger.

---

## 4. Multi-Portal Authentication Architecture

The system implements strict separation of concerns for user authentication across three independent access levels:

```
                          ┌────────────────────────┐
                          │     Incoming User      │
                          └───────────┬────────────┘
                                      │
               ┌──────────────────────┼──────────────────────┐
               ▼                      ▼                      ▼
        [/admin/login]          [/staff/login]            [/login]
      Admin/Attorney Portal      Staff Portal          Client Portal
               │                      │                      │
     - Multi-role check      - role:staff check     - Role redirector
     - Dual guard: 'admin'   - StaffDetail active   - Security Wizard
       persists during       - IP Geo-resolution    - 2FA / OTP Gate
       client impersonation  - StaffLoginLog        - Session Lock & PIN
```

### A. Admin & Attorney Portal (`/admin/login`)
- **Controller:** [`AdminAuthController`](file:///c:/Users/USER/Downloads/YourCpaExpert%20Last%20Before%20Hostinger%20ID%20verif/app/Http/Controllers/AdminControllers/AdminAuthController.php)
- **Authorized Roles:** `admin`, `attorney`, `staff`, or User ID `1`.
- **Dual-Guard Persistence:** Signs the user into both `Auth::guard('web')` and `Auth::guard('admin')`. This ensures that when an administrator uses the **Impersonate / Login-As** feature to view a client's account, their administrative session remains active in the background.
- **Audit Logging:** Every successful staff/admin login writes directly to `system_audit_logs` with action key `STAFF_LOGIN`.
- **Theme:** Dark executive theme with gold accent branding (`#f59e0b`).

### B. Dedicated Staff Portal (`/staff/login`)
- **Controller:** [`StaffViewController`](file:///c:/Users/USER/Downloads/YourCpaExpert%20Last%20Before%20Hostinger%20ID%20verif/app/Http/Controllers/StaffControllers/StaffViewController.php#L49-L100)
- **Role Requirement:** Strictly `role:staff`. Rejects clients and redirect non-staff accounts.
- **Account Status Guard:** Checks `staff_details.is_active`. If an administrator deactivates a staff member, login is immediately denied.
- **IP Geolocation Resolution:** Automatically resolves the staff member's real IP address via `ip-api.com` (city, region, country) and records it into `staff_login_logs` alongside user-agent and login timestamp.
- **Theme:** Navy blue gradient portal (`#1e3c72` to `#2a5298`).

### C. Client Portal (`/login`) & Security Desk
- **Controller:** [`GuestViewController::loginRedirect`](file:///c:/Users/USER/Downloads/YourCpaExpert%20Last%20Before%20Hostinger%20ID%20verif/app/Http/Controllers/GuestViewControllers/GuestViewController.php#L35-L53)
- **Security Middleware:** [`EnsureSecuritySetupCompleted`](file:///c:/Users/USER/Downloads/YourCpaExpert%20Last%20Before%20Hostinger%20ID%20verif/app/Http/Middleware/EnsureSecuritySetupCompleted.php)
- **First-Time Login Wizard (`/client/security-wizard`)**: Triggered when `users.is_first_login == true`. Guides clients through updating their temporary password, configuring their security PIN, and initializing 2FA.
- **Two-Factor Authentication & OTP Gate (`/client/security/verify-otp`)**:
  - Validates one-time numeric verification codes sent to the client's email or checks the security PIN challenge (`pin_hash`).
  - Session bypass: When an admin is impersonating a client (`admin_login_as_bypass`), the 2FA gate is automatically bypassed so administrators are never blocked.
- **Security Desk Features**:
  - Remote logout of all other active sessions (`logoutAllOtherDevices`).
  - Security PIN management (`setPin`).
  - Session lock/unlock (`lockSession`, `unlockSession`).
  - Device login history monitoring.

---

## 5. In-Depth Feature Breakdown

### 1. Client Impersonation & Credential Management (Admin Panel)
- **1-Click Client Impersonation (`/admin/user/login/{id}`)**:
  - Administrators can seamlessly access any client's portal with one click.
  - Keeps admin credentials in `Auth::guard('admin')` while logging `Auth::guard('web')` into the client account.
  - Displays a persistent top-bar "Stop Impersonation" banner linking to `/stop-impersonation`.
  - Logs the impersonation event to `system_audit_logs`.
- **1-Click Credential Generation (`/admin/user/client/{id}/generate-credentials`)**:
  - Instantly generates an 8-character random password and default PIN `1234`.
  - Sets `is_temp_password = true` and `is_first_login = true`.
  - Sends a confidential HTML security alert email to the client informing them of an access reset.
  - Broadcasts a real-time Telegram notification to the practice management channel.
  - Flashes credentials to the admin UI for instant clipboard copy.
- **Customized Welcome / Onboarding Email Dispatcher (`/admin/user/client/send-welcome-email`)**:
  - Allows staff to send tailored onboarding letters with custom attorney briefing notes, portal links, and temporary login credentials.

### 2. Staff Management & Virtual Operations Desk
- **Staff Profiles & Contracts (`staff_details`)**: Stores staff ID (`STF-xxxx`), position, hire date, hourly wage, next pay date, bonuses, draws, and reimbursement balances.
- **Time Clock (`staff_time_logs`)**: Real-time Clock-In / Clock-Out tracker calculating elapsed time in seconds, hourly wage rates, earned amounts, and geolocation.
- **Task Dispatch Center (`staff_tasks`)**: Admins assign tasks with priority badges, deadlines, and descriptions; staff can view and mark tasks complete.
- **Financial Ledger & Reimbursements (`staff_ledger_entries`, `staff_payout_requests`)**: 
  - Complete history of wages earned, bonuses, and expense reimbursements.
  - Staff can submit reimbursement requests with receipt proofs.
  - Admins can approve entries and mark payouts as completed.
- **Direct Deposit Agreement**: Dynamic PDF generator for direct deposit authorization forms.
- **Internal Messaging (`staff_messages`)**: Real-time messaging between staff and managing attorneys with AJAX polling.

### 3. Legal & CPA Case Management Engine
- **Case Lifecycle Tracker (`client_cases`)**:
  - Tracks cases via unique case numbers (`CS-xxxxxx`).
  - 5-stage visual lifecycle progress bar:
    1. Case Intake & Retainer
    2. Document Discovery & Assessment
    3. Regulatory Filing & Audit
    4. Settlement Negotiation & Trust
    5. Final Disbursement & Case Closed
- **Financial & Audit Schedules (`case_financial_schedules`)**:
  - Structured schedules for audited assets, tax liabilities, settlement deductions, and escrow holdbacks.
  - Tracks status: `Pending Review`, `Audited`, `Reconciled`, `Disputed`.
- **Retainer & Trust Settlement Hub (`case_settlements`)**:
  - IOLTA Legal Trust escrow accounting.
  - Calculates gross settlement, legal fee percentage, expense deductions, and net client payout.
  - 4-stage clearance tracker: `Verified` &rarr; `Escrow Cleared` &rarr; `Settlement Approved` &rarr; `Disbursed`.
  - Secure client digital confirmation with signature hash recording.
- **Court & Regulatory Jurisdictions (`case_jurisdictions`)**:
  - Details court venues, IRS Tax Tribunals, action types, docket numbers, and hearing dates.
- **Case Document Vault (`case_documents`)**:
  - Categorized legal/CPA document repository.
  - Public token-based tracking URL (`/track/document/{token}`) with 1×1 GIF open tracking.

### 4. Dynamic KYC Verification & Compliance Hub
- **Dynamic Field Builder (`admin.kyc.config`)**: Administrators can add, edit, or delete custom KYC fields without code modifications.
- **Document Submissions (`client_kyc_documents`)**: Clients upload Government IDs, W-2/1099 tax returns, articles of incorporation, and financial statements.
- **Review Workflow**: Admins review documents and mark them as `Approved`, `Needs Resubmission`, or `Pending Review`, with direct feedback notes.

### 5. Invoicing, Receipts & Offline Payment Verification
- **Invoice Management (`invoices`)**: Supports hourly billing, retainer packages, tax preparation fees, and late fee calculations.
- **Offline Payment Hub**: Supports Wire Transfer, Cashier's Check, Zelle, and Cryptocurrency deposits.
- **Payment Proof Verification**: Clients upload payment receipt slips or transaction hashes. Admins receive real-time Telegram alerts to approve or reject payment proofs with automated receipt generation.

### 6. Real-Time Telegram Practice Notifications
The system is integrated with the Telegram Bot API via [`GeneralSettings::sendTelegramNotification`](file:///c:/Users/USER/Downloads/YourCpaExpert%20Last%20Before%20Hostinger%20ID%20verif/app/Models/GeneralSettings.php#L32):
- **Bot Token:** Configured in `.env` (`TELEGRAM_BOT_TOKEN`)
- **Admin Chat ID:** Configured in `.env` (`TELEGRAM_CHAT_ID`)
- **Automated Alerts Trigger On:**
  - Client registration & credentials generation
  - Custom welcome email delivery
  - Case milestone updates & settlement disbursements
  - Invoice generation & client payment proof uploads
  - Staff clock-ins, clock-outs, and payout requests
  - Hardship intake and appointment bookings

---

## 6. Project Directory Reference

```
YourCpaExpert/
├── app/
│   ├── Actions/Fortify/             # Fortify authentication actions (password, profile)
│   ├── Http/Controllers/
│   │   ├── AdminControllers/        # 22 Controllers (Cases, Staff, Invoices, KYC, Settings)
│   │   ├── ClientViewControllers/   # Client portal & security controllers
│   │   ├── GuestViewControllers/    # Public site, contact, appointment, & document tracking
│   │   └── StaffControllers/        # Staff dashboard, time clock, ledger, and tasks
│   ├── Http/Middleware/             # Admin guard, security wizard, 2FA gate middlewares
│   └── Models/                      # 56 Eloquent Models (ClientCase, StaffDetail, etc.)
│
├── database/
│   ├── migrations/                  # Complete database schema & feature migrations
│   └── seeders/                     # Default admin user, roles, and settings seeders
│
├── public/                          # Public web root (assets, uploads, document vault)
│   └── upload/                      # Uploaded case documents, KYC files, avatars, logos
│
├── resources/views/
│   ├── auth/                        # Login, admin-login, register, forgot-password
│   ├── backend/                     # Admin control panel blade views
│   └── frontend/theme1/
│       ├── auth-staff/              # Staff portal blade views (dashboard, ledger, clock)
│       ├── client/                  # Client portal blade views (cases, escrow, 2FA)
│       └── pages/                   # Public website pages (services, team, about, contact)
│
├── routes/
│   ├── web.php                      # All route definitions, /setup migration runner
│   └── api.php                      # API routes
│
├── .env                             # Environment configuration (Hostinger DB, SMTP, Telegram)
└── push.ps1                         # 1-Click PowerShell Git commit & GitHub deployment script
```

---

## 7. Operational Quick Reference Checklist

| Action | How to Execute |
|---|---|
| **Deploy code updates** | Run `.\push.ps1 "Your commit message"` |
| **Apply database changes** | Push changes, then visit `https://yourcpaexpert.com/setup` |
| **Admin & Attorney Login** | Visit `https://yourcpaexpert.com/admin/login` |
| **Staff Portal Login** | Visit `https://yourcpaexpert.com/staff/login` |
| **Client Portal Login** | Visit `https://yourcpaexpert.com/login` |
| **Impersonate a Client** | Admin &rarr; Users &rarr; Clients &rarr; Click **Login As** |
| **Regenerate Client Credentials** | Admin &rarr; Users &rarr; Clients &rarr; Click **Generate Credentials** |
| **Verify Staff Hours & Wages** | Admin &rarr; Staff &rarr; View **Time Logs** or **Ledger** |
| **Review Payment Proofs** | Admin &rarr; Invoices &rarr; Click **Approve/Reject Proof** |
| **Monitor Practice Alerts** | Check Telegram chat notifications via bot |

