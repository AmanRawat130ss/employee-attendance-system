-- Database Schema for Employee Attendance Management System
-- Generated for XAMPP MySQL import

CREATE DATABASE IF NOT EXISTS `employee_attendance_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `employee_attendance_db`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `attendances`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `employee_id` VARCHAR(50) DEFAULT NULL UNIQUE,
  `role` ENUM('admin', 'employee') NOT NULL DEFAULT 'employee',
  `phone` VARCHAR(20) DEFAULT NULL,
  `department` VARCHAR(100) DEFAULT NULL,
  `designation` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `attendances`
-- --------------------------------------------------------

CREATE TABLE `attendances` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `login_time` TIME DEFAULT NULL,
  `logout_time` TIME DEFAULT NULL,
  `total_hours` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('Present', 'Absent', 'Half Day', 'Leave', 'Holiday') NOT NULL DEFAULT 'Present',
  `notes` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_attendance_per_day` (`user_id`, `date`),
  CONSTRAINT `fk_attendances_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping sample data for table `users`
-- Default password for all users is: password123 (hashed with bcrypt)
-- --------------------------------------------------------

INSERT INTO `users` (`id`, `name`, `email`, `password`, `employee_id`, `role`, `phone`, `department`, `designation`, `status`) VALUES
(1, 'System Administrator', 'admin@ems.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EMP000', 'admin', '9876543210', 'Management', 'System Admin', 'active'),
(2, 'Rahul Sharma', 'rahul@ems.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EMP001', 'employee', '9811122233', 'Engineering', 'Full Stack Developer', 'active'),
(3, 'Priya Patel', 'priya@ems.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EMP002', 'employee', '9822233344', 'Design', 'UI/UX Designer', 'active'),
(4, 'Vikram Singh', 'vikram@ems.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EMP003', 'employee', '9833344455', 'Marketing', 'SEO Specialist', 'active'),
(5, 'Sneha Roy', 'sneha@ems.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EMP004', 'employee', '9844455566', 'HR', 'HR Executive', 'active');

-- --------------------------------------------------------
-- Dumping sample data for table `attendances`
-- Using CURRENT_DATE and past dates for instant testing
-- --------------------------------------------------------

INSERT INTO `attendances` (`user_id`, `date`, `login_time`, `logout_time`, `total_hours`, `status`, `notes`) VALUES
-- Rahul: Present today (worked 8.5 hours)
(2, CURRENT_DATE(), '09:30:00', '18:00:00', 8.50, 'Present', 'Regular working day'),
-- Priya: Half Day today (worked 3.5 hours)
(3, CURRENT_DATE(), '10:00:00', '13:30:00', 3.50, 'Half Day', 'Left early for doctor appointment'),
-- Vikram: On Leave today
(4, CURRENT_DATE(), NULL, NULL, 0.00, 'Leave', 'Casual Leave approved'),
-- Sneha: Present today (currently logged in, no logout yet)
(5, CURRENT_DATE(), '09:15:00', NULL, 0.00, 'Present', 'Logged in');
