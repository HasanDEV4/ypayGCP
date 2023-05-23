<?php

namespace App\Http\Controllers\API\version_4;

use App\Models\User;
use App\Models\CustBasicDetail;
use App\Models\CustBankDetail;
use App\Models\CustCnicDetail;
use App\Models\CustAccountDetail;
use App\Models\FundsAdditionalDetail;
use App\Models\RiskProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;

use App\Models\Amc;
use App\Models\Fund;

use function App\Libraries\Helpers\generateAssetAllocation;
use function App\Libraries\Helpers\userInvestmentSum;

class FundController4 extends Controller
{
    // public function getFunds(){
    //     $users = Fund::with('amc')->get();
    // }

    public function amcIndex()
    {
        $amc = Amc::where('status', 1)->orderBy('id', 'DESC')->get();

        $data = [];
        foreach ($amc as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['name'] = $value['entity_name'];
            $data[$key]['logo'] = $value['logo'];
        }
        return response()->json(['status' => true, 'data' => $data], 200);
    }

    public function amcShow(Request $request, $id)
    {
        // $fund = Fund::with('amc', 'additional_details')->whereHas('amc', function($q) use($id) {
        //     $q->where('id', $id);
        // })->whereHas('additional_details', function($q) {
        //     $q->where('status', 1);
        // })->orderBy('id', 'DESC')->get();
        $fund = Fund::with(['additional_details' => function($q){
            $q->select('id', 'fund_id', 'status','profile_risk', 'category');
        }])->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->whereHas('amc', function($q) use($id) {
            $q->where('id', $id);
        })->select('id','user_id','amc_id','risk_profile','fund_name','fund_size','nav','fund_image','return_rate')->get();
        $user_id = $request->user()->id;
        $data = [];
        $record = $fund->toArray();
        foreach($record as $key => $value){
                // foreach($value['amc']['amc_cust_profile'] as  $key2 => $value2){
                //     // echo '<pre>';         
                //     if ($value2['user_id'] == $request->user_id){
                //         $record[$key]['amc']['profile_status'] = $value2['status'];
                //         // unset($record[$key]['amc']['amc_cust_profile'][$key2]);
                //     }
                // }
                $funds_profile_risk=FundsAdditionalDetail::where('fund_id',$value['id'])->pluck('profile_risk')->first();
                $is_risk_profile=RiskProfile::where('type',$funds_profile_risk)->select('id','risk_profile')->get();
                if(isset($is_risk_profile[0]))
                {
                // $record[$key]['is_profile_risk']=$is_risk_profile[0]->risk_profile??'';
                $record[$key]['risk_profile_id']=$is_risk_profile[0]->id??'';
                }
                $record[$key]['is_profile_risk']=$value['risk_profile'];
                $data = $record;
        }
        $user_risk_status=CustAccountDetail::where('user_id',$user_id)->pluck('risk_profile_status');
        return response()->json(['status' => true, 'data' => $data, 'sum' => userInvestmentSum($user_id),'user_risk_status'=>$user_risk_status], 200);
    }

    public function fundShow($id)
    {
        $fund = Fund::with('amc', 'additional_details')->whereHas('amc', function($q) use($id) {
            $q->where('id', $id);
        })->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->get();
        $data = [];
        foreach ($fund as $key => $value) {
            $data[$key]['id']     = $value['id'];
            $data[$key]['amc_id'] = $value['amc']['id'];
            $data[$key]['logo']   = $value['amc']['logo'];
            $data[$key]['name']   = $value['fund_name'];
            $data[$key]['amount'] = $value['fund_size'];
            $data[$key]['rate']   = $value['return_rate'];
            $data[$key]['url']    = $value['url'];
        }

        // if(!$data->count()) {
        //     return response()->json(['status' => true, 'message' => 'Funds not found.'], 404);
        // }
        return response()->json(['status' => true, 'data' => $data], 200);
    }


    public function show($id)
    {
        $data = Fund::with('amc', 'additional_details', 'asset', 'asset_allocations', 'holdings', 'fund_bank')
        ->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->find($id);

        if (!$data) {
            return response()->json(['status' => true, 'message' => 'Fund not found.'], 404);
        }
        $data = $data->toArray();
        $funds_profile_risk=FundsAdditionalDetail::where('fund_id',$id)->pluck('profile_risk')->first();
        // $is_risk_profile=RiskProfile::where('type',$funds_profile_risk)->pluck('risk_profile')->first();
        $is_risk_profile=$data['risk_profile'];
        $data['is_profile_risk']=$is_risk_profile;
        $data['asset_allocations'] = generateAssetAllocation($data['asset_allocations'], true);
        $asset = [];
        foreach ($data['asset'] as $key => $value) {
            $asset[$key]['name']            =  $value['asset'];
            $asset[$key]['value']           =  json_decode($value['share_percent']);
            $asset[$key]['color']           =  $value['color'];

        }
        $holding = [];
        foreach ($data['holdings'] as $key => $value) {
            $holding[$key]['name']  =  $value['type'];
            $holding[$key]['value'] =  $value['share_percent'];
        }
        $data['asset']    = $asset;
        $data['holding'] = $holding;
        // echo '<pre>'; print_r($data['asset']); echo '</pre>';
        return response()->json(['status' => true, 'data' => $data], 200);
    }
    public function get_popular_and_new(Request $request)
    {
        $popularfunds = Fund::with(['additional_details' => function($q){
            $q->select('id', 'fund_id', 'status','profile_risk', 'category');
        },'amc'=> function($q){
            $q->select('id','entity_name','status');
        }])->where('is_popular',1)->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->select('id','user_id','amc_id','risk_profile','fund_name','fund_size','nav','fund_image','return_rate')->get();
       
        $newfunds = Fund::with(['additional_details' => function($q){
            $q->select('id', 'fund_id', 'status','profile_risk', 'category');
        },'amc'=> function($q){
            $q->select('id','entity_name','status');
        }])->where('is_new',1)
        ->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->select('id','user_id','amc_id','risk_profile','fund_name','fund_size','nav','fund_image','return_rate')->get();
        $popularfundsdata = [];
        $new_funds_data = [];

        $popularfundsrecord = $popularfunds->toArray();
        $newfundsrecord = $newfunds->toArray();
        foreach($newfundsrecord as $newfund)
        {
            $new_funds_data=$newfundsrecord;
        }
        foreach($popularfundsrecord as $key => $value){
                // foreach($value['amc']['amc_cust_profile'] as  $key2 => $value2){
                //     // echo '<pre>';         
                //     if ($value2['user_id'] == $request->user_id){
                //         $popularfundsrecord[$key]['amc']['profile_status'] = $value2['status'];
                //         // unset($popularfundsrecord[$key]['amc']['amc_cust_profile'][$key2]);
                //     }
                // }
                $funds_profile_risk=FundsAdditionalDetail::where('fund_id',$value['id'])->pluck('profile_risk')->first();
                // $is_risk_profile=RiskProfile::where('type',$funds_profile_risk)->pluck('risk_profile')->first();
                $is_risk_profile=$value['risk_profile'];
                $popularfundsrecord[$key]['amc_name']=$value['amc']['entity_name'];
                $popularfundsrecord[$key]['is_profile_risk']=$is_risk_profile;
                $popularfundsdata=$popularfundsrecord;
        }
        foreach($newfundsrecord as $key => $value){
            // foreach($value['amc']['amc_cust_profile'] as  $key2 => $value2){
            //     // echo '<pre>';         
            //     if ($value2['user_id'] == $request->user_id){
            //         $popularfundsrecord[$key]['amc']['profile_status'] = $value2['status'];
            //         // unset($popularfundsrecord[$key]['amc']['amc_cust_profile'][$key2]);
            //     }
            // }
            $funds_profile_risk=FundsAdditionalDetail::where('fund_id',$value['id'])->pluck('profile_risk')->first();
            // $is_risk_profile=RiskProfile::where('type',$funds_profile_risk)->pluck('risk_profile')->first();
            $is_risk_profile=$value['risk_profile'];
            $newfundsrecord[$key]['amc_name']=$value['amc']['entity_name'];
            $newfundsrecord[$key]['is_profile_risk']=$is_risk_profile;
            $new_funds_data=$newfundsrecord;
    }
        if(count($new_funds_data)==0)
        $new_funds_data=null;
        $user_risk_status=CustAccountDetail::where('user_id',$request->user_id)->pluck('risk_profile_status');
        return response()->json(['status' => true, 'data' => $popularfundsdata,'new_funds_data'=>$new_funds_data,'user_risk_status'=>$user_risk_status], 200);
    }
    public function isPopular(Request $request)
    {

        $fund = Fund::with('additional_details','amc.amcCustProfile')->where('is_popular',1)
        ->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->get();
        $data = [];
        $record = $fund->toArray();
        foreach($record as $key => $value){
                foreach($value['amc']['amc_cust_profile'] as  $key2 => $value2){
                    // echo '<pre>';         
                    if ($value2['user_id'] == $request->user_id){
                        $record[$key]['amc']['profile_status'] = $value2['status'];
                        // unset($record[$key]['amc']['amc_cust_profile'][$key2]);
                    }
                }
                $funds_profile_risk=FundsAdditionalDetail::where('fund_id',$value['id'])->pluck('profile_risk')->first();
                // $is_risk_profile=RiskProfile::where('type',$funds_profile_risk)->pluck('risk_profile')->first();
                $is_risk_profile=$value['risk_profile'];
                $record[$key]['is_profile_risk']=$is_risk_profile;
                $data=$record;
        }
        $user_risk_status=CustAccountDetail::where('user_id',$request->user_id)->pluck('risk_profile_status');
        return response()->json(['status' => true, 'data' => $data,'user_risk_status'=>$user_risk_status], 200);
    }

    public function isAll(Request $request)
    {
        $fund = Fund::with(['additional_details' => function($q){
            $q->select('id', 'fund_id', 'status','profile_risk', 'category');
        }, 'amc'])->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->select('id','user_id','amc_id','risk_profile','fund_name','fund_size','nav','fund_image','return_rate')->get();
        $data = [];
        $record = $fund->toArray();
        foreach($record as $key => $value){
                // foreach($value['amc']['amc_cust_profile'] as  $key2 => $value2){
                //     // echo '<pre>';         
                //     if ($value2['user_id'] == $request->user_id){
                //         $record[$key]['amc']['profile_status'] = $value2['status'];
                //         // unset($record[$key]['amc']['amc_cust_profile'][$key2]);
                //     }
                // }
                $funds_profile_risk=FundsAdditionalDetail::where('fund_id',$value['id'])->pluck('profile_risk')->first();
                $is_risk_profile=RiskProfile::where('type',$funds_profile_risk)->select('id','risk_profile')->get();
                if(isset($is_risk_profile[0]))
                {
                // $record[$key]['is_profile_risk']=$is_risk_profile[0]->risk_profile??'';
                $record[$key]['risk_profile_id']=$is_risk_profile[0]->id??'';
                }
                $record[$key]['is_profile_risk']=$value['risk_profile']??'';
                $record[$key]['amc_name'] = $value['amc']['entity_name'];
                $data = $record;
        }
        $user_risk_status=CustAccountDetail::where('user_id',$request->user_id)->pluck('risk_profile_status');
        return response()->json(['status' => true, 'data' => $data, 'sum' => userInvestmentSum($request->user_id),'user_risk_status'=>$user_risk_status], 200);
    }

    public function searchFund(Request $request)
    {

        $fund = Fund::with('additional_details')->where(function ($q) use ($request){
            $q->where('fund_name', 'like', '%' . $request->search . '%')->orWhere('nav', 'like', '%' . $request->search . '%');
        })->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->get();

        return response()->json(['status' => true, 'data' => $fund, 'sum' => userInvestmentSum($request->user_id)], 200);
    }
}