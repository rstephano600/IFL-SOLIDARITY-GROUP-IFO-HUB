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
    User_id BIGINT UNSIGNED NOT NULL,
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
    User_id BIGINT UNSIGNED NOT NULL,
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

CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    department_code VARCHAR(30) NOT NULL UNIQUE,
    department_name VARCHAR(200) NOT NULL,
    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,

    function TEXT NULL,
    description TEXT NULL,

    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

 
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_department_user
        FOREIGN KEY (User_id)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_department_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_department_branch
        FOREIGN KEY (branch_id)
        REFERENCES branchies(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_department_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_department_updated_by
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

    User_id BIGINT UNSIGNED NOT NULL,
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

    User_id BIGINT UNSIGNED NOT NULL,
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

    User_id BIGINT UNSIGNED NOT NULL,
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

    User_id BIGINT UNSIGNED NOT NULL,
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


