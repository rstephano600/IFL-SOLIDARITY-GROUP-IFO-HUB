-- 20/04/2026

ALTER TABLE users
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER remember_token;

UPDATE users
SET User_id = created_by;

UPDATE users
SET Status = status;

ALTER TABLE users
DROP COLUMN status;

ALTER TABLE users
ADD CONSTRAINT users_user_fk
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE users
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id;

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    Status VARCHAR(20) DEFAULT 'Active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE permission_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    User_id BIGINT UNSIGNED,
    permission_id BIGINT UNSIGNED,
    Creater_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(20) DEFAULT 'Active',
    duration ENUM('Permanent', 'Temporary') NOT NULL DEFAULT 'Permanent',
    start_date DATE NULL,
    end_date DATE NULL,

    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (Creater_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('Company Admin', 'is-company-admin','company admin can manage everything of the company', 'Active', NOW(), NOW()),
('Company Director', 'is-company-director','Given to Director of the  Company', 'Active', NOW(), NOW()),
('Company CEO', 'is-company-ceo','Given to CEO of the  Company', 'Active', NOW(), NOW()),
('Company Shareholder', 'is-company-shareholder','Given to shareholders of the  Company', 'Active', NOW(), NOW()),
('Company Manager', 'is-company-manager','Given to manager of the  Company', 'Active', NOW(), NOW()),
('Company Human Resources', 'is-company-hr','Given to Human Resources of the  Company', 'Active', NOW(), NOW()),
('Company Accountant', 'is-company-accountant','Given to accountant of the  Company', 'Active', NOW(), NOW()),
('Company Marketingofficer', 'is-company-marketingofficer','Given to marketingofficer of the  Company', 'Active', NOW(), NOW()),
('Company Secretary', 'is-company-secretary','Given to secretary of the  Company', 'Active', NOW(), NOW()),
('Company Loanofficer', 'is-company-loanofficer','Given to loanofficer of the  Company', 'Active', NOW(), NOW()),
('Company client', 'is-company-client','Given to client of the  Company', 'Active', NOW(), NOW()),
('Company user', 'is-company-user','Given to user of the  Company', 'Active', NOW(), NOW());

ALTER TABLE permission_users
ADD COLUMN created_at TIMESTAMP NULL,
ADD COLUMN updated_at TIMESTAMP NULL;

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Configuration Side', 'view-confirguration-side','View Configuration Side', 'Active', NOW(), NOW()),
('View Working Side', 'view-working-side','View Working Side', 'Active', NOW(), NOW()),
('View Reporting Side', 'view-reporting-side','View Reporting Side', 'Active', NOW(), NOW());

-- 04/05/2026
INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Country', 'view-country','View Country', 'Active', NOW(), NOW()),
('Create Country', 'create-country','Create Country', 'Active', NOW(), NOW()),
('Create Read Update Delete Country', 'crud-country','Crud Country', 'Active', NOW(), NOW()),
('View Currency', 'view-currency','View Currency', 'Active', NOW(), NOW()),
('Create Currency', 'create-currency','create Currency', 'Active', NOW(), NOW()),
('Create Read Update Delete Currency', 'crud-currency','Create Read Update Delete Currency Currency', 'Active', NOW(), NOW()),
('View Today Currency', 'view-today-currency','View Today Currency', 'Active', NOW(), NOW()),
('Create Today Currency', 'create-today-currency','create Today Currency', 'Active', NOW(), NOW()),
('Create Read Update Delete Today Currency', 'crud-today-currency','Create Read Update Delete Today Currency Currency', 'Active', NOW(), NOW()),
('View Company Branches', 'view-company-branches','View Company Branches', 'Active', NOW(), NOW()),
('create Company Branches', 'create-company-branches','Create Company Branches', 'Active', NOW(), NOW()),
('Create Read Update Delete Company Branches', 'crud-company-branches','Create Read Update Delete Company Branches', 'Active', NOW(), NOW()),
('View Accounting Codes', 'view-accounting-codes','View The Accounting Codes', 'Active', NOW(), NOW()),
('Create Accounting Codes', 'create-accounting-codes','Create The Accounting Codes', 'Active', NOW(), NOW()),
('Create Read Update Delete Accounting Codes', 'crud-accounting-codes','Create Read Update Delete The Accounting Codes', 'Active', NOW(), NOW());

CREATE TABLE account_countries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CountryCode VARCHAR(10),
    CountryName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE account_businesses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Country_id BIGINT UNSIGNED NOT NULL,
    BusinessCode VARCHAR(50),
    BusinessName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (Country_id) REFERENCES account_countries(id) ON DELETE CASCADE
);

CREATE TABLE account_roots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    AccountCode VARCHAR(50) UNIQUE,
    AccountName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE account_first_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    AccountRoot_id BIGINT UNSIGNED NOT NULL,
    FirstAccountCode VARCHAR(50),
    FirstAccountName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (AccountRoot_id) REFERENCES account_roots(id) ON DELETE CASCADE
);

CREATE TABLE account_second_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FirstRoot_id BIGINT UNSIGNED NOT NULL,
    SecondAccountCode VARCHAR(50),
    SecondAccountName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (FirstRoot_id) REFERENCES account_first_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_third_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    SecondRoot_id BIGINT UNSIGNED NOT NULL,
    ThirdAccountCode VARCHAR(50),
    ThirdAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (SecondRoot_id) REFERENCES account_second_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_fourth_center_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ThirdRoot_id BIGINT UNSIGNED NOT NULL,
    FourthAccountCode VARCHAR(50),
    FourthAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ThirdRoot_id) REFERENCES account_third_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_fifth_group_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FourthRoot_id BIGINT UNSIGNED NOT NULL,
    FifthAccountCode VARCHAR(50),
    FifthAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (FourthRoot_id) REFERENCES account_fourth_center_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_sixth_member_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FifthRoot_id BIGINT UNSIGNED NOT NULL,
    SixthAccountCode VARCHAR(50),
    SixthAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (FifthRoot_id) REFERENCES account_fifth_group_branches(id) ON DELETE CASCADE
);

ALTER TABLE users
ADD COLUMN Roles VARCHAR(20) DEFAULT 'User' AFTER role;

UPDATE users
SET Roles = role;

ALTER TABLE users
DROP COLUMN role;

ALTER TABLE users
ADD COLUMN Role VARCHAR(20) DEFAULT 'User' AFTER Roles;

UPDATE users
SET Role = Roles;

ALTER TABLE users
DROP COLUMN Roles;

ALTER TABLE users
ADD COLUMN FirstName VARCHAR(50) NULL AFTER name,
ADD COLUMN MiddleName VARCHAR(50) NULL AFTER FirstName,
ADD COLUMN LastName VARCHAR(50) NULL AFTER MiddleName;

ALTER TABLE users
DROP COLUMN created_by;

ALTER TABLE users
DROP COLUMN updated_by;

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Accounting Menu', 'view-accounting-menu','View Accounting Menu', 'Active', NOW(), NOW());

-- tarehe 16/05/2026

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Employee Menu', 'view-employee-menu','View Employee Menu', 'Active', NOW(), NOW()),
('Register Employees', 'register-employees','Register Employees', 'Active', NOW(), NOW()),
('Update, Delete Employees', 'crud-employees','Update, Delete Employees', 'Active', NOW(), NOW()),
('View Permission Access Menu', 'view-permission-access-menu','View Permission Access Menu', 'Active', NOW(), NOW()),
('Assign Permission Access', 'assign-permission-access','Assign Permission Access', 'Active', NOW(), NOW()),
('Assign As Admin Permission Access', 'assign-AsAdmin-permission-access','Assign As Admin Permission Access', 'Active', NOW(), NOW()),
('Register Permission Access', 'register-permission-access','Register Permission Access', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View System Users Menu', 'view-system-users-menu','View System Users Menu', 'Active', NOW(), NOW()),
('View System Users', 'view-system-users','View System Users', 'Active', NOW(), NOW()),
('Register System Users Menu', 'register-system-users-menu','Register System Users Menu', 'Active', NOW(), NOW()),
('Update, Delete System Users Menu', 'crud-system-users-menu','Update, Delete System Users Menu', 'Active', NOW(), NOW());


ALTER TABLE employees
ADD COLUMN Employee_id BIGINT NOT NULL AFTER id;

ALTER TABLE employees
DROP COLUMN Emp_id;

ALTER TABLE employees
DROP COLUMN first_name,
DROP COLUMN middle_name,
DROP COLUMN last_name,
DROP COLUMN gender,
DROP COLUMN date_of_birth;

ALTER TABLE users
ADD COLUMN gender VARCHAR(20) NULL AFTER Role,
ADD COLUMN Dob VARCHAR(20) NULL AFTER gender;

ALTER TABLE users
DROP COLUMN Emp_id;

ALTER TABLE employees
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER other_information,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE employees
ADD COLUMN EmployeeID BIGINT NOT NULL AFTER id;

ALTER TABLE employees
MODIFY COLUMN EmployeeID VARCHAR(100) NOT NULL;

ALTER TABLE employees
MODIFY COLUMN Employee_id BIGINT UNSIGNED NOT NULL;

UPDATE employees
SET Employee_id = User_id;

ALTER TABLE employees
ADD CONSTRAINT fk_employees_user
FOREIGN KEY (Employee_id)
REFERENCES users(id)
ON DELETE CASCADE;


ALTER TABLE referee
ADD COLUMN User_id BIGINT UNSIGNED NOT NULL AFTER other_informations,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE referee
ADD CONSTRAINT fk_referee_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

--20/07/2026
ALTER TABLE employees
ADD COLUMN basic_salary BIGINT NOT NULL AFTER other_information;

ALTER TABLE referee
ADD COLUMN occupation VARCHAR(50) DEFAULT NULL AFTER other_informations;

ALTER TABLE nist_of_kin
ADD COLUMN relationship VARCHAR(50) DEFAULT NULL AFTER other_informations;

ALTER TABLE nist_of_kin
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER other_informations,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE nist_of_kin
ADD CONSTRAINT fk_nist_of_kin_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

-- tar 23/05/2026
ALTER TABLE employees
MODIFY EmployeeID VARCHAR(255) DEFAULT NULL;

ALTER TABLE group_centers
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER established_date,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE group_centers
ADD CONSTRAINT fk_group_centers_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

ALTER TABLE groups
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER registration_date,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE groups
ADD CONSTRAINT fk_groups_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

ALTER TABLE group_members
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER role_in_group,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE group_members
ADD CONSTRAINT fk_group_members_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

UPDATE group_members
SET User_id = created_by;
UPDATE groups
SET User_id = created_by;


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('Group Groups Centers Menu', 'view-group-centers-menu','Group Groups Centers Menu', 'Active', NOW(), NOW()),
('View Group Centers', 'view-group-centers','View Group Centers', 'Active', NOW(), NOW()),
('Update, Delete Group Centers', 'crud-group-centers','Update or Delete Group Centers', 'Active', NOW(), NOW()),
('Company Loan Groups Menu', 'view-loan-groups-menu','Company Loan Groups Menu', 'Active', NOW(), NOW()),
('View Company Loan Groups', 'view-loan-groups','View Company Loan Groups', 'Active', NOW(), NOW()),
('Update, Delete Company Loan Groups', 'crud-loan-groups','Update or Delete Company Loan Groups', 'Active', NOW(), NOW()),
('Loan beneficiary Menu', 'view-loan-beneficiary-menu','Loan beneficiary Menu', 'Active', NOW(), NOW()),
('View Loan beneficiary', 'view-loan-beneficiary','View Loan beneficiary', 'Active', NOW(), NOW()),
('Update, Delete Loan beneficiary', 'crud-loan-beneficiary','Update or Delete Loan beneficiary', 'Active', NOW(), NOW());


ALTER TABLE clients
DROP COLUMN status,
DROP COLUMN gender,
DROP COLUMN marital_status,
DROP COLUMN spouse_name,
DROP COLUMN other_name,
DROP COLUMN date_of_birth,
DROP COLUMN street_leader,
DROP COLUMN profile_picture,
DROP COLUMN sign_image;

ALTER TABLE clients
ADD COLUMN marital_status VARCHAR(50) DEFAULT NULL AFTER credit_officer_id,
ADD COLUMN spouse_name VARCHAR(50) DEFAULT NULL AFTER marital_status,
ADD COLUMN other_name VARCHAR(50) DEFAULT NULL AFTER spouse_name,
ADD COLUMN street_leader VARCHAR(50) DEFAULT NULL AFTER other_name,
ADD COLUMN profile_picture VARCHAR(50) DEFAULT NULL AFTER street_leader,
ADD COLUMN sign_image VARCHAR(50) DEFAULT NULL AFTER profile_picture,
ADD COLUMN country_id BIGINT UNSIGNED NULL AFTER country,
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER kyc_completed_at,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE clients
ADD CONSTRAINT fk_clients_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

ALTER TABLE clients
MODIFY credit_officer_id BIGINT UNSIGNED NULL;

ALTER TABLE clients
MODIFY group_id BIGINT UNSIGNED NULL;

ALTER TABLE clients
MODIFY group_center_id BIGINT UNSIGNED NULL;

ALTER TABLE clients
ADD CONSTRAINT fk_clients_employee
FOREIGN KEY (credit_officer_id)
REFERENCES employees(id)
ON DELETE CASCADE;

ALTER TABLE clients
ADD CONSTRAINT fk_group_center_user
FOREIGN KEY (group_center_id)
REFERENCES group_centers(id)
ON DELETE CASCADE;

ALTER TABLE clients
ADD CONSTRAINT fk_clients_group_id
FOREIGN KEY (group_id)
REFERENCES groups(id)
ON DELETE CASCADE;

ALTER TABLE clients
DROP COLUMN first_name,
DROP COLUMN last_name,
DROP COLUMN middle_name,
DROP COLUMN email,
DROP COLUMN phone;

ALTER TABLE clients
ADD COLUMN client_code VARCHAR(50) UNIQUE AFTER group_id;


ALTER TABLE clients
ADD COLUMN client_id BIGINT UNSIGNED NULL AFTER id;


ALTER TABLE clients
ADD CONSTRAINT fk_clients_clients_user
FOREIGN KEY (client_id)
REFERENCES users(id)
ON DELETE CASCADE;


ALTER TABLE `clients` 
MODIFY COLUMN `profile_picture` TEXT NULL,
MODIFY COLUMN `sign_image` TEXT NULL;

ALTER TABLE clients
ADD COLUMN updated_by BIGINT UNSIGNED NULL AFTER kyc_completed_at;

ALTER TABLE clients
ADD CONSTRAINT fk_clients_updated_user
FOREIGN KEY (updated_by)
REFERENCES users(id)
ON DELETE CASCADE;

ALTER TABLE users
ADD COLUMN updated_by BIGINT UNSIGNED NULL AFTER remember_token;

ALTER TABLE users
ADD CONSTRAINT fk_users_updated_user
FOREIGN KEY (updated_by)
REFERENCES users(id)
ON DELETE CASCADE;

-- 28/05/2026
ALTER TABLE loan_categories
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER is_new_client,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE loan_categories
ADD CONSTRAINT fk_loan_categories_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

UPDATE loan_categories
SET User_id = created_by;

ALTER TABLE loans
ADD COLUMN ApprovalStatus VARCHAR(50) DEFAULT 'Active' AFTER currency;
UPDATE loans
SET ApprovalStatus = status;
ALTER TABLE loans
DROP COLUMN status;

ALTER TABLE loans
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER is_new_client,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE loans
ADD CONSTRAINT fk_loans_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

UPDATE loans
SET User_id = created_by;

ALTER TABLE loan_payments
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER remarks,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE loan_payments
ADD CONSTRAINT fk_loan_payments_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('view loan categories menu', 'view-loan-categories-menu','view loan categories menu', 'Active', NOW(), NOW()),
('view loan categories', 'view-loan-categories','view loan categories', 'Active', NOW(), NOW()),
('register loan categories', 'register-loan-categories','register loan categories', 'Active', NOW(), NOW()),
('Update or Delete loan categories', 'crud-loan-categories','Update or Delete loan categories', 'Active', NOW(), NOW()),
('view loan  menu', 'view-loan-menu','view loan  menu', 'Active', NOW(), NOW()),
('view loan ', 'view-loan','view loan ', 'Active', NOW(), NOW()),
('register loan ', 'register-loan','register loan ', 'Active', NOW(), NOW()),
('Update or Delete loan ', 'crud-loan-','Update or Delete loan ', 'Active', NOW(), NOW());


-- 29/05/2026

CREATE TABLE loan_penalties_categories (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name TEXT NULL,
    conditions TEXT NULL,
    descriptions TEXT NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_penality_user 
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE

);

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('view loan penalty categories menu', 'view-loan-penalty-categories-menu', 'view loan penalty categories menu', 'Active', NOW(), NOW()),
('view loan penalty categories', 'view-loan-penalty-categories', 'view loan penalty categories', 'Active', NOW(), NOW()),
('register loan penalty categories', 'register-loan-penalty-categories', 'register loan penalty categories', 'Active', NOW(), NOW()),
('Update or Delete loan penalty categories', 'crud-loan-penalty-categories', 'Update or Delete loan penalty categories', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('view loan repayments menu', 'view-loan-repayments-menu', 'view loan repayments menu', 'Active', NOW(), NOW()),
('view loan repayments', 'view-loan-repayments', 'view loan repayments', 'Active', NOW(), NOW()),
('register loan repayments', 'register-loan-repayments', 'register loan repayments', 'Active', NOW(), NOW()),
('Update or Delete loan repayments', 'crud-loan-repayments', 'Update or Delete loan repayments', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('view loan guarantors menu', 'view-loan-guarantors-menu', 'view loan guarantors menu', 'Active', NOW(), NOW()),
('view loan guarantors', 'view-loan-guarantors', 'view loan guarantors', 'Active', NOW(), NOW()),
('register loan guarantors', 'register-loan-guarantors', 'register loan guarantors', 'Active', NOW(), NOW()),
('Update or Delete loan guarantors', 'crud-loan-guarantors', 'Update or Delete loan guarantors', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('view loan penalties menu', 'view-loan-penalties-menu', 'view loan penalties menu', 'Active', NOW(), NOW()),
('view loan penalties', 'view-loan-penalties', 'view loan penalties', 'Active', NOW(), NOW()),
('register loan penalties', 'register-loan-penalties', 'register loan penalties', 'Active', NOW(), NOW()),
('Update or Delete loan penalties', 'crud-loan-penalties', 'Update or Delete loan penalties', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('approve loans', 'approve-loans', 'approve loans', 'Active', NOW(), NOW()),
('reject loans', 'reject-loans', 'reject loans', 'Active', NOW(), NOW()),
('disburse loans', 'disburse-loans', 'disburse loans', 'Active', NOW(), NOW()),
('close loans', 'close-loans', 'close loans', 'Active', NOW(), NOW()),
('view loan reports', 'view-loan-reports', 'view loan reports', 'Active', NOW(), NOW());



DROP TABLE IF EXISTS loan_repayments;
CREATE TABLE loan_repayments (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    loan_id BIGINT UNSIGNED NOT NULL,
    client_id BIGINT UNSIGNED NOT NULL,

    payment_date DATE NOT NULL,

    amount_paid DECIMAL(18,2) DEFAULT 0,

    principal_paid DECIMAL(18,2) DEFAULT 0,
    interest_paid DECIMAL(18,2) DEFAULT 0,

    penalty_paid DECIMAL(18,2) DEFAULT 0,

    payment_method VARCHAR(50) NULL,
    reference_number VARCHAR(100) NULL,

    remarks TEXT NULL,

    received_by BIGINT UNSIGNED NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_loan_repayments_user 
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,

    CONSTRAINT fk_loan_repayments_received_by 
    FOREIGN KEY (received_by) REFERENCES users(id) ON DELETE CASCADE,

    CONSTRAINT fk_loan_repayments_loan
    FOREIGN KEY (loan_id) REFERENCES loans(id) ON DELETE CASCADE,

    CONSTRAINT fk_loan_repayments_client
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE loan_repayments
DROP FOREIGN KEY fk_loan_repayments_client;

ALTER TABLE loan_repayments
ADD CONSTRAINT fk_loan_repayments_client
FOREIGN KEY (client_id)
REFERENCES clients(id)
ON DELETE CASCADE;


CREATE TABLE guarantors (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    guarantor_number VARCHAR(100) NULL,

    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,

    gender VARCHAR(20) NULL,

    phone_number VARCHAR(50) NULL,
    alternative_phone VARCHAR(50) NULL,

    nida_number VARCHAR(100) NULL,

    email VARCHAR(255) NULL,

    occupation VARCHAR(255) NULL,

    physical_address TEXT NULL,

    relationship_with_client VARCHAR(100) NULL,

    remarks TEXT NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_guarantors_user
    FOREIGN KEY (User_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

);

CREATE TABLE loan_guarantors (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    loan_id BIGINT UNSIGNED NOT NULL,

    client_id BIGINT UNSIGNED NOT NULL,

    guarantor_id BIGINT UNSIGNED NOT NULL,

    guarantee_amount DECIMAL(18,2) DEFAULT 0,

    relationship_type VARCHAR(100) NULL,

    remarks TEXT NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_loan_guarantors
    FOREIGN KEY (guarantor_id)
    REFERENCES client_guarantors(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_loan_loan_guarantors
    FOREIGN KEY (loan_id)
    REFERENCES loans(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_client_loan_guarantors
    FOREIGN KEY (client_id)
    REFERENCES clients(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_loan_guarantors_user
    FOREIGN KEY (User_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);


CREATE TABLE loan_penalties (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    loan_id BIGINT UNSIGNED NOT NULL,

    client_id BIGINT UNSIGNED NOT NULL,

    penalty_id BIGINT UNSIGNED NULL,

    penalty_date DATE NOT NULL,

    overdue_days INT DEFAULT 0,

    penalty_rate DECIMAL(10,2) DEFAULT 0,

    penalty_amount DECIMAL(18,2) DEFAULT 0,

    payment_status VARCHAR(50) DEFAULT 'NOT PAID',

    paid_at DATETIME NULL,

    remarks TEXT NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_penalty_date (penalty_date),

    CONSTRAINT fk_loan_penalties_loans
    FOREIGN KEY (loan_id)
    REFERENCES loans(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_loan_penalties_client
    FOREIGN KEY (client_id)
    REFERENCES clients(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_loan_penalties_penalty
    FOREIGN KEY (penalty_id)
    REFERENCES loan_penalties_categories(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_loan_penalties_user
    FOREIGN KEY (User_id)
    REFERENCES users(id)
    ON DELETE CASCADE

);

ALTER TABLE loans
ADD COLUMN RefundStatus VARCHAR(50) DEFAULT 'Not Refunded' AFTER ApprovalStatus,
ADD COLUMN CloseStatus VARCHAR(50) DEFAULT 'Not Closed' AFTER RefundStatus;

ALTER TABLE loans
ADD COLUMN RejectReasons VARCHAR(250) NULL AFTER CloseStatus;

CREATE TABLE loan_repayment_fees (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    loan_id BIGINT UNSIGNED NOT NULL,

    client_id BIGINT UNSIGNED NOT NULL,

    payment_date DATE NOT NULL,
    membership_fee_paid DECIMAL(18,2) DEFAULT 0,
    officer_visit_fee_paid DECIMAL(18,2) DEFAULT 0,
    insurance_fee_paid DECIMAL(18,2) DEFAULT 0,
    preclosure_fee_paid DECIMAL(18,2) DEFAULT 0,
    penalty_fee_paid DECIMAL(18,2) DEFAULT 0,
    other_fee_paid DECIMAL(18,2) DEFAULT 0,
    remarks TEXT NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_payment_date (payment_date),

    CONSTRAINT fk_loan_repayment_fees_loans
    FOREIGN KEY (loan_id)
    REFERENCES loans(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_loan_repayment_fees_clients
    FOREIGN KEY (client_id)
    REFERENCES clients(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_loan_repayment_fees_user
    FOREIGN KEY (User_id)
    REFERENCES users(id)
    ON DELETE CASCADE

);


ALTER TABLE loan_repayment_fees
ADD COLUMN reference_number VARCHAR(50) NULL AFTER payment_date,
ADD COLUMN received_by BIGINT UNSIGNED NULL AFTER remarks;

ALTER TABLE loan_repayment_fees
ADD CONSTRAINT fk_loan_repayment_fees_receiver
FOREIGN KEY (received_by)
REFERENCES users(id)
ON DELETE CASCADE;

ALTER TABLE loan_repayment_fees
ADD COLUMN payment_method VARCHAR(50) NULL AFTER other_fee_paid;

ALTER TABLE loans
ADD COLUMN application_date date NULL AFTER loan_number;

ALTER TABLE loans
ADD COLUMN LoanStatus VARCHAR(50) NULL AFTER RepaymentStatus;

-- 24/06/2026
INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('view loan Doubling menu', 'view-loan-doubling-menu', 'view loan Doubling menu', 'Active', NOW(), NOW()),
('view loan doubling', 'view-loan-doubling', 'view loan doubling', 'Active', NOW(), NOW()),
('register loan doubling', 'register-loan-doubling', 'register loan doubling', 'Active', NOW(), NOW()),
('Update or Delete loan doubling', 'crud-loan-doubling', 'Update or Delete loan doubling', 'Active', NOW(), NOW()),
('Approve loan Doubling menu', 'approve-loan-doubling-menu', 'aprove loan Doubling menu', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('view loan Refuning menu', 'view-loan-refunding-menu', 'view loan refunding menu', 'Active', NOW(), NOW()),
('view loan refunding', 'view-loan-refunding', 'view loan refunding', 'Active', NOW(), NOW()),
('register loan refunding', 'register-loan-refunding', 'register loan refunding', 'Active', NOW(), NOW()),
('Update or Delete loan refunding', 'crud-loan-refunding', 'Update or Delete loan refunding', 'Active', NOW(), NOW()),
('Approve loan refunding menu', 'approve-loan-refunding-menu', 'aprove loan refunding menu', 'Active', NOW(), NOW());


ALTER TABLE loans
ADD COLUMN ApprovalRemarks VARCHAR(50) NULL AFTER currency;


--28/06/2026
CREATE TABLE loan_topups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Loan References
    old_loan_id BIGINT UNSIGNED NOT NULL,
    new_loan_id BIGINT UNSIGNED DEFAULT NULL,

    client_id BIGINT UNSIGNED NOT NULL,
    group_center_id BIGINT UNSIGNED DEFAULT NULL,
    group_id BIGINT UNSIGNED DEFAULT NULL,

    -- Loan Values
    requested_amount DECIMAL(18,2) DEFAULT 0.00,
    approved_amount DECIMAL(18,2) DEFAULT 0.00,
    amount_disbursed DECIMAL(18,2) DEFAULT 0.00,

    -- Outstanding Amounts from Old Loan
    outstanding_principal DECIMAL(18,2) DEFAULT 0.00,
    outstanding_interest DECIMAL(18,2) DEFAULT 0.00,
    outstanding_penalty DECIMAL(18,2) DEFAULT 0.00,
    outstanding_other_fee DECIMAL(18,2) DEFAULT 0.00,

    total_outstanding DECIMAL(18,2) DEFAULT 0.00,

    -- Office Charges
    topup_fee DECIMAL(18,2) DEFAULT 5000.00,

    total_deductions DECIMAL(18,2) DEFAULT 0.00,

    net_disbursed DECIMAL(18,2) DEFAULT 0.00,

    remaining_installments INT DEFAULT 0,

    topup_reason TEXT NULL,
    remarks TEXT NULL,

    topup_date DATE NULL,

    ApprovalStatus VARCHAR(30) DEFAULT 'Pending',
    approved_by BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    Status VARCHAR(30) DEFAULT 'Active',
    AuditingStatus VARCHAR(30) DEFAULT 'Pending',
    ReportStatus VARCHAR(30) DEFAULT 'Show',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_topup_oldloan
        FOREIGN KEY (old_loan_id)
        REFERENCES loans(id),

    CONSTRAINT fk_topup_newloan
        FOREIGN KEY (new_loan_id)
        REFERENCES loans(id),

    CONSTRAINT fk_topup_client
        FOREIGN KEY (client_id)
        REFERENCES clients(id),

    CONSTRAINT fk_topup_group
        FOREIGN KEY (group_id)
        REFERENCES groups(id),

    CONSTRAINT fk_topup_center
        FOREIGN KEY (group_center_id)
        REFERENCES group_centers(id),

    CONSTRAINT fk_topup_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
);



CREATE TABLE loan_refunds (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Loan References
    loan_id BIGINT UNSIGNED NOT NULL,
    client_id BIGINT UNSIGNED NOT NULL,
    group_center_id BIGINT UNSIGNED NULL,
    group_id BIGINT UNSIGNED NULL,

    -- Refund Information
    refund_number VARCHAR(100) NULL,
    refund_date DATE NOT NULL,

    requested_refund DECIMAL(18,2) DEFAULT 0.00,
    approved_refund DECIMAL(18,2) DEFAULT 0.00,
    refunded_amount DECIMAL(18,2) DEFAULT 0.00,

    -- Refundable Components
    membership_fee_refund DECIMAL(18,2) DEFAULT 0.00,
    insurance_fee_refund DECIMAL(18,2) DEFAULT 0.00,
    officer_visit_fee_refund DECIMAL(18,2) DEFAULT 0.00,
    other_fee_refund DECIMAL(18,2) DEFAULT 0.00,
    penalty_fee_refund DECIMAL(18,2) DEFAULT 0.00,
    preclosure_fee_refund DECIMAL(18,2) DEFAULT 0.00,

    total_refund DECIMAL(18,2) DEFAULT 0.00,

    refund_reason TEXT NULL,
    remarks TEXT NULL,

    ApprovalStatus VARCHAR(50) DEFAULT 'Pending',

    approved_by BIGINT UNSIGNED NULL,
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_loan_refunds_loan
        FOREIGN KEY (loan_id) REFERENCES loans(id),

    CONSTRAINT fk_loan_refunds_client
        FOREIGN KEY (client_id) REFERENCES clients(id),

    CONSTRAINT fk_loan_refunds_group
        FOREIGN KEY (group_id) REFERENCES groups(id),

    CONSTRAINT fk_loan_refunds_group_center
        FOREIGN KEY (group_center_id) REFERENCES group_centers(id),

    CONSTRAINT fk_loan_refunds_user
        FOREIGN KEY (User_id) REFERENCES users(id),

    CONSTRAINT fk_loan_refunds_created_by
        FOREIGN KEY (created_by) REFERENCES users(id),

    CONSTRAINT fk_loan_refunds_updated_by
        FOREIGN KEY (updated_by) REFERENCES users(id),

    CONSTRAINT fk_loan_refunds_approved_by
        FOREIGN KEY (approved_by) REFERENCES users(id)
);


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Expense Menu', 'view-expense-menu', 'Access Expense Management Module', 'Active', NOW(), NOW()),
('View Expense Category', 'view-expense-category', 'View Expense Categories', 'Active', NOW(), NOW()),
('Register Expense Category', 'register-expense-category', 'Register Expense Categories', 'Active', NOW(), NOW()),
('Update or Delete Expense Category', 'crud-expense-category', 'Update or Delete Expense Categories', 'Active', NOW(), NOW()),
('Register Expense', 'register-expense', 'Register Company Expense', 'Active', NOW(), NOW()),
('Update or Delete Expense', 'crud-expense', 'Update or Delete Company Expense', 'Active', NOW(), NOW()),
('Approve Expense', 'approve-expense', 'Approve Company Expense', 'Active', NOW(), NOW()),
('Reject Expense', 'reject-expense', 'Reject Company Expense', 'Active', NOW(), NOW()),
('Pay Expense', 'pay-expense', 'Pay Approved Expense', 'Active', NOW(), NOW()),
('Print Expense Reports', 'print-expense-report', 'Print Expense Reports', 'Active', NOW(), NOW()),
('Export Expense Reports', 'export-expense-report', 'Export Expense Reports', 'Active', NOW(), NOW()),
('View Expense Category Reports', 'view-expense-category-report', 'View Expense Category Reports', 'Active', NOW(), NOW());

ALTER TABLE expenses
ADD COLUMN expense_title VARCHAR(50) NULL AFTER expense_number;

ALTER TABLE expenses
ADD COLUMN approved_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER approved_by,
ADD COLUMN PaymentStatus VARCHAR(50) DEFAULT 'Pending' AFTER approved_at,
ADD COLUMN paid_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER PaymentStatus,
ADD COLUMN paid_by BIGINT UNSIGNED NULL AFTER paid_at;

ALTER TABLE expenses
ADD CONSTRAINT fk_expenses_payer
FOREIGN KEY (paid_by)
REFERENCES users(id)
ON DELETE CASCADE;

ALTER TABLE `expenses` 
ADD COLUMN `amount_paid` DECIMAL(15, 2) NOT NULL DEFAULT 0.00 
AFTER `total_amount`;

CREATE TABLE expense_payments (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    expense_id BIGINT UNSIGNED NOT NULL,

    payment_number VARCHAR(100) NULL,

    payment_date DATE NOT NULL,

    amount_paid DECIMAL(18,2) DEFAULT 0.00,

    payment_method VARCHAR(100) NULL,

    reference_number VARCHAR(255) NULL,

    descriptions TEXT NULL,

    paid_by BIGINT UNSIGNED NULL,

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(30) DEFAULT 'Active',

    AuditingStatus VARCHAR(30) DEFAULT 'Pending',

    ReportStatus VARCHAR(30) DEFAULT 'Show',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_expense_payment_expense
        FOREIGN KEY (expense_id)
        REFERENCES expenses(id),

    CONSTRAINT fk_expense_payment_user
        FOREIGN KEY (User_id)
        REFERENCES users(id),

    CONSTRAINT fk_expense_payment_paidby
        FOREIGN KEY (paid_by)
        REFERENCES users(id)

);


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Expense Category Menu', 'view-expense-category-menu', 'View Expense Category Menu', 'Active', NOW(), NOW());



INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Expense Payment Menu', 'view-expense-payment-menu', 'View Expense Payment Menu', 'Active', NOW(), NOW()),
('View Expense Payments', 'view-expense-payments', 'View Expense Payments', 'Active', NOW(), NOW()),
('Register Expense Payment', 'register-expense-payment', 'Register Expense Payment', 'Active', NOW(), NOW()),
('Update or Delete Expense Payment', 'crud-expense-payment', 'Update or Delete Expense Payment', 'Active', NOW(), NOW()),
('View Unpaid Expenses', 'view-unpaid-expenses', 'View Unpaid Expenses', 'Active', NOW(), NOW()),
('View Expense Payment History', 'view-expense-payment-history', 'View Expense Payment History', 'Active', NOW(), NOW());

ALTER TABLE employees
ADD COLUMN weekly_allowance_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00
AFTER basic_salary;


CREATE TABLE salary_paids (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    Employee_id BIGINT UNSIGNED NOT NULL,
    User_id BIGINT UNSIGNED NOT NULL,

    AmountPaid DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    ActualGross DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    NetPay DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    Allowance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    Overtime DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    Advance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    OvtmAdvn DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    Heslb DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    Absent DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    Bcabd DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    EmpNssf DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    NssfPay DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    Paye DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    SdlPay DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    WcfPay DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    PayMode VARCHAR(100) NULL,

    PaidMonth VARCHAR(20) NOT NULL,
    PayrollYear YEAR NOT NULL,
    PaidDate DATE NULL,
    NextPaidDate DATE NULL,

    Status VARCHAR(50) NOT NULL DEFAULT 'Active',
    Conditions VARCHAR(100) NULL,
    ActionPay VARCHAR(100) NULL,

    ApprovalStatus VARCHAR(50) NOT NULL DEFAULT 'Pending',
    PaymentStatus VARCHAR(50) NOT NULL DEFAULT 'Pending',

    HrManager VARCHAR(50) DEFAULT 'Pending',
    HrDirector VARCHAR(50) DEFAULT 'Pending',
    FinManger VARCHAR(50) DEFAULT 'Pending',
    DafComnt VARCHAR(50) DEFAULT 'Pending',

    MdComnt VARCHAR(50) DEFAULT 'Pending',

    HrManagerComnt TEXT NULL,
    HrDirectorComnt TEXT NULL,
    FinMangerComnt TEXT NULL,
    DafComntComnt TEXT NULL,
    MdComntComnt TEXT NULL,

    PayrollComment TEXT NULL,

    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_salary_employee FOREIGN KEY (Employee_id) REFERENCES employees(id),
    CONSTRAINT fk_salary_user FOREIGN KEY (User_id) REFERENCES users(id)
);


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

('View Salary Menu', 'view-salary-menu', 'View Salary Menu', 'Active', NOW(), NOW()),
('View Salaries', 'view-salary', 'View Salaries', 'Active', NOW(), NOW()),
('Generate Salary', 'generate-salary', 'Generate Salary', 'Active', NOW(), NOW()),
('Update or Delete Salary', 'crud-salary', 'Update or Delete Salary', 'Active', NOW(), NOW()),
('View Pending Salaries', 'pending-salary', 'View Pending Salaries', 'Active', NOW(), NOW()),
('Approve Salary', 'approve-salary', 'Approve Salary', 'Active', NOW(), NOW()),
('Reject Salary', 'reject-salary', 'Reject Salary', 'Active', NOW(), NOW()),
('Pay Salary', 'pay-salary', 'Pay Salary', 'Active', NOW(), NOW()),
('View Salary History', 'salary-history', 'View Salary History', 'Active', NOW(), NOW()),

('View Weekly Allowance Menu', 'view-weekly-allowance-menu', 'View Weekly Allowance Menu', 'Active', NOW(), NOW()),
('View Weekly Allowances', 'view-weekly-allowance', 'View Weekly Allowances', 'Active', NOW(), NOW()),
('Generate Weekly Allowance', 'generate-weekly-allowance', 'Generate Weekly Allowance', 'Active', NOW(), NOW()),
('Update or Delete Weekly Allowance', 'crud-weekly-allowance', 'Update or Delete Weekly Allowance', 'Active', NOW(), NOW()),
('View Pending Weekly Allowances', 'pending-weekly-allowance', 'View Pending Weekly Allowances', 'Active', NOW(), NOW()),
('Approve Weekly Allowance', 'approve-weekly-allowance', 'Approve Weekly Allowance', 'Active', NOW(), NOW()),
('Reject Weekly Allowance', 'reject-weekly-allowance', 'Reject Weekly Allowance', 'Active', NOW(), NOW()),
('Pay Weekly Allowance', 'pay-weekly-allowance', 'Pay Weekly Allowance', 'Active', NOW(), NOW()),
('View Weekly Allowance History', 'weekly-allowance-history', 'View Weekly Allowance History', 'Active', NOW(), NOW());

CREATE TABLE weekly_allowances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    Employee_id BIGINT UNSIGNED NOT NULL,
    User_id BIGINT UNSIGNED NOT NULL,

    AllowanceAmount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    AmountPaid DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    WeekNumber INT NOT NULL,
    AllowanceMonth VARCHAR(20) NOT NULL,
    AllowanceYear YEAR NOT NULL,

    GeneratedDate DATE NOT NULL,
    PaidDate DATE NULL,
    NextAllowanceDate DATE NULL,

    PayMode VARCHAR(100) NULL,

    Status VARCHAR(50) NOT NULL DEFAULT 'Active',
    Conditions VARCHAR(100) DEFAULT 'Pending',
    ActionPay VARCHAR(100) DEFAULT 'Pending',

    ApprovalStatus VARCHAR(50) DEFAULT 'Pending',
    PaymentStatus VARCHAR(50) DEFAULT 'Pending',

    HrManager VARCHAR(50) DEFAULT 'Pending',
    HrDirector VARCHAR(50) DEFAULT 'Pending',
    FinManger VARCHAR(50) DEFAULT 'Pending',

    HrManagerComnt TEXT NULL,
    HrDirectorComnt TEXT NULL,
    FinMangerComnt TEXT NULL,

    AllowanceComment TEXT NULL,

    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_weekly_allowance_employee
        FOREIGN KEY (Employee_id) REFERENCES employees(id),

    CONSTRAINT fk_weekly_allowance_user
        FOREIGN KEY (User_id) REFERENCES users(id)
);






































































CREATE TABLE loan_repayment_schedules (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    loan_id BIGINT UNSIGNED NOT NULL,

    installment_no INT NOT NULL,

    due_date DATE NOT NULL,

    principal_due DECIMAL(18,2) DEFAULT 0,
    interest_due DECIMAL(18,2) DEFAULT 0,

    total_due DECIMAL(18,2) DEFAULT 0,

    amount_paid DECIMAL(18,2) DEFAULT 0,

    balance_due DECIMAL(18,2) DEFAULT 0,

    payment_status ENUM(
        'Pending',
        'Partial',
        'Paid',
        'Overdue'
    ) DEFAULT 'Pending',

    User_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_loan_id (loan_id),
    INDEX idx_due_date (due_date)

);
