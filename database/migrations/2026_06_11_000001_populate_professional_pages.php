<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PopulateProfessionalPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Update dynamic_pages table
        // Terms of Use
        DB::table('dynamic_pages')->updateOrInsert(
            ['slug' => 'terms-of-use'],
            [
                'name' => 'terms-of-use',
                'title' => 'TERMS OF USE',
                'page_content' => '<h3>1. Agreement to Terms</h3><p>Welcome to Your CPA Expert. These Terms of Use govern your access to and use of our website, client portal, and related financial and legal advisory services. By accessing or using our services, you agree to be bound by these Terms. If you do not agree, please discontinue use immediately.</p><h3>2. Professional Services and No Client Relationship</h3><p>The information provided on this website is for general educational and informational purposes only. Transmission of information does not create an attorney-client or CPA-client relationship. A formal relationship is only established through a signed engagement letter between you and Your CPA Expert.</p><h3>3. User Accounts & Client Portal</h3><p>When you register for an account or upload documents to our client portal, you must provide accurate, complete, and current information. You are solely responsible for safeguarding the credentials you use to access the portal and for any activities or actions under your password.</p><h3>4. Acceptable Use</h3><p>You agree not to use the portal or our systems to upload or transmit any materials that are unlawful, defamatory, harassing, or contain software viruses. Any unauthorized attempt to breach our security controls or access other user accounts will result in immediate termination of service and referral to law enforcement.</p><h3>5. Limitation of Liability</h3><p>To the maximum extent permitted by law, Your CPA Expert and its professionals shall not be liable for any direct, indirect, incidental, special, or consequential damages resulting from the use of, or inability to use, our digital portal or materials. Financial planning and legal advice are highly individualized; outcomes are never guaranteed.</p><h3>6. Intellectual Property</h3><p>All content, branding, calculations, forms, and custom document templates provided on our site are the property of Your CPA Expert and are protected by copyright and intellectual property laws.</p><h3>7. Contact Information</h3><p>For questions or formal inquiries regarding these Terms, please contact us at support@yourcpaexpert.com.</p>',
                'status' => true,
                'delete_able' => false,
                'on_page_menu' => true,
            ]
        );

        // Privacy Policy
        DB::table('dynamic_pages')->updateOrInsert(
            ['slug' => 'privacy-policies'],
            [
                'name' => 'privacy-policies',
                'title' => 'PRIVACY POLICIES',
                'page_content' => '<h3>1. Commitment to Privacy</h3><p>Your CPA Expert is committed to protecting the confidentiality and integrity of your personal and financial information. This Privacy Policy describes how we collect, store, share, and protect your data when you interact with our website, portal, or advisory services.</p><h3>2. Information We Collect</h3><p>We collect personal information necessary to deliver legal and tax compliance services. This includes:<ul><li><strong>Contact Details:</strong> Name, address, email address, phone number.</li><li><strong>Financial & Tax Data:</strong> IRS documents, payroll information, bank routing details, ledger logs, voided checks, and transaction records.</li><li><strong>Technical Information:</strong> IP address, browser type, and usage patterns gathered via cookies to optimize portal performance.</li></ul></p><h3>3. How We Use Your Information</h3><p>We use your information exclusively to:<ul><li>Deliver professional accounting, tax representation, and legal advisory services.</li><li>Provide access to and manage your client portal dashboard.</li><li>Generate secure, accurate invoice and billing documentation.</li><li>Send transactional emails with secure PDF attachments (e.g., invoices and signed agreements).</li></ul></p><h3>4. Sharing and Disclosure</h3><p>We never sell, rent, or trade your personal data. We only share information with third-party service providers (such as secure cloud storage or payment processors) to the extent necessary to process payments or execute service requests. We may disclose data if required by law, court order, or professional regulatory standards.</p><h3>5. Data Security</h3><p>We implement bank-grade security controls, including SSL/TLS encryption for all data transit and AES-256 encryption for documents stored in our case vault. Access is restricted to authorized personnel bound by strict professional confidentiality agreements.</p><h3>6. Your Rights & Options</h3><p>Depending on your jurisdiction, you may have the right to request access to, correction of, or deletion of your personal data. You may withdraw consent at any time, subject to legal and professional record retention requirements.</p><h3>7. Updates and Contact</h3><p>We may update this policy periodically. Any changes will be posted on this page with an updated revision date. If you have questions about our data practices, please contact us at support@yourcpaexpert.com.</p>',
                'status' => true,
                'delete_able' => false,
                'on_page_menu' => true,
            ]
        );

        // 2. Update page_section_settings table for About page
        // Find or create the About page row in page_settings
        $aboutPageId = DB::table('page_settings')->where('name', 'about')->value('id');
        if (!$aboutPageId) {
            $aboutPageId = DB::table('page_settings')->insertGetId([
                'name' => 'about',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Section: right_about
        DB::table('page_section_settings')->updateOrInsert(
            ['name' => 'right_about'],
            [
                'page_id' => $aboutPageId,
                'title' => 'Integrated Law & CPA Advisory',
                'sub_title' => 'A Unified Front for Your Assets',
                'description' => '<p>At Your CPA Expert, we bridge the gap between financial compliance and legal defense. Our unique firm combines senior tax professionals, forensic accountants, and corporate attorneys under one roof, offering you seamless protection and strategic optimization.</p><p>We believe that modern businesses and high-net-worth individuals deserve proactive advisory, not just reactive compliance. By integrating professional legal guidance with advanced tax strategies, we protect your legacy, lower your tax liability, and ensure your operations are fully shielded from regulatory risk.</p>',
                'show' => true,
            ]
        );

        // Section: left_about_img
        DB::table('page_section_settings')->updateOrInsert(
            ['name' => 'left_about_img'],
            [
                'page_id' => $aboutPageId,
                'title' => 'Corporate Headquarters',
                'fnt_img' => '/frontend/theme1/images/about/1.jpg',
                'show' => true,
            ]
        );

        // Section: about_appointment
        DB::table('page_section_settings')->updateOrInsert(
            ['name' => 'about_appointment'],
            [
                'page_id' => $aboutPageId,
                'case_won' => '99% Success Rate',
                'total_attorney' => '15+ Senior CPAs & Attorneys',
                'total_client' => '1,500+ Clients Retained',
                'total_case_dismissed' => '10M+ Saved in Tax Controversy',
                'form_title' => 'Schedule Your Consultation',
                'form_subtitle' => 'Connect directly with an attorney or CPA to discuss your strategy.',
                'show' => true,
            ]
        );

        // Section: about_attorney
        DB::table('page_section_settings')->updateOrInsert(
            ['name' => 'about_attorney'],
            [
                'page_id' => $aboutPageId,
                'title' => 'Dedicated Advisors',
                'sub_title' => 'Meet Our Senior Leadership',
                'description' => 'Our legal partners and accounting experts bring decades of experience, working together to deliver holistic tax strategies and corporate defense.',
                'number_of_content' => 3,
                'show' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No-op
    }
}
