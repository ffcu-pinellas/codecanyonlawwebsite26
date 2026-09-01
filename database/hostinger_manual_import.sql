-- =================================================================
-- HOSTINGER / PHPMYADMIN COMPATIBLE MANUAL SQL IMPORT SCRIPT
-- Your CPA Expert & Legal Representation Platform
-- =================================================================

-- 1. USERS: 2FA, Security PIN, Temporary Credentials & Preferences
ALTER TABLE `users` 
  ADD COLUMN IF NOT EXISTS `pin_hash` VARCHAR(255) NULL AFTER `password`,
  ADD COLUMN IF NOT EXISTS `is_temp_password` TINYINT(1) DEFAULT 0 AFTER `pin_hash`,
  ADD COLUMN IF NOT EXISTS `is_first_login` TINYINT(1) DEFAULT 0 AFTER `is_temp_password`,
  ADD COLUMN IF NOT EXISTS `assigned_attorney_id` BIGINT UNSIGNED NULL AFTER `is_first_login`,
  ADD COLUMN IF NOT EXISTS `preferred_currency` VARCHAR(10) DEFAULT 'USD' AFTER `assigned_attorney_id`,
  ADD COLUMN IF NOT EXISTS `device_history` LONGTEXT NULL AFTER `preferred_currency`,
  ADD COLUMN IF NOT EXISTS `two_factor_enabled` TINYINT(1) DEFAULT 1 AFTER `password`,
  ADD COLUMN IF NOT EXISTS `otp_code` VARCHAR(10) NULL AFTER `two_factor_enabled`,
  ADD COLUMN IF NOT EXISTS `otp_expires_at` TIMESTAMP NULL AFTER `otp_code`,
  ADD COLUMN IF NOT EXISTS `otp_method` VARCHAR(10) DEFAULT 'email' AFTER `otp_expires_at`;

-- 2. CASE DOCUMENTS: Document Vault, Signatures, and Custom Content
ALTER TABLE `case_documents`
  ADD COLUMN IF NOT EXISTS `document_type` VARCHAR(100) NULL DEFAULT 'Standard / General Document' AFTER `is_client_uploaded`,
  ADD COLUMN IF NOT EXISTS `requires_signature` TINYINT(1) DEFAULT 0 AFTER `document_type`,
  ADD COLUMN IF NOT EXISTS `is_signed` TINYINT(1) DEFAULT 0 AFTER `requires_signature`,
  ADD COLUMN IF NOT EXISTS `signed_at` TIMESTAMP NULL AFTER `is_signed`,
  ADD COLUMN IF NOT EXISTS `custom_content` LONGTEXT NULL AFTER `signed_at`,
  ADD COLUMN IF NOT EXISTS `visibility` VARCHAR(20) NULL DEFAULT 'client_visible' AFTER `custom_content`;

-- 3. CASE MILESTONES: Timeline Visibility & Status Indicators
ALTER TABLE `case_milestones`
  ADD COLUMN IF NOT EXISTS `visibility` VARCHAR(20) NULL DEFAULT 'client_visible' AFTER `status`,
  ADD COLUMN IF NOT EXISTS `color` VARCHAR(20) NULL DEFAULT 'completed' AFTER `visibility`;

-- 4. INVOICES: Late Fee System, Schedules, and Payment Instructions
ALTER TABLE `invoices`
  ADD COLUMN IF NOT EXISTS `late_fee_enabled` TINYINT(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `late_fee_type` VARCHAR(20) DEFAULT 'daily',
  ADD COLUMN IF NOT EXISTS `late_fee_is_percentage` TINYINT(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `late_fee_amount` DECIMAL(12, 2) DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS `late_fee_start_date` DATE NULL,
  ADD COLUMN IF NOT EXISTS `late_fee_accumulated` DECIMAL(12, 2) DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS `payment_info` TEXT NULL;

-- 5. CLIENT CASES: Case Stages, Claims, Escrow, and Jurisdiction Hubs
ALTER TABLE `client_cases`
  ADD COLUMN IF NOT EXISTS `lifecycle_stage` INT DEFAULT 1 AFTER `status`,
  ADD COLUMN IF NOT EXISTS `progress_percent` INT DEFAULT 20 AFTER `lifecycle_stage`,
  ADD COLUMN IF NOT EXISTS `claim_amount` DECIMAL(15, 2) DEFAULT 0.00 AFTER `progress_percent`,
  ADD COLUMN IF NOT EXISTS `settled_amount` DECIMAL(15, 2) DEFAULT 0.00 AFTER `claim_amount`,
  ADD COLUMN IF NOT EXISTS `currency` VARCHAR(10) DEFAULT 'USD' AFTER `settled_amount`,
  ADD COLUMN IF NOT EXISTS `show_financial_schedule` TINYINT(1) DEFAULT 0 AFTER `currency`,
  ADD COLUMN IF NOT EXISTS `show_settlement_escrow` TINYINT(1) DEFAULT 0 AFTER `show_financial_schedule`,
  ADD COLUMN IF NOT EXISTS `show_jurisdiction_tracker` TINYINT(1) DEFAULT 0 AFTER `show_settlement_escrow`,
  ADD COLUMN IF NOT EXISTS `schedule_title` VARCHAR(150) DEFAULT 'Audit & Financial Schedule' AFTER `show_jurisdiction_tracker`,
  ADD COLUMN IF NOT EXISTS `settlement_title` VARCHAR(150) DEFAULT 'Retainer & Trust Settlement Hub' AFTER `schedule_title`,
  ADD COLUMN IF NOT EXISTS `jurisdiction_title` VARCHAR(150) DEFAULT 'Court & Regulatory Jurisdictions' AFTER `settlement_title`;

-- Optional table creation for Audit Logs if not yet created
CREATE TABLE IF NOT EXISTS `system_audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `action` VARCHAR(100) NOT NULL,
  `details` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `user_type` VARCHAR(20) DEFAULT 'client',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
