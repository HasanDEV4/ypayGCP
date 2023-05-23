<?php

namespace App\Http\Controllers;

use App\Exports\MISReport;
use Illuminate\Http\Request;
use App\Models\AmcCustProfile;
use App\Models\Amc;
use App\Models\AmcBank;
use App\Models\AmcFund;
use App\Models\Investment;
use App\Models\Redemption;
use Maatwebsite\Excel\Facades\Excel;

class MISReportController extends Controller
{
    public function index()
    {
        $amcs=Amc::where('status',1)->get();
        return view('mis.index',compact('amcs'));
    }
    public function export(Request $request)
    {
      $amc_ids=$request->amc_ids;
      $date=$request->date;
      $verified=$request->verified;
      $amcs=Amc::whereIn('id',$amc_ids)->get();
      $statuses = array("0"=>"Pending", "1"=>"Approved", "2"=>"Rejected", "3"=>"On Hold");
      $data=[];
      foreach($amcs as $amc)
      {
          $amc_name=strtolower(str_replace(' ','_',$amc->entity_name));
          if($verified=="3")
          $amc_profiles=AmcCustProfile::with('user.cust_cnic_detail','user.cust_bank_detail','user.cust_basic_detail')->where('verified',"2")->where('amc_id',$amc->id)->where('verified_at','like','%'.$date.'%')->get();
          elseif($verified=="2")
          $amc_profiles=[];
          else
          $amc_profiles=AmcCustProfile::with('user.cust_cnic_detail','user.cust_bank_detail','user.cust_basic_detail')->where('verified',$verified)->where('amc_id',$amc->id)->where('verified_at','like','%'.$date.'%')->get();
          $acc_opening_data=array();
          foreach($amc_profiles as $index => $amc_profile)
          {
            $amc_bank_name=AmcBank::where('ypay_bank_id',$amc_profile->user->cust_basic_detail->bank)->pluck('amc_bank_name')->first();
            $acc_opening_data[]=array(
              "srno"=>$index+1,
              "ref_no"=>$amc_profile->amc_reference_number??"",
              "date"=>date('Y-m-d h:i:s a',strtotime($amc_profile->verified_at))??"",
              "cust_name"=>strtoupper($amc_profile->user->full_name)??"",
              "father_name"=>$amc_profile->user->cust_basic_detail->father_name??"",
              "cnic_no"=>$amc_profile->user->cust_cnic_detail->cnic_number??"",
              "iban"=>$amc_profile->user->cust_bank_detail->iban??"",
              "bank_name"=>$amc_bank_name??"",
            );
          }
          $investments=Investment::with('user.cust_cnic_detail','user.cust_bank_detail','fund')->where('verified',$verified)->whereHas('fund', function ($q)use($amc){
            $q->where('amc_id',$amc->id);
          })->where('verified_at','like','%'.$date.'%')->get();
          $purchase_app_data=array();
          foreach($investments as $index => $investment)
          {
            $amc_profile=AmcCustProfile::where('amc_id',$amc->id)->where('user_id',$investment->user->id)->first();
            $amc_fund=AmcFund::where('ypay_fund_id',$investment->fund_id)->where('amc_id',$amc->id)->first();
            $purchase_app_data[]=array(
              "srno"=>$index+1,
              "cnic"=>$investment->user->cust_cnic_detail->cnic_number??"",
              "cust_name"=>strtoupper($investment->user->full_name)??"",
              "folio_no"=>$amc_profile->account_number??"",
              "fund_short_code"=>$amc_fund->amc_fund_id??"",
              "fund_name"=>$investment->fund->fund_name??"",
              "from_account"=>$investment->user->cust_bank_detail->iban??"",
              "amount"=>$investment->amount??"",
              "to_account"=>$investment->fund->fund_bank->iban_number??"",
              "trx_reference"=>$investment->amc_reference_number??"",
              "trx_id"=>$investment->transaction_id??"",
              "stamp_date"=>date('Y-m-d h:i:s a',strtotime($investment->verified_at))??"",
              "status"=>$statuses[$investment->status]??"",
              "fund_unit_class"=>$amc_fund->amc_fund_class_type??"",
              "fund_unit_type"=>$amc_fund->amc_fund_unit_type??"",
            );
          }
          $redemptions=Redemption::with('investment.user.cust_cnic_detail','investment.fund')->where('verified',$verified)->where('verified_at','like','%'.$date.'%')->whereHas('investment', function ($q)use($amc){
            $q->whereHas('fund', function ($qry)use($amc){
              $qry->where('amc_id',$amc->id);
            });
          })->get();
          $redemptions_data=array();
          foreach($redemptions as $index => $redemption)
          {
            $amc_profile=AmcCustProfile::where('amc_id',$amc->id)->where('user_id',$redemption->investment->user->id)->first();
            $amc_fund=AmcFund::where('ypay_fund_id',$redemption->investment->fund_id)->where('amc_id',$amc->id)->first();
            $redemptions_data[]=array(
              "srno"=>$index+1,
              "cnic"=>$redemption->investment->user->cust_cnic_detail->cnic_number??"",
              "folio_no"=>$amc_profile->account_number??"",
              "cust_name"=>strtoupper($redemption->investment->user->full_name)??"",
              "fund_name"=>$redemption->investment->fund->fund_name??"",
              "fund_unit_class"=>$amc_fund->amc_fund_class_type??"",
              "redeem_amount"=>$redemption->redeem_amount??"",
              "redeem_by"=>$redemption->redeem_by??"",
              "redeem_units"=>$redemption->redeem_units??"",
              "trx_reference"=>$redemption->amc_reference_number??"",
              "trx_id"=>$redemption->transaction_id??"",
              "stamp_date"=>date('Y-m-d h:i:s a',strtotime($redemption->verified_at))??"",
            );
          }
          $data['acc_opening_data']=$acc_opening_data;
          $data['purchase_app_data']=$purchase_app_data;
          $data['redemptions_data']=$redemptions_data;
          return Excel::download(new MISReport($data),$amc_name.".xlsx");
      }
      // return response()->json(['success'=> true, 'message' => 'Data Sent successfully!',"mis_csvs"=>$mis_csvs]);
    }
}