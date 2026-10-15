<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\SalesType;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\SalesBillingPeriod;
use App\Models\SalesCustomer;
use App\Models\UnitOfMeasure;
use App\Models\BillingCompany;
use App\Models\StaffMealShift;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BillingCompanyController extends Controller
{
    public function index(Request $request)
    {
        $billingCompanies = BillingCompany::withCount('customers')
            ->when($request->q, function ($query, $search) {
                $query->where(function ($w) use ($search) {
                    $w->where('company_name', 'like', "%{$search}%")
                      ->orWhere('company_code', 'like', "%{$search}%")
                      ->orWhere('tin_number', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('company_name')
            ->paginate(15)
            ->withQueryString();

        return view('in.billingcompanies.index', compact('billingCompanies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return back()->withErrors($validator, 'createBilling')->withInput();
        }

        try {
            $user = Auth::user();

            BillingCompany::create([
                'company_code'        => $request->company_code
                                            ? strtoupper($request->company_code)
                                            : $this->generateCode(),
                'company_name'        => strtoupper($request->company_name),
                'tin_number'          => $request->tin_number,
                'vrn_number'          => $request->vrn_number,
                'registration_number' => $request->registration_number,
                'contact_person'      => $request->contact_person ? strtoupper($request->contact_person) : null,
                'phone'               => $request->phone,
                'email'               => $request->email,
                'city'                => $request->city,
                'region'              => $request->region,
                'country'             => $request->country ?: 'Tanzania',
                'address'             => $request->address,
                'credit_limit'        => $request->credit_limit ?? 0,
                'payment_terms_days'  => $request->payment_terms_days ?? 0,
                'Status'              => $request->Status ?? 'Active',
                'created_by'          => $user->id,
                'updated_by'          => $user->id,
            ]);

            Alert::success('Success ' . $user->name, 'Billing company registered successfully.');
            return back();

        } catch (\Throwable $th) {
            \Log::error('Billing Company Store Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . Auth::user()->name, 'Technical error exists, please contact Technical Support.');
            return back()->withInput();
        }
    }

    public function update(Request $request, BillingCompany $billingCompany)
    {
        $validator = Validator::make($request->all(), $this->rules($billingCompany->id));

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'updateBilling')
                ->withInput()
                ->with('edit_id', $billingCompany->id);
        }

        try {
            $user = Auth::user();

            $billingCompany->update([
                'company_code'        => strtoupper($request->company_code ?: $billingCompany->company_code),
                'company_name'        => strtoupper($request->company_name),
                'tin_number'          => $request->tin_number,
                'vrn_number'          => $request->vrn_number,
                'registration_number' => $request->registration_number,
                'contact_person'      => $request->contact_person ? strtoupper($request->contact_person) : null,
                'phone'               => $request->phone,
                'email'               => $request->email,
                'city'                => $request->city,
                'region'              => $request->region,
                'country'             => $request->country ?: 'Tanzania',
                'address'             => $request->address,
                'credit_limit'        => $request->credit_limit ?? 0,
                'payment_terms_days'  => $request->payment_terms_days ?? 0,
                'Status'              => $request->Status ?? $billingCompany->Status,
                'updated_by'          => $user->id,
            ]);

            Alert::success('Success ' . $user->name, 'Billing company updated successfully.');
            return back();

        } catch (\Throwable $th) {
            \Log::error('Billing Company Update Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . Auth::user()->name, 'Technical error exists, please contact Technical Support.');
            return back()->withInput();
        }
    }

    public function destroy(BillingCompany $billingCompany)
    {
        try {
            if ($billingCompany->customers()->exists()) {
                Alert::warning(
                    'Cannot delete',
                    'This company has linked customers. Set it to Inactive instead.'
                );
                return back();
            }

            $billingCompany->delete();

            Alert::success('Deleted', 'Billing company deleted successfully.');
            return back();

        } catch (\Throwable $th) {
            \Log::error('Billing Company Delete Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . Auth::user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'company_code'        => ['nullable', 'string', 'max:50',
                                      Rule::unique('billing_companies', 'company_code')->ignore($ignoreId)],
            'company_name'        => ['required', 'string', 'max:200',
                                      Rule::unique('billing_companies', 'company_name')
                                          ->ignore($ignoreId)->whereNull('deleted_at')],
            'tin_number'          => ['nullable', 'string', 'max:50'],
            'vrn_number'          => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:80'],
            'contact_person'      => ['nullable', 'string', 'max:150'],
            'phone'               => ['nullable', 'string', 'max:30'],
            'email'               => ['nullable', 'email', 'max:150'],
            'city'                => ['nullable', 'string', 'max:100'],
            'region'              => ['nullable', 'string', 'max:100'],
            'country'             => ['nullable', 'string', 'max:100'],
            'address'             => ['nullable', 'string'],
            'credit_limit'        => ['nullable', 'numeric', 'min:0'],
            'payment_terms_days'  => ['nullable', 'integer', 'min:0'],
            'Status'              => ['nullable', Rule::in(['Active', 'Inactive'])],
        ];
    }

    private function generateCode(): string
    {
        $last = BillingCompany::withTrashed()->orderByDesc('id')->first();
        $next = $last ? ((int) preg_replace('/\D/', '', $last->company_code)) + 1 : 1;

        return 'BC-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }






    /**
     * Display a listing of active staff meal shifts.
     */
    public function staffMealShift()
    {
        try {
            $data = StaffMealShift::where('Status', 'Active')
                ->with(['company', 'branch', 'user', 'creator'])
                ->orderBy('display_order', 'asc')
                ->latest('id')
                ->get();

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.meals.shifts.staffMealShift', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created staff meal shift in storage.
     */
    public function storestaffMealShift(Request $request)
    {
        $request->validate([
            'branch_id'     => 'required|integer|exists:branchies,id',
            'shift_code'    => 'required|string|max:30',
            'shift_name'    => 'required|string|max:100',
            'start_time'    => 'nullable|date_format:H:i',
            'end_time'      => 'nullable|date_format:H:i',
            'display_order' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $user   = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);

            // Verify unique constraint: (company_id, branch_id, shift_code)
            $exists = StaffMealShift::where('company_id', $branch->company_id)
                ->where('branch_id', $branch->id)
                ->where('shift_code', strtoupper($request->shift_code))
                ->where('Status', '!=', 'Deleted')
                ->exists();

            if ($exists) {
                Alert::warning('Duplicate Code', 'Shift code already exists for this branch.');
                return back()->withInput();
            }

            // Generate Reference Number (e.g. SMS-BRC-2026-1049)
            $branchWords  = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Incremental Reference Number
            $prefix = 'IFLSG/SMS/' . $branchPrefix . '/' . date('Y') . '/';

            $lastRecord = StaffMealShift::where('ReferenceNo', 'LIKE', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            $lastNumber = $lastRecord
                ? (int) substr($lastRecord->ReferenceNo, strlen($prefix))
                : 0;

            $generatedRefNo = $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            StaffMealShift::create([
                'ReferenceNo'    => $generatedRefNo,
                'shift_code'     => strtoupper($request->shift_code),
                'shift_name'     => strtoupper($request->shift_name),
                'start_time'     => $request->start_time,
                'end_time'       => $request->end_time,
                'display_order'  => $request->display_order ?? 0,
                'company_id'     => $branch->company_id,
                'branch_id'      => $branch->id,
                'User_id'        => $user->id,
                'Status'         => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus'   => 'Pending',
                'created_by'     => $user->id,
                'updated_by'     => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Staff Meal Shift Registered Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back()->withInput();
        }
    }

    /**
     * Display profile/details of a specific staff meal shift.
     */
    public function viewstaffMealShift($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);

            $data = StaffMealShift::with([
                'company',
                'branch',
                'user',
                'creator',
                'updater',
                'mealRates',
                'shiftRecords'
            ])->findOrFail($id);

            return view('in.meals.shifts.viewstaffMealShift', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Show form for editing the specified staff meal shift.
     */
    public function editstaffMealShift($encryptedId)
    {
        try {
            $id   = Crypt::decrypt($encryptedId);
            $data = StaffMealShift::findOrFail($id);

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.meals.shifts.editstaffMealShift', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified staff meal shift in storage.
     */
    public function updatestaffMealShift(Request $request, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);

        $request->validate([
            'branch_id'     => 'required|integer|exists:branchies,id',
            'shift_code'    => 'required|string|max:30',
            'shift_name'    => 'required|string|max:100',
            'start_time'    => 'nullable|date_format:H:i',
            'end_time'      => 'nullable|date_format:H:i',
            'display_order' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $shift  = StaffMealShift::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);

            // Unique constraint check excluding current shift ID
            $exists = StaffMealShift::where('company_id', $branch->company_id)
                ->where('branch_id', $branch->id)
                ->where('shift_code', strtoupper($request->shift_code))
                ->where('id', '!=', $id)
                ->where('Status', '!=', 'Deleted')
                ->exists();

            if ($exists) {
                Alert::warning('Duplicate Code', 'Shift code already exists for this branch.');
                return back()->withInput();
            }

            $shift->update([
                'shift_code'    => strtoupper($request->shift_code),
                'shift_name'    => strtoupper($request->shift_name),
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'display_order' => $request->display_order ?? $shift->display_order,
                'company_id'    => $branch->company_id,
                'branch_id'     => $branch->id,
                'updated_by'    => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Staff Meal Shift Updated Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back()->withInput();
        }
    }

    /**
     * Soft delete the specified staff meal shift.
     */
    public function destroystaffMealShift($encryptedId)
    {
        try {
            $id    = Crypt::decrypt($encryptedId);
            $shift = StaffMealShift::findOrFail($id);

            $shift->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $shift->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Staff Meal Shift Removed Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted staff meal shifts.
     */
    public function deletedstaffMealShift()
    {
        try {
            $data = StaffMealShift::onlyTrashed()
                ->with(['company', 'branch', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.meals.shifts.deletedstaffMealShift', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted staff meal shift back to Active status.
     */
    public function restorestaffMealShift($encryptedId)
    {
        try {
            $id    = Crypt::decrypt($encryptedId);
            $shift = StaffMealShift::withTrashed()->findOrFail($id);

            $shift->restore();

            $shift->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Staff Meal Shift Restored Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }








}