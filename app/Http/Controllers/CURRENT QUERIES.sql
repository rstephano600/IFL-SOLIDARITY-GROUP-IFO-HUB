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
    share_type_id BIGINT UNSIGNED NOT NULL,

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
