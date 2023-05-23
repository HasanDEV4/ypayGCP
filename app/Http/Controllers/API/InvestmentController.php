<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Investment;
use App\Models\FundHolding;
use App\Models\Redemption;
use App\Models\CustAccountDetail;
use App\Models\FundsAdditionalDetail;
use App\Models\RiskProfile;
use App\Models\Fund;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;
use App\Models\AmcCustProfile;
use App\Models\AmcFund;
use Mail;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use function App\Libraries\Helpers\s3ImageUploadApi;
use Carbon\Carbon;
use Config;
use DB;
class InvestmentController extends Controller
{
    public function showInvestments(Request $request)
    {
        $investment = Investment::where('user_id', $request->user_id)->orderBy('id', 'DESC');
        $data = [];
    if ($request->redemption == 1) { 
        $redemption = Redemption::with('investment.fund.amc')->whereIn('invest_id', $investment->pluck('id')->toArray())->orderBy('id', 'DESC')->get();
        foreach ($redemption as $key => $value) {
            $data[$key]['status']         = $value['status'];
            $data[$key]['id']             = $value['id'];
            $data[$key]['amount']         = $value['investment']['amount'];
            $data[$key]['return_amount']  = $value['redeem_amount'];
            $data[$key]['investment_date']= date('Y-m-d',strtotime($value['investment']['created_at']));
            $data[$key]['transaction_id'] = $value['investment']['transaction_id'];
            $data[$key]['logo_link']      = $value['investment']['fund']['amc']['logo_link'];
            $data[$key]['fund_name']      = $value['investment']['fund']['fund_name'];
            $data[$key]['fund_image']     = $value['investment']['fund']['fund_image'];
            $data[$key]['fund_id']        = $value['investment']['fund']['id'];
            // $data[$key]['return_amount']  = ($value['amount']*$value['fund']['nav'])/$value['nav'];
        }
     }
     else if ($request->redemption == 0) { 
        $investment = $investment->with('fund.amc', 'redemption')->where('status', 1)->get();
        $my_investment = $investment->toArray();
        foreach ($my_investment as $key => $value) {
            /*Investment*/
            if ((!$value['redemption'] || $value['redemption']) && $request->redemption == 0) {
                foreach ($value['redemption'] as $key2 => $value2) {
                    if($value2['status'] == 1 || $value2['status'] == 0){
                        unset($my_investment[$key]);
                        continue;
                    }
                }
            }
        }
        
        foreach (array_values($my_investment) as $key => $value) {
            $data[$key]['id']             = $value['id'];
            $data[$key]['amount']         = $value['amount'];
            $data[$key]['status']         = $value['status'];
            $data[$key]['transaction_id'] = $value['transaction_id'];
            $data[$key]['logo_link']      = $value['fund']['amc']['logo_link'];
            $data[$key]['fund_name']      = $value['fund']['fund_name'];
            $data[$key]['fund_image']     = $value['fund']['fund_image'];
            $data[$key]['fund_id']        = $value['fund']['id'];
            $data[$key]['return_amount']  = $value['unit']*$value['fund']['nav'];
            $data[$key]['investment_date']= date('Y-m-d',strtotime($value['created_at']));
        }
     }
     else{
        $data = [];
     }
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }

    public function showSingleInvestments(Request $request)
    {
        $data = Investment::with('fund.amc', 'redemption')->find($request->id);
        $investment=Investment::select(DB::raw('created_at'))->find($request->id);
        $investment_date=date('d M Y',strtotime($data->created_at));
        $return_amount=$data->unit*$data->fund->nav;
        $user = CustAccountDetail::where('user_id', $data->user_id)->first();
        return response()->json([
            'status' => true,
            'data'   => $data,
            'investment_date'=>$investment_date,
            'created_at'=>date("Y-m-d h:i:s a",strtotime($investment->created_at)),
            'return_amount'=>$return_amount,
            'user'   => $user,
        ], 200);
    }

    public function showSingleRedemptions(Request $request)
    {
        $data = Redemption::with('investment.fund.amc')->find($request->id);
        $data->amount=number_format($data->amount);
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }

    public function getAmc()
    {  
        $fund = Fund::where('is_popular', 1)->with('additional_details')->whereHas('additional_details', function ($q) {
                $q->where('status', 1);
            })->orderBy('id', 'DESC')->get();
        $data = [];
        foreach ($fund as $key => $value) {
            $data[$key]['label'] = $value['fund_name'].' ('.$value['return_rate'].')';
            $data[$key]['value'] = $value['return_rate'];
        }
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
    public function getuserinvestmentcount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fund_id' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $investment_amounts = Investment::where('user_id',$request->user_id)->where('fund_id',$request->fund_id)->whereIn('status',[0,1])->pluck('amount');
        $redeem_investments = Investment::where('user_id',$request->user_id)->where('fund_id',$request->fund_id)->whereHas('redemption', function ($q){ 
            $q->whereIn('status',[0,1]);
          })->pluck('amount');
        $total_investment_amount=0.0;
        foreach($investment_amounts as $investment_amount)
        {
            $total_investment_amount+=$investment_amount;
        }
        foreach($redeem_investments as $redeem_investment)
        {
            $total_investment_amount-=$redeem_investment;
        }
        $funds_details=FundsAdditionalDetail::where('fund_id',$request->fund_id)->first();
        $risk_profile_data=RiskProfile::where('type',$funds_details->profile_risk)->get();

        return response()->json([
            'status' => true,
            'risk_profile_data' => $risk_profile_data[0]??"",
            'total_investment_amount' => $total_investment_amount??""
        ], 200);
    }
    public function save(Request $request){
        if(strtolower($request->pay_method)!="nift")
        {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $folderPath     = "storage/uploads/investment/";
        $image_parts    = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $filename       = 'ibft_' . time();
        $file           = $filename . '.'.$image_type;
        // file_put_contents($file, $image_base64);
        $path               = "investments/".$file;
        $image_url           = s3ImageUploadApi($request->image, $path);

        /* calculate units */
        }
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $amc_fund_data=AmcFund::where('ypay_fund_id',$request->fund_id)->get();
        $investment                 = new Investment;
        $investment->transaction_id = $string;
        $investment->user_id        = $request->user_id;
        $investment->fund_id        = $request->fund_id;
        $investment->amc_fund_id    =$amc_fund_data[0]->id??'';
        $investment->amount         = $request->amount;
        $investment->frequency     =  "Monthly";
        $investment->pay_option     = "100%";
        $investment->pay_method     = strtolower($request->pay_method);
        $fund = Fund::with('fund_bank','additional_details')->where('id',$investment->fund_id)->first();
        $custAmcProfile = AmcCustProfile::where('amc_id',$fund->amc_id)->where('user_id',$request->user_id)->first();
        $investment->account_number = $custAmcProfile->account_number??"";
        if(isset($file))
        $investment->image          = $image_url??"";
        $investment->rrn            = $request->rrn;
        $investment->transaction_status=$request->transaction_status;
        $investment->transaction_time=$request->transaction_time;
        // $investment->nav            = $request->nav;
        // $investment->unit           = round($request->amount/$request->nav, 2);
        $investment->save();

        $user = User::where('id', $request->user_id)->first();
        

        if(empty($custAmcProfile))
        {
            $amcCustProfiles                        = new AmcCustProfile();
            $amcCustProfiles->amc_id                = $fund->amc_id;
            $amcCustProfiles->user_id               = $request->user_id;
            $amcCustProfiles->status                = -1;
            $amcCustProfiles->save();
        }

                // email notificaion 
                $url = 'https://networks.ypayfinancial.com/api/mailv1/transaction_mail.php';
                $body = [
                        'email'             => $user->email, 
                        'name'              => $user->full_name,
                        'investment_id'     => $investment->transaction_id, 
                        'timestamp'         => Carbon::parse($investment->created_at)->format('d/m/y H:i:s'), 
                        'fund_bank'         => $fund->fund_bank->bank_name,
                        'fund_iban'         => $fund->fund_bank->iban_number,
                        'fund_name'         => $fund->fund_name,
                        'sales_load'        => $fund->additional_details->fund_ratings,
                        'amount'            => $investment->amount 
                    ];
                
                sendEmail($body,$url);
        
        // Mail::send('mail.userInvestmentCreated', ['name'=> $user->full_name], function($message) use ($user) {
        //     $message->to($user->email, $user->full_name)->subject('New Investment');
        //     $message->from('hello@ypayfinancial.com', 'YPay');
        //   });
          $data = ['message' => Config::get('messages.investment_request_received'), 'image' => $request->fund_image];
          sendNotification($user->fcm_token, $data, $request->user_id, 'investment_pending');

        return response()->json([
            'status' => true,
        ], 200);
    }
    public function addRedemption(Request $request){

        $redemption            = new Redemption;
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $investment = Investment::where('id',$request->id)->first();
        $redemption->transaction_id=$string;
        $redemption->invest_id = $request->id;
        $redemption->amount    = $request->amount;
        $redemption->redeem_amount=$request->amount;
        $redemption->redeem_units=(float) $investment->unit;
        $redemption->redeem_by= "Unit";
        $redemption->save();

        $user = User::where('id', $request->user_id)->first();
        $fund = Fund::where('id', $request->fund_id)->first();

          // email notificaion 
          $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_mail.php';
          $body = [
                  'email'             => $user->email, 
                  'name'              => $user->full_name,
                  'investment_id'     => $investment->transaction_id, 
                  'timestamp'         => Carbon::parse($investment->created_at)->format('d/m/y H:i:s'), 
                  'fund_name'         => $fund->fund_name,
                  'amount'            => $redemption->amount,
                  'redemption_date'   => Carbon::parse($redemption->created_at)->format('d/m/y H:i:s')
              ];
          
          sendEmail($body,$url);

        // Mail::send('mail.userRedemptionCreated', ['name'=> $user->full_name], function($message) use ($user) {
        //     $message->to($user->email, $user->full_name)->subject('New Redemption');
        //     $message->from('hello@ypayfinancial.com', 'YPay');
        //   });
          $data = ['message' => Config::get('messages.redemption_request_received'), 'image' => $fund->fund_image];
          sendNotification($user->fcm_token, $data, $request->user_id, 'redemption_pending');


        return response()->json([
            'status' => true,
        ], 200);
    }

    public function showInvestmentFunds(Request $request)
    {
        $data = Investment::with('fund', 'redemption')->where('user_id', $request->user_id)->where('status', 1)->orderBy('id','ASC')->get();
        $data = $data->toArray();
        foreach ($data as $key => $value) {
             if ($value['redemption']) {
                foreach ($value['redemption'] as $key2 => $value2) {
                    if($value2['status'] == 1 || $value2['status'] == 0){
                        unset($data[$key]);
                    }
                }
            }
        }
        $total_sum = array_sum(array_map(function($item) { 
                if($item['status'] == 1)
                    return @$item['amount']; 
            }, $data));
        $unique = collect($data)->unique('fund_id');
        $funds     = [];
        $other     = [];
        $other_sum = [];
        $count     = 0;
        $color     = ['#04668D', '#2E7591', '#5CA9C6', '#73D6FB'];
        foreach (array_values($unique->toArray()) as $key => $value) {
            $sum = 0;
            if ($key >= 3) {
                $other[$count]['fund_name'] = $value['fund']['fund_name'];
                $other[$count]['fund_id']   = $value['fund']['id'];
                $other[$count]['amount']    = $value['amount'];
                $other['color']             = $color[3];
                $count++;
                foreach ($data as $key2 => $value2) {
                    if (($value['fund_id'] == $value2['fund_id']) && $value2['status'] == 1) {
                        $other_sum[$key2] = $value2['amount'];
                    }
                    $other['sum'] = round(array_sum($other_sum) / $total_sum * 100, 1);
                }
                continue;
            }
            $funds[$key]['fund_id'] = $value['fund']['id'];
            $funds[$key]['name']    = $value['fund']['fund_name'];
            $funds[$key]['color']   = $color[$key];
            foreach ($data as $key3 => $value3) {
                if (($value['fund_id'] == $value3['fund_id']) && $value3['status'] == 1) {
                    $sum += $value3['amount'];
                }
            $funds[$key]['sum'] = $sum;
            }
        }

        $fund_ids    = collect($funds)->pluck('fund_id'); 
        $fundholding = FundHolding::whereIn('fund_id', $fund_ids)->get();
        foreach ($funds as $key => $value) {
            $funds[$key]['sum'] = round($value['sum'] / $total_sum * 100, 1);
            foreach ($fundholding->toArray() as $key2 => $value2) {
                if ($value2['fund_id'] == $value['fund_id']) {
                    $funds[$key]['holdings'][$key2] = $value2;
                }
            }
        }
        $other_fund_ids    = collect($other)->pluck('fund_id');
        if (count($other)) {
            $fundholding       = FundHolding::where('fund_id', $other[0]['fund_id'])->get();
            $other['holdings'] = $fundholding->toArray();
        }
        return response()->json([
            'status' => true,
            'fund'   => array_values($funds),
            'other'  => $other,
        ], 200);
    }

    public function searchViaFundName(Request $request)
    {

        $fundName = Fund::where('fund_name', 'like', '%' . $request->search . '%' )->pluck('id');
        $investment      = Investment::whereIn('fund_id',$fundName)->where('user_id', $request->user_id)->with('fund','redemption')->orderBy('id', 'desc')->get();
        $redemption_data = [];
        $investment_data = [];
        foreach($investment as $key => $value)
        {
            $investment_data[$key]['invest_id'] = $value['id'];
            $investment_data[$key]['date']      = $value['created_at']->format('Y-m-d H:i:s');
            $investment_data[$key]['type']      = 'Investment';
            $investment_data[$key]['funds']     = $value['fund']['fund_name'];
            $investment_data[$key]['amount']    = $value['amount'];
            $investment_data[$key]['status']    = $value['status'];  
            if($value['redemption'])
            {
                foreach($value['redemption'] as $key2 => $value2){
                    $redemption_data[$key]['invest_id'] = $value['id'];
                    $redemption_data[$key]['date']      = $value2['created_at']->format('Y-m-d H:i:s');
                    $redemption_data[$key]['type']      = 'Redemption';
                    $redemption_data[$key]['funds']     = $value['fund']['fund_name'];
                    $redemption_data[$key]['amount']    = $value['amount'];
                    $redemption_data[$key]['status']    = $value2['status'];   
                }
            }
        }
        $data = array_merge($investment_data, $redemption_data);
        array_multisort(array_column($data, 'date'), SORT_DESC, $data);
        $tableData = [];
        foreach ($data as $key => $value) {
            $tableData[$key][] = date('d M,Y',strtotime($value['date']));
            $tableData[$key][] = $value['type'];
            $tableData[$key][] = strlen($value['funds']) > 10 ? substr($value['funds'],0,10)."..." : $value['funds'];
            $tableData[$key][] = 'Rs. '.$value['amount'];
            $tableData[$key][] = ($value['status'] == 0 ? 'Pending' : ($value['status'] == 1 ? 'Successful' : 'Failed'));
            $tableData[$key][] = $value['invest_id'];
        }
        return response()->json([
            'data' => $tableData
        ], 200);
    }


    public function myTransactionHistory(Request $request)
    {
        $investment      = Investment::where('user_id', $request->user_id)->with('fund','redemption')->orderBy('id', 'desc')->get();
        $redemption_data = [];
        $investment_data = [];
        foreach($investment as $key => $value)
        {
            $investment_data[$key]['invest_id'] = $value['id'];
            $investment_data[$key]['date']      = $value['created_at']->format('Y-m-d H:i:s');
            $investment_data[$key]['type']      = 'Investment';
            $investment_data[$key]['funds']     = $value['fund']['fund_name'];
            $investment_data[$key]['amount']    = number_format($value['amount']);
            $investment_data[$key]['status']    = $value['status'];  
            $investment_data[$key]['transaction_id']    = $value['transaction_id']; 
            $investment_data[$key]['rejected_reason']    = $value['rejected_reason'];  
            if($value['redemption'])
            {
                foreach($value['redemption'] as $key2 => $value2){
                    $redemption_data[$key]['invest_id'] = $value['id'];
                    $redemption_data[$key]['date']      = $value2['created_at']->format('Y-m-d H:i:s');
                    $redemption_data[$key]['type']      = 'Redemption';
                    $redemption_data[$key]['funds']     = $value['fund']['fund_name'];
                    $redemption_data[$key]['amount']    = isset($value2['redeem_amount']) ? number_format($value2['redeem_amount']) : number_format($value2['amount']);
                    $redemption_data[$key]['status']    = $value2['status'];
                    $redemption_data[$key]['transaction_id']    = $value2['transaction_id'];   
                    $redemption_data[$key]['rejected_reason']    = $value2['rejected_reason'];   
                }
            }
        }
        $data = array_merge($investment_data, $redemption_data);
        array_multisort(array_column($data, 'date'), SORT_DESC, $data);
        $tableData = [];
        foreach ($data as $key => $value) {
            $tableData[$key]['date'] = date('d M,Y',strtotime($value['date']));
            $tableData[$key]['transaction_id'] = $value['transaction_id'];
            $tableData[$key]['type'] = $value['type'];
            $tableData[$key]['funds'] = $value['funds'];
            $tableData[$key]['amount'] = 'Rs. '.$value['amount'];
            $tableData[$key]['status'] = ($value['status'] == 0 ? 'Pending' : ($value['status'] == 1 ? 'Successful' : 'Rejected'));
            if($value['status']==2)
            $tableData[$key]['rejected_reason']=$value['rejected_reason'];
            $tableData[$key]['investment_id'] = $value['invest_id'];
        }
        return response()->json([
            'data' => $tableData
        ], 200);
    }

    public function searchInvestment(Request $request)
    {
      
        $search = $request->search;

        $investment = Investment::where(function ($q) use ($search){
            $q->WhereHas('fund', function($z) use ($search) {
                $z->where('fund_name','like', '%' . $search . '%');
            })->orWhere('amount', 'like', '%' . $search . '%');
        })->where('user_id', $request->user_id)->where('status',1);
        
        $data = [];
    if ($request->redemption == 1) { 
        $redemption = Redemption::with('investment.fund.amc')->whereIn('invest_id', $investment->pluck('id')->toArray())->orderBy('id', 'DESC')->get();
        foreach ($redemption as $key => $value) {
            $data[$key]['status']         = $value['status'];
            $data[$key]['id']             = $value['id'];
            $data[$key]['amount']         = number_format($value['investment']['amount']);
            $data[$key]['transaction_id'] = $value['investment']['transaction_id'];
            $data[$key]['logo_link']      = $value['investment']['fund']['amc']['logo_link'];
            $data[$key]['fund_name']      = $value['investment']['fund']['fund_name'];
            $data[$key]['fund_image']     = $value['investment']['fund']['fund_image'];
            $data[$key]['fund_id']        = $value['investment']['fund']['id'];
            // $data[$key]['return_amount']  = ($value['amount']*$value['fund']['nav'])/$value['nav'];
        }
     }
     else if ($request->redemption == 0) { 

        $investment = $investment->with('fund.amc', 'redemption')->where('status', 1)->orderBy('id','desc')->get();
        $my_investment = $investment->toArray();
        foreach ($my_investment as $key => $value) {
            /*Investment*/
            if ((!$value['redemption'] || $value['redemption']) && $request->redemption == 0) {
                foreach ($value['redemption'] as $key2 => $value2) {
                    if($value2['status'] == 1 || $value2['status'] == 0){
                        unset($my_investment[$key]);
                        continue;
                    }
                }
            }
        }
        
        foreach (array_values($my_investment) as $key => $value) {
            $data[$key]['id']             = $value['id'];
            $data[$key]['amount']         = number_format($value['amount']);
            $data[$key]['status']         = $value['status'];
            $data[$key]['transaction_id'] = $value['transaction_id'];
            $data[$key]['logo_link']      = $value['fund']['amc']['logo_link'];
            $data[$key]['fund_name']      = $value['fund']['fund_name'];
            $data[$key]['fund_image']     = $value['fund']['fund_image'];
            $data[$key]['fund_id']        = $value['fund']['id'];
            // $data[$key]['return_amount']  = ($value['amount']*$value['fund']['nav'])/$value['nav'];
        }
     }
     else{
        $data = [];
     }
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);

    }

    public function checkAmcProfile(Request $request)
    {
        $checkProfile = AmcCustProfile::where('amc_id',$request->amc_id)->where('user_id',$request->user_id)->pluck('status')->first();

        return response()->json([
            'status' => true,
            'profileStatus' => $checkProfile,
        ], 200);
    }

}