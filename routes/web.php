
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
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CostCentreController;
use App\Http\Controllers\MemberController;
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

    Route::get('/employeeinfo', [EmployeeController::class, 'employeeinfo'])->name('employeeinfo');
    Route::post('/storenewemployeeinfo', [EmployeeController::class, 'storenewemployeeinfo'])->name('storenewemployeeinfo');
    Route::get('employees/{id}/edit', [EmployeeController::class, 'editemployeeinfo'])->name('editemployeeinfo');
    Route::put('employees/{id}/update', [EmployeeController::class, 'updateemployeeinfo'])->name('updateemployeeinfo');
    Route::get('/destroyemployeeinfo/{id}', [EmployeeController::class, 'destroyemployeeinfo'])->name('destroyemployeeinfo');
    Route::post('/storeemployeeInformation', [EmployeeController::class, 'storeemployeeInformation'])->name('storeemployeeInformation');
    Route::get('/editemployeeInformation/{id}', [EmployeeController::class, 'editemployeeInformation'])->name('editemployeeInformation');
    Route::get('/viewemployeeInformation/{id}', [EmployeeController::class, 'viewemployeeInformation'])->name('viewemployeeInformation');
    Route::get('/deletedemployeeInformation', [EmployeeController::class, 'deletedemployeeInformation'])->name('deletedemployeeInformation');

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

});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/toggle-lock', [UserController::class, 'toggleLock'])->name('users.toggle-lock');
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
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

Route::resource('expense-categories', ExpenseCategoryController::class);
Route::resource('expenses', ExpenseController::class);
Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
Route::get('/expenses/export-excel', [ExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
Route::get('/expenses/export-pdf', [ExpenseController::class, 'exportPDF'])->name('expenses.export.pdf');

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

