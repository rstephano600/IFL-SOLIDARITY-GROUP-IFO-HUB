<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request Form - {{ $data->request_no }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Font Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #212529;
        }

        .print-container {
            max-width: 900px;
            margin: 30px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .company-header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .company-logo {
            max-height: 70px;
            object-fit: contain;
        }

        .document-title {
            font-weight: 700;
            color: #0d6efd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-custom th {
            background-color: #f1f3f5;
            color: #495057;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .signature-box {
            border: 1px dashed #adb5bd;
            border-radius: 4px;
            padding: 15px;
            background-color: #f8f9fa;
            min-height: 140px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .signature-line {
            border-bottom: 1px solid #495057;
            margin-top: 30px;
            width: 80%;
        }

        .stamp-box {
            border: 2px dashed #ced4da;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        /* PRINT MEDIA CONTROL STYLES */
        @media print {
            body {
                background-color: #ffffff;
                margin: 0;
                padding: 0;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-before: always;
            }

            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    <!-- ACTION BUTTONS (Hidden on Print) -->
    <div class="container text-center my-4 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg me-2">
            <i class="fas fa-print me-2"></i> Print Form
        </button>
        <a href="{{ route('viewServiceRequest', encrypt($data->id)) }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left me-2"></i> Back to Details
        </a>
    </div>

    <!-- PRINTABLE FORM CONTAINER -->
    <div class="print-container">
        
        <!-- HEADER / LOGO SECTION -->
        <div class="row align-items-center company-header">
            <div class="col-7">
                <h3 class="fw-bold mb-1">{{ $data->company->company_name ?? 'IFL SOLIDARITY GROUP' }}[cite: 1]</h3>
                <p class="text-muted mb-0 small">
                    <i class="fas fa-building me-1"></i> {{ $data->branch->branch_name ?? 'Head Office Branch' }} <br>
                    <i class="fas fa-hashtag me-1"></i> Ref: {{ $data->ReferenceNo }}
                </p>
            </div>
            <div class="col-5 text-end">
                <h4 class="document-title mb-1">Service Request Form</h4>
                <div class="fw-bold text-dark fs-5">{{ $data->request_no }}</div>
                <small class="text-muted">Date: {{ $data->request_date ? $data->request_date->format('d/m/Y') : date('d/m/Y') }}</small>
            </div>
        </div>

        <!-- GENERAL INFORMATION -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <th class="text-muted" style="width: 35%;">Requester Name:</th>
                        <td class="fw-bold">{{ $data->requester->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Committee:</th>
                        <td class="fw-bold">{{ $data->committee_name ?? $data->committee->committee_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Service Category:</th>
                        <td>{{ $data->serviceCategory->category_name ?? 'Unassigned' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <th class="text-muted" style="width: 35%;">Priority Level:</th>
                        <td class="fw-bold text-uppercase">{{ $data->priority }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Required Date:</th>
                        <td>{{ $data->required_date ? $data->required_date->format('d/m/Y') : 'As Soon As Possible' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Beneficiary:</th>
                        <td>{{ $data->beneficiary_name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-12 mt-2">
                <div class="p-2 border rounded bg-light small">
                    <strong>Request Title:</strong> {{ $data->request_title }} <br>
                    <strong>Purpose / Overview:</strong> {{ $data->purpose ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- REQUESTED LINE ITEMS -->
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase fs-7 text-secondary mb-2"><i class="fas fa-list me-1"></i> Line Items Breakdown</h6>
            <table class="table table-bordered table-sm align-middle table-custom">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 45%;">Service / Item Details</th>
                        <th style="width: 20%;">Description / Specs</th>
                        <th style="width: 15%;" class="text-center">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data->items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $item->item_type }}</span></td>
                        <td><strong>{{ $item->service->service_name ?? $item->product->product_name ?? 'N/A' }}</strong></td>
                        <td class="small">{{ $item->item_description }}</td>
                        <td class="text-center fw-bold">{{ $item->quantity }} {{ $item->unit_of_measure }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No items attached to this request.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- SIGNATURE & APPROVAL TRACKING SECTION -->
        <div class="mt-5">
            <h6 class="fw-bold text-uppercase fs-7 text-secondary mb-3"><i class="fas fa-file-signature me-1"></i> Signatures & Approval Authorization</h6>
            
            <div class="row g-3">
                <!-- 1. REQUESTER / INITIATOR -->
                <div class="col-4">
                    <div class="signature-box">
                        <div>
                            <span class="fw-bold small text-uppercase d-block mb-1">1. Prepared By (Requester)</span>
                            <div class="small">Name: <strong>{{ $data->requester->name ?? 'N/A' }}</strong></div>
                            <div class="small text-muted">Date: {{ $data->created_at ? $data->created_at->format('d/m/Y') : '' }}</div>
                        </div>
                        <div>
                            <div class="signature-line"></div>
                            <small class="text-muted d-block mt-1">Signature</small>
                        </div>
                    </div>
                </div>

                <!-- 2. COMMITTEE CHAIRMAN APPROVAL -->
                <div class="col-4">
                    <div class="signature-box">
                        <div>
                            <span class="fw-bold small text-uppercase d-block mb-1">2. Committee Chairman</span>
                            <div class="small">Name: <strong>{{ $data->committeeApprover->name ?? $data->committeeChairman->name ?? 'Pending' }}</strong></div>
                            <div class="small text-muted">Status: {{ $data->committee_chairman_approval_status }}</div>
                            <div class="small text-muted">Date: {{ $data->committee_chairman_approved_at ? $data->committee_chairman_approved_at->format('d/m/Y') : '___/___/20__' }}</div>
                        </div>
                        <div>
                            <div class="signature-line"></div>
                            <small class="text-muted d-block mt-1">Signature & Date</small>
                        </div>
                    </div>
                </div>

                <!-- 3. GROUP CHAIRMAN APPROVAL -->
                <div class="col-4">
                    <div class="signature-box">
                        <div>
                            <span class="fw-bold small text-uppercase d-block mb-1">3. Group Chairman (Final)</span>
                            <div class="small">Name: <strong>{{ $data->groupChairmanApprover->name ?? 'Pending' }}</strong></div>
                            <div class="small text-muted">Status: {{ $data->group_chairman_approval_status }}</div>
                            <div class="small text-muted">Date: {{ $data->group_chairman_approved_at ? $data->group_chairman_approved_at->format('d/m/Y') : '___/___/20__' }}</div>
                        </div>
                        <div>
                            <div class="signature-line"></div>
                            <small class="text-muted d-block mt-1">Signature & Date</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OFFICIAL STAMP & FOOTER NOTES -->
            <div class="row g-3 mt-3 align-items-center">
                <div class="col-8">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">
                            <strong>Note:</strong> This approval document serves as an official internal voucher for service requisition. Pricing and financial disbursement will be processed in the secondary Money Request phase following final approval.
                        </small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stamp-box">
                        Official Stamp / Seal
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>