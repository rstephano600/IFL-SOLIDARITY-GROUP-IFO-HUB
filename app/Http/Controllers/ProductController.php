<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\CompanyBusinessCode;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Product;
use App\Models\AccountThirdBranch;
use App\Models\UnitOfMeasure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function products()
    {
        try {

            $products = Product::with([
                    'category',
                    'business',
                    'company',
                    'branch',
                    'incomeAccount',
                    'expenseAccount',
                    'inventoryAccount',
                    'unit',
                    'user',
                    'creator',
                    'updater'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $categories     = ProductCategory::where('Status', 'Active')->orderBy('category_name')->get();
            $businessCodes  = CompanyBusinessCode::where('Status', 'Active')->orderBy('business_code')->get();
            $companies      = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches       = Branch::where('Status', 'Active')->orderBy('branch_name')->get();
            $glAccounts     = AccountThirdBranch::orderBy('ThirdAccountCode')->get();
            $unitsOfMeasure = UnitOfMeasure::orderBy('UnitName')->get();

            return view('in.products.products', compact('products','categories', 'businessCodes', 'companies', 'branches', 'glAccounts', 'unitsOfMeasure'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storeproducts(Request $request)
    {
        $request->validate([
            'product_code'           => 'required|string|max:50|unique:products,product_code',
            'product_name'           => 'required|string|max:200',
            'product_category_id'    => 'nullable|exists:product_categories,id',
            'CompanyBusinessCode_id' => 'nullable|exists:company_businesses_codes,id',
            'income_gl_account_id'   => 'nullable|exists:account_third_branches,id',
            'expense_gl_account_id'  => 'nullable|exists:account_third_branches,id',
            'inventory_gl_account_id'=> 'nullable|exists:account_third_branches,id',
            'unit_of_measure_id'     => 'nullable|exists:units_of_measure,id',
            'cost_price'             => 'nullable|numeric|min:0',
            'selling_price'          => 'nullable|numeric|min:0',
            'minimum_price'          => 'nullable|numeric|min:0',
            'maximum_price'          => 'nullable|numeric|min:0',
            'tax_rate'               => 'nullable|numeric|min:0|max:100',
            'requires_member'        => 'nullable|boolean',
            'requires_approval'      => 'nullable|boolean',
            'is_stock_item'          => 'nullable|boolean',
            'allow_discount'         => 'nullable|boolean',
            'description'            => 'nullable|string',
            'company_id'             => 'nullable|exists:companies,id',
            'branch_id'              => 'nullable|exists:branchies,id',
        ]);

        try {

            Product::create([
                'product_code'           => $request->product_code,
                'product_name'           => $request->product_name,
                'product_category_id'    => $request->product_category_id,
                'CompanyBusinessCode_id' => $request->CompanyBusinessCode_id,
                'income_gl_account_id'   => $request->income_gl_account_id,
                'expense_gl_account_id'  => $request->expense_gl_account_id,
                'inventory_gl_account_id'=> $request->inventory_gl_account_id,
                'unit_of_measure_id'     => $request->unit_of_measure_id,
                'cost_price'             => $request->cost_price ?? 0.00,
                'selling_price'          => $request->selling_price ?? 0.00,
                'minimum_price'          => $request->minimum_price ?? 0.00,
                'maximum_price'          => $request->maximum_price ?? 0.00,
                'tax_rate'               => $request->tax_rate ?? 0.00,
                'requires_member'        => $request->has('requires_member') ? $request->requires_member : false,
                'requires_approval'      => $request->has('requires_approval') ? $request->requires_approval : false,
                'is_stock_item'          => $request->has('is_stock_item') ? $request->is_stock_item : true,
                'allow_discount'         => $request->has('allow_discount') ? $request->allow_discount : true,
                'description'            => $request->description,
                'company_id'             => $request->company_id,
                'branch_id'              => $request->branch_id,
                'User_id'                => auth()->id(),
                'Status'                 => 'Active',
                'AuditingStatus'         => 'Pending',
                'ReportStatus'           => 'Pending',
                'created_by'             => auth()->id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name, 'Operation completed successfully.'
            );
            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function viewproducts($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $product = Product::with([
                    'category',
                    'business',
                    'company',
                    'branch',
                    'incomeAccount',
                    'expenseAccount',
                    'inventoryAccount',
                    'unit',
                    'user',
                    'creator',
                    'updater',
                    'deleter',
                ])
                ->findOrFail($id);

            return view('in.products.viewproducts', compact('product'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function editproducts($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $product = Product::with([
                    'category',
                    'business',
                    'company',
                    'branch',
                    'incomeAccount',
                    'expenseAccount',
                    'inventoryAccount',
                    'unit',
                    'user',
                    'creator',
                    'updater'
                ])
                ->findOrFail($id);

            $categories     = ProductCategory::where('Status', 'Active')->orderBy('category_name')->get();
            $businessCodes  = CompanyBusinessCode::where('Status', 'Active')->orderBy('business_code')->get();
            $companies      = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches       = Branch::where('Status', 'Active')->orderBy('branch_name')->get();
            $glAccounts     = AccountThirdBranch::orderBy('ThirdAccountCode')->get();
            $unitsOfMeasure = UnitOfMeasure::orderBy('UnitName')->get();

            return view('in.products.editproducts', compact(
                'product',
                'categories',
                'businessCodes',
                'companies',
                'branches',
                'glAccounts',
                'unitsOfMeasure'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function updateproducts(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'product_code'           => 'required|string|max:50|unique:products,product_code,' . $id,
            'product_name'           => 'required|string|max:200',
            'product_category_id'    => 'nullable|exists:product_categories,id',
            'CompanyBusinessCode_id' => 'nullable|exists:company_businesses_codes,id',
            'income_gl_account_id'   => 'nullable|exists:account_third_branches,id',
            'expense_gl_account_id'  => 'nullable|exists:account_third_branches,id',
            'inventory_gl_account_id'=> 'nullable|exists:account_third_branches,id',
            'unit_of_measure_id'     => 'nullable|exists:units_of_measure,id',
            'cost_price'             => 'nullable|numeric|min:0',
            'selling_price'          => 'nullable|numeric|min:0',
            'minimum_price'          => 'nullable|numeric|min:0',
            'maximum_price'          => 'nullable|numeric|min:0',
            'tax_rate'               => 'nullable|numeric|min:0|max:100',
            'requires_member'        => 'nullable|boolean',
            'requires_approval'      => 'nullable|boolean',
            'is_stock_item'          => 'nullable|boolean',
            'allow_discount'         => 'nullable|boolean',
            'description'            => 'nullable|string',
            'company_id'             => 'nullable|exists:companies,id',
            'branch_id'              => 'nullable|exists:branchies,id',
        ]);

        try {

            $product = Product::findOrFail($id);

            $product->update([
                'product_code'           => $request->product_code,
                'product_name'           => $request->product_name,
                'product_category_id'    => $request->product_category_id,
                'CompanyBusinessCode_id' => $request->CompanyBusinessCode_id,
                'income_gl_account_id'   => $request->income_gl_account_id,
                'expense_gl_account_id'  => $request->expense_gl_account_id,
                'inventory_gl_account_id'=> $request->inventory_gl_account_id,
                'unit_of_measure_id'     => $request->unit_of_measure_id,
                'cost_price'             => $request->cost_price ?? $product->cost_price,
                'selling_price'          => $request->selling_price ?? $product->selling_price,
                'minimum_price'          => $request->minimum_price ?? $product->minimum_price,
                'maximum_price'          => $request->maximum_price ?? $product->maximum_price,
                'tax_rate'               => $request->tax_rate ?? $product->tax_rate,
                'requires_member'        => $request->has('requires_member') ? $request->requires_member : $product->requires_member,
                'requires_approval'      => $request->has('requires_approval') ? $request->requires_approval : $product->requires_approval,
                'is_stock_item'          => $request->has('is_stock_item') ? $request->is_stock_item : $product->is_stock_item,
                'allow_discount'         => $request->has('allow_discount') ? $request->allow_discount : $product->allow_discount,
                'description'            => $request->description,
                'company_id'             => $request->company_id,
                'branch_id'              => $request->branch_id,
                'updated_by'             => auth()->id(),
            ]);

            Alert::success('Success', 'Product updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function destroyproducts($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $product = Product::findOrFail($id);

            // Set soft-delete metadata and custom status
            $product->update([
                'Status'     => 'Deleted',
                'deleted_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Triggers Laravel SoftDeletes (sets deleted_at timestamp)
            $product->delete();

            Alert::success('Success', 'Product deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoreproducts($id)
    {
        try {
            $id = Crypt::decrypt($id);

            // Fetch soft-deleted product
            $product = Product::withTrashed()->findOrFail($id);

            // Restore Eloquent soft delete
            $product->restore();

            $product->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => auth()->id(),
            ]);

            Alert::success('Success', 'Product restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    // PRODUCT CATEGORY INFORMATIONS
    public function productcategories()
    {
        try {

            $productCategories = ProductCategory::with([
                    'business',
                    'company',
                    'branch',
                    'user',
                    'creator',
                    'updater'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $businessCodes = CompanyBusinessCode::where('Status', 'Active')->get();
            $companies = Company::where('Status', 'Active')->get();
            $branches = Company::where('Status', 'Active')->get();
            return view('in.products.productcategories', compact('productCategories', 'businessCodes', 'companies', 'branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storeproductcategories(Request $request)
    {
        $request->validate([
            'CompanyBusinessCode_id' => 'nullable|exists:company_businesses_codes,id',
            'category_code'          => 'required|string|max:50|unique:product_categories,category_code',
            'category_name'          => 'required|string|max:200',
            'description'            => 'nullable|string',
            'display_order'          => 'nullable|integer',
            'company_id'             => 'nullable|exists:companies,id',
            'branch_id'              => 'nullable|exists:branchies,id',
        ]);

        try {

            ProductCategory::create([
                'CompanyBusinessCode_id' => $request->CompanyBusinessCode_id,
                'category_code'          => $request->category_code,
                'category_name'          => $request->category_name,
                'description'            => $request->description,
                'display_order'          => $request->display_order ?? 0,
                'company_id'             => $request->company_id,
                'branch_id'              => $request->branch_id,
                'User_id'                => auth()->id(),
                'Status'                 => 'Active',
                'AuditingStatus'         => 'Pending',
                'ReportStatus'           => 'Pending',
                'created_by'             => auth()->id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name, 'Operation completed successfully.'
            );
            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function viewproductcategories($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $productCategory = ProductCategory::with([
                    'business',
                    'company',
                    'branch',
                    'user',
                    'creator',
                    'updater',
                    'deleter'
                ])
                ->findOrFail($id);

            return view('in.products.viewproductcategories', compact('productCategory'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function editproductcategories($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $productCategory = ProductCategory::with([
                    'business',
                    'company',
                    'branch',
                    'user',
                    'creator',
                    'updater'
                ])
                ->findOrFail($id);

            $businessCodes = CompanyBusinessCode::where('Status', 'Active')->orderBy('business_name')->get();
            $companies     = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches      = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.products.editproductcategories', compact(
                'productCategory',
                'businessCodes',
                'companies',
                'branches'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function updateproductcategories(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'CompanyBusinessCode_id' => 'nullable|exists:company_businesses_codes,id',
            'category_code'          => 'required|string|max:50|unique:product_categories,category_code,' . $id,
            'category_name'          => 'required|string|max:200',
            'description'            => 'nullable|string',
            'display_order'          => 'nullable|integer',
            'company_id'             => 'nullable|exists:companies,id',
            'branch_id'              => 'nullable|exists:branchies,id',
        ]);

        try {

            $productCategory = ProductCategory::findOrFail($id);

            $productCategory->update([
                'CompanyBusinessCode_id' => $request->CompanyBusinessCode_id,
                'category_code'          => $request->category_code,
                'category_name'          => $request->category_name,
                'description'            => $request->description,
                'display_order'          => $request->display_order ?? $productCategory->display_order,
                'company_id'             => $request->company_id,
                'branch_id'              => $request->branch_id,
                'updated_by'             => auth()->id(),
            ]);

            Alert::success('Success', 'Product category updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function destroyproductcategories($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $productCategory = ProductCategory::findOrFail($id);

            // Update custom status and soft-delete user before soft deleting
            $productCategory->update([
                'Status'     => 'Deleted',
                'deleted_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Triggers Laravel SoftDeletes (sets deleted_at timestamp)
            $productCategory->delete();

            Alert::success('Success', 'Product category deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoreproductcategories($id)
    {
        try {
            $id = Crypt::decrypt($id);

            // Fetch soft-deleted category
            $productCategory = ProductCategory::withTrashed()->findOrFail($id);

            // Restore Eloquent soft delete
            $productCategory->restore();

            $productCategory->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => auth()->id(),
            ]);

            Alert::success('Success', 'Product category restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }
}