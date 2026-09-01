-- Clean Schema Dump for Client Management System
SET FOREIGN_KEY_CHECKS=0;

-- Table structure for `activity_logs`
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_client_id_index` (`client_id`),
  KEY `activity_logs_created_at_index` (`created_at`),
  KEY `activity_logs_user_id_index` (`user_id`),
  KEY `activity_logs_admin_id_index` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `cache`
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `cache_locks`
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `client_documents`
DROP TABLE IF EXISTS `client_documents`;
CREATE TABLE `client_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_documents_client_id_index` (`client_id`),
  KEY `client_documents_uploaded_by_index` (`uploaded_by`),
  KEY `client_documents_status_index` (`status`),
  KEY `client_documents_verified_by_index` (`verified_by`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `client_documents`
INSERT INTO `client_documents` (`id`, `client_id`, `uploaded_by`, `document_name`, `document_type`, `file_path`, `status`, `remarks`, `verified_at`, `verified_by`, `created_at`, `updated_at`) VALUES ('5', '13', '14', 'pan card', 'Identity Proof', 'documents/clients/13/1787634194_ChatGPT Image Aug 18, 2026, 12_05_12 PM.png', 'Pending', NULL, NULL, NULL, '2026-08-25 05:03:15', '2026-08-25 05:03:15');

-- Table structure for `client_services`
DROP TABLE IF EXISTS `client_services`;
CREATE TABLE `client_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `assigned_team` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_services_client_id_index` (`client_id`),
  KEY `client_services_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `client_tbl`
DROP TABLE IF EXISTS `client_tbl`;
CREATE TABLE `client_tbl` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `client_company` varchar(100) NOT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `company_size` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `client_location` varchar(100) NOT NULL,
  `client_email` varchar(50) NOT NULL,
  `primary_contact` varchar(255) DEFAULT NULL,
  `secondary_contact` varchar(255) DEFAULT NULL,
  `client_gst` varchar(30) NOT NULL,
  `client_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `joined_date` datetime DEFAULT current_timestamp(),
  `client_created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `client_updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for `client_tbl`
INSERT INTO `client_tbl` (`client_id`, `entity_id`, `client_name`, `client_company`, `industry`, `company_size`, `website`, `client_location`, `client_email`, `primary_contact`, `secondary_contact`, `client_gst`, `client_status`, `joined_date`, `client_created_date`, `client_updated_date`, `city`, `state`, `country`) VALUES ('6', '1', 'Joshua', 'Best Matrimonial', 'Retail', '1 - 10', '', '--', 'joesva@gmail.com', '7338934701', '', '', 'Active', '2026-08-24 16:20:25', '2024-11-16 10:35:29', '2026-08-24 07:35:22', NULL, NULL, NULL);
INSERT INTO `client_tbl` (`client_id`, `entity_id`, `client_name`, `client_company`, `industry`, `company_size`, `website`, `client_location`, `client_email`, `primary_contact`, `secondary_contact`, `client_gst`, `client_status`, `joined_date`, `client_created_date`, `client_updated_date`, `city`, `state`, `country`) VALUES ('7', '1', 'Senthil Murugan', 'SD Tiles', NULL, NULL, NULL, '', '--', '9597174280', NULL, '', 'Active', '2026-08-24 16:20:25', '2024-11-29 09:15:02', '2024-11-29 22:01:31', NULL, NULL, NULL);
INSERT INTO `client_tbl` (`client_id`, `entity_id`, `client_name`, `client_company`, `industry`, `company_size`, `website`, `client_location`, `client_email`, `primary_contact`, `secondary_contact`, `client_gst`, `client_status`, `joined_date`, `client_created_date`, `client_updated_date`, `city`, `state`, `country`) VALUES ('8', '1', 'Manikandan BNI', 'Gold Plan Mobile App', NULL, NULL, NULL, 'Tirunelveli', 'manikandan@gmail.com', '9094447770', NULL, '', 'Active', '2026-08-24 16:20:25', '2024-12-05 02:06:50', '2024-12-05 14:37:15', NULL, NULL, NULL);
INSERT INTO `client_tbl` (`client_id`, `entity_id`, `client_name`, `client_company`, `industry`, `company_size`, `website`, `client_location`, `client_email`, `primary_contact`, `secondary_contact`, `client_gst`, `client_status`, `joined_date`, `client_created_date`, `client_updated_date`, `city`, `state`, `country`) VALUES ('13', '1', 'dhanush', 'dhanush it park', 'IT Services', '11 - 50', '', '', 'dhanush420490@gmail.com', '9876543210', '', '', 'Active', '2026-08-24 11:02:58', '2026-08-24 11:02:58', '2026-08-25 05:02:47', 'pavoorchatram', 'tamilnadu', 'india');

-- Table structure for `client_users`
DROP TABLE IF EXISTS `client_users`;
CREATE TABLE `client_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_users_email_unique` (`email`),
  KEY `client_users_client_id_index` (`client_id`),
  KEY `client_users_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `client_users`
INSERT INTO `client_users` (`id`, `client_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES ('6', '6', 'Joshua', 'joesva@gmail.com', NULL, '$2y$12$V84avJxPl75dDtkUGQA08uxHX7puPYDIX3hJXQqglxNszoQvdgU/S', 'client', 'Active', NULL, NULL, '2026-08-12 05:17:01', '2026-08-24 07:35:22');
INSERT INTO `client_users` (`id`, `client_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES ('7', '7', 'Senthil Murugan', '--', NULL, '$2y$12$QUnjzJC0En..QcHW43mygO./KucCQiKn0GCTB/gvaw2oIJVRJ814y', 'client', 'active', NULL, NULL, '2026-08-12 05:17:01', '2026-08-12 05:17:01');
INSERT INTO `client_users` (`id`, `client_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES ('8', '8', 'Manikandan BNI', 'manikandan@gmail.com', NULL, '$2y$12$UXGPsHsG5JoltmOEzOXbue1Z7Dr5v2kK8hdXo4SZOtCKve4/WYUj2', 'client', 'active', NULL, NULL, '2026-08-12 05:17:02', '2026-08-12 05:17:02');
INSERT INTO `client_users` (`id`, `client_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES ('14', '13', 'dhanush', 'dhanush420490@gmail.com', NULL, '$2y$12$tW9ipme8vzrKRbIJ/r0yx.Zh8YdnnvJbvBA8.eD7y.4u2f5f0Pzs2', 'Admin', 'Active', NULL, NULL, '2026-08-24 11:02:59', '2026-08-25 05:14:24');

-- Table structure for `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `jobs`
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_08_10_073601_create_client_services_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_08_10_073601_create_client_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_08_10_073601_create_portal_admins_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_08_10_073602_create_client_documents_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_08_10_073602_create_notifications_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_08_10_073602_create_support_tickets_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_08_10_073602_create_ticket_replies_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_08_10_073603_create_activity_logs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_08_10_102800_create_portal_password_reset_tokens_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_08_20_000000_create_otps_table', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_08_24_104956_add_joined_date_to_client_tbl', '4');

-- Table structure for `notifications`
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_client_id_index` (`client_id`),
  KEY `notifications_user_id_index` (`user_id`),
  KEY `notifications_is_read_index` (`is_read`),
  KEY `notifications_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `notifications`
INSERT INTO `notifications` (`id`, `client_id`, `user_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES ('1', '1', NULL, 'Document Uploaded', 'Your document \"gst\" has been successfully uploaded and is pending verification.', '0', '2026-08-19 09:26:35', '2026-08-19 09:26:35');
INSERT INTO `notifications` (`id`, `client_id`, `user_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES ('2', '1', NULL, 'Document Verified', 'Your document \"gst\" has been verified.', '0', '2026-08-19 09:35:07', '2026-08-19 09:35:07');
INSERT INTO `notifications` (`id`, `client_id`, `user_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES ('3', '9', NULL, 'Document Uploaded', 'Your document \"aadhar\" has been successfully uploaded and is pending verification.', '0', '2026-08-24 09:45:25', '2026-08-24 09:45:25');
INSERT INTO `notifications` (`id`, `client_id`, `user_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES ('4', '9', NULL, 'Document Verified', 'Your document \"aadhar\" has been verified.', '0', '2026-08-24 09:59:10', '2026-08-24 09:59:10');
INSERT INTO `notifications` (`id`, `client_id`, `user_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES ('5', '13', NULL, 'Document Uploaded', 'Your document \"pan card\" has been successfully uploaded and is pending verification.', '0', '2026-08-25 05:03:15', '2026-08-25 05:03:15');

-- Table structure for `otps`
DROP TABLE IF EXISTS `otps`;
CREATE TABLE `otps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otps_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `password_reset_tokens`
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `portal_admins`
DROP TABLE IF EXISTS `portal_admins`;
CREATE TABLE `portal_admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portal_admins_email_unique` (`email`),
  KEY `portal_admins_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `portal_admins`
INSERT INTO `portal_admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Admin DK', 'admindk@gmail.com', NULL, '$2y$12$0dLvE5YUbi8M9v4O6XSA5evzIlMoSogKSlJCxcdJwIsIZonuDSS7e', 'Super Admin', 'active', NULL, NULL, '2026-08-11 06:03:50', '2026-08-12 07:17:02');

-- Table structure for `portal_password_reset_tokens`
DROP TABLE IF EXISTS `portal_password_reset_tokens`;
CREATE TABLE `portal_password_reset_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `portal_password_reset_tokens_user_id_index` (`user_id`),
  KEY `portal_password_reset_tokens_email_index` (`email`),
  KEY `portal_password_reset_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `sessions`
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `sessions`
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('2K1aHktYtDQ1Uxv5Dzl7rz0RyEaLqRwYrIaHFIBf', '14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaEZmNlBKbUpIYXZXd2xPT1ExRmJyVmNUUGVaRG9HSllvZWVpak9sbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE2OiJjbGllbnQuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MzoibG9naW5fY2xpZW50XzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTQ7fQ==', '1787806048');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('DAR0UMhPKpJZsgup23EVevdM1XM20JCTWEV2qWIj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.26100.9168', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemp1aVpyaUhSaUttZ2hJZ0hlVDNORU5LVEgwaHRoMHZnNW9HTnNKRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbi9jbGllbnQiO3M6NToicm91dGUiO3M6MTI6ImNsaWVudC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1787634097');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('NHBdTIrX3q9V4ENX2gSZY4yVSr9XHfuq7LBeiqBk', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicFRZbGkwY3Bna3dqNFZXRnludzJheFBMSURXRHd0U0FEdHJpU2JReiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uc2V0dGluZ3MuaW5kZXgiO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', '1787635290');

-- Table structure for `support_tickets`
DROP TABLE IF EXISTS `support_tickets`;
CREATE TABLE `support_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `assigned_staff_id` bigint(20) unsigned DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Open',
  `priority` varchar(255) NOT NULL DEFAULT 'Medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_tickets_client_id_index` (`client_id`),
  KEY `support_tickets_status_index` (`status`),
  KEY `support_tickets_created_by_index` (`created_by`),
  KEY `support_tickets_assigned_staff_id_index` (`assigned_staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `support_tickets`
INSERT INTO `support_tickets` (`id`, `client_id`, `created_by`, `assigned_staff_id`, `subject`, `description`, `status`, `priority`, `created_at`, `updated_at`) VALUES ('3', '13', '14', NULL, 'for update my ui', 'i want improve my projects ui , add more styles', 'Open', 'Medium', '2026-08-25 05:04:14', '2026-08-25 05:04:14');

-- Table structure for `staff_members`
DROP TABLE IF EXISTS `staff_members`;
CREATE TABLE `staff_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('Pending Activation','Active','Inactive') NOT NULL DEFAULT 'Pending Activation',
  `avatar` varchar(255) DEFAULT NULL,
  `created_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_members_email_unique` (`email`),
  KEY `staff_members_status_index` (`status`),
  KEY `staff_members_department_index` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `client_assignments`
DROP TABLE IF EXISTS `client_assignments`;
CREATE TABLE `client_assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `role_in_project` varchar(255) NOT NULL DEFAULT 'Assigned Engineer',
  `assigned_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_assignments_staff_id_client_id_unique` (`staff_id`,`client_id`),
  KEY `client_assignments_staff_id_index` (`staff_id`),
  KEY `client_assignments_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `ticket_replies`
DROP TABLE IF EXISTS `ticket_replies`;
CREATE TABLE `ticket_replies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) unsigned NOT NULL,
  `sender_type` varchar(255) NOT NULL,
  `sender_id` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_replies_sender_type_sender_id_index` (`sender_type`,`sender_id`),
  KEY `ticket_replies_ticket_id_index` (`ticket_id`),
  CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
