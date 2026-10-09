<?php

namespace App\Http\Controllers;


use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\SupplierCategory;
use App\Models\Supplier;
use App\Models\Member;
use App\Models\SupplierTender;
use App\Models\PurchaseRequest;
use App\Models\SupplierQuotation;
use App\Models\SupplierQuotationItem;
use App\Models\Product;
use App\Models\UnitOfMeasure;
use App\Models\QuotationEvaluation;
use App\Models\SupplierAward;
use App\Models\SupplierInvoice;
use App\Models\SupplierInvoiceItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SupplierController extends Controller
{

    /**
     * Display a listing of active supplier categories.
     */
    public function supplierCategory()
    {
        try {
            $data = SupplierCategory::where('Status', 'Active')
                ->with(['company', 'branch', 'user'])
                ->get();

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.suppliers.supplierCategory.supplierCategory', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created supplier category in storage.
     */
    public function storesupplierCategory(Request $request)
    {
        $request->validate([
            'branch_id'    => 'required|integer',
            'CategoryCode' => 'required|string|max:50|unique:supplier_categories,CategoryCode',
            'CategoryName' => 'required|string|max:255',
            'SupplierGroup'=> 'required|string|max:50',
            'Description'  => 'nullable|string',
        ]);

        try {

            // Find branch to directly extract associated company_id
            $branch = Branch::findOrFail($request->branch_id);

            SupplierCategory::create([
                'company_id'    => $branch->company_id,
                'branch_id'     => $branch->id,
                'User_id'       => Auth::id(),
                'CategoryCode'  => strtoupper($request->CategoryCode),
                'CategoryName'  => strtoupper($request->CategoryName),
                'SupplierGroup' => strtoupper($request->SupplierGroup),
                'Description'   => $request->Description,
                'Status'        => 'Active',
                'AuditingStatus'=> 'Pending',
                'ReportStatus'  => 'Pending',
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Registered Supplier Category Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Show the form for editing the specified supplier category.
     */
    public function editsupplierCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = SupplierCategory::findOrFail($id);

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.suppliers.supplierCategory.editsupplierCategory', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified supplier category in storage.
     */
    public function updatesupplierCategory(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'    => 'required|integer',
            'CategoryCode' => 'required|string|max:50|unique:supplier_categories,CategoryCode,' . $id,
            'CategoryName' => 'required|string|max:255',
            'SupplierGroup'=> 'required|string|max:50',
            'Description'  => 'nullable|string',
        ]);

        try {

            $category = SupplierCategory::findOrFail($id);

            // Fetch selected branch and get its company_id automatically
            $branch = Branch::findOrFail($request->branch_id);

            $category->update([
                'company_id'    => $branch->company_id,
                'branch_id'     => $branch->id,
                'User_id'       => Auth::id(),
                'CategoryCode'  => strtoupper($request->CategoryCode),
                'CategoryName'  => strtoupper($request->CategoryName),
                'SupplierGroup' => strtoupper($request->SupplierGroup),
                'Description'   => $request->Description,
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Updated Supplier Category Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Display the specific supplier category profile.
     */
    public function viewsupplierCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = SupplierCategory::with(['company', 'branch', 'user'])->findOrFail($id);

            return view('in.suppliers.supplierCategory.viewsupplierCategory', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete the specified supplier category.
     */
    public function destroysupplierCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $category = SupplierCategory::findOrFail($id);

            $category->update([
                'Status' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Removed Supplier Category Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted supplier categories.
     */
    public function deletedsupplierCategory()
    {
        try {
            $data = SupplierCategory::where('Status', 'Deleted')
                ->with(['company', 'branch'])
                ->get();

            return view('in.suppliers.supplierCategory.deletedsupplierCategory', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted supplier category back to Active status.
     */
    public function restoresupplierCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $category = SupplierCategory::findOrFail($id);

            $category->update([
                'Status' => 'Active',
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Restored Supplier Category Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }


    /**
     * Display a listing of active suppliers.
     */
    public function suppliers()
    {
        try {
            $data = Supplier::where('Status', 'Active')
                ->with(['company', 'branch', 'user', 'supplierUser', 'category', 'member'])
                ->get();

            $categories = SupplierCategory::where('Status', 'Active')->get();
            $branchies  = Branch::where('Status', 'Active')->with('company')->get();
            $members    = Member::where('Status', 'Active')->get();

            return view('in.suppliers.suppliers.suppliers', compact('data', 'categories', 'branchies', 'members'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created MEMBER Supplier.
     */
    public function storeMemberSupplier(Request $request)
    {
        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'Member_id'           => 'required|integer|exists:members,id',
            'SupplierCategory_id' => 'required|integer|exists:supplier_categories,id',
            'SupplierCode'        => 'nullable|string|max:50|unique:suppliers,SupplierCode',
            'CreditLimit'         => 'nullable|numeric|min:0',
            'OpeningBalance'      => 'nullable|numeric|min:0',
            'PaymentTerms'        => 'nullable|string|max:100',
            'TinNumber'           => 'nullable|string|max:100',
            'VatNumber'           => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {

            $branch = Branch::findOrFail($request->branch_id);
            $member = Member::findOrFail($request->Member_id);

            // Generate Branch Abbreviation for Reference No
            $branchWords  = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Unique Reference Number
            do {
                $generatedRefNo = 'IFLSG/SUP/MBR/' . $branchPrefix . '/' . date('Y') . '/' . mt_rand(1000, 9999);
            } while (Supplier::where('SupplierRefNo', $generatedRefNo)->exists());

            Supplier::create([
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'User_id'             => Auth::id(),
                'supplier_id'         => null,
                'SupplierRefNo'       => $generatedRefNo,
                'SupplierCode'        => $request->SupplierCode ? strtoupper($request->SupplierCode) : null,
                'SupplierName'        => strtoupper($member->full_name ?? $member->name ?? 'MEMBER SUPPLIER'),
                'SupplierCategory_id' => $request->SupplierCategory_id,
                'Member_id'           => $member->id,
                'SupplierType'        => 'Member',
                'ContactPerson'       => $member->full_name ?? $member->name ?? null,
                'Phone'               => $member->phone ?? null,
                'AlternativePhone'    => $member->alternative_phone ?? null,
                'Email'               => $member->email ?? null,
                'Address'             => $member->address ?? null,
                'City'                => $member->city ?? null,
                'Region'              => $member->region ?? null,
                'Country'             => $member->country ?? 'Tanzania',
                'TinNumber'           => $request->TinNumber,
                'VatNumber'           => $request->VatNumber,
                'CreditLimit'         => $request->CreditLimit ?? 0.00,
                'OpeningBalance'      => $request->OpeningBalance ?? 0.00,
                'PaymentTerms'        => $request->PaymentTerms,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Member Supplier Registered Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Store a newly created EXTERNAL (Non-Member) Supplier.
     * Creates a User account first, then links supplier_id.
     */
/**
     * Store a newly created EXTERNAL (Non-Member) Supplier.
     * Creates a User account first, then links supplier_id.
     */
    public function storeExternalSupplier(Request $request)
    {
        $validated = $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'FirstName'           => 'required|string|max:100',
            'MiddleName'          => 'nullable|string|max:100',
            'LastName'            => 'required|string|max:100',
            'SupplierName'        => 'nullable|string|max:255',
            'SupplierCategory_id' => 'required|integer|exists:supplier_categories,id',
            'Phone'               => 'required|string|max:50',
            'Email'               => 'nullable|email|max:255|unique:users,email',
            'SupplierCode'        => 'nullable|string|max:50|unique:suppliers,SupplierCode',
            'ContactPerson'       => 'nullable|string|max:255',
            'Address'             => 'nullable|string',
            'City'                => 'nullable|string|max:100',
            'Region'              => 'nullable|string|max:100',
            'Country'             => 'nullable|string|max:100',
            'TinNumber'           => 'nullable|string|max:100',
            'VatNumber'           => 'nullable|string|max:100',
            'CreditLimit'         => 'nullable|numeric|min:0',
            'OpeningBalance'      => 'nullable|numeric|min:0',
            'PaymentTerms'        => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        // try {

            $branch = Branch::findOrFail($request->branch_id);

            // 1. Process Names & Initials
            $firstName  = trim($validated['FirstName']);
            $middleName = trim($validated['MiddleName'] ?? '');
            $lastName   = trim($validated['LastName']);

            $fullName = trim($lastName . ', ' . $firstName . ' ' . $middleName);

            $fInit = strtoupper(substr($firstName, 0, 1));
            $mInit = $middleName ? strtoupper(substr($middleName, 0, 1)) : 'X';
            $lInit = strtoupper(substr($lastName, 0, 1));
            $initials = $fInit . $mInit . $lInit;

            // 2. Generate Username: IFLSG/{INITIALS}/{YEAR}/00{NO}
            $userCount = User::count();
            $no        = sprintf('%03d', $userCount + 1);
            $year      = date('Y');
            $username  = 'IFLSG/' . $initials . '/' . $year . '/' . $no;

            // 3. Email Fallback
            $cleanUsernameSlug = Str::slug(str_replace('/', '_', $username));
            $email = $request->Email ?? ($cleanUsernameSlug . '@supplier.local');

            // 4. Create User Account for External Supplier
            $supplierUser = User::create([
                'username'          => $username,
                'name'              => strtoupper($fullName),
                'FirstName'         => strtoupper($firstName),
                'MiddleName'        => $middleName ? strtoupper($middleName) : null,
                'LastName'          => strtoupper($lastName),
                'email'             => $email,
                'phone'             => $request->Phone,
                'password'          => Hash::make('IFLSGSupp@1234'), // Default initial password
                'Role'              => User::ROLE_USER,
                'Status'            => 'Active',
                'User_id'           => Auth::id(),
            ]);

            // 5. Generate Branch Abbreviation for Supplier Reference No
            $branchWords  = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            do {
                $generatedRefNo = 'IFLSG/SUP/EXT/' . $branchPrefix . '/' . date('Y') . '/' . mt_rand(1000, 9999);
            } while (Supplier::where('SupplierRefNo', $generatedRefNo)->exists());

            // 6. Determine Final Supplier Display Name
            $finalSupplierName = $request->filled('SupplierName') 
                ? strtoupper($request->SupplierName) 
                : strtoupper($fullName);

            // 7. Create Supplier Record
            Supplier::create([
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'User_id'             => Auth::id(),
                'supplier_id'         => $supplierUser->id, // Linked user account
                'SupplierRefNo'       => $generatedRefNo,
                'SupplierCode'        => $request->SupplierCode ? strtoupper($request->SupplierCode) : null,
                'SupplierName'        => $finalSupplierName,
                'SupplierCategory_id' => $request->SupplierCategory_id,
                'Member_id'           => null,
                'SupplierType'        => 'External',
                'ContactPerson'       => $request->ContactPerson ?? $fullName,
                'Phone'               => $request->Phone,
                'AlternativePhone'    => $request->AlternativePhone,
                'Email'               => $request->Email,
                'Address'             => $request->Address,
                'City'                => $request->City,
                'Region'              => $request->Region,
                'Country'             => $request->Country ?? 'Tanzania',
                'TinNumber'           => $request->TinNumber,
                'VatNumber'           => $request->VatNumber,
                'CreditLimit'         => $request->CreditLimit ?? 0.00,
                'OpeningBalance'      => $request->OpeningBalance ?? 0.00,
                'PaymentTerms'        => $request->PaymentTerms,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'External Supplier & User Account Created Successfully'
            );

            return back();

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );

        //     return back();
        // }
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function editSupplier($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = Supplier::findOrFail($id);

            $categories = SupplierCategory::where('Status', 'Active')->get();
            $branchies  = Branch::where('Status', 'Active')->with('company')->get();
            $members    = Member::where('Status', 'Active')->get();

            return view('in.suppliers.suppliers.editSupplier', compact('data', 'categories', 'branchies', 'members'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified supplier in storage.
     */
    public function updateSupplier(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'SupplierName'        => 'required|string|max:255',
            'SupplierCategory_id' => 'required|integer|exists:supplier_categories,id',
            'SupplierCode'        => 'nullable|string|max:50|unique:suppliers,SupplierCode,' . $id,
            'Phone'               => 'nullable|string|max:50',
            'CreditLimit'         => 'nullable|numeric|min:0',
            'OpeningBalance'      => 'nullable|numeric|min:0',
        ]);

        try {
            $branch = Branch::findOrFail($request->branch_id);

            $supplier->update([
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'SupplierCode'        => $request->SupplierCode ? strtoupper($request->SupplierCode) : null,
                'SupplierName'        => strtoupper($request->SupplierName),
                'SupplierCategory_id' => $request->SupplierCategory_id,
                'ContactPerson'       => $request->ContactPerson,
                'Phone'               => $request->Phone,
                'AlternativePhone'    => $request->AlternativePhone,
                'Email'               => $request->Email,
                'Address'             => $request->Address,
                'City'                => $request->City,
                'Region'              => $request->Region,
                'Country'             => $request->Country ?? 'Tanzania',
                'TinNumber'           => $request->TinNumber,
                'VatNumber'           => $request->VatNumber,
                'CreditLimit'         => $request->CreditLimit ?? 0.00,
                'OpeningBalance'      => $request->OpeningBalance ?? 0.00,
                'PaymentTerms'        => $request->PaymentTerms,
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Supplier Updated Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display the specified supplier profile.
     */
    public function viewSupplier($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = Supplier::with(['company', 'branch', 'user', 'supplierUser', 'category', 'member'])->findOrFail($id);

            return view('in.suppliers.suppliers.viewSupplier', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete the specified supplier.
     */
    public function destroySupplier($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $supplier = Supplier::findOrFail($id);

            $supplier->update([
                'Status' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Supplier Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted suppliers.suppliers.
     */
    public function deletedSuppliers()
    {
        try {
            $data = Supplier::where('Status', 'Deleted')
                ->with(['company', 'branch', 'category', 'member'])
                ->get();

            return view('in.suppliers.suppliers.deletedSuppliers', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted supplier back to Active status.
     */
    public function restoreSupplier($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $supplier = Supplier::findOrFail($id);

            $supplier->update([
                'Status' => 'Active',
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Supplier Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function supplierTender()
    {
        // try {
            $data = SupplierTender::where('Status', 'Active')
                ->with(['company', 'branch', 'user', 'purchaseRequest', 'awardedSupplier'])
                ->get();

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $purchaseRequests = PurchaseRequest::where('Status', 'Active')->get();
            $suppliers = Supplier::where('Status', 'Active')->get();

            return view('in.suppliers.supplierTender.supplierTender', compact('data', 'companies', 'branchies', 'purchaseRequests', 'suppliers'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Store a newly created supplier tender in storage.
     */
    public function storesupplierTender(Request $request)
    {
        $request->validate([
            'branch_id'           => 'required|integer',
            'tender_no'           => 'required|string|max:50|unique:supplier_tenders,tender_no',
            'tender_title'        => 'required|string|max:255',
            'purchase_request_id' => 'nullable|integer',
            'description'         => 'nullable|string',
            'tender_date'         => 'required|date',
            'closing_date'        => 'required|date|after_or_equal:tender_date',
            'tender_status'       => 'required|string|max:50',
            'evaluation_method'   => 'nullable|string|max:100',
            'estimated_budget'    => 'nullable|numeric|min:0',
            'currency_code'       => 'nullable|string|max:10',
            'awarded_supplier_id' => 'nullable|integer',
            'awarded_at'          => 'nullable|date',
            'award_comment'       => 'nullable|string',
        ]);

        try {

            // Find branch to directly extract associated company_id
            $branch = Branch::findOrFail($request->branch_id);

            SupplierTender::create([
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'User_id'             => Auth::id(),
                'created_by'          => Auth::id(),
                'tender_no'           => strtoupper($request->tender_no),
                'tender_title'        => strtoupper($request->tender_title),
                'purchase_request_id' => $request->purchase_request_id,
                'description'         => $request->description,
                'tender_date'         => $request->tender_date,
                'closing_date'        => $request->closing_date,
                'tender_status'       => $request->tender_status,
                'evaluation_method'   => $request->evaluation_method,
                'estimated_budget'    => $request->estimated_budget,
                'currency_code'       => $request->currency_code ? strtoupper($request->currency_code) : 'TZS',
                'awarded_supplier_id' => $request->awarded_supplier_id,
                'awarded_at'          => $request->awarded_at,
                'award_comment'       => $request->award_comment,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Registered Supplier Tender Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Show the form for editing the specified supplier tender.
     */
    public function editsupplierTender($id)
    {
        // try {
            $id = Crypt::decrypt($id);
            $data = SupplierTender::findOrFail($id);

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $purchaseRequests = PurchaseRequest::where('Status', 'Active')->get();
            $suppliers = Supplier::where('Status', 'Active')->get();

            return view('in.suppliers.supplierTender.editsupplierTender', compact('data', 'companies', 'branchies', 'purchaseRequests', 'suppliers'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Update the specified supplier tender in storage.
     */
    public function updatesupplierTender(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'           => 'required|integer',
            'tender_no'           => 'required|string|max:50|unique:supplier_tenders,tender_no,' . $id,
            'tender_title'        => 'required|string|max:255',
            'purchase_request_id' => 'nullable|integer',
            'description'         => 'nullable|string',
            'tender_date'         => 'required|date',
            'closing_date'        => 'required|date|after_or_equal:tender_date',
            'tender_status'       => 'required|string|max:50',
            'evaluation_method'   => 'nullable|string|max:100',
            'estimated_budget'    => 'nullable|numeric|min:0',
            'currency_code'       => 'nullable|string|max:10',
            'awarded_supplier_id' => 'nullable|integer',
            'awarded_at'          => 'nullable|date',
            'award_comment'       => 'nullable|string',
        ]);

        try {

            $tender = SupplierTender::findOrFail($id);

            // Fetch selected branch and get its company_id automatically
            $branch = Branch::findOrFail($request->branch_id);

            $tender->update([
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'updated_by'          => Auth::id(),
                'tender_no'           => strtoupper($request->tender_no),
                'tender_title'        => strtoupper($request->tender_title),
                'purchase_request_id' => $request->purchase_request_id,
                'description'         => $request->description,
                'tender_date'         => $request->tender_date,
                'closing_date'        => $request->closing_date,
                'tender_status'       => $request->tender_status,
                'evaluation_method'   => $request->evaluation_method,
                'estimated_budget'    => $request->estimated_budget,
                'currency_code'       => $request->currency_code ? strtoupper($request->currency_code) : 'TZS',
                'awarded_supplier_id' => $request->awarded_supplier_id,
                'awarded_at'          => $request->awarded_at,
                'award_comment'       => $request->award_comment,
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Updated Supplier Tender Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Display the specific supplier tender details.
     */
    public function viewsupplierTender($id)
    {
        $id = Crypt::decrypt($id);
        // try {

            $data = SupplierTender::with([
                'company',
                'branch',
                'user',
                'creator',
                'updater',
                'purchaseRequest',
                'awardedSupplier',
                'invitations',
                'quotations',
                'awards'
            ])->findOrFail($id);

            return view('in.suppliers.supplierTender.viewsupplierTender', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Soft delete the specified supplier tender.
     */
    public function destroysupplierTender($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $tender = SupplierTender::findOrFail($id);

            $tender->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $tender->delete(); // Leverages SoftDeletes trait on deleted_at

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Removed Supplier Tender Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted supplier tenders.
     */
    public function deletedsupplierTender()
    {
        try {
            $data = SupplierTender::onlyTrashed()
                ->orWhere('Status', 'Deleted')
                ->with(['company', 'branch', 'user', 'deleter', 'purchaseRequest'])
                ->get();

            return view('in.suppliers.supplierTender.deletedsupplierTender', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted supplier tender back to Active status.
     */
    public function restoresupplierTender($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $tender = SupplierTender::withTrashed()->findOrFail($id);

            $tender->restore();
            $tender->update([
                'Status'     => 'Active',
                'deleted_by' => null,
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Restored Supplier Tender Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }



    /**
     * Display a listing of active supplier quotations.
     */
    public function supplierQuotation()
    {
        // try {
            $data = SupplierQuotation::with(['tender', 'purchaseRequest', 'supplier', 'branch', 'creator'])
                ->latest()
                ->get();

            return view('in.suppliers.supplierQuotation.supplierQuotation', compact('data'));
        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show the form for creating a new supplier quotation.
     */
    public function createsupplierQuotation()
    {
        try {
            $tenders          = SupplierTender::where('tender_status', 'Published')->get();
            $purchaseRequests = PurchaseRequest::all();
            $suppliers        = Supplier::all();
            $branches         = Branch::all();
            $products         = Product::all();
            $unitsOfMeasure   = UnitOfMeasure::all();

            return view('in.suppliers.supplierQuotation.createsupplierQuotation', compact(
                'tenders',
                'purchaseRequests',
                'suppliers',
                'branches',
                'products',
                'unitsOfMeasure'
            ));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created supplier quotation and its line items.
     */
    public function storesupplierQuotation(Request $request)
    {
        // try {
            $request->validate([
                'quotation_no'          => 'required|string|unique:supplier_quotations,quotation_no',
                'supplier_id'           => 'required|integer',
                'branch_id'             => 'required|integer',
                'quotation_date'        => 'required|date',
                'valid_until'           => 'nullable|date|after_or_equal:quotation_date',
                'currency_code'         => 'nullable|string|max:10',
                'attachment'            => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
                'items'                 => 'required|array|min:1',
                'items.*.product_id'    => 'nullable|integer',
                'items.*.quantity'      => 'required|numeric|min:0.001',
                'items.*.unit_price'    => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('supplier_quotations', 'public');
            }

            $quotation = SupplierQuotation::create([
                'quotation_no'          => $request->quotation_no,
                'tender_id'             => $request->tender_id,
                'purchase_request_id'   => $request->purchase_request_id,
                'supplier_id'           => $request->supplier_id,
                'supplier_quotation_no' => $request->supplier_quotation_no,
                'quotation_date'        => $request->quotation_date,
                'valid_until'           => $request->valid_until,
                'currency_code'         => $request->currency_code ?? 'TZS',
                'subtotal'              => $request->subtotal ?? 0.00,
                'tax_amount'            => $request->tax_amount ?? 0.00,
                'discount_amount'       => $request->discount_amount ?? 0.00,
                'delivery_amount'       => $request->delivery_amount ?? 0.00,
                'total_amount'          => $request->total_amount ?? 0.00,
                'quotation_status'      => $request->quotation_status ?? 'Pending',
                'payment_terms'         => $request->payment_terms,
                'delivery_terms'        => $request->delivery_terms,
                'notes'                 => $request->notes,
                'attachment_path'       => $attachmentPath,
                'company_id'            => Auth::user()->company_id ?? 1,
                'branch_id'             => $request->branch_id,
                'User_id'               => Auth::id(),
                'Status'                => 'Active',
                'AuditingStatus'        => 'Pending',
                'ReportStatus'          => 'Pending',
                'created_by'            => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                SupplierQuotationItem::create([
                    'supplier_quotation_id'    => $quotation->id,
                    'purchase_request_item_id' => $item['purchase_request_item_id'] ?? null,
                    'product_id'               => $item['product_id'] ?? null,
                    'unit_of_measure_id'       => $item['unit_of_measure_id'] ?? null,
                    'description'              => $item['description'] ?? null,
                    'quantity'                 => $item['quantity'],
                    'unit_price'               => $item['unit_price'],
                    'discount_amount'          => $item['discount_amount'] ?? 0.00,
                    'tax_rate'                 => $item['tax_rate'] ?? 0.00,
                    'tax_amount'               => $item['tax_amount'] ?? 0.00,
                    'total_amount'             => $item['total_amount'] ?? 0.00,
                    'delivery_date'            => $item['delivery_date'] ?? null,
                    'item_status'              => $item['item_status'] ?? 'Active',
                    'remarks'                  => $item['remarks'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('supplierQuotation')->with('success', 'Supplier Quotation created successfully.');
        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Display details for a specific supplier quotation (Includes printable layout view).
     */
    public function viewsupplierQuotation($id)
    {
        // try {
            $decryptedId = decrypt($id);
            $data = SupplierQuotation::with([
                'tender',
                'purchaseRequest',
                'supplier',
                'items.product',
                'items.unitOfMeasure',
                'company',
                'branch',
                'user',
                'creator',
                'updater'
            ])->findOrFail($decryptedId);

            return view('in.suppliers.supplierQuotation.viewsupplierQuotation', compact('data'));
        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show the form for editing an existing supplier quotation.
     */
    public function editsupplierQuotation($id)
    {
        try {
            $decryptedId      = decrypt($id);
            $data             = SupplierQuotation::with('items')->findOrFail($decryptedId);
            $tenders          = SupplierTender::all();
            $purchaseRequests = PurchaseRequest::all();
            $suppliers        = Supplier::all();
            $branches         = Branch::all();
            $products         = Product::all();
            $unitsOfMeasure   = UnitOfMeasure::all();

            return view('in.suppliers.supplierQuotation.editsupplierQuotation', compact(
                'data',
                'tenders',
                'purchaseRequests',
                'suppliers',
                'branches',
                'products',
                'unitsOfMeasure'
            ));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified supplier quotation and synchronize items.
     */
    public function updatesupplierQuotation(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
            $quotation   = SupplierQuotation::findOrFail($decryptedId);

            $request->validate([
                'quotation_no'       => 'required|string|unique:supplier_quotations,quotation_no,' . $quotation->id,
                'supplier_id'        => 'required|integer',
                'branch_id'          => 'required|integer',
                'quotation_date'     => 'required|date',
                'attachment'         => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
                'items'              => 'required|array|min:1',
                'items.*.quantity'   => 'required|numeric|min:0.001',
                'items.*.unit_price' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            $attachmentPath = $quotation->attachment_path;
            if ($request->hasFile('attachment')) {
                if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                    Storage::disk('public')->delete($attachmentPath);
                }
                $attachmentPath = $request->file('attachment')->store('supplier_quotations', 'public');
            }

            $quotation->update([
                'quotation_no'          => $request->quotation_no,
                'tender_id'             => $request->tender_id,
                'purchase_request_id'   => $request->purchase_request_id,
                'supplier_id'           => $request->supplier_id,
                'supplier_quotation_no' => $request->supplier_quotation_no,
                'quotation_date'        => $request->quotation_date,
                'valid_until'           => $request->valid_until,
                'currency_code'         => $request->currency_code ?? $quotation->currency_code,
                'subtotal'              => $request->subtotal ?? 0.00,
                'tax_amount'            => $request->tax_amount ?? 0.00,
                'discount_amount'       => $request->discount_amount ?? 0.00,
                'delivery_amount'       => $request->delivery_amount ?? 0.00,
                'total_amount'          => $request->total_amount ?? 0.00,
                'quotation_status'      => $request->quotation_status,
                'payment_terms'         => $request->payment_terms,
                'delivery_terms'        => $request->delivery_terms,
                'notes'                 => $request->notes,
                'attachment_path'       => $attachmentPath,
                'branch_id'             => $request->branch_id,
                'updated_by'            => Auth::id(),
            ]);

            // Replace line items
            SupplierQuotationItem::where('supplier_quotation_id', $quotation->id)->delete();

            foreach ($request->items as $item) {
                SupplierQuotationItem::create([
                    'supplier_quotation_id'    => $quotation->id,
                    'purchase_request_item_id' => $item['purchase_request_item_id'] ?? null,
                    'product_id'               => $item['product_id'] ?? null,
                    'unit_of_measure_id'       => $item['unit_of_measure_id'] ?? null,
                    'description'              => $item['description'] ?? null,
                    'quantity'                 => $item['quantity'],
                    'unit_price'               => $item['unit_price'],
                    'discount_amount'          => $item['discount_amount'] ?? 0.00,
                    'tax_rate'                 => $item['tax_rate'] ?? 0.00,
                    'tax_amount'               => $item['tax_amount'] ?? 0.00,
                    'total_amount'             => $item['total_amount'] ?? 0.00,
                    'delivery_date'            => $item['delivery_date'] ?? null,
                    'item_status'              => $item['item_status'] ?? 'Active',
                    'remarks'                  => $item['remarks'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('supplierQuotation')->with('success', 'Supplier Quotation updated successfully.');
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete a supplier quotation.
     */
    public function destroysupplierQuotation($id)
    {
        try {
            $decryptedId = decrypt($id);
            $quotation   = SupplierQuotation::findOrFail($decryptedId);

            $quotation->update(['deleted_by' => Auth::id()]);
            $quotation->delete();

            return redirect()->route('supplierQuotation')->with('success', 'Supplier Quotation deleted successfully.');
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted supplier quotations.
     */
    public function deletedsupplierQuotation()
    {
        try {
            $data = SupplierQuotation::onlyTrashed()
                ->with(['supplier', 'branch', 'deleter'])
                ->latest()
                ->get();

            return view('in.suppliers.supplierQuotation.deletedsupplierQuotation', compact('data'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted supplier quotation.
     */
    public function restoresupplierQuotation($id)
    {
        try {
            $decryptedId = decrypt($id);
            $quotation   = SupplierQuotation::onlyTrashed()->findOrFail($decryptedId);

            $quotation->restore();
            $quotation->update(['deleted_by' => null]);

            return redirect()->route('deletedsupplierQuotation')->with('success', 'Supplier Quotation restored successfully.');
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }


    /**
     * Display a listing of quotation evaluations.
     */
    public function quotationEvaluation()
    {
        // try {
            $data = QuotationEvaluation::with([
                'quotation.supplier',
                'evaluator',
                'branch',
                'creator'
            ])
            ->latest()
            ->get();
            $supplierQuotations = SupplierQuotation::with('supplier')->get();
            $branches = Branch::all();
            return view('in.suppliers.quotationEvaluation.quotationEvaluation', compact('data', 'supplierQuotations', 'branches'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show the form for creating a new quotation evaluation.
     */
    public function createquotationEvaluation()
    {
        // try {
            $supplierQuotations = SupplierQuotation::with('supplier')->get();
            $branches = Branch::all();

            return view('in.suppliers.quotationEvaluation.createquotationEvaluation', compact('supplierQuotations', 'branches'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Store a newly created quotation evaluation in storage.
     */
    public function storequotationEvaluation(Request $request)
    {
        // try {
            $request->validate([
                'supplier_quotation_id' => 'required|integer',
                'evaluation_no'        => 'required|string|unique:quotation_evaluations,evaluation_no',
                'branch_id'            => 'required|integer',
                'technical_score'      => 'required|numeric|min:0|max:100',
                'price_score'          => 'required|numeric|min:0|max:100',
                'delivery_score'       => 'required|numeric|min:0|max:100',
                'quality_score'        => 'required|numeric|min:0|max:100',
                'evaluation_status'    => 'nullable|string',
                'recommendation'       => 'nullable|string',
                'evaluation_comment'   => 'nullable|string',
            ]);

            DB::beginTransaction();

            $technicalScore = (float) $request->technical_score;
            $priceScore     = (float) $request->price_score;
            $deliveryScore  = (float) $request->delivery_score;
            $qualityScore   = (float) $request->quality_score;

            $totalScore = ($technicalScore + $priceScore + $deliveryScore + $qualityScore) / 4;

            QuotationEvaluation::create([
                'supplier_quotation_id' => $request->supplier_quotation_id,
                'evaluation_no'        => $request->evaluation_no,
                'technical_score'      => $technicalScore,
                'price_score'          => $priceScore,
                'delivery_score'       => $deliveryScore,
                'quality_score'        => $qualityScore,
                'total_score'          => $totalScore,
                'evaluation_status'    => $request->evaluation_status ?? 'Pending',
                'recommendation'       => $request->recommendation,
                'evaluation_comment'   => $request->evaluation_comment,
                'evaluated_by'         => Auth::id(),
                'evaluated_at'         => now(),
                'company_id'           => Auth::user()->company_id ?? 1,
                'branch_id'            => $request->branch_id,
                'User_id'              => Auth::id(),
                'Status'               => 1,
                'AuditingStatus'       => 'Pending',
                'ReportStatus'         => 'Pending',
                'created_by'           => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('quotationEvaluation')->with('success', 'Quotation Evaluation created successfully.');

        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show the form for editing the specified quotation evaluation.
     */
    public function editquotationEvaluation($id)
    {
        // try {
            $evaluationId = decrypt($id);
            $data = QuotationEvaluation::findOrFail($evaluationId);

            $supplierQuotations = SupplierQuotation::with('supplier')->get();
            $branches = Branch::all();

            return view('in.suppliers.quotationEvaluation.editquotationEvaluation', compact('data', 'supplierQuotations', 'branches'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Update the specified quotation evaluation in storage.
     */
    public function updatequotationEvaluation(Request $request, $id)
    {
        // try {
            $evaluationId = decrypt($id);

            $request->validate([
                'supplier_quotation_id' => 'required|integer',
                'evaluation_no'        => 'required|string|unique:quotation_evaluations,evaluation_no,' . $evaluationId,
                'branch_id'            => 'required|integer',
                'technical_score'      => 'required|numeric|min:0|max:100',
                'price_score'          => 'required|numeric|min:0|max:100',
                'delivery_score'       => 'required|numeric|min:0|max:100',
                'quality_score'        => 'required|numeric|min:0|max:100',
                'evaluation_status'    => 'nullable|string',
                'recommendation'       => 'nullable|string',
                'evaluation_comment'   => 'nullable|string',
            ]);

            DB::beginTransaction();

            $evaluation = QuotationEvaluation::findOrFail($evaluationId);

            $technicalScore = (float) $request->technical_score;
            $priceScore     = (float) $request->price_score;
            $deliveryScore  = (float) $request->delivery_score;
            $qualityScore   = (float) $request->quality_score;

            $totalScore = ($technicalScore + $priceScore + $deliveryScore + $qualityScore) / 4;

            $evaluation->update([
                'supplier_quotation_id' => $request->supplier_quotation_id,
                'evaluation_no'        => $request->evaluation_no,
                'technical_score'      => $technicalScore,
                'price_score'          => $priceScore,
                'delivery_score'       => $deliveryScore,
                'quality_score'        => $qualityScore,
                'total_score'          => $totalScore,
                'evaluation_status'    => $request->evaluation_status ?? $evaluation->evaluation_status,
                'recommendation'       => $request->recommendation,
                'evaluation_comment'   => $request->evaluation_comment,
                'branch_id'            => $request->branch_id,
                'updated_by'           => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('quotationEvaluation')->with('success', 'Quotation Evaluation updated successfully.');

        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Display the specified quotation evaluation.
     */
    public function viewquotationEvaluation($id)
    {
        // try {
            $evaluationId = decrypt($id);

            $data = QuotationEvaluation::with([
                'quotation.supplier',
                'quotation.items.product',
                'evaluator',
                'branch',
                'company',
                'creator'
            ])->findOrFail($evaluationId);

            return view('in.suppliers.quotationEvaluation.viewquotationEvaluation', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Soft delete the specified quotation evaluation.
     */
    public function destroyquotationEvaluation($id)
    {
        // try {
            $evaluationId = decrypt($id);

            $evaluation = QuotationEvaluation::findOrFail($evaluationId);
            $evaluation->update([
                'deleted_by' => Auth::id()
            ]);
            $evaluation->delete();

            return redirect()->route('quotationEvaluation')->with('success', 'Quotation Evaluation moved to trash.');

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Display a listing of soft-deleted quotation evaluations.
     */
    public function deletedquotationEvaluation()
    {
        // try {
            $data = QuotationEvaluation::onlyTrashed()
                ->with(['quotation.supplier', 'deleter', 'branch'])
                ->latest()
                ->get();

            return view('in.suppliers.quotationEvaluation.deletedquotationEvaluation', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Restore a soft-deleted quotation evaluation.
     */
    public function restorequotationEvaluation($id)
    {
        // try {
            $evaluationId = decrypt($id);

            $evaluation = QuotationEvaluation::onlyTrashed()->findOrFail($evaluationId);
            $evaluation->restore();
            $evaluation->update([
                'deleted_by' => null
            ]);

            return redirect()->route('deletedquotationEvaluation')->with('success', 'Quotation Evaluation restored successfully.');

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }


    /**
     * Display a listing of active supplier awards.
     */
    public function supplierAward(Request $request)
    {
        // try {
            $query = SupplierAward::where('Status', 'Active')
                ->with([
                    'tender',
                    'supplier',
                    'quotation',
                    'purchaseRequest',
                    'approver',
                    'company',
                    'branch',
                    'creator'
                ]);

            if ($request->filled('award_status')) {
                $query->where('award_status', $request->award_status);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('award_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('award_date', '<=', $request->to_date);
            }

            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.supplierawards.supplierAward', compact('data', 'suppliers'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show form for creating a new supplier award.
     */
    public function createsupplierAward()
    {
        try {
            $tenders = SupplierTender::where('Status', 'Active')->get();
            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();
            $quotations = SupplierQuotation::where('Status', 'Active')->get();
            $purchaseRequests = PurchaseRequest::where('Status', 'Approved')->get();
            $branchies = Branch::where('Status', 'Active')->get();

            return view('in.suppliers.supplierawards.createsupplierAward', compact(
                'tenders',
                'suppliers',
                'quotations',
                'purchaseRequests',
                'branchies'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created supplier award in storage.
     */
    public function storesupplierAward(Request $request)
    {
        $request->validate([
            'tender_id'             => 'nullable|integer|exists:supplier_tenders,id',
            'supplier_id'           => 'required|integer|exists:suppliers,id',
            'supplier_quotation_id' => 'nullable|integer|exists:supplier_quotations,id',
            'purchase_request_id'   => 'nullable|integer|exists:purchase_requests,id',
            'award_date'            => 'required|date',
            'award_amount'          => 'required|numeric|min:0',
            'currency_code'         => 'required|string|max:10',
            'award_comment'         => 'nullable|string',
            'branch_id'             => 'required|integer|exists:branchies,id',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);
            $awardNo = $this->generateAwardNumber();

            SupplierAward::create([
                'award_no'              => $awardNo,
                'tender_id'             => $request->tender_id,
                'supplier_id'           => $request->supplier_id,
                'supplier_quotation_id' => $request->supplier_quotation_id,
                'purchase_request_id'   => $request->purchase_request_id,
                'award_date'            => $request->award_date,
                'award_amount'          => $request->award_amount,
                'currency_code'         => strtoupper($request->currency_code),
                'award_status'          => 'Pending',
                'award_comment'         => $request->award_comment,
                'company_id'            => $branch->company_id,
                'branch_id'             => $branch->id,
                'User_id'               => $user->id,
                'Status'                => 'Active',
                'AuditingStatus'        => 'Pending',
                'ReportStatus'          => 'Pending',
                'created_by'            => $user->id,
                'updated_by'            => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Supplier Award Registered Successfully (' . $awardNo . ')'
            );

            return redirect()->route('supplierAward');

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );

        //     return back()->withInput();
        // }
    }

    /**
     * Display profile/details of a specific supplier award.
     */
    public function viewsupplierAward($id)
    {
        // try {
            $id = Crypt::decrypt($id);

            $data = SupplierAward::with([
                'tender',
                'supplier',
                'quotation',
                'purchaseRequest',
                'approver',
                'company',
                'branch',
                'user',
                'creator',
                'updater',
                'purchaseOrders'
            ])->findOrFail($id);

            return view('in.suppliers.supplierawards.viewsupplierAward', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show form for editing the specified supplier award.
     */
    public function editsupplierAward($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = SupplierAward::findOrFail($id);

            if ($data->award_status === 'Approved') {
                Alert::warning(
                    'Action Denied',
                    'Approved supplier awards cannot be edited.'
                );
                return back();
            }

            $tenders = SupplierTender::where('Status', 'Active')->get();
            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();
            $quotations = SupplierQuotation::where('Status', 'Active')->get();
            $purchaseRequests = PurchaseRequest::where('Status', 'Approved')->get();
            $branchies = Branch::where('Status', 'Active')->get();

            return view('in.suppliers.supplierawards.editsupplierAward', compact(
                'data',
                'tenders',
                'suppliers',
                'quotations',
                'purchaseRequests',
                'branchies'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified supplier award in storage.
     */
    public function updatesupplierAward(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'tender_id'             => 'nullable|integer|exists:supplier_tenders,id',
            'supplier_id'           => 'required|integer|exists:suppliers,id',
            'supplier_quotation_id' => 'nullable|integer|exists:supplier_quotations,id',
            'purchase_request_id'   => 'nullable|integer|exists:purchase_requests,id',
            'award_date'            => 'required|date',
            'award_amount'          => 'required|numeric|min:0',
            'currency_code'         => 'required|string|max:10',
            'award_comment'         => 'nullable|string',
            'branch_id'             => 'required|integer|exists:branchies,id',
        ]);

        DB::beginTransaction();
        try {
            $award = SupplierAward::findOrFail($id);

            if ($award->award_status === 'Approved') {
                Alert::warning('Warning', 'Approved supplier awards cannot be updated.');
                return back();
            }

            $branch = Branch::findOrFail($request->branch_id);

            $award->update([
                'tender_id'             => $request->tender_id,
                'supplier_id'           => $request->supplier_id,
                'supplier_quotation_id' => $request->supplier_quotation_id,
                'purchase_request_id'   => $request->purchase_request_id,
                'award_date'            => $request->award_date,
                'award_amount'          => $request->award_amount,
                'currency_code'         => strtoupper($request->currency_code),
                'award_comment'         => $request->award_comment,
                'company_id'            => $branch->company_id,
                'branch_id'             => $branch->id,
                'updated_by'            => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Supplier Award Updated Successfully'
            );

            return redirect()->route('viewsupplierAward', encrypt($award->id));

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Approve the specified supplier award.
     */
    public function approvesupplierAward(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $award = SupplierAward::findOrFail($id);

            if ($award->award_status === 'Approved') {
                Alert::warning('Notice', 'This award is already approved.');
                return back();
            }

            $award->update([
                'award_status'  => 'Approved',
                'approved_by'   => $user->id,
                'approved_at'   => now(),
                'award_comment' => $request->award_comment ?? $award->award_comment,
                'updated_by'    => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Supplier Award Approved Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Reject the specified supplier award.
     */
    public function rejectsupplierAward(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'award_comment' => 'required|string|min:3',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $award = SupplierAward::findOrFail($id);

            $award->update([
                'award_status'  => 'Rejected',
                'award_comment' => $request->award_comment,
                'updated_by'    => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Supplier Award Rejected Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Soft delete the specified supplier award.
     */
    public function destroysupplierAward($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $award = SupplierAward::findOrFail($id);

            $award->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $award->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Supplier Award Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted supplier awards.
     */
    public function deletedsupplierAward()
    {
        try {
            $data = SupplierAward::onlyTrashed()
                ->with(['tender', 'supplier', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.suppliers.supplierawards.deletedsupplierAward', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted supplier award.
     */
    public function restoresupplierAward($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $award = SupplierAward::withTrashed()->findOrFail($id);

            $award->restore();

            $award->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Supplier Award Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Generate unique sequential award number (SA-YYYY-00001).
     */
    private function generateAwardNumber(): string
    {
        $year = date('Y');

        $lastAward = SupplierAward::where('award_no', 'like', 'IFLSG/SA/' . $year . '-%')
            ->orderByDesc('id')
            ->first();

        if (!$lastAward) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastAward->award_no, -5);
            $number = $lastNumber + 1;
        }

        return 'IFLSG/SA' . $year . '/' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }



    /**
     * Display a listing of registered supplier invoices.
     */
    public function supplierInvoice(Request $request)
    {
        try {
            $query = SupplierInvoice::where('Status', 'Active')
                ->with(['supplier', 'purchaseOrder', 'goodsReceipt', 'branch', 'verifier']);

            if ($request->filled('invoice_status')) {
                $query->where('invoice_status', $request->invoice_status);
            }

            if ($request->filled('verification_status')) {
                $query->where('verification_status', $request->verification_status);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('invoice_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('invoice_date', '<=', $request->to_date);
            }

            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();
            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.supplierinvoices.supplierInvoice', compact('data', 'suppliers'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display list of Goods Receipts pending invoicing.
     */
    public function unInvoicedGoodsReceipts(Request $request)
    {
        // try {
            // Find GRNs that are received and either Uninvoiced or Partially Invoiced
            $query = GoodsReceipt::where('Status', 'Active')
                ->where('receipt_status', 'Received')
                ->whereIn('invoiced_status', ['Uninvoiced', 'Partially Invoiced'])
                ->with(['supplier', 'purchaseOrder', 'branch', 'items.product']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('receipt_no', 'like', "%{$search}%")
                      ->orWhereHas('purchaseOrder', function ($pq) use ($search) {
                          $pq->where('purchase_order_no', 'like', "%{$search}%");
                      })
                      ->orWhereHas('supplier', function ($sq) use ($search) {
                          $sq->where('SupplierName', 'like', "%{$search}%");
                      });
                });
            }

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.supplierinvoices.unInvoicedGoodsReceipts', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show form to generate invoice against a specific Goods Receipt.
     */
    public function createsupplierInvoice($encryptedGrnId)
    {
        try {
            $grnId = Crypt::decrypt($encryptedGrnId);

            $goodsReceipt = GoodsReceipt::where('Status', 'Active')
                ->with(['supplier', 'purchaseOrder', 'branch', 'items.product', 'items.unitOfMeasure', 'items.purchaseOrderItem'])
                ->findOrFail($grnId);

            if ($goodsReceipt->invoiced_status === 'Invoiced') {
                Alert::warning('Notice', 'This Goods Receipt has already been fully invoiced.');
                return redirect()->route('unInvoicedGoodsReceipts');
            }

            return view('in.suppliers.supplierinvoices.createsupplierInvoice', compact('goodsReceipt'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store new Supplier Invoice in storage and update Goods Receipt invoiced_status.
     */
    public function storesupplierInvoice(Request $request, $encryptedGrnId)
    {
        $grnId = Crypt::decrypt($encryptedGrnId);

        $request->validate([
            'supplier_invoice_no'       => 'required|string|max:100',
            'invoice_date'              => 'required|date',
            'due_date'                  => 'required|date|after_or_equal:invoice_date',
            'currency_code'             => 'required|string|max:10',
            'notes'                     => 'nullable|string',
            'attachment'                => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'items'                     => 'required|array|min:1',
            'items.*.grn_item_id'       => 'required|integer|exists:goods_receipt_items,id',
            'items.*.po_item_id'        => 'nullable|integer|exists:purchase_order_items,id',
            'items.*.product_id'        => 'required|integer|exists:products,id',
            'items.*.unit_of_measure_id'=> 'nullable|integer|exists:units_of_measure,id',
            'items.*.quantity'          => 'required|numeric|min:0.001',
            'items.*.unit_price'        => 'required|numeric|min:0',
            'items.*.tax_rate'          => 'nullable|numeric|min:0|max:100',
            'items.*.discount_amount'   => 'nullable|numeric|min:0',
            'items.*.description'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $goodsReceipt = GoodsReceipt::with('items')->findOrFail($grnId);
            $sysInvoiceNo = $this->generateInvoiceNumber();

            // Attachment upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('supplier_invoices', 'public');
            }

            $supplierInvoice = SupplierInvoice::create([
                'invoice_no'          => $sysInvoiceNo,
                'supplier_invoice_no' => $request->supplier_invoice_no,
                'supplier_id'         => $goodsReceipt->supplier_id,
                'purchase_order_id'   => $goodsReceipt->purchase_order_id,
                'goods_receipt_id'    => $goodsReceipt->id,
                'invoice_date'        => $request->invoice_date,
                'due_date'            => $request->due_date,
                'currency_code'       => strtoupper($request->currency_code),
                'subtotal'            => 0,
                'tax_amount'          => 0,
                'discount_amount'     => 0,
                'total_amount'        => 0,
                'paid_amount'         => 0,
                'outstanding_amount'  => 0,
                'invoice_status'      => 'Unpaid',
                'verification_status' => 'Pending',
                'notes'               => $request->notes,
                'attachment_path'     => $attachmentPath,
                'company_id'          => $goodsReceipt->company_id,
                'branch_id'           => $goodsReceipt->branch_id,
                'User_id'             => $user->id,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
                'created_by'          => $user->id,
                'updated_by'          => $user->id,
            ]);

            $calculatedSubtotal = 0;
            $calculatedTaxTotal = 0;
            $calculatedDiscountTotal = 0;

            foreach ($request->items as $itemData) {
                $qty = (float) $itemData['quantity'];
                $price = (float) $itemData['unit_price'];
                $taxRate = (float) ($itemData['tax_rate'] ?? 0);
                $discount = (float) ($itemData['discount_amount'] ?? 0);

                $lineSubtotal = $qty * $price;
                $lineTax = ($lineSubtotal - $discount) * ($taxRate / 100);
                if ($lineTax < 0) $lineTax = 0;

                $lineTotal = ($lineSubtotal - $discount) + $lineTax;

                SupplierInvoiceItem::create([
                    'supplier_invoice_id'    => $supplierInvoice->id,
                    'purchase_order_item_id' => $itemData['po_item_id'] ?? null,
                    'goods_receipt_item_id'  => $itemData['grn_item_id'],
                    'product_id'             => $itemData['product_id'],
                    'unit_of_measure_id'     => $itemData['unit_of_measure_id'] ?? null,
                    'description'            => $itemData['description'] ?? null,
                    'quantity'               => $qty,
                    'unit_price'             => $price,
                    'tax_rate'               => $taxRate,
                    'tax_amount'             => $lineTax,
                    'discount_amount'        => $discount,
                    'total_amount'           => $lineTotal,
                ]);

                $calculatedSubtotal += $lineSubtotal;
                $calculatedTaxTotal += $lineTax;
                $calculatedDiscountTotal += $discount;
            }

            $overallTotal = ($calculatedSubtotal - $calculatedDiscountTotal) + $calculatedTaxTotal;

            $supplierInvoice->update([
                'subtotal'           => $calculatedSubtotal,
                'tax_amount'         => $calculatedTaxTotal,
                'discount_amount'    => $calculatedDiscountTotal,
                'total_amount'       => $overallTotal,
                'outstanding_amount' => $overallTotal,
            ]);

            // UPDATE GRN INVOICED STATUS
            $goodsReceipt->update([
                'invoiced_status' => 'Invoiced',
                'updated_by'      => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Supplier Invoice Created Successfully (' . $sysInvoiceNo . ')'
            );

            return redirect()->route('viewsupplierInvoice', encrypt($supplierInvoice->id));

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Display details of a specific supplier invoice.
     */
    public function viewsupplierInvoice($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);

            $data = SupplierInvoice::with([
                'supplier',
                'purchaseOrder',
                'goodsReceipt',
                'branch',
                'company',
                'verifier',
                'creator',
                'updater',
                'items.product',
                'items.unitOfMeasure',
                'payments'
            ])->findOrFail($id);

            return view('in.suppliers.supplierinvoices.viewsupplierInvoice', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show form to edit a pending supplier invoice.
     */
    public function editsupplierInvoice($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $data = SupplierInvoice::with('items.product', 'items.unitOfMeasure')->findOrFail($id);

            if ($data->verification_status === 'Verified' || $data->paid_amount > 0) {
                Alert::warning('Action Denied', 'Verified or partially paid invoices cannot be edited.');
                return back();
            }

            return view('in.suppliers.supplierinvoices.editsupplierInvoice', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update supplier invoice in storage.
     */
    public function updatesupplierInvoice(Request $request, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);

        $request->validate([
            'supplier_invoice_no'       => 'required|string|max:100',
            'invoice_date'              => 'required|date',
            'due_date'                  => 'required|date|after_or_equal:invoice_date',
            'currency_code'             => 'required|string|max:10',
            'notes'                     => 'nullable|string',
            'items'                     => 'required|array|min:1',
            'items.*.item_id'           => 'required|integer|exists:supplier_invoice_items,id',
            'items.*.quantity'          => 'required|numeric|min:0.001',
            'items.*.unit_price'        => 'required|numeric|min:0',
            'items.*.tax_rate'          => 'nullable|numeric|min:0|max:100',
            'items.*.discount_amount'   => 'nullable|numeric|min:0',
            'items.*.description'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $invoice = SupplierInvoice::findOrFail($id);

            if ($invoice->verification_status === 'Verified' || $invoice->paid_amount > 0) {
                Alert::warning('Action Denied', 'Verified or paid invoices cannot be modified.');
                return back();
            }

            $invoice->update([
                'supplier_invoice_no' => $request->supplier_invoice_no,
                'invoice_date'        => $request->invoice_date,
                'due_date'            => $request->due_date,
                'currency_code'       => strtoupper($request->currency_code),
                'notes'               => $request->notes,
                'updated_by'          => Auth::id(),
            ]);

            $calculatedSubtotal = 0;
            $calculatedTaxTotal = 0;
            $calculatedDiscountTotal = 0;

            foreach ($request->items as $itemData) {
                $item = SupplierInvoiceItem::findOrFail($itemData['item_id']);

                $qty = (float) $itemData['quantity'];
                $price = (float) $itemData['unit_price'];
                $taxRate = (float) ($itemData['tax_rate'] ?? 0);
                $discount = (float) ($itemData['discount_amount'] ?? 0);

                $lineSubtotal = $qty * $price;
                $lineTax = ($lineSubtotal - $discount) * ($taxRate / 100);
                if ($lineTax < 0) $lineTax = 0;

                $lineTotal = ($lineSubtotal - $discount) + $lineTax;

                $item->update([
                    'description'     => $itemData['description'] ?? null,
                    'quantity'        => $qty,
                    'unit_price'      => $price,
                    'tax_rate'        => $taxRate,
                    'tax_amount'      => $lineTax,
                    'discount_amount' => $discount,
                    'total_amount'    => $lineTotal,
                ]);

                $calculatedSubtotal += $lineSubtotal;
                $calculatedTaxTotal += $lineTax;
                $calculatedDiscountTotal += $discount;
            }

            $overallTotal = ($calculatedSubtotal - $calculatedDiscountTotal) + $calculatedTaxTotal;
            $outstanding = $overallTotal - $invoice->paid_amount;

            $invoice->update([
                'subtotal'           => $calculatedSubtotal,
                'tax_amount'         => $calculatedTaxTotal,
                'discount_amount'    => $calculatedDiscountTotal,
                'total_amount'       => $overallTotal,
                'outstanding_amount' => max(0, $outstanding),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Supplier Invoice Updated Successfully'
            );

            return redirect()->route('viewsupplierInvoice', encrypt($invoice->id));

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Verify/Approve supplier invoice for accounting payment.
     */
    public function verifysupplierInvoice(Request $request, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $invoice = SupplierInvoice::findOrFail($id);

            $invoice->update([
                'verification_status' => 'Verified',
                'verified_by'         => $user->id,
                'verified_at'         => now(),
                'notes'               => $request->notes ?? $invoice->notes,
                'updated_by'          => $user->id,
            ]);

            DB::commit();

            Alert::success('Success ' . $user->name, 'Invoice Verified Successfully');
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete supplier invoice & restore GRN invoiced_status to Uninvoiced.
     */
    public function destroysupplierInvoice($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encryptedId);
            $invoice = SupplierInvoice::findOrFail($id);

            // Revert GRN status back to Uninvoiced
            if ($invoice->goods_receipt_id) {
                GoodsReceipt::where('id', $invoice->goods_receipt_id)->update([
                    'invoiced_status' => 'Uninvoiced'
                ]);
            }

            $invoice->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $invoice->delete();

            DB::commit();

            Alert::success('Success ' . Auth::user()->name, 'Supplier Invoice Soft Deleted Successfully');
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft-deleted invoices list.
     */
    public function deletedsupplierInvoice()
    {
        try {
            $data = SupplierInvoice::onlyTrashed()
                ->with(['supplier', 'goodsReceipt', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.suppliers.supplierinvoices.deletedsupplierInvoice', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore soft-deleted invoice & update GRN status back to Invoiced.
     */
    public function restoresupplierInvoice($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encryptedId);
            $invoice = SupplierInvoice::withTrashed()->findOrFail($id);

            $invoice->restore();

            $invoice->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            // Re-mark GRN as Invoiced
            if ($invoice->goods_receipt_id) {
                GoodsReceipt::where('id', $invoice->goods_receipt_id)->update([
                    'invoiced_status' => 'Invoiced'
                ]);
            }

            DB::commit();

            Alert::success('Success ' . Auth::user()->name, 'Supplier Invoice Restored Successfully');
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Generate unique sequence invoice code (INV-YYYY-00001).
     */
    private function generateInvoiceNumber(): string
    {
        $year = date('Y');

        $lastInv = SupplierInvoice::where('invoice_no', 'like', 'IFLSG/INV/' . $year . '-%')
            ->orderByDesc('id')
            ->first();

        if (!$lastInv) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastInv->invoice_no, -5);
            $number = $lastNumber + 1;
        }

        return 'IFLSG/INV/' . $year . '/' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }



    /**
     * Display a listing of registered supplier payments.
     */
    public function supplierPayment(Request $request)
    {
        try {
            $query = SupplierPayment::where('Status', 'Active')
                ->with(['supplier', 'supplierInvoice', 'requestedBy', 'approvedBy', 'paidBy', 'branch']);

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->filled('approval_status')) {
                $query->where('approval_status', $request->approval_status);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('payment_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('payment_date', '<=', $request->to_date);
            }

            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();
            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.supplierpayments.supplierPayment', compact('data', 'suppliers'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of verified invoices with outstanding balances awaiting payment.
     */
    public function unPaidSupplierInvoices(Request $request)
    {
        try {
            $query = SupplierInvoice::where('Status', 'Active')
                ->where('verification_status', 'Verified')
                ->where('outstanding_amount', '>', 0)
                ->with(['supplier', 'purchaseOrder', 'goodsReceipt', 'branch']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                      ->orWhere('supplier_invoice_no', 'like', "%{$search}%")
                      ->orWhereHas('supplier', function ($sq) use ($search) {
                          $sq->where('SupplierName', 'like', "%{$search}%");
                      });
                });
            }

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.supplierpayments.unPaidSupplierInvoices', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show form to make a payment against a specific unpaid or partially paid invoice.
     */
    public function createsupplierPayment($encryptedInvoiceId)
    {
        try {
            $invoiceId = Crypt::decrypt($encryptedInvoiceId);

            $invoice = SupplierInvoice::where('Status', 'Active')
                ->with(['supplier', 'purchaseOrder', 'goodsReceipt', 'branch', 'items.product'])
                ->findOrFail($invoiceId);

            if ($invoice->outstanding_amount <= 0) {
                Alert::warning('Notice', 'This supplier invoice has already been fully paid.');
                return redirect()->route('unPaidSupplierInvoices');
            }

            return view('in.suppliers.supplierpayments.createsupplierPayment', compact('invoice'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created supplier payment in storage and update invoice balances.
     */
    public function storesupplierPayment(Request $request, $encryptedInvoiceId)
    {
        $invoiceId = Crypt::decrypt($encryptedInvoiceId);

        $request->validate([
            'payment_date'      => 'required|date',
            'payment_amount'    => 'required|numeric|min:0.01',
            'payment_method'    => 'required|string|max:50',
            'payment_reference' => 'nullable|string|max:100',
            'bank_account'      => 'nullable|string|max:100',
            'cheque_no'         => 'nullable|string|max:50',
            'comment'           => 'nullable|string',
            'attachment'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $invoice = SupplierInvoice::findOrFail($invoiceId);
            $payAmount = (float) $request->payment_amount;

            // Prevent overpayment beyond outstanding balance
            if ($payAmount > $invoice->outstanding_amount) {
                Alert::warning(
                    'Overpayment Warning',
                    'Payment amount (' . number_format($payAmount, 2) . ') cannot exceed the invoice outstanding amount (' . number_format($invoice->outstanding_amount, 2) . ').'
                );
                return back()->withInput();
            }

            $paymentNo = $this->generatePaymentNumber();

            // Attachment upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('supplier_payments', 'public');
            }

            $payment = SupplierPayment::create([
                'payment_no'          => $paymentNo,
                'supplier_id'         => $invoice->supplier_id,
                'supplier_invoice_id' => $invoice->id,
                'payment_date'        => $request->payment_date,
                'payment_amount'      => $payAmount,
                'currency_code'       => $invoice->currency_code,
                'payment_method'      => $request->payment_method,
                'payment_reference'   => $request->payment_reference,
                'bank_account'        => $request->bank_account,
                'cheque_no'           => $request->cheque_no,
                'payment_status'      => 'Completed',
                'approval_status'     => 'Approved',
                'requested_by'        => $user->id,
                'approved_by'         => $user->id,
                'approved_at'         => now(),
                'paid_by'             => $user->id,
                'paid_at'             => now(),
                'comment'             => $request->comment,
                'attachment_path'     => $attachmentPath,
                'company_id'          => $invoice->company_id,
                'branch_id'           => $invoice->branch_id,
                'User_id'             => $user->id,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
                'created_by'          => $user->id,
                'updated_by'          => $user->id,
            ]);

            // Update Invoice Paid and Outstanding Balances
            $newPaidAmount = $invoice->paid_amount + $payAmount;
            $newOutstanding = max(0, $invoice->total_amount - $newPaidAmount);
            $newInvoiceStatus = $newOutstanding == 0 ? 'Paid' : 'Partially Paid';

            $invoice->update([
                'paid_amount'        => $newPaidAmount,
                'outstanding_amount' => $newOutstanding,
                'invoice_status'     => $newInvoiceStatus,
                'updated_by'         => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Payment Processed Successfully (' . $paymentNo . '). Invoice status: ' . $newInvoiceStatus
            );

            return redirect()->route('viewsupplierPayment', encrypt($payment->id));

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Display details/receipt profile of a specific supplier payment.
     */
    public function viewsupplierPayment($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);

            $data = SupplierPayment::with([
                'supplier',
                'supplierInvoice.purchaseOrder',
                'requestedBy',
                'approvedBy',
                'paidBy',
                'branch',
                'company',
                'creator'
            ])->findOrFail($id);

            return view('in.suppliers.supplierpayments.viewsupplierPayment', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete a supplier payment and adjust invoice financial balances.
     */
    public function destroysupplierPayment($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encryptedId);
            $payment = SupplierPayment::findOrFail($id);

            if ($payment->supplierInvoice) {
                $invoice = $payment->supplierInvoice;
                $revertedPaid = max(0, $invoice->paid_amount - $payment->payment_amount);
                $revertedOutstanding = $invoice->total_amount - $revertedPaid;
                $revertedStatus = $revertedPaid > 0 ? 'Partially Paid' : 'Unpaid';

                $invoice->update([
                    'paid_amount'        => $revertedPaid,
                    'outstanding_amount' => $revertedOutstanding,
                    'invoice_status'     => $revertedStatus,
                ]);
            }

            $payment->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $payment->delete();

            DB::commit();

            Alert::success('Success ' . Auth::user()->name, 'Supplier Payment Soft Deleted Successfully');
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display soft-deleted supplier payments.
     */
    public function deletedsupplierPayment()
    {
        try {
            $data = SupplierPayment::onlyTrashed()
                ->with(['supplier', 'supplierInvoice', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.suppliers.supplierpayments.deletedsupplierPayment', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore soft-deleted supplier payment and recalculate invoice balances.
     */
    public function restoresupplierPayment($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encryptedId);
            $payment = SupplierPayment::withTrashed()->findOrFail($id);

            $payment->restore();

            $payment->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            if ($payment->supplierInvoice) {
                $invoice = $payment->supplierInvoice;
                $restoredPaid = $invoice->paid_amount + $payment->payment_amount;
                $restoredOutstanding = max(0, $invoice->total_amount - $restoredPaid);
                $restoredStatus = $restoredOutstanding == 0 ? 'Paid' : 'Partially Paid';

                $invoice->update([
                    'paid_amount'        => $restoredPaid,
                    'outstanding_amount' => $restoredOutstanding,
                    'invoice_status'     => $restoredStatus,
                ]);
            }

            DB::commit();

            Alert::success('Success ' . Auth::user()->name, 'Supplier Payment Restored Successfully');
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Generate unique sequence payment reference code (PAY-YYYY-00001).
     */
    private function generatePaymentNumber(): string
    {
        $year = date('Y');

        $lastPay = SupplierPayment::where('payment_no', 'like', 'IFLSG/PAY/' . $year . '-%')
            ->orderByDesc('id')
            ->first();

        if (!$lastPay) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastPay->payment_no, -5);
            $number = $lastNumber + 1;
        }

        return 'IFLSG/PAY/' . $year . '/' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}