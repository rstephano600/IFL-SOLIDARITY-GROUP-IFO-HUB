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






-- 22/07/2026 (UN POSTED TO LIVE) - PRODUCTS AND SHARE INFORMATIONS
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


-- 23/07/2026

CREATE TABLE share_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    TypeRefNo VARCHAR(50) UNIQUE NOT NULL,
    TypeCode VARCHAR(20) NOT NULL,
    TypeName VARCHAR(100) NOT NULL,
    Description TEXT NULL,

    NominalValue DECIMAL(15,2) NOT NULL DEFAULT 0,
    DividendEligible TINYINT(1) NOT NULL DEFAULT 1,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_share_types_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_share_types_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_share_types_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

-- =====================================================
-- SHARE TYPES
-- =====================================================
('Share Types Menu', 'view-share-types-menu','Share Types Menu', 'Active', NOW(), NOW()),
('View Share Types', 'view-share-types','View Share Types', 'Active', NOW(), NOW()),
('Create, Update, Delete Share Types', 'crud-share-types','Create, Update or Delete Share Types', 'Active', NOW(), NOW()),
('Restore Share Types', 'restore-share-types','Restore Share Types', 'Active', NOW(), NOW()),
('View Share Types Report', 'view-share-types-report','View Share Types Report', 'Active', NOW(), NOW()),

-- =====================================================
-- SHARE ISSUES
-- =====================================================
('Share Issues Menu', 'view-share-issues-menu','Share Issues Menu', 'Active', NOW(), NOW()),
('View Share Issues', 'view-share-issues','View Share Issues', 'Active', NOW(), NOW()),
('Create, Update, Delete Share Issues', 'crud-share-issues','Create, Update or Delete Share Issues', 'Active', NOW(), NOW()),
('Restore Share Issues', 'restore-share-issues','Restore Share Issues', 'Active', NOW(), NOW()),
('View Share Issues Report', 'view-share-issues-report','View Share Issues Report', 'Active', NOW(), NOW()),

-- =====================================================
-- SHARE PURCHASES
-- =====================================================
('Share Purchases Menu', 'view-share-purchases-menu','Share Purchases Menu', 'Active', NOW(), NOW()),
('View Share Purchases', 'view-share-purchases','View Share Purchases', 'Active', NOW(), NOW()),
('Create, Update, Delete Share Purchases', 'crud-share-purchases','Create, Update or Delete Share Purchases', 'Active', NOW(), NOW()),
('Approve Share Purchases', 'approve-share-purchases','Approve Share Purchases', 'Active', NOW(), NOW()),
('Restore Share Purchases', 'restore-share-purchases','Restore Share Purchases', 'Active', NOW(), NOW()),
('View Share Purchases Report', 'view-share-purchases-report','View Share Purchases Report', 'Active', NOW(), NOW()),

-- =====================================================
-- SHARE CERTIFICATES
-- =====================================================
('Share Certificates Menu', 'view-share-certificates-menu','Share Certificates Menu', 'Active', NOW(), NOW()),
('View Share Certificates', 'view-share-certificates','View Share Certificates', 'Active', NOW(), NOW()),
('Generate Share Certificates', 'generate-share-certificates','Generate Share Certificates', 'Active', NOW(), NOW()),
('Reissue Share Certificates', 'reissue-share-certificates','Reissue Share Certificates', 'Active', NOW(), NOW()),
('Cancel Share Certificates', 'cancel-share-certificates','Cancel Share Certificates', 'Active', NOW(), NOW()),
('View Share Certificates Report', 'view-share-certificates-report','View Share Certificates Report', 'Active', NOW(), NOW()),

-- =====================================================
-- SHARE TRANSFERS
-- =====================================================
('Share Transfers Menu', 'view-share-transfers-menu','Share Transfers Menu', 'Active', NOW(), NOW()),
('View Share Transfers', 'view-share-transfers','View Share Transfers', 'Active', NOW(), NOW()),
('Create, Update, Delete Share Transfers', 'crud-share-transfers','Create, Update or Delete Share Transfers', 'Active', NOW(), NOW()),
('Approve Share Transfers', 'approve-share-transfers','Approve Share Transfers', 'Active', NOW(), NOW()),
('Restore Share Transfers', 'restore-share-transfers','Restore Share Transfers', 'Active', NOW(), NOW()),
('View Share Transfers Report', 'view-share-transfers-report','View Share Transfers Report', 'Active', NOW(), NOW()),

-- =====================================================
-- DIVIDEND DECLARATIONS
-- =====================================================
('Dividend Declaration Menu', 'view-dividend-declarations-menu','Dividend Declaration Menu', 'Active', NOW(), NOW()),
('View Dividend Declarations', 'view-dividend-declarations','View Dividend Declarations', 'Active', NOW(), NOW()),
('Create, Update, Delete Dividend Declarations', 'crud-dividend-declarations','Create, Update or Delete Dividend Declarations', 'Active', NOW(), NOW()),
('Approve Dividend Declarations', 'approve-dividend-declarations','Approve Dividend Declarations', 'Active', NOW(), NOW()),
('Restore Dividend Declarations', 'restore-dividend-declarations','Restore Dividend Declarations', 'Active', NOW(), NOW()),
('View Dividend Declaration Report', 'view-dividend-declarations-report','View Dividend Declaration Report', 'Active', NOW(), NOW()),

-- =====================================================
-- DIVIDEND ALLOCATIONS
-- =====================================================
('Dividend Allocations Menu', 'view-dividend-allocations-menu','Dividend Allocations Menu', 'Active', NOW(), NOW()),
('View Dividend Allocations', 'view-dividend-allocations','View Dividend Allocations', 'Active', NOW(), NOW()),
('Calculate Dividend Allocations', 'calculate-dividend-allocations','Calculate Dividend Allocations', 'Active', NOW(), NOW()),
('Approve Dividend Allocations', 'approve-dividend-allocations','Approve Dividend Allocations', 'Active', NOW(), NOW()),
('View Dividend Allocation Report', 'view-dividend-allocations-report','View Dividend Allocation Report', 'Active', NOW(), NOW()),

-- =====================================================
-- MEMBERSHIP FEES
-- =====================================================
('Membership Fees Menu', 'view-membership-fees-menu','Membership Fees Menu', 'Active', NOW(), NOW()),
('View Membership Fees', 'view-membership-fees','View Membership Fees', 'Active', NOW(), NOW()),
('Create, Update, Delete Membership Fees', 'crud-membership-fees','Create, Update or Delete Membership Fees', 'Active', NOW(), NOW()),
('Receive Membership Fee Payments', 'receive-membership-fee-payments','Receive Membership Fee Payments', 'Active', NOW(), NOW()),
('View Membership Fees Report', 'view-membership-fees-report','View Membership Fees Report', 'Active', NOW(), NOW()),

-- =====================================================
-- SOCIAL CONTRIBUTIONS
-- =====================================================
('Social Contributions Menu', 'view-social-contributions-menu','Social Contributions Menu', 'Active', NOW(), NOW()),
('View Social Contributions', 'view-social-contributions','View Social Contributions', 'Active', NOW(), NOW()),
('Create, Update, Delete Social Contributions', 'crud-social-contributions','Create, Update or Delete Social Contributions', 'Active', NOW(), NOW()),
('Receive Social Contributions', 'receive-social-contributions','Receive Social Contributions', 'Active', NOW(), NOW()),
('View Social Contributions Report', 'view-social-contributions-report','View Social Contributions Report', 'Active', NOW(), NOW());

-- =====================================================
-- GENERAL FINANCE MANAGENT
-- =====================================================
INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

('Manage Share Capital', 'manage-share-capital','Manage all share capital operations', 'Active', NOW(), NOW()),

('Manage Member Investments', 'manage-member-investments','Manage member investments, shares and dividends', 'Active', NOW(), NOW());


CREATE TABLE share_offerings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    OfferingRefNo VARCHAR(50) UNIQUE NOT NULL,
    OfferingName VARCHAR(150) NOT NULL,        -- e.g. 'Hisa za Mwanzo 2026'
    share_type_id BIGINT UNSIGNED NOT NULL,

    TotalShares DECIMAL(15,2) NOT NULL,        -- 5,000
    PricePerShare DECIMAL(15,2) NOT NULL,      -- 10,000
    TotalCapitalAmount DECIMAL(18,2) AS (TotalShares * PricePerShare) STORED, -- 50,000,000

    MaxPercentPerMember DECIMAL(5,2) NOT NULL DEFAULT 25.00,
    MaxSharesPerMember DECIMAL(15,2) AS (TotalShares * MaxPercentPerMember / 100) STORED,      -- 1,250
    MaxAmountPerMember DECIMAL(18,2) AS (TotalShares * PricePerShare * MaxPercentPerMember / 100) STORED, -- 12,500,000

    OfferingStartDate DATE NOT NULL,
    OfferingEndDate DATE NULL,
    OfferingStatus VARCHAR(50) DEFAULT 'Open',

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_offering_share_type FOREIGN KEY (share_type_id) REFERENCES share_types(id) ON DELETE RESTRICT,
    CONSTRAINT fk_offering_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_offering_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_offering_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);


CREATE TABLE membership_fee_schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    ScheduleRefNo VARCHAR(50) UNIQUE NOT NULL,
    FeeAmount DECIMAL(15,2) NOT NULL,          -- e.g. 50,000
    EffectiveFrom DATE NOT NULL,
    EffectiveTo DATE NULL,                     -- NULL = still active
    Description VARCHAR(255) NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_mfs_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_mfs_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_mfs_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);

CREATE TABLE membership_fee_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    PaymentRefNo VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,
    fee_schedule_id BIGINT UNSIGNED NOT NULL,

    AmountPaid DECIMAL(15,2) NOT NULL,
    PaymentDate DATE NOT NULL,
    PaymentMethod VARCHAR(50) NULL,
    PaymentReference VARCHAR(100) NULL,
    Narration VARCHAR(255) NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_mfp_member FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT,
    CONSTRAINT fk_mfp_schedule FOREIGN KEY (fee_schedule_id) REFERENCES membership_fee_schedules(id) ON DELETE RESTRICT,
    CONSTRAINT fk_mfp_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_mfp_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_mfp_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);


CREATE TABLE social_contributions_schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    ScheduleRefNo VARCHAR(50) UNIQUE NOT NULL,
    FeeAmount DECIMAL(15,2) NOT NULL,          
    EffectiveFrom DATE NOT NULL,
    EffectiveTo DATE NULL,                     
    Description VARCHAR(255) NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_scs_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_scs_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_scs_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);

CREATE TABLE social_contributions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    social_contribution_schedule_id BIGINT UNSIGNED NOT NULL,

    ContributionRefNo VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,

    ContributionMonth DATE NOT NULL,
    ExpectedAmount DECIMAL(15,2) NOT NULL DEFAULT 5000.00,
    AmountPaid DECIMAL(15,2) NOT NULL,
    PaymentDate DATE NOT NULL,
    PaymentMethod VARCHAR(50) NULL,
    PaymentReference VARCHAR(100) NULL,

    PaymentStatus ENUM('Paid','Partial','Late','Waived') NOT NULL DEFAULT 'Paid',
    Narration VARCHAR(255) NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_social_contributions_schedules FOREIGN KEY (social_contribution_schedule_id) REFERENCES social_contributions_schedules(id) ON DELETE RESTRICT,
    CONSTRAINT fk_social_member FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT,
    CONSTRAINT fk_social_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_social_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_social_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,

    UNIQUE KEY uq_social_member_month (member_id, ContributionMonth)
);



CREATE TABLE share_purchase_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    TransactionRefNo VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,
    share_offering_id BIGINT UNSIGNED NOT NULL,
    share_type_id BIGINT UNSIGNED NOT NULL,

    TransactionType VARCHAR(100) DEFAULT 'Purchase',

    SharesQuantity DECIMAL(15,2) NOT NULL,       -- signed
    PricePerShare DECIMAL(15,2) NOT NULL,
    TotalAmount DECIMAL(15,2) AS (SharesQuantity * PricePerShare) STORED,

    TransactionDate DATE NOT NULL,
    PaymentMethod VARCHAR(50) NULL,
    PaymentReference VARCHAR(100) NULL,          -- same ref as membership_fee_payments/social_contributions if paid together

    related_transfer_id BIGINT UNSIGNED NULL,
    Narration VARCHAR(255) NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_spt_member FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT,
    CONSTRAINT fk_spt_offering FOREIGN KEY (share_offering_id) REFERENCES share_offerings(id) ON DELETE RESTRICT,
    CONSTRAINT fk_spt_share_type FOREIGN KEY (share_type_id) REFERENCES share_types(id) ON DELETE RESTRICT,
    CONSTRAINT fk_spt_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_spt_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_spt_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,

    INDEX idx_spt_member_offering (member_id, share_offering_id),
    INDEX idx_spt_date (TransactionDate)
);

-- =========================================================
-- 6a. TRIGGER: enforce the 25% (or whatever MaxPercentPerMember is) cap
-- =========================================================
DELIMITER $$

CREATE TRIGGER trg_check_share_cap
BEFORE INSERT ON share_purchase_transactions
FOR EACH ROW
BEGIN
    DECLARE v_max_shares DECIMAL(15,2);
    DECLARE v_current_shares DECIMAL(15,2);
    DECLARE v_projected_total DECIMAL(15,2);

    IF NEW.TransactionType IN ('Purchase','TransferIn') THEN

        SELECT MaxSharesPerMember INTO v_max_shares
        FROM share_offerings
        WHERE id = NEW.share_offering_id;

        SELECT COALESCE(SUM(SharesQuantity), 0) INTO v_current_shares
        FROM share_purchase_transactions
        WHERE member_id = NEW.member_id
          AND share_offering_id = NEW.share_offering_id;

        SET v_projected_total = v_current_shares + NEW.SharesQuantity;

        IF v_projected_total > v_max_shares THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Purchase exceeds the maximum allowed shares (25% cap) for this member in this offering.';
        END IF;

    END IF;
END$$

DELIMITER ;



CREATE TABLE share_certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    CertificateRefNo VARCHAR(50) UNIQUE NOT NULL,
    CertificateNumber VARCHAR(50) UNIQUE NOT NULL,

    member_id BIGINT UNSIGNED NOT NULL,
    share_offering_id BIGINT UNSIGNED NOT NULL,
    share_type_id BIGINT UNSIGNED NOT NULL,

    SharesQuantity DECIMAL(15,2) NOT NULL,
    IssueDate DATE NOT NULL,
    RevocationDate DATE NULL,
    RevocationReason VARCHAR(255) NULL,
    replaced_by_certificate_id BIGINT UNSIGNED NULL,

    CertificateStatus VARCHAR(50) DEFAULT 'Active',

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_cert_member FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT,
    CONSTRAINT fk_cert_offering FOREIGN KEY (share_offering_id) REFERENCES share_offerings(id) ON DELETE RESTRICT,
    CONSTRAINT fk_cert_share_type FOREIGN KEY (share_type_id) REFERENCES share_types(id) ON DELETE RESTRICT,
    CONSTRAINT fk_cert_replaced FOREIGN KEY (replaced_by_certificate_id) REFERENCES share_certificates(id) ON DELETE SET NULL,
    CONSTRAINT fk_cert_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_cert_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_cert_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);


CREATE TABLE share_transfers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    TransferRefNo VARCHAR(50) UNIQUE NOT NULL,

    from_member_id BIGINT UNSIGNED NOT NULL,
    to_member_id BIGINT UNSIGNED NOT NULL,
    share_offering_id BIGINT UNSIGNED NOT NULL,
    share_type_id BIGINT UNSIGNED NULL,

    SharesQuantity DECIMAL(15,2) NOT NULL,
    TransferPrice DECIMAL(15,2) NULL,
    TransferDate DATE NOT NULL,
    Reason VARCHAR(255) NULL,
    ApprovedBy BIGINT UNSIGNED NULL,
    ApprovalDate DATE NULL,

    TransferStatus VARCHAR(50) DEFAULT 'Pending',

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_transfer_from FOREIGN KEY (from_member_id) REFERENCES members(id) ON DELETE RESTRICT,
    CONSTRAINT fk_transfer_to FOREIGN KEY (to_member_id) REFERENCES members(id) ON DELETE RESTRICT,
    CONSTRAINT fk_transfer_offering FOREIGN KEY (share_offering_id) REFERENCES share_offerings(id) ON DELETE RESTRICT,
    CONSTRAINT fk_transfer_share_type FOREIGN KEY (share_type_id) REFERENCES share_types(id) ON DELETE RESTRICT,
    CONSTRAINT fk_transfer_approver FOREIGN KEY (ApprovedBy) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_transfer_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_transfer_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_transfer_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);


ALTER TABLE share_purchase_transactions

-- ==========================================================
-- VERIFICATION
-- ==========================================================
ADD COLUMN verification_status ENUM('Pending','Verified','Rejected')
    NOT NULL DEFAULT 'Pending' AFTER Narration,

ADD COLUMN verified_by BIGINT UNSIGNED NULL AFTER verification_status,

ADD COLUMN verified_at DATETIME NULL AFTER verified_by,

ADD COLUMN verification_remarks TEXT NULL AFTER verified_at,

-- ==========================================================
-- APPROVAL
-- ==========================================================
ADD COLUMN approval_status ENUM('Pending','Approved','Rejected')
    NOT NULL DEFAULT 'Pending' AFTER verification_remarks,

ADD COLUMN approved_by BIGINT UNSIGNED NULL AFTER approval_status,

ADD COLUMN approved_at DATETIME NULL AFTER approved_by,

ADD COLUMN approval_remarks TEXT NULL AFTER approved_at,

-- ==========================================================
-- ACCOUNTING POSTING
-- ==========================================================
ADD COLUMN posting_status ENUM('Pending','Posted','Reversed')
    NOT NULL DEFAULT 'Pending' AFTER approval_remarks,

ADD COLUMN posted_by BIGINT UNSIGNED NULL AFTER posting_status,

ADD COLUMN posted_at DATETIME NULL AFTER posted_by,

ADD COLUMN posting_reference VARCHAR(100) NULL AFTER posted_at,

ADD COLUMN journal_no VARCHAR(50) NULL AFTER posting_reference,

ADD COLUMN journal_id BIGINT UNSIGNED NULL AFTER journal_no,

-- ==========================================================
-- GL ACCOUNT CODES
-- ==========================================================
ADD COLUMN cash_account_code VARCHAR(30) NULL AFTER journal_id,

ADD COLUMN share_capital_account_code VARCHAR(30) NULL AFTER cash_account_code,

ADD COLUMN membership_fee_account_code VARCHAR(30) NULL AFTER share_capital_account_code,

ADD COLUMN social_contribution_account_code VARCHAR(30) NULL AFTER membership_fee_account_code,

-- ==========================================================
-- SHARE CERTIFICATE
-- ==========================================================
ADD COLUMN certificate_status ENUM('Not Generated','Generated','Reissued','Cancelled')
    NOT NULL DEFAULT 'Not Generated' AFTER social_contribution_account_code,

ADD COLUMN certificate_generated_at DATETIME NULL AFTER certificate_status,

ADD COLUMN certificate_generated_by BIGINT UNSIGNED NULL AFTER certificate_generated_at,

ADD COLUMN certificate_id BIGINT UNSIGNED NULL AFTER certificate_generated_by;


ALTER TABLE share_purchase_transactions

ADD CONSTRAINT fk_spt_verified_by
FOREIGN KEY (verified_by)
REFERENCES users(id),

ADD CONSTRAINT fk_spt_approved_by
FOREIGN KEY (approved_by)
REFERENCES users(id),

ADD CONSTRAINT fk_spt_posted_by
FOREIGN KEY (posted_by)
REFERENCES users(id),

ADD CONSTRAINT fk_spt_certificate_generated_by
FOREIGN KEY (certificate_generated_by)
REFERENCES users(id),

ADD CONSTRAINT fk_spt_certificate
FOREIGN KEY (certificate_id)
REFERENCES share_certificates(id);

ALTER TABLE share_purchase_transactions
ADD COLUMN transaction_status ENUM(
    'Draft',
    'Pending Verification',
    'Verified',
    'Approved',
    'Posted',
    'Completed',
    'Cancelled',
    'Rejected'
) NOT NULL DEFAULT 'Draft' AFTER certificate_id;


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('Verify Share Purchases', 'verify-share-purchases','Verify Share Purchase Transactions before approval', 'Active', NOW(), NOW()),
('Post Share Purchases', 'post-share-purchases','Post Share Purchase Transactions into Accounting', 'Active', NOW(), NOW()),
('Generate Certificate Share Purchases', 'generate-certificate-share-purchases','Generate Share Certificates after successful share purchase posting', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('Reject Share Purchases', 'reject-share-purchases','Reject invalid Share Purchase Transactions during verification or approval', 'Active', NOW(), NOW()),
('Reverse Posted Share Purchases', 'reverse-share-purchases','Reverse posted Share Purchase Transactions from Accounting', 'Active', NOW(), NOW());


ALTER TABLE social_contributions

-- =====================================================
-- VERIFICATION STAGE
-- =====================================================
ADD COLUMN verification_status ENUM('Pending','Verified','Rejected')
DEFAULT 'Pending' AFTER Narration,

ADD COLUMN verified_by BIGINT UNSIGNED NULL AFTER verification_status,

ADD COLUMN verified_at DATETIME NULL AFTER verified_by,

ADD COLUMN verification_remarks TEXT NULL AFTER verified_at,


-- =====================================================
-- APPROVAL STAGE
-- =====================================================
ADD COLUMN approval_status ENUM('Pending','Approved','Rejected')
DEFAULT 'Pending' AFTER verification_remarks,

ADD COLUMN approved_by BIGINT UNSIGNED NULL AFTER approval_status,

ADD COLUMN approved_at DATETIME NULL AFTER approved_by,

ADD COLUMN approval_remarks TEXT NULL AFTER approved_at,


-- =====================================================
-- FINANCE POSTING STAGE
-- =====================================================
ADD COLUMN posting_status ENUM('Pending','Posted','Reversed')
DEFAULT 'Pending' AFTER approval_remarks,

ADD COLUMN posted_by BIGINT UNSIGNED NULL AFTER posting_status,

ADD COLUMN posted_at DATETIME NULL AFTER posted_by,

ADD COLUMN posting_reference VARCHAR(100) NULL AFTER posted_at,


-- =====================================================
-- ACCOUNTING INTEGRATION
-- =====================================================
ADD COLUMN journal_no VARCHAR(50) NULL AFTER posting_reference,

ADD COLUMN journal_id BIGINT UNSIGNED NULL AFTER journal_no,

ADD COLUMN cash_account_code VARCHAR(30) NULL AFTER journal_id,

ADD COLUMN social_contribution_account_code VARCHAR(30) NULL AFTER cash_account_code,


-- =====================================================
-- GENERAL TRANSACTION STATUS
-- =====================================================
ADD COLUMN transaction_status ENUM(
    'Draft',
    'Pending Verification',
    'Verified',
    'Approved',
    'Posted',
    'Completed',
    'Rejected',
    'Cancelled'
)
DEFAULT 'Draft' AFTER social_contribution_account_code;


ALTER TABLE social_contributions

ADD CONSTRAINT fk_social_verified_by
FOREIGN KEY (verified_by)
REFERENCES users(id),

ADD CONSTRAINT fk_social_approved_by
FOREIGN KEY (approved_by)
REFERENCES users(id),

ADD CONSTRAINT fk_social_posted_by
FOREIGN KEY (posted_by)
REFERENCES users(id);


ALTER TABLE membership_fee_payments

-- =====================================================
-- VERIFICATION STAGE
-- =====================================================
ADD COLUMN verification_status ENUM('Pending','Verified','Rejected')
DEFAULT 'Pending' AFTER Narration,

ADD COLUMN verified_by BIGINT UNSIGNED NULL AFTER verification_status,

ADD COLUMN verified_at DATETIME NULL AFTER verified_by,

ADD COLUMN verification_remarks TEXT NULL AFTER verified_at,


-- =====================================================
-- APPROVAL STAGE
-- =====================================================
ADD COLUMN approval_status ENUM('Pending','Approved','Rejected')
DEFAULT 'Pending' AFTER verification_remarks,

ADD COLUMN approved_by BIGINT UNSIGNED NULL AFTER approval_status,

ADD COLUMN approved_at DATETIME NULL AFTER approved_by,

ADD COLUMN approval_remarks TEXT NULL AFTER approved_at,


-- =====================================================
-- FINANCE POSTING STAGE
-- =====================================================
ADD COLUMN posting_status ENUM('Pending','Posted','Reversed')
DEFAULT 'Pending' AFTER approval_remarks,

ADD COLUMN posted_by BIGINT UNSIGNED NULL AFTER posting_status,

ADD COLUMN posted_at DATETIME NULL AFTER posted_by,

ADD COLUMN posting_reference VARCHAR(100) NULL AFTER posted_at,


-- =====================================================
-- ACCOUNTING INTEGRATION
-- =====================================================
ADD COLUMN journal_no VARCHAR(50) NULL AFTER posting_reference,

ADD COLUMN journal_id BIGINT UNSIGNED NULL AFTER journal_no,

ADD COLUMN cash_account_code VARCHAR(30) NULL AFTER journal_id,

ADD COLUMN social_contribution_account_code VARCHAR(30) NULL AFTER cash_account_code,


-- =====================================================
-- GENERAL TRANSACTION STATUS
-- =====================================================
ADD COLUMN membership_fee_payments ENUM(
    'Draft',
    'Pending Verification',
    'Verified',
    'Approved',
    'Posted',
    'Completed',
    'Rejected',
    'Cancelled'
)
DEFAULT 'Draft' AFTER social_contribution_account_code;


ALTER TABLE membership_fee_payments

ADD CONSTRAINT fk_somembership_fee_payments_verified_by
FOREIGN KEY (verified_by)
REFERENCES users(id),

ADD CONSTRAINT fk_somembership_fee_payments_approved_by
FOREIGN KEY (approved_by)
REFERENCES users(id),

ADD CONSTRAINT fk_membership_fee_payments_posted_by
FOREIGN KEY (posted_by)
REFERENCES users(id);

INSERT INTO permissions 
(name, slug, description, Status, created_at, updated_at) VALUES
('Verify Social Contribution',  'verify-social-contribution', 'Verify member social contribution payments before posting', 'Active', NOW(), NOW()),
('Approve Social Contribution',  'approve-social-contribution', 'Approve verified member social contribution payments', 'Active', NOW(), NOW()),
('Post Social Contribution',  'post-social-contribution', 'Post social contribution payments into accounting', 'Active', NOW(), NOW());

INSERT INTO permissions 
(name, slug, description, Status, created_at, updated_at) VALUES
('Reject Social Contribution', 'reject-social-contribution','Reject incorrect social contribution payments during verification or approval','Active', NOW(), NOW()),
('View Social Contribution Report', 'view-social-contribution-report','View social contribution reports and summaries','Active', NOW(), NOW());


INSERT INTO permissions 
(name, slug, description, Status, created_at, updated_at) VALUES
('Create Membership Fee Payment', 'create-membership-fee-payment', 'Create member membership fee payment transactions', 'Active', NOW(), NOW()),
('Verify Membership Fee Payment', 'verify-membership-fee-payment', 'Verify membership fee payments before approval and posting', 'Active', NOW(), NOW()),
('Approve Membership Fee Payment', 'approve-membership-fee-payment', 'Approve verified membership fee payment transactions', 'Active', NOW(), NOW()),
('Post Membership Fee Payment', 'post-membership-fee-payment', 'Post membership fee payments into accounting records', 'Active', NOW(), NOW());

INSERT INTO permissions 
(name, slug, description, Status, created_at, updated_at) 
VALUES

('Reject Membership Fee Payment', 'reject-membership-fee-payment', 'Reject incorrect membership fee payment transactions', 'Active', NOW(), NOW()),
('View Membership Fee Payment Report', 'view-membership-fee-payment-report', 'View membership fee payment reports and summaries', 'Active', NOW(), NOW());


ALTER TABLE social_contributions
CHANGE COLUMN membership_fee_payments transaction_status
ENUM(
    'Draft',
    'Pending Verification',
    'Verified',
    'Approved',
    'Posted',
    'Completed',
    'Rejected',
    'Cancelled'
)
DEFAULT 'Draft';



-- tarehe 25/08/2026
CREATE TABLE share_dividend_allocations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    share_dividend_declaration_id BIGINT UNSIGNED NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,
    share_type_id BIGINT UNSIGNED NULL,

    shares_held DECIMAL(18,4) NOT NULL DEFAULT 0,
    share_value DECIMAL(18,2) NOT NULL DEFAULT 0,
    dividend_rate DECIMAL(10,4) NOT NULL DEFAULT 0,
    dividend_amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    payment_status VARCHAR(50) NOT NULL DEFAULT 'Pending',
    payment_date DATE NULL,
    payment_reference VARCHAR(100) NULL,

    accounting_transaction_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) NOT NULL DEFAULT 'Active',
    AuditingStatus VARCHAR(50) NULL,
    ReportStatus VARCHAR(50) NULL,

    User_id BIGINT UNSIGNED NULL,
    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_dividend_declaration (
        share_dividend_declaration_id
    ),

    INDEX idx_member (
        member_id
    ),

    INDEX idx_share_type (
        share_type_id
    ),

    INDEX idx_payment_status (
        payment_status
    ),

    INDEX idx_accounting_transaction (
        accounting_transaction_id
    )
);


--- TAREHE 06/09/2026
CREATE TABLE product_subcategories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    company_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NOT NULL,

    SubCategoryRefNo VARCHAR(50) UNIQUE NOT NULL,

    category_id BIGINT UNSIGNED NOT NULL,

    SubCategoryCode VARCHAR(50) NULL,
    SubCategoryName VARCHAR(255) NOT NULL,
    Description TEXT NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id)
        REFERENCES product_categories(id)
        ON DELETE CASCADE,

    FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL
);



-- 18/09/2026
ALTER TABLE `employees`
    -- Payment / Salary processing
    ADD COLUMN `bank_name` VARCHAR(150) NULL AFTER `weekly_allowance_amount`,
    ADD COLUMN `bank_account_number` VARCHAR(50) NULL AFTER `bank_name`,
    ADD COLUMN `bank_account_name` VARCHAR(150) NULL AFTER `bank_account_number`,
    ADD COLUMN `bank_branch` VARCHAR(150) NULL AFTER `bank_account_name`,
    ADD COLUMN `mobile_money_provider` ENUM('mpesa','tigopesa','airtelmoney','halopesa','other') NULL AFTER `bank_branch`,
    ADD COLUMN `mobile_money_number` VARCHAR(20) NULL AFTER `mobile_money_provider`,
    ADD COLUMN `payment_method` ENUM('bank','mobile_money','cash') NULL AFTER `mobile_money_number`,
    ADD COLUMN `housing_allowance` DECIMAL(20,6) NULL AFTER `payment_method`,
    ADD COLUMN `transport_allowance` DECIMAL(20,6) NULL AFTER `housing_allowance`,
    ADD COLUMN `medical_allowance` DECIMAL(20,6) NULL AFTER `transport_allowance`,
    ADD COLUMN `overtime_rate` DECIMAL(20,6) NULL AFTER `medical_allowance`,

    -- Tanzanian statutory / compliance
    ADD COLUMN `tin_number` VARCHAR(30) NULL AFTER `overtime_rate`,
    ADD COLUMN `nssf_number` VARCHAR(30) NULL AFTER `tin_number`,
    ADD COLUMN `pssss_number` VARCHAR(30) NULL AFTER `nssf_number`,
    ADD COLUMN `nhif_number` VARCHAR(30) NULL AFTER `pssss_number`,
    ADD COLUMN `wcf_number` VARCHAR(30) NULL AFTER `nhif_number`,
    ADD COLUMN `employment_type` ENUM('permanent','contract','probation','casual') NULL AFTER `wcf_number`,
    ADD COLUMN `contract_start_date` DATE NULL AFTER `employment_type`,
    ADD COLUMN `contract_end_date` DATE NULL AFTER `contract_start_date`,

    -- Employment structure
    ADD COLUMN `supervisor_id` BIGINT UNSIGNED NULL AFTER `contract_end_date`,
    ADD COLUMN `branch_id` BIGINT UNSIGNED NULL AFTER `supervisor_id`,
    ADD COLUMN `termination_date` DATE NULL AFTER `branch_id`,
    ADD COLUMN `termination_reason` VARCHAR(255) NULL AFTER `termination_date`,
    ADD COLUMN `leave_balance` DECIMAL(20,6) NULL AFTER `termination_reason`;

-- Optional FKs (uncomment / adjust if `employees.id` and a `branches` table exist)
-- ALTER TABLE `employees`
--     ADD CONSTRAINT `fk_employees_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `employees`(`id`) ON DELETE SET NULL,
--     ADD CONSTRAINT `fk_employees_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL;


CREATE TABLE procurement_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    procurement_code VARCHAR(50) NOT NULL UNIQUE,
    procurement_name VARCHAR(150) NOT NULL,

    description TEXT NULL,

    requires_request BOOLEAN DEFAULT TRUE,
    requires_quotation BOOLEAN DEFAULT FALSE,
    requires_quotation_evaluation BOOLEAN DEFAULT FALSE,
    requires_purchase_order BOOLEAN DEFAULT FALSE,
    requires_receiving BOOLEAN DEFAULT TRUE,
    requires_inspection BOOLEAN DEFAULT FALSE,
    requires_invoice BOOLEAN DEFAULT TRUE,
    requires_payment_approval BOOLEAN DEFAULT TRUE,

    creates_stock BOOLEAN DEFAULT FALSE,
    creates_expense BOOLEAN DEFAULT FALSE,
    creates_asset BOOLEAN DEFAULT FALSE,

    requires_budget BOOLEAN DEFAULT TRUE,
    requires_approval BOOLEAN DEFAULT TRUE,

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
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_procurement_types_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_procurement_types_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_procurement_types_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_procurement_types_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_procurement_types_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_procurement_types_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);



ALTER TABLE products
ADD COLUMN procurement_type_id BIGINT UNSIGNED NULL
AFTER product_category_id;

ALTER TABLE products
ADD CONSTRAINT fk_products_procurement_type
FOREIGN KEY (procurement_type_id)
REFERENCES procurement_types(id)
ON UPDATE CASCADE
ON DELETE SET NULL;


CREATE TABLE procurement_workflow_steps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    procurement_type_id BIGINT UNSIGNED NOT NULL,

    step_code VARCHAR(50) NOT NULL,
    step_name VARCHAR(150) NOT NULL,

    step_order INT NOT NULL DEFAULT 1,

    description TEXT NULL,

    is_required BOOLEAN DEFAULT TRUE,
    is_active BOOLEAN DEFAULT TRUE,
    can_skip BOOLEAN DEFAULT FALSE,
    is_final_step BOOLEAN DEFAULT FALSE,

    requires_approval BOOLEAN DEFAULT FALSE,
    requires_attachment BOOLEAN DEFAULT FALSE,
    requires_comment BOOLEAN DEFAULT FALSE,

    assigned_role VARCHAR(100) NULL,

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
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_proc_workflow_type
        FOREIGN KEY (procurement_type_id)
        REFERENCES procurement_types(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_proc_workflow_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_proc_workflow_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_proc_workflow_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_proc_workflow_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_proc_workflow_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_proc_workflow_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    UNIQUE (
        procurement_type_id,
        step_code
    )
);

CREATE TABLE purchase_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    request_no VARCHAR(50) NOT NULL UNIQUE,

    procurement_type_id BIGINT UNSIGNED NOT NULL,

    requester_id BIGINT UNSIGNED NOT NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,

    request_date DATE NOT NULL,
    required_date DATE NULL,

    priority VARCHAR(30) DEFAULT 'Normal',

    purpose VARCHAR(255) NULL,
    justification TEXT NULL,

    budget_amount DECIMAL(18,2) DEFAULT 0.00,
    estimated_total DECIMAL(18,2) DEFAULT 0.00,
    approved_total DECIMAL(18,2) DEFAULT 0.00,

    currency_code VARCHAR(10) DEFAULT 'TZS',

    current_step_id BIGINT UNSIGNED NULL,

    Status VARCHAR(50) DEFAULT 'Draft',

    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    submitted_at TIMESTAMP NULL,
    approved_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,

    approval_comment TEXT NULL,
    rejection_reason TEXT NULL,

    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    deleted_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_purchase_requests_type
        FOREIGN KEY (procurement_type_id)
        REFERENCES procurement_types(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_purchase_requests_requester
        FOREIGN KEY (requester_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_purchase_requests_step
        FOREIGN KEY (current_step_id)
        REFERENCES procurement_workflow_steps(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_requests_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_requests_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_requests_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_requests_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_requests_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_requests_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE purchase_request_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    purchase_request_id BIGINT UNSIGNED NOT NULL,

    product_id BIGINT UNSIGNED NOT NULL,

    unit_of_measure_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    requested_quantity DECIMAL(18,3) NOT NULL DEFAULT 0.000,

    estimated_unit_price DECIMAL(18,2) DEFAULT 0.00,

    estimated_total DECIMAL(18,2) DEFAULT 0.00,

    approved_quantity DECIMAL(18,3) DEFAULT 0.000,

    approved_unit_price DECIMAL(18,2) DEFAULT 0.00,

    approved_total DECIMAL(18,2) DEFAULT 0.00,

    item_status VARCHAR(50) DEFAULT 'Pending',

    remarks TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_purchase_request_items_request
        FOREIGN KEY (purchase_request_id)
        REFERENCES purchase_requests(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_purchase_request_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_purchase_request_items_uom
        FOREIGN KEY (unit_of_measure_id)
        REFERENCES units_of_measure(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. salary_calcls — active base-salary setup per employee.
--    This is what generatePayrollByMonth() reads to start a run.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `salary_calcls` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Paid_id`    BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `GrsAmount`  DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Status`     VARCHAR(20) NOT NULL DEFAULT 'Active',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_salary_calcls_paid_id` (`Paid_id`),
    KEY `idx_salary_calcls_status` (`Status`),
    CONSTRAINT `fk_salary_calcls_employee` FOREIGN KEY (`Paid_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 2. payroll_batches — one row per payroll generation run.
--    SnapshotJson stores the pre-generation state for rollback.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payroll_batches` (
    `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `BatchCode`     VARCHAR(50) NOT NULL,
    `PayrollMonth`  CHAR(7) NOT NULL COMMENT 'Format: Y-m',
    `BatchStatus`   VARCHAR(20) NOT NULL DEFAULT 'Generated' COMMENT 'Generated / RolledBack / Submitted',
    `SnapshotJson`  LONGTEXT NULL,
    `User_id`       BIGINT UNSIGNED NULL COMMENT 'FK to users.id — who generated the batch',
    `created_at`    TIMESTAMP NULL DEFAULT NULL,
    `updated_at`    TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_payroll_batches_batchcode` (`BatchCode`),
    KEY `idx_payroll_batches_month` (`PayrollMonth`),
    CONSTRAINT `fk_payroll_batches_user` FOREIGN KEY (`User_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 3. temp_salaries — the working/draft payroll rows for the
--    currently generated (not-yet-submitted) batch.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `temp_salaries` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Emply_id`     BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `ActualGross`  DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Allowance`    DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Overtime`     DECIMAL(20,6) NOT NULL DEFAULT 0,
    `OvtmAdvn`     DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Heslb`        DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Absent`       DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Bcabd`        DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Advance`      DECIMAL(20,6) NOT NULL DEFAULT 0,
    `AmountPaid`   DECIMAL(20,6) NOT NULL DEFAULT 0 COMMENT 'Gross amount for the run',
    `EmpNssf`      DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Paye`         DECIMAL(20,6) NOT NULL DEFAULT 0,
    `NetPay`       DECIMAL(20,6) NOT NULL DEFAULT 0,
    `SdlPay`       DECIMAL(20,6) NOT NULL DEFAULT 0 COMMENT 'Employer-side SDL cost',
    `NssfPay`      DECIMAL(20,6) NOT NULL DEFAULT 0 COMMENT 'Employer-side NSSF cost',
    `WcfPay`       DECIMAL(20,6) NOT NULL DEFAULT 0 COMMENT 'Employer-side WCF cost',
    `PaidMonth`    CHAR(7) NOT NULL COMMENT 'Format: Y-m',
    `BatchCode`    VARCHAR(50) NOT NULL,
    `User_id`      BIGINT UNSIGNED NULL COMMENT 'FK to users.id — who generated/edited the row',
    `Condition`    VARCHAR(50) NOT NULL DEFAULT 'None',
    `Status`       VARCHAR(20) NOT NULL DEFAULT 'Active',
    `created_at`   TIMESTAMP NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_temp_salaries_emply_id` (`Emply_id`),
    KEY `idx_temp_salaries_batchcode` (`BatchCode`),
    KEY `idx_temp_salaries_paidmonth` (`PaidMonth`),
    KEY `idx_temp_salaries_status` (`Status`),
    CONSTRAINT `fk_temp_salaries_employee` FOREIGN KEY (`Emply_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_temp_salaries_user` FOREIGN KEY (`User_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 4. salary_paids — finalized/historical payroll rows, mirrors
--    temp_salaries once a batch is submitted for a given month.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `salary_paids` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Emply_id`     BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `ActualGross`  DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Allowance`    DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Overtime`     DECIMAL(20,6) NOT NULL DEFAULT 0,
    `OvtmAdvn`     DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Heslb`        DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Absent`       DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Bcabd`        DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Advance`      DECIMAL(20,6) NOT NULL DEFAULT 0,
    `AmountPaid`   DECIMAL(20,6) NOT NULL DEFAULT 0,
    `EmpNssf`      DECIMAL(20,6) NOT NULL DEFAULT 0,
    `Paye`         DECIMAL(20,6) NOT NULL DEFAULT 0,
    `NetPay`       DECIMAL(20,6) NOT NULL DEFAULT 0,
    `SdlPay`       DECIMAL(20,6) NOT NULL DEFAULT 0,
    `NssfPay`      DECIMAL(20,6) NOT NULL DEFAULT 0,
    `WcfPay`       DECIMAL(20,6) NOT NULL DEFAULT 0,
    `PaidMonth`    CHAR(7) NOT NULL COMMENT 'Format: Y-m',
    `BatchCode`    VARCHAR(50) NULL,
    `DatePaid`     DATE NULL,
    `User_id`      BIGINT UNSIGNED NULL,
    `Condition`    VARCHAR(50) NOT NULL DEFAULT 'None',
    `Status`       VARCHAR(20) NOT NULL DEFAULT 'Active',
    `created_at`   TIMESTAMP NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_salary_paids_emply_id` (`Emply_id`),
    KEY `idx_salary_paids_paidmonth` (`PaidMonth`),
    CONSTRAINT `fk_salary_paids_employee` FOREIGN KEY (`Emply_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_salary_paids_user` FOREIGN KEY (`User_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 5. staff_overtimes — approved overtime claims awaiting/paid in payroll.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `staff_overtimes` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Emp_id`           BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `PayAmnt`          DECIMAL(20,6) NOT NULL DEFAULT 0,
    `AppStatus`        VARCHAR(20) NOT NULL DEFAULT 'Pending' COMMENT 'Approved / Pending / Rejected',
    `PayStatus`        VARCHAR(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending / Paid',
    `AdvanceStatus`    VARCHAR(30) NULL COMMENT 'Paid / Not In Advance',
    `AdvanceCondition` VARCHAR(30) NULL COMMENT 'Pending / Cleared / Not In Advance',
    `PaidMonth`        CHAR(7) NULL,
	`Status`    VARCHAR(30) NULL COMMENT 'Paid / Not In Advance',
    `created_by`       BIGINT UNSIGNED NULL,
    `created_at`       TIMESTAMP NULL DEFAULT NULL,
    `updated_at`       TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_staff_overtimes_emp_id` (`Emp_id`),
    KEY `idx_staff_overtimes_statuses` (`AppStatus`,`PayStatus`),
    CONSTRAINT `fk_staff_overtimes_employee` FOREIGN KEY (`Emp_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 6. overt_pays — cashout log, one row per overtime claim paid out.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `overt_pays` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Ovrt_id`        BIGINT UNSIGNED NOT NULL COMMENT 'FK to staff_overtimes.id',
    `Emp_id`         BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `User_id`        BIGINT UNSIGNED NULL COMMENT 'FK to users.id — who processed it',
    `AmntPay`        DECIMAL(20,6) NOT NULL DEFAULT 0,
    `DatePaid`       DATE NULL,
    `CashOutStatus`  VARCHAR(20) NOT NULL DEFAULT 'Cashed-Out',
    `Condition`      VARCHAR(20) NOT NULL DEFAULT 'Cleared',
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_overt_pays_emp_id` (`Emp_id`),
    KEY `idx_overt_pays_ovrt_id` (`Ovrt_id`),
    CONSTRAINT `fk_overt_pays_overtime` FOREIGN KEY (`Ovrt_id`) REFERENCES `staff_overtimes`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_overt_pays_employee` FOREIGN KEY (`Emp_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_overt_pays_user` FOREIGN KEY (`User_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 7. staff_heslbs — HESLB (student loan) repayment schedule per employee.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `staff_heslbs` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Emp_id`         BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `LoanAmount`     DECIMAL(20,6) NOT NULL DEFAULT 0,
    `PaidAmount`     DECIMAL(20,6) NOT NULL DEFAULT 0,
    `InstallAmount`  DECIMAL(20,6) NOT NULL DEFAULT 0,
    `LoanStatus`     VARCHAR(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending / Active / Complete',
    `created_by`     BIGINT UNSIGNED NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_staff_heslbs_emp_id` (`Emp_id`),
    KEY `idx_staff_heslbs_status` (`LoanStatus`),
    CONSTRAINT `fk_staff_heslbs_employee` FOREIGN KEY (`Emp_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 8. staff_advnc_slries — salary advance repayment schedule per employee.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `staff_advnc_slries` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Emp_id`         BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `AdvanceAmount`  DECIMAL(20,6) NOT NULL DEFAULT 0,
    `PaidAmount`     DECIMAL(20,6) NOT NULL DEFAULT 0,
    `InstallAmount`  DECIMAL(20,6) NOT NULL DEFAULT 0,
    `AdvStatus`      VARCHAR(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending / Complete',
    `created_by`     BIGINT UNSIGNED NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_staff_advnc_slries_emp_id` (`Emp_id`),
    KEY `idx_staff_advnc_slries_status` (`AdvStatus`),
    CONSTRAINT `fk_staff_advnc_slries_employee` FOREIGN KEY (`Emp_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 9. staff_absents — absence deduction queue per employee.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `staff_absents` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Emp_id`         BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `DedactAmount`   DECIMAL(20,6) NOT NULL DEFAULT 0,
    `AbsentStatus`   VARCHAR(30) NOT NULL DEFAULT 'Submited' COMMENT 'Submited / Dedacted/Complete',
    `created_by`     BIGINT UNSIGNED NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_staff_absents_emp_id` (`Emp_id`),
    KEY `idx_staff_absents_status` (`AbsentStatus`),
    CONSTRAINT `fk_staff_absents_employee` FOREIGN KEY (`Emp_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 10. staff_absent_calenders — the underlying day-by-day absence log
--     that staff_absents rolls up from/mirrors status with.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `staff_absent_calenders` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Emp_id`         BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `AbsentDate`     DATE NULL,
    `AbsentStatus`   VARCHAR(30) NOT NULL DEFAULT 'Submited' COMMENT 'Submited / Dedacted/Complete',
    `created_by`     BIGINT UNSIGNED NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_staff_absent_calenders_emp_id` (`Emp_id`),
    KEY `idx_staff_absent_calenders_status` (`AbsentStatus`),
    CONSTRAINT `fk_staff_absent_calenders_employee` FOREIGN KEY (`Emp_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 11. emp_slr_adv_costs — per-employee staff loan/cost with an
--     outstanding balance drawn down each payroll run (`Bcabd`).
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `emp_slr_adv_costs` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Paid_id`      BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `Amount`       DECIMAL(20,6) NOT NULL DEFAULT 0 COMMENT 'Installment amount per run',
    `OutsAmount`   DECIMAL(20,6) NOT NULL DEFAULT 0 COMMENT 'Outstanding balance remaining',
    `PaidAmount`   DECIMAL(20,6) NOT NULL DEFAULT 0 COMMENT 'Cumulative amount paid so far',
    `PaidMonth`    CHAR(7) NULL,
    `Status`       VARCHAR(20) NOT NULL DEFAULT 'Active' COMMENT 'Active / Paid',
    `created_by`   BIGINT UNSIGNED NULL,
    `created_at`   TIMESTAMP NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_emp_slr_adv_costs_paid_id` (`Paid_id`),
    KEY `idx_emp_slr_adv_costs_status` (`Status`),
    CONSTRAINT `fk_emp_slr_adv_costs_employee` FOREIGN KEY (`Paid_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 12. comp_slr_adv_costs — company-wide statutory rates
--     (SDL, NSSF Employer's, WCF). NOT linked to an employee —
--     this is global payroll configuration.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `comp_slr_adv_costs` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `AdvName`    VARCHAR(100) NOT NULL COMMENT "e.g. SDL, NSSF Employer's, WCF",
    `Amount`     DECIMAL(10,6) NOT NULL DEFAULT 0 COMMENT 'Percentage rate, e.g. 10.000000 for 10%',
    `Status`     VARCHAR(20) NOT NULL DEFAULT 'Active',
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_comp_slr_adv_costs_advname` (`AdvName`),
    KEY `idx_comp_slr_adv_costs_status` (`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 13. slr_allwncs — queued one-off allowances per employee,
--     consumed by getAllowance() on the next payroll run.
--     Note: `PayStaus` spelling matches the controller's exact field name.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `slr_allwncs` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Paid_id`     BIGINT UNSIGNED NOT NULL COMMENT 'FK to employees.id',
    `AllwAmount`  DECIMAL(20,6) NOT NULL DEFAULT 0,
    `PayStaus`    VARCHAR(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending / Paid',
    `created_by`  BIGINT UNSIGNED NULL,
    `created_at`  TIMESTAMP NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_slr_allwncs_paid_id` (`Paid_id`),
    KEY `idx_slr_allwncs_status` (`PayStaus`),
    CONSTRAINT `fk_slr_allwncs_employee` FOREIGN KEY (`Paid_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE `payroll_batches`
    ADD COLUMN `SubmittedBy`     BIGINT UNSIGNED NULL AFTER `User_id`,
    ADD COLUMN `SubmittedAt`     TIMESTAMP NULL DEFAULT NULL AFTER `SubmittedBy`,
    ADD COLUMN `ApprovedBy`      BIGINT UNSIGNED NULL AFTER `SubmittedAt`,
    ADD COLUMN `ApprovedAt`      TIMESTAMP NULL DEFAULT NULL AFTER `ApprovedBy`,
    ADD COLUMN `RejectedBy`      BIGINT UNSIGNED NULL AFTER `ApprovedAt`,
    ADD COLUMN `RejectedAt`      TIMESTAMP NULL DEFAULT NULL AFTER `RejectedBy`,
    ADD COLUMN `RejectionReason` VARCHAR(500) NULL AFTER `RejectedAt`;

ALTER TABLE `payroll_batches`
    ADD CONSTRAINT `fk_payroll_batches_submitted_by` FOREIGN KEY (`SubmittedBy`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_payroll_batches_approved_by`  FOREIGN KEY (`ApprovedBy`)  REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_payroll_batches_rejected_by`  FOREIGN KEY (`RejectedBy`)  REFERENCES `users`(`id`) ON DELETE SET NULL;

ALTER TABLE salary_paids
ADD COLUMN BatchCode VARCHAR(100) NULL AFTER id;
ALTER TABLE salary_paids
ADD COLUMN DatePaid DATETIME NULL AFTER BatchCode;
ALTER TABLE salary_paids
ADD COLUMN `Condition` VARCHAR(100) NULL AFTER `User_id`;
ALTER TABLE salary_paids
DROP COLUMN PayrollYear;



INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

-- =====================================================
-- STAFF ABSENT CALENDARS
-- =====================================================
('Staff Absent Calendar Menu', 'view-staff-absent-calendars-menu', 'Staff Absent Calendar Menu', 'Active', NOW(), NOW()),
('View Staff Absent Calendars', 'view-staff-absent-calendars', 'View Staff Absent Calendar Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Staff Absent Calendars', 'crud-staff-absent-calendars', 'Create, Update or Delete Staff Absent Calendar Records', 'Active', NOW(), NOW()),
('Restore Staff Absent Calendars', 'restore-staff-absent-calendars', 'Restore Deleted Staff Absent Calendar Records', 'Active', NOW(), NOW()),
('View Staff Absent Calendars Report', 'view-staff-absent-calendars-report', 'View Staff Absent Calendars Report', 'Active', NOW(), NOW()),

-- =====================================================
-- STAFF ABSENT DEDUCTIONS
-- =====================================================
('Staff Absent Deductions Menu', 'view-staff-absents-menu', 'Staff Absent Deductions Menu', 'Active', NOW(), NOW()),
('View Staff Absent Deductions', 'view-staff-absents', 'View Staff Absent Deductions', 'Active', NOW(), NOW()),
('Create, Update, Delete Staff Absent Deductions', 'crud-staff-absents', 'Create, Update or Delete Staff Absent Deductions', 'Active', NOW(), NOW()),
('Restore Staff Absent Deductions', 'restore-staff-absents', 'Restore Deleted Staff Absent Deductions', 'Active', NOW(), NOW()),
('View Staff Absent Deductions Report', 'view-staff-absents-report', 'View Staff Absent Deductions Report', 'Active', NOW(), NOW());


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

-- =====================================================
-- STAFF ADVANCE SALARIES
-- =====================================================
('Staff Advance Salaries Menu', 'view-staff-advance-salaries-menu', 'Staff Advance Salaries Menu', 'Active', NOW(), NOW()),
('View Staff Advance Salaries', 'view-staff-advance-salaries', 'View Staff Advance Salaries', 'Active', NOW(), NOW()),
('Create, Update, Delete Staff Advance Salaries', 'crud-staff-advance-salaries', 'Create, Update or Delete Staff Advance Salaries', 'Active', NOW(), NOW()),
('Restore Staff Advance Salaries', 'restore-staff-advance-salaries', 'Restore Deleted Staff Advance Salaries', 'Active', NOW(), NOW()),
('View Staff Advance Salaries Report', 'view-staff-advance-salaries-report', 'View Staff Advance Salaries Report', 'Active', NOW(), NOW()),

-- =====================================================
-- STAFF OVERTIMES
-- =====================================================
('Staff Overtimes Menu', 'view-staff-overtimes-menu', 'Staff Overtimes Menu', 'Active', NOW(), NOW()),
('View Staff Overtimes', 'view-staff-overtimes', 'View Staff Overtimes', 'Active', NOW(), NOW()),
('Create, Update, Delete Staff Overtimes', 'crud-staff-overtimes', 'Create, Update or Delete Staff Overtimes', 'Active', NOW(), NOW()),
('Restore Staff Overtimes', 'restore-staff-overtimes', 'Restore Deleted Staff Overtimes', 'Active', NOW(), NOW()),
('View Staff Overtimes Report', 'view-staff-overtimes-report', 'View Staff Overtimes Report', 'Active', NOW(), NOW()),

-- =====================================================
-- SALARY ALLOWANCES
-- =====================================================
('Salary Allowances Menu', 'view-salary-allowances-menu', 'Salary Allowances Menu', 'Active', NOW(), NOW()),
('View Salary Allowances', 'view-salary-allowances', 'View Salary Allowances', 'Active', NOW(), NOW()),
('Create, Update, Delete Salary Allowances', 'crud-salary-allowances', 'Create, Update or Delete Salary Allowances', 'Active', NOW(), NOW()),
('Restore Salary Allowances', 'restore-salary-allowances', 'Restore Deleted Salary Allowances', 'Active', NOW(), NOW()),
('View Salary Allowances Report', 'view-salary-allowances-report', 'View Salary Allowances Report', 'Active', NOW(), NOW());




-- tarehe 05/10/2026

CREATE TABLE supplier_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    company_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NOT NULL,

    CategoryCode VARCHAR(50) UNIQUE NOT NULL,
    CategoryName VARCHAR(255) NOT NULL,

    SupplierGroup VARCHAR(50) NOT NULL,
    Description TEXT NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);




INSERT INTO supplier_categories
(
    company_id,
    branch_id,
    User_id,
    CategoryCode,
    CategoryName,
    SupplierGroup,
    Description
)
VALUES
(
    1,
    1,
    1,
    'IFLSG/SUP/CAT001',
    'Group Member Supplier',
    'MEMBER',
    'Suppliers who are registered members of IFL Solidarity Group and supply products, equipment or services to the group.'
),
(
    1,
    1,
    1,
    'IFLSG/SUP/CAT002',
    'External Supplier',
    'NON_MEMBER',
    'Suppliers who are not members of IFL Solidarity Group but supply products, equipment or services to the group.'
);

CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    company_id BIGINT UNSIGNED NOT NULL,
    branch_id BIGINT UNSIGNED NULL,

    -- User who registered/created this supplier
    User_id BIGINT UNSIGNED NOT NULL,

    -- User account representing the supplier
    supplier_id BIGINT UNSIGNED NULL,

    SupplierRefNo VARCHAR(50) UNIQUE NOT NULL,
    SupplierCode VARCHAR(50) UNIQUE NULL,

    SupplierName VARCHAR(255) NOT NULL,

    SupplierCategory_id BIGINT UNSIGNED NULL,
    Member_id BIGINT UNSIGNED NULL,

    SupplierType VARCHAR(50) NULL,

    ContactPerson VARCHAR(255) NULL,
    Phone VARCHAR(50) NULL,
    AlternativePhone VARCHAR(50) NULL,
    Email VARCHAR(255) NULL,

    Address TEXT NULL,
    City VARCHAR(100) NULL,
    Region VARCHAR(100) NULL,
    Country VARCHAR(100) DEFAULT 'Tanzania',

    TinNumber VARCHAR(100) NULL,
    VatNumber VARCHAR(100) NULL,

    CreditLimit DECIMAL(18,2) DEFAULT 0.00,
    OpeningBalance DECIMAL(18,2) DEFAULT 0.00,

    PaymentTerms VARCHAR(100) NULL,

    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE,

    FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (supplier_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    FOREIGN KEY (SupplierCategory_id)
        REFERENCES supplier_categories(id)
        ON DELETE SET NULL
);

INSERT INTO permissions
(
    name,
    slug,
    description,
    Status,
    created_at,
    updated_at
)
VALUES

-- =====================================================
-- SUPPLIER MANAGEMENT MENU
-- =====================================================
(
    'Supplier Menu',
    'view-suppliers-menu',
    'View Supplier Management Menu',
    'Active',
    NOW(),
    NOW()
),

-- =====================================================
-- SUPPLIER INFORMATION
-- =====================================================
(
    'View Suppliers',
    'view-suppliers',
    'View Supplier Information',
    'Active',
    NOW(),
    NOW()
),

(
    'Create, Update, Delete Suppliers',
    'crud-suppliers',
    'Create, Update or Delete Suppliers',
    'Active',
    NOW(),
    NOW()
),

(
    'Restore Suppliers',
    'restore-suppliers',
    'Restore Deleted Suppliers',
    'Active',
    NOW(),
    NOW()
),

(
    'View Supplier Report',
    'view-supplier-report',
    'View Supplier Reports',
    'Active',
    NOW(),
    NOW()
),

-- =====================================================
-- SUPPLIER CATEGORIES
-- =====================================================
(
    'View Supplier Categories',
    'view-supplier-categories',
    'View Supplier Categories',
    'Active',
    NOW(),
    NOW()
),

(
    'Create, Update, Delete Supplier Categories',
    'crud-supplier-categories',
    'Create, Update or Delete Supplier Categories',
    'Active',
    NOW(),
    NOW()
),

(
    'Restore Supplier Categories',
    'restore-supplier-categories',
    'Restore Deleted Supplier Categories',
    'Active',
    NOW(),
    NOW()
),

-- =====================================================
-- SUPPLIER PRODUCTS
-- =====================================================
(
    'View Supplier Products',
    'view-supplier-products',
    'View Products Supplied by Suppliers',
    'Active',
    NOW(),
    NOW()
),

(
    'Create, Update, Delete Supplier Products',
    'crud-supplier-products',
    'Create, Update or Delete Supplier Products',
    'Active',
    NOW(),
    NOW()
),

(
    'Restore Supplier Products',
    'restore-supplier-products',
    'Restore Deleted Supplier Products',
    'Active',
    NOW(),
    NOW()
),

-- =====================================================
-- SUPPLIER DOCUMENTS
-- =====================================================
(
    'View Supplier Documents',
    'view-supplier-documents',
    'View Supplier Documents',
    'Active',
    NOW(),
    NOW()
),

(
    'Create, Update, Delete Supplier Documents',
    'crud-supplier-documents',
    'Create, Update or Delete Supplier Documents',
    'Active',
    NOW(),
    NOW()
),

(
    'Restore Supplier Documents',
    'restore-supplier-documents',
    'Restore Deleted Supplier Documents',
    'Active',
    NOW(),
    NOW()
),

-- =====================================================
-- SUPPLIER EVALUATION
-- =====================================================
(
    'View Supplier Evaluation',
    'view-supplier-evaluation',
    'View Supplier Evaluation Information',
    'Active',
    NOW(),
    NOW()
),

(
    'Create, Update, Delete Supplier Evaluation',
    'crud-supplier-evaluation',
    'Create, Update or Delete Supplier Evaluation',
    'Active',
    NOW(),
    NOW()
),

(
    'View Supplier Evaluation Report',
    'view-supplier-evaluation-report',
    'View Supplier Evaluation Reports',
    'Active',
    NOW(),
    NOW()
),

-- =====================================================
-- SUPPLIER TRANSACTIONS
-- =====================================================
(
    'View Supplier Transactions',
    'view-supplier-transactions',
    'View Supplier Transactions',
    'Active',
    NOW(),
    NOW()
),

(
    'Create, Update, Delete Supplier Transactions',
    'crud-supplier-transactions',
    'Create, Update or Delete Supplier Transactions',
    'Active',
    NOW(),
    NOW()
),

(
    'View Supplier Transaction Report',
    'view-supplier-transaction-report',
    'View Supplier Transaction Reports',
    'Active',
    NOW(),
    NOW()
);


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

-- =====================================================
-- PROCUREMENT TYPES
-- =====================================================
('Procurement Types Menu', 'view-procurement-types-menu', 'Procurement Types Menu', 'Active', NOW(), NOW()),
('View Procurement Types', 'view-procurement-types', 'View Procurement Type Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Procurement Types', 'crud-procurement-types', 'Create, Update or Delete Procurement Type Records', 'Active', NOW(), NOW()),
('Restore Procurement Types', 'restore-procurement-types', 'Restore Deleted Procurement Type Records', 'Active', NOW(), NOW()),


-- =====================================================
-- PROCUREMENT WORKFLOW STEPS
-- =====================================================
('Procurement Workflow Steps Menu', 'view-procurement-workflow-steps-menu', 'Procurement Workflow Steps Menu', 'Active', NOW(), NOW()),
('View Procurement Workflow Steps', 'view-procurement-workflow-steps', 'View Procurement Workflow Step Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Procurement Workflow Steps', 'crud-procurement-workflow-steps', 'Create, Update or Delete Procurement Workflow Step Records', 'Active', NOW(), NOW()),
('Restore Procurement Workflow Steps', 'restore-procurement-workflow-steps', 'Restore Deleted Procurement Workflow Step Records', 'Active', NOW(), NOW()),
('Manage Procurement Workflow Steps', 'manage-procurement-workflow-steps', 'Activate, Deactivate and Reorder Procurement Workflow Steps', 'Active', NOW(), NOW());


CREATE TABLE purchase_request_workflows (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_request_id BIGINT UNSIGNED NOT NULL,
    workflow_step_id BIGINT UNSIGNED NOT NULL,
    purchase_status VARCHAR(50) DEFAULT 'Pending',

    action VARCHAR(50) NULL,
    assigned_to BIGINT UNSIGNED NULL,
    acted_by BIGINT UNSIGNED NULL,

    started_at TIMESTAMP NULL,
    acted_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,

    previous_step_id BIGINT UNSIGNED NULL,
    next_step_id BIGINT UNSIGNED NULL,

    comment TEXT NULL,
    rejection_reason TEXT NULL,

    attachment_path VARCHAR(500) NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_purchase_request_workflows_request
        FOREIGN KEY (purchase_request_id)
        REFERENCES purchase_requests(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_purchase_request_workflows_step
        FOREIGN KEY (workflow_step_id)
        REFERENCES procurement_workflow_steps(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_purchase_request_workflows_assigned_to
        FOREIGN KEY (assigned_to)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_acted_by
        FOREIGN KEY (acted_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_previous_step
        FOREIGN KEY (previous_step_id)
        REFERENCES procurement_workflow_steps(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_next_step
        FOREIGN KEY (next_step_id)
        REFERENCES procurement_workflow_steps(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_request_workflows_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);




-- SUPPLIERS INFORMATIONS
CREATE TABLE supplier_tenders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    tender_no VARCHAR(50) NOT NULL UNIQUE,

    purchase_request_id BIGINT UNSIGNED NULL,

    tender_title VARCHAR(255) NOT NULL,
    description TEXT NULL,

    tender_date DATE NOT NULL,
    closing_date DATE NULL,

    tender_status VARCHAR(50) DEFAULT 'Draft',

    evaluation_method VARCHAR(100) NULL,

    estimated_budget DECIMAL(18,2) DEFAULT 0.00,

    currency_code VARCHAR(10) DEFAULT 'TZS',

    awarded_supplier_id BIGINT UNSIGNED NULL,

    awarded_at TIMESTAMP NULL,

    award_comment TEXT NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_supplier_tenders_request
        FOREIGN KEY (purchase_request_id)
        REFERENCES purchase_requests(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_tenders_supplier
        FOREIGN KEY (awarded_supplier_id)
        REFERENCES suppliers(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_tenders_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_tenders_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_tenders_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_tenders_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_tenders_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_tenders_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE supplier_tender_invitations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    tender_id BIGINT UNSIGNED NOT NULL,

    supplier_id BIGINT UNSIGNED NOT NULL,

    invitation_date DATE NULL,

    response_deadline DATE NULL,

    invitation_status VARCHAR(50) DEFAULT 'Invited',

    responded_at TIMESTAMP NULL,

    response_status VARCHAR(50) DEFAULT 'Pending',

    comment TEXT NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_tender_invitations_tender
        FOREIGN KEY (tender_id)
        REFERENCES supplier_tenders(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_tender_invitations_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_tender_invitations_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_tender_invitations_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_tender_invitations_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_tender_invitations_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_tender_invitations_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_tender_invitations_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE supplier_quotations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    quotation_no VARCHAR(50) NOT NULL UNIQUE,

    tender_id BIGINT UNSIGNED NULL,

    purchase_request_id BIGINT UNSIGNED NULL,

    supplier_id BIGINT UNSIGNED NOT NULL,

    supplier_quotation_no VARCHAR(100) NULL,

    quotation_date DATE NOT NULL,

    valid_until DATE NULL,

    currency_code VARCHAR(10) DEFAULT 'TZS',

    subtotal DECIMAL(18,2) DEFAULT 0.00,
    tax_amount DECIMAL(18,2) DEFAULT 0.00,
    discount_amount DECIMAL(18,2) DEFAULT 0.00,
    delivery_amount DECIMAL(18,2) DEFAULT 0.00,
    total_amount DECIMAL(18,2) DEFAULT 0.00,

    quotation_status VARCHAR(50) DEFAULT 'Submitted',

    payment_terms TEXT NULL,
    delivery_terms TEXT NULL,

    notes TEXT NULL,

    attachment_path VARCHAR(500) NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_supplier_quotations_tender
        FOREIGN KEY (tender_id)
        REFERENCES supplier_tenders(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_quotations_request
        FOREIGN KEY (purchase_request_id)
        REFERENCES purchase_requests(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_quotations_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_quotations_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_quotations_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_quotations_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_quotations_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_quotations_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_quotations_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE supplier_quotation_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    supplier_quotation_id BIGINT UNSIGNED NOT NULL,

    purchase_request_item_id BIGINT UNSIGNED NULL,

    product_id BIGINT UNSIGNED NOT NULL,

    unit_of_measure_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    quantity DECIMAL(18,3) NOT NULL DEFAULT 0.000,

    unit_price DECIMAL(18,2) DEFAULT 0.00,

    discount_amount DECIMAL(18,2) DEFAULT 0.00,

    tax_rate DECIMAL(5,2) DEFAULT 0.00,

    tax_amount DECIMAL(18,2) DEFAULT 0.00,

    total_amount DECIMAL(18,2) DEFAULT 0.00,

    delivery_date DATE NULL,

    item_status VARCHAR(50) DEFAULT 'Pending',

    remarks TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_quotation_items_quotation
        FOREIGN KEY (supplier_quotation_id)
        REFERENCES supplier_quotations(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_quotation_items_request_item
        FOREIGN KEY (purchase_request_item_id)
        REFERENCES purchase_request_items(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_quotation_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_quotation_items_uom
        FOREIGN KEY (unit_of_measure_id)
        REFERENCES units_of_measure(id)
        ON DELETE SET NULL
);


CREATE TABLE quotation_evaluations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    supplier_quotation_id BIGINT UNSIGNED NOT NULL,

    evaluation_no VARCHAR(50) NOT NULL UNIQUE,

    technical_score DECIMAL(8,2) DEFAULT 0.00,
    price_score DECIMAL(8,2) DEFAULT 0.00,
    delivery_score DECIMAL(8,2) DEFAULT 0.00,
    quality_score DECIMAL(8,2) DEFAULT 0.00,

    total_score DECIMAL(8,2) DEFAULT 0.00,

    evaluation_status VARCHAR(50) DEFAULT 'Pending',

    recommendation VARCHAR(50) NULL,

    evaluation_comment TEXT NULL,

    evaluated_by BIGINT UNSIGNED NULL,
    evaluated_at TIMESTAMP NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_quotation_evaluations_quotation
        FOREIGN KEY (supplier_quotation_id)
        REFERENCES supplier_quotations(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_quotation_evaluations_evaluator
        FOREIGN KEY (evaluated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_quotation_evaluations_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_quotation_evaluations_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_quotation_evaluations_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_quotation_evaluations_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_quotation_evaluations_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_quotation_evaluations_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE supplier_awards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    award_no VARCHAR(50) NOT NULL UNIQUE,

    tender_id BIGINT UNSIGNED NOT NULL,

    supplier_id BIGINT UNSIGNED NOT NULL,

    supplier_quotation_id BIGINT UNSIGNED NOT NULL,

    purchase_request_id BIGINT UNSIGNED NULL,

    award_date DATE NOT NULL,

    award_amount DECIMAL(18,2) DEFAULT 0.00,

    currency_code VARCHAR(10) DEFAULT 'TZS',

    award_status VARCHAR(50) DEFAULT 'Pending',

    award_comment TEXT NULL,

    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_supplier_awards_tender
        FOREIGN KEY (tender_id)
        REFERENCES supplier_tenders(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_awards_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_awards_quotation
        FOREIGN KEY (supplier_quotation_id)
        REFERENCES supplier_quotations(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_awards_request
        FOREIGN KEY (purchase_request_id)
        REFERENCES purchase_requests(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_awards_approved_by
        FOREIGN KEY (approved_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_awards_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_awards_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_awards_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_awards_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_awards_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_awards_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE purchase_orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    purchase_order_no VARCHAR(50) NOT NULL UNIQUE,

    purchase_request_id BIGINT UNSIGNED NULL,

    supplier_award_id BIGINT UNSIGNED NULL,

    supplier_id BIGINT UNSIGNED NOT NULL,

    order_date DATE NOT NULL,

    expected_delivery_date DATE NULL,

    currency_code VARCHAR(10) DEFAULT 'TZS',

    subtotal DECIMAL(18,2) DEFAULT 0.00,
    tax_amount DECIMAL(18,2) DEFAULT 0.00,
    discount_amount DECIMAL(18,2) DEFAULT 0.00,
    total_amount DECIMAL(18,2) DEFAULT 0.00,

    order_status VARCHAR(50) DEFAULT 'Draft',

    payment_terms TEXT NULL,
    delivery_terms TEXT NULL,

    notes TEXT NULL,

    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_purchase_orders_request
        FOREIGN KEY (purchase_request_id)
        REFERENCES purchase_requests(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_award
        FOREIGN KEY (supplier_award_id)
        REFERENCES supplier_awards(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_purchase_orders_approved_by
        FOREIGN KEY (approved_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_orders_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE purchase_order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    purchase_order_id BIGINT UNSIGNED NOT NULL,

    supplier_quotation_item_id BIGINT UNSIGNED NULL,

    product_id BIGINT UNSIGNED NOT NULL,

    unit_of_measure_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    ordered_quantity DECIMAL(18,3) NOT NULL DEFAULT 0.000,

    unit_price DECIMAL(18,2) DEFAULT 0.00,

    tax_rate DECIMAL(5,2) DEFAULT 0.00,

    tax_amount DECIMAL(18,2) DEFAULT 0.00,

    discount_amount DECIMAL(18,2) DEFAULT 0.00,

    total_amount DECIMAL(18,2) DEFAULT 0.00,

    received_quantity DECIMAL(18,3) DEFAULT 0.000,

    pending_quantity DECIMAL(18,3) DEFAULT 0.000,

    item_status VARCHAR(50) DEFAULT 'Pending',

    remarks TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_purchase_order_items_order
        FOREIGN KEY (purchase_order_id)
        REFERENCES purchase_orders(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_purchase_order_items_quotation
        FOREIGN KEY (supplier_quotation_item_id)
        REFERENCES supplier_quotation_items(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_purchase_order_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_purchase_order_items_uom
        FOREIGN KEY (unit_of_measure_id)
        REFERENCES units_of_measure(id)
        ON DELETE SET NULL
);


CREATE TABLE goods_receipts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    receipt_no VARCHAR(50) NOT NULL UNIQUE,

    purchase_order_id BIGINT UNSIGNED NOT NULL,

    supplier_id BIGINT UNSIGNED NOT NULL,

    receipt_date DATE NOT NULL,

    supplier_delivery_note VARCHAR(100) NULL,

    vehicle_no VARCHAR(50) NULL,

    received_by BIGINT UNSIGNED NULL,

    inspection_status VARCHAR(50) DEFAULT 'Pending',

    receipt_status VARCHAR(50) DEFAULT 'Pending',

    remarks TEXT NULL,

    attachment_path VARCHAR(500) NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_goods_receipts_order
        FOREIGN KEY (purchase_order_id)
        REFERENCES purchase_orders(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_goods_receipts_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_goods_receipts_received_by
        FOREIGN KEY (received_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_goods_receipts_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_goods_receipts_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_goods_receipts_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_goods_receipts_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_goods_receipts_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_goods_receipts_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE goods_receipt_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    goods_receipt_id BIGINT UNSIGNED NOT NULL,

    purchase_order_item_id BIGINT UNSIGNED NOT NULL,

    product_id BIGINT UNSIGNED NOT NULL,

    unit_of_measure_id BIGINT UNSIGNED NULL,

    ordered_quantity DECIMAL(18,3) DEFAULT 0.000,

    received_quantity DECIMAL(18,3) DEFAULT 0.000,

    accepted_quantity DECIMAL(18,3) DEFAULT 0.000,

    rejected_quantity DECIMAL(18,3) DEFAULT 0.000,

    rejection_reason TEXT NULL,

    item_status VARCHAR(50) DEFAULT 'Accepted',

    remarks TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_goods_receipt_items_receipt
        FOREIGN KEY (goods_receipt_id)
        REFERENCES goods_receipts(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_goods_receipt_items_order_item
        FOREIGN KEY (purchase_order_item_id)
        REFERENCES purchase_order_items(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_goods_receipt_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_goods_receipt_items_uom
        FOREIGN KEY (unit_of_measure_id)
        REFERENCES units_of_measure(id)
        ON DELETE SET NULL
);

CREATE TABLE supplier_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice_no VARCHAR(50) NOT NULL UNIQUE,

    supplier_invoice_no VARCHAR(100) NOT NULL,

    supplier_id BIGINT UNSIGNED NOT NULL,

    purchase_order_id BIGINT UNSIGNED NULL,

    goods_receipt_id BIGINT UNSIGNED NULL,

    invoice_date DATE NOT NULL,

    due_date DATE NULL,

    currency_code VARCHAR(10) DEFAULT 'TZS',

    subtotal DECIMAL(18,2) DEFAULT 0.00,

    tax_amount DECIMAL(18,2) DEFAULT 0.00,

    discount_amount DECIMAL(18,2) DEFAULT 0.00,

    total_amount DECIMAL(18,2) DEFAULT 0.00,

    paid_amount DECIMAL(18,2) DEFAULT 0.00,

    outstanding_amount DECIMAL(18,2) DEFAULT 0.00,

    invoice_status VARCHAR(50) DEFAULT 'Pending',

    verification_status VARCHAR(50) DEFAULT 'Pending',

    verified_by BIGINT UNSIGNED NULL,

    verified_at TIMESTAMP NULL,

    notes TEXT NULL,

    attachment_path VARCHAR(500) NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_supplier_invoices_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_invoices_order
        FOREIGN KEY (purchase_order_id)
        REFERENCES purchase_orders(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_receipt
        FOREIGN KEY (goods_receipt_id)
        REFERENCES goods_receipts(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_verified_by
        FOREIGN KEY (verified_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoices_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE supplier_invoice_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    supplier_invoice_id BIGINT UNSIGNED NOT NULL,

    purchase_order_item_id BIGINT UNSIGNED NULL,

    goods_receipt_item_id BIGINT UNSIGNED NULL,

    product_id BIGINT UNSIGNED NOT NULL,

    unit_of_measure_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    quantity DECIMAL(18,3) DEFAULT 0.000,

    unit_price DECIMAL(18,2) DEFAULT 0.00,

    tax_rate DECIMAL(5,2) DEFAULT 0.00,

    tax_amount DECIMAL(18,2) DEFAULT 0.00,

    discount_amount DECIMAL(18,2) DEFAULT 0.00,

    total_amount DECIMAL(18,2) DEFAULT 0.00,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_supplier_invoice_items_invoice
        FOREIGN KEY (supplier_invoice_id)
        REFERENCES supplier_invoices(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_supplier_invoice_items_order_item
        FOREIGN KEY (purchase_order_item_id)
        REFERENCES purchase_order_items(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoice_items_receipt_item
        FOREIGN KEY (goods_receipt_item_id)
        REFERENCES goods_receipt_items(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_invoice_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_invoice_items_uom
        FOREIGN KEY (unit_of_measure_id)
        REFERENCES units_of_measure(id)
        ON DELETE SET NULL
);

CREATE TABLE supplier_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    payment_no VARCHAR(50) NOT NULL UNIQUE,

    supplier_id BIGINT UNSIGNED NOT NULL,

    supplier_invoice_id BIGINT UNSIGNED NOT NULL,

    payment_date DATE NOT NULL,

    payment_amount DECIMAL(18,2) NOT NULL DEFAULT 0.00,

    currency_code VARCHAR(10) DEFAULT 'TZS',

    payment_method VARCHAR(50) NULL,

    payment_reference VARCHAR(100) NULL,

    bank_account VARCHAR(100) NULL,

    cheque_no VARCHAR(100) NULL,

    payment_status VARCHAR(50) DEFAULT 'Pending',

    approval_status VARCHAR(50) DEFAULT 'Pending',

    requested_by BIGINT UNSIGNED NULL,

    approved_by BIGINT UNSIGNED NULL,

    approved_at TIMESTAMP NULL,

    paid_by BIGINT UNSIGNED NULL,

    paid_at TIMESTAMP NULL,

    comment TEXT NULL,

    attachment_path VARCHAR(500) NULL,

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

    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_supplier_payments_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_payments_invoice
        FOREIGN KEY (supplier_invoice_id)
        REFERENCES supplier_invoices(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_supplier_payments_requested_by
        FOREIGN KEY (requested_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_approved_by
        FOREIGN KEY (approved_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_paid_by
        FOREIGN KEY (paid_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_updated_by
        FOREIGN KEY (updated_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_supplier_payments_deleted_by
        FOREIGN KEY (deleted_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);


INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

-- =====================================================
-- SUPPLIER TENDERS
-- =====================================================
('Supplier Tenders Menu', 'view-supplier-tenders-menu', 'Supplier Tenders Menu', 'Active', NOW(), NOW()),
('View Supplier Tenders', 'view-supplier-tenders', 'View Supplier Tender Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Supplier Tenders', 'crud-supplier-tenders', 'Create, Update or Delete Supplier Tender Records', 'Active', NOW(), NOW()),
('Publish Supplier Tenders', 'publish-supplier-tenders', 'Publish Supplier Tender Announcements', 'Active', NOW(), NOW()),
('Close Supplier Tenders', 'close-supplier-tenders', 'Close Supplier Tender Announcements', 'Active', NOW(), NOW()),
('Invite Suppliers to Tenders', 'invite-suppliers-to-tenders', 'Invite Suppliers to Participate in Tenders', 'Active', NOW(), NOW()),
('View Tender Invitations', 'view-tender-invitations', 'View Supplier Tender Invitations', 'Active', NOW(), NOW()),
('Manage Tender Invitations', 'manage-tender-invitations', 'Create, Update or Delete Supplier Tender Invitations', 'Active', NOW(), NOW()),
('Approve Supplier Tenders', 'approve-supplier-tenders', 'Approve Supplier Tender Records', 'Active', NOW(), NOW()),
('Restore Supplier Tenders', 'restore-supplier-tenders', 'Restore Deleted Supplier Tender Records', 'Active', NOW(), NOW()),


-- =====================================================
-- PURCHASE QUOTATIONS
-- =====================================================
('Purchase Quotations Menu', 'view-purchase-quotations-menu', 'Purchase Quotations Menu', 'Active', NOW(), NOW()),
('View Purchase Quotations', 'view-purchase-quotations', 'View Purchase Quotation Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Purchase Quotations', 'crud-purchase-quotations', 'Create, Update or Delete Purchase Quotation Records', 'Active', NOW(), NOW()),
('Submit Purchase Quotations', 'submit-purchase-quotations', 'Submit Supplier Purchase Quotations', 'Active', NOW(), NOW()),
('Approve Purchase Quotations', 'approve-purchase-quotations', 'Approve Purchase Quotation Records', 'Active', NOW(), NOW()),
('Evaluate Purchase Quotations', 'evaluate-purchase-quotations', 'Evaluate and Score Supplier Purchase Quotations', 'Active', NOW(), NOW()),
('View Quotation Evaluations', 'view-quotation-evaluations', 'View Purchase Quotation Evaluations', 'Active', NOW(), NOW()),
('Manage Quotation Evaluations', 'manage-quotation-evaluations', 'Create, Update or Delete Purchase Quotation Evaluations', 'Active', NOW(), NOW()),
('Restore Purchase Quotations', 'restore-purchase-quotations', 'Restore Deleted Purchase Quotation Records', 'Active', NOW(), NOW()),


-- =====================================================
-- SUPPLIER AWARDS
-- =====================================================
('Supplier Awards Menu', 'view-supplier-awards-menu', 'Supplier Awards Menu', 'Active', NOW(), NOW()),
('View Supplier Awards', 'view-supplier-awards', 'View Supplier Award Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Supplier Awards', 'crud-supplier-awards', 'Create, Update or Delete Supplier Award Records', 'Active', NOW(), NOW()),
('Approve Supplier Awards', 'approve-supplier-awards', 'Approve Supplier Award Records', 'Active', NOW(), NOW()),
('Award Supplier Tender', 'award-supplier-tender', 'Award a Tender to a Selected Supplier', 'Active', NOW(), NOW()),
('Cancel Supplier Awards', 'cancel-supplier-awards', 'Cancel Supplier Award Records', 'Active', NOW(), NOW()),
('Restore Supplier Awards', 'restore-supplier-awards', 'Restore Deleted Supplier Award Records', 'Active', NOW(), NOW()),



-- =====================================================
-- SUPPLIER INVOICES
-- =====================================================
('Supplier Invoices Menu', 'view-supplier-invoices-menu', 'Supplier Invoices Menu', 'Active', NOW(), NOW()),
('View Supplier Invoices', 'view-supplier-invoices', 'View Supplier Invoice Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Supplier Invoices', 'crud-supplier-invoices', 'Create, Update or Delete Supplier Invoice Records', 'Active', NOW(), NOW()),
('Submit Supplier Invoices', 'submit-supplier-invoices', 'Submit Supplier Invoices for Verification', 'Active', NOW(), NOW()),
('Verify Supplier Invoices', 'verify-supplier-invoices', 'Verify Supplier Invoice Records Against Purchase Documents', 'Active', NOW(), NOW()),
('Approve Supplier Invoices', 'approve-supplier-invoices', 'Approve Supplier Invoice Records', 'Active', NOW(), NOW()),
('Reject Supplier Invoices', 'reject-supplier-invoices', 'Reject Supplier Invoice Records', 'Active', NOW(), NOW()),
('Print Supplier Invoices', 'print-supplier-invoices', 'Print Supplier Invoice Records', 'Active', NOW(), NOW()),
('Restore Supplier Invoices', 'restore-supplier-invoices', 'Restore Deleted Supplier Invoice Records', 'Active', NOW(), NOW()),


-- =====================================================
-- SUPPLIER PAYMENTS
-- =====================================================
('Supplier Payments Menu', 'view-supplier-payments-menu', 'Supplier Payments Menu', 'Active', NOW(), NOW()),
('View Supplier Payments', 'view-supplier-payments', 'View Supplier Payment Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Supplier Payments', 'crud-supplier-payments', 'Create, Update or Delete Supplier Payment Records', 'Active', NOW(), NOW()),
('Request Supplier Payments', 'request-supplier-payments', 'Create and Submit Supplier Payment Requests', 'Active', NOW(), NOW()),
('Approve Supplier Payments', 'approve-supplier-payments', 'Approve Supplier Payment Requests', 'Active', NOW(), NOW()),
('Process Supplier Payments', 'process-supplier-payments', 'Process Approved Supplier Payments', 'Active', NOW(), NOW()),
('Reject Supplier Payments', 'reject-supplier-payments', 'Reject Supplier Payment Requests', 'Active', NOW(), NOW()),
('Cancel Supplier Payments', 'cancel-supplier-payments', 'Cancel Supplier Payment Records', 'Active', NOW(), NOW()),
('Print Supplier Payment Receipts', 'print-supplier-payment-receipts', 'Print Supplier Payment Receipts', 'Active', NOW(), NOW()),
('Restore Supplier Payments', 'restore-supplier-payments', 'Restore Deleted Supplier Payment Records', 'Active', NOW(), NOW());


ALTER TABLE goods_receipts
ADD COLUMN invoiced_status VARCHAR(50) NOT NULL DEFAULT 'Uninvoiced'
AFTER receipt_status;

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES

-- =====================================================
-- PURCHASE REQUEST APPROVAL: GENERAL ACCESS
-- =====================================================
('Purchase Requests Menu', 'view-purchase-requests-menu', 'Access the Purchase Requests Menu', 'Active', NOW(), NOW()),
('View Purchase Requests', 'view-purchase-requests', 'View Purchase Request Records', 'Active', NOW(), NOW()),

-- =====================================================
-- LEVEL 1 APPROVAL
-- =====================================================
('View Purchase Request Level 1', 'view-purchase-request-level-1', 'View Purchase Requests Awaiting Level 1 Approval', 'Active', NOW(), NOW()),
('Approve Purchase Request Level 1', 'approve-purchase-request-level-1', 'Approve Purchase Requests at Workflow Level 1', 'Active', NOW(), NOW()),
('Reject Purchase Request Level 1', 'reject-purchase-request-level-1', 'Reject Purchase Requests at Workflow Level 1', 'Active', NOW(), NOW()),

-- =====================================================
-- LEVEL 2 APPROVAL
-- =====================================================
('View Purchase Request Level 2', 'view-purchase-request-level-2', 'View Purchase Requests Awaiting Level 2 Approval', 'Active', NOW(), NOW()),
('Approve Purchase Request Level 2', 'approve-purchase-request-level-2', 'Approve Purchase Requests at Workflow Level 2', 'Active', NOW(), NOW()),
('Reject Purchase Request Level 2', 'reject-purchase-request-level-2', 'Reject Purchase Requests at Workflow Level 2', 'Active', NOW(), NOW()),

-- =====================================================
-- LEVEL 3 APPROVAL
-- =====================================================
('View Purchase Request Level 3', 'view-purchase-request-level-3', 'View Purchase Requests Awaiting Level 3 Approval', 'Active', NOW(), NOW()),
('Approve Purchase Request Level 3', 'approve-purchase-request-level-3', 'Approve Purchase Requests at Workflow Level 3', 'Active', NOW(), NOW()),
('Reject Purchase Request Level 3', 'reject-purchase-request-level-3', 'Reject Purchase Requests at Workflow Level 3', 'Active', NOW(), NOW()),

-- =====================================================
-- LEVEL 4 APPROVAL
-- =====================================================
('View Purchase Request Level 4', 'view-purchase-request-level-4', 'View Purchase Requests Awaiting Level 4 Approval', 'Active', NOW(), NOW()),
('Approve Purchase Request Level 4', 'approve-purchase-request-level-4', 'Approve Purchase Requests at Workflow Level 4', 'Active', NOW(), NOW()),
('Reject Purchase Request Level 4', 'reject-purchase-request-level-4', 'Reject Purchase Requests at Workflow Level 4', 'Active', NOW(), NOW()),

-- =====================================================
-- LEVEL 5 APPROVAL
-- =====================================================
('View Purchase Request Level 5', 'view-purchase-request-level-5', 'View Purchase Requests Awaiting Level 5 Approval', 'Active', NOW(), NOW()),
('Approve Purchase Request Level 5', 'approve-purchase-request-level-5', 'Approve Purchase Requests at Workflow Level 5', 'Active', NOW(), NOW()),
('Reject Purchase Request Level 5', 'reject-purchase-request-level-5', 'Reject Purchase Requests at Workflow Level 5', 'Active', NOW(), NOW()),

-- =====================================================
-- LEVEL 6 APPROVAL
-- =====================================================
('View Purchase Request Level 6', 'view-purchase-request-level-6', 'View Purchase Requests Awaiting Level 6 Approval', 'Active', NOW(), NOW()),
('Approve Purchase Request Level 6', 'approve-purchase-request-level-6', 'Approve Purchase Requests at Workflow Level 6', 'Active', NOW(), NOW()),
('Reject Purchase Request Level 6', 'reject-purchase-request-level-6', 'Reject Purchase Requests at Workflow Level 6', 'Active', NOW(), NOW());


ALTER TABLE procurement_workflow_steps
ADD COLUMN approval_permission VARCHAR(150) NULL
AFTER assigned_role;

ALTER TABLE procurement_workflow_steps
    ADD COLUMN view_permission VARCHAR(150) NULL
        AFTER approval_permission,
    ADD COLUMN rejection_permission VARCHAR(150) NULL
        AFTER view_permission;