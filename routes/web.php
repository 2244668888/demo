<?php

use App\Http\Controllers\AmortizationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BomController;
use App\Http\Controllers\OEEController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AreaRackController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OutgoingController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SalePriceController;
use App\Http\Controllers\AreaLevelController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ShopfloorController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\DiscrepancyController;
use App\Http\Controllers\PurchasePriceController;
use App\Http\Controllers\TypeOfProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\GoodReceivingController;
use App\Http\Controllers\MachineStatusController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\MachineTonnageController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\TransferRequestController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockCardReportController;
use App\Http\Controllers\SupplierRankingController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\TypeOfRejectionController;
use App\Http\Controllers\StockRelocationController;
use App\Http\Controllers\SummaryDoReportController;
use App\Http\Controllers\PurchasePlanningController;
use App\Http\Controllers\CallForAssistanceController;
use App\Http\Controllers\ProductReorderingController;
use App\Http\Controllers\InvertoryShopfloorController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\InvertoryDashboardController;
use App\Http\Controllers\DeliveryInstructionController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\MaterialRequisitionController;
use App\Http\Controllers\ProductionSchedulingController;
use App\Http\Controllers\DailyProductionPlanningController;
use App\Http\Controllers\MonthlyProductionPlanningController;
use App\Http\Controllers\ProductionOutputTraceabilityController;
use App\Http\Controllers\SummaryAttendanceController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\AccountDashboardController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\CarryForwardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AgingReportController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StripePaymentController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/stripe-payment', [MembershipController::class, 'showPaymentForm'])->name('stripe.payment');
Route::post('/stripe-payment', [MembershipController::class, 'handleStripePayment'])->name('stripe.post');
Route::get('/payment-success', [MembershipController::class, 'paymentSuccess'])->name('payment.success');



Route::resource('services', ServiceController::class);

Route::get('/memberships/create', [MembershipController::class, 'create'])->name('memberships.create');
Route::post('/memberships', [MembershipController::class, 'store'])->name('memberships.store');
Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
Route::get('memberships/{id}/edit', [MembershipController::class, 'edit'])->name('memberships.edit');
Route::put('memberships/{id}', [MembershipController::class, 'update'])->name('memberships.update'); 
Route::delete('memberships/{id}', [MembershipController::class, 'destroy'])->name('memberships.destroy');
Route::get('memberships/{id}/preview', [MembershipController::class, 'preview'])->name('memberships.preview');





Route::get('/', function () {
    return redirect('login');
})->name('main');

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => true
]);

// NON ACTIVE USER
Route::view('/non-active-user', 'layouts.non_active_user')->name('non_active_user');

Route::middleware('auth')->group(function () {

    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');

    Route::get('/notifications/count', function () {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count(),
        ]);
    })->name('notifications.count');


    Route::get('/notifications/all', function () {
        return response()->json([
            'notifications' => Auth::user()->unreadNotifications,
        ]);
    })->name('notifications.all');

    Route::post('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');




    // HOME
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // NOTIFICATIONS
    Route::get('/notification', [App\Http\Controllers\NotificationController::class, 'index'])->name('get.notifications');

    // ACCOUNT SETTINGS
    Route::get('/account/settings', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/account/settings/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/account/settings/password', [App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password.update');

    //ERP BD ROUTES

    // QUOTATION
    Route::get('erp/bd/quotation/index', [QuotationController::class, 'index'])->name('quotation.index');
    Route::get('erp/bd/quotation/data', [QuotationController::class, 'Data'])->name('quotation.data');
    Route::get('erp/bd/quotation/create', [QuotationController::class, 'create'])->name('quotation.create');
    Route::post('erp/bd/quotation/store', [QuotationController::class, "store"])->name('quotation.store');
    Route::get('erp/bd/quotation/edit/{id}', [QuotationController::class, "edit"])->name('quotation.edit');
    Route::get('erp/bd/quotation/view/{id}', [QuotationController::class, "view"])->name('quotation.view');
    Route::get('erp/bd/quotation/preview/{id}', [QuotationController::class, "preview"])->name('quotation.preview');
    Route::post('erp/bd/quotation/update/{id}', [QuotationController::class, "update"])->name('quotation.update');
    Route::delete('erp/bd/quotation/destroy/{id}', [QuotationController::class, "destroy"])->name('quotation.destroy');
    Route::get('erp/bd/quotation/verify/{id}', [QuotationController::class, 'verify'])->name('quotation.verify');
    Route::post('erp/bd/quotation/verify/update/{id}', [QuotationController::class, 'verify_update'])->name('quotation.verify_update');
    Route::post('erp/bd/quotation/decline_cancel/{id}', [QuotationController::class, "decline_cancel"])->name('quotation.decline_cancel');

    // ORDER
    Route::get('erp/bd/order/index', [OrderController::class, 'index'])->name('order.index');
    Route::get('erp/bd/order/data', [OrderController::class, 'Data'])->name('order.data');
    Route::get('erp/bd/order/create', [OrderController::class, 'create'])->name('order.create');
    Route::post('erp/bd/order/store', [OrderController::class, "store"])->name('order.store');
    Route::get('erp/bd/order/edit/{id}', [OrderController::class, "edit"])->name('order.edit');
    Route::get('erp/bd/order/view/{id}', [OrderController::class, "view"])->name('order.view');
    Route::post('erp/bd/order/update/{id}', [OrderController::class, "update"])->name('order.update');
    Route::delete('erp/bd/order/destroy/{id}', [OrderController::class, "destroy"])->name('order.destroy');

    // SALE PRICE
    Route::get('erp/bd/sale-price/getData', [SalePriceController::class, "getData"])->name('sale_price.getData');
    Route::get('erp/bd/sale-price/index', [SalePriceController::class, 'index'])->name('sale_price.index');
    Route::get('erp/bd/sale-price/data', [SalePriceController::class, 'Data'])->name('sale_price.data');
    Route::get('erp/bd/sale-price/create', [SalePriceController::class, 'create'])->name('sale_price.create');
    Route::post('erp/bd/sale-price/store', [SalePriceController::class, "store"])->name('sale_price.store');
    Route::get('erp/bd/sale-price/edit/{id}', [SalePriceController::class, "edit"])->name('sale_price.edit');
    Route::get('erp/bd/sale-price/view/{id}', [SalePriceController::class, "view"])->name('sale_price.view');
    Route::post('erp/bd/sale-price/update/{id}', [SalePriceController::class, "update"])->name('sale_price.update');
    Route::delete('erp/bd/sale-price/destroy/{id}', [SalePriceController::class, "destroy"])->name('sale_price.destroy');
    Route::get('erp/bd/sale-price/verify/{id}', [SalePriceController::class, 'verify'])->name('sale_price.verify');
    Route::post('erp/bd/sale-price/verify/update/{id}', [SalePriceController::class, 'verify_update'])->name('sale_price.verify_update');
    Route::post('erp/bd/sale-price/decline_cancel/{id}', [SalePriceController::class, "decline_cancel"])->name('sale_price.decline_cancel');
    Route::get('erp/bd/sale-price/get-Purchase-price', [SalePriceController::class, 'get_Purchase_price'])->name('get.Purchase_price');
    Route::post('hr/leave/verify/status/{id}', [SalePriceController::class, "verified"])->name('sale_price.verifyStatus');
    Route::post('hr/leave/decline/status/{id}', [SalePriceController::class, "declined"])->name('sale_price.declineStatus');


    // INVOICE
    Route::get('erp/bd/invoice/index', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('erp/bd/invoice/data', [InvoiceController::class, 'Data'])->name('invoice.data');
    Route::get('erp/bd/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('erp/bd/invoice/store', [InvoiceController::class, "store"])->name('invoice.store');
    Route::get('erp/bd/invoice/edit/{id}', [InvoiceController::class, "edit"])->name('invoice.edit');
    Route::get('erp/bd/invoice/view/{id}', [InvoiceController::class, "view"])->name('invoice.view');
    Route::post('erp/bd/invoice/update/{id}', [InvoiceController::class, "update"])->name('invoice.update');
    Route::delete('erp/bd/invoice/destroy/{id}', [InvoiceController::class, "destroy"])->name('invoice.destroy');
    Route::get('erp/bd/invoice/preview/{id}', [InvoiceController::class, "preview"])->name('invoice.preview');
    Route::get('erp/bd/invoice/paymentvoucher/{id}', [InvoiceController::class, "paymentVoucherApprove"])->name('invoice.payment_voucher_approve');
    Route::post('erp/bd/invoice/paymentVouchercheck/{id}', [InvoiceController::class, 'paymentVouchercheck'])->name('invoice.paymentVouchercheck');

    //END ERP BD ROUTES

    //ERP PVD ROUTES

     // PURCHASE PRICE
     Route::get('erp/pvd/purchase-price/index', [PurchasePriceController::class, 'index'])->name('purchase_price.index');
    Route::get('erp/pvd/purchase-price/data', [PurchasePriceController::class, 'Data'])->name('purchase_price.data');
     Route::get('erp/pvd/purchase-price/create', [PurchasePriceController::class, "create"])->name('purchase_price.create');
     Route::post('erp/pvd/purchase-price/store', [PurchasePriceController::class, "store"])->name('purchase_price.store');
     Route::get('erp/pvd/purchase-price/view/{id}', [PurchasePriceController::class, "view"])->name('purchase_price.view');
     Route::get('erp/pvd/purchase-price/edit/{id}', [PurchasePriceController::class, "edit"])->name('purchase_price.edit');
     Route::post('erp/pvd/purchase-price/update/{id}', [PurchasePriceController::class, "update"])->name('purchase_price.update');
     Route::delete('erp/pvd/purchase-price/destroy/{id}', [PurchasePriceController::class, "destroy"])->name('purchase_price.destroy');
     Route::get('erp/bd/purchase-price/verify/{id}', [PurchasePriceController::class, 'verified'])->name('purchase_price.verify');
     Route::post('erp/bd/purchase-price/verify/update/{id}', [PurchasePriceController::class, 'verify_update'])->name('purchase_price.verify_update');
     Route::post('erp/bd/purchase-price/decline_cancel/{id}', [PurchasePriceController::class, "decline_cancel"])->name('purchase_price.decline_cancel');
    Route::get('erp/pvd/purchase-price/get-Sale-price', [PurchasePriceController::class, 'get_Sale_price'])->name('get.Sale_price');
    Route::post('erp/bd/purchase-price/verify/status/{id}', [PurchasePriceController::class, "verify"])->name('purchase_price.verifying');
    Route::post('erp/bd/purchase-price/decline/{id}', [PurchasePriceController::class, "decline"])->name('purchase_price.decline');
    Route::get('erp/pvd/purchase-price/getData', [PurchasePriceController::class, "getData"])->name('purchase_price.getData');
     // PURCHASE PLANNING
    Route::get('erp/pvd/purchase-planning/index', [PurchasePlanningController::class, 'index'])->name('purchase_planning.index');
    Route::get('erp/pvd/purchase-planning/data', [PurchasePlanningController::class, 'Data'])->name('purchase_planning.data');
    Route::get('erp/pvd/purchase-planning/create', [PurchasePlanningController::class, "create"])->name('purchase_planning.create');
    Route::get('erp/pvd/purchase-planning/bom/get', [PurchasePlanningController::class, "bom_get"])->name('purchase_planning.bom.get');
    Route::post('erp/pvd/purchase-planning/store', [PurchasePlanningController::class, "store"])->name('purchase_planning.store');
    Route::get('erp/pvd/purchase-planning/view/{id}', [PurchasePlanningController::class, "view"])->name('purchase_planning.view');
    Route::get('erp/pvd/purchase-planning/edit/{id}', [PurchasePlanningController::class, "edit"])->name('purchase_planning.edit');
    Route::post('erp/pvd/purchase-planning/update/{id}', [PurchasePlanningController::class, "update"])->name('purchase_planning.update');
    Route::get('erp/pvd/purchase-planning/destroy/{id}', [PurchasePlanningController::class, "destroy"])->name('purchase_planning.destroy');
    Route::get('erp/pvd/purchase-planning/verification/{id}/{action}', [PurchasePlanningController::class, "verification"])->name('purchase_planning.verification');
    Route::post('erp/pvd/purchase-planning/check/{id}', [PurchasePlanningController::class, "check"])->name('purchase_planning.check');
    Route::post('erp/pvd/purchase-planning/verify-hod/{id}', [PurchasePlanningController::class, "verifyHOD"])->name('purchase_planning.verify_hod');
    Route::post('erp/pvd/purchase-planning/verify-acc/{id}', [PurchasePlanningController::class, "verifyAcc"])->name('purchase_planning.verify_acc');
    Route::post('erp/pvd/purchase-planning/approve/{id}', [PurchasePlanningController::class, "approve"])->name('purchase_planning.approve');
    Route::post('erp/pvd/purchase-planning/decline_cancel/{id}', [PurchasePlanningController::class, "decline_cancel"])->name('purchase_planning.decline_cancel');

     // PURCHASE REQUISITION
     Route::get('erp/pvd/purchase-requisition/index', [PurchaseRequisitionController::class, 'index'])->name('purchase_requisition.index');
     Route::get('erp/pvd/purchase-requisition/data', [PurchaseRequisitionController::class, 'Data'])->name('purchase_requisition.data');
     Route::get('erp/pvd/purchase-requisition/create', [PurchaseRequisitionController::class, "create"])->name('purchase_requisition.create');
     Route::post('erp/pvd/purchase-requisition/store', [PurchaseRequisitionController::class, "store"])->name('purchase_requisition.store');
     Route::get('erp/pvd/purchase-requisition/view/{id}', [PurchaseRequisitionController::class, "view"])->name('purchase_requisition.view');
     Route::get('erp/pvd/purchase-requisition/edit/{id}', [PurchaseRequisitionController::class, "edit"])->name('purchase_requisition.edit');
     Route::get('erp/pvd/purchase-requisition/changestatus/{id}/{status}', [PurchaseRequisitionController::class, "changestatus"])->name('purchase_requisition.changestatus');
     Route::post('erp/pvd/purchase-requisition/verify/{id}/{hodacc}', [PurchaseRequisitionController::class, "verify"])->name('purchase_requisition.verify');
     Route::post('erp/pvd/purchase-requisition/approve/{id}', [PurchaseRequisitionController::class, "approve"])->name('purchase_requisition.approve');
     Route::post('erp/pvd/purchase-requisition/decline/{id}/{hodacc}', [PurchaseRequisitionController::class, "decline"])->name('purchase_requisition.decline');
     Route::post('erp/pvd/purchase-requisition/cancel/{id}', [PurchaseRequisitionController::class, "cancel"])->name('purchase_requisition.cancel');
     Route::post('erp/pvd/purchase-requisition/update/{id}', [PurchaseRequisitionController::class, "update"])->name('purchase_requisition.update');
     Route::delete('erp/pvd/purchase-requisition/destroy/{id}', [PurchaseRequisitionController::class, "destroy"])->name('purchase_requisition.destroy');

     // PURCHASE ORDER
    Route::get('erp/pvd/purchase-order/index', [PurchaseOrderController::class, 'index'])->name('purchase_order.index');
    Route::get('erp/pvd/purchase-order/data', [PurchaseOrderController::class, 'Data'])->name('purchase_order.data');
    Route::get('erp/pvd/purchase-order/create', [PurchaseOrderController::class, "create"])->name('purchase_order.create');
    Route::get('erp/pvd/purchase-order/bom/get', [PurchaseOrderController::class, "bom_get"])->name('purchase_order.bom.get');
    Route::post('erp/pvd/purchase-order/store', [PurchaseOrderController::class, "store"])->name('purchase_order.store');
    Route::get('erp/pvd/purchase-order/view/{id}', [PurchaseOrderController::class, "view"])->name('purchase_order.view');
    Route::get('erp/pvd/purchase-order/edit/{id}', [PurchaseOrderController::class, "edit"])->name('purchase_order.edit');
    Route::post('erp/pvd/purchase-order/update/{id}', [PurchaseOrderController::class, "update"])->name('purchase_order.update');
    Route::get('erp/pvd/purchase-order/preview/{id}', [PurchaseOrderController::class, "preview"])->name('purchase_order.preview');
    Route::delete('erp/pvd/purchase-order/destroy/{id}', [PurchaseOrderController::class, "destroy"])->name('purchase_order.destroy');
    Route::get('erp/pvd/purchase-order/verification/{id}/{action}', [PurchaseOrderController::class, "verification"])->name('purchase_order.verification');
    Route::post('erp/pvd/purchase-order/check/{id}', [PurchaseOrderController::class, "check"])->name('purchase_order.check');
    Route::post('erp/pvd/purchase-order/verify/{id}', [PurchaseOrderController::class, "verify"])->name('purchase_order.verify');
    Route::post('erp/pvd/purchase-order/decline_cancel/{id}', [PurchaseOrderController::class, "decline_cancel"])->name('purchase_order.decline_cancel');

     // SUPPLIER RANKING
     Route::get('erp/pvd/supplier-ranking/index', [SupplierRankingController::class, 'index'])->name('supplier_ranking.index');
     Route::get('erp/pvd/supplier-ranking/data', [SupplierRankingController::class, 'Data'])->name('supplier_ranking.data');
     Route::get('erp/pvd/supplier-ranking/create', [SupplierRankingController::class, "create"])->name('supplier_ranking.create');
     Route::post('erp/pvd/supplier-ranking/store', [SupplierRankingController::class, "store"])->name('supplier_ranking.store');
     Route::get('erp/pvd/supplier-ranking/view/{id}', [SupplierRankingController::class, "view"])->name('supplier_ranking.view');
     Route::get('erp/pvd/supplier-ranking/edit/{id}', [SupplierRankingController::class, "edit"])->name('supplier_ranking.edit');
     Route::post('erp/pvd/supplier-ranking/update/{id}', [SupplierRankingController::class, "update"])->name('supplier_ranking.update');
     Route::delete('erp/pvd/supplier-ranking/destroy/{id}', [SupplierRankingController::class, "destroy"])->name('supplier_ranking.destroy');

    //END ERP PVD ROUTES

    //MES ROUTES

    // MACHINE STATUS
    Route::get('/mes/dashboard/machine-status', [MachineStatusController::class, "index"])->name('machine_status');
    Route::get('/mes/dashboard/machine-status/generate', [MachineStatusController::class, "generate"])->name('machine_status.generate');

    // SHOPFLOOR
    Route::get('/mes/dashboard/shopfloor', [ShopfloorController::class, "index"])->name('shopfloor');
    Route::get('/mes/dashboard/shopfloor/generate', [ShopfloorController::class, "generate"])->name('shopfloor.generate');

    //ENGINEERING
    // BOM
    Route::get('/mes/engineering/bom/index', [BomController::class, 'index'])->name('bom');
    Route::get('/mes/engineering/bom/data', [BomController::class, 'data'])->name('bom.data');
    Route::get('/mes/engineering/bom/create', [BomController::class, "create"])->name('bom.create');
    Route::post('/mes/engineering/bom/store', [BomController::class, "store"])->name('bom.store');
    Route::get('/mes/engineering/bom/view/{id}', [BomController::class, "view"])->name('bom.view');
    Route::get('/mes/engineering/bom/edit/{id}', [BomController::class, "edit"])->name('bom.edit');
    Route::get('/mes/engineering/bom/verification/{id}', [BomController::class, "verification"])->name('bom.verification');
    Route::post('/mes/engineering/bom/verify/{id}', [BomController::class, "verify"])->name('bom.verify');
    Route::post('/mes/engineering/bom/decline/{id}', [BomController::class, "decline"])->name('bom.decline');
    Route::post('/mes/engineering/bom/cancel/{id}', [BomController::class, "cancel"])->name('bom.cancel');
    Route::get('/mes/engineering/bom/inactive/{id}', [BomController::class, "inactive"])->name('bom.inactive');
    Route::post('/mes/engineering/bom/update/{id}', [BomController::class, "update"])->name('bom.update');
    Route::delete('/mes/engineering/bom/destroy/{id}', [BomController::class, "destroy"])->name('bom.destroy');

    // BOM REPORT
    Route::get('/mes/engineering/bom-report', [BomController::class, "bom_report"])->name('bom.report');
    Route::post('/mes/engineering/bom-report/genrate', [BomController::class, "bom_report_generate"])->name('bom.report.genrate');
    Route::post('/mes/engineering/bom-report/export', [BomController::class, "bomReportExport"])->name('bom.report.export');

    //PPC
    //MONTHLY PRODUCTION PLANNING
    Route::get('/mes/ppc/monthly-production-planning', [MonthlyProductionPlanningController::class, "monthly_production_planning"])->name('ppc.monthly_production_planning');
    Route::post('/mes/ppc/monthly-production-planning/generate', [MonthlyProductionPlanningController::class, "generate"])->name('ppc.monthly_production_planning.generate');

    //DAILY PRODUCTION PLANNING
    Route::get('/mes/ppc/daily-production-planning', [DailyProductionPlanningController::class, 'index'])->name('daily-production-planning');
    Route::get('/mes/ppc/daily-production-planning/data', [DailyProductionPlanningController::class, 'Data'])->name('daily-production-planning.data');
    Route::get('/mes/ppc/daily-production-planning/create', [DailyProductionPlanningController::class, "create"])->name('daily-production-planning.create');
    Route::post('/mes/ppc/daily-production-planning/generate', [DailyProductionPlanningController::class, 'generate'])->name('daily-production-planning.generate');
    Route::post('/mes/ppc/daily-production-planning/get_subparts', [DailyProductionPlanningController::class, 'get_subparts'])->name('daily-production-planning.get_subparts');
    Route::post('/mes/ppc/daily-production-planning/get_subparts_inv_qty', [DailyProductionPlanningController::class, 'get_inventory_qty'])->name('daily-production-planning.get_inventory_qty');
    Route::post('/mes/ppc/daily-production-planning/get_bom_process', [DailyProductionPlanningController::class, 'get_bom_process'])->name('daily-production-planning.get_bom_process');
    Route::post('/mes/ppc/daily-production-planning/store', [DailyProductionPlanningController::class, "store"])->name('daily-production-planning.store');
    Route::get('/mes/ppc/daily-production-planning/view/{id}', [DailyProductionPlanningController::class, "view"])->name('daily-production-planning.view');
    Route::get('/mes/ppc/daily-production-planning/edit/{id}', [DailyProductionPlanningController::class, "edit"])->name('daily-production-planning.edit');
    Route::get('/mes/ppc/daily-production-planning/get_date', [DailyProductionPlanningController::class, "get_date"])->name('daily-production-planning.get_date');
    Route::get('/mes/ppc/daily-production-planning/machines-by-tonnage/{tonnageId}', [DailyProductionPlanningController::class, 'getMachinesByTonnage'])->name('daily-production-planning.getmachinebytonnage');
    Route::post('/mes/ppc/daily-production-planning/update/{id}', [DailyProductionPlanningController::class, "update"])->name('daily-production-planning.update');
    Route::delete('/mes/ppc/daily-production-planning/destroy/{id}', [DailyProductionPlanningController::class, "destroy"])->name('daily-production-planning.destroy');
    Route::post('/mes/ppc/daily-production-planning/planning-store', [DailyProductionPlanningController::class, "planning_store"])->name('daily-production-planning.planning.store');

    //PRODUCTION SCHEDULING
    Route::get('/mes/ppc/production-scheduling', [ProductionSchedulingController::class, 'index'])->name('production-scheduling.index');
    Route::get('/mes/ppc/production-scheduling/getSchedules', [ProductionSchedulingController::class, 'getSchedules'])->name('production-scheduling.getSchedules');

    //END MES ENGINEERING ROUTES

    //MES PRODUCTION ROUTES

    // PRODUCTION OUTPUT TRACEABILITY
    Route::get('/mes/production/production-output-traceability',[ProductionOutputTraceabilityController::class, 'index'])->name('production_output_traceability.index');
    Route::get('/mes/production/production-output-traceability/view/{id}',[ProductionOutputTraceabilityController::class, 'edit'])->name('production_output_traceability.view');
    Route::get('/mes/production/production-output-traceability/edit/{id}',[ProductionOutputTraceabilityController::class, 'edit'])->name('production_output_traceability.edit');
    Route::post('/mes/production/production-output-traceability/update/{id}',[ProductionOutputTraceabilityController::class, 'update'])->name('production_output_traceability.update');
    Route::post('/mes/production/production-output-traceability/rejection',[ProductionOutputTraceabilityController::class, 'rejection'])->name('production_output_traceability.rejection');
    Route::get('/mes/production/production-output-traceability/qc/edit/{id}',[ProductionOutputTraceabilityController::class, 'edit'])->name('production_output_traceability.qc_edit');
    Route::post('/mes/production/production-output-traceability/qc/update/{id}',[ProductionOutputTraceabilityController::class, 'qc_update'])->name('production_output_traceability.qc_update');
    Route::post('/mes/production/production-output-traceability/production/starter',[ProductionOutputTraceabilityController::class, 'starter'])->name('production_output_traceability.starter');
    Route::get('/mes/production/production-output-traceability/production/machineCount',[ProductionOutputTraceabilityController::class, 'machine_count_data'])->name('production_output_traceability.machine_count');

    // SUMMARY REPORT
    Route::get('/mes/production/summary-report', [SummaryController::class, "index"])->name('summary_report');
    Route::get('/mes/production/summary-report/generate', [SummaryController::class, "generate"])->name('summary_report.generate');

    // CALL FOR ASSISTANCE
    Route::get('/mes/production/call-for-assistance',[CallForAssistanceController::class, 'index'])->name('call_for_assistance.index');
    Route::get('/mes/production/call-for-assistance/edit/{id}',[CallForAssistanceController::class, 'edit'])->name('call_for_assistance.edit');
    Route::post('/mes/production/call-for-assistance/update/{id}',[CallForAssistanceController::class, 'update'])->name('call_for_assistance.update');
    Route::get('/mes/production/call-for-assistance/view/{id}',[CallForAssistanceController::class, 'view'])->name('call_for_assistance.view');

    // OEE REPORT
    Route::get('/mes/oee/oee-report', [OEEController::class, "index"])->name('oee');
    Route::get('/mes/oee/oee-report/generate', [OEEController::class, "generate"])->name('oee.generate');
    Route::get('/mes/oee/oee-report/details', [OEEController::class, "details"])->name('oee.details');

    //END MES PRODUCTION ROUTES

    //WMS DASHBOARD ROUTES

    // INVENTORY DASHBOARD
    Route::get('/wms/dashboard/inventory-dashboard', [InvertoryDashboardController::class, 'index'])->name('inventory_dashboard');
    Route::get('/wms/dashboard/inventory-dashboard/generate', [InvertoryDashboardController::class, 'generate'])->name('inventory_dashboard.generate');

    // INVENTORY SHOPFLOOR
    Route::get('/wms/dashboard/inventory-shopfloor', [InvertoryShopfloorController::class, 'index'])->name('inventory_shopfloor');
    Route::get('/wms/dashboard/inventory-shopfloor/generate', [InvertoryShopfloorController::class, 'generate'])->name('inventory_shopfloor.generate');
    Route::get('/wms/dashboard/inventory-shopfloor/generate2', [InvertoryShopfloorController::class, 'generate2'])->name('inventory_shopfloor.generate2');

    //END WMS DASHBOARD ROUTES

    //WMS OPERATIONS ROUTES

    // DELIVERY INSTRUCTION
    Route::get('wms/operations/delivery-instruction/index', [DeliveryInstructionController::class, 'index'])->name('delivery_instruction.index');
    Route::get('wms/operations/delivery-instruction/data', [DeliveryInstructionController::class, 'Data'])->name('delivery_instruction.data');
    Route::get('wms/operations/delivery-instruction/create', [DeliveryInstructionController::class, 'create'])->name('delivery_instruction.create');
    Route::post('wms/operations/delivery-instruction/store', [DeliveryInstructionController::class, "store"])->name('delivery_instruction.store');
    Route::get('wms/operations/delivery-instruction/edit/{id}', [DeliveryInstructionController::class, "edit"])->name('delivery_instruction.edit');
    Route::get('wms/operations/delivery-instruction/view/{id}', [DeliveryInstructionController::class, "view"])->name('delivery_instruction.view');
    Route::post('wms/operations/delivery-instruction/update/{id}', [DeliveryInstructionController::class, "update"])->name('delivery_instruction.update');
    Route::delete('wms/operations/delivery-instruction/destroy/{id}', [DeliveryInstructionController::class, "destroy"])->name('delivery_instruction.destroy');

    // GOOD RECEIVING
    Route::get('wms/operations/good-receiving/index', [GoodReceivingController::class, 'index'])->name('good_receiving.index');
    Route::get('wms/operations/good-receiving/data', [GoodReceivingController::class, 'Data'])->name('good_receiving.data');
    Route::get('wms/operations/good-receiving/create', [GoodReceivingController::class, 'create'])->name('good_receiving.create');
    Route::post('wms/operations/good-receiving/store', [GoodReceivingController::class, "store"])->name('good_receiving.store');
    Route::get('wms/operations/good-receiving/edit/{id}', [GoodReceivingController::class, "edit"])->name('good_receiving.edit');
    Route::post('wms/operations/good-receiving/update/{id}', [GoodReceivingController::class, "update"])->name('good_receiving.update');
    Route::get('wms/operations/good-receiving/receive/{id}', [GoodReceivingController::class, "receive"])->name('good_receiving.receive');
    Route::get('wms/operations/good-receiving/qc/{id}', [GoodReceivingController::class, "qc"])->name('good_receiving.qc');
    Route::post('wms/operations/good-receiving/qc_update/{id}', [GoodReceivingController::class, "qc_update"])->name('good_receiving.qc_update');
    Route::get('wms/operations/good-receiving/allocation/{id}', [GoodReceivingController::class, "allocation"])->name('good_receiving.allocation');
    Route::post('wms/operations/good-receiving/allocation_update/{id}', [GoodReceivingController::class, "allocation_update"])->name('good_receiving.allocation_update');
    Route::get('wms/operations/good-receiving/approve/{id}', [GoodReceivingController::class, "approve"])->name('good_receiving.approve');
    Route::get('wms/operations/good-receiving/view/{id}', [GoodReceivingController::class, "view"])->name('good_receiving.view');
    Route::delete('wms/operations/good-receiving/destroy/{id}', [GoodReceivingController::class, "destroy"])->name('good_receiving.destroy');

    // MATERIAL REQUISITION
    Route::get('wms/operations/material-requisition/create-material-planning/{id?}', [MaterialRequisitionController::class, 'create_planning'])->name('material_planning.create');
    Route::post('wms/operations/material-requisition/store-material-planning', [MaterialRequisitionController::class, "store_materialplanning"])->name('material_planning.store');
    Route::get('wms/operations/material-requisition/index', [MaterialRequisitionController::class, 'index'])->name('material_requisition.index');
    Route::get('wms/operations/material-requisition/data', [MaterialRequisitionController::class, 'Data'])->name('material_requisition.data');
    Route::get('wms/operations/material-requisition/create/{id?}', [MaterialRequisitionController::class, 'create'])->name('material_requisition.create');
    Route::post('wms/operations/material-requisition/store', [MaterialRequisitionController::class, "store"])->name('material_requisition.store');
    Route::get('wms/operations/material-requisition/edit/{id}', [MaterialRequisitionController::class, "edit"])->name('material_requisition.edit');
    Route::post('wms/operations/material-requisition/update/{id}', [MaterialRequisitionController::class, "update"])->name('material_requisition.update');
    Route::get('wms/operations/material-requisition/receive/{id}', [MaterialRequisitionController::class, "receive"])->name('material_requisition.receive');
    Route::post('wms/operations/material-requisition/received/{id}', [MaterialRequisitionController::class, "received"])->name('material_requisition.received');
    Route::get('wms/operations/material-requisition/issue/{id}', [MaterialRequisitionController::class, "issue"])->name('material_requisition.issue');
    Route::get('wms/operations/material-requisition/issue/print/{id}', [MaterialRequisitionController::class, "issue_print"])->name('material_requisition.issue.print');
    Route::get('wms/operations/material-requisition/issue/ack/{id}', [MaterialRequisitionController::class, "rec_ack"])->name('material_requisition.issue.ack');
    Route::get('wms/operations/material-requisition/issue/reject/{id}', [MaterialRequisitionController::class, "rec_reject"])->name('material_requisition.issue.reject');
    Route::post('wms/operations/material-requisition/issued/{id}', [MaterialRequisitionController::class, "issued"])->name('material_requisition.issued');
    Route::get('wms/operations/material-requisition/view/{id}', [MaterialRequisitionController::class, "view"])->name('material_requisition.view');
    Route::delete('wms/operations/material-requisition/destroy/{id}', [MaterialRequisitionController::class, "destroy"])->name('material_requisition.destroy');

    // TRANSFER REQUEST

    Route::get('wms/operations/transfer-request/index', [TransferRequestController::class, 'index'])->name('transfer_request.index');
    Route::get('wms/operations/transfer-request/data', [TransferRequestController::class, 'Data'])->name('transfer_request.data');
    Route::get('wms/operations/transfer-request/create', [TransferRequestController::class, 'create'])->name('transfer_request.create');
    Route::post('wms/operations/transfer-request/store', [TransferRequestController::class, "store"])->name('transfer_request.store');
    Route::get('wms/operations/transfer-request/edit/{id}', [TransferRequestController::class, "edit"])->name('transfer_request.edit');
    Route::post('wms/operations/transfer-request/update/{id}', [TransferRequestController::class, "update"])->name('transfer_request.update');
    Route::get('wms/operations/transfer-request/receive/{id}', [TransferRequestController::class, "receive"])->name('transfer_request.receive');
    Route::post('wms/operations/transfer-request/received/{id}', [TransferRequestController::class, "received"])->name('transfer_request.received');
    Route::get('wms/operations/transfer-request/issue/{id}', [TransferRequestController::class, "issue"])->name('transfer_request.issue');
    Route::post('wms/operations/transfer-request/issued/{id}', [TransferRequestController::class, "issued"])->name('transfer_request.issued');
    Route::get('wms/operations/transfer-request/view/{id}', [TransferRequestController::class, "view"])->name('transfer_request.view');
    Route::delete('wms/operations/transfer-request/destroy/{id}', [TransferRequestController::class, "destroy"])->name('transfer_request.destroy');
    Route::get('wms/operations/transfer-request/get-products-by-mrf/{mrf_id}', [TransferRequestController::class, 'getProductsByMrf'])->name('get-products-by-mrf');

    // Discrepancy
    Route::get('wms/operations/discrepancy/index', [DiscrepancyController::class, 'index'])->name('discrepancy.index');
    Route::get('wms/operations/discrepancy/data', [DiscrepancyController::class, 'Data'])->name('discrepancy.data');
    Route::get('wms/operations/discrepancy/edit/{id}/{check}', [DiscrepancyController::class, "edit"])->name('discrepancy.edit');
    Route::post('wms/operations/discrepancy/update/{id}', [DiscrepancyController::class, "update"])->name('discrepancy.update');
    Route::get('wms/operations/discrepancy/view/{id}/{check}', [DiscrepancyController::class, "view"])->name('discrepancy.view');

    // STOCK RELOCATION
    Route::get('wms/operations/stock-relocation/index', [StockRelocationController::class, 'index'])->name('stock_relocation.index');
    Route::get('wms/operations/stock-relocation/data', [StockRelocationController::class, 'Data'])->name('stock_relocation.data');
    Route::get('wms/operations/stock-relocation/create', [StockRelocationController::class, 'create'])->name('stock_relocation.create');
    Route::get('wms/operations/stock-relocation/products', [StockRelocationController::class, 'products'])->name('stock_relocation.products');
    Route::post('wms/operations/stock-relocation/store', [StockRelocationController::class, "store"])->name('stock_relocation.store');
    Route::get('wms/operations/stock-relocation/edit/{id}', [StockRelocationController::class, "edit"])->name('stock_relocation.edit');
    Route::get('wms/operations/stock-relocation/view/{id}', [StockRelocationController::class, "view"])->name('stock_relocation.view');
    Route::post('wms/operations/stock-relocation/update/{id}', [StockRelocationController::class, "update"])->name('stock_relocation.update');
    Route::delete('wms/operations/stock-relocation/destroy/{id}', [StockRelocationController::class, "destroy"])->name('stock_relocation.destroy');

    // PRODUCT REORDERING
    Route::get('wms/operations/product-reordering/index', [ProductReorderingController::class, 'index'])->name('product_reordering.index');
    Route::get('wms/operations/product-reordering/data', [ProductReorderingController::class, 'Data'])->name('product_reordering.data');
    Route::get('wms/operations/product-reordering/create', [ProductReorderingController::class, 'create'])->name('product_reordering.create');
    Route::get('wms/operations/product-reordering/products', [ProductReorderingController::class, 'products'])->name('product_reordering.products');
    Route::post('wms/operations/product-reordering/store', [ProductReorderingController::class, "store"])->name('product_reordering.store');
    Route::get('wms/operations/product-reordering/edit/{id}', [ProductReorderingController::class, "edit"])->name('product_reordering.edit');
    Route::get('wms/operations/product-reordering/view/{id}', [ProductReorderingController::class, "view"])->name('product_reordering.view');
    Route::post('wms/operations/product-reordering/update/{id}', [ProductReorderingController::class, "update"])->name('product_reordering.update');
    Route::delete('wms/operations/product-reordering/destroy/{id}', [ProductReorderingController::class, "destroy"])->name('product_reordering.destroy');

    // STOCK ADJUSTMENT
    Route::get('wms/operations/stock-adjustment/index', [StockAdjustmentController::class, 'index'])->name('stock_adjustment.index');
    Route::get('wms/operations/stock-adjustment/data', [StockAdjustmentController::class, 'Data'])->name('stock_adjustment.data');
    Route::get('wms/operations/stock-adjustment/edit/{id}', [StockAdjustmentController::class, "edit"])->name('stock_adjustment.edit');
    Route::post('wms/operations/stock-adjustment/update/{id}', [StockAdjustmentController::class, "update"])->name('stock_adjustment.update');

    // OUTGOING
    Route::get('wms/operations/outgoing/index', [OutgoingController::class, 'index'])->name('outgoing.index');
    Route::get('wms/operations/outgoing/data', [OutgoingController::class, 'Data'])->name('outgoing.data');
    Route::get('wms/operations/outgoing/create', [OutgoingController::class, 'create'])->name('outgoing.create');
    Route::post('wms/operations/outgoing/store', [OutgoingController::class, "store"])->name('outgoing.store');
    Route::get('wms/operations/outgoing/edit/{id}', [OutgoingController::class, "edit"])->name('outgoing.edit');
    Route::get('wms/operations/outgoing/view/{id}', [OutgoingController::class, "view"])->name('outgoing.view');
    Route::get('wms/operations/outgoing/preview/{id}', [OutgoingController::class, "preview"])->name('outgoing.preview');
    Route::post('wms/operations/outgoing/update/{id}', [OutgoingController::class, "update"])->name('outgoing.update');
    Route::delete('wms/operations/outgoing/destroy/{id}', [OutgoingController::class, "destroy"])->name('outgoing.destroy');

    // SALES RETURN
    Route::get('wms/operations/sales-return/index', [SalesReturnController::class, 'index'])->name('sales_return.index');
    Route::get('wms/operations/sales-return/data', [SalesReturnController::class, 'Data'])->name('sales_return.data');
    Route::get('wms/operations/sales-return/create', [SalesReturnController::class, 'create'])->name('sales_return.create');
    Route::post('wms/operations/sales-return/store', [SalesReturnController::class, "store"])->name('sales_return.store');
    Route::get('wms/operations/sales-return/edit/{id}', [SalesReturnController::class, "edit"])->name('sales_return.edit');
    Route::get('wms/operations/sales-return/view/{id}', [SalesReturnController::class, "view"])->name('sales_return.view');
    Route::post('wms/operations/sales-return/update/{id}', [SalesReturnController::class, "update"])->name('sales_return.update');
    Route::delete('wms/operations/sales-return/destroy/{id}', [SalesReturnController::class, "destroy"])->name('sales_return.destroy');

    // PURCHASE RETURN
    Route::get('wms/operations/purchase-return/index', [PurchaseReturnController::class, 'index'])->name('purchase_return.index');
    Route::get('wms/operations/purchase-return/data', [PurchaseReturnController::class, 'Data'])->name('purchase_return.data');
    Route::get('wms/operations/purchase-return/create', [PurchaseReturnController::class, 'create'])->name('purchase_return.create');
    Route::post('wms/operations/purchase-return/store', [PurchaseReturnController::class, "store"])->name('purchase_return.store');
    Route::get('wms/operations/purchase-return/edit/{id}', [PurchaseReturnController::class, "edit"])->name('purchase_return.edit');
    Route::get('wms/operations/purchase-return/preview/{id}', [PurchaseReturnController::class, "preview"])->name('purchase_return.preview');
    Route::get('wms/operations/purchase-return/view/{id}', [PurchaseReturnController::class, "view"])->name('purchase_return.view');
    Route::post('wms/operations/purchase-return/update/{id}', [PurchaseReturnController::class, "update"])->name('purchase_return.update');
    Route::delete('wms/operations/purchase-return/destroy/{id}', [PurchaseReturnController::class, "destroy"])->name('purchase_return.destroy');
    Route::get('wms/operations/purchase-return/qc/{id}', [PurchaseReturnController::class, "qc"])->name('purchase_return.qc');
    Route::post('wms/operations/purchase-return/qc_update/{id}', [PurchaseReturnController::class, "qc_update"])->name('purchase_return.qc_update');
    Route::get('wms/operations/purchase-return/receive/{id}', [PurchaseReturnController::class, "receive"])->name('purchase_return.receive');
    Route::post('wms/operations/purchase-return/receive_update/{id}', [PurchaseReturnController::class, "receive_update"])->name('purchase_return.receive_update');
    Route::get('wms/operations/purchase-return/available-qty', [PurchaseReturnController::class, 'available_qty'])->name('get.available_qty');

    //END WMS OPERATIONS ROUTES

    //WMS REPORT ROUTES

    // INVENTORY REPORT
    Route::get('/wms/report/invertory-report', [InventoryReportController::class, 'index'])->name('inventory_report');
    Route::get('/wms/report/invertory-report/generate', [InventoryReportController::class, 'generate'])->name('inventory_report.generate');

    // STOCK CARD REPORT
    Route::get('/wms/report/stock-card-report', [StockCardReportController::class, 'index'])->name('stock_card_report');
    Route::get('/wms/report/stock-card-report/generate', [StockCardReportController::class, 'generate'])->name('stock_card_report.generate');

    // SUMMARY DO REPORT
    Route::get('/wms/report/summary-do-report', [SummaryDoReportController::class, 'index'])->name('summary_do_report');
    Route::get('/wms/report/summary-do-report/generate', [SummaryDoReportController::class, 'generate'])->name('summary_do_report.generate');

    //END WMS REPORT ROUTES

    //SETTINGS ADMINISTRATION ROUTES

    // User
    Route::get('settings/administration/user/index', [UserController::class, "index"])->name('user.index');
    Route::get('settings/administration/user/Data', [UserController::class, 'Data'])->name('user.data');
    Route::get('settings/administration/user/create', [UserController::class, "create"])->name('user.create');
    Route::post('settings/administration/user/store', [UserController::class, "store"])->name('user.store');
    Route::get('settings/administration/user/edit/{id}', [UserController::class, "edit"])->name('user.edit');
    Route::get('settings/administration/user/view/{id}', [UserController::class, "view"])->name('user.view');
    Route::post('settings/administration/user/update/{id}', [UserController::class, "update"])->name('user.update');
    Route::delete('settings/administration/user/destroy/{id}', [UserController::class, "destroy"])->name('user.destroy');

    // Role
    Route::get('settings/administration/role/index', [RoleController::class, "index"])->name('role.index');
    Route::get('setting/administration/role/data', [RoleController::class,'Data'])->name('role.data');

    Route::get('settings/administration/role/create', [RoleController::class, "create"])->name('role.create');
    Route::post('settings/administration/role/store', [RoleController::class, "store"])->name('role.store');
    Route::get('settings/administration/role/edit/{id}', [RoleController::class, "edit"])->name('role.edit');
    Route::get('settings/administration/role/view/{id}', [RoleController::class, "view"])->name('role.view');
    Route::post('settings/administration/role/update/{id}', [RoleController::class, "update"])->name('role.update');
    Route::delete('settings/administration/role/destroy/{id}', [RoleController::class, "destroy"])->name('role.destroy');

    // Department
    Route::get('settings/administration/department/index', [DepartmentController::class, "index"])->name('department.index');
    Route::get('setting/administration/Department/Data', [DepartmentController::class, 'Data'])->name('department.data');
    Route::get('settings/administration/department/create', [DepartmentController::class, "create"])->name('department.create');
    Route::post('settings/administration/department/store', [DepartmentController::class, "store"])->name('department.store');
    Route::get('settings/administration/department/edit/{id}', [DepartmentController::class, "edit"])->name('department.edit');
    Route::get('settings/administration/department/view/{id}', [DepartmentController::class, "view"])->name('department.view');
    Route::post('settings/administration/department/update/{id}', [DepartmentController::class, "update"])->name('department.update');
    Route::delete('settings/administration/department/destroy/{id}', [DepartmentController::class, "destroy"])->name('department.destroy');

    // Designation
    Route::get('settings/administration/designation/index', [DesignationController::class, "index"])->name('designation.index');
    Route::get('setting/administration/Designation/Data', [DesignationController::class, 'Data'])->name('designation.data');
    Route::get('settings/administration/designation/create', [DesignationController::class, "create"])->name('designation.create');
    Route::post('settings/administration/designation/store', [DesignationController::class, "store"])->name('designation.store');
    Route::get('settings/administration/designation/edit/{id}', [DesignationController::class, "edit"])->name('designation.edit');
    Route::get('settings/administration/designation/view/{id}', [DesignationController::class, "view"])->name('designation.view');
    Route::post('settings/administration/designation/update/{id}', [DesignationController::class, "update"])->name('designation.update');
    Route::delete('settings/administration/designation/destroy/{id}', [DesignationController::class, "destroy"])->name('designation.destroy');

    //END SETTINGS ADMINISTRATION ROUTES

    //SETTINGS DATABASE ROUTES

    // Product
    Route::get('settings/database/product/index', [ProductController::class, "index"])->name('product.index');
    Route::get('settings/database/product/Data', [ProductController::class, 'Data'])->name('product.data');
    Route::get('settings/database/product/create', [ProductController::class, "create"])->name('product.create');
    Route::post('settings/database/product/store', [ProductController::class, "store"])->name('product.store');
    Route::get('settings/database/product/edit/{id}', [ProductController::class, "edit"])->name('product.edit');
    Route::get('settings/database/product/view/{id}', [ProductController::class, "view"])->name('product.view');
    Route::post('settings/database/product/update/{id}', [ProductController::class, "update"])->name('product.update');
    Route::delete('settings/database/product/destroy/{id}', [ProductController::class, "destroy"])->name('product.destroy');

    // Category
    Route::get('settings/database/category/index', [CategoryController::class, "index"])->name('category.index');
    Route::get('settings/database/category/Data', [CategoryController::class, 'Data'])->name('category.data');
    Route::get('settings/database/category/create', [CategoryController::class, "create"])->name('category.create');
    Route::post('settings/database/category/store', [CategoryController::class, "store"])->name('category.store');
    Route::get('settings/database/category/edit/{id}', [CategoryController::class, "edit"])->name('category.edit');
    Route::get('settings/database/category/view/{id}', [CategoryController::class, "view"])->name('category.view');
    Route::post('settings/database/category/update/{id}', [CategoryController::class, "update"])->name('category.update');
    Route::delete('settings/database/category/destroy/{id}', [CategoryController::class, "destroy"])->name('category.destroy');

    // Amortization
    Route::get('settings/database/product/amortization/edit/{id}', [AmortizationController::class, "edit"])->name('product.amortization.edit');
    Route::post('settings/database/product/amortization/update/{id}', [AmortizationController::class, "update"])->name('product.amortization.update');

    // Supplier
    Route::get('settings/database/supplier/index', [SupplierController::class, "index"])->name('supplier.index');
    Route::get('settings/database/supplier/Data', [SupplierController::class, 'Data'])->name('supplier.data');
    Route::get('settings/database/supplier/create', [SupplierController::class, "create"])->name('supplier.create');
    Route::post('settings/database/supplier/store', [SupplierController::class, "store"])->name('supplier.store');
    Route::get('settings/database/supplier/edit/{id}', [SupplierController::class, "edit"])->name('supplier.edit');
    Route::get('settings/database/supplier/view/{id}', [SupplierController::class, "view"])->name('supplier.view');
    Route::post('settings/database/supplier/update/{id}', [SupplierController::class, "update"])->name('supplier.update');
    Route::delete('settings/database/supplier/destroy/{id}', [SupplierController::class, "destroy"])->name('supplier.destroy');

    // Customer
    Route::get('settings/database/customer/index', [CustomerController::class, "index"])->name('customer.index');
    Route::get('settings/database/customer/Data', [CustomerController::class, 'Data'])->name('customer.data');
    Route::get('settings/database/customer/create', [CustomerController::class, "create"])->name('customer.create');
    Route::post('settings/database/customer/store', [CustomerController::class, "store"])->name('customer.store');
    Route::get('settings/database/customer/edit/{id}', [CustomerController::class, "edit"])->name('customer.edit');
    Route::get('settings/database/customer/view/{id}', [CustomerController::class, "view"])->name('customer.view');
    Route::post('settings/database/customer/update/{id}', [CustomerController::class, "update"])->name('customer.update');
    Route::delete('settings/database/customer/destroy/{id}', [CustomerController::class, "destroy"])->name('customer.destroy');

    // Process
    Route::get('settings/database/process/index', [ProcessController::class, "index"])->name('process.index');
    Route::get('settings/database/process/Data', [ProcessController::class, 'Data'])->name('process.data');
    Route::get('settings/database/process/create', [ProcessController::class, "create"])->name('process.create');
    Route::post('settings/database/process/store', [ProcessController::class, "store"])->name('process.store');
    Route::get('settings/database/process/edit/{id}', [ProcessController::class, "edit"])->name('process.edit');
    Route::get('settings/database/process/view/{id}', [ProcessController::class, "view"])->name('process.view');
    Route::post('settings/database/process/update/{id}', [ProcessController::class, "update"])->name('process.update');
    Route::delete('settings/database/process/destroy/{id}', [ProcessController::class, "destroy"])->name('process.destroy');

    // Unit
    Route::get('settings/database/unit/index', [UnitController::class, "index"])->name('unit.index');
    Route::get('settings/database/unit/Data', [UnitController::class, 'Data'])->name('unit.data');
    Route::get('settings/database/unit/create', [UnitController::class, "create"])->name('unit.create');
    Route::post('settings/database/unit/store', [UnitController::class, "store"])->name('unit.store');
    Route::get('settings/database/unit/edit/{id}', [UnitController::class, "edit"])->name('unit.edit');
    Route::get('settings/database/unit/view/{id}', [UnitController::class, "view"])->name('unit.view');
    Route::post('settings/database/unit/update/{id}', [UnitController::class, "update"])->name('unit.update');
    Route::delete('settings/database/unit/destroy/{id}', [UnitController::class, "destroy"])->name('unit.destroy');

    // Area Level
    Route::get('settings/database/area-level/index', [AreaLevelController::class, "index"])->name('area_level.index');
    Route::get('settings/database/area-level/Data', [AreaLevelController::class, 'Data'])->name('area_level.data');
    Route::get('settings/database/area-level/create', [AreaLevelController::class, "create"])->name('area_level.create');
    Route::post('settings/database/area-level/store', [AreaLevelController::class, "store"])->name('area_level.store');
    Route::get('settings/database/area-level/edit/{id}', [AreaLevelController::class, "edit"])->name('area_level.edit');
    Route::get('settings/database/area-level/view/{id}', [AreaLevelController::class, "view"])->name('area_level.view');
    Route::post('settings/database/area-level/update/{id}', [AreaLevelController::class, "update"])->name('area_level.update');
    Route::delete('settings/database/area-level/destroy/{id}', [AreaLevelController::class, "destroy"])->name('area_level.destroy');

    // Area Rack
    Route::get('settings/database/area-rack/index', [AreaRackController::class, "index"])->name('area_rack.index');
    Route::get('settings/database/area-rack/Data', [AreaRackController::class, 'Data'])->name('area_rack.data');
    Route::get('settings/database/area-rack/create', [AreaRackController::class, "create"])->name('area_rack.create');
    Route::post('settings/database/area-rack/store', [AreaRackController::class, "store"])->name('area_rack.store');
    Route::get('settings/database/area-rack/edit/{id}', [AreaRackController::class, "edit"])->name('area_rack.edit');
    Route::get('settings/database/area-rack/view/{id}', [AreaRackController::class, "view"])->name('area_rack.view');
    Route::post('settings/database/area-rack/update/{id}', [AreaRackController::class, "update"])->name('area_rack.update');
    Route::delete('settings/database/area-rack/destroy/{id}', [AreaRackController::class, "destroy"])->name('area_rack.destroy');

    // Area
    Route::get('settings/database/area/index', [AreaController::class, "index"])->name('area.index');
    Route::get('settings/database/area/Data', [AreaController::class, 'Data'])->name('area.data');
    Route::get('settings/database/area/create', [AreaController::class, "create"])->name('area.create');
    Route::post('settings/database/area/store', [AreaController::class, "store"])->name('area.store');
    Route::get('settings/database/area/edit/{id}', [AreaController::class, "edit"])->name('area.edit');
    Route::get('settings/database/area/view/{id}', [AreaController::class, "view"])->name('area.view');
    Route::post('settings/database/area/update/{id}', [AreaController::class, "update"])->name('area.update');
    Route::delete('settings/database/area/destroy/{id}', [AreaController::class, "destroy"])->name('area.destroy');

    // Machine
    Route::get('settings/database/machine/index', [MachineController::class, "index"])->name('machine.index');
    Route::get('settings/database/machine/Data', [MachineController::class, 'Data'])->name('machine.data');
    Route::get('settings/database/machine/create', [MachineController::class, "create"])->name('machine.create');
    Route::post('settings/database/machine/store', [MachineController::class, "store"])->name('machine.store');
    Route::get('settings/database/machine/edit/{id}', [MachineController::class, "edit"])->name('machine.edit');
    Route::get('settings/database/machine/view/{id}', [MachineController::class, "view"])->name('machine.view');
    Route::post('settings/database/machine/update/{id}', [MachineController::class, "update"])->name('machine.update');
    Route::delete('settings/database/machine/destroy/{id}', [MachineController::class, "destroy"])->name('machine.destroy');

    // MachineTonnage
    Route::get('settings/database/machine-tonage/index', [MachineTonnageController::class, "index"])->name('machine_tonage.index');
    Route::get('settings/database/machine-tonage/Data', [MachineTonnageController::class, 'Data'])->name('machine_tonage.data');
    Route::get('settings/database/machine-tonage/create', [MachineTonnageController::class, "create"])->name('machine_tonage.create');
    Route::post('settings/database/machine-tonage/store', [MachineTonnageController::class, "store"])->name('machine_tonage.store');
    Route::get('settings/database/machine-tonage/edit/{id}', [MachineTonnageController::class, "edit"])->name('machine_tonage.edit');
    Route::get('settings/database/machine-tonage/view/{id}', [MachineTonnageController::class, "view"])->name('machine_tonage.view');
    Route::post('settings/database/machine-tonage/update/{id}', [MachineTonnageController::class, "update"])->name('machine_tonage.update');
    Route::delete('settings/database/machine-tonage/destroy/{id}', [MachineTonnageController::class, "destroy"])->name('machine_tonage.destroy');

    // Type Of Product
    Route::get('settings/database/type-of-product/index', [TypeOfProductController::class, "index"])->name('type_of_product.index');
    Route::get('settings/database/type-of-product/Data', [TypeOfProductController::class, 'Data'])->name('type_of_product.data');
    Route::get('settings/database/type-of-product/create', [TypeOfProductController::class, "create"])->name('type_of_product.create');
    Route::post('settings/database/type-of-product/store', [TypeOfProductController::class, "store"])->name('type_of_product.store');
    Route::get('settings/database/type-of-product/edit/{id}', [TypeOfProductController::class, "edit"])->name('type_of_product.edit');
    Route::get('settings/database/type-of-product/view/{id}', [TypeOfProductController::class, "view"])->name('type_of_product.view');
    Route::post('settings/database/type-of-product/update/{id}', [TypeOfProductController::class, "update"])->name('type_of_product.update');
    Route::delete('settings/database/type-of-product/destroy/{id}', [TypeOfProductController::class, "destroy"])->name('type_of_product.destroy');

    // Type Of Reject
    Route::get('settings/database/type-of-rejection/index', [TypeOfRejectionController::class, "index"])->name('type_of_rejection.index');
    Route::get('settings/database/type-of-rejection/Data', [TypeOfRejectionController::class, 'Data'])->name('type_of_rejection.data');
    Route::get('settings/database/type-of-rejection/create', [TypeOfRejectionController::class, "create"])->name('type_of_rejection.create');
    Route::post('settings/database/type-of-rejection/store', [TypeOfRejectionController::class, "store"])->name('type_of_rejection.store');
    Route::get('settings/database/type-of-rejection/edit/{id}', [TypeOfRejectionController::class, "edit"])->name('type_of_rejection.edit');
    Route::get('settings/database/type-of-rejection/view/{id}', [TypeOfRejectionController::class, "view"])->name('type_of_rejection.view');
    Route::post('settings/database/type-of-rejection/update/{id}', [TypeOfRejectionController::class, "update"])->name('type_of_rejection.update');
    Route::delete('settings/database/type-of-rejection/destroy/{id}', [TypeOfRejectionController::class, "destroy"])->name('type_of_rejection.destroy');

    //END SETTINGS DATABASE ROUTES

    //SETTINGS GENERAL SETTINGS ROUTES

    // General Setting
    Route::get('settings/general-setting/index/{active}', [GeneralSettingController::class, "index"])->name('general_setting.index');
    Route::post('settings/general-setting/updates', [GeneralSettingController::class, "updateSST"])->name('general_setting.updateSST');
    Route::post('settings/general-setting/updatep', [GeneralSettingController::class, "updatePO"])->name('general_setting.updatePO');
    Route::post('settings/general-setting/updateb', [GeneralSettingController::class, "updateSB"])->name('general_setting.updateSB');
    Route::post('settings/general-setting/updaterefno', [GeneralSettingController::class, "updateRefNo"])->name('general_setting.updateRefNo');
    Route::post('settings/general-setting/updatepr', [GeneralSettingController::class, "updatePR"])->name('general_setting.updatePR');
    Route::post('settings/general-setting/updateps', [GeneralSettingController::class, "updatePS"])->name('general_setting.updatePS');

    //END SETTINGS GENERAL SETTINGS ROUTES

    //HR ROUTES

    // Leave
    Route::get('hr/leave/index', [LeaveController::class, "index"])->name('leave.index');
    Route::get('hr/leave/data', [LeaveController::class, "Data"])->name('leave.data');
    Route::get('hr/leave/create', [LeaveController::class, "create"])->name('leave.create');
    Route::get('hr/leave/manage/{id}', [LeaveController::class, "manage"])->name('leave.manage');
    Route::post('hr/leave/store', [LeaveController::class, "store"])->name('leave.store');
    Route::post('hr/leave/manage/store{id}', [LeaveController::class, "manageStore"])->name('leave.manage.store');
    Route::post('hr/leave/verify/{id}', [LeaveController::class, "verify"])->name('leave.verify');
    Route::post('hr/leave/decline/{id}', [LeaveController::class, "decline"])->name('leave.decline');
    Route::get('hr/leave/edit/{id}', [LeaveController::class, "edit"])->name('leave.edit');
    Route::get('hr/leave/view/{id}', [LeaveController::class, "view"])->name('leave.view');
    Route::post('hr/leave/update/{id}', [LeaveController::class, "update"])->name('leave.update');
    Route::delete('hr/leave/destroy/{id}', [LeaveController::class, "destroy"])->name('leave.destroy');


    // Payroll routes
    // Route::get('hr/payroll/index', [PayrollController::class, "index"])->name('payroll.index');
    Route::get('hr/payroll/data', [PayrollController::class, "Data"])->name('payroll.index');
    Route::get('hr/payroll/create', [PayrollController::class, "create"])->name('payroll.create');
    Route::get('hr/payroll/store', [PayrollController::class, "generateStore"])->name('payroll.generateStore');
    Route::get('hr/payroll/approve/{id}', [PayrollController::class, "approve"])->name('payroll.approve');
    Route::post('hr/payroll/verify/{id}', [PayrollController::class, "verify"])->name('payroll.verify');
    Route::post('hr/payroll/decline/{id}', [PayrollController::class, "decline"])->name('payroll.decline');
    Route::post('hr/payroll/cancel/{id}', [PayrollController::class, "cancel"])->name('payroll.cancel');
    Route::post('hr/payroll/manage/store{id}', [PayrollController::class, "manageStore"])->name('payroll.manage.store');
    Route::get('hr/payroll/edit/{id}', [PayrollController::class, "edit"])->name('payroll.edit');
    Route::get('hr/payroll/view/{id}', [PayrollController::class, "view"])->name('payroll.view');
    Route::post('hr/payroll/update/{id}', [PayrollController::class, "update"])->name('payroll.update');
    Route::delete('hr/payroll/destroy/{id}', [PayrollController::class, "destroy"])->name('payroll.destroy');

    // PAYROLL DETAILS ROUTE
    Route::get('hr/payroll_detail/data/{id}', [PayrollController::class, "payrollDetailData"])->name('payroll_detail.index');
    Route::get('hr/payroll_detail/dataView/{id}', [PayrollController::class, "payrollDetailDataView"])->name('payroll_detail.payrollDetailDataView');
    Route::get('hr/payroll_detail/create', [PayrollController::class, "create"])->name('payroll_detail.create');
    Route::get('hr/payroll_detail/manage/{id}', [PayrollController::class, "manage"])->name('payroll_detail.manage');
    Route::get('hr/payroll_detail/store', [PayrollController::class, "generateStore"])->name('payroll_detail.generateStore');
    Route::post('hr/payroll_detail/manage/store{id}', [PayrollController::class, "manageStore"])->name('payroll_detail.manage.store');
    Route::get('hr/payroll_detail/edit/{id}', [PayrollController::class, "payrollDetailEdit"])->name('payroll_detail.edit');
    Route::get('hr/payroll_detail/view/{id}', [PayrollController::class, "payrollDetailView"])->name('payroll_detail.view');
    Route::post('hr/payroll_detail/update/{id}', [PayrollController::class, "payrollDetailStore"])->name('payroll_detail.update');
    Route::delete('hr/payroll_detail/destroy/{id}', [PayrollController::class, "payrollDetailDestroy"])->name('payroll_detail.destroy');
    Route::get('hr/payroll_detail/preview/{id}', [PayrollController::class, "preview"])->name('payroll_detail.preview');


    // SUMMARY ATTENDANCE REPROT
    Route::get('hr/summary_attendance/data', [SummaryAttendanceController::class, "index"])->name('summary_attendance.index');
    Route::get('hr/summary_attendance/generate', [SummaryAttendanceController::class, "report"])->name('summary_attendance.generate');


    // End HR ROUTES




    Route::get('hr/attendance/index', [AttendanceController::class, "index"])->name('attendance.index');
    Route::get('hr/attendance/data', [AttendanceController::class, "Data"])->name('attendance.data');
    // Route::get('hr/attendance/create', [AttendanceController::class, "create"])->name('attendance.create');
    // Route::get('hr/attendance/manage/{id}', [AttendanceController::class, "manage"])->name('attendance.manage');
    Route::post('hr/attendance/import', [AttendanceController::class, "attendanceExcelImport"])->name('attendance.import');
    Route::get('hr/attendance/export', [AttendanceController::class, "attendanceExcelExport"])->name('attendance.export');
    Route::post('hr/attendance/saveremarks', [AttendanceController::class, "saveRemark"])->name('attendance.saveremarks');
    // Route::post('hr/attendance/manage/store{id}', [AttendanceController::class, "manageStore"])->name('attendance.manage.store');
    // Route::get('hr/attendance/edit/{id}', [AttendanceController::class, "edit"])->name('attendance.edit');
    // Route::get('hr/attendance/view/{id}', [AttendanceController::class, "view"])->name('attendance.view');
    // Route::post('hr/attendance/update/{id}', [AttendanceController::class, "update"])->name('attendance.update');
    // Route::get('hr/attendance/destroy/{id}', [AttendanceController::class, "destroy"])->name('attendance.destroy');

    Route::get('accounting/account-dashboard', [AccountDashboardController::class, 'index'])->name('account-home');
    Route::get('accounting/account_categories/index', [AccountCategoryController::class, "index"])->name('account_categories.index');
    Route::get('accounting/account_categories/Data', [AccountCategoryController::class, 'Data'])->name('account_categories.data');
    Route::get('accounting/account_categories/create', [AccountCategoryController::class, "create"])->name('account_categories.create');
    Route::post('accounting/account_categories/store', [AccountCategoryController::class, "store"])->name('account_categories.store');
    Route::get('accounting/account_categories/edit/{id}', [AccountCategoryController::class, "edit"])->name('account_categories.edit');
    Route::get('accounting/account_categories/view/{id}', [AccountCategoryController::class, "view"])->name('account_categories.view');
    Route::post('accounting/account_categories/update/{id}', [AccountCategoryController::class, "update"])->name('account_categories.update');
    Route::delete('accounting/account_categories/destroy/{id}', [AccountCategoryController::class, "destroy"])->name('account_categories.destroy');

    Route::prefix('accounting')->group(function () {
        Route::resource('accounts', AccountController::class);
        Route::get('export-excel', [AccountController::class, 'exportExcel'])->name('accounts.export-excel');
        Route::get('export-pdf', [AccountController::class, 'exportPDF'])->name('accounts.export-pdf');
    });
    Route::get('account_reports/accounts/{account}/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('account_reports/accounts/{account}/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('account_reports/ledger', [LedgerController::class, 'index'])->name('ledger.index');
    Route::get('account_reports/ledger/export/{format}', [LedgerController::class, 'export'])->name('ledger.export');
    Route::get('account_reports/ledger/{accountId}', [LedgerController::class, 'ledgerAccount'])->name('ledger.ledgerAccount');
    Route::get('account_reports/ledger/exportSingleLedger/{accountId}/{format}', [LedgerController::class, 'exportSingleLedger'])->name('ledger.export-single');
    Route::get('account_reports/reports/trial_balance', [TrialBalanceController::class, 'index'])->name('reports.trial_balance');
    Route::get('account_reports/reports/trial-balance/export', [TrialBalanceController::class, 'export'])->name('trial_balance.export');
    Route::get('account_reports/reports/trial-balance/pdf', [TrialBalanceController::class, 'downloadPdf'])->name('trial_balance.pdf');
    Route::get('account_reports/reports/profit_loss', [ProfitLossController::class, 'index'])->name('reports.profit_loss');
    Route::get('account_reports/reports/profit-loss/export/{format}', [ProfitLossController::class, 'export'])->name('reports.profit_loss.export');
    Route::get('account_reports/reports/balance_sheet', [BalanceSheetController::class, 'index'])->name('reports.balance_sheet');
    Route::get('account_reports/reports/balance_sheet/export/{format}', [BalanceSheetController::class, 'export'])->name('reports.balance_sheet.export');
    Route::get('account_reports/reconciliation/{account}', [ReconciliationController::class, 'index'])->name('reconciliation.index');
    Route::post('account_reports/reconciliation/{account}/external-statements', [ReconciliationController::class, 'storeExternalStatement'])->name('reconciliation.storeExternalStatement');
    Route::post('account_reports/reconciliation/{account}', [ReconciliationController::class, 'reconcile'])->name('reconciliation.reconcile');
    Route::get('account_reports/carryforward', [CarryForwardController::class, 'index'])->name('carryforward.index');
    Route::get('account_reports/calculate-carryforward/{year}', [CarryForwardController::class, 'calculate'])->name('carryforward.calculate');
    Route::post('account_reports/save-carryforward', [CarryForwardController::class, 'store'])->name('carryforward.store');
    Route::get('account_reports/again_due_report', [AgingReportController::class, 'index'])->name('aging_report.index');
    Route::get('account_reports/again_due_report/Data', [AgingReportController::class, 'Data'])->name('aging_report.data');
    Route::prefix('payments')->group(function () {
        Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/history/{purchaseOrderId}', [PaymentController::class, 'getPayments'])->name('payments.history');
        Route::post('/storepaymentinvoice', [PaymentController::class, 'storeInvoicePayment'])->name('payments.storeInvoice');
        Route::get('/invoice/{id}', [PaymentController::class, 'showInvoicePayments'])->name('payments.showInvoice');
        Route::get('payments/invoicehistory/{invoiceId}', [PaymentController::class, 'getPaymentsForInvoice'])->name('payments.invoicehistory');
        Route::post('/storepaymentpayroll', [PaymentController::class, 'storePayrollPayment'])->name('payments.storePayroll');
        Route::get('/payroll/{id}', [PaymentController::class, 'showPayrollPayments'])->name('payments.showPayroll');
        Route::get('payments/payrollhistory/{payrollId}', [PaymentController::class, 'getPaymentsForPayroll'])->name('payments.payrollhistory');
    });



});
