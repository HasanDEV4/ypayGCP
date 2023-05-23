<?php

namespace App\Http\Controllers\API\version_4;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
// use App\Models\CustCnicDetail;
// use App\Models\AmcCustProfile;
use App\Models\Investment;
use App\Models\Conversion;
use App\Models\RiskProfile;
use App\Models\Fund;
use App\Models\User;
use App\Models\FundsAdditionalDetail;
use App\Models\AmcFund;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use Carbon\Carbon;
use Config;

class ConversionController4 extends Controller
{
	public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'fund_id' => 'required',
            'investment_id' => 'required',
            'type' => 'required|in:investment,conversion',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user_id = $request->user()->id;
        if ($request->type == "investment") {
            $source = Investment::where(['id'=> $request->investment_id, 'user_id' => $user_id, 'status' => 1])->with('redemption', 'fund')->whereHas('redemption', function ($q){ 
                $q->whereIn('status',[0,1]);
              })->first();
        } else if ($request->type == "conversion") {
            $source = Conversion::where(['id'=> $request->investment_id, 'user_id' => $user_id, 'status' => 1])->with('redemption', 'fund')->whereHas('redemption', function ($q){ 
                $q->whereIn('status',[0,1]);
              })->first();
        }
        if (!isset($source)) {
            $source_check_error_message = [
                'error' => ['investment not found']
            ];
            return response()->json(['status' => 'error', 'errors' => $source_check_error_message], 422);
        } else if (count($source->redemption)>0) {
            $source_check_error_message = [
                'error' => ['We have already received a redemption request for this investment.']
            ];
            return response()->json(['status' => 'error', 'errors' => $source_check_error_message], 422);
        }

        $fund = Fund::with('fund_bank','additional_details')->where('id',$request->fund_id)->first();
        if ($request->fund_id == $source->fund_id) {
            $fund_check_error_message = [
                'error' => ['Conversion can not be proccessed into same fund. Please select another fund']
            ];
            return response()->json(['status' => 'error', 'errors' => $fund_check_error_message], 422);
        } else if ($fund->amc_id != $source->fund->amc_id) {
            $fund_check_error_message = [
                'error' => ["Fund's AMC must be same."]
            ];
            return response()->json(['status' => 'error', 'errors' => $fund_check_error_message], 422);
        }
        // $funds_details=FundsAdditionalDetail::where('fund_id',$request->fund_id)->first();
        
        // $risk_profile_data=RiskProfile::where('type',$fund->additional_details->profile_risk)->first();
        // $amount_check_error_message = [
        //     'amount' => ["For this fund, your investment amount should be to be between Rs. ".number_format($risk_profile_data->min_transaction_amount)." and Rs. ".number_format($risk_profile_data->max_transaction_amount)."."]
        // ];
        // $amount = $source->unit*$source->fund->nav;
        // if ($amount > $risk_profile_data->max_transaction_amount || $amount < $risk_profile_data->min_transaction_amount ) {
        //     return response()->json(['status' => 'error', 'errors' => $amount_check_error_message], 422);
        // }
        $amount_check_error_message = [
            'error' => ["For this fund, your investment amount should be to be between Rs. ".number_format($fund->min_transaction_amount)." and Rs. ".number_format($fund->max_transaction_amount)."."]
        ];
        $amount = $source->unit*$source->fund->nav;
        if ($amount > $fund->max_transaction_amount || $amount < $fund->min_transaction_amount ) {
            return response()->json(['status' => 'error', 'errors' => $amount_check_error_message], 422);
        }
        $amc_fund_data=AmcFund::where('ypay_fund_id',$request->fund_id)->get();
        $conversion                 = new Conversion;
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $conversion->transaction_id  = $string;
        if ($request->type == "investment") {
            $conversion->investment_id  = $request->investment_id;
        } else if ($request->type == "conversion") {
            $conversion->conversion_id = $request->investment_id;
        }
        $conversion->type = $request->type;
        $conversion->amount = $amount; //$value['unit']*$value['fund']['nav'];
        $conversion->user_id = $user_id;
        $conversion->fund_id = $request->fund_id;
        $conversion->amc_fund_id = $amc_fund_data[0]->id??'';
        $conversion->save();
    
        $user = User::where('id', $user_id)->first();
    
        // email notificaion 
        $url = 'https://networks.ypayfinancial.com/api/mailv1/transaction_mail.php';
        $body = [
                'email'             => $user->email, 
                'name'              => $user->full_name,
                'investment_id'     => $source->transaction_id, 
                'timestamp'         => Carbon::parse($conversion->created_at)->format('d/m/y H:i:s'), 
                'fund_bank'         => $fund->fund_bank->bank_name,
                'fund_iban'         => $fund->fund_bank->iban_number,
                'fund_name'         => $fund->fund_name,
                'sales_load'        => $fund->additional_details->fund_ratings,
                'amount'            => $amount 
            ];
        
        sendEmail($body,$url);
        
        $data = ['message' => Config::get('messages.conversion_request_received'), 'image' => $fund->fund_image];
          sendNotification($user->fcm_token, $data, $user_id, 'Your conversion request have been submitted');
    
        return response()->json([
            'status' => true,
        ], 200);
    }
}