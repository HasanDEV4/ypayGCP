<?php

namespace App\Http\Controllers\API\version_4;

use App\Models\AccountType;
use App\Models\User;
use App\Models\Investment;
use App\Models\Conversion;
use App\Models\Dividend;
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
class InvestmentController4 extends Controller
{
    public function showInvestments(Request $request)
    {
        $investment = Investment::where('user_id', $request->user()->id)->orderBy('id', 'DESC');
        if (isset($request->units_convertable) && $request->units_convertable == 1) {
            $investment = $investment->whereHas('fund', function($q) {
                $q->whereHas('amc', function($qry) {
                    $qry->where('units_convertable', 1);
                });
            });
        }
        $data = [];
        $data_1 = [];
        $data_2 = [];
        $data_3 = [];
    if ($request->redemption == 1) { 
        $redemption = Redemption::with('investment.fund.amc')->whereIn('invest_id', $investment->pluck('id')->toArray())->orderBy('id', 'DESC')->get();
        foreach ($redemption as $key => $value) {
            $data[$key]['status']         = $value['status'];
            $data[$key]['id']             = $value['id'];
            $data[$key]['amount']         = number_format($value['investment']['amount']);
            $data[$key]['return_amount']  = number_format($value['redeem_amount']);
            $data[$key]['investment_date']= date('Y-m-d',strtotime($value['investment']['created_at']));
            $data[$key]['transaction_id'] = $value['investment']['transaction_id'];
            $data[$key]['logo_link']      = $value['investment']['fund']['amc']['logo_link'];
            $data[$key]['fund_name']      = $value['investment']['fund']['fund_name'];
            $data[$key]['fund_image']     = $value['investment']['fund']['fund_image'];
            $data[$key]['fund_id']        = $value['investment']['fund']['id'];
            if($value['redeem_amount']-$value['investment']['amount']>=0)
            $data[$key]['total_return']   = "+".number_format($value['redeem_amount']-$value['investment']['amount']);
            else
            $data[$key]['total_return']   = number_format($value['redeem_amount']-$value['investment']['amount']);
            $data[$key]['units_convertable'] = $value['investment']['fund']['amc']['units_convertable'];
            // $data[$key]['return_amount']  = ($value['amount']*$value['fund']['nav'])/$value['nav'];
        }
     }
     else if ($request->redemption == 0) { 
        $investment = $investment->with('fund.amc', 'redemption', 'conversions')->where('status', 1)->get();
        $my_investment = $investment->toArray();
        foreach ($my_investment as $key => $value) {
            /*Investment*/
            if ($value['redemption']) {
                foreach ($value['redemption'] as $key2 => $value2) {
                    if($value2['status'] == 1 || $value2['status'] == 0){
                        unset($my_investment[$key]);
                        continue;
                    }
                }
            } else if ($value['conversions']) {
                foreach ($value['conversions'] as $key3 => $value3) {
                    if($value3['status'] == 1 || $value3['status'] == 0){
                        unset($my_investment[$key]);
                        continue;
                    }
                }
            }
        }
        
        foreach (array_values($my_investment) as $key => $value) {
            // dd($value['conversions']);
            // if ($value['conversions']) {
            //     $type = "conversion";
            //     $fund_name = $value['conversions']['fund']['fund_name'];
            //     $fund_image = $value['conversions']['fund']['fund_image'];
            //     $fund_id = $value['conversions']['fund']['id'];
            //     $return_amount = number_format($value['conversion']['unit']*$value['conversions']['fund']['nav']);
            //     if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
            //         $total_return   = "+".number_format(($value['conversions']['unit']*$value['conversions']['fund']['nav'])-$value['amount']);
            //     else
            //         $total_return   = number_format(($value['conversions']['unit']*$value['conversions']['fund']['nav'])-$value['amount']);
            // } else {
            //     $type = "investment";
            //     $fund_name = $value['conversions']['fund']['fund_name'];
            //     $fund_image = $value['conversions']['fund']['fund_image'];
            //     $fund_id = $value['conversions']['fund']['id'];
            //     $return_amount = number_format($value['conversion']['unit']*$value['conversions']['fund']['nav']);
            //     if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
            //         $total_return   = "+".number_format(($value['conversions']['unit']*$value['conversions']['fund']['nav'])-$value['amount']);
            //     else
            //         $total_return   = number_format(($value['conversions']['unit']*$value['conversions']['fund']['nav'])-$value['amount']);
            // }
            // $data[$key]['id']             = $value['id'];
            // $data[$key]['amount']         = number_format($value['amount']);
            // $data[$key]['status']         = $value['status'];
            // $data[$key]['transaction_id'] = $value['transaction_id'];
            // $data[$key]['logo_link']      = $value['fund']['amc']['logo_link'];
            // $data[$key]['fund_name']      = $value['fund']['fund_name'];
            // $data[$key]['fund_image']     = $value['fund']['fund_image'];
            // $data[$key]['fund_id']        = $value['fund']['id'];
            // $data[$key]['return_amount']  = number_format($value['unit']*$value['fund']['nav']);
            // if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
            // $data[$key]['total_return']   = "+".number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
            // else
            // $data[$key]['total_return']   = number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
            // $data[$key]['investment_date']= date('Y-m-d',strtotime($value['created_at']));
            
            if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
            $total_return   = "+".number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
            else
            $total_return   = number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
            array_push($data_1, [
                    'id' => $value['id'],
                    'amount' => number_format($value['amount']),
                    'status' => $value['status'],
                    'transaction_id' => $value['transaction_id'],
                    'logo_link' => $value['fund']['amc']['logo_link'],
                    'fund_name' => $value['fund']['fund_name'],
                    'fund_image' => $value['fund']['fund_image'],
                    'fund_id' => $value['fund']['id'],
                    'return_amount' => number_format($value['unit']*$value['fund']['nav']),
                    'total_return' => $total_return,
                    'investment_date' => date('Y-m-d',strtotime($value['created_at'])),
                    'type' => 'investment'

                ]
            );
        }
    }
    // else{
    // $data = [];
    // }
    $conversion = Conversion::where('user_id', $request->user()->id)->orderBy('id', 'DESC');
    if (isset($request->units_convertable) && $request->units_convertable == 1) {
        $conversion = $conversion->whereHas('fund', function($q) {
            $q->whereHas('amc', function($qry) {
                $qry->where('units_convertable', 1);
            });
        });
    }

    $conversion = $conversion->with('fund.amc', 'redemption', 'children')->where(['status' => 1, 'is_converted' => 0])->get();
    $my_conversion = $conversion->toArray();
    
    foreach ($my_conversion as $key => $value) {
        /*Investment*/
        if ($value['redemption']) {
            foreach ($value['redemption'] as $key2 => $value2) {
                if($value2['status'] == 1 || $value2['status'] == 0){
                    unset($my_conversion[$key]);
                    continue;
                }
            }
        }
        if ($value['children']) {
            foreach ($value['children'] as $key3 => $value3) {
                if($value3['status'] == 1 || $value3['status'] == 0){
                    unset($my_conversion[$key]);
                    continue;
                }
            }
        }
    }
    
    foreach (array_values($my_conversion) as $key => $value) {
        if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
        $total_return   = "+".number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
        else
        $total_return   = number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
        array_push($data_2, [
                'id' => $value['id'],
                'amount' => number_format($value['amount']),
                'status' => $value['status'],
                'transaction_id' => $value['transaction_id'],
                'logo_link' => $value['fund']['amc']['logo_link'],
                'fund_name' => $value['fund']['fund_name'],
                'fund_image' => $value['fund']['fund_image'],
                'fund_id' => $value['fund']['id'],
                'return_amount' => number_format($value['unit']*$value['fund']['nav']),
                'total_return' => $total_return,
                'investment_date' => date('Y-m-d',strtotime($value['created_at'])),
                'type' => 'conversion'

            ]
        );
    }
    if (isset($request->units_convertable) && $request->units_convertable != 1) {
        $dividend = Dividend::where('user_id', $request->user()->id)->orderBy('id', 'DESC');
        $dividend = $dividend->with('fund.amc', 'redemption')->where(['status' => 1])->get();
        $my_dividend = $dividend->toArray();
        
        foreach ($my_dividend as $key => $value) {
            /*Investment*/
            if ($value['redemption']) {
                foreach ($value['redemption'] as $key2 => $value2) {
                    if($value2['status'] == 1 || $value2['status'] == 0){
                        dd("there");
                        unset($my_dividend[$key]);
                        continue;
                    }
                }
            }
        }
        
        foreach (array_values($my_dividend) as $key => $value) {
            if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
            $total_return   = "+".number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
            else
            $total_return   = number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
            array_push($data_3, [
                    'id' => $value['id'],
                    'amount' => number_format($value['amount']),
                    'status' => $value['status'],
                    'transaction_id' => $value['transaction_id'],
                    'logo_link' => $value['fund']['amc']['logo_link'],
                    'fund_name' => $value['fund']['fund_name'],
                    'fund_image' => $value['fund']['fund_image'],
                    'fund_id' => $value['fund']['id'],
                    'return_amount' => number_format($value['unit']*$value['fund']['nav']),
                    'total_return' => $total_return,
                    'investment_date' => date('Y-m-d',strtotime($value['created_at'])),
                    'type' => 'dividend'

                ]
            );
        }
    }
    $data = array_merge($data_1, $data_2, $data_3);
    array_multisort(array_column($data, 'investment_date'), SORT_DESC, $data);

        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }

    public function showSingleInvestments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            // 'type' => 'required|in:investment,conversion',
        ]);

        if ($request->type == 'conversion') {
            $data = Conversion::with('fund.amc', 'redemption')->find($request->id);
            $investment=Conversion::select(DB::raw('created_at'))->find($request->id);
            $investment_date=date('d M Y',strtotime($data->created_at));
        } elseif ($request->type == 'dividend') {
            $data = Dividend::with('fund.amc', 'redemption')->find($request->id);
            $investment=Dividend::select(DB::raw('created_at'))->find($request->id);
            $investment_date=date('d M Y',strtotime($data->created_at));
        } else {
            $data = Investment::with('fund.amc', 'redemption')->find($request->id);
            $investment=Investment::select(DB::raw('created_at'))->find($request->id);
            $investment_date=date('d M Y',strtotime($data->created_at));
        }
        $total_return=($data->unit*$data->fund->nav)-$data->amount;
        $data->amount=number_format($data->amount);
        if($total_return>=0)
        $total_return="+".number_format($total_return);
        else
        $total_return=number_format($total_return);
        $return_amount=$data->unit*$data->fund->nav;
        $user = CustAccountDetail::where('user_id', $data->user_id)->first();
        return response()->json([
            'status' => true,
            'data'   => $data,
            'investment_date'=>$investment_date,
            'created_at'=>date("Y-m-d h:i:s a",strtotime($investment->created_at)),
            'return_amount'=>number_format($return_amount),
            'total_return'=>$total_return,
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
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        // $investment_amounts = Investment::where('user_id',$request->user()->id)->where('fund_id',$request->fund_id)->whereIn('status',[0,1])->where(function($builder){
        //     $builder->whereDoesntHave('redemption', function ($query){
        //        $query->whereIn('status',[0,1]);
        //     })
        //     ->whereDoesntHave('conversions', function ($query){
        //         $query->whereIn('status',[0,1]);
        //      });
        // })->pluck('amount');
        $fund = Fund::with('amc', 'additional_details')->where('id',$request->fund_id)->first();
        $investment_amounts = Investment::where('user_id',$request->user()->id)->whereHas('fund', function ($q)use ($fund){
            $q->where('amc_id',$fund->amc_id);
          })->whereIn('status',[0,1])->where(function($builder){
            $builder->whereDoesntHave('redemption', function ($query){
               $query->whereIn('status',[0,1]);
            })
            ->whereDoesntHave('conversions', function ($query){
                $query->whereIn('status',[0,1]);
             });
        })->pluck('amount');
        // $redeem_investments = Investment::where('user_id',$request->user()->id)->where('fund_id',$request->fund_id)->whereHas('redemption', function ($q){ 
        //     $q->whereIn('status',[0,1]);
        //   })->pluck('amount');
        $amc_profile=AmcCustProfile::where('user_id',$request->user()->id)->where('amc_id',$fund->amc_id)->first();
        if(isset($amc_profile))
        $max_investment_amount=$amc_profile->accounttype->max_investment_amount;
        else
        $max_investment_amount=AccountType::where('id',1)->pluck('max_investment_amount')[0];
        $conversion_amounts = Conversion::where('user_id',$request->user()->id)->whereHas('fund', function ($q)use ($fund){
            $q->where('amc_id',$fund->amc_id);
          })->whereIn('status',[0,1])->where(function($builder){
            $builder->whereDoesntHave('redemption', function ($query){
               $query->whereIn('status',[0,1]);
            })
            ->whereDoesntHave('children', function ($query){
                $query->whereIn('status',[0,1]);
             });
        })->pluck('amount');
        $total_investment_amount=0.0;
        $total_conversion_amount=0.0;
        foreach($investment_amounts as $investment_amount)
        {
            $total_investment_amount+=$investment_amount;
        }
        foreach($conversion_amounts as $conversion_amount)
        {
            $total_conversion_amount+=$conversion_amount;
        }
        $total_amount=$total_conversion_amount+$total_investment_amount;
        $fund = Fund::with('amc', 'additional_details')->where('id',$request->fund_id)->first();
        $funds_details=$fund->additional_details;
        $risk_profile_data=RiskProfile::where('type',$funds_details->profile_risk)->get();

        return response()->json([
            'status' => true,
            // 'risk_profile_data' => $risk_profile_data[0]??"",
            'min_transaction_amount'=>$fund->min_transaction_amount??0,
            'max_transaction_amount'=>$fund->max_transaction_amount??0,
            'max_investment_amount'=>$max_investment_amount,
            'total_investment_amount' => $total_amount??0
        ], 200);
    }
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'fund_id' => 'required',
            'nav' => 'required',
            'pay_method' => 'required|in:ibft,nift',
            'amount' => 'required|integer',
            'image' => 'required_if:pay_method,==,ibft',
            'rrn' => 'required_if:pay_method,==,nift',
            'transaction_status' => 'required_if:pay_method,==,nift',
            'transaction_time' => 'required_if:pay_method,==,nift',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user_id = $request->user()->id;
        $fund = Fund::with('fund_bank','additional_details', 'amc')->where('id',$request->fund_id)->first();
        $custAmcProfile = AmcCustProfile::where('amc_id',$fund->amc_id)->where('user_id',$user_id)->first();

        if(!empty($custAmcProfile) && $custAmcProfile->status == 2)
        {
            $amc_profile_check_error_message = [
                'error' => ["Since you already have an account at ".$fund->amc->entity_name." outside of YPay, you won't be able to make investment here. You are free to choose funds from any other company. For any questions or concerns, write to us at operations@ypayfinancial.com"]
            ];
            return response()->json(['status' => 'error', 'errors' => $amc_profile_check_error_message], 422);
        }

        // $funds_details=FundsAdditionalDetail::where('fund_id',$request->fund_id)->first();
        $risk_profile_data=RiskProfile::where('type',$fund->additional_details->profile_risk)->first();

        // $amount_check_error_message = [
        //     'amount' => ["For this fund, your investment amount should be to be between Rs. ".number_format($risk_profile_data->min_transaction_amount)." and Rs. ".number_format($risk_profile_data->max_transaction_amount)."."]
        // ];
        // if ($request->amount > $risk_profile_data->max_transaction_amount || $request->amount < $risk_profile_data->min_transaction_amount ) {
        //     return response()->json(['status' => 'error', 'errors' => $amount_check_error_message], 422);
        // }
        $amount_check_error_message = [
            'amount' => ["For this fund, your investment amount should be to be between Rs. ".number_format($fund->min_transaction_amount)." and Rs. ".number_format($fund->max_transaction_amount)."."]
        ];
        if ($request->amount > $fund->max_transaction_amount || $request->amount < $fund->min_transaction_amount ) {
            return response()->json(['status' => 'error', 'errors' => $amount_check_error_message], 422);
        }
        if(strtolower($request->pay_method)!="nift")
        {
        // $validator = Validator::make($request->all(), [
        //     'image' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        // }

        $folderPath     = "storage/uploads/investment/";
        $image_parts    = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $filename       = 'ibft_' . time();
        $file           = $filename . '.'.$image_type;
        //file_put_contents($file, $image_base64);
        $path               = "investments/".$file;
        $image_url           = s3ImageUploadApi($request->image, $path);

        /* calculate units */
        }
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $amc_fund_data=AmcFund::where('ypay_fund_id',$request->fund_id)->get();
        $investment                 = new Investment;
        $investment->transaction_id = $string;
        $investment->user_id        = $user_id;
        $investment->fund_id        = $request->fund_id;
        $investment->amc_fund_id    =$amc_fund_data[0]->id??'';
        $investment->amount         = $request->amount;
        $investment->pay_method     = strtolower($request->pay_method);
        $investment->account_number = $custAmcProfile->account_number??"";
        if(isset($file))
        $investment->image          = $image_url??"";
        $investment->rrn            = $request->rrn;
        $investment->transaction_status=$request->transaction_status;
        $investment->transaction_time=$request->transaction_time;
        // $investment->nav            = $request->nav;
        // $investment->unit           = round($request->amount/$request->nav, 2);
        $investment->save();

        $user = User::where('id', $user_id)->first();
        

        if(empty($custAmcProfile))
        {
            $amcCustProfiles                        = new AmcCustProfile();
            $amcCustProfiles->amc_id                = $fund->amc_id;
            $amcCustProfiles->user_id               = $user_id;
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
          $data = ['message' => Config::get('messages.investment_request_received'), 'image' => $fund->fund_image];
          sendNotification($user->fcm_token, $data, $user_id, 'Kudos on making smart decisions! 💡🚀');

        return response()->json([
            'status' => true,
        ], 200);
    }
    public function addRedemption(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'fund_id' => 'required',
            'amount' => 'required|integer',
            'reason' => 'required',
            // 'type' => 'required|in:investment,conversion',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }

        $redemption            = new Redemption;
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $redemption->transaction_id=$string;

        if ($request->type == 'conversion') {
            $investment = Conversion::where('id',$request->id)->first();
            $redemption->conversion_id = $request->id;
            $type = 'conversion';
        } elseif ($request->type == 'dividend') {
            $investment = Dividend::where('id',$request->id)->first();
            $redemption->dividend_id = $request->id;
            $type = 'dividend';
        } else {
            $investment = Investment::where('id',$request->id)->first();
            $redemption->invest_id = $request->id;
            $type = 'investment';
        }
        $user_id = $request->user()->id;
        $redemption->amount    = $request->amount;
        $redemption->redeem_amount=(float) $request->amount;
        $redemption->redeem_units=(float) $investment->unit;
        $redemption->redeem_by= "Unit";
        $redemption->reason = $request->reason;
        $redemption->type = $type;
        $redemption->save();

        $user = User::where('id', $user_id)->first();
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
          sendNotification($user->fcm_token, $data, $user_id, 'Good news! Your redemption request has been submitted 🙌');


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
        $user_id = $request->user()->id;
        $investment      = Investment::where('user_id', $user_id)->with('fund','redemption')->orderBy('id', 'desc')->get();
        $redemption_data = [];
        $investment_data = [];
        foreach($investment as $key => $value)
        {
            $investment_data[$key]['invest_id'] = $value['id'];
            $investment_data[$key]['date']      = $value['created_at']->format('Y-m-d H:i:s');
            $investment_data[$key]['type']      = 'Investment';
            $investment_data[$key]['funds']     = $value['fund']['fund_name'];
            $investment_data[$key]['to_fund']   = '';
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
                    $redemption_data[$key]['to_fund']   = '';
                    $redemption_data[$key]['amount']    = isset($value2['redeem_amount']) ? number_format($value2['redeem_amount']) : number_format($value2['amount']);
                    $redemption_data[$key]['status']    = $value2['status'];
                    $redemption_data[$key]['transaction_id']    = $value2['transaction_id'];   
                    $redemption_data[$key]['rejected_reason']    = $value2['rejected_reason'];   
                }
            }
        }
        $conversion      = Conversion::where('user_id', $user_id)->with('fund','redemption','investment.fund','parent.fund')->orderBy('id', 'desc')->get();
        $redemption_data_2 = [];
        $conversion_data = [];
        foreach($conversion as $key => $value)
        {
            if ($value['type'] == "investment") {
                $fund = $value['investment']['fund']['fund_name'];
            } else {
                $fund = $value['parent']['fund']['fund_name'];
            }
            $conversion_data[$key]['invest_id'] = $value['id'];
            $conversion_data[$key]['date']      = $value['created_at']->format('Y-m-d H:i:s');
            $conversion_data[$key]['type']      = 'conversion';
            $conversion_data[$key]['funds']     = $fund;
            $conversion_data[$key]['to_fund']   = $value['fund']['fund_name'];
            $conversion_data[$key]['amount']    = number_format($value['amount']);
            $conversion_data[$key]['status']    = $value['status'];  
            $conversion_data[$key]['transaction_id']    = $value['transaction_id']; 
            $conversion_data[$key]['rejected_reason']    = $value['rejected_reason'];  
            if($value['redemption'])
            {
                foreach($value['redemption'] as $key2 => $value2){
                    $redemption_data_2[$key]['invest_id'] = $value['id'];
                    $redemption_data_2[$key]['date']      = $value2['created_at']->format('Y-m-d H:i:s');
                    $redemption_data_2[$key]['type']      = 'Redemption';
                    $redemption_data_2[$key]['funds']     = $value['fund']['fund_name'];
                    $redemption_data_2[$key]['to_fund']     = '';
                    $redemption_data_2[$key]['amount']    = isset($value2['redeem_amount']) ? number_format($value2['redeem_amount']) : number_format($value2['amount']);
                    $redemption_data_2[$key]['status']    = $value2['status'];
                    $redemption_data_2[$key]['transaction_id']    = $value2['transaction_id'];   
                    $redemption_data_2[$key]['rejected_reason']    = $value2['rejected_reason'];   
                }
            }
        }
        $data = array_merge($investment_data, $redemption_data, $redemption_data_2, $conversion_data);
        array_multisort(array_column($data, 'date'), SORT_DESC, $data);
        $tableData = [];
        foreach ($data as $key => $value) {
            $tableData[$key]['date'] = date('d M,Y',strtotime($value['date']));
            $tableData[$key]['transaction_id'] = $value['transaction_id'];
            $tableData[$key]['type'] = $value['type'];
            $tableData[$key]['funds'] = $value['funds'];
            $tableData[$key]['to_fund'] = $value['to_fund'];
            $tableData[$key]['amount'] = 'Rs. '.$value['amount'];
            if ($value['status'] == 0 || $value['status'] == 3) {
                $status = "Pending";
            } elseif ($value['status'] == 1) {
                $status = "Successful";
            } else {
                $status = "Rejected";
            }
            $tableData[$key]['status'] = $status;
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
        $validator = Validator::make($request->all(), [
            'amc_id' => 'required',
        ]);
        $user_id = $request->user()->id;
        $checkProfile = AmcCustProfile::where('amc_id',$request->amc_id)->where('user_id',$user_id)->pluck('status')->first();

        return response()->json([
            'status' => true,
            'profileStatus' => $checkProfile,
        ], 200);
    }

    public function addAmcProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'amc_id' => 'required',
        ]);
        $user_id = $request->user()->id;
        $custAmcProfile = AmcCustProfile::where('amc_id',$request->amc_id)->where('user_id',$user_id)->first();
        
        if (empty($custAmcProfile)) {
            $amcCustProfiles = new AmcCustProfile();
            $amcCustProfiles->amc_id = $request->amc_id;
            $amcCustProfiles->user_id = $user_id;
            $amcCustProfiles->status = 2;
            $amcCustProfiles->save();
        } else {
            $error_message = [
                'error' => ['AMC customer profle already exists.']
            ];
            return response()->json(['status' => 'error', 'errors' => $error_message], 422);
        }

        return response()->json([
            'status' => true,
        ], 200);
    }

}