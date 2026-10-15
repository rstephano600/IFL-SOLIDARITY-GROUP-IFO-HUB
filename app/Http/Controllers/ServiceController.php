<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Branch;
use App\Models\ServiceCatalog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
class ServiceController extends Controller
{

    /**
     * Display a listing of active service categories.
     */
    public function serviceCategory(Request $request)
    {
        try {
            $query = ServiceCategory::where('Status', 'Active')
                ->with(['company', 'branch', 'user'])
                ->withCount('services');

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('category_name', 'like', "%{$search}%")
                      ->orWhere('category_code', 'like', "%{$search}%")
                      ->orWhere('ReferenceNo', 'like', "%{$search}%");
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.services.serviceCategory.serviceCategory', compact('data', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created service category in storage.
     */
    public function storeserviceCategory(Request $request)
    {
        $request->validate([
            'branch_id'     => 'required|integer|exists:branchies,id',
            'category_name' => 'required|string|max:255',
            'category_code' => 'nullable|string|max:50|unique:service_categories,category_code',
            'description'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);

            // Generate Branch Abbreviation for Reference No
            $branchWords = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Unique Incremental Reference Number
            $year = date('Y');
            $prefix = 'IFLSG/SCAT/' . $branchPrefix . '/' . $year . '/';

            $lastCategory = ServiceCategory::where('ReferenceNo', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            if ($lastCategory) {
                $lastNumber = (int) substr($lastCategory->ReferenceNo, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            do {
                $generatedRefNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (
                ServiceCategory::where('ReferenceNo', $generatedRefNo)->exists()
            );

            // Generate Category Code if empty
            $categoryCode = $request->category_code 
                ? strtoupper($request->category_code) 
                : 'CAT-' . mt_rand(100, 999);

            ServiceCategory::create([
                'ReferenceNo'    => $generatedRefNo,
                'category_code'  => $categoryCode,
                'category_name'  => strtoupper($request->category_name),
                'description'    => $request->description,
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
                'Service Category Registered Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Show form for editing the specified service category.
     */
    public function editserviceCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ServiceCategory::findOrFail($id);
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.services.serviceCategory.editServiceCategory', compact('data', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified service category in storage.
     */
    public function updateserviceCategory(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'     => 'required|integer|exists:branchies,id',
            'category_name' => 'required|string|max:255',
            'category_code' => 'required|string|max:50|unique:service_categories,category_code,' . $id,
            'description'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $category = ServiceCategory::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);

            $category->update([
                'branch_id'     => $branch->id,
                'company_id'    => $branch->company_id,
                'category_code' => strtoupper($request->category_code),
                'category_name' => strtoupper($request->category_name),
                'description'   => $request->description,
                'updated_by'    => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Category Updated Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Display details of a specific service category.
     */
    public function viewserviceCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = ServiceCategory::with([
                'company',
                'branch',
                'user',
                'services'
            ])->findOrFail($id);

            return view('in.services.serviceCategory.viewServiceCategory', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete the specified service category.
     */
    public function destroyserviceCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $category = ServiceCategory::findOrFail($id);

            $category->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $category->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Category Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted service categories.
     */
    public function deletedserviceCategory()
    {
        // try {
            $data = ServiceCategory::onlyTrashed()
                ->with(['company', 'branch'])
                ->latest('deleted_at')
                ->get();

            return view('in.services.serviceCategory.deletedServiceCategory', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Restore a soft-deleted service category back to Active status.
     */
    public function restoreserviceCategory($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $category = ServiceCategory::withTrashed()->findOrFail($id);

            $category->restore();

            $category->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Category Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }



    /**
     * Display a listing of active service catalog items.
     */
    public function serviceCatalog(Request $request)
    {
        try {
            $query = ServiceCatalog::where('Status', 'Active')
                ->with(['company', 'branch', 'user', 'category'])
                ->withCount('requestItems');

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('service_category_id')) {
                $query->where('service_category_id', $request->service_category_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('service_name', 'like', "%{$search}%")
                      ->orWhere('service_code', 'like', "%{$search}%")
                      ->orWhere('ReferenceNo', 'like', "%{$search}%");
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $categories = ServiceCategory::where('Status', 'Active')->get();

            return view('in.services.serviceCatalog.serviceCatalog', compact('data', 'branchies', 'categories'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created service catalog item in storage.
     */
    public function storeServiceCatalog(Request $request)
    {
        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'service_category_id' => 'required|integer|exists:service_categories,id',
            'service_name'        => 'required|string|max:255',
            'service_code'        => 'nullable|string|max:50|unique:service_catalog,service_code',
            'default_unit'        => 'nullable|string|max:50',
            'default_unit_price'  => 'nullable|numeric|min:0',
            'requires_product'    => 'nullable|boolean',
            'requires_attachment' => 'nullable|boolean',
            'description'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);

            // Generate Branch Abbreviation for Reference No
            $branchWords = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Unique Incremental Reference Number
            $year = date('Y');
            $prefix = 'IFLSG/SRVC/' . $branchPrefix . '/' . $year . '/';

            $lastService = ServiceCatalog::where('ReferenceNo', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            if ($lastService) {
                $lastNumber = (int) substr($lastService->ReferenceNo, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            do {
                $generatedRefNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (
                ServiceCatalog::where('ReferenceNo', $generatedRefNo)->exists()
            );

            // Generate Service Code if empty
            $serviceCode = $request->service_code 
                ? strtoupper($request->service_code) 
                : 'SRV-' . mt_rand(100, 999);

            ServiceCatalog::create([
                'ReferenceNo'         => $generatedRefNo,
                'service_category_id' => $request->service_category_id,
                'service_code'        => $serviceCode,
                'service_name'        => strtoupper($request->service_name),
                'description'         => $request->description,
                'default_unit'        => $request->default_unit,
                'default_unit_price'  => $request->default_unit_price ?? 0.00,
                'requires_product'    => $request->has('requires_product') ? 1 : 0,
                'requires_attachment' => $request->has('requires_attachment') ? 1 : 0,
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'User_id'             => $user->id,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
                'created_by'          => $user->id,
                'updated_by'          => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Service Item Registered Successfully'
            );

            return back();

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back()->withInput();
        // }
    }

    /**
     * Show form for editing the specified service catalog item.
     */
    public function editServiceCatalog($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ServiceCatalog::findOrFail($id);
            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $categories = ServiceCategory::where('Status', 'Active')->get();

            return view('in.services.serviceCatalog.editServiceCatalog', compact('data', 'branchies', 'categories'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified service catalog item in storage.
     */
    public function updateServiceCatalog(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'service_category_id' => 'required|integer|exists:service_categories,id',
            'service_name'        => 'required|string|max:255',
            'service_code'        => 'required|string|max:50|unique:service_catalog,service_code,' . $id,
            'default_unit'        => 'nullable|string|max:50',
            'default_unit_price'  => 'nullable|numeric|min:0',
            'requires_product'    => 'nullable|boolean',
            'requires_attachment' => 'nullable|boolean',
            'description'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $service = ServiceCatalog::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);

            $service->update([
                'branch_id'           => $branch->id,
                'company_id'          => $branch->company_id,
                'service_category_id' => $request->service_category_id,
                'service_code'        => strtoupper($request->service_code),
                'service_name'        => strtoupper($request->service_name),
                'description'         => $request->description,
                'default_unit'        => $request->default_unit,
                'default_unit_price'  => $request->default_unit_price ?? 0.00,
                'requires_product'    => $request->has('requires_product') ? 1 : 0,
                'requires_attachment' => $request->has('requires_attachment') ? 1 : 0,
                'updated_by'          => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Item Updated Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Display details of a specific service catalog item.
     */
    public function viewServiceCatalog($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = ServiceCatalog::with([
                'company',
                'branch',
                'user',
                'category',
                'requestItems'
            ])->findOrFail($id);

            return view('in.services.serviceCatalog.viewServiceCatalog', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete the specified service catalog item.
     */
    public function destroyServiceCatalog($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $service = ServiceCatalog::findOrFail($id);

            $service->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $service->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Item Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted service catalog items.
     */
    public function deletedServiceCatalog()
    {
        try {
            $data = ServiceCatalog::onlyTrashed()
                ->with(['company', 'branch', 'category'])
                ->latest('deleted_at')
                ->get();

            return view('in.services.serviceCatalog.deletedServiceCatalog', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted service catalog item back to Active status.
     */
    public function restoreServiceCatalog($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $service = ServiceCatalog::withTrashed()->findOrFail($id);

            $service->restore();

            $service->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Item Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }
}