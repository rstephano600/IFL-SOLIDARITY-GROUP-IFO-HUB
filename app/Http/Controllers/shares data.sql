-- =========================================================
-- 1. SHARE TYPES
-- =========================================================
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

-- =========================================================
-- 2. SHARE OFFERINGS (the announced round — e.g. 5,000 shares @ 10,000)
-- =========================================================
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
    OfferingStatus ENUM('Open','Closed','FullySubscribed') NOT NULL DEFAULT 'Open',

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

-- =========================================================
-- 3. MEMBERSHIP FEE SCHEDULES (Kiingilio can vary by time)
-- =========================================================
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

-- =========================================================
-- 4. MEMBERSHIP FEE PAYMENTS (actual Kiingilio paid per member)
-- =========================================================
CREATE TABLE membership_fee_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    PaymentRefNo VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,
    fee_schedule_id BIGINT UNSIGNED NOT NULL,

    AmountPaid DECIMAL(15,2) NOT NULL,
    PaymentDate DATE NOT NULL,
    PaymentMethod VARCHAR(50) NULL,
    PaymentReference VARCHAR(100) NULL,        -- links to combined receipt if paid together with shares
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

-- =========================================================
-- 5. SOCIAL CONTRIBUTIONS (Jamii — monthly)
-- =========================================================
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

    ContributionMonth DATE NOT NULL,           -- store as YYYY-MM-01 to represent the period
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

-- =========================================================
-- 6. SHARE PURCHASE TRANSACTIONS (permanent ledger, now tied to an offering)
-- =========================================================
CREATE TABLE share_purchase_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    TransactionRefNo VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,
    share_offering_id BIGINT UNSIGNED NOT NULL,
    share_type_id BIGINT UNSIGNED NOT NULL,

    TransactionType ENUM('Purchase','TransferIn','TransferOut','Refund','Adjustment')
        NOT NULL DEFAULT 'Purchase',

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
    CONSTRAINT fk_spt_transfer FOREIGN KEY (related_transfer_id) REFERENCES share_transfers(id) ON DELETE SET NULL,
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

-- =========================================================
-- 7. SHARE CERTIFICATES
-- =========================================================
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

    CertificateStatus ENUM('Active','Revoked','Replaced') NOT NULL DEFAULT 'Active',

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

-- =========================================================
-- 8. SHARE TRANSFERS
-- =========================================================
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

    TransferStatus ENUM('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',

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

-- =========================================================
-- 9. SHARE DIVIDEND DECLARATIONS
-- =========================================================
CREATE TABLE share_dividend_declarations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    DeclarationRefNo VARCHAR(50) UNIQUE NOT NULL,
    share_offering_id BIGINT UNSIGNED NULL,

    FinancialYear VARCHAR(9) NOT NULL,
    DeclarationDate DATE NOT NULL,
    PeriodStartDate DATE NOT NULL,
    PeriodEndDate DATE NOT NULL,

    DividendRatePercent DECIMAL(6,3) NOT NULL,
    TotalDividendPool DECIMAL(18,2) NOT NULL,

    AllocationMethod ENUM('Flat','TimeWeighted') NOT NULL DEFAULT 'Flat',
    MinimumHoldingDays INT UNSIGNED NULL,

    DeclarationStatus ENUM('Draft','Approved','Allocated','Paid') NOT NULL DEFAULT 'Draft',

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_decl_offering FOREIGN KEY (share_offering_id) REFERENCES share_offerings(id) ON DELETE SET NULL,
    CONSTRAINT fk_decl_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_decl_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_decl_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);

-- =========================================================
-- 10. SHARE DIVIDEND ALLOCATIONS
-- =========================================================
CREATE TABLE share_dividend_allocations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    AllocationRefNo VARCHAR(50) UNIQUE NOT NULL,
    declaration_id BIGINT UNSIGNED NOT NULL,
    member_id BIGINT UNSIGNED NOT NULL,
    share_type_id BIGINT UNSIGNED NOT NULL,

    EligibleShares DECIMAL(15,2) NOT NULL,
    HoldingDays INT UNSIGNED NULL,
    WeightFactor DECIMAL(8,6) NULL,

    CalculatedAmount DECIMAL(15,2) NOT NULL,
    AdjustedAmount DECIMAL(15,2) NULL,
    AdjustmentReason VARCHAR(255) NULL,
    FinalAmount DECIMAL(15,2) AS (COALESCE(AdjustedAmount, CalculatedAmount)) STORED,

    PaymentStatus ENUM('Pending','Paid','Reinvested','Reversed') NOT NULL DEFAULT 'Pending',
    PaymentDate DATE NULL,
    PaymentReference VARCHAR(100) NULL,

    company_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    User_id BIGINT UNSIGNED NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_alloc_declaration FOREIGN KEY (declaration_id) REFERENCES share_dividend_declarations(id) ON DELETE CASCADE,
    CONSTRAINT fk_alloc_member FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT,
    CONSTRAINT fk_alloc_share_type FOREIGN KEY (share_type_id) REFERENCES share_types(id) ON DELETE RESTRICT,
    CONSTRAINT fk_alloc_user FOREIGN KEY (User_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_alloc_branch FOREIGN KEY (branch_id) REFERENCES branchies(id) ON DELETE SET NULL,
    CONSTRAINT fk_alloc_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,

    UNIQUE KEY uq_alloc_member_declaration_type (declaration_id, member_id, share_type_id)
);


