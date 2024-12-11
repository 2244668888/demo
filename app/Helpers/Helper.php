<?php

namespace App\Helpers;

class Helper
{
    public static function getpermissions($permissions)
    {

        $bd = [
            'Quotation' => [
                'Quotation List',
                'Quotation Create',
                'Quotation Edit',
                'Quotation Verify',
                'Quotation View',
                'Quotation Delete',
            ],
            'Order' => [
                'Order List',
                'Order Create',
                'Order Edit',
                'Order View',
                'Order Delete',
            ],
            'Sales Price' => [
                'Sales Price List',
                'Sales Price Create',
                'Sales Price Edit',
                'Sales Price Verify',
                'Sales Price View',
                'Sales Price Delete',
            ],
            'Invoice' => [
                'Invoice List',
                'Invoice Create',
                'Invoice Edit',
                'Invoice View',
                'Invoice Preview',
                'Invoice Delete',
            ],
        ];

        $pvd = [
            'Purchase Price' => [
                'Purchase Price List',
                'Purchase Price Create',
                'Purchase Price Edit',
                'Purchase Price Verify',
                'Purchase Price View',
                'Purchase Price Delete',
            ],
            'Purchase Planning' => [
                'Purchase Planning List',
                'Purchase Planning Create',
                'Purchase Planning Edit',
                'Purchase Planning View',
                'Purchase Planning Delete',
                'Purchase Planning Verify HOD',
                'Purchase Planning Verify ACC',
                'Purchase Planning Approve',
                'Purchase Planning Check',
                'Purchase Planning Decline',
                'Purchase Planning Cancel',
            ],
            'Purchase Requisition' => [
                'Purchase Requisition List',
                'Purchase Requisition Create',
                'Purchase Requisition Edit',
                'Purchase Requisition View',
                'Purchase Requisition Delete',
                'Purchase Requisition Verify HOD',
                'Purchase Requisition Verify ACC',
                'Purchase Requisition Approve',
                'Purchase Requisition Decline',
                'Purchase Requisition Cancel',
            ],
            'Purchase Order' => [
                'Purchase Order List',
                'Purchase Order Create',
                'Purchase Order Edit',
                'Purchase Order View',
                'Purchase Order Preview',
                'Purchase Order Delete',
                'Purchase Order Verify',
                'Purchase Order Check',
                'Purchase Order Decline',
                'Purchase Order Cancel',
            ],
            'Supplier Ranking' => [
                'Supplier Ranking List',
                'Supplier Ranking Create',
                'Supplier Ranking Edit',
                'Supplier Ranking View',
                'Supplier Ranking Delete',
            ],
        ];

        $dashboard = [
            'Machine Status' => [
                'Machine Status View'
            ],
            'Shopfloor' => [
                'Shopfloor View'
            ]
        ];

        $engineering = [
            'BOM'=>[
                'BOM List',
                'BOM Create',
                'BOM Edit',
                'BOM View',
                'BOM Delete',
                'BOM Verification',
                'BOM Verify',
                'BOM Decline',
                'BOM Cancel',
                'BOM Inactive'
            ],
            'BOM Report'=>[
                'BOM Report'
            ]
        ];

        $ppc = [
            'Monthly Production Planning' => [
                'Monthly Production Planning'
            ],
            'Daily Production Planning' => [
                'Daily Production Planning Create',
                'Daily Production Planning Edit',
                'Daily Production Planning Delete',
                'Daily Production Planning View',
                'Daily Production Planning List',
            ],
            'Production Scheduling' => [
                'Production Scheduling View'
            ]
        ];

        $production = [
            'Production Output Traceability' => [
                'Production Output Traceability List',
                'Production Output Traceability Edit',
                'Production Output Traceability QC',
                'Production Output Traceability View',
            ],
            'Summary Report' => [
                'Summary Report View'
            ],
            'Call For Assistance' => [
                'Call For Assistance List',
                'Call For Assistance Update',
                'Call For Assistance View',
            ]
        ];

        $oee = [
            'OEE Report' => [
                'OEE Report View'
            ]
        ];

        $wms_dashboard = [
            'Inventory Shopfloor' => [
                'Inventory Shopfloor View'
            ],
            'Inventory Dashboard' => [
                'Inventory Dashboard View'
            ]
        ];

        $operation = [
            'Good Receiving' => [
                'Good Receiving List',
                'Good Receiving Create',
                'Good Receiving Edit',
                'Good Receiving Receive',
                'Good Receiving QC',
                'Good Receiving Approve',
                'Good Receiving Allocation',
                'Good Receiving View',
                'Good Receiving Delete',
            ],
            'Delivery Instruction' => [
                'Delivery Instruction List',
                'Delivery Instruction Create',
                'Delivery Instruction Edit',
                'Delivery Instruction View',
                'Delivery Instruction Delete',
            ],
            'Material Requisition' => [
                'Material Requisition List',
                'Material Requisition Create',
                'Material Requisition Edit',
                'Material Requisition View',
                'Material Requisition Delete',
                'Material Requisition Issue',
                'Material Requisition Receive',
            ],
            'Transfer Request' => [
                'Transfer Request List',
                'Transfer Request Create',
                'Transfer Request Edit',
                'Transfer Request View',
                'Transfer Request Delete',
                'Transfer Request Issue',
                'Transfer Request Receive',
                'Transfer Request QC',
            ],
            'Discrepancy' => [
                'Discrepancy List',
                'Discrepancy Edit',
                'Discrepancy View'
            ],
            'Stock Adjustment' => [
                'Stock Adjustment List',
                'Stock Adjustment Update',
            ],
            'Stock Relocation' => [
                'Stock Relocation List',
                'Stock Relocation Create',
                'Stock Relocation Edit',
                'Stock Relocation View',
                'Stock Relocation Delete',
            ],
            'Product Reordering' => [
                'Product Reordering List',
                'Product Reordering Create',
                'Product Reordering Edit',
                'Product Reordering View',
                'Product Reordering Delete',
            ],
            'Outgoing' => [
                'Outgoing List',
                'Outgoing Create',
                'Outgoing Edit',
                'Outgoing View',
                'Outgoing Preview',
                'Outgoing Delete',
            ],
            'Sales Return' => [
                'Sales Return List',
                'Sales Return Create',
                'Sales Return Edit',
                'Sales Return View',
                'Sales Return Delete',
            ],
            'Purchase Return' => [
                'Purchase Return List',
                'Purchase Return Create',
                'Purchase Return Edit',
                'Purchase Return View',
                'Purchase Return Preview',
                'Purchase Return QC',
                'Purchase Return Receive',
                'Purchase Return Delete',
            ]
        ];

        $report = [
            'Inventory Report' => [
                'Inventory Report View'
            ],
            'Stock Card Report' => [
                'Stock Card Report View'
            ],
            'Summary DO Report' => [
                'Summary DO Report View'
            ]
        ];

        $hr = [
            'Leave' => [
                'Leave List',
                'Leave Create',
                'Leave Edit',
                'Leave View',
                'Leave Manage',
                'Leave Delete',
            ],
            'Attendance' => [
                'Attendance List',
                'Attendance Import',
                'Attendance Export',
            ],

            'Summary Attendance' => [
                'Summary Attendence List',
            ],

            'Payroll' => [
                'Payroll List',
                'Payroll Create',
            ],
        ];

        $administration = [
            'User' => [
                'User List',
                'User Create',
                'User Edit',
                'User View',
                'User Delete',
            ],
            'Role' => [
                'Role List',
                'Role Create',
                'Role Edit',
                'Role View',
                'Role Delete',
            ],
            'Department' => [
                'Department List',
                'Department Create',
                'Department Edit',
                'Department View',
                'Department Delete',
            ],
            'Designation' => [
                'Designation List',
                'Designation Create',
                'Designation Edit',
                'Designation View',
                'Designation Delete',
            ]
        ];

        $database = [
            'Product' => [
                'Product List',
                'Product Create',
                'Product Edit',
                'Product View',
                'Product Delete',
                'Product Amortization Edit',
            ],
            'Category' => [
                'Category List',
                'Category Create',
                'Category Edit',
                'Category View',
                'Category Delete',
            ],
            'Supplier' => [
                'Supplier List',
                'Supplier Create',
                'Supplier Edit',
                'Supplier View',
                'Supplier Delete',
            ],
            'Customer' => [
                'Customer List',
                'Customer Create',
                'Customer Edit',
                'Customer View',
                'Customer Delete',
            ],
            'Process' => [
                'Process List',
                'Process Create',
                'Process Edit',
                'Process View',
                'Process Delete',
            ],
            'Unit' => [
                'Unit List',
                'Unit Create',
                'Unit Edit',
                'Unit View',
                'Unit Delete',
            ],
            'Area Level' => [
                'Area Level List',
                'Area Level Create',
                'Area Level Edit',
                'Area Level View',
                'Area Level Delete',
            ],
            'Area Rack' => [
                'Area Rack List',
                'Area Rack Create',
                'Area Rack Edit',
                'Area Rack View',
                'Area Rack Delete',
            ],
            'Area' => [
                'Area List',
                'Area Create',
                'Area Edit',
                'Area View',
                'Area Delete',
            ],
            'Machine' => [
                'Machine List',
                'Machine Create',
                'Machine Edit',
                'Machine View',
                'Machine Delete',
            ],
            'Machine Tonnage' => [
                'Machine Tonnage List',
                'Machine Tonnage Create',
                'Machine Tonnage Edit',
                'Machine Tonnage View',
                'Machine Tonnage Delete',
            ],
            'Type Of Product' => [
                'Type Of Product List',
                'Type Of Product Create',
                'Type Of Product Edit',
                'Type Of Product View',
                'Type Of Product Delete',
            ],
            'Type Of Rejection' => [
                'Type Of Rejection List',
                'Type Of Rejection Create',
                'Type Of Rejection Edit',
                'Type Of Rejection View',
                'Type Of Rejection Delete',
            ]
        ];

        $general_setting = [
            'General Settings SST Percentage',
            'General Settings PO Important Note',
            'General Settings Spec Break',
            'General Settings Initial Ref No',
            'General Settings PR Approval',
            'General Settings Payroll Setup'
        ];

        $accounting = [
            'Accounts Dashboard' => [
                'Account Dashboard',
            ],
            'Account Category' => [
                'Account Category List',
                'Account Category Create',
                'Account Category Edit',
                'Account Category View',
                'Account Category Delete',
            ],

            'Account Details' => [
                'Account List',
                'Account Create',
            ],

            'Account Ledger' => [
                'Account Ledger List',
            ],

            'Account Transaction' => [
                'Account Transaction Create',
            ],

            'Account Reconcile' => [
                'Account Reconcile List',
            ],

            'Ledger Summary' => [
                'Ledger List',
            ],

            'Trial Balance' => [
                'Trial Balance List',
            ],

            'Carry Forward' => [
                'Carryforward List',
            ],

            'Profit and Loss' => [
                'Profit Loss List',
            ],

            'Balance Sheet' => [
                'Balance Sheet List',
            ],

            'Aging Report' => [
                'Aging Report List',
            ],


        ];

        if ($permissions == 'bd') {
            return $bd;
        } else if ($permissions == 'pvd') {
            return $pvd;
        } else if ($permissions == 'dashboard') {
            return $dashboard;
        } else if ($permissions == 'engineering') {
            return $engineering;
        } else if ($permissions == 'ppc') {
            return $ppc;
        } else if ($permissions == 'production') {
            return $production;
        } else if ($permissions == 'oee') {
            return $oee;
        } else if ($permissions == 'wms_dashboard') {
            return $wms_dashboard;
        } else if ($permissions == 'operation') {
            return $operation;
        } else if ($permissions == 'report') {
            return $report;
        } else if ($permissions == 'hr') {
            return $hr;
        } else if ($permissions == 'administration') {
            return $administration;
        } else if ($permissions == 'database') {
            return $database;
        } else if ($permissions == 'general_setting') {
            return $general_setting;
        } else if ($permissions == 'accounting') {
            return $accounting;
        }
        

    }

}
