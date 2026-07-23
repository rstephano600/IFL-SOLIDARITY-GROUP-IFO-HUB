ALTER TABLE account_fifth_group_branches
DROP FOREIGN KEY account_fifth_group_branches_ibfk_2;

ALTER TABLE account_sixth_member_branches
DROP FOREIGN KEY account_sixth_member_branches_ibfk_2;

RENAME TABLE account_fourth_center_branches TO account_fourth_branches;

RENAME TABLE account_fifth_group_branches TO account_fifth_branches;

RENAME TABLE account_sixth_member_branches TO account_sixth_branches;


ALTER TABLE account_fifth_branches
ADD CONSTRAINT fk_account_fifth_branches_fourth
FOREIGN KEY (FourthRoot_id)
REFERENCES account_fourth_branches(id)
ON DELETE CASCADE;

ALTER TABLE account_sixth_branches
ADD CONSTRAINT fk_account_sixth_branches_fifth
FOREIGN KEY (FifthRoot_id)
REFERENCES account_fifth_branches(id)
ON DELETE CASCADE;



CREATE TABLE companies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    company_code VARCHAR(30) NOT NULL UNIQUE,
    company_name VARCHAR(200) NOT NULL,

    company_type VARCHAR(200) NOT NULL,

    parent_company_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    address VARCHAR(255) NULL,
    region VARCHAR(100) NULL,
    district VARCHAR(100) NULL,
    ward VARCHAR(100) NULL,
    village VARCHAR(100) NULL,

    phone VARCHAR(30) NULL,
    email VARCHAR(150) NULL,

    established_date DATE NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

 
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_company_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_company_parent
        FOREIGN KEY (parent_company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_company_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_company_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE branchies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    branch_code VARCHAR(30) NOT NULL UNIQUE,
    branch_name VARCHAR(200) NOT NULL,
    company_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    address VARCHAR(255) NULL,
    region VARCHAR(100) NULL,
    district VARCHAR(100) NULL,
    ward VARCHAR(100) NULL,
    village VARCHAR(100) NULL,

    phone VARCHAR(30) NULL,
    email VARCHAR(150) NULL,

    established_date DATE NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

 
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_branch_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_branch_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_branch_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_branch_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);


CREATE TABLE cost_centres (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    cost_centre_code VARCHAR(30) NOT NULL UNIQUE,
    cost_centre_name VARCHAR(200) NOT NULL,
    department_id BIGINT UNSIGNED NULL,
    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,

    reporting_segment TEXT NULL,

    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

 
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_cost_centre_department
        FOREIGN KEY (department_id)
        REFERENCES departments(id)
        ON DELETE SET NULL,
        
    CONSTRAINT fk_cost_centre_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_cost_centre_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_cost_centre_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_cost_centre_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_cost_centre_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE company_businesses_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    cost_centre_code VARCHAR(30) NOT NULL UNIQUE,
    cost_centre_name VARCHAR(200) NOT NULL,
    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,

    business_activity TEXT NULL,
    segment TEXT NULL,
    description TEXT NULL,

    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

 
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,


        
    CONSTRAINT fk_company_businesse_code_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_company_businesses_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_company_businesse_code_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_company_businesse_code_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_company_businesse_code_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);


CREATE TABLE member_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    member_category_code VARCHAR(30) NOT NULL UNIQUE,
    member_category_name VARCHAR(200) NOT NULL,
    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,

    description TEXT NULL,
    voting_right TEXT NULL,
    loan_eligibility TEXT NULL,

    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

 
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,


        
    CONSTRAINT fk_member_category_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_category_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_category_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_category_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_category_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);


CREATE TABLE members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    member_code VARCHAR(30) NOT NULL UNIQUE,
    member_name VARCHAR(200) NOT NULL,
    member_category_id BIGINT UNSIGNED NULL,
    member_id BIGINT UNSIGNED NULL,
    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,

    nida TEXT NULL,
    tin TEXT NULL,
    work_permit TEXT NULL,

    admission_date DATE NULL,

    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_member_member_category
        FOREIGN KEY (member_category_id)
        REFERENCES member_categories(id)
        ON DELETE SET NULL,
        
    CONSTRAINT fk_member_member
        FOREIGN KEY (member_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_member_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);


ALTER TABLE departments
ADD COLUMN company_id BIGINT UNSIGNED NULL AFTER id,
ADD COLUMN branch_id BIGINT UNSIGNED NULL AFTER company_id,
ADD COLUMN department_function TEXT NULL AFTER descriptions;

ALTER TABLE departments
ADD CONSTRAINT fk_departments_company
FOREIGN KEY (company_id) REFERENCES companies(id)
ON DELETE SET NULL,
ADD CONSTRAINT fk_departments_branch
FOREIGN KEY (branch_id) REFERENCES branchies(id)
ON DELETE SET NULL;

ALTER TABLE departments
ADD COLUMN function TEXT NULL AFTER descriptions;

ALTER TABLE members
ADD COLUMN profile_picture TEXT NULL AFTER admission_date;

ALTER TABLE users
ADD COLUMN profile_picture TEXT NULL AFTER Dob;

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

-- ===========================
-- COMPANIES
-- ===========================
('Companies Menu', 'view-companies-menu','Companies Menu', 'Active', NOW(), NOW()),
('View Companies', 'view-companies','View Companies', 'Active', NOW(), NOW()),
('Create, Update, Delete Companies', 'crud-companies','Create, Update or Delete Companies', 'Active', NOW(), NOW()),
('Restore Companies', 'restore-companies','Restore Companies', 'Active', NOW(), NOW()),
('View Companies Report', 'view-companies-report','View Companies Report', 'Active', NOW(), NOW()),


-- ===========================
-- BRANCHES
-- ===========================
('Branches Menu', 'view-branches-menu','Branches Menu', 'Active', NOW(), NOW()),
('View Branches', 'view-branches','View Branches', 'Active', NOW(), NOW()),
('Create, Update, Delete Branches', 'crud-branches','Create, Update or Delete Branches', 'Active', NOW(), NOW()),
('Restore Branches', 'restore-branches','Restore Branches', 'Active', NOW(), NOW()),
('View Branches Report', 'view-branches-report','View Branches Report', 'Active', NOW(), NOW()),


-- ===========================
-- DEPARTMENTS
-- ===========================
('Restore Departments', 'restore-departments','Restore Departments', 'Active', NOW(), NOW()),
('View Departments Report', 'view-departments-report','View Departments Report', 'Active', NOW(), NOW()),


-- ===========================
-- COST CENTRES
-- ===========================
('Cost Centres Menu', 'view-cost-centres-menu','Cost Centres Menu', 'Active', NOW(), NOW()),
('View Cost Centres', 'view-cost-centres','View Cost Centres', 'Active', NOW(), NOW()),
('Create, Update, Delete Cost Centres', 'crud-cost-centres','Create, Update or Delete Cost Centres', 'Active', NOW(), NOW()),
('Restore Cost Centres', 'restore-cost-centres','Restore Cost Centres', 'Active', NOW(), NOW()),
('View Cost Centres Report', 'view-cost-centres-report','View Cost Centres Report', 'Active', NOW(), NOW()),


-- ===========================
-- COMPANY BUSINESS CODES
-- ===========================
('Company Business Codes Menu', 'view-company-business-codes-menu','Company Business Codes Menu', 'Active', NOW(), NOW()),
('View Company Business Codes', 'view-company-business-codes','View Company Business Codes', 'Active', NOW(), NOW()),
('Create, Update, Delete Company Business Codes', 'crud-company-business-codes','Create, Update or Delete Company Business Codes', 'Active', NOW(), NOW()),
('Restore Company Business Codes', 'restore-company-business-codes','Restore Company Business Codes', 'Active', NOW(), NOW()),
('View Company Business Codes Report', 'view-company-business-codes-report','View Company Business Codes Report', 'Active', NOW(), NOW()),


-- ===========================
-- MEMBER CATEGORIES
-- ===========================
('Member Categories Menu', 'view-member-categories-menu','Member Categories Menu', 'Active', NOW(), NOW()),
('View Member Categories', 'view-member-categories','View Member Categories', 'Active', NOW(), NOW()),
('Create, Update, Delete Member Categories', 'crud-member-categories','Create, Update or Delete Member Categories', 'Active', NOW(), NOW()),
('Restore Member Categories', 'restore-member-categories','Restore Member Categories', 'Active', NOW(), NOW()),
('View Member Categories Report', 'view-member-categories-report','View Member Categories Report', 'Active', NOW(), NOW()),


-- ===========================
-- MEMBERS
-- ===========================
('Members Menu', 'view-members-menu','Members Menu', 'Active', NOW(), NOW()),
('View Members', 'view-members','View Members', 'Active', NOW(), NOW()),
('Create, Update, Delete Members', 'crud-members','Create, Update or Delete Members', 'Active', NOW(), NOW()),
('Restore Members', 'restore-members','Restore Members', 'Active', NOW(), NOW()),
('View Members Report', 'view-members-report','View Members Report', 'Active', NOW(), NOW());

ALTER TABLE company_businesses_codes
CHANGE COLUMN cost_centre_code business_code VARCHAR(30) NOT NULL UNIQUE,
CHANGE COLUMN cost_centre_name business_name VARCHAR(200) NOT NULL;

CREATE TABLE `member_notifications` (
    `id` CHAR(36) NOT NULL PRIMARY KEY,
    `type` VARCHAR(255) NOT NULL,
    `notifiable_type` VARCHAR(255) NOT NULL,
    `notifiable_id` BIGINT UNSIGNED NOT NULL,
    `data` TEXT NOT NULL,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`, `notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 22/07/2026
CREATE TABLE product_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    CompanyBusinessCode_id BIGINT UNSIGNED NOT NULL,


    category_code VARCHAR(30) NOT NULL UNIQUE,
    category_name VARCHAR(150) NOT NULL,

    description TEXT NULL,

    display_order INT DEFAULT 1,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    deleted_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_product_categories_business
        FOREIGN KEY (CompanyBusinessCode_id)
        REFERENCES account_businesses(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_product_categories_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_product_categories_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_product_categories_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_product_categories_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_product_categories_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_product_categories_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

ALTER TABLE product_categories
DROP FOREIGN KEY fk_product_categories_business;

ALTER TABLE product_categories
ADD CONSTRAINT fk_product_categories_business
FOREIGN KEY (CompanyBusinessCode_id)
REFERENCES company_businesses_codes(id)
ON UPDATE CASCADE
ON DELETE RESTRICT;

CREATE TABLE brands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    BrandRefNo VARCHAR(50) UNIQUE NOT NULL,
    BrandCode VARCHAR(50) NULL,
    BrandName VARCHAR(255) NOT NULL,

    Description TEXT NULL,
    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_brands_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_brands_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_brands_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL
);


CREATE TABLE units_of_measure (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,



    UnitRefNo VARCHAR(50) UNIQUE NOT NULL,

    UnitCode VARCHAR(20) NOT NULL,
    UnitName VARCHAR(100) NOT NULL,

    Description TEXT NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_units_of_measure_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_units_of_measure_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_units_of_measure_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL
);

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    product_code VARCHAR(30) NOT NULL UNIQUE,

    product_name VARCHAR(200) NOT NULL,

    product_category_id BIGINT UNSIGNED NOT NULL,

    CompanyBusinessCode_id BIGINT UNSIGNED NOT NULL,

    income_gl_account_id BIGINT UNSIGNED NULL,

    expense_gl_account_id BIGINT UNSIGNED NULL,

    inventory_gl_account_id BIGINT UNSIGNED NULL,

    unit_of_measure_id BIGINT UNSIGNED NULL,

    cost_price DECIMAL(18,2) DEFAULT 0.00,

    selling_price DECIMAL(18,2) DEFAULT 0.00,

    minimum_price DECIMAL(18,2) DEFAULT 0.00,

    maximum_price DECIMAL(18,2) DEFAULT 0.00,

    tax_rate DECIMAL(5,2) DEFAULT 0.00,

    requires_member BOOLEAN DEFAULT FALSE,

    requires_approval BOOLEAN DEFAULT FALSE,

    is_stock_item BOOLEAN DEFAULT FALSE,

    allow_discount BOOLEAN DEFAULT TRUE,

    description TEXT NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    deleted_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_products_category
        FOREIGN KEY (product_category_id)
        REFERENCES product_categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_products_business
        FOREIGN KEY (CompanyBusinessCode_id)
        REFERENCES company_businesses_codes(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_products_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_products_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_products_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_products_income_gl
        FOREIGN KEY (income_gl_account_id)
        REFERENCES account_third_branches(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_products_expense_gl
        FOREIGN KEY (expense_gl_account_id)
        REFERENCES account_third_branches(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_products_inventory_gl
        FOREIGN KEY (inventory_gl_account_id)
        REFERENCES account_third_branches(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_products_uom
        FOREIGN KEY (unit_of_measure_id)
        REFERENCES units_of_measure(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_products_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_products_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_products_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('products Menu', 'view-products-menu','products Menu', 'Active', NOW(), NOW()),
('View products', 'view-products','View products', 'Active', NOW(), NOW()),
('Create, Update, Delete products', 'crud-products','Create, Update or Delete products', 'Active', NOW(), NOW()),
('Restore products', 'restore-products','Restore products', 'Active', NOW(), NOW()),
('View products Report', 'view-products-report','View products Report', 'Active', NOW(), NOW());


INSERT INTO units_of_measure
    (UnitRefNo, UnitCode, UnitName, Description, company_id, branch_id, User_id, Status, AuditingStatus, ReportStatus)
VALUES
    ('UOM-0001', 'PCS',  'Pieces',      'Individual countable units',                 NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0002', 'KG',   'Kilogram',    'Standard weight measure',                    NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0003', 'G',    'Gram',        'Small weight measure',                       NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0004', 'L',    'Litre',       'Standard liquid volume measure',             NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0005', 'ML',   'Millilitre',  'Small liquid volume measure',                NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0006', 'BOX',  'Box',         'Packaged in boxes',                          NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0007', 'CTN',  'Carton',      'Packaged in cartons',                        NULL, NULL, 1, 'Active', 'Pending',  'Pending'),
    ('UOM-0008', 'BAG',  'Bag',         'Sold or stored in bags (e.g. fertilizer)',   NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0009', 'SACK', 'Sack',        'Sold or stored in sacks (e.g. maize, rice)', NULL, NULL, 1, 'Active', 'Pending',  'Pending'),
    ('UOM-0010', 'MTR',  'Meter',       'Length measure',                             NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0011', 'FT',   'Feet',        'Length measure (imperial)',                  NULL, NULL, 1, 'Active', 'Pending',  'Pending'),
    ('UOM-0012', 'DZ',   'Dozen',       'Set of 12 units',                            NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0013', 'PKT',  'Packet',      'Small packaged unit',                        NULL, NULL, 1, 'Active', 'Approved', 'Approved'),
    ('UOM-0014', 'ROLL', 'Roll',        'Rolled goods (e.g. wire, fabric)',           NULL, NULL, 1, 'Active', 'Pending',  'Pending'),
    ('UOM-0015', 'TON',  'Ton',         'Bulk weight measure (1000 kg)',              NULL, NULL, 1, 'Inactive','Rejected','Rejected');