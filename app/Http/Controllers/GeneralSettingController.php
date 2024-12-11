<?php

namespace App\Http\Controllers;
use App\Models\SstPercentage;
use App\Models\PoImportantNote;
use App\Models\SpecBreak;
use App\Models\InitailNo;
use App\Models\PrApproval;
use App\Models\Designation;
use App\Models\PayrollSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralSettingController extends Controller
{
    public function index($active){
        if (
            Auth::user()->hasPermissionTo('General Settings SST Percentage') ||
            Auth::user()->hasPermissionTo('General Settings PO Important Note') ||
            Auth::user()->hasPermissionTo('General Settings Spec Break') ||
            Auth::user()->hasPermissionTo('General Settings Initial Ref No') ||
            Auth::user()->hasPermissionTo('General Settings PR Approval') ||
            Auth::user()->hasPermissionTo('General Settings Payroll Setup')
        ) {

            $sstPercentages = SstPercentage::first();
            $poimportantnotes = PoImportantNote::first();
            $specbreaks = SpecBreak::first();
            $payroll_setup = PayrollSetup::first();
            $initialRefNo = InitailNo::all();
            $prapprovals = PrApproval::all();
            $designations = Designation::all();
            return view('general_settings.index', compact('payroll_setup','sstPercentages', 'poimportantnotes', 'specbreaks', 'initialRefNo', 'prapprovals', 'designations', 'active'));

        }

        return back()->with('custom_errors', 'You don`t have the right permission');
    }

    public function updateSST(Request $request){
        $sstPercentage = SstPercentage::find(1);
        $sstPercentage->sst_percentage = $request->sst_percentage ?? $sstPercentage->sst_percentage;
        $sstPercentage->save();
        return redirect()->route('general_setting.index', 1)->with('custom_success', 'SST Percentage(%) Updated Successfully.');
    }
    public function updatePO(Request $request){
        $poimportantnotes = PoImportantNote::find(1);
        $poimportantnotes->po_note = $request->po_note ?? $poimportantnotes->po_note;
        $poimportantnotes->save();
        return redirect()->route('general_setting.index', 2)->with('custom_success', 'PO Important Note Updated Successfully.');
    }

    public function updateSB(Request $request){
        $specbreaks = SpecBreak::find(1);
        $specbreaks->normal_hour = $request->normal_hour ?? $specbreaks->normal_hour;
        $specbreaks->ot_hour = $request->ot_hour ?? $specbreaks->ot_hour;
        $specbreaks->save();
        return redirect()->route('general_setting.index', 3)->with('custom_success', 'Spec Break  Updated Successfully.');
    }

    public function updateRefNo(Request $request)
    {
        InitailNo::truncate();
        if($request->initial){
            foreach($request->initial as $value){
                $prapproval = new InitailNo();
                $prapproval->screen = $value['screen'];
                $prapproval->ref_no = $value['ref_no'];
                $prapproval->running_no= $value['running_no'];
                $prapproval->sample= $value['sample'];
                $prapproval->save();
            }
        }
        return redirect()->route('general_setting.index', 4)->with('custom_success', 'Initial Ref No Updated Successfully.');
    }

    public function updatePR(Request $request)
    {
        PrApproval::truncate();
        if($request->pr){
            foreach($request->pr as $value){
                $prapproval = new PrApproval();
                $prapproval->designation_id = $value['designation'];
                $prapproval->operator= $value['operator'];
                $prapproval->amount= $value['amount'];
                $prapproval->category= $value['category'];
                if($value['category'] == 'Others'){
                    $prapproval->category_other =  $value['category_other'];
                }else{
                    $prapproval->category_other =  NULL;
                }
                $prapproval->save();
            }
        }
        return redirect()->route('general_setting.index', 5)->with('custom_success', 'PR Approval Updated Successfully.');
    }

    public function updatePS(Request $request) {

        $payroll_setup = PayrollSetup::find(1);
    
        if (!$payroll_setup) {
            $payroll_setup = new PayrollSetup;
            $payroll_setup->id = 1; 
        }
        $payroll_setup->hrdf = isset($request->hrdf) ? $request->hrdf : '0';
        $payroll_setup->hrdf_per = $request->hrdf_per ?? $payroll_setup->hrdf_per;

        $payroll_setup->paysilp = isset($request->paysilp) ? $request->paysilp : '0';
        $payroll_setup->paysilp_remarks = $request->paysilp_remarks ?? $payroll_setup->paysilp_remarks;

        $payroll_setup->kwsp = isset($request->kwsp) ? $request->kwsp : '0';
        $payroll_setup->kwsp_category_1_employee_per = $request->kwsp_category_1_employee_per ?? $payroll_setup->kwsp_category_1_employee_per;
        $payroll_setup->kwsp_category_1_employer_per = $request->kwsp_category_1_employer_per ?? $payroll_setup->kwsp_category_1_employer_per;
        $payroll_setup->kwsp_category_2_employee_per = $request->kwsp_category_2_employee_per ?? $payroll_setup->kwsp_category_2_employee_per;
        $payroll_setup->kwsp_category_2_employer_per = $request->kwsp_category_2_employer_per ?? $payroll_setup->kwsp_category_2_employer_per;
        $payroll_setup->kwsp_category_3_employee_per = $request->kwsp_category_3_employee_per ?? $payroll_setup->kwsp_category_3_employee_per;
        $payroll_setup->kwsp_category_3_employer_per = $request->kwsp_category_3_employer_per ?? $payroll_setup->kwsp_category_3_employer_per;
        $payroll_setup->kwsp_category_4_employee_per = $request->kwsp_category_4_employee_per ?? $payroll_setup->kwsp_category_4_employee_per;
        $payroll_setup->kwsp_category_4_employer_per = $request->kwsp_category_4_employer_per ?? $payroll_setup->kwsp_category_4_employer_per;

        $payroll_setup->eve_employee = isset($request->eve_employee) ? $request->eve_employee : '0' ;
        $payroll_setup->eve_employee_per = $request->eve_employee_per ?? $payroll_setup->eve_employee_per;

        $payroll_setup->eve_employer = isset($request->eve_employer) ? $request->eve_employer : '0' ;
        $payroll_setup->eve_employer_per = $request->eve_employer_per ?? $payroll_setup->eve_employer_per;

        $payroll_setup->eve_ot = isset($request->eve_ot) ? $request->eve_ot : '0' ;

        $payroll_setup->eve_ot_allowance = isset($request->eve_ot_allowance) ? $request->eve_ot_allowance : '0' ;


        $payroll_setup->socso = isset($request->socso) ? $request->socso : '0';
        $payroll_setup->socso_employee_per = $request->socso_employee_per ?? $payroll_setup->socso_employee_per;
        $payroll_setup->socso_employer_per = $request->socso_employer_per ?? $payroll_setup->socso_employer_per;

        $payroll_setup->eis = isset($request->eis) ? $request->eis : '0';
        $payroll_setup->eis_employee_per = $request->eis_employee_per ?? $payroll_setup->eis_employee_per;
        $payroll_setup->eis_employer_per = $request->eis_employer_per ?? $payroll_setup->eis_employer_per;

        $payroll_setup->save();


        return redirect()->route('general_setting.index', 6)->with('custom_success', 'Payroll Setup Updated Successfully.');
    }

}
