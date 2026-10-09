
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\PermissionUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\EmployeeManagementController;
use App\Http\Controllers\Group\GroupMemberController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Loan\LoanReportController;
use App\Http\Controllers\Loan\CollectionSummaryController;
use App\Http\Controllers\Loan\CollectionSummaryController2;
use App\Http\Controllers\Salary\EmployeeWeeklyAllowanceController;
use App\Http\Controllers\Donation\DonationController;
use App\Http\Controllers\Expense\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Salary\SalaryLevelController;
use App\Http\Controllers\Salary\EmployeeSalaryController;
use App\Http\Controllers\Salary\EmployeeSalaryPaymentController;
use App\Http\Controllers\Loan\RepaymentScheduleController;
use App\Http\Controllers\Loan\LoanApprovalController;
use App\Http\Controllers\Loan\LoanRequestNewClientController;
use App\Http\Controllers\Loan\LoanRequestContinuengClientController;
use App\Http\Controllers\Loan\LoanOfficerLoansController;
use App\Http\Controllers\Employee\EmployeeExportController;
use App\Http\Controllers\Group\GroupCenterController;
use App\Http\Controllers\Loan\LoanCategoryController;
use App\Http\Controllers\Loan\LoanPaymentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\MembershipContributionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CostCentreController;
use App\Http\Controllers\MemberSelfInformationsController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\PurchaseOrderController;

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Loan\LoanDashboardController;
use App\Http\Controllers\Loan\ClientLoanController;
use App\Http\Controllers\Loan\DailyCollectionController;

use App\Http\Controllers\MemberDashboardController;


Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login.submit');
Route::get('/showregisterForm', [AuthenticationController::class, 'showregisterForm'])->name('showregisterForm');
Route::post('/storeregisterdata', [AuthenticationController::class, 'storeregisterdata'])->name('storeregisterdata');

Route::get('/home', [AuthenticationController::class, 'home'])->name('home');
Route::post('/settings', [AuthenticationController::class, 'settings'])->name('settings');
Route::post('/delete-active-session', [AuthenticationController::class, 'deleteActvSession'])->name('delete-active-session');
// Session activity ping — must be inside auth middleware group
Route::middleware('auth')->post('/session/ping', function () {
    session(['last_activity_time' => time()]);
    return response()->json(['status' => 'ok']);
});
//MEMBER INFORMATIONS ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/memberdashboard', [MemberDashboardController::class, 'memberdashboard'])->name('memberdashboard');
    Route::get('/myShares', [MemberSelfInformationsController::class, 'myShares'])->name('myShares');
});
Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {
    Route::get('/profile', [MemberDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [MemberDashboardController::class, 'updateProfile'])->name('profile.update');

    Route::get('/settings', [MemberDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [MemberDashboardController::class, 'updatePassword'])->name('settings.password');

    // Route::get('/notifications', [MemberDashboardController::class, 'notifications'])->name('notifications');
    // Route::patch('/notifications/{id}/read', [MemberDashboardController::class, 'markNotificationRead'])->name('notifications.read');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    // COMPANY INFORMATIONS
    Route::get('/companiesinformations', [CompanyController::class, 'companiesinformations'])->name('companiesinformations');
    Route::post('/storecompaniesinformations', [CompanyController::class, 'storecompaniesinformations'])->name('storecompaniesinformations');
    Route::get('/viewcompaniesinformations/{id}', [CompanyController::class, 'viewcompaniesinformations'])->name('viewcompaniesinformations');
    Route::get('/editcompaniesinformations/{id}', [CompanyController::class, 'editcompaniesinformations'])->name('editcompaniesinformations');
    Route::put('/updatecompaniesinformations/{id}', [CompanyController::class, 'updatecompaniesinformations'])->name('updatecompaniesinformations');
    Route::get('/deletecompaniesinformations/{id}', [CompanyController::class, 'deletecompaniesinformations'])->name('deletecompaniesinformations');
    Route::get('/companiesRestore', [CompanyController::class, 'companiesRestore'])->name('companiesRestore');
    Route::get('/companiesReport', [CompanyController::class, 'companiesReport'])->name('companiesReport');

    // COMPANY BRANCHIES
    Route::get('/branchiesinformations', [CompanyController::class, 'branchiesinformations'])->name('branchiesinformations');
    Route::get('/branchiesinformationsreport', [CompanyController::class, 'branchiesinformationsreport'])->name('branchiesinformationsreport');
    Route::post('/storebranchiesinformations', [CompanyController::class, 'storebranchiesinformations'])->name('storebranchiesinformations');
    Route::get('/viewbranchiesinformations/{id}', [CompanyController::class, 'viewbranchiesinformations'])->name('viewbranchiesinformations');
    Route::get('/editbranchiesinformations/{id}', [CompanyController::class, 'editbranchiesinformations'])->name('editbranchiesinformations');
    Route::put('/updatebranchiesinformations/{id}', [CompanyController::class, 'updatebranchiesinformations'])->name('updatebranchiesinformations');
    Route::get('/deletedbranchiesinformations', [CompanyController::class, 'deletedbranchiesinformations'])->name('deletedbranchiesinformations');
    Route::get('/deletebranchiesinformations/{id}', [CompanyController::class, 'deletebranchiesinformations'])->name('deletebranchiesinformations');
    Route::get('/restorebranchiesinformations/{id}', [CompanyController::class, 'restorebranchiesinformations'])->name('restorebranchiesinformations');

    // DEPARTMENT INFORMATIONS
    Route::get('/departmentinformations', [DepartmentController::class, 'departmentinformations'])->name('departmentinformations');
    Route::get('/departmentinformationsreport', [DepartmentController::class, 'departmentinformationsreport'])->name('departmentinformationsreport');
    Route::post('/storedepartmentinformations', [DepartmentController::class, 'storedepartmentinformations'])->name('storedepartmentinformations');
    Route::get('/viewdepartmentinformations/{id}', [DepartmentController::class, 'viewdepartmentinformations'])->name('viewdepartmentinformations');
    Route::get('/editdepartmentinformations/{id}', [DepartmentController::class, 'editdepartmentinformations'])->name('editdepartmentinformations');
    Route::put('/updatedepartmentinformations/{id}', [DepartmentController::class, 'updatedepartmentinformations'])->name('updatedepartmentinformations');
    Route::get('/deleteddepartmentinformations', [DepartmentController::class, 'deleteddepartmentinformations'])->name('deleteddepartmentinformations');
    Route::get('/deletedepartmentinformations/{id}', [DepartmentController::class, 'deletedepartmentinformations'])->name('deletedepartmentinformations');
    Route::get('/restoredepartmentinformations/{id}', [DepartmentController::class, 'restoredepartmentinformations'])->name('restoredepartmentinformations');

    // COST CENTERS INFORMATIONS
    Route::get('/costcentreinformations', [CostCentreController::class, 'costcentreinformations'])->name('costcentreinformations');
    Route::get('/costcentreinformationsreport', [CostCentreController::class, 'costcentreinformationsreport'])->name('costcentreinformationsreport');
    Route::post('/storecostcentreinformations', [CostCentreController::class, 'storecostcentreinformations'])->name('storecostcentreinformations');
    Route::get('/viewcostcentreinformations/{id}', [CostCentreController::class, 'viewcostcentreinformations'])->name('viewcostcentreinformations');
    Route::get('/editcostcentreinformations/{id}', [CostCentreController::class, 'editcostcentreinformations'])->name('editcostcentreinformations');
    Route::put('/updatecostcentreinformations/{id}', [CostCentreController::class, 'updatecostcentreinformations'])->name('updatecostcentreinformations');
    Route::get('/deletedcostcentreinformations', [CostCentreController::class, 'deletedcostcentreinformations'])->name('deletedcostcentreinformations');
    Route::get('/deletecostcentreinformations/{id}', [CostCentreController::class, 'deletecostcentreinformations'])->name('deletecostcentreinformations');
    Route::get('/restorecostcentreinformations/{id}', [CostCentreController::class, 'restorecostcentreinformations'])->name('restorecostcentreinformations');

    // COMPANY BUSINESS CODES INFORMATIONS
    Route::get('/companybusinesscodeinformations', [CompanyController::class, 'companybusinesscodeinformations'])->name('companybusinesscodeinformations');
    Route::get('/companybusinesscodeinformationsreport', [CompanyController::class, 'companybusinesscodeinformationsreport'])->name('companybusinesscodeinformationsreport');
    Route::post('/storecompanybusinesscodeinformations', [CompanyController::class, 'storecompanybusinesscodeinformations'])->name('storecompanybusinesscodeinformations');
    Route::get('/viewcompanybusinesscodeinformations/{id}', [CompanyController::class, 'viewcompanybusinesscodeinformations'])->name('viewcompanybusinesscodeinformations');
    Route::get('/editcompanybusinesscodeinformations/{id}', [CompanyController::class, 'editcompanybusinesscodeinformations'])->name('editcompanybusinesscodeinformations');
    Route::put('/updatecompanybusinesscodeinformations/{id}', [CompanyController::class, 'updatecompanybusinesscodeinformations'])->name('updatecompanybusinesscodeinformations');
    Route::get('/deletedcompanybusinesscodeinformations', [CompanyController::class, 'deletedcompanybusinesscodeinformations'])->name('deletedcompanybusinesscodeinformations');
    Route::get('/deletecompanybusinesscodeinformations/{id}', [CompanyController::class, 'deletecompanybusinesscodeinformations'])->name('deletecompanybusinesscodeinformations');
    Route::get('/restorecompanybusinesscodeinformations/{id}', [CompanyController::class, 'restorecompanybusinesscodeinformations'])->name('restorecompanybusinesscodeinformations');

    // MEMBERS CATEGORIES INFORMATIONS
    Route::get('/membercategoryinformations', [MemberController::class, 'membercategoryinformations'])->name('membercategoryinformations');
    Route::get('/membercategoryinformationsreport', [MemberController::class, 'membercategoryinformationsreport'])->name('membercategoryinformationsreport');
    Route::post('/storemembercategoryinformations', [MemberController::class, 'storemembercategoryinformations'])->name('storemembercategoryinformations');
    Route::get('/viewmembercategoryinformations/{id}', [MemberController::class, 'viewmembercategoryinformations'])->name('viewmembercategoryinformations');
    Route::get('/editmembercategoryinformations/{id}', [MemberController::class, 'editmembercategoryinformations'])->name('editmembercategoryinformations');
    Route::put('/updatemembercategoryinformations/{id}', [MemberController::class, 'updatemembercategoryinformations'])->name('updatemembercategoryinformations');
    Route::get('/deletedmembercategoryinformations', [MemberController::class, 'deletedmembercategoryinformations'])->name('deletedmembercategoryinformations');
    Route::get('/deletemembercategoryinformations/{id}', [MemberController::class, 'deletemembercategoryinformations'])->name('deletemembercategoryinformations');
    Route::get('/restoremembercategoryinformations/{id}', [MemberController::class, 'restoremembercategoryinformations'])->name('restoremembercategoryinformations');

    // MEMBERS INFORMATIONS
    Route::get('/memberinformations', [MemberController::class, 'memberinformations'])->name('memberinformations');
    Route::get('/memberinformationsreport', [MemberController::class, 'memberinformationsreport'])->name('memberinformationsreport');
    Route::post('/storememberinformations', [MemberController::class, 'storememberinformations'])->name('storememberinformations');
    Route::post('/storememberinformationsexist', [MemberController::class, 'storememberinformationsexist'])->name('storememberinformationsexist');
    Route::get('/viewmemberinformations/{id}', [MemberController::class, 'viewmemberinformations'])->name('viewmemberinformations');
    Route::get('/editmemberinformations/{id}', [MemberController::class, 'editmemberinformations'])->name('editmemberinformations');
    Route::put('/updatememberinformations/{id}', [MemberController::class, 'updatememberinformations'])->name('updatememberinformations');
    Route::get('/deletedmemberinformations', [MemberController::class, 'deletedmemberinformations'])->name('deletedmemberinformations');
    Route::get('/deletememberinformations/{id}', [MemberController::class, 'deletememberinformations'])->name('deletememberinformations');
    Route::get('/restorememberinformations/{id}', [MemberController::class, 'restorememberinformations'])->name('restorememberinformations');

    // PRODUCTS INFORMATIONS
    Route::get('/productcategories',  [ProductController::class, 'productcategories'])->name('productcategories');
    Route::post('/storeproductcategories', [ProductController::class, 'storeproductcategories'])->name('storeproductcategories');
    Route::get('/viewproductcategories/{id}', [ProductController::class,'viewproductcategories'])->name('viewproductcategories');
    Route::get('/editproductcategories/{id}', [ProductController::class,'editproductcategories'])->name('editproductcategories');
    Route::put('/updateproductcategories/{id}', [ProductController::class,'updateproductcategories'])->name('updateproductcategories');
    Route::get('/destroyproductcategories/{id}', [ProductController::class,'destroyproductcategories'])->name('destroyproductcategories');
    Route::get('/deletedproductcategories', [ProductController::class,'deletedproductcategories'])->name('deletedproductcategories');
    Route::get('/productcategoriesreport', [ProductController::class,'productcategoriesreport'])->name('productcategoriesreport');
    Route::get('/restoreproductcategories', [ProductController::class,'restoreproductcategories'])->name('restoreproductcategories');

    Route::get('/products',  [ProductController::class, 'products'])->name('products');
    Route::post('/storeproducts', [ProductController::class, 'storeproducts'])->name('storeproducts');
    Route::get('/viewproducts/{id}', [ProductController::class,'viewproducts'])->name('viewproducts');
    Route::get('/editproducts/{id}', [ProductController::class,'editproducts'])->name('editproducts');
    Route::put('/updateproducts/{id}', [ProductController::class,'updateproducts'])->name('updateproducts');
    Route::get('/destroyproducts/{id}', [ProductController::class,'destroyproducts'])->name('destroyproducts');
    Route::get('/deletedproducts', [ProductController::class,'deletedproducts'])->name('deletedproducts');
    Route::get('/productsreport', [ProductController::class,'productsreport'])->name('productsreport');
    Route::get('/restoreproducts', [ProductController::class,'restoreproducts'])->name('restoreproducts');

    Route::get('/productCategory', [ProductController::class, 'productCategory'])->name('productCategory');
    Route::post('/storeproductCategory', [ProductController::class, 'storeproductCategory'])->name('storeproductCategory');
    Route::get('/editproductCategory/{id}', [ProductController::class, 'editproductCategory'])->name('editproductCategory');
    Route::put('/updateproductCategory/{id}', [ProductController::class, 'updateproductCategory'])->name('updateproductCategory');
    Route::get('/viewproductCategory/{id}', [ProductController::class, 'viewproductCategory'])->name('viewproductCategory');
    Route::get('/destroyproductCategory/{id}', [ProductController::class, 'destroyproductCategory'])->name('destroyproductCategory');
    Route::get('/deletedproductCategory', [ProductController::class, 'deletedproductCategory'])->name('deletedproductCategory');
    Route::get('/restoreproductCategory/{id}', [ProductController::class, 'restoreproductCategory'])->name('restoreproductCategory');

    Route::get('/brand', [ProductController::class, 'brand'])->name('brand');
    Route::post('/storebrand', [ProductController::class, 'storebrand'])->name('storebrand');
    Route::get('/editbrand/{id}', [ProductController::class, 'editbrand'])->name('editbrand');
    Route::put('/updatebrand/{id}', [ProductController::class, 'updatebrand'])->name('updatebrand');
    Route::get('/viewbrand/{id}', [ProductController::class, 'viewbrand'])->name('viewbrand');
    Route::get('/destroybrand/{id}', [ProductController::class, 'destroybrand'])->name('destroybrand');
    Route::get('/deletedbrand', [ProductController::class, 'deletedbrand'])->name('deletedbrand');
    Route::get('/restorebrand/{id}', [ProductController::class, 'restorebrand'])->name('restorebrand');

    Route::get('/productSubCategory', [ProductController::class, 'productSubCategory'])->name('productSubCategory');
    Route::post('/storeproductSubCategory', [ProductController::class, 'storeproductSubCategory'])->name('storeproductSubCategory');
    Route::get('/editproductSubCategory/{id}', [ProductController::class, 'editproductSubCategory'])->name('editproductSubCategory');
    Route::post('/updateproductSubCategory/{id}', [ProductController::class, 'updateproductSubCategory'])->name('updateproductSubCategory');
    Route::get('/viewproductSubCategory/{id}', [ProductController::class, 'viewproductSubCategory'])->name('viewproductSubCategory');
    Route::get('/destroyproductSubCategory/{id}', [ProductController::class, 'destroyproductSubCategory'])->name('destroyproductSubCategory');
    Route::get('/deletedproductSubCategory', [ProductController::class, 'deletedproductSubCategory'])->name('deletedproductSubCategory');
    Route::get('/restoreproductSubCategory/{id}', [ProductController::class, 'restoreproductSubCategory'])->name('restoreproductSubCategory');

    Route::get('/unitOfMeasure', [ProductController::class, 'unitOfMeasure'])->name('unitOfMeasure');
    Route::post('/storeunitOfMeasure', [ProductController::class, 'storeunitOfMeasure'])->name('storeunitOfMeasure');
    Route::get('/editunitOfMeasure/{id}', [ProductController::class, 'editunitOfMeasure'])->name('editunitOfMeasure');
    Route::put('/updateunitOfMeasure/{id}', [ProductController::class, 'updateunitOfMeasure'])->name('updateunitOfMeasure');
    Route::get('/viewunitOfMeasure/{id}', [ProductController::class, 'viewunitOfMeasure'])->name('viewunitOfMeasure');
    Route::get('/destroyunitOfMeasure/{id}', [ProductController::class, 'destroyunitOfMeasure'])->name('destroyunitOfMeasure');
    Route::get('/deletedunitOfMeasure', [ProductController::class, 'deletedunitOfMeasure'])->name('deletedunitOfMeasure');
    Route::get('/restoreunitOfMeasure/{id}', [ProductController::class, 'restoreunitOfMeasure'])->name('restoreunitOfMeasure');

    // SHARE TYPES ROUTES
    Route::get('/sharetypes',  [ShareController::class, 'sharetypes'])->name('sharetypes');
    Route::post('/storesharetypes', [ShareController::class, 'storesharetypes'])->name('storesharetypes');
    Route::get('/viewsharetypes/{id}', [ShareController::class,'viewsharetypes'])->name('viewsharetypes');
    Route::get('/editsharetypes/{id}', [ShareController::class,'editsharetypes'])->name('editsharetypes');
    Route::post('/updatesharetypes/{id}', [ShareController::class,'updatesharetypes'])->name('updatesharetypes');
    Route::get('/destroysharetypes/{id}', [ShareController::class,'destroysharetypes'])->name('destroysharetypes');
    Route::get('/deletedsharetypes', [ShareController::class,'deletedsharetypes'])->name('deletedsharetypes');
    Route::get('/sharetypesreport', [ShareController::class,'sharetypesreport'])->name('sharetypesreport');
    Route::get('/restoresharetypes', [ShareController::class,'restoresharetypes'])->name('restoresharetypes');

    // SHARE OFERRING ROUTES
    Route::get('/shareofferings',  [ShareController::class, 'shareofferings'])->name('shareofferings');
    Route::post('/storeshareofferings', [ShareController::class, 'storeshareofferings'])->name('storeshareofferings');
    Route::get('/viewshareofferings/{id}', [ShareController::class,'viewshareofferings'])->name('viewshareofferings');
    Route::get('/editshareofferings/{id}', [ShareController::class,'editshareofferings'])->name('editshareofferings');
    Route::post('/updateshareofferings/{id}', [ShareController::class,'updateshareofferings'])->name('updateshareofferings');
    Route::get('/destroyshareofferings/{id}', [ShareController::class,'destroyshareofferings'])->name('destroyshareofferings');
    Route::get('/deletedshareofferings', [ShareController::class,'deletedshareofferings'])->name('deletedshareofferings');
    Route::get('/shareofferingsreport', [ShareController::class,'shareofferingsreport'])->name('shareofferingsreport');
    Route::get('/restoreshareofferings', [ShareController::class,'restoreshareofferings'])->name('restoreshareofferings');

    // SHARE PURCHASES TRANSACTIONS ROUTES
    Route::get('/sharepurchasetransactionshub',  [ShareController::class, 'sharepurchasetransactionshub'])->name('sharepurchasetransactionshub');
    Route::get('/sharepurchasetransactions',  [ShareController::class, 'sharepurchasetransactions'])->name('sharepurchasetransactions');
    Route::post('/storesharepurchasetransactions', [ShareController::class, 'storesharepurchasetransactions'])->name('storesharepurchasetransactions');
    Route::get('/viewsharepurchasetransactions/{id}', [ShareController::class,'viewsharepurchasetransactions'])->name('viewsharepurchasetransactions');
    Route::get('/editsharepurchasetransactions/{id}', [ShareController::class,'editsharepurchasetransactions'])->name('editsharepurchasetransactions');
    Route::post('/updatesharepurchasetransactions/{id}', [ShareController::class,'updatesharepurchasetransactions'])->name('updatesharepurchasetransactions');
    Route::get('/destroysharepurchasetransactions/{id}', [ShareController::class,'destroysharepurchasetransactions'])->name('destroysharepurchasetransactions');
    Route::get('/deletedsharepurchasetransactions', [ShareController::class,'deletedsharepurchasetransactions'])->name('deletedsharepurchasetransactions');
    Route::get('/sharepurchasetransactionsreport', [ShareController::class,'sharepurchasetransactionsreport'])->name('sharepurchasetransactionsreport');
    Route::get('/restoresharepurchasetransactions', [ShareController::class,'restoresharepurchasetransactions'])->name('restoresharepurchasetransactions');
    Route::get('/downloadsharepurchasetemplate', [ShareController::class,'downloadsharepurchasetemplate'])->name('downloadsharepurchasetemplate');
    Route::post('/importsharepurchasetransactions', [ShareController::class,'importsharepurchasetransactions'])->name('importsharepurchasetransactions');
    Route::post('/downloadcertificatesharepurchasetransactions', [ShareController::class,'downloadcertificatesharepurchasetransactions'])->name('downloadcertificatesharepurchasetransactions');

    // WORKFLOW STAGE 1: VERIFICATION ROUTES
    Route::get('/unverifiedsharepurchasetransactions', [ShareController::class, 'unverifiedsharepurchasetransactions'])->name('unverifiedsharepurchasetransactions');
    Route::post('/verifysharepurchasetransaction/{id}', [ShareController::class, 'verifysharepurchasetransaction'])->name('verifysharepurchasetransaction');

    // WORKFLOW STAGE 2: APPROVAL ROUTES
    Route::get('/pendingapprovalsharepurchasetransactions', [ShareController::class, 'pendingapprovalsharepurchasetransactions'])->name('pendingapprovalsharepurchasetransactions');
    Route::post('/approvesharepurchasetransaction/{id}', [ShareController::class, 'approvesharepurchasetransaction'])->name('approvesharepurchasetransaction');

    // WORKFLOW STAGE 3: ACCOUNTING POSTING ROUTES
    Route::get('/pendingpostingsharepurchasetransactions', [ShareController::class, 'pendingpostingsharepurchasetransactions'])->name('pendingpostingsharepurchasetransactions');
    Route::post('/postsharepurchasetransaction/{id}', [ShareController::class, 'postsharepurchasetransaction'])->name('postsharepurchasetransaction');

    // WORKFLOW STAGE 4: SHARE CERTIFICATE ROUTES
    Route::get('/pendingcertificatesharepurchasetransactions', [ShareController::class, 'pendingcertificatesharepurchasetransactions'])->name('pendingcertificatesharepurchasetransactions');
    Route::post('/generatecertificate/{id}', [ShareController::class, 'generatecertificate'])->name('generatecertificate');

    Route::get('/completedsharepurchasetransactions', [ShareController::class, 'completedsharepurchasetransactions'])->name('completedsharepurchasetransactions');

    // membership fee schedules ROUTES
    Route::get('/membershipfeeschedules',  [MembershipContributionController::class, 'membershipfeeschedules'])->name('membershipfeeschedules');
    Route::post('/storemembershipfeeschedules', [MembershipContributionController::class, 'storemembershipfeeschedules'])->name('storemembershipfeeschedules');
    Route::get('/viewmembershipfeeschedules/{id}', [MembershipContributionController::class,'viewmembershipfeeschedules'])->name('viewmembershipfeeschedules');
    Route::get('/editmembershipfeeschedules/{id}', [MembershipContributionController::class,'editmembershipfeeschedules'])->name('editmembershipfeeschedules');
    Route::post('/updatemembershipfeeschedules/{id}', [MembershipContributionController::class,'updatemembershipfeeschedules'])->name('updatemembershipfeeschedules');
    Route::get('/destroymembershipfeeschedules/{id}', [MembershipContributionController::class,'destroymembershipfeeschedules'])->name('destroymembershipfeeschedules');
    Route::get('/deletedmembershipfeeschedules', [MembershipContributionController::class,'deletedmembershipfeeschedules'])->name('deletedmembershipfeeschedules');
    Route::get('/membershipfeeschedulesreport', [MembershipContributionController::class,'membershipfeeschedulesreport'])->name('membershipfeeschedulesreport');
    Route::get('/restoremembershipfeeschedules', [MembershipContributionController::class,'restoremembershipfeeschedules'])->name('restoremembershipfeeschedules');

    // membership fee schedules ROUTES
    Route::get('/membershipfeepaymentshub',  [MembershipContributionController::class, 'membershipfeepaymentshub'])->name('membershipfeepaymentshub');
    Route::get('/membershipfeepayments',  [MembershipContributionController::class, 'membershipfeepayments'])->name('membershipfeepayments');
    Route::post('/storemembershipfeepayments', [MembershipContributionController::class, 'storemembershipfeepayments'])->name('storemembershipfeepayments');
    Route::get('/viewmembershipfeepayments/{id}', [MembershipContributionController::class,'viewmembershipfeepayments'])->name('viewmembershipfeepayments');
    Route::get('/editmembershipfeepayments/{id}', [MembershipContributionController::class,'editmembershipfeepayments'])->name('editmembershipfeepayments');
    Route::post('/updatemembershipfeepayments/{id}', [MembershipContributionController::class,'updatemembershipfeepayments'])->name('updatemembershipfeepayments');
    Route::get('/destroymembershipfeepayments/{id}', [MembershipContributionController::class,'destroymembershipfeepayments'])->name('destroymembershipfeepayments');
    Route::get('/deletedmembershipfeepayments', [MembershipContributionController::class,'deletedmembershipfeepayments'])->name('deletedmembershipfeepayments');
    Route::get('/membershipfeepaymentsreport', [MembershipContributionController::class,'membershipfeepaymentsreport'])->name('membershipfeepaymentsreport');
    Route::get('/restoremembershipfeepayments', [MembershipContributionController::class,'restoremembershipfeepayments'])->name('restoremembershipfeepayments');
    // WORKFLOW STAGE 1: VERIFICATION ROUTES
    Route::get('/unverifiedmembershipfeepayments', [MembershipContributionController::class, 'unverifiedmembershipfeepayments'])->name('unverifiedmembershipfeepayments');
    Route::post('/verifymembershipfeepayment/{id}', [MembershipContributionController::class, 'verifymembershipfeepayment'])->name('verifymembershipfeepayment');

    // WORKFLOW STAGE 2: APPROVAL ROUTES
    Route::get('/pendingapprovalmembershipfeepayments', [MembershipContributionController::class, 'pendingapprovalmembershipfeepayments']) ->name('pendingapprovalmembershipfeepayments');
    Route::post('/approvemembershipfeepayment/{id}', [MembershipContributionController::class, 'approvemembershipfeepayment'])->name('approvemembershipfeepayment');

    // WORKFLOW STAGE 3: ACCOUNTING POSTING ROUTES
    Route::get('/pendingpostingmembershipfeepayments', [MembershipContributionController::class, 'pendingpostingmembershipfeepayments'])->name('pendingpostingmembershipfeepayments');
    Route::post('/postmembershipfeepayment/{id}', [MembershipContributionController::class, 'postmembershipfeepayment'])->name('postmembershipfeepayment');

    Route::get('/completedmembershipfeepayments', [MembershipContributionController::class, 'completedmembershipfeepayments'])->name('completedmembershipfeepayments');

    // social contribution schedules ROUTES
    Route::get('/socialcontributionschedules',  [MembershipContributionController::class, 'socialcontributionschedules'])->name('socialcontributionschedules');
    Route::post('/storesocialcontributionschedules', [MembershipContributionController::class, 'storesocialcontributionschedules'])->name('storesocialcontributionschedules');
    Route::get('/viewsocialcontributionschedules/{id}', [MembershipContributionController::class,'viewsocialcontributionschedules'])->name('viewsocialcontributionschedules');
    Route::get('/editsocialcontributionschedules/{id}', [MembershipContributionController::class,'editsocialcontributionschedules'])->name('editsocialcontributionschedules');
    Route::post('/updatesocialcontributionschedules/{id}', [MembershipContributionController::class,'updatesocialcontributionschedules'])->name('updatesocialcontributionschedules');
    Route::get('/destroysocialcontributionschedules/{id}', [MembershipContributionController::class,'destroysocialcontributionschedules'])->name('destroysocialcontributionschedules');
    Route::get('/deletedsocialcontributionschedules', [MembershipContributionController::class,'deletedsocialcontributionschedules'])->name('deletedsocialcontributionschedules');
    Route::get('/socialcontributionschedulesreport', [MembershipContributionController::class,'socialcontributionschedulesreport'])->name('socialcontributionschedulesreport');
    Route::get('/restoresocialcontributionschedules', [MembershipContributionController::class,'restoresocialcontributionschedules'])->name('restoresocialcontributionschedules');

    // social contribution monthly ROUTES
    Route::get('/socialcontributionshub',  [MembershipContributionController::class, 'socialcontributionshub'])->name('socialcontributionshub');
    Route::get('/socialcontributions',  [MembershipContributionController::class, 'socialcontributions'])->name('socialcontributions');
    Route::post('/storesocialcontributions', [MembershipContributionController::class, 'storesocialcontributions'])->name('storesocialcontributions');
    Route::get('/viewsocialcontributions/{id}', [MembershipContributionController::class,'viewsocialcontributions'])->name('viewsocialcontributions');
    Route::get('/editsocialcontributions/{id}', [MembershipContributionController::class,'editsocialcontributions'])->name('editsocialcontributions');
    Route::post('/updatesocialcontributions/{id}', [MembershipContributionController::class,'updatesocialcontributions'])->name('updatesocialcontributions');
    Route::get('/destroysocialcontributions/{id}', [MembershipContributionController::class,'destroysocialcontributions'])->name('destroysocialcontributions');
    Route::get('/deletedsocialcontributions', [MembershipContributionController::class,'deletedsocialcontributions'])->name('deletedsocialcontributions');
    Route::get('/socialcontributionsreport', [MembershipContributionController::class,'socialcontributionsreport'])->name('socialcontributionsreport');
    Route::get('/restoresocialcontributions', [MembershipContributionController::class,'restoresocialcontributions'])->name('restoresocialcontributions');
    Route::get('/downloadsocialcontributiontemplate', [MembershipContributionController::class,'downloadsocialcontributiontemplate'])->name('downloadsocialcontributiontemplate');
    Route::post('/importsocialcontributions', [MembershipContributionController::class,'importsocialcontributions'])->name('importsocialcontributions');

    // WORKFLOW STAGE 1: VERIFICATION ROUTES
    Route::get('/unverifiedsocialcontributions', [MembershipContributionController::class, 'unverifiedsocialcontributions'])->name('unverifiedsocialcontributions');
    Route::post('/verifysocialcontribution/{id}', [MembershipContributionController::class, 'verifysocialcontribution'])->name('verifysocialcontribution');

    // WORKFLOW STAGE 2: APPROVAL ROUTES
    Route::get('/pendingapprovalsocialcontributions', [MembershipContributionController::class, 'pendingapprovalsocialcontributions'])->name('pendingapprovalsocialcontributions');
    Route::post('/approvesocialcontribution/{id}', [MembershipContributionController::class, 'approvesocialcontribution'])->name('approvesocialcontribution');

    // WORKFLOW STAGE 3: ACCOUNTING POSTING ROUTES
    Route::get('/pendingpostingsocialcontributions', [MembershipContributionController::class, 'pendingpostingsocialcontributions'])->name('pendingpostingsocialcontributions');
    Route::post('/postsocialcontribution/{id}', [MembershipContributionController::class, 'postsocialcontribution'])->name('postsocialcontribution');

    Route::get('/completedsocialcontributions', [MembershipContributionController::class, 'completedsocialcontributions'])->name('completedsocialcontributions');

    // NEW IMPROVED CODES
    Route::get('/systemUsers', [UserController::class, 'systemUsers'])->name('systemUsers');
    Route::post('/storesystemUsers', [UserController::class, 'storesystemUsers'])->name('storesystemUsers');
    Route::get('/editsystemUsers/{id}', [UserController::class, 'editsystemUsers'])->name('editsystemUsers');
    Route::put('/updatesystemUsers/{id}', [UserController::class, 'updatesystemUsers'])->name('updatesystemUsers');
    Route::get('/destroysystemUsers/{id}', [UserController::class, 'destroysystemUsers'])->name('destroysystemUsers');
    Route::put('/resetPassword/{user}/', [UserController::class, 'resetPassword'])->name('resetPassword');

    // Permission User Controller
    Route::get('/usersRole', [PermissionUserController::class, 'usersRole'])->name('usersRole');
    Route::get('/assignRole/{id}', [PermissionUserController::class, 'assignRole'])->name('assignRole');
    Route::post('/permissionsstore', [PermissionUserController::class, 'permissionsstore'])->name('permissionsstore');
    Route::post('/permissionsremove', [PermissionUserController::class, 'permissionsremove'])->name('permissionsremove');

    Route::get('/configurationside', [DashboardController::class, 'configurationside'])->name('configurationside');
    Route::get('/workingside', [DashboardController::class, 'workingside'])->name('workingside');
    Route::get('/reportingside', [DashboardController::class, 'reportingside'])->name('reportingside');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/accountCountry', [AccountingController::class, 'accountCountry'])->name('accountCountry');
    Route::post('/storeaccountCountry', [AccountingController::class, 'storeaccountCountry'])->name('storeaccountCountry');
    Route::get('/editaccountCountry/{id}', [AccountingController::class, 'editaccountCountry'])->name('editaccountCountry');
    Route::put('/updateaccountCountry/{id}', [AccountingController::class, 'updateaccountCountry'])->name('updateaccountCountry'); // Changed to PUT
    Route::get('/destroyaccountCountry/{id}', [AccountingController::class, 'destroyaccountCountry'])->name('destroyaccountCountry'); // Changed to DELETE
    Route::get('/editaccountCountry/{id}', [AccountingController::class, 'editaccountCountry'])->name('editaccountCountry'); // NEW: For fetching edit data
    // Add these routes to web.php

    Route::get('/accountBusiness', [AccountingController::class, 'accountBusiness'])->name('accountBusiness');
    Route::post('/storeaccountBusiness', [AccountingController::class, 'storeaccountBusiness'])->name('storeaccountBusiness');
    Route::get('/editaccountBusiness/{id}', [AccountingController::class, 'editaccountBusiness'])->name('editaccountBusiness');
    Route::put('/updateaccountBusiness/{id}', [AccountingController::class, 'updateaccountBusiness'])->name('updateaccountBusiness');
    Route::get('/destroyaccountBusiness/{id}', [AccountingController::class, 'destroyaccountBusiness'])->name('destroyaccountBusiness');

    Route::get('/accountRoot', [AccountingController::class, 'accountRoot'])->name('accountRoot');
    Route::post('/storeaccountRoot', [AccountingController::class, 'storeaccountRoot'])->name('storeaccountRoot');
    Route::get('/editaccountRoot/{id}', [AccountingController::class, 'editaccountRoot'])->name('editaccountRoot');
    Route::put('/updateaccountRoot/{id}', [AccountingController::class, 'updateaccountRoot'])->name('updateaccountRoot');
    Route::get('/destroyaccountRoot/{id}', [AccountingController::class, 'destroyaccountRoot'])->name('destroyaccountRoot');

    Route::get('/accountFirstBranch', [AccountingController::class, 'accountFirstBranch'])->name('accountFirstBranch');
    Route::post('/storeaccountFirstBranch', [AccountingController::class, 'storeaccountFirstBranch'])->name('storeaccountFirstBranch');
    Route::get('/editaccountFirstBranch/{id}', [AccountingController::class, 'editaccountFirstBranch'])->name('editaccountFirstBranch');
    Route::put('/updateaccountFirstBranch/{id}', [AccountingController::class, 'updateaccountFirstBranch'])->name('updateaccountFirstBranch');
    Route::get('/destroyaccountFirstBranch/{id}', [AccountingController::class, 'destroyaccountFirstBranch'])->name('destroyaccountFirstBranch');

    Route::get('/accountSecondBranch', [AccountingController::class, 'accountSecondBranch'])->name('accountSecondBranch');
    Route::post('/storeaccountSecondBranch', [AccountingController::class, 'storeaccountSecondBranch'])->name('storeaccountSecondBranch');
    Route::get('/editaccountSecondBranch/{id}', [AccountingController::class, 'editaccountSecondBranch'])->name('editaccountSecondBranch');
    Route::put('/updateaccountSecondBranch/{id}', [AccountingController::class, 'updateaccountSecondBranch'])->name('updateaccountSecondBranch');
    Route::get('/destroyaccountSecondBranch/{id}', [AccountingController::class, 'destroyaccountSecondBranch'])->name('destroyaccountSecondBranch');

    Route::get('/customer', [CustomerController::class, 'customer'])->name('customer');
    Route::post('/storecustomer', [CustomerController::class, 'storecustomer'])->name('storecustomer');
    Route::get('/downloadTemplateCustomer', [CustomerController::class, 'downloadTemplateCustomer'])->name('downloadTemplateCustomer');
    Route::post('/importStoreCustomer', [CustomerController::class, 'importStoreCustomer'])->name('importStoreCustomer');
    Route::get('/editcustomer/{id}', [CustomerController::class, 'editcustomer'])->name('editcustomer');
    Route::post('/updatecustomer/{id}', [CustomerController::class, 'updatecustomer'])->name('updatecustomer');
    Route::get('/viewcustomer/{id}', [CustomerController::class, 'viewcustomer'])->name('viewcustomer');
    Route::get('/destroycustomer/{id}', [CustomerController::class, 'destroycustomer'])->name('destroycustomer');
    Route::get('/deletedcustomer', [CustomerController::class, 'deletedcustomer'])->name('deletedcustomer');
    Route::get('/restorecustomer/{id}', [CustomerController::class, 'restorecustomer'])->name('restorecustomer');

    Route::get('/salesinvoice', [SaleController::class, 'salesinvoice'])->name('salesinvoice');
    Route::post('/storesalesinvoice', [SaleController::class, 'storesalesinvoice'])->name('storesalesinvoice');
    Route::get('/downloadTemplatesalesinvoice', [SaleController::class, 'downloadTemplatesalesinvoice'])->name('downloadTemplatesalesinvoice');
    Route::post('/importStoresalesinvoice', [SaleController::class, 'importStoresalesinvoice'])->name('importStoresalesinvoice');
    Route::get('/editsalesinvoice/{id}', [SaleController::class, 'editsalesinvoice'])->name('editsalesinvoice');
    Route::post('/updatesalesinvoice/{id}', [SaleController::class, 'updatesalesinvoice'])->name('updatesalesinvoice');
    Route::get('/viewsalesinvoice/{id}', [SaleController::class, 'viewsalesinvoice'])->name('viewsalesinvoice');
    Route::get('/destroysalesinvoice/{id}', [SaleController::class, 'destroysalesinvoice'])->name('destroysalesinvoice');
    Route::get('/deletedsalesinvoice', [SaleController::class, 'deletedsalesinvoice'])->name('deletedsalesinvoice');
    Route::get('/restoresalesinvoice/{id}', [SaleController::class, 'restoresalesinvoice'])->name('restoresalesinvoice');
    
    Route::get('/customerPaymentinvoice', [SaleController::class, 'customerPaymentinvoice'])->name('customerPaymentinvoice');
    Route::get('/stockOutsalesinvoice', [SaleController::class, 'stockOutsalesinvoice'])->name('stockOutsalesinvoice');
    Route::get('/createPaymentSales/{id}', [SaleController::class, 'createPaymentSales'])->name('createPaymentSales');
    Route::post('/storePaymentSales/store', [SaleController::class, 'storePaymentSales'])->name('storePaymentSales');

    Route::get('/customerPayment', [SaleController::class, 'customerPayment'])->name('customerPayment');
    Route::post('/storecustomerPayment', [SaleController::class, 'storecustomerPayment'])->name('storecustomerPayment');
    Route::get('/downloadTemplatecustomerPayment', [SaleController::class, 'downloadTemplatecustomerPayment'])->name('downloadTemplatecustomerPayment');
    Route::post('/importStorecustomerPayment', [SaleController::class, 'importStorecustomerPayment'])->name('importStorecustomerPayment');
    Route::get('/editcustomerPayment/{id}', [SaleController::class, 'editcustomerPayment'])->name('editcustomerPayment');
    Route::post('/updatecustomerPayment/{id}', [SaleController::class, 'updatecustomerPayment'])->name('updatecustomerPayment');
    Route::get('/viewcustomerPayment/{id}', [SaleController::class, 'viewcustomerPayment'])->name('viewcustomerPayment');
    Route::get('/destroycustomerPayment/{id}', [SaleController::class, 'destroycustomerPayment'])->name('destroycustomerPayment');
    Route::get('/deletedcustomerPayment', [SaleController::class, 'deletedcustomerPayment'])->name('deletedcustomerPayment');
    Route::get('/restorecustomerPayment/{id}', [SaleController::class, 'restorecustomerPayment'])->name('restorecustomerPayment');

    Route::get('/supplier', [SupplierController::class, 'supplier'])->name('supplier');
    Route::post('/storesupplier', [SupplierController::class, 'storesupplier'])->name('storesupplier');
    Route::get('/editsupplier/{id}', [SupplierController::class, 'editsupplier'])->name('editsupplier');
    Route::post('/updatesupplier/{id}', [SupplierController::class, 'updatesupplier'])->name('updatesupplier');
    Route::get('/viewsupplier/{id}', [SupplierController::class, 'viewsupplier'])->name('viewsupplier');
    Route::get('/destroysupplier/{id}', [SupplierController::class, 'destroysupplier'])->name('destroysupplier');
    Route::get('/deletedsupplier', [SupplierController::class, 'deletedsupplier'])->name('deletedsupplier');
    Route::get('/restoresupplier/{id}', [SupplierController::class, 'restoresupplier'])->name('restoresupplier');

    // Listing
    Route::get('employeeinfo/', [EmployeeController::class, 'employeeinfo'])->name('employeeinfo');
    Route::post('storenewemployeeinfo/store', [EmployeeController::class, 'storenewemployeeinfo'])->name('storenewemployeeinfo');
    Route::get('showemployeeinfo/show/{id}', [EmployeeController::class, 'showemployeeinfo'])->name('showemployeeinfo');
    Route::get('editemployeeinfo/edit/{id}', [EmployeeController::class, 'editemployeeinfo'])->name('editemployeeinfo');
    Route::put('updateemployeeinfo/update/{id}', [EmployeeController::class, 'updateemployeeinfo'])->name('updateemployeeinfo');
    Route::get('deleteemployeeinfo/delete/{id}', [EmployeeController::class, 'deleteemployeeinfo'])->name('destroyemployeeinfo');
    Route::get('trashedemployeeinfo/trashed', [EmployeeController::class, 'trashedemployeeinfo'])->name('trashedemployeeinfo');
    Route::get('restoreemployeeinfo/restore/{id}', [EmployeeController::class, 'restoreemployeeinfo'])->name('restoreemployeeinfo');
    Route::get('lockemployeeaccount/lock/{id}', [EmployeeController::class, 'lockemployeeaccount'])->name('lockemployeeaccount');
    Route::get('unlockemployeeaccount/unlock/{id}', [EmployeeController::class, 'unlockemployeeaccount'])->name('unlockemployeeaccount');

    // Dashboard
    Route::get('/salarypay', [SalaryController::class, 'salarypay'])->name('salarypay');
    // Batch generation / rollback / month change
    Route::post('/generate/generatePayrollByMonth', [SalaryController::class, 'generatePayrollByMonth'])->name('generatePayrollByMonth');
    Route::post('/rollback/rollbackGeneratedPayroll', [SalaryController::class, 'rollbackGeneratedPayroll'])->name('rollbackGeneratedPayroll');
    Route::post('/change-month/changeGeneratedPayrollMonth', [SalaryController::class, 'changeGeneratedPayrollMonth'])->name('changeGeneratedPayrollMonth');
    // Edit gross salary (AmountPaid)
    Route::get('/edit-salary/editsalary/{id}', [SalaryController::class, 'editsalary'])->name('editsalary');
    Route::post('/edit-salary/tempsalaryreg', [SalaryController::class, 'tempsalaryreg'])->name('tempsalaryreg');
    // Edit net salary (NetPay override)
    Route::get('/edit-net-salary/{id}', [SalaryController::class, 'editnetsalary'])->name('editnetsalary');
    Route::post('/edit-net-salary/editnetsalary', [SalaryController::class, 'tempnetsalaryreg'])->name('tempnetsalaryreg');
    // Edit salary details (Allowance/Overtime/Absent/Gross)
    Route::get('/edit-salary-details/editsalarydetails/{id}', [SalaryController::class, 'editsalarydetails'])->name('editsalarydetails');
    Route::post('/edit-salary-details/tempsalarydetailsreg', [SalaryController::class, 'tempsalarydetailsreg'])->name('tempsalarydetailsreg');


    // Queue + history
    Route::get('payroll/payrollApprovalIndex', [SalaryController::class, 'payrollApprovalIndex'])->name('payrollApprovalIndex');
    Route::get('/review/payrollBatchReview/{batchCode}', [SalaryController::class, 'payrollBatchReview'])->name('payrollBatchReview');
    Route::post('/submit/submitPayrollForApproval', [SalaryController::class, 'submitPayrollForApproval'])->name('submitPayrollForApproval');
    Route::post('/approve/approvePayroll', [SalaryController::class, 'approvePayroll'])->middleware('can:approve-payroll')->name('approvePayroll');
    Route::post('/reject/rejectPayroll', [SalaryController::class, 'rejectPayroll'])->middleware('can:approve-payroll')->name('rejectPayroll');

    // STAFF ADVANCE SALARIES ROUTES
    Route::get('/staffadvncslries', [SalaryController::class, 'staffadvncslries'])->name('staffadvncslries');
    Route::post('/storestaffadvncslries', [SalaryController::class, 'storestaffadvncslries'])->name('storestaffadvncslries');
    Route::get('/viewstaffadvncslries/{id}', [SalaryController::class, 'viewstaffadvncslries'])->name('viewstaffadvncslries');
    Route::get('/editstaffadvncslries/{id}', [SalaryController::class, 'editstaffadvncslries'])->name('editstaffadvncslries');
    Route::post('/updatestaffadvncslries/{id}', [SalaryController::class, 'updatestaffadvncslries'])->name('updatestaffadvncslries');
    Route::get('/deletestaffadvncslries/{id}', [SalaryController::class, 'deletestaffadvncslries'])->name('deletestaffadvncslries');
    Route::get('/restorestaffadvncslries/{id}', [SalaryController::class, 'restorestaffadvncslries'])->name('restorestaffadvncslries');

    // STAFF OVERTIMES ROUTES
    Route::get('/staffovertimes', [SalaryController::class, 'staffovertimes'])->name('staffovertimes');
    Route::post('/storestaffovertimes', [SalaryController::class, 'storestaffovertimes'])->name('storestaffovertimes');
    Route::get('/viewstaffovertimes/{id}', [SalaryController::class, 'viewstaffovertimes'])->name('viewstaffovertimes');
    Route::get('/editstaffovertimes/{id}', [SalaryController::class, 'editstaffovertimes'])->name('editstaffovertimes');
    Route::post('/updatestaffovertimes/{id}', [SalaryController::class, 'updatestaffovertimes'])->name('updatestaffovertimes');
    Route::get('/deletestaffovertimes/{id}', [SalaryController::class, 'deletestaffovertimes'])->name('deletestaffovertimes');
    Route::get('/restorestaffovertimes/{id}', [SalaryController::class, 'restorestaffovertimes'])->name('restorestaffovertimes');

    // SALARY ALLOWANCES ROUTES
    Route::get('/slrallwncs', [SalaryController::class, 'slrallwncs'])->name('slrallwncs');
    Route::post('/storeslrallwncs', [SalaryController::class, 'storeslrallwncs'])->name('storeslrallwncs');
    Route::get('/viewslrallwncs/{id}', [SalaryController::class, 'viewslrallwncs'])->name('viewslrallwncs');
    Route::get('/editslrallwncs/{id}', [SalaryController::class, 'editslrallwncs'])->name('editslrallwncs');
    Route::post('/updateslrallwncs/{id}', [SalaryController::class, 'updateslrallwncs'])->name('updateslrallwncs');
    Route::get('/deleteslrallwncs/{id}', [SalaryController::class, 'deleteslrallwncs'])->name('deleteslrallwncs');
    Route::get('/restoreslrallwncs/{id}', [SalaryController::class, 'restoreslrallwncs'])->name('restoreslrallwncs');

    // Staff Absent Calendar Routes
    Route::get('/staffabsentcalenders', [SalaryController::class, 'staffabsentcalenders'])->name('staffabsentcalenders');
    Route::post('/storestaffabsentcalenders', [SalaryController::class, 'storestaffabsentcalenders'])->name('storestaffabsentcalenders');
    Route::get('/viewstaffabsentcalenders/{id}', [SalaryController::class, 'viewstaffabsentcalenders'])->name('viewstaffabsentcalenders');
    Route::get('/editstaffabsentcalenders/{id}', [SalaryController::class, 'editstaffabsentcalenders'])->name('editstaffabsentcalenders');
    Route::post('/updatestaffabsentcalenders/{id}', [SalaryController::class, 'updatestaffabsentcalenders'])->name('updatestaffabsentcalenders');
    Route::get('/deletestaffabsentcalenders/{id}', [SalaryController::class, 'deletestaffabsentcalenders'])->name('deletestaffabsentcalenders');
    Route::get('/restorestaffabsentcalenders/{id}', [SalaryController::class, 'restorestaffabsentcalenders'])->name('restorestaffabsentcalenders');

    // Staff Absent Deduction Routes
    Route::get('/staffabsent', [SalaryController::class, 'staffabsent'])->name('staffabsent');
    Route::post('/storestaffabsent', [SalaryController::class, 'storestaffabsent'])->name('storestaffabsent');
    Route::get('/viewstaffabsent/{id}', [SalaryController::class, 'viewstaffabsent'])->name('viewstaffabsent');
    Route::get('/editstaffabsent/{id}', [SalaryController::class, 'editstaffabsent'])->name('editstaffabsent');
    Route::post('/updatestaffabsent/{id}', [SalaryController::class, 'updatestaffabsent'])->name('updatestaffabsent');
    Route::get('/deletestaffabsent/{id}', [SalaryController::class, 'deletestaffabsent'])->name('deletestaffabsent');
    Route::get('/restorestaffabsent/{id}', [SalaryController::class, 'restorestaffabsent'])->name('restorestaffabsent');


    // SUPPLIER CATEGORY ROUTES
    Route::get('/supplier-categories', [SupplierController::class, 'supplierCategory'])->name('supplierCategory');
    Route::post('/supplier-categories/store', [SupplierController::class, 'storesupplierCategory'])->name('storesupplierCategory');
    Route::get('/supplier-categories/edit/{id}', [SupplierController::class, 'editsupplierCategory'])->name('editsupplierCategory');
    Route::post('/supplier-categories/update/{id}', [SupplierController::class, 'updatesupplierCategory'])->name('updatesupplierCategory');
    Route::get('/supplier-categories/view/{id}', [SupplierController::class, 'viewsupplierCategory'])->name('viewsupplierCategory');
    Route::get('/supplier-categories/destroy/{id}', [SupplierController::class, 'destroysupplierCategory'])->name('destroysupplierCategory');
    Route::get('/supplier-categories/deleted', [SupplierController::class, 'deletedsupplierCategory'])->name('deletedsupplierCategory');
    Route::get('/supplier-categories/restore/{id}', [SupplierController::class, 'restoresupplierCategory'])->name('restoresupplierCategory');

    // SUPPLIER ROUTES
    Route::get('/suppliers', [SupplierController::class, 'suppliers'])->name('suppliers');
    Route::post('/suppliers/store-member', [SupplierController::class, 'storeMemberSupplier'])->name('storeMemberSupplier');
    Route::post('/suppliers/store-external', [SupplierController::class, 'storeExternalSupplier'])->name('storeExternalSupplier');
    Route::get('/suppliers/edit/{id}', [SupplierController::class, 'editSupplier'])->name('editSupplier');
    Route::post('/suppliers/update/{id}', [SupplierController::class, 'updateSupplier'])->name('updateSupplier');
    Route::get('/suppliers/view/{id}', [SupplierController::class, 'viewSupplier'])->name('viewSupplier');
    Route::get('/suppliers/destroy/{id}', [SupplierController::class, 'destroySupplier'])->name('destroySupplier');
    Route::get('/suppliers/deleted', [SupplierController::class, 'deletedSuppliers'])->name('deletedSuppliers');
    Route::get('/suppliers/restore/{id}', [SupplierController::class, 'restoreSupplier'])->name('restoreSupplier');

    Route::get('/supplierProducts', [SupplierController::class, 'supplierProducts'])->name('supplierProducts');
    Route::get('/supplierDocuments', [SupplierController::class, 'supplierDocuments'])->name('supplierDocuments');
    Route::get('/supplierEvaluation', [SupplierController::class, 'supplierEvaluation'])->name('supplierEvaluation');
    Route::get('/supplierTransactions', [SupplierController::class, 'supplierTransactions'])->name('supplierTransactions');

    // PROCUREMENT TYPES ROUTES
    Route::get('/procurement-types',[ProcurementController::class, 'procurementType'])->name('procurementType');
    Route::post('/procurement-types/store',[ProcurementController::class, 'storeprocurementType'])->name('storeprocurementType');
    Route::get('/procurement-types/edit/{id}',[ProcurementController::class, 'editprocurementType'])->name('editprocurementType');
    Route::post('/procurement-types/update/{id}',[ProcurementController::class, 'updateprocurementType'])->name('updateprocurementType');
    Route::get('/procurement-types/view/{id}',[ProcurementController::class, 'viewprocurementType'])->name('viewprocurementType');
    Route::get('/procurement-types/destroy/{id}',[ProcurementController::class, 'destroyprocurementType'])->name('destroyprocurementType');
    Route::get('/procurement-types/deleted',[ProcurementController::class, 'deletedprocurementType'])->name('deletedprocurementType');
    Route::get('/procurement-types/restore/{id}',[ProcurementController::class, 'restoreprocurementType'])->name('restoreprocurementType');

    // PROCUREMENT WORKFLOW STEPS ROUTES
    Route::get('/procurement-workflow-steps',[ProcurementController::class, 'procurementWorkflowStep'])->name('procurementWorkflowStep');
    Route::post('/procurement-workflow-steps/store',[ProcurementController::class, 'storeprocurementWorkflowStep'])->name('storeprocurementWorkflowStep');
    Route::get('/procurement-workflow-steps/edit/{id}',[ProcurementController::class, 'editprocurementWorkflowStep'])->name('editprocurementWorkflowStep');
    Route::post('/procurement-workflow-steps/update/{id}',[ProcurementController::class, 'updateprocurementWorkflowStep'])->name('updateprocurementWorkflowStep');
    Route::get('/procurement-workflow-steps/view/{id}',[ProcurementController::class, 'viewprocurementWorkflowStep'])->name('viewprocurementWorkflowStep');
    Route::get('/procurement-workflow-steps/toggle/{id}',[ProcurementController::class, 'toggleprocurementWorkflowStep'])->name('toggleprocurementWorkflowStep');
    Route::post('/procurement-workflow-steps/reorder',[ProcurementController::class, 'reorderprocurementWorkflowSteps'])->name('reorderprocurementWorkflowSteps');
    Route::get('/procurement-workflow-steps/destroy/{id}',[ProcurementController::class, 'destroyprocurementWorkflowStep'])->name('destroyprocurementWorkflowStep');
    Route::get('/procurement-workflow-steps/deleted',[ProcurementController::class, 'deletedprocurementWorkflowStep'])->name('deletedprocurementWorkflowStep');
    Route::get('/procurement-workflow-steps/restore/{id}',[ProcurementController::class, 'restoreprocurementWorkflowStep'])->name('restoreprocurementWorkflowStep');

    // PURCHASE REQUEST ROUTES
    Route::get('/purchase-requests',[PurchaseController::class, 'purchaseRequest'])->name('purchaseRequest');
    Route::get('/purchase-requests/create',[PurchaseController::class, 'createpurchaseRequest'])->name('createpurchaseRequest');
    Route::post('/purchase-requests/store',[PurchaseController::class, 'storepurchaseRequest'])->name('storepurchaseRequest');
    Route::get('/purchase-requests/view/{id}',[PurchaseController::class, 'viewpurchaseRequest'])->name('viewpurchaseRequest');
    Route::get('/purchase-requests/edit/{id}',[PurchaseController::class, 'editpurchaseRequest'])->name('editpurchaseRequest');
    Route::post('/purchase-requests/update/{id}',[PurchaseController::class, 'updatepurchaseRequest'])->name('updatepurchaseRequest');

    // WORKFLOW / APPROVAL ACTIONS
    Route::get('/purchase-requests/submit/{id}',[PurchaseController::class, 'submitpurchaseRequest'])->name('submitpurchaseRequest');
    Route::post('/purchase-requests/approve/{id}',[PurchaseController::class, 'approvepurchaseRequest'])->name('approvepurchaseRequest');
    Route::post('/purchase-requests/reject/{id}',[PurchaseController::class, 'rejectpurchaseRequest'])->name('rejectpurchaseRequest');
    Route::get('/purchase-requests/resubmit/{id}',[PurchaseController::class, 'resubmitpurchaseRequest'])->name('resubmitpurchaseRequest');
    Route::get('/purchase-requests/cancel/{id}',[PurchaseController::class, 'cancelpurchaseRequest'])->name('cancelpurchaseRequest');
    Route::get('/purchase-requests/workflow-history/{id}',[PurchaseController::class, 'purchaseRequestWorkflow'])->name('purchaseRequestWorkflow');

    // SOFT DELETE & RECOVERY
    Route::get('/purchase-requests/destroy/{id}',[PurchaseController::class, 'destroypurchaseRequest'])->name('destroypurchaseRequest');
    Route::get('/purchase-requests/deleted',[PurchaseController::class, 'deletedpurchaseRequest'])->name('deletedpurchaseRequest');
    Route::get('/purchase-requests/restore/{id}',[PurchaseController::class, 'restorepurchaseRequest'])->name('restorepurchaseRequest');

    // PURCHASE REQUEST APPROVAL QUEUES
    Route::get('/purchase-requests/approvals/level-1',[PurchaseController::class, 'purchaseRequestApprovalLevel1'])->name('purchaseRequestApprovalLevel1');
    Route::get('/purchase-requests/approvals/level-2',[PurchaseController::class, 'purchaseRequestApprovalLevel2'])->name('purchaseRequestApprovalLevel2');
    Route::get('/purchase-requests/approvals/level-3',[PurchaseController::class, 'purchaseRequestApprovalLevel3'])->name('purchaseRequestApprovalLevel3');
    Route::get('/purchase-requests/approvals/level-4',[PurchaseController::class, 'purchaseRequestApprovalLevel4'])->name('purchaseRequestApprovalLevel4');
    Route::get('/purchase-requests/approvals/level-5',[PurchaseController::class, 'purchaseRequestApprovalLevel5'])->name('purchaseRequestApprovalLevel5');
    Route::get('/purchase-requests/approvals/level-6',[PurchaseController::class, 'purchaseRequestApprovalLevel6'])->name('purchaseRequestApprovalLevel6');

    // APPROVE PURCHASE REQUEST BY LEVEL
    Route::post('/purchase-requests/approve/level-1/{id}',[PurchaseController::class, 'approvepurchaseRequestLevel1'])->name('approvepurchaseRequestLevel1');
    Route::post('/purchase-requests/approve/level-2/{id}',[PurchaseController::class, 'approvepurchaseRequestLevel2'])->name('approvepurchaseRequestLevel2');
    Route::post('/purchase-requests/approve/level-3/{id}',[PurchaseController::class, 'approvepurchaseRequestLevel3'])->name('approvepurchaseRequestLevel3');
    Route::post('/purchase-requests/approve/level-4/{id}',[PurchaseController::class, 'approvepurchaseRequestLevel4'])->name('approvepurchaseRequestLevel4');
    Route::post('/purchase-requests/approve/level-5/{id}',[PurchaseController::class, 'approvepurchaseRequestLevel5'])->name('approvepurchaseRequestLevel5');
    Route::post('/purchase-requests/approve/level-6/{id}',[PurchaseController::class, 'approvepurchaseRequestLevel6'])->name('approvepurchaseRequestLevel6');

    // REJECT PURCHASE REQUEST BY LEVEL
    Route::post('/purchase-requests/reject/level-1/{id}',[PurchaseController::class, 'rejectpurchaseRequestLevel1'])->name('rejectpurchaseRequestLevel1');
    Route::post('/purchase-requests/reject/level-2/{id}',[PurchaseController::class, 'rejectpurchaseRequestLevel2'])->name('rejectpurchaseRequestLevel2');
    Route::post('/purchase-requests/reject/level-3/{id}',[PurchaseController::class, 'rejectpurchaseRequestLevel3'])->name('rejectpurchaseRequestLevel3');
    Route::post('/purchase-requests/reject/level-4/{id}',[PurchaseController::class, 'rejectpurchaseRequestLevel4'])->name('rejectpurchaseRequestLevel4');
    Route::post('/purchase-requests/reject/level-5/{id}',[PurchaseController::class, 'rejectpurchaseRequestLevel5'])->name('rejectpurchaseRequestLevel5');
    Route::post('/purchase-requests/reject/level-6/{id}',[PurchaseController::class, 'rejectpurchaseRequestLevel6'])->name('rejectpurchaseRequestLevel6');


    Route::get('/supplierTender', [SupplierController::class, 'supplierTender'])->name('supplierTender');
    Route::post('/storesupplierTender', [SupplierController::class, 'storesupplierTender'])->name('storesupplierTender');
    Route::get('/editsupplierTender/{id}/edit', [SupplierController::class, 'editsupplierTender'])->name('editsupplierTender');
    Route::put('/updatesupplierTender/{id}', [SupplierController::class, 'updatesupplierTender'])->name('updatesupplierTender');
    Route::get('/viewsupplierTender/{id}', [SupplierController::class, 'viewsupplierTender'])->name('viewsupplierTender');
    Route::delete('/destroysupplierTender/{id}', [SupplierController::class, 'destroysupplierTender'])->name('destroysupplierTender');
    Route::get('/deletedsupplierTender/status/deleted', [SupplierController::class, 'deletedsupplierTender'])->name('deletedsupplierTender');
    Route::patch('/restoresupplierTender/{id}/restore', [SupplierController::class, 'restoresupplierTender'])->name('restoresupplierTender');


    Route::get('/supplierQuotation',  [SupplierController::class, 'supplierQuotation'])->name('supplierQuotation');
    Route::get('/createsupplierQuotation/create',  [SupplierController::class, 'createsupplierQuotation'])->name('createsupplierQuotation');
    Route::post('/storesupplierQuotation',  [SupplierController::class, 'storesupplierQuotation'])->name('storesupplierQuotation');
    Route::get('/editsupplierQuotation/{id}/edit',  [SupplierController::class, 'editsupplierQuotation'])->name('editsupplierQuotation');
    Route::put('/updatesupplierQuotation/{id}',  [SupplierController::class, 'updatesupplierQuotation'])->name('updatesupplierQuotation');
    Route::get('/viewsupplierQuotation/{id}',  [SupplierController::class, 'viewsupplierQuotation'])->name('viewsupplierQuotation');
    Route::delete('/destroysupplierQuotation/{id}',  [SupplierController::class, 'destdestroysupplierQuotationroy'])->name('destroysupplierQuotation');
    Route::get('/deletedsupplierQuotation-deleted',  [SupplierController::class, 'deletedsupplierQuotation'])->name('deletedsupplierQuotation');
    Route::patch('/restoresupplierQuotation/{id}/restore',  [SupplierController::class, 'restoresupplierQuotation'])->name('restoresupplierQuotation');

    // Quotation Evaluation Routes
    Route::get('/quotationEvaluation', [SupplierController::class, 'quotationEvaluation'])->name('quotationEvaluation');
    Route::post('/storequotationEvaluation', [SupplierController::class, 'storequotationEvaluation'])->name('storequotationEvaluation');
    Route::get('/editquotationEvaluation/edit/{id}', [SupplierController::class, 'editquotationEvaluation'])->name('editquotationEvaluation');
    Route::post('/updatequotationEvaluation/update/{id}', [SupplierController::class, 'updatequotationEvaluation'])->name('updatequotationEvaluation');
    Route::get('/viewquotationEvaluation/view/{id}', [SupplierController::class, 'viewquotationEvaluation'])->name('viewquotationEvaluation');
    Route::get('/destroyquotationEvaluation/destroy/{id}', [SupplierController::class, 'destroyquotationEvaluation'])->name('destroyquotationEvaluation');
    
    // Trash / Soft Deletes Routes
    Route::get('/quotationEvaluation/deleted', [SupplierController::class, 'deleted'])->name('deletedquotationEvaluation');
    Route::get('/quotationEvaluation/restore/{id}', [SupplierController::class, 'restore'])->name('restorequotationEvaluation');


    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier-awards', 'supplierAward')->name('supplierAward');
        Route::get('/supplier-awards/create', 'createsupplierAward')->name('createsupplierAward');
        Route::post('/supplier-awards/store', 'storesupplierAward')->name('storesupplierAward');
        Route::get('/supplier-awards/view/{id}', 'viewsupplierAward')->name('viewsupplierAward');
        Route::get('/supplier-awards/edit/{id}', 'editsupplierAward')->name('editsupplierAward');
        Route::post('/supplier-awards/update/{id}', 'updatesupplierAward')->name('updatesupplierAward');

        // Approval Actions
        Route::post('/supplier-awards/approve/{id}', 'approvesupplierAward')->name('approvesupplierAward');
        Route::post('/supplier-awards/reject/{id}', 'rejectsupplierAward')->name('rejectsupplierAward');

        // Soft Delete & Recovery
        Route::get('/supplier-awards/destroy/{id}', 'destroysupplierAward')->name('destroysupplierAward');
        Route::get('/supplier-awards/deleted', 'deletedsupplierAward')->name('deletedsupplierAward');
        Route::get('/supplier-awards/restore/{id}', 'restoresupplierAward')->name('restoresupplierAward');
    });

    Route::controller(PurchaseOrderController::class)->group(function () {
        Route::get('/purchase-orders', 'purchaseOrder')->name('purchaseOrder');
        Route::get('/purchase-orders/create', 'createpurchaseOrder')->name('createpurchaseOrder');
        Route::post('/purchase-orders/store', 'storepurchaseOrder')->name('storepurchaseOrder');
        Route::get('/purchase-orders/view/{id}', 'viewpurchaseOrder')->name('viewpurchaseOrder');
        Route::get('/purchase-orders/edit/{id}', 'editpurchaseOrder')->name('editpurchaseOrder');
        Route::post('/purchase-orders/update/{id}', 'updatepurchaseOrder')->name('updatepurchaseOrder');

        // Approval Actions
        Route::post('/purchase-orders/approve/{id}', 'approvepurchaseOrder')->name('approvepurchaseOrder');
        Route::post('/purchase-orders/reject/{id}', 'rejectpurchaseOrder')->name('rejectpurchaseOrder');

        // Soft Delete & Restore
        Route::get('/purchase-orders/destroy/{id}', 'destroypurchaseOrder')->name('destroypurchaseOrder');
        Route::get('/purchase-orders/deleted', 'deletedpurchaseOrder')->name('deletedpurchaseOrder');
        Route::get('/purchase-orders/restore/{id}', 'restorepurchaseOrder')->name('restorepurchaseOrder');
    });


    Route::controller(PurchaseOrderController::class)->group(function () {
        // Goods Receipts History List
        Route::get('/goods-receipts', 'goodsReceipt')->name('goodsReceipt');

        // Unreceived Approved Purchase Orders List
        Route::get('/goods-receipts/unreceived-purchase-orders', 'unreceivedPurchaseOrders')->name('unreceivedPurchaseOrders');

        // Receive Form & Action
        Route::get('/goods-receipts/create/{encryptedPoId}', 'creategoodsReceipt')->name('creategoodsReceipt');
        Route::post('/goods-receipts/store/{encryptedPoId}', 'storegoodsReceipt')->name('storegoodsReceipt');

        // View Details
        Route::get('/goods-receipts/view/{encryptedId}', 'viewgoodsReceipt')->name('viewgoodsReceipt');

        // Soft Delete & Restore
        Route::get('/goods-receipts/destroy/{encryptedId}', 'destroygoodsReceipt')->name('destroygoodsReceipt');
        Route::get('/goods-receipts/deleted', 'deletedgoodsReceipt')->name('deletedgoodsReceipt');
        Route::get('/goods-receipts/restore/{encryptedId}', 'restoregoodsReceipt')->name('restoregoodsReceipt');
    });



    Route::controller(SupplierController::class)->group(function () {
        // Invoices List
        Route::get('/supplier-invoices', 'supplierInvoice')->name('supplierInvoice');

        // Pending Goods Receipts to Invoice
        Route::get('/supplier-invoices/un-invoiced-goods-receipts', 'unInvoicedGoodsReceipts')->name('unInvoicedGoodsReceipts');

        // Create Invoice against GRN
        Route::get('/supplier-invoices/create/{encryptedGrnId}', 'createsupplierInvoice')->name('createsupplierInvoice');
        Route::post('/supplier-invoices/store/{encryptedGrnId}', 'storesupplierInvoice')->name('storesupplierInvoice');

        // View & Edit Invoice
        Route::get('/supplier-invoices/view/{encryptedId}', 'viewsupplierInvoice')->name('viewsupplierInvoice');
        Route::get('/supplier-invoices/edit/{encryptedId}', 'editsupplierInvoice')->name('editsupplierInvoice');
        Route::post('/supplier-invoices/update/{encryptedId}', 'updatesupplierInvoice')->name('updatesupplierInvoice');

        // Verification / Approval Action
        Route::post('/supplier-invoices/verify/{encryptedId}', 'verifysupplierInvoice')->name('verifysupplierInvoice');

        // Soft Delete & Restore
        Route::get('/supplier-invoices/destroy/{encryptedId}', 'destroysupplierInvoice')->name('destroysupplierInvoice');
        Route::get('/supplier-invoices/deleted', 'deletedsupplierInvoice')->name('deletedsupplierInvoice');
        Route::get('/supplier-invoices/restore/{encryptedId}', 'restoresupplierInvoice')->name('restoresupplierInvoice');
    });


    Route::controller(SupplierController::class)->group(function () {
        // Payments History List
        Route::get('/supplier-payments', 'supplierPayment')->name('supplierPayment');

        // Unpaid & Partially Paid Verified Invoices List
        Route::get('/supplier-payments/unpaid-invoices', 'unPaidSupplierInvoices')->name('unPaidSupplierInvoices');

        // Make Payment Form & Action
        Route::get('/supplier-payments/create/{encryptedInvoiceId}', 'createsupplierPayment')->name('createsupplierPayment');
        Route::post('/supplier-payments/store/{encryptedInvoiceId}', 'storesupplierPayment')->name('storesupplierPayment');

        // View Payment Details
        Route::get('/supplier-payments/view/{encryptedId}', 'viewsupplierPayment')->name('viewsupplierPayment');

        // Soft Delete & Recovery
        Route::get('/supplier-payments/destroy/{encryptedId}', 'destroysupplierPayment')->name('destroysupplierPayment');
        Route::get('/supplier-payments/deleted', 'deletedsupplierPayment')->name('deletedsupplierPayment');
        Route::get('/supplier-payments/restore/{encryptedId}', 'restoresupplierPayment')->name('restoresupplierPayment');
    });










    Route::get('/groupCenter', [GroupController::class, 'groupCenter'])->name('groupCenter');
    Route::post('/storegroupCenter', [GroupController::class, 'storegroupCenter'])->name('storegroupCenter');
    Route::get('/editgroupCenter/{id}', [GroupController::class, 'editgroupCenter'])->name('editgroupCenter');
    Route::put('/updategroupCenter/{id}', [GroupController::class, 'updategroupCenter'])->name('updategroupCenter');
    Route::get('/destroygroupCenter/{id}', [GroupController::class, 'destroygroupCenter'])->name('destroygroupCenter');
    Route::get('/innactivegroupCenter', [GroupController::class, 'innactivegroupCenter'])->name('innactivegroupCenter');
    Route::get('/activategroupCenter/{id}', [GroupController::class, 'activategroupCenter'])->name('activategroupCenter');

    Route::get('/centerGroups', [GroupController::class, 'centerGroups'])->name('centerGroups');
    Route::post('/storecenterGroups', [GroupController::class, 'storecenterGroups'])->name('storecenterGroups');
    Route::get('/editcenterGroups/{id}', [GroupController::class, 'editcenterGroups'])->name('editcenterGroups');
    Route::put('/updatecenterGroups/{id}', [GroupController::class, 'updatecenterGroups'])->name('updatecenterGroups');
    Route::get('/destroycenterGroups/{id}', [GroupController::class, 'destroycenterGroups'])->name('destroycenterGroups');
    Route::get('/innactivecenterGroups', [GroupController::class, 'innactivecenterGroups'])->name('innactivecenterGroups');
    Route::get('/activatecenterGroups/{id}', [GroupController::class, 'activatecenterGroups'])->name('activatecenterGroups');


    Route::get('/clientinformations', [GroupController::class, 'clientinformations'])->name('clientinformations');
    Route::post('/storeclientinformations', [GroupController::class, 'storeclientinformations'])->name('storeclientinformations');
    Route::get('/editclientinformations/{id}', [GroupController::class, 'editclientinformations'])->name('editclientinformations');
    Route::put('/updateclientinformations/{id}', [GroupController::class, 'updateclientinformations'])->name('updateclientinformations');
    Route::get('/destroyclientinformations/{id}', [GroupController::class, 'destroyclientinformations'])->name('destroyclientinformations');
    Route::get('/showclientinformations/{id}/show', [GroupController::class, 'showclientinformations'])->name('showclientinformations');

    Route::get('/groupMembers', [GroupController::class, 'groupMembers'])->name('groupMembers');
    Route::post('/storegroupMembers', [GroupController::class, 'storegroupMembers'])->name('storegroupMembers');
    Route::get('/editgroupMembers/{id}', [GroupController::class, 'editgroupMembers'])->name('editgroupMembers');
    Route::get('/destroygroupMembers/{id}', [GroupController::class, 'destroygroupMembers'])->name('destroygroupMembers');
    Route::get('/innactivegroupMembers', [GroupController::class, 'innactivegroupMembers'])->name('innactivegroupMembers');


    Route::get('/loancategories', [LoanController::class, 'loancategories'])->name('loancategories');
    Route::post('/storeloancategory', [LoanController::class, 'storeloancategory'])->name('storeloancategory');
    Route::get('/editloancategory/{id}', [LoanController::class, 'editloancategory'])->name('editloancategory');
    Route::post('/updateloancategory/{id}', [LoanController::class, 'updateloancategory'])->name('updateloancategory');
    Route::get('/destroyloancategory/{id}', [LoanController::class, 'destroyloancategory'])->name('destroyloancategory');
    Route::get('/viewloancategory/{id}', [LoanController::class, 'viewloancategory'])->name('viewloancategory');

    Route::get('/loansinformations', [LoanController::class, 'loansinformations'])->name('loansinformations');
    Route::get('/printloaninformationformtemplate', [LoanController::class, 'printloaninformationformtemplate'])->name('printloaninformationformtemplate');
    Route::post('/registerloaninformation', [LoanController::class, 'registerloaninformation'])->name('registerloaninformation');
    Route::get('/editloaninformation/{id}', [LoanController::class, 'editloaninformation'])->name('editloaninformation');
    Route::post('/updateloaninformation/{id}', [LoanController::class, 'updateloaninformation'])->name('updateloaninformation');
    Route::get('/viewloaninformation/{id}', [LoanController::class, 'viewloaninformation'])->name('viewloaninformation');
    Route::get('/destroyloaninformation/{id}', [LoanController::class, 'destroyloaninformation'])->name('destroyloaninformation');
    Route::get('/printloaninformationform/{id}', [LoanController::class, 'printloaninformationform'])->name('printloaninformationform');

    // LOAN DOUBLING
    Route::get('/doubledloansinformations', [LoanController::class, 'doubledloansinformations'])->name('doubledloansinformations');
    Route::post('/storeloandoublinginformations', [LoanController::class, 'storeloandoublinginformations'])->name('storeloandoublinginformations');
    Route::get('/doublethisloansinformations/{id}', [LoanController::class, 'doublethisloansinformations'])->name('doublethisloansinformations');
    Route::get('/viewloandoublinginformations/{id}', [LoanController::class, 'viewloandoublinginformations'])->name('viewloandoublinginformations');
    Route::get('/viewapprovedoubledloansinformations/{id}', [LoanController::class, 'viewapprovedoubledloansinformations'])->name('viewapprovedoubledloansinformations');
    Route::get('/approvedoubledloansinformations', [LoanController::class, 'approvedoubledloansinformations'])->name('approvedoubledloansinformations');

    // LOAN REFUNDED
    Route::get('/refundedloansinformations', [LoanController::class, 'refundedloansinformations'])->name('refundedloansinformations');
    Route::get('/refundthisloansinformations/{id}', [LoanController::class, 'refundthisloansinformations'])->name('refundthisloansinformations');
    Route::get('/viewapproverefundedloansinformations/{id}', [LoanController::class, 'viewapproverefundedloansinformations'])->name('viewapproverefundedloansinformations');
    Route::get('/approverefundedloansinformations/{id}', [LoanController::class, 'approverefundedloansinformations'])->name('approverefundedloansinformations');
    Route::get('/unrefundloansinfo/{id}', [LoanController::class, 'unrefundloansinfo'])->name('unrefundloansinfo');
    // Refund Requests
    Route::get('/pendingloanrefund', [LoanController::class, 'pendingloanrefund'])->name('pendingloanrefund');
    Route::get('/viewloanrefund/{id}', [LoanController::class, 'viewloanrefund'])->name('viewloanrefund');
    Route::post('/approveloanrefund/{id}', [LoanController::class, 'approveloanrefund'])->name('approveloanrefund');
    Route::post('/rejectloanrefund/{id}', [LoanController::class, 'rejectloanrefund'])->name('rejectloanrefund');
    // LOAN APPROVAL
    Route::get('/approveloansinformations', [LoanController::class, 'approveloansinformations'])->name('approveloansinformations');
    Route::get('/viewapproveloansinformations/{id}', [LoanController::class, 'viewapproveloansinformations'])->name('viewapproveloansinformations');
    Route::get('/rejectedloansinformations', [LoanController::class, 'rejectedloansinformations'])->name('rejectedloansinformations');
    Route::post('/approveloansinfo/{id}', [LoanController::class, 'approveloansinfo'])->name('approveloansinfo');
    Route::get('/rejectloansinfo/{id}', [LoanController::class, 'rejectloansinfo'])->name('rejectloansinfo');


    Route::get('/loanpenaltycategories', [LoanController::class, 'loanpenaltycategories'])->name('loanpenaltycategories');
    Route::post('/storeloanpenaltycategory', [LoanController::class, 'storeloanpenaltycategory'])->name('storeloanpenaltycategory');
    Route::get('/editloanpenaltycategory/{id}', [LoanController::class, 'editloanpenaltycategory'])->name('editloanpenaltycategory');
    Route::post('/updateloanpenaltycategory/{id}', [LoanController::class, 'updateloanpenaltycategory'])->name('updateloanpenaltycategory');
    Route::get('/viewloanpenaltycategory/{id}', [LoanController::class, 'viewloanpenaltycategory'])->name('viewloanpenaltycategory');
    Route::get('/destroyloanpenaltycategory/{id}', [LoanController::class, 'destroyloanpenaltycategory'])->name('destroyloanpenaltycategory');

    // ROAN REPAYMENT
    Route::get('/loansrepayments', [LoanController::class, 'loansrepayments'])->name('loansrepayments');
    Route::post('/storeloanrepayment', [LoanController::class, 'storeloanrepayment'])->name('storeloanrepayment');
    Route::get('/editloanrepayment/{id}', [LoanController::class, 'editloanrepayment'])->name('editloanrepayment');
    Route::put('/updateloanrepayment/{id}', [LoanController::class, 'updateloanrepayment'])->name('updateloanrepayment');
    Route::get('/viewloanrepayment/{id}', [LoanController::class, 'viewloanrepayment'])->name('viewloanrepayment');
    Route::get('/destroyloanrepayment/{id}', [LoanController::class, 'destroyloanrepayment'])->name('destroyloanrepayment');
    Route::get('/downloadloanrepaymenttemplate', [LoanController::class, 'downloadloanrepaymenttemplate'])->name('downloadloanrepaymenttemplate');
    Route::post('/importloanrepayments', [LoanController::class, 'importloanrepayments'])->name('importloanrepayments');
    // FEE REPAYMENTS
    Route::get('/loansrepaymentsfees', [LoanController::class, 'loansrepaymentsfees'])->name('loansrepaymentsfees');
    Route::post('/storeloanrepaymentfee', [LoanController::class, 'storeloanrepaymentfee'])->name('storeloanrepaymentfee');
    Route::get('/editloanrepaymentfee/{id}', [LoanController::class, 'editloanrepaymentfee'])->name('editloanrepaymentfee');
    Route::post('/updateloanrepaymentfee/{id}', [LoanController::class, 'updateloanrepaymentfee'])->name('updateloanrepaymentfee');
    Route::get('/viewloanrepaymentfee/{id}', [LoanController::class, 'viewloanrepaymentfee'])->name('viewloanrepaymentfee');
    Route::get('/destroyloanrepaymentfee/{id}', [LoanController::class, 'destroyloanrepaymentfee'])->name('destroyloanrepaymentfee');
    Route::get('/downloadloanrepaymenttemplatefee', [LoanController::class, 'downloadloanrepaymenttemplatefee'])->name('downloadloanrepaymenttemplatefee');
    Route::post('/importloanrepaymentsfee', [LoanController::class, 'importloanrepaymentsfee'])->name('importloanrepaymentsfee');
    Route::get('/unpaidloanpenalties', [LoanController::class, 'unpaidloanpenalties'])->name('unpaidloanpenalties');


    Route::get('/guarantors', [LoanController::class, 'guarantors'])->name('guarantors');
    Route::post('/storeguarantor', [LoanController::class, 'storeguarantor'])->name('storeguarantor');
    Route::get('/editguarantor/{id}', [LoanController::class, 'editguarantor'])->name('editguarantor');
    Route::post('/updateguarantor/{id}', [LoanController::class, 'updateguarantor'])->name('updateguarantor');
    Route::get('/viewguarantor/{id}', [LoanController::class, 'viewguarantor'])->name('viewguarantor');
    Route::get('/destroyguarantor/{id}', [LoanController::class, 'destroyguarantor'])->name('destroyguarantor');

    Route::get('/loanguarantors', [LoanController::class, 'loanguarantors'])->name('loanguarantors');
    Route::post('/storeloanguarantor', [LoanController::class, 'storeloanguarantor'])->name('storeloanguarantor');
    Route::get('/editloanguarantor/{id}', [LoanController::class, 'editloanguarantor'])->name('editloanguarantor');
    Route::post('/updateloanguarantor/{id}', [LoanController::class, 'updateloanguarantor'])->name('updateloanguarantor');
    Route::get('/viewloanguarantor/{id}', [LoanController::class, 'viewloanguarantor'])->name('viewloanguarantor');
    Route::get('/destroyloanguarantor/{id}', [LoanController::class, 'destroyloanguarantor'])->name('destroyloanguarantor');

    Route::get('/loanpenalties', [LoanController::class, 'loanpenalties'])->name('loanpenalties');
    Route::post('/storeloanpenalty', [LoanController::class, 'storeloanpenalty'])->name('storeloanpenalty');
    Route::get('/editloanpenalty/{id}', [LoanController::class, 'editloanpenalty'])->name('editloanpenalty');
    Route::post('/updateloanpenalty/{id}', [LoanController::class, 'updateloanpenalty'])->name('updateloanpenalty');
    Route::get('/viewloanpenalty/{id}', [LoanController::class, 'viewloanpenalty'])->name('viewloanpenalty');
    Route::get('/destroyloanpenalty/{id}', [LoanController::class, 'destroyloanpenalty'])->name('destroyloanpenalty');
    Route::get('/payloanpenalty/{id}', [LoanController::class, 'payloanpenalty'])->name('payloanpenalty');

    // EXPENSES
    Route::get('/expensecategoryinformations',[ExpenseController::class,'expensecategoryinformations'])->name('expensecategoryinformations');
    Route::post('/registerexpensecategory',[ExpenseController::class,'registerexpensecategory'])->name('registerexpensecategory');
    Route::get('/viewexpensecategory/{id}',[ExpenseController::class,'viewexpensecategory'])->name('viewexpensecategory');
    Route::get('/editexpensecategory/{id}',[ExpenseController::class,'editexpensecategory'])->name('editexpensecategory');
    Route::post('/updateexpensecategory/{id}',[ExpenseController::class,'updateexpensecategory'])->name('updateexpensecategory');
    Route::get('/deleteexpensecategory/{id}',[ExpenseController::class,'deleteexpensecategory'])->name('deleteexpensecategory');
    Route::get('/activeexpensecategory/{id}',[ExpenseController::class,'activeexpensecategory'])->name('activeexpensecategory');
    Route::get('/inactiveexpensecategory/{id}',[ExpenseController::class,'inactiveexpensecategory'])->name('inactiveexpensecategory');

    Route::get('/expenseinformations', [ExpenseController::class, 'expenseinformations'])->name('expenseinformations');
    Route::post('/registerexpense', [ExpenseController::class, 'registerexpense'])->name('registerexpense');
    Route::post('/importexcelexpense', [ExpenseController::class, 'importexcelexpense'])->name('importexcelexpense');
    Route::get('/viewexpense/{id}', [ExpenseController::class, 'viewexpense'])->name('viewexpense');
    Route::get('/editexpense', [ExpenseController::class, 'editexpense'])->name('editexpense');
    Route::post('/updateexpense/{id}', [ExpenseController::class, 'updateexpense'])->name('updateexpense');
    Route::get('/deleteexpense/{id}', [ExpenseController::class, 'deleteexpense'])->name('deleteexpense');

    Route::get('/pendingexpenseinformations', [ExpenseController::class, 'pendingexpenseinformations'])->name('pendingexpenseinformations');
    Route::post('/approveexpense/{id}', [ExpenseController::class, 'approveexpense'])->name('approveexpense');
    Route::post('/rejectexpense/{id}', [ExpenseController::class, 'rejectexpense'])->name('rejectexpense');
    Route::get('/pendingexpense', [ExpenseController::class, 'pendingexpense'])->name('pendingexpense');
    Route::post('/payexpense/{id}', [ExpenseController::class, 'payexpense'])->name('payexpense');
    Route::get('/unpayedexpense', [ExpenseController::class, 'unpayedexpense'])->name('unpayedexpense');
    Route::get('/payedexpense', [ExpenseController::class, 'payedexpense'])->name('payedexpense');
    Route::get('/expensehistory', [ExpenseController::class, 'expensehistory'])->name('expensehistory');
    Route::get('/expensepayments', [ExpenseController::class, 'expensepayments'])->name('expensepayments');
    Route::get('/expensepaymenthistory', [ExpenseController::class, 'expensepaymenthistory'])->name('expensepaymenthistory');

    // Salary Information
    Route::get('/salaryinformations', [SalaryController::class, 'salaryinformations'])->name('salaryinformations');
    Route::get('/registersalary', [SalaryController::class, 'registersalary'])->name('registersalary');
    Route::post('/storesalary', [SalaryController::class, 'storesalary'])->name('storesalary');
    Route::get('/viewsalary/{id}', [SalaryController::class, 'viewsalary'])->name('viewsalary');
    Route::post('/updatesalary', [SalaryController::class, 'updatesalary'])->name('updatesalary');
    Route::get('/deletesalary/{id}', [SalaryController::class, 'deletesalary'])->name('deletesalary');
    Route::get('/pendingsalary', [SalaryController::class, 'pendingsalary'])->name('pendingsalary');
    Route::post('/approvesalary/{id}', [SalaryController::class, 'approvesalary'])->name('approvesalary');
    Route::post('/rejectsalary/{id}', [SalaryController::class, 'rejectsalary'])->name('rejectsalary');
    Route::post('/paysalary/{id}', [SalaryController::class, 'paysalary'])->name('paysalary');
    Route::get('/pendingpaysalary', [SalaryController::class, 'pendingpaysalary'])->name('pendingpaysalary');
    Route::get('/salaryhistory', [SalaryController::class, 'salaryhistory'])->name('salaryhistory');

    Route::get('/weeklyallowanceinformations', [SalaryController::class, 'weeklyallowanceinformations'])->name('weeklyallowanceinformations');
    Route::get('/registerweeklyallowance', [SalaryController::class, 'registerweeklyallowance'])->name('registerweeklyallowance');
    Route::post('/storeweeklyallowance', [SalaryController::class, 'storeweeklyallowance'])->name('storeweeklyallowance');
    Route::get('/viewweeklyallowance/{id}', [SalaryController::class, 'viewweeklyallowance'])->name('viewweeklyallowance');
    Route::post('/updateweeklyallowance', [SalaryController::class, 'updateweeklyallowance'])->name('updateweeklyallowance');
    Route::get('/deleteweeklyallowance/{id}', [SalaryController::class, 'deleteweeklyallowance'])->name('deleteweeklyallowance');
    Route::get('/pendingweeklyallowance', [SalaryController::class, 'pendingweeklyallowance'])->name('pendingweeklyallowance');
    Route::post('/approveweeklyallowance/{id}', [SalaryController::class, 'approveweeklyallowance'])->name('approveweeklyallowance');
    Route::post('/rejectweeklyallowance/{id}', [SalaryController::class, 'rejectweeklyallowance'])->name('rejectweeklyallowance');
    Route::get('/payweeklyallowances', [SalaryController::class, 'payweeklyallowances'])->name('payweeklyallowances');
    Route::post('/payweeklyallowance/{id}', [SalaryController::class, 'payweeklyallowance'])->name('payweeklyallowance');
    Route::get('/weeklyallowancehistory', [SalaryController::class, 'weeklyallowancehistory'])->name('weeklyallowancehistory');


        // EXPENSES ROUTES
    Route::get('/expensecategories', [ExpenseController::class, 'expensecategories'])->name('expensecategories');
    Route::post('/storeexpensecategory', [ExpenseController::class, 'storeexpensecategory'])->name('storeexpensecategory');
    Route::get('/editexpensecategory/{id}', [ExpenseController::class, 'editexpensecategory'])->name('editexpensecategory');
    Route::post('/updateexpensecategory/{id}', [ExpenseController::class, 'updateexpensecategory'])->name('updateexpensecategory');
    Route::get('/viewexpensecategory/{id}', [ExpenseController::class, 'viewexpensecategory'])->name('viewexpensecategory');
    Route::get('/destroyexpensecategory/{id}', [ExpenseController::class, 'destroyexpensecategory'])->name('destroyexpensecategory');

    Route::get('/expenses', [ExpenseController::class, 'expenses'])->name('expenses');
    Route::post('/storeexpense', [ExpenseController::class, 'storeexpense'])->name('storeexpense');
    Route::get('/editexpense/{id}', [ExpenseController::class, 'editexpense'])->name('editexpense');
    Route::post('/updateexpense/{id}', [ExpenseController::class, 'updateexpense'])->name('updateexpense');
    Route::get('/viewexpense/{id}', [ExpenseController::class, 'viewexpense'])->name('viewexpense');
    Route::get('/approveexpenses', [ExpenseController::class, 'approveexpenses'])->name('approveexpenses');
    Route::get('/approveexpense/{id}', [ExpenseController::class, 'approveexpense'])->name('approveexpense');
    Route::get('/rejectexpense/{id}', [ExpenseController::class, 'rejectexpense'])->name('rejectexpense');
    Route::get('/destroyexpense/{id}', [ExpenseController::class, 'destroyexpense'])->name('destroyexpense');

});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Example dashboard routes per role
    Route::get('/admin/dashboard', fn() => view('dashboards.admin'))->name('admin.dashboard');
    Route::get('/director/dashboard', fn() => view('dashboards.director'))->name('director.dashboard');
    Route::get('/ceo/dashboard', fn() => view('dashboards.ceo'))->name('ceo.dashboard');
    Route::get('/shareholders/dashboard', fn() => view('dashboards.shareholders'))->name('shareholders.dashboard');
    Route::get('/manager/dashboard', fn() => view('dashboards.manager'))->name('manager.dashboard');
    Route::get('/marketingofficer/dashboard', fn() => view('dashboards.marketingofficer'))->name('marketingofficer.dashboard');
    Route::get('/hr/dashboard', fn() => view('dashboards.hr'))->name('hr.dashboard');
    Route::get('/accountant/dashboard', fn() => view('dashboards.accountant'))->name('accountant.dashboard');
    Route::get('/secretary/dashboard', fn() => view('dashboards.secretary'))->name('secretary.dashboard');
    Route::get('/loanofficer/dashboard', fn() => view('dashboards.loanofficer'))->name('loanofficer.dashboard');
    Route::get('/client/dashboard', fn() => view('dashboards.client'))->name('client.dashboard');
    Route::get('/user/dashboard', fn() => view('dashboards.user'))->name('user.dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::resource('employees', EmployeeManagementController::class);

    Route::post('employees/{employee}/toggle-status', [EmployeeManagementController::class, 'toggleStatus'])
        ->name('employees.toggle-status');
    Route::post('employees/{employee}/referees', [EmployeeManagementController::class, 'storeReferee'])
        ->name('employees.referees.store');
    Route::delete('referees/{referee}', [EmployeeManagementController::class, 'deleteReferee'])
        ->name('referees.destroy');

    Route::get('/search-officers', [EmployeeManagementController::class, 'searchOfficers'])->name('search.officers');

});


Route::middleware(['auth'])->group(function () {
Route::resource('groups', GroupController::class);

});

Route::middleware(['auth'])->group(function () {
Route::get('/groups/{group}/members/create', [GroupMemberController::class, 'create'])->name('group_members.create');
Route::post('/groups/{group}/members', [GroupMemberController::class, 'store'])->name('group_members.store');
Route::delete('/group_members/{member}', [GroupMemberController::class, 'destroy'])->name('group_members.destroy');
});


Route::middleware(['auth'])->group(function () {
Route::resource('clients', ClientController::class);
Route::get('/clients/{client}/export', [ClientController::class, 'export'])->name('clients.export');

});


Route::middleware(['auth'])->group(function () {
Route::resource('group_centers', GroupCenterController::class);
});

Route::middleware(['auth'])->group(function () {
Route::resource('loan_categories', LoanCategoryController::class);
Route::patch('loan_categories/{loanCategory}/toggle', [LoanCategoryController::class, 'toggleStatus'])
     ->name('loan_categories.toggle');
Route::resource('loans', LoanController::class);
Route::post('/loans/{id}/preclosure/set', [LoanController::class, 'setPreclosureFee'])->name('loans.preclosure.set');
Route::post('/loans/{id}/preclosure/pay', [LoanController::class, 'markPreclosurePaid'])->name('loans.preclosure.pay');
Route::post('/loans/{id}/refunding/set', [LoanController::class, 'setRefund'])->name('loans.refund.set');

Route::resource('loan_payments', LoanPaymentController::class);
});


Route::middleware(['auth'])->group(function () {
    // Employee Export Routes
    Route::get('employees/export', [EmployeeExportController::class, 'exportOptions'])
        ->name('employees.export.options');
    Route::post('employees/export/pdf', [EmployeeExportController::class, 'exportPdf'])
        ->name('employees.export.pdf');
    Route::post('employees/export/excel', [EmployeeExportController::class, 'exportExcel'])
        ->name('employees.export.excel');
    Route::post('employees/export/csv', [EmployeeExportController::class, 'exportCsv'])
        ->name('employees.export.csv');
});

// NEW CLIENT REQUST CONTROLLER


Route::middleware(['auth'])->group(function () {
Route::resource('loan_request_continueng_client', LoanRequestContinuengClientController::class)
    ->parameters(['loan_request_continueng_client' => 'loan']);;

Route::resource('loan_request_new_client', LoanRequestNewClientController::class)
    ->parameters(['loan_request_new_client' => 'loan']);

Route::resource('loan-pfficers-loans', LoanOfficerLoansController::class)
    ->parameters(['loan-pfficers-loans' => 'loan']);
});



Route::prefix('loan-approvals')->middleware(['auth'])->group(function () {
    Route::get('loan-approvals/', [LoanApprovalController::class, 'index'])->name('loan-approvals.index');
    Route::get('loan-approvals/{id}', [LoanApprovalController::class, 'show'])->name('loan-approvals.show');
    Route::post('loan-approvals/{id}/approve', [LoanApprovalController::class, 'approve'])->name('loan-approvals.approve');
    Route::post('loan-approvals/{id}/reject', [LoanApprovalController::class, 'reject'])->name('loan-approvals.reject');
});


Route::middleware(['auth'])->group(function () {
Route::get('/repayments/{loan}', [RepaymentScheduleController::class, 'show'])->name('repayment_schedules.show');
Route::post('/repayments/pay/{id}', [RepaymentScheduleController::class, 'pay'])->name('repayments.pay');
Route::post('/schedules/{id}/penalty', [RepaymentScheduleController::class, 'addPenalty'])->name('schedules.addPenalty');
});
// Route::get('/repayments/{loan}', [RepaymentScheduleController::class, 'show'])->name('repayment_schedules.show');


Route::middleware(['auth'])->group(function () {
Route::resource('donations', DonationController::class);

Route::resource('salary_levels', SalaryLevelController::class);

Route::resource('employee_salaries', EmployeeSalaryController::class);
Route::get('/employees/search', [EmployeeSalaryController::class, 'searchEmployees'])->name('employees.search');


    Route::resource('employee_salary_payments', EmployeeSalaryPaymentController::class);

    // Route for employee to approve/sign salary payment
    Route::get('employee_salary_payments/{id}/sign', [EmployeeSalaryPaymentController::class, 'sign'])
        ->name('employee_salary_payments.sign');

    Route::post('employee_salary_payments/{id}/sign', [EmployeeSalaryPaymentController::class, 'storeSignature'])
        ->name('employee_salary_payments.storeSignature');
});


Route::middleware(['auth'])->prefix('employee-payments')->name('employee_payments.')->group(function () {
    Route::get('/', [EmployeeSalaryPaymentController::class, 'index'])->name('index');
    Route::get('/{id}/create', [EmployeeSalaryPaymentController::class, 'create'])->name('create');
    Route::post('/', [EmployeeSalaryPaymentController::class, 'store'])->name('store');
    Route::get('/{id}', [EmployeeSalaryPaymentController::class, 'show'])->name('show');
    Route::delete('/{id}', [EmployeeSalaryPaymentController::class, 'destroy'])->name('destroy');
});


Route::middleware(['auth'])->group(function () {
Route::resource('employee_weekly_allowances', EmployeeWeeklyAllowanceController::class);

// For AJAX search
Route::get('/search/employees', [EmployeeWeeklyAllowanceController::class, 'searchEmployees'])->name('search.employees');
});




Route::middleware(['auth'])->group(function () {
Route::resource('client_loans', ClientLoanController::class);
Route::post('client_loans/{clientLoan}/close', [ClientLoanController::class, 'closeLoan'])->name('client_loans.close');
});



Route::middleware(['auth'])->group(function () {
    Route::resource('daily_collections', DailyCollectionController::class);
});

Route::middleware(['auth'])->group(function () {
Route::resource('client-loan-photos', App\Http\Controllers\Loan\ClientLoanPhotoController::class);
});
// Add these routes to your web.php file



Route::middleware(['auth'])->group(function () {
    Route::get('/loansAnalysis/dashboard', [LoanDashboardController::class, 'index'])->name('loans_dashboard.dashboard');
    Route::get('/loansAnalysis/export', [LoanDashboardController::class, 'export'])->name('loans_export');
    Route::get('/loans/export/excel', [LoanDashboardController::class, 'exportExcel'])->name('loans.export.excel');
    Route::get('/loans/export/pdf', [LoanDashboardController::class, 'exportPdf'])->name('loans.export.pdf');
});





Route::middleware('auth')->group(function () {
    Route::get('/collections/summary', [CollectionSummaryController::class, 'index'])->name('collections.summary.index');
    Route::get('/collections/summary/export/excel', [CollectionSummaryController::class, 'exportExcel'])->name('collections.summary.export.excel');
    Route::get('/collections/summary/export/pdf', [CollectionSummaryController::class, 'exportPdf'])->name('collections.summary.export.pdf');
    Route::get('/collections/summary/export/pdfwithnodata', [CollectionSummaryController::class, 'exportPdfWithNoData'])->name('collections.summary.export.pdfwithnodata');
});
Route::prefix('loans/collections')->name('loans.collections.')->middleware(['auth'])->group(function () {
    // Main collection summary page
    Route::get('/summary', [CollectionSummaryController2::class, 'index'])->name('summary.index');
    
    // Trending data API (AJAX)
    Route::get('/summary/trending-data', [CollectionSummaryController2::class, 'getTrendingDataApi'])->name('summary.trending');
    
    // Export routes
    Route::get('/summary/export/excel', [CollectionSummaryController2::class, 'exportExcel'])->name('summary.export.excel');
    Route::get('/summary/export/pdf', [CollectionSummaryController2::class, 'exportPdf'])->name('summary.export.pdf');
    Route::get('/summary/print', [CollectionSummaryController2::class, 'print'])->name('summary.print');
    
    // AJAX endpoints for cascading dropdowns
    Route::get('/api/groups-by-center/{centerId}', [CollectionSummaryController2::class, 'getGroupsByCenter'])->name('api.groups-by-center');
    Route::get('/api/clients-by-group/{groupId}', [CollectionSummaryController2::class, 'getClientsByGroup'])->name('api.clients-by-group');
    Route::get('/api/loans-by-client/{clientId}', [CollectionSummaryController2::class, 'getLoansByClient'])->name('api.loans-by-client');
});

Route::middleware(['auth'])->prefix('reports/loans')->name('reports.loans.')->group(function () {
    // Main report page
    Route::get('/', [LoanReportController::class, 'index'])->name('index');
    
    // Trending data for graph (AJAX)
    Route::get('/trending-data', [LoanReportController::class, 'getTrendingData'])->name('trending');
    
    // Export routes
    Route::get('/export/excel', [LoanReportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [LoanReportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/print', [LoanReportController::class, 'print'])->name('print');
});

// API routes for cascading dropdowns
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/groups-by-center/{centerId}', [LoanReportController::class, 'getGroupsByCenter']);
    Route::get('/clients-by-group/{groupId}', [LoanReportController::class, 'getClientsByGroup']);
});


// Add these routes to your routes/web.php file


// use App\Http\Controllers\Loan\LoanCategoryController;

// Route::post('loan-categories/{loan_category}/toggle-status', [LoanCategoryController::class, 'toggleStatus'])
//     ->name('loan_categories.toggleStatus');
// Route::resource('loan_categories', LoanCategoryController::class);


// use App\Http\Controllers\Loan\LoanController;
// Route::resource('loans', LoanController::class);


// use App\Http\Controllers\Loan\RepaymentScheduleController;
// Route::get('loans/{loan}/schedule', [RepaymentScheduleController::class, 'index'])->name('schedules.index');
// Route::get('schedules/{repaymentSchedule}', [RepaymentScheduleController::class, 'show'])->name('schedules.show');

// use App\Http\Controllers\Loan\PaymentController;
// Route::get('loans/{loan}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
// Route::post('loans/{loan}/payments', [PaymentController::class, 'store'])->name('payments.store');

