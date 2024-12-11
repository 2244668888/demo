$(document).ready(function () {

    var currentURL = window.location.href;

    if (currentURL.includes('erp/bd')) {
        $('.treeview').removeClass('active');
        $('.treeview.bd-bar').addClass('active');
        if (currentURL.includes('quotation')) {
            $('.treeview.bd-bar').find('a').removeClass('active-sub');
            $('.treeview.bd-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('order')) {
            $('.treeview.bd-bar').find('a').removeClass('active-sub');
            $('.treeview.bd-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('sale-price')) {
            $('.treeview.bd-bar').find('a').removeClass('active-sub');
            $('.treeview.bd-bar').find('a:eq(3)').addClass('active-sub');
        } else if (currentURL.includes('invoice')) {
            $('.treeview.bd-bar').find('a').removeClass('active-sub');
            $('.treeview.bd-bar').find('a:eq(4)').addClass('active-sub');
        }
    } else if (currentURL.includes('erp/pvd')) {
        $('.treeview').removeClass('active');
        $('.treeview.pvd-bar').addClass('active');
        if (currentURL.includes('purchase-price')) {
            $('.treeview.pvd-bar').find('a').removeClass('active-sub');
            $('.treeview.pvd-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('purchase-planning')) {
            $('.treeview.pvd-bar').find('a').removeClass('active-sub');
            $('.treeview.pvd-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('purchase-requisition')) {
            $('.treeview.pvd-bar').find('a').removeClass('active-sub');
            $('.treeview.pvd-bar').find('a:eq(3)').addClass('active-sub');
        } else if (currentURL.includes('purchase-order')) {
            $('.treeview.pvd-bar').find('a').removeClass('active-sub');
            $('.treeview.pvd-bar').find('a:eq(4)').addClass('active-sub');
        } else if (currentURL.includes('supplier-ranking')) {
            $('.treeview.pvd-bar').find('a').removeClass('active-sub');
            $('.treeview.pvd-bar').find('a:eq(5)').addClass('active-sub');
        }
    } else if (currentURL.includes('mes/dashboard')) {
        $('.treeview').removeClass('active');
        $('.treeview.dashboard-bar').addClass('active');
        if (currentURL.includes('machine-status')) {
            $('.treeview.dashboard-bar').find('a').removeClass('active-sub');
            $('.treeview.dashboard-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('shopfloor')) {
            $('.treeview.dashboard-bar').find('a').removeClass('active-sub');
            $('.treeview.dashboard-bar').find('a:eq(2)').addClass('active-sub');
        }
    } else if (currentURL.includes('mes/engineering')) {
        $('.treeview').removeClass('active');
        $('.treeview.engineering-bar').addClass('active');
        if (currentURL.includes('bom/')) {
            $('.treeview.engineering-bar').find('a').removeClass('active-sub');
            $('.treeview.engineering-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('bom-report')) {
            $('.treeview.engineering-bar').find('a').removeClass('active-sub');
            $('.treeview.engineering-bar').find('a:eq(2)').addClass('active-sub');
        }
    } else if (currentURL.includes('mes/ppc')) {
        $('.treeview').removeClass('active');
        $('.treeview.ppc-bar').addClass('active');
        if (currentURL.includes('monthly-production-planning')) {
            $('.treeview.ppc-bar').find('a').removeClass('active-sub');
            $('.treeview.ppc-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('daily-production-planning')) {
            $('.treeview.ppc-bar').find('a').removeClass('active-sub');
            $('.treeview.ppc-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('production-scheduling')) {
            $('.treeview.ppc-bar').find('a').removeClass('active-sub');
            $('.treeview.ppc-bar').find('a:eq(3)').addClass('active-sub');
        }
    } else if (currentURL.includes('mes/production')) {
        $('.treeview').removeClass('active');
        $('.treeview.production-bar').addClass('active');
        if (currentURL.includes('production-output-traceability')) {
            $('.treeview.production-bar').find('a').removeClass('active-sub');
            $('.treeview.production-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('summary-report')) {
            $('.treeview.production-bar').find('a').removeClass('active-sub');
            $('.treeview.production-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('call-for-assistance')) {
            $('.treeview.production-bar').find('a').removeClass('active-sub');
            $('.treeview.production-bar').find('a:eq(3)').addClass('active-sub');
        }
    } else if (currentURL.includes('mes/oee')) {
        $('.treeview').removeClass('active');
        $('.treeview.oee-bar').addClass('active');
        if (currentURL.includes('oee-report')) {
            $('.treeview.oee-bar').find('a').removeClass('active-sub');
            $('.treeview.oee-bar').find('a:eq(1)').addClass('active-sub');
        }
    } else if (currentURL.includes('wms/dashboard')) {
        $('.treeview').removeClass('active');
        $('.treeview.wms-dashboard-bar').addClass('active');
        if (currentURL.includes('inventory-dashboard')) {
            $('.treeview.wms-dashboard-bar').find('a').removeClass('active-sub');
            $('.treeview.wms-dashboard-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('inventory-shopfloor')) {
            $('.treeview.wms-dashboard-bar').find('a').removeClass('active-sub');
            $('.treeview.wms-dashboard-bar').find('a:eq(2)').addClass('active-sub');
        }
    } else if (currentURL.includes('wms/operations')) {
        $('.treeview').removeClass('active');
        $('.treeview.operations-bar').addClass('active');
        if (currentURL.includes('delivery-instruction')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('good-receiving')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('material-requisition')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(3)').addClass('active-sub');
        } else if (currentURL.includes('transfer-request')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(4)').addClass('active-sub');
        } else if (currentURL.includes('discrepancy')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(5)').addClass('active-sub');
        } else if (currentURL.includes('stock-adjustment')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(6)').addClass('active-sub');
        } else if (currentURL.includes('stock-relocation')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(7)').addClass('active-sub');
        } else if (currentURL.includes('product-reordering')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(8)').addClass('active-sub');
        } else if (currentURL.includes('outgoing')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(9)').addClass('active-sub');
        } else if (currentURL.includes('sales-return')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(10)').addClass('active-sub');
        } else if (currentURL.includes('purchase-return')) {
            $('.treeview.operations-bar').find('a').removeClass('active-sub');
            $('.treeview.operations-bar').find('a:eq(11)').addClass('active-sub');
        }
    } else if (currentURL.includes('wms/report')) {
        $('.treeview').removeClass('active');
        $('.treeview.report-bar').addClass('active');
        if (currentURL.includes('invertory-report')) {
            $('.treeview.report-bar').find('a').removeClass('active-sub');
            $('.treeview.report-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('stock-card')) {
            $('.treeview.report-bar').find('a').removeClass('active-sub');
            $('.treeview.report-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('summary-do-report')) {
            $('.treeview.report-bar').find('a').removeClass('active-sub');
            $('.treeview.report-bar').find('a:eq(3)').addClass('active-sub');
        }
    } else if (currentURL.includes('settings/administration')) {
        $('.treeview').removeClass('active');
        $('.treeview.administration-bar').addClass('active');
        if (currentURL.includes('user')) {
            $('.treeview.administration-bar').find('a').removeClass('active-sub');
            $('.treeview.administration-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('role')) {
            $('.treeview.administration-bar').find('a').removeClass('active-sub');
            $('.treeview.administration-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('department')) {
            $('.treeview.administration-bar').find('a').removeClass('active-sub');
            $('.treeview.administration-bar').find('a:eq(3)').addClass('active-sub');
        } else if (currentURL.includes('designation')) {
            $('.treeview.administration-bar').find('a').removeClass('active-sub');
            $('.treeview.administration-bar').find('a:eq(4)').addClass('active-sub');
        }
    } else if (currentURL.includes('settings/database')) {
        $('.treeview').removeClass('active');
        $('.treeview.database-bar').addClass('active');
        if (currentURL.includes('/product/')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('/category/')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('/supplier/')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(3)').addClass('active-sub');
        } else if (currentURL.includes('customer')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(4)').addClass('active-sub');
        } else if (currentURL.includes('process')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(5)').addClass('active-sub');
        } else if (currentURL.includes('unit')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(6)').addClass('active-sub');
        } else if (currentURL.includes('area-level')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(7)').addClass('active-sub');
        } else if (currentURL.includes('area-rack')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(8)').addClass('active-sub');
        } else if (currentURL.includes('area/')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(9)').addClass('active-sub');
        } else if (currentURL.includes('machine/')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(10)').addClass('active-sub');
        } else if (currentURL.includes('machine-tonage')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(11)').addClass('active-sub');
        } else if (currentURL.includes('type-of-product')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(12)').addClass('active-sub');
        } else if (currentURL.includes('type-of-rejection')) {
            $('.treeview.database-bar').find('a').removeClass('active-sub');
            $('.treeview.database-bar').find('a:eq(13)').addClass('active-sub');
        }
    } else if (currentURL.includes('settings/general')) {
        $('.treeview').removeClass('active');
        $('.general-bar').addClass('active');
    } else if (currentURL.includes('hr/leave')) {
        $('.treeview').removeClass('active');
        $('.leave-bar').addClass('active');
    } else if (currentURL.includes('hr/attendance')) {
        $('.treeview').removeClass('active');
        $('.attendance-bar').addClass('active');
    } else if (currentURL.includes('accounting/account-dashboard')) {
        $('.treeview').removeClass('active');
        $('.account-dashboard').addClass('active');
    } else if (currentURL.includes('accounting')){
        $('.treeview').removeClass('active');
        $('.treeview.accounts-bar').addClass('active');
        if (currentURL.includes('account_categories')) {
            $('.treeview.accounts-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('accounts-bar')) {
            $('.treeview.accounts-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-bar').find('a:eq(2)').addClass('active-sub');
        }
    } else if (currentURL.includes('accounting')){
        $('.treeview').removeClass('active');
        $('.treeview.accounts-bar').addClass('active');
        if (currentURL.includes('account_categories')) {
            $('.treeview.accounts-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('accounts-bar')) {
            $('.treeview.accounts-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-bar').find('a:eq(2)').addClass('active-sub');
        }
    } else if (currentURL.includes('account_reports')){
        $('.treeview').removeClass('active');
        $('.treeview.accounts-reports-bar').addClass('active');
        if (currentURL.includes('ledger')) {
            $('.treeview.accounts-reports-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-reports-bar').find('a:eq(1)').addClass('active-sub');
        } else if (currentURL.includes('account_reports/reports/trial_balance')) {
            $('.treeview.accounts-reports-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-reports-bar').find('a:eq(2)').addClass('active-sub');
        } else if (currentURL.includes('account_reports/reports/profit_loss')) {
            $('.treeview.accounts-reports-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-reports-bar').find('a:eq(3)').addClass('active-sub');
        }  else if (currentURL.includes('account_reports/reports/balance_sheet')) {
            $('.treeview.accounts-reports-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-reports-bar').find('a:eq(4)').addClass('active-sub');
        }  else if (currentURL.includes('account_reports/reports/carryforward')) {
            $('.treeview.accounts-reports-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-reports-bar').find('a:eq(5)').addClass('active-sub');
        } else if (currentURL.includes('account_reports/again_due_report')) {
            $('.treeview.accounts-reports-bar').find('a').removeClass('active-sub');
            $('.treeview.accounts-reports-bar').find('a:eq(6)').addClass('active-sub');
        }
    }

});
