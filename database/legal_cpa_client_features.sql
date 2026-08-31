-- --------------------------------------------------------
-- Legal & CPA Client Features Schema Update for Bibric Law
-- Database: u664663598_cpaexpert
-- --------------------------------------------------------

-- 1. Extend Users table with PIN, First-Login, Attorney assignment, currency & device history
ALTER TABLE `users` 
  ADD COLUMN IF NOT EXISTS `pin_hash` VARCHAR(255) NULL AFTER `password`,
  ADD COLUMN IF NOT EXISTS `is_temp_password` TINYINT(1) DEFAULT 0 AFTER `pin_hash`,
  ADD COLUMN IF NOT EXISTS `is_first_login` TINYINT(1) DEFAULT 0 AFTER `is_temp_password`,
  ADD COLUMN IF NOT EXISTS `assigned_attorney_id` BIGINT UNSIGNED NULL AFTER `is_first_login`,
  ADD COLUMN IF NOT EXISTS `preferred_currency` VARCHAR(10) DEFAULT 'USD' AFTER `assigned_attorney_id`,
  ADD COLUMN IF NOT EXISTS `device_history` JSON NULL AFTER `preferred_currency`;

-- 2. Extend Client Cases with customizable Legal & CPA modules
ALTER TABLE `client_cases`
  ADD COLUMN IF NOT EXISTS `lifecycle_stage` INT DEFAULT 1 AFTER `status`,
  ADD COLUMN IF NOT EXISTS `progress_percent` INT DEFAULT 20 AFTER `lifecycle_stage`,
  ADD COLUMN IF NOT EXISTS `claim_amount` DECIMAL(15,2) DEFAULT 0.00 AFTER `progress_percent`,
  ADD COLUMN IF NOT EXISTS `settled_amount` DECIMAL(15,2) DEFAULT 0.00 AFTER `claim_amount`,
  ADD COLUMN IF NOT EXISTS `currency` VARCHAR(10) DEFAULT 'USD' AFTER `settled_amount`,
  ADD COLUMN IF NOT EXISTS `show_financial_schedule` TINYINT(1) DEFAULT 0 AFTER `currency`,
  ADD COLUMN IF NOT EXISTS `show_settlement_escrow` TINYINT(1) DEFAULT 0 AFTER `show_financial_schedule`,
  ADD COLUMN IF NOT EXISTS `show_jurisdiction_tracker` TINYINT(1) DEFAULT 0 AFTER `show_settlement_escrow`,
  ADD COLUMN IF NOT EXISTS `schedule_title` VARCHAR(150) DEFAULT 'Audit & Financial Schedule' AFTER `show_jurisdiction_tracker`,
  ADD COLUMN IF NOT EXISTS `settlement_title` VARCHAR(150) DEFAULT 'Retainer & Trust Settlement Hub' AFTER `schedule_title`,
  ADD COLUMN IF NOT EXISTS `jurisdiction_title` VARCHAR(150) DEFAULT 'Court & Regulatory Jurisdictions' AFTER `settlement_title`;

-- 3. Case Financial & Audit Schedules Table
CREATE TABLE IF NOT EXISTS `case_financial_schedules` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `case_id` BIGINT UNSIGNED NOT NULL,
  `item_category` VARCHAR(100) DEFAULT 'Asset / Schedule',
  `item_description` VARCHAR(255) NOT NULL,
  `reference_code` VARCHAR(100) NULL,
  `amount` DECIMAL(15,2) DEFAULT 0.00,
  `currency` VARCHAR(10) DEFAULT 'USD',
  `status` VARCHAR(50) DEFAULT 'Audited',
  `entry_date` DATE NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_sched_case` (`case_id`),
  FOREIGN KEY (`case_id`) REFERENCES `client_cases`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Case Settlements & Retainer Trust Table
CREATE TABLE IF NOT EXISTS `case_settlements` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `case_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `client_id` BIGINT UNSIGNED NOT NULL,
  `gross_amount` DECIMAL(15,2) DEFAULT 0.00,
  `legal_fee_percent` DECIMAL(5,2) DEFAULT 10.00,
  `legal_fee_amount` DECIMAL(15,2) DEFAULT 0.00,
  `expenses_amount` DECIMAL(15,2) DEFAULT 0.00,
  `net_client_payout` DECIMAL(15,2) DEFAULT 0.00,
  `currency` VARCHAR(10) DEFAULT 'USD',
  `escrow_trust_ref` VARCHAR(100) NULL,
  `custody_depository` VARCHAR(255) DEFAULT 'IOLTA Legal Trust Account',
  `clearance_stage` INT DEFAULT 1,
  `status` VARCHAR(100) DEFAULT 'Held in Trust',
  `payout_method` VARCHAR(100) NULL,
  `payout_destination_details` TEXT NULL,
  `client_confirmed_at` DATETIME NULL,
  `client_signature_hash` VARCHAR(255) NULL,
  `is_enabled` TINYINT(1) DEFAULT 1,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_settle_case` (`case_id`),
  INDEX `idx_settle_client` (`client_id`),
  FOREIGN KEY (`case_id`) REFERENCES `client_cases`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`client_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Case Court & Regulatory Jurisdictions Table
CREATE TABLE IF NOT EXISTS `case_jurisdictions` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `case_id` BIGINT UNSIGNED NOT NULL,
  `jurisdiction_name` VARCHAR(150) NOT NULL,
  `court_venue` VARCHAR(255) NULL,
  `action_type` VARCHAR(150) NOT NULL,
  `docket_number` VARCHAR(100) NULL,
  `status` VARCHAR(100) DEFAULT 'Filing Active',
  `filing_date` DATE NULL,
  `notes` TEXT NULL,
  `is_enabled` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_juris_case` (`case_id`),
  FOREIGN KEY (`case_id`) REFERENCES `client_cases`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Legal & CPA KYC / Due Diligence Documents Table
CREATE TABLE IF NOT EXISTS `client_kyc_documents` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `client_id` BIGINT UNSIGNED NOT NULL,
  `case_id` BIGINT UNSIGNED NULL,
  `document_type` VARCHAR(100) NOT NULL,
  `file_title` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `file_size` VARCHAR(50) NULL,
  `status` ENUM('Pending Review', 'Approved', 'Needs Resubmission') DEFAULT 'Pending Review',
  `reviewer_notes` TEXT NULL,
  `reviewed_by` BIGINT UNSIGNED NULL,
  `reviewed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_kyc_client` (`client_id`),
  INDEX `idx_kyc_case` (`case_id`),
  FOREIGN KEY (`client_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`case_id`) REFERENCES `client_cases`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. System Audit Logs Table
CREATE TABLE IF NOT EXISTS `system_audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `user_type` VARCHAR(50) DEFAULT 'client',
  `action_key` VARCHAR(100) NOT NULL,
  `details` TEXT NULL,
  `ip_address` VARCHAR(100) NULL,
  `user_agent` VARCHAR(500) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_audit_user` (`user_id`, `user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
