<aside class="sidebar" id="sidebar">
    @php
        // Get the authenticated user
        $user = Auth::user();
    
    @endphp

    <div class="d-flex align-items-center justify-content-center gap-2 text-center py-4 border-bottom border-white border-opacity-25">
        <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:28px;height:28px;object-fit:contain;">
        <h5 class="text-white mb-0">
            <span id="logo-text">IFL Solidarity</span>
        </h5>
    </div>
    <!-- <div class="px-3 py-4 border-bottom border-white border-opacity-25">
        <div class="d-flex align-items-center">
            <div class="user-avatar me-3">
                {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
            </div>
            <div class="user-info d-flex flex-column">
                <h6 class="mb-0 text-white">{{ $user->username }}</h6>
                <small class="text-light">
                    @php
                        $role = $user->role;
                        $roleClass = 'badge-' . str_replace('_', '-', strtolower($role));
                    @endphp
                </small>
            </div>
        </div>
    </div> -->

    <nav class="nav flex-column p-3">

        {{-- ================= 1. DAILY WORK DASHBOARD ================= --}}
        @can('view-working-side')
        <a href="{{ route('workingside') }}" 
        class="nav-link {{ Request::is('workingside*') ? 'active' : '' }}">
            <i class="fas fa-tasks me-1"></i> 
            <span>Working Side</span>
        </a>
        @endcan


        {{-- ── Procurement Menu ── --}}
        @can('view-purchase-orders-menu')
        <div class="nav-item">
            <a href="#procurementSubmenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="procurementSubmenu">
                <i class="fas fa-shopping-cart me-2"></i>
                <span>Procurement Menu</span>
                <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
            </a>

            <div class="collapse" id="procurementSubmenu">
                <div class="ps-4 mt-2">

                    {{-- ── Purchase Requests ── --}}
                    @can('view-purchase-orders-for-procurement')
                        <a href="{{ route('purchaseRequest') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-boxes-packing me-2"></i> <span>Purchase Requests</span> </a>
                        <a href="{{ route('createpurchaseRequest') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-cart-plus me-2"></i> <span>New Purchase Request</span> </a>
                    @endcan

                    {{-- ── Purchase Request Approvals ── --}}
                    @if(
                        auth()->user()->can('view-purchase-request-level-1') ||
                        auth()->user()->can('view-purchase-request-level-2') ||
                        auth()->user()->can('view-purchase-request-level-3') ||
                        auth()->user()->can('view-purchase-request-level-4') ||
                        auth()->user()->can('view-purchase-request-level-5') ||
                        auth()->user()->can('view-purchase-request-level-6')
                    )
                        <div class="mt-3 mb-2">
                            <span class="text-muted small d-flex fw-bold text-uppercase"> <i class="fas fa-clipboard-check me-2"></i> Purchase Request Approvals </span>
                        </div>

                        @can('view-purchase-request-level-1')
                            <a href="{{ route('purchaseRequestApprovalLevel1') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-check-circle me-2"></i> <span>Approval Level 1</span> </a>
                        @endcan

                        @can('view-purchase-request-level-2')
                            <a href="{{ route('purchaseRequestApprovalLevel2') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-check-circle me-2"></i> <span>Approval Level 2</span> </a>
                        @endcan

                        @can('view-purchase-request-level-3')
                            <a href="{{ route('purchaseRequestApprovalLevel3') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-check-circle me-2"></i> <span>Approval Level 3</span> </a>
                        @endcan

                        @can('view-purchase-request-level-4')
                            <a href="{{ route('purchaseRequestApprovalLevel4') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-check-circle me-2"></i> <span>Approval Level 4</span> </a>
                        @endcan

                        @can('view-purchase-request-level-5')
                            <a href="{{ route('purchaseRequestApprovalLevel5') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-check-circle me-2"></i> <span>Approval Level 5</span> </a>
                        @endcan

                        @can('view-purchase-request-level-6')
                            <a href="{{ route('purchaseRequestApprovalLevel6') }}" class="nav-link d-flex align-items-center"> <i class="fas fa-check-circle me-2"></i> <span>Approval Level 6</span> </a>
                        @endcan
                    @endif

                    {{-- ── Procurement Configuration ── --}}
                    @can('view-procurement-types')
                        <a href="{{ route('procurementType') }}"
                        class="nav-link d-flex align-items-center">
                            <i class="fas fa-tags me-2"></i>
                            <span>Procurement Types</span>
                        </a>
                    @endcan

                    @can('view-procurement-workflow-steps')
                        <a href="{{ route('procurementWorkflowStep') }}"
                        class="nav-link d-flex align-items-center">
                            <i class="fas fa-list-check me-2"></i>
                            <span>Workflow Steps</span>
                        </a>
                    @endcan

                </div>
            </div>
        </div>
        @endcan


        {{-- ── Sales Menu ── --}}
        @can('view-sales-invoices-menu')
        <div class="nav-item">
            <a href="#salesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-cash-register me-2"></i>
                <span>Sales Menu</span>
                <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
            </a>
            <div class="collapse" id="salesSubmenu">
                <div class="ps-4 mt-2">
                    @can('view-customers')
                    <a href="{{ route('customer') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-users me-2"></i> <span>Customers</span>
                    </a>
                    @endcan
                    @can('view-sales-invoices')
                    <a href="{{ route('salesinvoice') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar me-2"></i> <span>Sales Invoices</span>
                    </a>
                    @endcan
                    @can('view-customer-invoice-payments')
                    <a href="{{ route('customerPayment') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-credit-card me-2"></i> <span>Customer Payments</span>
                    </a>
                    <a href="{{ route('customerPaymentinvoice') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar me-2"></i> <span>Add Invoice Sales</span>
                    </a>
                    @endcan
                    @can('view-stock-adjustments')
                    <a href="{{ route('stockOutsalesinvoice') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar me-2"></i> <span>Stock Out Sold Porducts</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endcan

    {{-- ── Supplier Menu ── --}}
        @canany([
            'view-suppliers-menu',
            'view-supplier-tenders-menu',
            'view-purchase-quotations-menu',
            'view-supplier-awards-menu',
            'view-purchase-orders-menu',
            'view-goods-receipts-menu',
            'view-supplier-invoices-menu',
            'view-supplier-payments-menu',
        ])
        <div class="nav-item">
            <a href="#supplierSubmenu"
            class="nav-link"
            data-bs-toggle="collapse"
            role="button"
            aria-expanded="false"
            aria-controls="supplierSubmenu">

                <i class="fas fa-truck me-2"></i>
                <span>Suppliers & Procurement</span>
                <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
            </a>

            <div class="collapse" id="supplierSubmenu">
                <div class="ps-4 mt-2">

                    {{-- ═══════════ 1. Supplier Information ═══════════ --}}
                    @canany(['view-suppliers', 'view-supplier-categories'])
                    <a href="#supplierInfoSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="supplierInfoSubmenu">
                        <i class="fas fa-address-book me-2"></i>
                        <span>Supplier Information</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="supplierInfoSubmenu">
                        <div class="ps-3">
                            @can('view-suppliers')
                            <a href="{{ route('suppliers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i><span>Suppliers</span></a>
                            <a href="{{ route('deletedSuppliers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Suppliers</span></a>
                            @endcan

                            @can('view-supplier-categories')
                            <a href="{{ route('supplierCategory') }}" class="nav-link d-flex align-items-center"><i class="fas fa-tags me-2"></i><span>Supplier Categories</span></a>
                            <a href="{{ route('deletedsupplierCategory') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Categories</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcanany

                    {{-- ═══════════ 2. Tenders ═══════════ --}}
                    @can('view-supplier-tenders-menu')
                    <a href="#tendersSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="tendersSubmenu">
                        <i class="fas fa-bullhorn me-2"></i>
                        <span>Tenders</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="tendersSubmenu">
                        <div class="ps-3">
                            @can('view-supplier-tenders')
                            <a href="{{ route('supplierTender') }}" class="nav-link d-flex align-items-center"><i class="fas fa-gavel me-2"></i><span>Tender Announcements</span></a>
                            @endcan
                            @can('restore-supplier-tenders')
                            <a href="{{ route('deletedsupplierTender') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Tenders</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                    {{-- ═══════════ 3. Quotations ═══════════ --}}
                    @can('view-purchase-quotations-menu')
                    <a href="#quotationsSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="quotationsSubmenu">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        <span>Quotations</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="quotationsSubmenu">
                        <div class="ps-3">
                            @can('view-purchase-quotations')
                            <a href="{{ route('supplierQuotation') }}" class="nav-link d-flex align-items-center"><i class="fas fa-file-invoice-dollar me-2"></i><span>Supplier Quotations</span></a>
                            @endcan
                            @can('crud-purchase-quotations')
                            <a href="{{ route('createsupplierQuotation') }}" class="nav-link d-flex align-items-center"><i class="fas fa-plus-circle me-2"></i><span>New Quotation</span></a>
                            @endcan
                            @can('restore-purchase-quotations')
                            <a href="{{ route('deletedsupplierQuotation') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Quotations</span></a>
                            @endcan

                            @can('view-quotation-evaluations')
                            <a href="{{ route('quotationEvaluation') }}" class="nav-link d-flex align-items-center"><i class="fas fa-balance-scale me-2"></i><span>Quotation Evaluations</span></a>
                            @endcan
                            @can('manage-quotation-evaluations')
                            <a href="{{ route('deletedquotationEvaluation') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Evaluations</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                    {{-- ═══════════ 4. Awards & Contracts ═══════════ --}}
                    @can('view-supplier-awards-menu')
                    <a href="#awardsSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="awardsSubmenu">
                        <i class="fas fa-award me-2"></i>
                        <span>Awards & Contracts</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="awardsSubmenu">
                        <div class="ps-3">
                            @can('view-supplier-awards')
                            <a href="{{ route('supplierAward') }}" class="nav-link d-flex align-items-center"><i class="fas fa-file-signature me-2"></i><span>Awards / Contracts</span></a>
                            @endcan
                            @can('restore-supplier-awards')
                            <a href="{{ route('deletedsupplierAward') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Awards</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                    {{-- ═══════════ 5. Purchase Orders ═══════════ --}}
                    @can('view-purchase-orders-menu')
                    <a href="#purchaseOrdersSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="purchaseOrdersSubmenu">
                        <i class="fas fa-shopping-cart me-2"></i>
                        <span>Purchase Orders</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="purchaseOrdersSubmenu">
                        <div class="ps-3">
                            @can('view-purchase-orders')
                            <a href="{{ route('purchaseOrder') }}" class="nav-link d-flex align-items-center"><i class="fas fa-clipboard-list me-2"></i><span>Purchase Orders</span></a>
                            @endcan
                            @can('crud-purchase-orders')
                            <a href="{{ route('createpurchaseOrder') }}" class="nav-link d-flex align-items-center"><i class="fas fa-plus-circle me-2"></i><span>New Purchase Order</span></a>
                            @endcan
                            @can('restore-purchase-orders')
                            <a href="{{ route('deletedpurchaseOrder') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Purchase Orders</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                    {{-- ═══════════ 6. Goods Receipts ═══════════ --}}
                    @can('view-goods-receipts-menu')
                    <a href="#goodsReceiptsSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="goodsReceiptsSubmenu">
                        <i class="fas fa-dolly me-2"></i>
                        <span>Goods Receipts</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="goodsReceiptsSubmenu">
                        <div class="ps-3">
                            @can('view-goods-receipts')
                            <a href="{{ route('goodsReceipt') }}" class="nav-link d-flex align-items-center"><i class="fas fa-box-open me-2"></i><span>Goods Receipts</span></a>
                            @endcan
                            @can('receive-supplier-goods')
                            <a href="{{ route('unreceivedPurchaseOrders') }}" class="nav-link d-flex align-items-center"><i class="fas fa-truck-loading me-2"></i><span>Orders Awaiting Receipt</span></a>
                            @endcan
                            @can('restore-goods-receipts')
                            <a href="{{ route('deletedgoodsReceipt') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Goods Receipts</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                    {{-- ═══════════ 7. Supplier Invoices ═══════════ --}}
                    @can('view-supplier-invoices-menu')
                    <a href="#supplierInvoicesSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="supplierInvoicesSubmenu">
                        <i class="fas fa-file-invoice me-2"></i>
                        <span>Supplier Invoices</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="supplierInvoicesSubmenu">
                        <div class="ps-3">
                            @can('view-supplier-invoices')
                            <a href="{{ route('supplierInvoice') }}" class="nav-link d-flex align-items-center"><i class="fas fa-receipt me-2"></i><span>Supplier Invoices</span></a>
                            @endcan
                            @can('crud-supplier-invoices')
                            <a href="{{ route('unInvoicedGoodsReceipts') }}" class="nav-link d-flex align-items-center"><i class="fas fa-clipboard-check me-2"></i><span>Receipts Awaiting Invoice</span></a>
                            @endcan
                            @can('restore-supplier-invoices')
                            <a href="{{ route('deletedsupplierInvoice') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Invoices</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                    {{-- ═══════════ 8. Supplier Payments ═══════════ --}}
                    @can('view-supplier-payments-menu')
                    <a href="#supplierPaymentsSubmenu"
                    class="nav-link d-flex align-items-center"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="supplierPaymentsSubmenu">
                        <i class="fas fa-hand-holding-usd me-2"></i>
                        <span>Supplier Payments</span>
                        <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
                    </a>
                    <div class="collapse" id="supplierPaymentsSubmenu">
                        <div class="ps-3">
                            @can('view-supplier-payments')
                            <a href="{{ route('supplierPayment') }}" class="nav-link d-flex align-items-center"><i class="fas fa-money-check-alt me-2"></i><span>Supplier Payments</span></a>
                            @endcan
                            @can('request-supplier-payments')
                            <a href="{{ route('unPaidSupplierInvoices') }}" class="nav-link d-flex align-items-center"><i class="fas fa-search-dollar me-2"></i><span>Unpaid Invoices</span></a>
                            @endcan
                            @can('restore-supplier-payments')
                            <a href="{{ route('deletedsupplierPayment') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-restore me-2"></i><span>Deleted Payments</span></a>
                            @endcan
                        </div>
                    </div>
                    @endcan

                </div>
            </div>
        </div>
        @endcanany
        @can('view-salary-menu')
        <div class="nav-item">
            <a href="#salarymenu" class="nav-link" data-bs-toggle="collapse">
                <i class="fas fa-money-check-alt nav-icon"></i>
                Salary Menu
                <i class="right fas fa-angle-left"></i>
            </a>
            <div id="salarymenu" class="collapse">
                @can('view-salary')
                <a href="{{ route('salarypay') }}" class="nav-link">
                    <i class="fas fa-wallet nav-icon"></i>
                    Monthly Salaries
                </a>
                @endcan

                <!-- @can('generate-salary')
                <a href="{{ route('registersalary') }}" class="nav-link">
                    <i class="fas fa-plus-circle nav-icon"></i>
                    Generate Salary
                </a>
                @endcan -->

                @can('pending-salary')
                <a href="{{ route('payrollApprovalIndex') }}" class="nav-link">
                    <i class="fas fa-clock nav-icon"></i>
                    Pending Approval
                </a>
                @endcan

                <!-- @can('pay-salary')
                <a href="{{ route('pendingpaysalary') }}" class="nav-link">
                    <i class="fas fa-credit-card nav-icon"></i>
                    Pending Payment
                </a>
                @endcan -->

                @can('view-staff-advance-salaries-menu')
                <a href="{{ route('staffadvncslries') }}" class="nav-link">
                    <i class="fas fa-hand-holding-usd nav-icon"></i>
                    Advance Salaries
                </a>
                @endcan

                @can('view-staff-overtimes-menu')
                <a href="{{ route('staffovertimes') }}" class="nav-link">
                    <i class="fas fa-business-time nav-icon"></i>
                    Staff Overtimes
                </a>
                @endcan

                @can('view-salary-allowances-menu')
                <a href="{{ route('slrallwncs') }}" class="nav-link">
                    <i class="fas fa-gift nav-icon"></i>
                    Salary Allowances
                </a>
                @endcan

                @can('view-staff-absent-calendars-menu')
                <a href="{{ route('staffabsentcalenders') }}" class="nav-link">
                    <i class="fas fa-calendar-times nav-icon"></i>
                    Absent Calendar
                </a>
                @endcan

                @can('view-staff-absents-menu')
                <a href="{{ route('staffabsent') }}" class="nav-link">
                    <i class="fas fa-calculator nav-icon"></i>
                    Absent Deductions
                </a>
                @endcan

                @can('salary-history')
                <a href="{{ route('salaryhistory') }}" class="nav-link">
                    <i class="fas fa-history nav-icon"></i>
                    Salary History
                </a>
                @endcan
            </div>
        </div>
        @endcan

        @can('view-share-purchases-menu')
        <div class="nav-item">
            <a href="#sharepurchasetransactions" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-coins me-2"></i> <span>Share Purchases</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="sharepurchasetransactions">
                <div class="ps-4 mt-2">
                    {{-- Main Overview Hub --}}
                    @can('view-share-purchases')
                    <a href="{{ route('sharepurchasetransactionshub') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-cubes me-2"></i> <span>Share Purchases Hub</span>
                    </a>
                    @endcan
                    <!-- @can('view-share-purchases')
                    <a href="{{ route('sharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-cubes me-2"></i> <span>Share Purchases</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 1: Verification --}}
                    @can('verify-share-purchases')
                    <a href="{{ route('unverifiedsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-shield-alt me-2"></i> <span>Pending Verification</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 2: Approval --}}
                    @can('approve-share-purchases')
                    <a href="{{ route('pendingapprovalsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-user-check me-2"></i> <span>Pending Approval</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 3: Posting --}}
                    @can('post-share-purchases')
                    <a href="{{ route('pendingpostingsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar me-2"></i> <span>Pending Posting</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 4: Certificate Generation --}}
                    @can('generate-certificate-share-purchases')
                    <a href="{{ route('pendingcertificatesharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-certificate me-2"></i> <span>Pending Certificate</span>
                    </a>
                    @endcan

                    {{-- Final Stage: Archive & Completed --}}
                    @can('view-share-purchases')
                    <a href="{{ route('completedsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-check-double me-2"></i> <span>Completed Purchases</span>
                    </a>
                    @endcan

                    {{-- Soft-Deleted Purchases --}}
                    @can('view-share-purchases')
                    <a href="{{ route('deletedsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-trash-restore me-2"></i> <span>Deleted Purchases</span>
                    </a>
                    @endcan

                    {{-- Reports --}}
                    @can('view-share-purchases-report')
                    <a href="{{ route('sharepurchasetransactionsreport') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-chart-bar me-2"></i> <span>Purchases Report</span>
                    </a>
                    @endcan -->
                </div>
            </div>
        </div>
        @endcan

        @can('view-membership-fees-menu')
        <div class="nav-item">
            <a href="#membershipfeepayments" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-hand-holding-dollar me-2"></i> <span>Fees & Contributions</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="membershipfeepayments">
                <div class="ps-4 mt-2">
                    @can('view-membership-fees')
                    <a href="{{ route('socialcontributionshub') }}" class="nav-link d-flex align-items-center"><i class="fas fa-hand-holding-heart me-2"></i> <span>Social Conts Hub</span></a>
                    <a href="{{ route('membershipfeepaymentshub') }}" class="nav-link d-flex align-items-center"><i class="fas fa-id-card me-2"></i> <span>Membership Fees Hub</span></a>
                    <a href="{{ route('socialcontributions') }}" class="nav-link d-flex align-items-center"><i class="fas fa-hand-holding-heart me-"></i> <span>Monthly Social Conts</span></a>
                    <a href="{{ route('membershipfeepayments') }}" class="nav-link d-flex align-items-center"><i class="fas fa-id-card me-2"></i> <span>Mbembership Fees</span></a>
                    @endcan
                    <!-- @can('view-membership-fees')
                    <a href="{{ route('deletedsocialcontributions') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members</span></a>
                    <a href="{{ route('deletedmembershipfeepayments') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members</span></a>
                    @endcan
                    @can('view-membership-fees-report')
                    <a href="{{ route('socialcontributionsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Report</span></a>
                    <a href="{{ route('membershipfeepaymentsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Report</span></a>
                    @endcan -->
                </div>
            </div>
        </div>
        @endcan
        @can('view-members-menu')
        <div class="nav-item">
            <a href="#membersSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-users me-2"></i> <span>Members Information</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="membersSubmenu">
                <div class="ps-4 mt-2">
                    @can('view-members')
                    <a href="{{ route('memberinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Members</span></a>
                    @endcan
                    @can('view-members')
                    <a href="{{ route('deletedmemberinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members</span></a>
                    @endcan
                    @can('view-members-report')
                    <a href="{{ route('memberinformationsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Report</span></a>
                    @endcan
                </div>
            </div>
        </div>
        @endcan
                {{-- ── Expenses Menu ── --}}
        @can('view-expenses-menu')
        <div class="nav-item">
            <a href="#expensesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-receipt me-2"></i>
                <span>Expenses Menu</span>
                <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
            </a>
            <div class="collapse" id="expensesSubmenu">
                <div class="ps-4 mt-2">
                    @can('view-expenses')
                    <a href="{{ route('expenseinformations') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar me-2"></i> <span>Expenses Info</span>
                    </a>
                    @endcan
                    @can('approve-expenses')
                    <a href="{{ route('approveexpenses') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-check me-2"></i> <span>Approve Expenses</span>
                    </a>
                    @endcan
                    @can('view-expense-categories')
                    <a href="{{ route('expensecategoryinformations') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-tags me-2"></i> <span>Expense Categories</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endcan
                {{-- ── Employee Menu ── --}}
        @can('view-employee-menu')
        <div class="nav-item">
            <a href="#employeeSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-users me-2"></i>
                <span>Employee Menu</span>
                <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
            </a>
            <div class="collapse" id="employeeSubmenu">
                <div class="ps-4 mt-2">
                    @can('register-employees')
                    <a href="{{ route('employeeinfo') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-building me-2"></i> <span>Company Employees</span>
                    </a>
                    <a href="#" class="nav-link d-flex align-items-center">
                        <i class="fas fa-user-slash me-2"></i> <span>Inactive Employees</span>
                    </a>
                    <a href="#" class="nav-link d-flex align-items-center">
                        <i class="fas fa-id-badge me-2"></i> <span>Employee Referees</span>
                    </a>
                    <a href="#" class="nav-link d-flex align-items-center">
                        <i class="fas fa-heart me-2"></i> <span>Employee Next of Kin</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endcan
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" 
                class="nav-link text-start w-100" style="background: none; border: none;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>