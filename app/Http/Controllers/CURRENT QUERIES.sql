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





-- TAREHE 09/10/2026
-- SALES RECORDS
USE `iflsgdb`;

-- ============================================================
-- 1. SALES TYPES
-- ============================================================
CREATE TABLE IF NOT EXISTS `sales_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `type_code` VARCHAR(50) NOT NULL UNIQUE,
    `type_name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `requires_customer` TINYINT(1) DEFAULT 0,
    `requires_employee` TINYINT(1) DEFAULT 0,
    `default_payment_terms` ENUM('IMMEDIATE','CREDIT','COMPANY_ACCOUNT')
        DEFAULT 'IMMEDIATE',
    `allows_discount` TINYINT(1) DEFAULT 1,
    `display_order` INT DEFAULT 0,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT fk_sales_types_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_types_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_types_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_types_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_types_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_types_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 2. SALES CUSTOMERS
-- Companies and individual customers
-- ============================================================
CREATE TABLE IF NOT EXISTS `sales_customers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `customer_code` VARCHAR(50) NOT NULL UNIQUE,
    `customer_name` VARCHAR(200) NOT NULL,
    `customer_type` ENUM('COMPANY','INDIVIDUAL','WALK_IN')
        DEFAULT 'INDIVIDUAL',
    `billing_company_id` BIGINT UNSIGNED NULL,
    `contact_person` VARCHAR(150) NULL,
    `phone` VARCHAR(30) NULL,
    `email` VARCHAR(150) NULL,
    `address` TEXT NULL,
    `credit_limit` DECIMAL(18,2) DEFAULT 0.00,
    `payment_terms_days` INT DEFAULT 0,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT fk_sales_customers_billing_company
        FOREIGN KEY (`billing_company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_customers_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_customers_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_customers_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_customers_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_customers_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_customers_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 3. STAFF MEAL SHIFTS
-- ============================================================
CREATE TABLE IF NOT EXISTS `staff_meal_shifts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `shift_code` VARCHAR(30) NOT NULL,
    `shift_name` VARCHAR(100) NOT NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `display_order` INT DEFAULT 0,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY uq_staff_meal_shift_code (`company_id`, `branch_id`, `shift_code`),

    CONSTRAINT fk_staff_meal_shifts_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_shifts_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_shifts_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_shifts_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_shifts_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_shifts_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 4. STAFF MEAL RATES
-- Company-controlled meal prices with effective dates
-- ============================================================
CREATE TABLE IF NOT EXISTS `staff_meal_rates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `meal_type` VARCHAR(50) NOT NULL,
    `shift_id` BIGINT UNSIGNED NULL,
    `unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `effective_from` DATE NOT NULL,
    `effective_until` DATE NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY idx_staff_meal_rates_lookup
        (`company_id`, `branch_id`, `meal_type`, `effective_from`),

    CONSTRAINT fk_staff_meal_rates_shift
        FOREIGN KEY (`shift_id`) REFERENCES `staff_meal_shifts`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_rates_approved_by
        FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_rates_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_rates_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_rates_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_rates_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_rates_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_rates_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 5. SALES BILLING PERIODS
-- Weekly, monthly or custom company billing periods
-- ============================================================
CREATE TABLE IF NOT EXISTS `sales_billing_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `period_name` VARCHAR(150) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `billing_frequency` ENUM('WEEKLY','MONTHLY','CUSTOM')
        DEFAULT 'MONTHLY',
    `closed_at` TIMESTAMP NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Open',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY idx_sales_billing_period_dates (`company_id`, `start_date`, `end_date`),

    CONSTRAINT fk_sales_billing_periods_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_billing_periods_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_billing_periods_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_billing_periods_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_billing_periods_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_billing_periods_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





-- ============================================================
-- 6. SALES
-- Main sales transaction header
-- ============================================================
CREATE TABLE IF NOT EXISTS `sales` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `sale_no` VARCHAR(50) NOT NULL UNIQUE,
    `sale_date` DATETIME NOT NULL,
    `sales_type_id` BIGINT UNSIGNED NULL,
    `customer_id` BIGINT UNSIGNED NULL,
    `billing_period_id` BIGINT UNSIGNED NULL,
    `cashier_id` BIGINT UNSIGNED NULL,
    `payment_terms` ENUM('IMMEDIATE','CREDIT','COMPANY_ACCOUNT')
        DEFAULT 'IMMEDIATE',
    `payment_status` ENUM('UNPAID','PARTIAL','PAID')
        DEFAULT 'UNPAID',
    `subtotal` DECIMAL(18,2) DEFAULT 0.00,
    `discount_amount` DECIMAL(18,2) DEFAULT 0.00,
    `tax_amount` DECIMAL(18,2) DEFAULT 0.00,
    `total_amount` DECIMAL(18,2) DEFAULT 0.00,
    `paid_amount` DECIMAL(18,2) DEFAULT 0.00,
    `balance_amount` DECIMAL(18,2) DEFAULT 0.00,
    `currency_code` VARCHAR(10) DEFAULT 'TZS',
    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Draft',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY idx_sales_date (`sale_date`),
    KEY idx_sales_status (`Status`, `payment_status`),

    CONSTRAINT fk_sales_type
        FOREIGN KEY (`sales_type_id`) REFERENCES `sales_types`(`id`),
    CONSTRAINT fk_sales_customer
        FOREIGN KEY (`customer_id`) REFERENCES `sales_customers`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_billing_period
        FOREIGN KEY (`billing_period_id`) REFERENCES `sales_billing_periods`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_cashier
        FOREIGN KEY (`cashier_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 7. SALE ITEMS
-- Products sold on each transaction
-- ============================================================
CREATE TABLE IF NOT EXISTS `sale_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `sale_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` DECIMAL(18,3) NOT NULL DEFAULT 1.000,
    `unit_of_measure_id` BIGINT UNSIGNED NULL,
    `unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `discount_amount` DECIMAL(18,2) DEFAULT 0.00,
    `line_total` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY idx_sale_items_sale (`sale_id`),

    CONSTRAINT fk_sale_items_sale
        FOREIGN KEY (`sale_id`) REFERENCES `sales`(`id`),
    CONSTRAINT fk_sale_items_product
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    CONSTRAINT fk_sale_items_uom
        FOREIGN KEY (`unit_of_measure_id`) REFERENCES `units_of_measure`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_items_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_items_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_items_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_items_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_items_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_items_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 8. SALE PAYMENTS
-- Multiple payments can be made against one sale
-- ============================================================
CREATE TABLE IF NOT EXISTS `sale_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `payment_no` VARCHAR(50) NOT NULL UNIQUE,
    `sale_id` BIGINT UNSIGNED NOT NULL,
    `payment_date` DATETIME NOT NULL,
    `amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `payment_method_id` BIGINT UNSIGNED NULL,
    `reference_transaction` VARCHAR(150) NULL,
    `received_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY idx_sale_payments_sale (`sale_id`),

    CONSTRAINT fk_sale_payments_sale
        FOREIGN KEY (`sale_id`) REFERENCES `sales`(`id`),
    CONSTRAINT fk_sale_payments_received_by
        FOREIGN KEY (`received_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_payments_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_payments_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_payments_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_payments_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_payments_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sale_payments_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 9. STAFF MEAL SHIFT RECORDS
-- One consolidated count for each shift/session
-- ============================================================
CREATE TABLE IF NOT EXISTS `staff_meal_shift_records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `record_no` VARCHAR(50) NOT NULL UNIQUE,
    `service_date` DATE NOT NULL,
    `shift_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NULL,
    `meal_type` VARCHAR(50) NOT NULL,
    `meal_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `billing_period_id` BIGINT UNSIGNED NULL,
    `confirmed_by` BIGINT UNSIGNED NULL,
    `confirmed_at` TIMESTAMP NULL,
    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Draft',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY uq_staff_meal_shift_count
        (`company_id`, `branch_id`, `service_date`, `shift_id`, `meal_type`),
    KEY idx_staff_meal_billing (`billing_period_id`, `Status`),

    CONSTRAINT fk_staff_meal_records_shift
        FOREIGN KEY (`shift_id`) REFERENCES `staff_meal_shifts`(`id`),
    CONSTRAINT `fk_staff_meal_shift_items_product`
        FOREIGN KEY (`product_id`)
        REFERENCES `products` (`id`),
    CONSTRAINT fk_staff_meal_records_period
        FOREIGN KEY (`billing_period_id`) REFERENCES `sales_billing_periods`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_records_confirmed_by
        FOREIGN KEY (`confirmed_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_records_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_records_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_records_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_records_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_records_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_staff_meal_records_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 10. COMPANY CONSUMPTION RECORDS
-- Management tea and other company-sponsored consumption
-- ============================================================
CREATE TABLE IF NOT EXISTS `company_consumption_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `staff_meal_shift_record_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,

    `quantity` DECIMAL(18,3) NOT NULL DEFAULT 1.000,
    `unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `fk_company_consumption_items_record`
        FOREIGN KEY (`staff_meal_shift_record_id`)
        REFERENCES `staff_meal_shift_records` (`id`),

    CONSTRAINT `fk_company_consumption_items_product`
        FOREIGN KEY (`product_id`)
        REFERENCES `products` (`id`),

    CONSTRAINT `fk_company_consumption_items_company`
        FOREIGN KEY (`company_id`)
        REFERENCES `companies` (`id`)
        ON DELETE SET NULL,

    CONSTRAINT `fk_company_consumption_items_branch`
        FOREIGN KEY (`branch_id`)
        REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,

    CONSTRAINT `fk_company_consumption_items_user`
        FOREIGN KEY (`User_id`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL,

    CONSTRAINT `fk_company_consumption_items_created_by`
        FOREIGN KEY (`created_by`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL,

    CONSTRAINT `fk_company_consumption_items_updated_by`
        FOREIGN KEY (`updated_by`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL,

    CONSTRAINT `fk_company_consumption_items_deleted_by`
        FOREIGN KEY (`deleted_by`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 11. COMPANY BILLS
-- Consolidated invoices for company-sponsored consumption
-- ============================================================
CREATE TABLE IF NOT EXISTS `sales_company_bills` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `bill_no` VARCHAR(50) NOT NULL UNIQUE,
    `customer_id` BIGINT UNSIGNED NULL,
    `billing_period_id` BIGINT UNSIGNED  NULL,
    `bill_date` DATE NOT NULL,
    `due_date` DATE NULL,
    `subtotal` DECIMAL(18,2) DEFAULT 0.00,
    `adjustments` DECIMAL(18,2) DEFAULT 0.00,
    `total_amount` DECIMAL(18,2) DEFAULT 0.00,
    `paid_amount` DECIMAL(18,2) DEFAULT 0.00,
    `balance_amount` DECIMAL(18,2) DEFAULT 0.00,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Draft',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY idx_sales_company_bills_period (`billing_period_id`),

    CONSTRAINT fk_sales_company_bills_customer
        FOREIGN KEY (`customer_id`) REFERENCES `sales_customers`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bills_period
        FOREIGN KEY (`billing_period_id`) REFERENCES `sales_billing_periods`(`id`),
    CONSTRAINT fk_sales_company_bills_approved_by
        FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bills_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bills_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bills_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bills_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bills_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bills_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- 12. COMPANY BILL ITEMS
-- Connects invoices to the source consumption records
-- ============================================================
CREATE TABLE IF NOT EXISTS `sales_company_bill_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,
    `company_bill_id` BIGINT UNSIGNED NOT NULL,
    `sale_id` BIGINT UNSIGNED NULL,
    `shift_record_id` BIGINT UNSIGNED NULL,
    `consumption_record_id` BIGINT UNSIGNED NULL,
    `description` VARCHAR(255) NULL,
    `quantity` DECIMAL(18,3) NOT NULL DEFAULT 1.000,
    `unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,
    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY idx_company_bill_items_bill (`company_bill_id`),

    CONSTRAINT fk_sales_company_bill_items_bill
        FOREIGN KEY (`company_bill_id`) REFERENCES `sales_company_bills`(`id`),
    CONSTRAINT fk_sales_company_bill_items_sale
        FOREIGN KEY (`sale_id`) REFERENCES `sales`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bill_items_shift
        FOREIGN KEY (`shift_record_id`) REFERENCES `staff_meal_shift_records`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bill_items_company
        FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bill_items_branch
        FOREIGN KEY (`branch_id`) REFERENCES `branchies`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bill_items_user
        FOREIGN KEY (`User_id`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bill_items_created_by
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bill_items_updated_by
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL,
    CONSTRAINT fk_sales_company_bill_items_deleted_by
        FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;








INSERT INTO permissions
    (name, slug, description, Status, created_at, updated_at)
VALUES

-- =====================================================
-- 1. SALES MANAGEMENT MENU
-- =====================================================
('Sales Management Menu', 'view-sales-management-menu',
 'Access the Sales Management Menu', 'Active', NOW(), NOW()),

('View Sales Dashboard', 'view-sales-dashboard',
 'View Sales Dashboard and Sales Statistics', 'Active', NOW(), NOW()),

('View All Sales Reports', 'view-sales-reports',
 'View Sales Reports and Summaries', 'Active', NOW(), NOW()),

('Export Sales Reports', 'export-sales-reports',
 'Export Sales Reports and Sales Data', 'Active', NOW(), NOW()),

-- =====================================================
-- 2. SALES TYPES
-- =====================================================
('Sales Types Menu', 'view-sales-types-menu',
 'Access the Sales Types Menu', 'Active', NOW(), NOW()),

('View Sales Types', 'view-sales-types',
 'View Sales Type Records', 'Active', NOW(), NOW()),

('Manage Sales Types', 'crud-sales-types',
 'Create, Update or Delete Sales Types', 'Active', NOW(), NOW()),

('Restore Sales Types', 'restore-sales-types',
 'Restore Deleted Sales Type Records', 'Active', NOW(), NOW()),

-- =====================================================
-- 3. SALES CUSTOMERS
-- =====================================================
('Sales Customers Menu', 'view-sales-customers-menu',
 'Access the Sales Customers Menu', 'Active', NOW(), NOW()),

('View Sales Customers', 'view-sales-customers',
 'View Sales Customer Records', 'Active', NOW(), NOW()),

('Manage Sales Customers', 'crud-sales-customers',
 'Create, Update or Delete Sales Customers', 'Active', NOW(), NOW()),

('View Customer Account Balances', 'view-customer-account-balances',
 'View Customer Outstanding Balances and Account Details', 'Active', NOW(), NOW()),

('Manage Customer Credit Limits', 'manage-customer-credit-limits',
 'Create or Update Customer Credit Limits and Payment Terms', 'Active', NOW(), NOW()),

('Restore Sales Customers', 'restore-sales-customers',
 'Restore Deleted Sales Customer Records', 'Active', NOW(), NOW()),

-- =====================================================
-- 4. SALES TRANSACTIONS
-- =====================================================
('Sales Transactions Menu', 'view-sales-transactions-menu',
 'Access the Sales Transactions Menu', 'Active', NOW(), NOW()),

('View Sales Transactions', 'view-sales',
 'View Sales Transaction Records', 'Active', NOW(), NOW()),

('Create Sales Transactions', 'create-sales',
 'Create New Sales Transactions', 'Active', NOW(), NOW()),

('Update Sales Transactions', 'update-sales',
 'Update Existing Sales Transactions', 'Active', NOW(), NOW()),

('Delete Sales Transactions', 'delete-sales',
 'Delete Sales Transaction Records', 'Active', NOW(), NOW()),

('Cancel Sales Transactions', 'cancel-sales',
 'Cancel Sales Transactions', 'Active', NOW(), NOW()),

('Approve Sales Transactions', 'approve-sales',
 'Approve Sales Transactions Where Approval Is Required', 'Active', NOW(), NOW()),

('View Sales Transaction Details', 'view-sale-items',
 'View Individual Product Items Within Sales Transactions', 'Active', NOW(), NOW()),

('Manage Sales Transaction Items', 'crud-sale-items',
 'Add, Update or Remove Product Items Within Sales Transactions', 'Active', NOW(), NOW()),

('Apply Sales Discounts', 'apply-sales-discounts',
 'Apply Discounts to Sales Transactions', 'Active', NOW(), NOW()),

('Restore Sales Transactions', 'restore-sales',
 'Restore Deleted Sales Transaction Records', 'Active', NOW(), NOW()),

-- =====================================================
-- 5. SALES PAYMENTS
-- =====================================================
('Sales Payments Menu', 'view-sales-payments-menu',
 'Access the Sales Payments Menu', 'Active', NOW(), NOW()),

('View Sales Payments', 'view-sales-payments',
 'View Sales Payment Records', 'Active', NOW(), NOW()),

('Record Sales Payments', 'create-sales-payments',
 'Record Payments Received Against Sales', 'Active', NOW(), NOW()),

('Update Sales Payments', 'update-sales-payments',
 'Update Sales Payment Records', 'Active', NOW(), NOW()),

('Delete Sales Payments', 'delete-sales-payments',
 'Delete Sales Payment Records', 'Active', NOW(), NOW()),

('Reverse Sales Payments', 'reverse-sales-payments',
 'Reverse or Void Recorded Sales Payments', 'Active', NOW(), NOW()),

('View Unpaid Sales', 'view-unpaid-sales',
 'View Sales Transactions With Outstanding Balances', 'Active', NOW(), NOW()),

('View Partially Paid Sales', 'view-partially-paid-sales',
 'View Sales Transactions With Partial Payments', 'Active', NOW(), NOW()),

('View Paid Sales', 'view-paid-sales',
 'View Fully Paid Sales Transactions', 'Active', NOW(), NOW()),

-- =====================================================
-- 6. STAFF MEAL SHIFTS
-- =====================================================
('Staff Meal Management Menu', 'view-staff-meal-management-menu',
 'Access Staff Meal Management', 'Active', NOW(), NOW()),

('View Staff Meal Shifts', 'view-staff-meal-shifts',
 'View Configured Staff Meal Shifts', 'Active', NOW(), NOW()),

('Manage Staff Meal Shifts', 'crud-staff-meal-shifts',
 'Create, Update or Delete Staff Meal Shifts', 'Active', NOW(), NOW()),

('Restore Staff Meal Shifts', 'restore-staff-meal-shifts',
 'Restore Deleted Staff Meal Shift Records', 'Active', NOW(), NOW()),

-- =====================================================
-- 7. STAFF MEAL RATES
-- =====================================================
('View Staff Meal Rates', 'view-staff-meal-rates',
 'View Staff Meal Prices and Effective Dates', 'Active', NOW(), NOW()),

('Manage Staff Meal Rates', 'crud-staff-meal-rates',
 'Create, Update or Delete Staff Meal Rates', 'Active', NOW(), NOW()),

('Approve Staff Meal Rates', 'approve-staff-meal-rates',
 'Approve Staff Meal Rates Before They Become Effective', 'Active', NOW(), NOW()),

('View Staff Meal Rate History', 'view-staff-meal-rate-history',
 'View Historical Staff Meal Rates and Changes', 'Active', NOW(), NOW()),

-- =====================================================
-- 8. STAFF MEAL SHIFT RECORDS
-- =====================================================
('View Staff Meal Shift Records', 'view-staff-meal-shift-records',
 'View Daily Staff Meal Counts Recorded Per Shift', 'Active', NOW(), NOW()),

('Create Staff Meal Shift Records', 'create-staff-meal-shift-records',
 'Record Staff Meal Counts for Morning, Noon and Night Shifts', 'Active', NOW(), NOW()),

('Update Staff Meal Shift Records', 'update-staff-meal-shift-records',
 'Update Existing Staff Meal Shift Counts Before Finalization', 'Active', NOW(), NOW()),

('Delete Staff Meal Shift Records', 'delete-staff-meal-shift-records',
 'Delete Staff Meal Shift Records', 'Active', NOW(), NOW()),

('Confirm Staff Meal Shift Records', 'confirm-staff-meal-shift-records',
 'Confirm and Finalize Staff Meal Counts for a Shift', 'Active', NOW(), NOW()),

('Reopen Staff Meal Shift Records', 'reopen-staff-meal-shift-records',
 'Reopen Previously Confirmed Staff Meal Shift Records', 'Active', NOW(), NOW()),

('Manage Staff Meal Shift Items', 'crud-staff-meal-shift-items',
 'Add, Update or Remove Products and Counts in Staff Meal Shifts', 'Active', NOW(), NOW()),

('View Staff Meal Consumption History', 'view-staff-meal-consumption-history',
 'View Historical Staff Meal Counts, Prices and Totals', 'Active', NOW(), NOW()),

-- =====================================================
-- 9. COMPANY CONSUMPTION
-- =====================================================
('Company Consumption Menu', 'view-company-consumption-menu',
 'Access Company-Sponsored Consumption Records', 'Active', NOW(), NOW()),

('View Company Consumption', 'view-company-consumption',
 'View Company-Sponsored Meals, Tea, Drinks and Other Consumption', 'Active', NOW(), NOW()),

('Create Company Consumption', 'create-company-consumption',
 'Record Company-Sponsored Meals, Tea, Drinks and Other Consumption', 'Active', NOW(), NOW()),

('Update Company Consumption', 'update-company-consumption',
 'Update Company Consumption Records', 'Active', NOW(), NOW()),

('Delete Company Consumption', 'delete-company-consumption',
 'Delete Company Consumption Records', 'Active', NOW(), NOW()),

('Manage Company Consumption Items', 'crud-company-consumption-items',
 'Add, Update or Remove Products in Company Consumption Records', 'Active', NOW(), NOW()),

('Approve Company Consumption', 'approve-company-consumption',
 'Approve Company-Sponsored Consumption Records', 'Active', NOW(), NOW()),

('Restore Company Consumption', 'restore-company-consumption',
 'Restore Deleted Company Consumption Records', 'Active', NOW(), NOW()),

-- =====================================================
-- 10. SALES BILLING PERIODS
-- =====================================================
('Sales Billing Periods Menu', 'view-sales-billing-periods-menu',
 'Access Sales Billing Periods', 'Active', NOW(), NOW()),

('View Sales Billing Periods', 'view-sales-billing-periods',
 'View Weekly, Monthly and Custom Sales Billing Periods', 'Active', NOW(), NOW()),

('Manage Sales Billing Periods', 'crud-sales-billing-periods',
 'Create, Update or Delete Sales Billing Periods', 'Active', NOW(), NOW()),

('Close Sales Billing Periods', 'close-sales-billing-periods',
 'Close Billing Periods to Prevent Further Uncontrolled Changes', 'Active', NOW(), NOW()),

('Reopen Sales Billing Periods', 'reopen-sales-billing-periods',
 'Reopen Previously Closed Sales Billing Periods', 'Active', NOW(), NOW()),

('View Billing Period Summaries', 'view-billing-period-summaries',
 'View Sales and Company Consumption Totals for a Billing Period', 'Active', NOW(), NOW()),

-- =====================================================
-- 11. COMPANY BILLS
-- =====================================================
('Company Billing Menu', 'view-sales-company-bills-menu',
 'Access Company Billing and Invoicing', 'Active', NOW(), NOW()),

('View Company Bills', 'view-sales-company-bills',
 'View Company Bills and Invoices', 'Active', NOW(), NOW()),

('Generate Company Bills', 'create-sales-company-bills',
 'Generate Bills From Sales, Staff Meals and Company Consumption', 'Active', NOW(), NOW()),

('Update Company Bills', 'update-sales-company-bills',
 'Update Company Bills Before Finalization', 'Active', NOW(), NOW()),

('Delete Company Bills', 'delete-sales-company-bills',
 'Delete Company Bills Where Permitted', 'Active', NOW(), NOW()),

('Approve Company Bills', 'approve-sales-company-bills',
 'Approve Company Bills Before Issuing Them', 'Active', NOW(), NOW()),

('Issue Company Bills', 'issue-sales-company-bills',
 'Finalize and Issue Company Bills to Customers or Departments', 'Active', NOW(), NOW()),

('View Company Bill Items', 'view-sales-company-bill-items',
 'View Product and Consumption Details Included in Company Bills', 'Active', NOW(), NOW()),

('Manage Company Bill Items', 'crud-sales-company-bill-items',
 'Add, Update or Remove Bill Items Before Finalization', 'Active', NOW(), NOW()),

('Record Company Bill Payments', 'record-sales-company-bill-payments',
 'Record Payments Against Company Bills', 'Active', NOW(), NOW()),

('View Outstanding Company Bills', 'view-outstanding-company-bills',
 'View Company Bills With Unpaid Balances', 'Active', NOW(), NOW()),

('View Overdue Company Bills', 'view-overdue-company-bills',
 'View Company Bills Past Their Due Dates', 'Active', NOW(), NOW()),

('Restore Company Bills', 'restore-sales-company-bills',
 'Restore Deleted Company Bill Records', 'Active', NOW(), NOW()),

-- =====================================================
-- 12. SALES REPORTS
-- =====================================================
('View Daily Sales Report', 'view-daily-sales-report',
 'View Daily Sales Totals and Transaction Details', 'Active', NOW(), NOW()),

('View Weekly Sales Report', 'view-weekly-sales-report',
 'View Weekly Sales Totals and Transaction Details', 'Active', NOW(), NOW()),

('View Monthly Sales Report', 'view-monthly-sales-report',
 'View Monthly Sales Totals and Transaction Details', 'Active', NOW(), NOW()),

('View Product Sales Report', 'view-product-sales-report',
 'View Sales Quantities and Revenue by Product', 'Active', NOW(), NOW()),

('View Sales Type Report', 'view-sales-type-report',
 'View Sales Totals by Sales Type', 'Active', NOW(), NOW()),

('View Cashier Sales Report', 'view-cashier-sales-report',
 'View Sales Transactions Grouped by Cashier', 'Active', NOW(), NOW()),

('View Sales Payment Report', 'view-sales-payment-report',
 'View Sales Payments by Date and Payment Method', 'Active', NOW(), NOW()),

('View Customer Sales Report', 'view-customer-sales-report',
 'View Sales and Outstanding Balances by Customer', 'Active', NOW(), NOW()),

('View Staff Meal Report', 'view-staff-meal-report',
 'View Staff Meal Counts and Costs by Date and Shift', 'Active', NOW(), NOW()),

('View Staff Meal Rate Report', 'view-staff-meal-rate-report',
 'View Staff Meal Rates and Historical Price Changes', 'Active', NOW(), NOW()),

('View Company Consumption Report', 'view-company-consumption-report',
 'View Company-Sponsored Consumption by Product and Period', 'Active', NOW(), NOW()),

('View Company Billing Report', 'view-company-billing-report',
 'View Company Bills, Payments and Outstanding Balances', 'Active', NOW(), NOW()),

('View Sales Profit Report', 'view-sales-profit-report',
 'View Sales Profitability Where Cost Data Is Available', 'Active', NOW(), NOW()),

-- =====================================================
-- 13. SALES DATA RESTORATION AND AUDIT
-- =====================================================
('View Sales Audit Trail', 'view-sales-audit-trail',
 'View Audit History for Sales Records and Changes', 'Active', NOW(), NOW()),

('Restore Sales Payments', 'restore-sales-payments',
 'Restore Deleted Sales Payment Records', 'Active', NOW(), NOW()),

('Restore Staff Meal Shift Records', 'restore-staff-meal-shift-records',
 'Restore Deleted Staff Meal Shift Records', 'Active', NOW(), NOW()),

('Restore Sales Billing Periods', 'restore-sales-billing-periods',
 'Restore Deleted Sales Billing Periods', 'Active', NOW(), NOW()),

('Restore Company Bill Items', 'restore-sales-company-bill-items',
 'Restore Deleted Company Bill Item Records', 'Active', NOW(), NOW());


 CREATE TABLE `billing_companies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `company_code` VARCHAR(50) NOT NULL,
    `company_name` VARCHAR(200) NOT NULL,
    `tin_number` VARCHAR(50) NULL,
    `vrn_number` VARCHAR(50) NULL,
    `registration_number` VARCHAR(80) NULL,
    `contact_person` VARCHAR(150) NULL,
    `phone` VARCHAR(30) NULL,
    `email` VARCHAR(150) NULL,
    `city` VARCHAR(100) NULL,
    `region` VARCHAR(100) NULL,
    `country` VARCHAR(100) NOT NULL DEFAULT 'Tanzania',
    `address` TEXT NULL,
    `credit_limit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `payment_terms_days` INT UNSIGNED NOT NULL DEFAULT 0,
    `Status` VARCHAR(20) NOT NULL DEFAULT 'Active',
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `billing_companies_company_code_unique` (`company_code`),
    KEY `billing_companies_company_name_index` (`company_name`),
    KEY `billing_companies_Status_index` (`Status`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `sales_customers`
    DROP FOREIGN KEY `fk_sales_customers_billing_company`;

ALTER TABLE `sales_customers`
    ADD CONSTRAINT `sales_customers_billing_company_id_foreign`
    FOREIGN KEY (`billing_company_id`)
    REFERENCES `billing_companies` (`id`)
    ON DELETE SET NULL
    ON UPDATE RESTRICT;


ALTER TABLE `branchies`

-- BANK INFORMATION
ADD COLUMN `bank_name` VARCHAR(150) NULL AFTER `email`,
ADD COLUMN `bank_branch` VARCHAR(150) NULL AFTER `bank_name`,
ADD COLUMN `bank_account_name` VARCHAR(200) NULL AFTER `bank_branch`,
ADD COLUMN `bank_account_number` VARCHAR(100) NULL AFTER `bank_account_name`,
ADD COLUMN `bank_swift_code` VARCHAR(50) NULL AFTER `bank_account_number`,

-- MOBILE MONEY INFORMATION
ADD COLUMN `mobile_money_provider` VARCHAR(100) NULL AFTER `bank_swift_code`,
ADD COLUMN `mobile_money_number` VARCHAR(30) NULL AFTER `mobile_money_provider`,
ADD COLUMN `mobile_money_account_name` VARCHAR(200) NULL AFTER `mobile_money_number`,

-- PAYMENT SETTINGS
ADD COLUMN `default_payment_method` VARCHAR(50) NULL DEFAULT 'CASH' AFTER `mobile_money_account_name`,
ADD COLUMN `default_currency` VARCHAR(10) NOT NULL DEFAULT 'TZS' AFTER `default_payment_method`,
ADD COLUMN `payment_reference_prefix` VARCHAR(50) NULL AFTER `default_currency`,

-- FINANCE CONTACT INFORMATION
ADD COLUMN `finance_contact_person` VARCHAR(150) NULL AFTER `payment_reference_prefix`,
ADD COLUMN `finance_phone` VARCHAR(30) NULL AFTER `finance_contact_person`,
ADD COLUMN `finance_email` VARCHAR(150) NULL AFTER `finance_phone`,

-- TAX INFORMATION
ADD COLUMN `tin_number` VARCHAR(50) NULL AFTER `finance_email`,
ADD COLUMN `vrn_number` VARCHAR(50) NULL AFTER `tin_number`;


ALTER TABLE `companies`

-- BANK INFORMATION
ADD COLUMN `bank_name` VARCHAR(150) NULL AFTER `email`,
ADD COLUMN `bank_branch` VARCHAR(150) NULL AFTER `bank_name`,
ADD COLUMN `bank_account_name` VARCHAR(200) NULL AFTER `bank_branch`,
ADD COLUMN `bank_account_number` VARCHAR(100) NULL AFTER `bank_account_name`,
ADD COLUMN `bank_swift_code` VARCHAR(50) NULL AFTER `bank_account_number`,

-- MOBILE MONEY INFORMATION
ADD COLUMN `mobile_money_provider` VARCHAR(100) NULL AFTER `bank_swift_code`,
ADD COLUMN `mobile_money_number` VARCHAR(30) NULL AFTER `mobile_money_provider`,
ADD COLUMN `mobile_money_account_name` VARCHAR(200) NULL AFTER `mobile_money_number`,

-- PAYMENT SETTINGS
ADD COLUMN `default_payment_method` VARCHAR(50) NULL DEFAULT 'CASH' AFTER `mobile_money_account_name`,
ADD COLUMN `default_currency` VARCHAR(10) NOT NULL DEFAULT 'TZS' AFTER `default_payment_method`,
ADD COLUMN `payment_reference_prefix` VARCHAR(50) NULL AFTER `default_currency`,

-- FINANCE CONTACT INFORMATION
ADD COLUMN `finance_contact_person` VARCHAR(150) NULL AFTER `payment_reference_prefix`,
ADD COLUMN `finance_phone` VARCHAR(30) NULL AFTER `finance_contact_person`,
ADD COLUMN `finance_email` VARCHAR(150) NULL AFTER `finance_phone`,

-- TAX INFORMATION
ADD COLUMN `tin_number` VARCHAR(50) NULL AFTER `finance_email`,
ADD COLUMN `vrn_number` VARCHAR(50) NULL AFTER `tin_number`;



CREATE TABLE `committees` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `committee_code` VARCHAR(50) NOT NULL,
    `committee_name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,

    `chairman_user_id` BIGINT UNSIGNED NULL,
    `secretary_user_id` BIGINT UNSIGNED NULL,

    `start_date` DATE NULL,
    `end_date` DATE NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    UNIQUE KEY `uq_committee_code`
        (`company_id`, `branch_id`, `committee_code`),

    KEY `idx_committees_chairman` (`chairman_user_id`),
    KEY `idx_committees_secretary` (`secretary_user_id`),

    CONSTRAINT `fk_committees_chairman`
        FOREIGN KEY (`chairman_user_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committees_secretary`
        FOREIGN KEY (`secretary_user_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committees_company`
        FOREIGN KEY (`company_id`)
        REFERENCES `companies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committees_branch`
        FOREIGN KEY (`branch_id`)
        REFERENCES `branchies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committees_user`
        FOREIGN KEY (`User_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `committee_positions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `position_code` VARCHAR(50) NOT NULL,
    `position_name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    UNIQUE KEY `uq_committee_position_code`
        (`company_id`, `branch_id`, `position_code`),

    CONSTRAINT `fk_committee_positions_company`
        FOREIGN KEY (`company_id`)
        REFERENCES `companies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committee_positions_branch`
        FOREIGN KEY (`branch_id`)
        REFERENCES `branchies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committee_positions_user`
        FOREIGN KEY (`User_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `committee_members` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `committee_id` BIGINT UNSIGNED NOT NULL,
    `member_id` BIGINT UNSIGNED NOT NULL,
    `committee_position_id` BIGINT UNSIGNED NOT NULL,

    `appointment_date` DATE NULL,
    `end_date` DATE NULL,
    `membership_status` VARCHAR(50) NOT NULL DEFAULT 'Active',
    `appointment_notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    UNIQUE KEY `uq_committee_member`
        (`committee_id`, `member_id`),

    KEY `idx_committee_members_position`
        (`committee_position_id`),

    KEY `idx_committee_members_status`
        (`membership_status`),

    CONSTRAINT `fk_committee_members_committee`
        FOREIGN KEY (`committee_id`)
        REFERENCES `committees` (`id`) ON DELETE RESTRICT,

    CONSTRAINT `fk_committee_members_user`
        FOREIGN KEY (`member_id`)
        REFERENCES `members` (`id`) ON DELETE RESTRICT,

    CONSTRAINT `fk_committee_members_position`
        FOREIGN KEY (`committee_position_id`)
        REFERENCES `committee_positions` (`id`) ON DELETE RESTRICT,

    CONSTRAINT `fk_committee_members_company`
        FOREIGN KEY (`company_id`)
        REFERENCES `companies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committee_members_branch`
        FOREIGN KEY (`branch_id`)
        REFERENCES `branchies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committee_members_user_audit`
        FOREIGN KEY (`User_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `committee_member_responsibilities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `committee_member_id` BIGINT UNSIGNED NOT NULL,
    `responsibility_title` VARCHAR(200) NOT NULL,
    `responsibility_description` TEXT NULL,

    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),

    KEY `idx_committee_responsibility_member`
        (`committee_member_id`),

    CONSTRAINT `fk_committee_responsibilities_member`
        FOREIGN KEY (`committee_member_id`)
        REFERENCES `committee_members` (`id`) ON DELETE RESTRICT,

    CONSTRAINT `fk_committee_responsibilities_company`
        FOREIGN KEY (`company_id`)
        REFERENCES `companies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committee_responsibilities_branch`
        FOREIGN KEY (`branch_id`)
        REFERENCES `branchies` (`id`) ON DELETE SET NULL,

    CONSTRAINT `fk_committee_responsibilities_user`
        FOREIGN KEY (`User_id`)
        REFERENCES `users` (`id`) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 1. SERVICE CATEGORIES
-- =========================================================

CREATE TABLE `service_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `category_code` VARCHAR(50) NOT NULL,
    `category_name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_service_category_code`
        (`company_id`, `branch_id`, `category_code`),

    CONSTRAINT `fk_service_categories_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_categories_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_categories_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 2. SERVICE CATALOG
-- =========================================================

CREATE TABLE `service_catalog` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `service_category_id` BIGINT UNSIGNED NOT NULL,
    `service_code` VARCHAR(50) NOT NULL,
    `service_name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,

    `default_unit` VARCHAR(50) NULL,
    `default_unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `requires_product` TINYINT(1) NOT NULL DEFAULT 0,
    `requires_attachment` TINYINT(1) NOT NULL DEFAULT 0,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_service_catalog_code`
        (`company_id`, `branch_id`, `service_code`),
    KEY `idx_service_catalog_category` (`service_category_id`),

    CONSTRAINT `fk_service_catalog_category`
        FOREIGN KEY (`service_category_id`)
        REFERENCES `service_categories` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_catalog_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_catalog_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_catalog_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 3. SERVICE REQUESTS
-- =========================================================

CREATE TABLE `service_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `request_no` VARCHAR(100) NOT NULL UNIQUE,
    `request_date` DATE NOT NULL,
    `required_date` DATE NULL,

    `requester_id` BIGINT UNSIGNED NOT NULL,
    `committee_name` VARCHAR(200) NULL,
    `committee_chairman_id` BIGINT UNSIGNED NULL,

    `request_title` VARCHAR(200) NOT NULL,
    `purpose` TEXT NOT NULL,
    `beneficiary_name` VARCHAR(200) NULL,
    `priority` VARCHAR(30) NOT NULL DEFAULT 'Normal',

    `estimated_total` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `requested_currency` VARCHAR(10) NOT NULL DEFAULT 'TZS',

    -- Overall requisition workflow
    `request_status` VARCHAR(50) NOT NULL DEFAULT 'Draft',

    -- Requesting Committee Chairman approval
    `committee_chairman_approval_status`
        VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `committee_chairman_approved_by` BIGINT UNSIGNED NULL,
    `committee_chairman_approved_at` TIMESTAMP NULL DEFAULT NULL,
    `committee_chairman_approval_comment` TEXT NULL,

    -- Group Chairman approval
    `group_chairman_approval_status`
        VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `group_chairman_approved_by` BIGINT UNSIGNED NULL,
    `group_chairman_approved_at` TIMESTAMP NULL DEFAULT NULL,
    `group_chairman_approval_comment` TEXT NULL,

    -- Money processing summary on the request
    `money_status` VARCHAR(50) NOT NULL DEFAULT 'NOT_REQUESTED',
    `money_requested_total` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `money_approved_total` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `money_processed_total` DECIMAL(18,2) NOT NULL DEFAULT 0.00,

    `completed_at` TIMESTAMP NULL DEFAULT NULL,
    `cancellation_reason` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `idx_service_request_date` (`request_date`),
    KEY `idx_service_request_status` (`request_status`),
    KEY `idx_service_request_money_status` (`money_status`),
    KEY `idx_service_request_requester` (`requester_id`),

    CONSTRAINT `fk_service_requests_requester`
        FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_requests_committee_chairman`
        FOREIGN KEY (`committee_chairman_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_requests_committee_approver`
        FOREIGN KEY (`committee_chairman_approved_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_requests_group_approver`
        FOREIGN KEY (`group_chairman_approved_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_requests_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_requests_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_requests_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `service_requests`
    ADD COLUMN `committee_id` BIGINT UNSIGNED NULL
        AFTER `requester_id`,
    ADD INDEX `idx_service_requests_committee` (`committee_id`),
    ADD CONSTRAINT `fk_service_requests_committee`
        FOREIGN KEY (`committee_id`)
        REFERENCES `committees` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE;


-- =========================================================
-- 4. SERVICE REQUEST ITEMS
-- =========================================================

CREATE TABLE `service_request_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `service_request_id` BIGINT UNSIGNED NOT NULL,
    `service_catalog_id` BIGINT UNSIGNED NULL,
    `product_id` BIGINT UNSIGNED NULL,

    `item_type` VARCHAR(30) NOT NULL DEFAULT 'SERVICE',
    `item_description` VARCHAR(255) NOT NULL,

    `quantity` DECIMAL(18,3) NOT NULL DEFAULT 1.000,
    `unit_of_measure` VARCHAR(50) NULL,
    `unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `discount_amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `line_total` DECIMAL(18,2) NOT NULL DEFAULT 0.00,

    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `idx_service_request_items_request` (`service_request_id`),
    KEY `idx_service_request_items_product` (`product_id`),

    CONSTRAINT `fk_service_request_items_request`
        FOREIGN KEY (`service_request_id`)
        REFERENCES `service_requests` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_request_items_catalog`
        FOREIGN KEY (`service_catalog_id`)
        REFERENCES `service_catalog` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_request_items_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_request_items_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_request_items_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_request_items_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 5. SERVICE REQUEST ATTACHMENTS
-- =========================================================

CREATE TABLE `service_request_attachments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `service_request_id` BIGINT UNSIGNED NOT NULL,
    `attachment_type` VARCHAR(100) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `mime_type` VARCHAR(150) NULL,
    `file_size` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `idx_service_attachments_request` (`service_request_id`),

    CONSTRAINT `fk_service_attachments_request`
        FOREIGN KEY (`service_request_id`)
        REFERENCES `service_requests` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_attachments_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_attachments_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_attachments_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 6. SERVICE MONEY REQUESTS
-- =========================================================

CREATE TABLE `service_money_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `money_request_no` VARCHAR(100) NOT NULL UNIQUE,
    `service_request_id` BIGINT UNSIGNED NOT NULL,

    `money_request_date` DATE NOT NULL,
    `payee_name` VARCHAR(200) NOT NULL,
    `payment_purpose` TEXT NOT NULL,

    `amount_requested` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `amount_approved` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `amount_processed` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT 'TZS',

    -- Cashier prepares the money request
    `prepared_by` BIGINT UNSIGNED NULL,
    `prepared_at` TIMESTAMP NULL DEFAULT NULL,
    `cashier_comment` TEXT NULL,

    -- Finance Committee Chairman approval
    `finance_chairman_approval_status`
        VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `finance_chairman_approved_by` BIGINT UNSIGNED NULL,
    `finance_chairman_approved_at` TIMESTAMP NULL DEFAULT NULL,
    `finance_chairman_approval_comment` TEXT NULL,

    -- Final Group Chairman approval
    `group_chairman_approval_status`
        VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `group_chairman_approved_by` BIGINT UNSIGNED NULL,
    `group_chairman_approved_at` TIMESTAMP NULL DEFAULT NULL,
    `group_chairman_approval_comment` TEXT NULL,

    -- Money processing and cash-out status
    `money_status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `payment_method` VARCHAR(50) NULL,
    `payment_reference` VARCHAR(150) NULL,
    `processed_by` BIGINT UNSIGNED NULL,
    `processed_at` TIMESTAMP NULL DEFAULT NULL,

    -- Financial posting status
    `financial_posting_status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `financial_posted_by` BIGINT UNSIGNED NULL,
    `financial_posted_at` TIMESTAMP NULL DEFAULT NULL,
    `financial_transaction_reference` VARCHAR(150) NULL,

    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `idx_service_money_request_service` (`service_request_id`),
    KEY `idx_service_money_status` (`money_status`),
    KEY `idx_service_money_posting_status` (`financial_posting_status`),

    CONSTRAINT `fk_service_money_requests_service`
        FOREIGN KEY (`service_request_id`)
        REFERENCES `service_requests` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_money_requests_prepared_by`
        FOREIGN KEY (`prepared_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_money_requests_finance_approver`
        FOREIGN KEY (`finance_chairman_approved_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_money_requests_group_approver`
        FOREIGN KEY (`group_chairman_approved_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_money_requests_processor`
        FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_money_requests_posted_by`
        FOREIGN KEY (`financial_posted_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_money_requests_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_money_requests_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_money_requests_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 7. SERVICE VOUCHERS
-- =========================================================

CREATE TABLE `service_vouchers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `voucher_no` VARCHAR(100) NOT NULL UNIQUE,
    `service_request_id` BIGINT UNSIGNED NOT NULL,
    `service_money_request_id` BIGINT UNSIGNED NOT NULL,

    `voucher_type` VARCHAR(50) NOT NULL DEFAULT 'PAYMENT',
    `voucher_date` DATE NOT NULL,
    `payee_name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,

    `voucher_amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT 'TZS',

    `voucher_status` VARCHAR(50) NOT NULL DEFAULT 'Draft',
    `prepared_by` BIGINT UNSIGNED NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL DEFAULT NULL,

    `financial_posting_status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `financial_transaction_reference` VARCHAR(150) NULL,
    `posted_by` BIGINT UNSIGNED NULL,
    `posted_at` TIMESTAMP NULL DEFAULT NULL,

    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `idx_service_vouchers_request` (`service_request_id`),
    KEY `idx_service_vouchers_money_request` (`service_money_request_id`),

    CONSTRAINT `fk_service_vouchers_request`
        FOREIGN KEY (`service_request_id`)
        REFERENCES `service_requests` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_vouchers_money_request`
        FOREIGN KEY (`service_money_request_id`)
        REFERENCES `service_money_requests` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_vouchers_prepared_by`
        FOREIGN KEY (`prepared_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_vouchers_approved_by`
        FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_vouchers_posted_by`
        FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_vouchers_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_vouchers_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_vouchers_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- 8. SERVICE VOUCHER ITEMS
-- =========================================================

CREATE TABLE `service_voucher_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ReferenceNo` VARCHAR(100) NOT NULL UNIQUE,

    `service_voucher_id` BIGINT UNSIGNED NOT NULL,
    `service_request_item_id` BIGINT UNSIGNED NULL,
    `product_id` BIGINT UNSIGNED NULL,

    `description` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(18,3) NOT NULL DEFAULT 1.000,
    `unit_price` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    `amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,

    -- Link to your accounting chart when its exact table/key is confirmed.
    `debit_account_id` BIGINT UNSIGNED NULL,
    `credit_account_id` BIGINT UNSIGNED NULL,

    `notes` TEXT NULL,

    `company_id` BIGINT UNSIGNED NULL,
    `branch_id` BIGINT UNSIGNED NULL,
    `User_id` BIGINT UNSIGNED NULL,

    `Status` VARCHAR(50) DEFAULT 'Active',
    `AuditingStatus` VARCHAR(50) DEFAULT 'Pending',
    `ReportStatus` VARCHAR(50) DEFAULT 'Pending',

    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `deleted_by` BIGINT UNSIGNED NULL,

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `idx_service_voucher_items_voucher` (`service_voucher_id`),
    KEY `idx_service_voucher_items_request_item` (`service_request_item_id`),
    KEY `idx_service_voucher_items_product` (`product_id`),

    CONSTRAINT `fk_service_voucher_items_voucher`
        FOREIGN KEY (`service_voucher_id`)
        REFERENCES `service_vouchers` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `fk_service_voucher_items_request_item`
        FOREIGN KEY (`service_request_item_id`)
        REFERENCES `service_request_items` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_voucher_items_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_voucher_items_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_voucher_items_branch`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `fk_service_voucher_items_user`
        FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `permissions`
    (`name`, `slug`, `description`, `Status`, `created_at`, `updated_at`)
VALUES

-- =====================================================
-- COMMITTEES
-- =====================================================
('Committees Menu', 'view-committees-menu', 'Committees Menu', 'Active', NOW(), NOW()),
('View Committees', 'view-committees', 'View Committee Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Committees', 'crud-committees', 'Create, Update or Delete Committee Records', 'Active', NOW(), NOW()),
('Restore Committees', 'restore-committees', 'Restore Deleted Committee Records', 'Active', NOW(), NOW()),
('View Committees Reports', 'view-committees-report', 'View Committee Reports', 'Active', NOW(), NOW()),

-- =====================================================
-- COMMITTEE POSITIONS
-- =====================================================
('Committee Positions Menu', 'view-committee-positions-menu', 'Committee Positions Menu', 'Active', NOW(), NOW()),
('View Committee Positions', 'view-committee-positions', 'View Committee Position Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Committee Positions', 'crud-committee-positions', 'Create, Update or Delete Committee Position Records', 'Active', NOW(), NOW()),
('Restore Committee Positions', 'restore-committee-positions', 'Restore Deleted Committee Position Records', 'Active', NOW(), NOW()),
('View Committee Positions Reports', 'view-committee-positions-report', 'View Committee Position Reports', 'Active', NOW(), NOW()),

-- =====================================================
-- COMMITTEE MEMBERS
-- =====================================================
('Committee Members Menu', 'view-committee-members-menu', 'Committee Members Menu', 'Active', NOW(), NOW()),
('View Committee Members', 'view-committee-members', 'View Committee Member Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Committee Members', 'crud-committee-members', 'Create, Update or Delete Committee Member Records', 'Active', NOW(), NOW()),
('Restore Committee Members', 'restore-committee-members', 'Restore Deleted Committee Member Records', 'Active', NOW(), NOW()),
('View Committee Members Reports', 'view-committee-members-report', 'View Committee Member Reports', 'Active', NOW(), NOW()),
('Appoint Committee Members', 'appoint-committee-members', 'Appoint Members to Committees', 'Active', NOW(), NOW()),
('Remove Committee Members', 'remove-committee-members', 'Remove or End Committee Member Appointments', 'Active', NOW(), NOW()),

-- =====================================================
-- COMMITTEE MEMBER RESPONSIBILITIES
-- =====================================================
('Committee Responsibilities Menu', 'view-committee-member-responsibilities-menu', 'Committee Member Responsibilities Menu', 'Active', NOW(), NOW()),
('View Committee Responsibilities', 'view-committee-member-responsibilities', 'View Committee Member Responsibilities', 'Active', NOW(), NOW()),
('Create, Update, Delete Committee Responsibilities', 'crud-committee-member-responsibilities', 'Create, Update or Delete Committee Member Responsibilities', 'Active', NOW(), NOW()),
('Restore Committee Responsibilities', 'restore-committee-member-responsibilities', 'Restore Deleted Committee Member Responsibilities', 'Active', NOW(), NOW()),
('View Committee Responsibilities Reports', 'view-committee-member-responsibilities-report', 'View Committee Member Responsibility Reports', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE REQUESTS
-- =====================================================
('Service Requests Menu', 'view-service-requests-menu', 'Service Requests Menu', 'Active', NOW(), NOW()),
('View Service Requests', 'view-service-requests', 'View Service Request Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Service Requests', 'crud-service-requests', 'Create, Update or Delete Service Request Records', 'Active', NOW(), NOW()),
('Submit Service Requests', 'submit-service-requests', 'Submit Draft Service Requests for Approval', 'Active', NOW(), NOW()),
('Cancel Service Requests', 'cancel-service-requests', 'Cancel Service Requests', 'Active', NOW(), NOW()),
('Approve Committee Service Requests', 'approve-committee-service-requests', 'Approve or Reject Service Requests at Committee Chairman Stage', 'Active', NOW(), NOW()),
('Approve Group Service Requests', 'approve-group-service-requests', 'Approve or Reject Service Requests at Group Chairman Stage', 'Active', NOW(), NOW()),
('Restore Service Requests', 'restore-service-requests', 'Restore Deleted Service Request Records', 'Active', NOW(), NOW()),
('View Service Request Reports', 'view-service-requests-report', 'View Service Request Reports', 'Active', NOW(), NOW()),
('Export Service Request Reports', 'export-service-requests-report', 'Export Service Request Reports', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE CATEGORIES
-- =====================================================
('Service Categories Menu', 'view-service-categories-menu', 'Service Categories Menu', 'Active', NOW(), NOW()),
('View Service Categories', 'view-service-categories', 'View Service Category Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Service Categories', 'crud-service-categories', 'Create, Update or Delete Service Category Records', 'Active', NOW(), NOW()),
('Restore Service Categories', 'restore-service-categories', 'Restore Deleted Service Categories', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE CATALOG
-- =====================================================
('Service Catalog Menu', 'view-service-catalog-menu', 'Service Catalog Menu', 'Active', NOW(), NOW()),
('View Service Catalog', 'view-service-catalog', 'View Available Services Catalog', 'Active', NOW(), NOW()),
('Create, Update, Delete Service Catalog', 'crud-service-catalog', 'Create, Update or Delete Service Catalog Records', 'Active', NOW(), NOW()),
('Restore Service Catalog', 'restore-service-catalog', 'Restore Deleted Service Catalog Records', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE REQUEST ITEMS
-- =====================================================
('View Service Request Items', 'view-service-request-items', 'View Items and Services Within Service Requests', 'Active', NOW(), NOW()),
('Manage Service Request Items', 'crud-service-request-items', 'Add, Update or Remove Items and Services Within Service Requests', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE MONEY REQUESTS
-- =====================================================
('Service Money Requests Menu', 'view-service-money-requests-menu', 'Service Money Requests Menu', 'Active', NOW(), NOW()),
('View Service Money Requests', 'view-service-money-requests', 'View Service Money Request Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Service Money Requests', 'crud-service-money-requests', 'Create, Update or Delete Service Money Request Records', 'Active', NOW(), NOW()),
('Submit Service Money Requests', 'submit-service-money-requests', 'Submit Service Money Requests for Approval', 'Active', NOW(), NOW()),
('Approve Finance Committee Money Requests', 'approve-finance-committee-money-requests', 'Approve or Reject Service Money Requests at Finance Committee Chairman Stage', 'Active', NOW(), NOW()),
('Approve Group Chairman Money Requests', 'approve-group-chairman-money-requests', 'Approve or Reject Service Money Requests at Group Chairman Stage', 'Active', NOW(), NOW()),
('Process Service Money Requests', 'process-service-money-requests', 'Process Approved Service Money Requests and Record Disbursements', 'Active', NOW(), NOW()),
('Restore Service Money Requests', 'restore-service-money-requests', 'Restore Deleted Service Money Request Records', 'Active', NOW(), NOW()),
('View Service Money Request Reports', 'view-service-money-requests-report', 'View Service Money Request Reports', 'Active', NOW(), NOW()),
('Export Service Money Request Reports', 'export-service-money-requests-report', 'Export Service Money Request Reports', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE VOUCHERS
-- =====================================================
('Service Vouchers Menu', 'view-service-vouchers-menu', 'Service Vouchers Menu', 'Active', NOW(), NOW()),
('View Service Vouchers', 'view-service-vouchers', 'View Service Voucher Records', 'Active', NOW(), NOW()),
('Create, Update, Delete Service Vouchers', 'crud-service-vouchers', 'Create, Update or Delete Service Voucher Records', 'Active', NOW(), NOW()),
('Approve Service Vouchers', 'approve-service-vouchers', 'Approve Service Vouchers', 'Active', NOW(), NOW()),
('Post Service Vouchers', 'post-service-vouchers', 'Post Service Vouchers to Financial Records', 'Active', NOW(), NOW()),
('Restore Service Vouchers', 'restore-service-vouchers', 'Restore Deleted Service Voucher Records', 'Active', NOW(), NOW()),
('View Service Voucher Reports', 'view-service-vouchers-report', 'View Service Voucher Reports', 'Active', NOW(), NOW()),
('Export Service Voucher Reports', 'export-service-vouchers-report', 'Export Service Voucher Reports', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE VOUCHER ITEMS
-- =====================================================
('View Service Voucher Items', 'view-service-voucher-items', 'View Service Voucher Item Details', 'Active', NOW(), NOW()),
('Manage Service Voucher Items', 'crud-service-voucher-items', 'Create, Update or Delete Service Voucher Item Details', 'Active', NOW(), NOW()),

-- =====================================================
-- SERVICE REQUEST ATTACHMENTS
-- =====================================================
('View Service Request Attachments', 'view-service-request-attachments', 'View Service Request Attachments', 'Active', NOW(), NOW()),
('Manage Service Request Attachments', 'crud-service-request-attachments', 'Upload, Update or Delete Service Request Attachments', 'Active', NOW(), NOW());

ALTER TABLE `service_requests`
ADD COLUMN `service_category_id` BIGINT UNSIGNED NULL
AFTER `committee_id`;

ALTER TABLE `service_requests`
ADD CONSTRAINT `fk_service_requests_service_category`
FOREIGN KEY (`service_category_id`)
REFERENCES `service_categories` (`id`)
ON DELETE SET NULL
ON UPDATE CASCADE;

ALTER TABLE expense_items
ADD COLUMN product_id BIGINT UNSIGNED NULL AFTER expense_id,
ADD CONSTRAINT fk_expense_items_product 
    FOREIGN KEY (product_id) REFERENCES products(id) 
    ON DELETE SET NULL;

-- 1. Add columns to expenses table
ALTER TABLE expenses
ADD COLUMN company_id BIGINT UNSIGNED NULL AFTER attachment,
ADD COLUMN branch_id BIGINT UNSIGNED NULL AFTER company_id,
ADD CONSTRAINT fk_expenses_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_expenses_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL;


-- 2. Add columns to expense_items table
ALTER TABLE expense_items
ADD COLUMN company_id BIGINT UNSIGNED NULL AFTER attachment,
ADD COLUMN branch_id BIGINT UNSIGNED NULL AFTER company_id,
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER branch_id,
ADD CONSTRAINT fk_expense_items_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_expense_items_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_expense_items_user FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE SET NULL;

-- 3. Add columns to expense_payments table
ALTER TABLE expense_payments
ADD COLUMN company_id BIGINT UNSIGNED NULL AFTER id,
ADD COLUMN branch_id BIGINT UNSIGNED NULL AFTER company_id,
ADD CONSTRAINT fk_expense_payments_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_expense_payments_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL;


ALTER TABLE `expense_categories`
    ADD COLUMN `company_id` BIGINT UNSIGNED NULL AFTER `id`,
    ADD COLUMN `branch_id` BIGINT UNSIGNED NULL AFTER `company_id`;

ALTER TABLE `expense_categories`
    ADD CONSTRAINT `expense_categories_company_id_foreign`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON DELETE SET NULL,
    ADD CONSTRAINT `expense_categories_branch_id_foreign`
        FOREIGN KEY (`branch_id`) REFERENCES `branchies` (`id`)
        ON DELETE SET NULL;