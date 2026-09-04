<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('document_templates')) {
            Schema::create('document_templates', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('title');
                $table->string('type'); // 'client' or 'staff'
                $table->longText('content');
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // Populate initial templates
        $templates = [
            // Client Templates
            [
                'key' => 'engagement_letter',
                'title' => 'Client Engagement Letter (Retainer Agreement)',
                'type' => 'client',
                'content' => '<h3>ENGAGEMENT LETTER &amp; RETAINER AGREEMENT</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>This agreement confirms the engagement of <strong>{{company_name}}</strong> (referred to as "the Firm") by <strong>{{client_name}}</strong> (referred to as "the Client") residing at <strong>{{client_address}}</strong>, to provide legal representation and CPA tax services.</p>
<h4>1. Scope of Representation</h4>
<p>The Firm is engaged to represent the Client in connection with tax compliance, financial evaluation, tax relief planning, or legal disputes. The specific case details will be evaluated and managed under Case #{{case_number}} by your assigned attorney/CPA <strong>{{attorney_name}}</strong>.</p>
<h4>2. Fees and Billing Arrangements</h4>
<p>Client agrees to pay the representation fees as set forth in the billing invoices. Statements of accounts and itemized bills will be issued periodically. Payments must be settled within the timeframe specified in the invoice details.</p>
<h4>3. Client Cooperation &amp; Obligations</h4>
<p>The Client agrees to fully cooperate with the Firm, providing all requested financial papers, tax notices, tax returns, and bank statements in a timely manner. The Client understands that delays in providing information may adversely affect case timelines and outcomes.</p>
<h4>4. Professional Disclaimer</h4>
<p>While the Firm will strive to obtain the best possible outcome for the Client, the Client acknowledges that no guarantees have been made regarding the final resolution of tax audits, penalty abatement, or settlement negotiations with the IRS or state revenue departments.</p>
<p>Please sign and return a copy of this agreement to indicate your consent to these terms.</p>
<hr />
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td style="width: 50%; vertical-align: top;">
      ____________________________________<br />
      <strong>Client Signature</strong><br />
      Name: {{client_name}}<br />
      Date: ____/____/________
    </td>
    <td style="width: 50%; vertical-align: top;">
      ____________________________________<br />
      <strong>Authorized Firm Representative</strong><br />
      Name: {{attorney_name}}<br />
      Date: {{date}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'payment_schedule',
                'title' => 'Payment Schedule Addendum',
                'type' => 'client',
                'content' => '<h3>PAYMENT SCHEDULE ADDENDUM</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>This Addendum is attached to and forms a part of the Engagement Agreement between <strong>{{company_name}}</strong> and <strong>{{client_name}}</strong> (Client) for Case #{{case_number}}.</p>
<h4>1. Compensation and Installments</h4>
<p>Client agrees to pay the total contract fee in installments according to the schedule below:</p>
<table style="width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px;">
  <thead>
    <tr style="background-color: #f2f2f2;">
      <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Payment Phase / Milestone</th>
      <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Amount ($)</th>
      <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Due Date / Trigger</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="border: 1px solid #ddd; padding: 8px;">Initial Retainer / Sign-on Fee</td>
      <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">$_______________</td>
      <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">Upon Execution</td>
    </tr>
    <tr>
      <td style="border: 1px solid #ddd; padding: 8px;">Case Evaluation &amp; Intake Preparation</td>
      <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">$_______________</td>
      <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">Within 30 Days</td>
    </tr>
    <tr>
      <td style="border: 1px solid #ddd; padding: 8px;">Formal Filing / IRS Submissions</td>
      <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">$_______________</td>
      <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">Prior to Filing</td>
    </tr>
  </tbody>
</table>
<h4>2. Default in Payment</h4>
<p>In the event that the Client fails to make any payment when due under this schedule, the Firm reserves the right to suspend all work on Case #{{case_number}} and, if necessary, withdraw from representation, subject to applicable ethical guidelines and notification requirements.</p>
<hr />
<p>Approved and agreed to by:</p>
<table style="width: 100%; margin-top: 20px;">
  <tr>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Client Signature</strong><br />
      Date: ____/____/________
    </td>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Firm Representative Signature</strong><br />
      Date: {{date}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'power_of_attorney',
                'title' => 'General Power of Attorney (POA)',
                'type' => 'client',
                'content' => '<h3>GENERAL DURABLE POWER OF ATTORNEY</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>I, <strong>{{client_name}}</strong>, residing at <strong>{{client_address}}</strong>, hereby appoint <strong>{{attorney_name}}</strong> of <strong>{{company_name}}</strong> as my attorney-in-fact (Agent) to act in my name, place, and stead in any way which I myself could do, if I were personally present.</p>
<h4>1. Powers Conferred</h4>
<p>The Agent is granted full authority to handle all legal, financial, and tax matters on my behalf. This includes, but is not limited to, the authority to access bank records, execute legal documents, coordinate with government authorities, file claims, and represent me in tax audits or administrative hearings.</p>
<h4>2. Durability</h4>
<p>This Power of Attorney is durable and shall not be affected by my subsequent disability or incapacity. It shall remain in full force and effect until revoked by me in writing.</p>
<h4>3. Indemnity</h4>
<p>I hereby agree to indemnify and hold harmless my Agent and any third party who acts in reliance upon this power of attorney before receiving written notification of its revocation.</p>
<hr />
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td style="width: 60%;">
      ____________________________________<br />
      <strong>Principal Signature</strong><br />
      Name: {{client_name}}<br />
      Date: ____/____/________
    </td>
    <td style="width: 40%;">
      ____________________________________<br />
      <strong>Notary Signature / Seal</strong><br />
      Date: ____/____/________
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'irs_cpa_auth',
                'title' => 'IRS Form CPA Representation Authorization',
                'type' => 'client',
                'content' => '<h3>IRS TAX REPRESENTATION AUTHORIZATION</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>The undersigned taxpayer, <strong>{{client_name}}</strong>, residing at <strong>{{client_address}}</strong>, hereby authorizes <strong>{{attorney_name}}</strong>, CPA/Representative of <strong>{{company_name}}</strong>, to represent the taxpayer before the Internal Revenue Service (IRS) and/or state tax commissions.</p>
<h4>1. Tax Matters Authorized</h4>
<p>The Representative is authorized to receive and inspect confidential tax information, receive and sign agreements, and represent the taxpayer before the IRS for the following tax forms and tax years:</p>
<ul>
  <li>Form 1040 (Individual Income Tax) - Years: 2021, 2022, 2023, 2024</li>
  <li>Form 941 (Employer\'s Quarterly Federal Tax Return) - Years: 2022, 2023</li>
  <li>Civil Penalty Evaluations and Abatements</li>
</ul>
<h4>2. Acts Authorized</h4>
<p>The Representative is authorized to sign consents extending the statutory period for assessment, sign closing agreements, and execute plans for installment payment agreements or offers in compromise.</p>
<hr />
<p>Signed and executed on this date:</p>
<table style="width: 100%; margin-top: 25px;">
  <tr>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Taxpayer Signature</strong><br />
      Name: {{client_name}}<br />
      Date: ____/____/________
    </td>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Representative Signature</strong><br />
      Name: {{attorney_name}}<br />
      Date: {{date}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'tax_return_review',
                'title' => 'Tax Return Review Confirmation',
                'type' => 'client',
                'content' => '<h3>TAX RETURN REVIEW &amp; E-FILE AUTHORIZATION</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>This form confirms that <strong>{{client_name}}</strong> has reviewed the prepared federal and state income tax returns for the tax year ending December 31, 2025.</p>
<h4>1. Taxpayer Responsibility</h4>
<p>The Client acknowledges that they have examined the tax return documents, including all attached schedules, and verify that the information is correct and complete to the best of their knowledge. The Client understands that they are legally responsible for the truthfulness of the disclosures made on the tax returns.</p>
<h4>2. Authorization to E-File</h4>
<p>The Client hereby authorizes the Firm <strong>{{company_name}}</strong> to electronically transmit (e-file) the federal and state tax returns on behalf of the Client.</p>
<hr />
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td>
      ____________________________________<br />
      <strong>Taxpayer Signature</strong><br />
      Date: ____/____/________
    </td>
    <td>
      ____________________________________<br />
      <strong>Spouse Signature (if Joint Return)</strong><br />
      Date: ____/____/________
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'privacy_disclosure',
                'title' => 'Privacy Policy and Disclosure Notice',
                'type' => 'client',
                'content' => '<h3>PRIVACY POLICY AND DISCLOSURE NOTICE</h3>
<p>At <strong>{{company_name}}</strong>, protecting your private financial and legal data is our top priority. This notice explains how we collect, store, and utilize your personal information.</p>
<h4>1. Information We Collect</h4>
<p>We collect non-public personal information about you from the data you provide to us on tax intakes, engagement papers, case summaries, or during tax consultations (e.g. Social Security Numbers, bank account info, income sources, asset schedules).</p>
<h4>2. Confidentiality and Security</h4>
<p>We restrict access to your personal information to those members of our Firm who need to know that information to provide professional legal or CPA representation to you. We maintain physical, electronic, and procedural safeguards that comply with federal regulations to guard your non-public personal information.</p>
<h4>3. Disclosure of Information</h4>
<p>We do not disclose any non-public personal information about our clients or former clients to anyone, except as permitted or required by law (e.g., submitting documents to the IRS or in response to a valid court subpoena).</p>
<p>By signing below, you acknowledge receipt of this privacy disclosure notice.</p>
<hr />
<p><strong>Client Acknowledgment:</strong></p>
<p>____________________________________<br />
<strong>Signature: {{client_name}}</strong><br />
Date: ____/____/________</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Staff Templates
            [
                'key' => 'direct_deposit',
                'title' => 'Direct Deposit Authorization Form',
                'type' => 'staff',
                'content' => '<h3>DIRECT DEPOSIT AUTHORIZATION FORM</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>Please use this form to authorize <strong>{{company_name}}</strong> to deposit wages directly into your bank account. Note: Staff ID: <strong>{{staff_id}}</strong>.</p>
<h4>1. Employee Details</h4>
<p>Employee Name: <strong>{{employee_name}}</strong><br />
Email Address: <strong>{{employee_email}}</strong><br />
Phone Number: <strong>{{employee_phone}}</strong></p>
<h4>2. Bank Details (Please Write Clearly)</h4>
<table style="width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px;">
  <tr>
    <td style="border: 1px solid #ddd; padding: 10px; font-weight: bold; background-color: #f9f9f9; width: 30%;">Bank Name</td>
    <td style="border: 1px solid #ddd; padding: 10px; height: 30px;"></td>
  </tr>
  <tr>
    <td style="border: 1px solid #ddd; padding: 10px; font-weight: bold; background-color: #f9f9f9;">Routing Number (9 Digits)</td>
    <td style="border: 1px solid #ddd; padding: 10px; height: 30px;"></td>
  </tr>
  <tr>
    <td style="border: 1px solid #ddd; padding: 10px; font-weight: bold; background-color: #f9f9f9;">Account Number</td>
    <td style="border: 1px solid #ddd; padding: 10px; height: 30px;"></td>
  </tr>
  <tr>
    <td style="border: 1px solid #ddd; padding: 10px; font-weight: bold; background-color: #f9f9f9;">Account Type</td>
    <td style="border: 1px solid #ddd; padding: 10px;">[  ] Checking &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [  ] Savings</td>
  </tr>
</table>
<h4>3. Authorization Agreement</h4>
<p>I hereby authorize <strong>{{company_name}}</strong> to initiate credit entries and, if necessary, debit entries and adjustments for any credit entries in error to my account indicated above. This authorization is to remain in full force and effect until the Company has received written notification from me of its termination in such time and in such manner as to afford the Company and the Depository a reasonable opportunity to act on it.</p>
<hr />
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td style="width: 70%;">
      ____________________________________<br />
      <strong>Employee Signature</strong><br />
      Date: ____/____/________
    </td>
    <td style="width: 30%;">
      <strong>Staff ID:</strong> {{staff_id}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'offer_letter',
                'title' => 'Employment Offer Letter',
                'type' => 'staff',
                'content' => '<h3>EMPLOYMENT OFFER LETTER</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>Dear <strong>{{employee_name}}</strong>,</p>
<p>On behalf of <strong>{{company_name}}</strong>, we are pleased to offer you employment for the position of Staff Specialist at our firm, reporting to your designated administrative supervisor.</p>
<h4>1. Position and Scope</h4>
<p>As a team member, you will perform duties associated with case coordination, administration, and financial operations. Your Staff Identifier will be <strong>{{staff_id}}</strong>.</p>
<h4>2. Salary and Payout Terms</h4>
<p>Your compensation and salary rates are managed and tracked under the Firm\'s Financial Ledger. Payout requests can be requested directly inside your Staff Dashboard, subject to administrative approval and standard payroll schedules.</p>
<h4>3. Verification and Terms</h4>
<p>This offer is contingent upon the successful completion of a background check, verification of identity, and execution of the standard Non-Disclosure Agreement (NDA).</p>
<p>To accept this offer, please sign and return this letter within 7 days.</p>
<p>Welcome to <strong>{{company_name}}</strong>!</p>
<hr />
<table style="width: 100%; margin-top: 35px;">
  <tr>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Employee Acceptance Signature</strong><br />
      Date: ____/____/________
    </td>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Authorized Officer Signature</strong><br />
      Date: {{date}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'background_check',
                'title' => 'Background Check Authorization Form',
                'type' => 'staff',
                'content' => '<h3>BACKGROUND CHECK &amp; CONSUMER REPORT DISCLOSURE</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>In connection with your employment application at <strong>{{company_name}}</strong>, a consumer background report may be obtained for employment purposes.</p>
<h4>1. Scope of Investigation</h4>
<p>The background check may include information regarding your character, general reputation, criminal record, driving history, previous employment, and educational background.</p>
<h4>2. Acknowledgment &amp; Consent</h4>
<p>I, <strong>{{employee_name}}</strong>, residing at <strong>{{employee_address}}</strong>, hereby authorize the Company and its designated background screening firm to conduct a background check and obtain consumer reports as described above.</p>
<hr />
<p><strong>Employee Consent Sign-off:</strong></p>
<p>____________________________________<br />
<strong>Signature: {{employee_name}}</strong><br />
Staff ID: {{staff_id}}<br />
Date: ____/____/________</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'fw4_addendum',
                'title' => 'Form W-4 Informational Addendum',
                'type' => 'staff',
                'content' => '<h3>FORM W-4 INFORMATIONAL ADDENDUM</h3>
<p><strong>Employee Name:</strong> {{employee_name}}<br />
<strong>Staff ID:</strong> {{staff_id}}<br />
<strong>Date:</strong> {{date}}</p>
<p>This addendum serves as an informational helper to assist you in completing IRS Form W-4 (Employee\'s Withholding Certificate) so that the payroll office at <strong>{{company_name}}</strong> can withhold the correct federal income tax from your pay.</p>
<h4>1. Withholding Considerations</h4>
<p>Your withholding is subject to marital status, multiple jobs adjustments, child tax credits, and additional deductions. Please consult a qualified tax advisor if you have questions regarding your specific withholding allowances.</p>
<h4>2. Submission Instructions</h4>
<p>Please fill out the formal IRS Form W-4, sign it, and upload it as a scanned file inside your secure Staff Dashboard alongside your Direct Deposit form.</p>
<hr />
<p>Employee Acknowledgment:</p>
<p>____________________________________<br />
<strong>Signature: {{employee_name}}</strong><br />
Date: ____/____/________</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'staff_nda',
                'title' => 'Employee Non-Disclosure Agreement (NDA)',
                'type' => 'staff',
                'content' => '<h3>EMPLOYEE NON-DISCLOSURE AGREEMENT (NDA)</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>This Employee Non-Disclosure Agreement is entered into by and between <strong>{{company_name}}</strong> and <strong>{{employee_name}}</strong> (referred to as "Employee"), Staff ID: <strong>{{staff_id}}</strong>.</p>
<h4>1. Confidential Information</h4>
<p>During employment, Employee will have access to confidential client information, tax returns, trade secrets, business strategies, and private financial data of the Firm and its clients. Employee agrees to hold all such information in strict confidence and not to disclose it to any third party without written authorization.</p>
<h4>2. Return of Materials</h4>
<p>Upon termination of employment, Employee shall immediately return all documents, client files, records, notes, and computer data containing or relating to Confidential Information.</p>
<hr />
<p><strong>Agreed and Executed:</strong></p>
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Employee Signature: {{employee_name}}</strong><br />
      Date: ____/____/________
    </td>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>For {{company_name}}</strong><br />
      Date: {{date}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'handbook_ack',
                'title' => 'Employee Handbook Acknowledgment Form',
                'type' => 'staff',
                'content' => '<h3>EMPLOYEE HANDBOOK ACKNOWLEDGMENT</h3>
<p><strong>Employee Name:</strong> {{employee_name}}<br />
<strong>Staff ID:</strong> {{staff_id}}<br />
<strong>Date:</strong> {{date}}</p>
<p>I hereby acknowledge that I have received a copy of the Employee Handbook of <strong>{{company_name}}</strong>. I understand that it is my responsibility to read, understand, and comply with all policies and guidelines outlined in the handbook.</p>
<p>I understand that the handbook is not a contract of employment and that my employment remains at-will, meaning either I or the Company can terminate the employment relationship at any time, with or without cause or notice.</p>
<hr />
<p><strong>Acknowledgment Sign-off:</strong></p>
<p>____________________________________<br />
<strong>Employee Signature</strong><br />
Date: ____/____/________</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'company_letterhead',
                'title' => 'Company Corporate Letterhead Layout',
                'type' => 'client',
                'content' => '<div class="company-executive-letterhead" style="margin-bottom: 25px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Georgia\', serif;">
    <div style="height: 4px; background: linear-gradient(90deg, #1e3c72 0%, #2a5298 50%, #d97706 100%); margin-bottom: 20px; border-radius: 2px;"></div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; border: none !important; background: transparent !important;">
        <tr>
            <td style="vertical-align: middle; text-align: left; width: 60%; border: none !important; padding: 0;">
                <div style="font-size: 24px; font-weight: 800; color: #1e3c72; letter-spacing: 0.8px; text-transform: uppercase;">
                    {{company_name}}
                </div>
                <div style="font-size: 10.5px; font-weight: 700; color: #b45309; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px;">
                    Privileged Legal &amp; CPA Advisory Services &bull; Forensic Recovery
                </div>
            </td>
            <td style="vertical-align: middle; text-align: right; width: 40%; font-size: 11px; color: #475569; line-height: 1.45; border: none !important; padding: 0;">
                <strong style="color: #1e3c72; font-size: 12px;">Corporate Headquarters</strong><br>
                <span>{{company_address}}</span><br>
                <span><strong>Phone:</strong> {{company_phone}}</span><br>
                <span><strong>Email:</strong> {{company_email}}</span>
            </td>
        </tr>
    </table>
    <div style="border-bottom: 2px solid #1e3c72; position: relative;">
        <div style="height: 1px; background: #fecc56; margin-top: 2px;"></div>
    </div>
</div>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'tax_checklist',
                'title' => 'Tax Compliance Intake Checklist',
                'type' => 'client',
                'content' => '<h3>TAX COMPLIANCE INTAKE CHECKLIST</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>Dear <strong>{{client_name}}</strong>,</p>
<p>To help us prepare your tax return accurately for Case #{{case_number}}, please gather the following documents and upload them to your Secure Document Vault:</p>
<h4>1. Income Documents</h4>
<ul>
  <li>Form W-2 (Wage and Tax Statements)</li>
  <li>Form 1099-NEC / 1099-MISC (Self-Employment Income)</li>
  <li>Form 1099-INT / 1099-DIV (Interest and Dividends)</li>
  <li>Schedule K-1 (Partnership or S-Corp Income)</li>
</ul>
<h4>2. Deductions &amp; Credits</h4>
<ul>
  <li>Form 1098 (Mortgage Interest Statement)</li>
  <li>Charitable Donation Receipts and Logs</li>
  <li>Medical and Dental Expense Records</li>
  <li>Childcare Provider Information &amp; Amount Paid</li>
</ul>
<h4>3. Identity &amp; Bank Routing Info</h4>
<ul>
  <li>Government-issued ID cards</li>
  <li>Voided Check for direct deposit of tax refund</li>
</ul>
<p>If you have any questions, please contact your assigned representative <strong>{{attorney_name}}</strong>.</p>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'flat_fee_agreement',
                'title' => 'Flat Fee CPA & Advisory Agreement',
                'type' => 'client',
                'content' => '<h3>FLAT FEE SERVICE AGREEMENT</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>This Flat Fee Agreement is entered into by <strong>{{company_name}}</strong> and <strong>{{client_name}}</strong> for professional services regarding Case #{{case_number}}.</p>
<h4>1. Services Rendered</h4>
<p>The Firm agrees to perform the following services for a set flat fee:</p>
<ul>
  <li>Preparation and filing of Federal &amp; State Income Tax Returns.</li>
  <li>Representation during initial IRS evaluation phases.</li>
  <li>Tax planning consultations for the current calendar year.</li>
</ul>
<h4>2. Flat Fee Compensation</h4>
<p>Client agrees to pay a total flat fee of $_______________ upon execution of this agreement. This fee covers only the scope defined above. Any additional services, disputes, or filings will be subject to a separate hourly agreement.</p>
<hr />
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Client Signature</strong><br />
      Date: ____/____/________
    </td>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>For {{company_name}}</strong><br />
      Date: {{date}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'contractor_agreement',
                'title' => 'Independent Contractor Agreement (1099)',
                'type' => 'staff',
                'content' => '<h3>INDEPENDENT CONTRACTOR AGREEMENT</h3>
<p><strong>Date:</strong> {{date}}</p>
<p>This Independent Contractor Agreement is made between <strong>{{company_name}}</strong> and <strong>{{employee_name}}</strong> (Contractor), Staff ID: <strong>{{staff_id}}</strong>.</p>
<h4>1. Services Provided</h4>
<p>Contractor agrees to perform professional services as a CPA associate or legal coordinator. Contractor shall exercise independent judgment in the manner and methods of performing services.</p>
<h4>2. Compensation and Billing</h4>
<p>Contractor shall submit invoices for services rendered, which will be settled in accordance with the payout policies of <strong>{{company_name}}</strong>. Contractor is solely responsible for all federal, state, and local self-employment taxes.</p>
<hr />
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>Contractor Signature: {{employee_name}}</strong><br />
      Date: ____/____/________
    </td>
    <td style="width: 50%;">
      ____________________________________<br />
      <strong>For {{company_name}}</strong><br />
      Date: {{date}}
    </td>
  </tr>
</table>',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'timesheet_policy',
                'title' => 'Timesheet & Billing Policy Acknowledgment',
                'type' => 'staff',
                'content' => '<h3>TIMESHEET &amp; RECORDING POLICY</h3>
<p><strong>Employee Name:</strong> {{employee_name}}<br />
<strong>Staff ID:</strong> {{staff_id}}<br />
<strong>Date:</strong> {{date}}</p>
<p>All staff members at <strong>{{company_name}}</strong> are required to record their billable time and case updates daily. Accurate timesheets ensure correct billing for client cases and transparent payroll operations.</p>
<h4>1. Daily Logging Guidelines</h4>
<ul>
  <li>Log all client work immediately after completion.</li>
  <li>Specify the exact Case Number and description of tasks performed.</li>
  <li>Submit weekly summaries by Friday at 5:00 PM.</li>
</ul>
<p>Failure to submit accurate logs on time may result in billing discrepancies and delays in payroll payout requests.</p>
<hr />
<p>I have read and agree to comply with this policy:</p>
<p>____________________________________<br />
<strong>Employee Signature: {{employee_name}}</strong><br />
Date: ____/____/________</p>',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($templates as $tmpl) {
            DB::table('document_templates')->insert($tmpl);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
