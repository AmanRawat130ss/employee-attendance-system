-- ========================================================================
-- Insert Employees Query for phpMyAdmin
-- Default password for all accounts: password123
-- ========================================================================

INSERT INTO `users` (
    `name`, 
    `email`, 
    `password`, 
    `employee_id`, 
    `role`, 
    `phone`, 
    `department`, 
    `designation`, 
    `status`, 
    `created_at`, 
    `updated_at`
) VALUES
('Aman', 'aman@ems.com', '$2y$10$4DgLcUc3dXcT1IIPJzu8duBE6I1Jig58snW.uvAwYW43dCAGxjzXO', 'EMP001', 'employee', '9811122233', 'Engineering', 'Full Stack Developer', 'active', NOW(), NOW()),
('Chandler Bing', 'chandler@ems.com', '$2y$10$4DgLcUc3dXcT1IIPJzu8duBE6I1Jig58snW.uvAwYW43dCAGxjzXO', 'EMP002', 'employee', '9822233344', 'Statistical Analysis', 'IT & Data Lead', 'active', NOW(), NOW()),
('Joey Tribbiani', 'joey@ems.com', '$2y$10$4DgLcUc3dXcT1IIPJzu8duBE6I1Jig58snW.uvAwYW43dCAGxjzXO', 'EMP003', 'employee', '9833344455', 'Marketing', 'Brand Specialist', 'active', NOW(), NOW()),
('Ross Geller', 'ross@ems.com', '$2y$10$4DgLcUc3dXcT1IIPJzu8duBE6I1Jig58snW.uvAwYW43dCAGxjzXO', 'EMP004', 'employee', '9844455566', 'Research', 'Senior Paleontologist', 'active', NOW(), NOW()),
('Monica Geller', 'monica@ems.com', '$2y$10$4DgLcUc3dXcT1IIPJzu8duBE6I1Jig58snW.uvAwYW43dCAGxjzXO', 'EMP005', 'employee', '9855566677', 'Operations', 'Quality & Food Lead', 'active', NOW(), NOW()),
('Phoebe Buffay', 'phoebe@ems.com', '$2y$10$4DgLcUc3dXcT1IIPJzu8duBE6I1Jig58snW.uvAwYW43dCAGxjzXO', 'EMP006', 'employee', '9866677788', 'Human Resources', 'Wellness Specialist', 'active', NOW(), NOW());
