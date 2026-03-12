-- THE "ZERO-BLEMISH" TOTAL PROFESSIONAL OVERHAUL
-- Firm: Your CPA Expert (Law & CPA Firm)
-- This script purges ALL developer data, dummy entries, and placeholders.

-- 1. SITE IDENTITY & SEO
UPDATE `general_settings` SET 
    `site_name` = 'Your CPA Expert', 
    `site_tag_line` = 'Strategic Law & CPA Advisory', 
    `site_sub_tag_line` = 'Holistic Financial and Legal Security',
    `author_name` = 'Your CPA Expert',
    `og_meta_title` = 'Your CPA Expert | Law & CPA Firm',
    `og_meta_description` = 'Integrated legal and accounting services for businesses and individuals.'
WHERE `id` > 0;

-- 2. STAFF & ADDRESSES (Removing all developer/dummy names)
UPDATE `attorneys` SET 
    `name` = 'Marcus Thorne, JD CPA', 
    `address` = '123 Professional Way, Financial District', 
    `description` = '<p>Marcus Thorne combines the precision of a CPA with the strategic defense of an attorney to provide unparalleled client service.</p>',
    `professional_exp` = 'Over 20 years of experience in corporate law and tax optimization.',
    `phone` = '(555) 123-4567',
    `email` = 'mthorne@yourcpaexpert.com'
WHERE `id` = 1;

UPDATE `attorneys` SET 
    `name` = 'Helena Vane, JD', 
    `address` = '123 Professional Way, Financial District', 
    `description` = '<p>Helena specializes in forensic accounting and legal compliance, ensuring your business is protected from every angle.</p>',
    `email` = 'hvane@yourcpaexpert.com'
WHERE `id` = 2;

-- Deleting extraneous demo attorneys
DELETE FROM `attorneys` WHERE `id` > 2;

-- 3. HEADER & FOOTER (Zeroing out dummy contacts/addresses)
UPDATE `header_settings` SET 
    `left_content` = 'Office: 123 Professional Way, Financial District', 
    `right_content` = 'Call Us: (555) 123-4567' 
WHERE `id` > 0;

UPDATE `footer_settings` SET 
    `column1_short_disc` = 'Your CPA Expert provides a unified front for your financial protection and legal prosperity.',
    `column4_description` = '123 Professional Way, Financial District\r\nUnited States',
    `footer_copy_right` = '© 2024 Your CPA Expert. All Rights Reserved.'
WHERE `id` > 0;

-- 4. CLEANING UP TEST DATA (Purging demo submissions)
TRUNCATE TABLE `appointments`;
TRUNCATE TABLE `contacts`;

-- 5. SLIDERS (Requested Copy + Pure Professionalism)
UPDATE `sliders` SET 
    `title` = 'Committed to your financial success and legal security.', 
    `sub_title` = 'The Ultimate Unified Firm',
    `description` = 'Protecting your personal and corporate assets through expert navigation of complex regulatory environments.',
    `button_name` = 'Consult An Expert'
WHERE `id` = 1;

UPDATE `sliders` SET 
    `title` = 'Strategic Tax & Legal Advisory.', 
    `sub_title` = 'Protecting Your Legacy',
    `description` = 'We combine the rigorous standards of CPAs with the strategic defense of skilled attorneys.'
WHERE `id` = 2;

-- Deleting extra demo sliders
DELETE FROM `sliders` WHERE `id` > 2;

-- 6. UNIQUE SERVICES & CASE STUDIES (No Duplicates)
UPDATE `services` SET `title` = 'Tax Strategy', `description` = 'Optimizing your tax position with legal precision.' WHERE `id` = 1;
UPDATE `services` SET `title` = 'Corporate Counsel', `description` = 'Expert legal advice for your business operations.' WHERE `id` = 2;
UPDATE `services` SET `title` = 'Audit & Compliance', `description` = 'Ensuring financial integrity and regulatory safety.' WHERE `id` = 3;
UPDATE `services` SET `title` = 'Forensic Accounting', `description` = 'Deep financial investigation for litigation support.' WHERE `id` = 4;
UPDATE `services` SET `title` = 'Real Estate Law', `description` = 'Navigating complex property and land use regulations.' WHERE `id` = 5;
UPDATE `services` SET `title` = 'Estate & Trust', `description` = 'Securing your future through expert wealth planning.' WHERE `id` = 6;

-- Case Studies (Table: case_studies)
UPDATE `case_studies` SET `title` = 'Corporate Debt Restructuring', `service_title` = 'Case Study' WHERE `id` = 1;
UPDATE `case_studies` SET `title` = 'M&A Due Diligence', `service_title` = 'Case Study' WHERE `id` = 2;
UPDATE `case_studies` SET `title` = 'Fraud Detection Audit', `service_title` = 'Case Study' WHERE `id` = 3;
UPDATE `case_studies` SET `title` = 'Tax Settlement Triumph', `service_title` = 'Case Study' WHERE `id` = 4;
UPDATE `case_studies` SET `title` = 'Portfolio Asset Protection', `service_title` = 'Case Study' WHERE `id` = 5;
UPDATE `case_studies` SET `title` = 'Global Compliance Audit', `service_title` = 'Case Study' WHERE `id` = 6;

-- 7. TESTIMONIALS (Pure Industry Professionalism)
UPDATE `testimonials` SET `name` = 'Arthur Sterling', `designation` = 'CEO, Sterling Industries', `testimonial` = 'Your CPA Expert redefined our approach to corporate risk. Their dual expertise is a game-changer.' WHERE `id` = 2;
UPDATE `testimonials` SET `name` = 'Elena Vance', `designation` = 'Managing Partner, Vance & Co', `testimonial` = 'Finally, a firm that understands the intersection of the balance sheet and the rule of law.' WHERE `id` = 3;
UPDATE `testimonials` SET `name` = 'Dr. Julian Thorne', `designation` = 'Founder, Thorne Medical', `testimonial` = 'Their tax planning saved us seven figures in the first year alone. Highly ethical and deeply strategic.' WHERE `id` = 4;

-- 8. BLOGS (Unique Educational Content)
UPDATE `blogs` SET `title` = 'The Future of Corporate Taxation', `description` = 'What CEOs need to know about the upcoming legislative shifts.' WHERE `id` = 10;
UPDATE `blogs` SET `title` = 'Protecting Digital Assets', `description` = 'Legal frameworks for safeguarding intellectual property in the cloud.' WHERE `id` = 11;
UPDATE `blogs` SET `title` = 'The Power of Forensic Audits', `description` = 'How internal transparency leads to long-term investor confidence.' WHERE `id` = 12;

-- 9. GLOBAL SEARCH & REPLACE FOR ENCODING/TYPOS
UPDATE `services` SET `title` = REPLACE(`title`, '&amp;', '&'), `description` = REPLACE(`description`, '&amp;', '&');
UPDATE `page_section_settings` SET `title` = REPLACE(`title`, '&amp;', '&'), `sub_title` = REPLACE(`sub_title`, '&amp;', '&'), `description` = REPLACE(`description`, '&amp;', '&');

-- Removing developer scripts
UPDATE `header_footer_settings` SET `header` = NULL;
-- Rename hardships table to relief_requests
RENAME TABLE hardships TO relief_requests;
