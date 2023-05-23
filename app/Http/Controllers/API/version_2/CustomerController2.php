<?php

namespace App\Http\Controllers\API\version_2;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Configurations;
use App\Models\City;
use App\Models\Fund;
use App\Models\Conversion;
use App\Models\Otp;
use App\Models\Amc;
use App\Models\Goal;
use App\Models\Notification;
use App\Models\RiskProfileRank;
use App\Models\HighRiskResponse;
use App\Models\Options;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Question;
use App\Models\CustBasicDetail;
use App\Models\AmcCity;
use App\Models\AmcCountry;
use App\Models\AmcOccupation;
use App\Models\AmcSourceofIncome;
use App\Models\AmcBank;
use App\Models\CustBankDetail;
use App\Models\CustCnicDetail;
use App\Models\CustomerGoal;
use App\Models\Investment;
use App\Models\Redemption;
use App\Models\Country;
use App\Models\Occupation;
use App\Models\Bank;
use App\Models\SourcesofIncome;
use App\Models\CustAccountDetail;
use App\Models\AmcCustProfile;
use App\Models\FundAsset;
use App\Models\FundAssetAllocation;
use App\Models\FundHolding;
use App\Models\FundsAdditionalDetail;
use App\Models\FundsBankDetail;
use App\Models\OTPCount;

use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use function App\Libraries\Helpers\s3ImageUploadApi;
use Mail;

class CustomerController2 extends Controller
{
    public function risk_profile(Request $request){
        $validator = Validator::make($request->all(), [
                'user_id' => 'required'
            ]
            ,
        [
            'user_id.required' => 'This field is required!',
        ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user_id=$request->user_id;
        $user=User::with('cust_cnic_detail')->where('id',$user_id)->first();
        $cust_account=CustAccountDetail::where('user_id',$user_id)->first();
        if(isset($cust_account))
        {
        $cust_account->risk_profile_status=1;
        $cust_account->save();
        }
        $ypay_operations_email=Configurations::where('name','YPAY Operations Email')->first();
        $url = 'https://networks.ypayfinancial.com/api/mailv1/risk_profile_request.php';
        $body = ['email' => $ypay_operations_email??'', 'cnic'=>$user->cust_cnic_detail->cnic_number??''];
        sendEmail($body,$url);
        $data = ['message' => 'Your request is sent for analysis of your risk profile. You will receive a notification once the process is completed. Thank you. ', 'image' => ''];
        sendNotification($user->fcm_token, $data, $user->id, 'risk_profile_in_process');
        return response()->json([
            'status' => true,
        ], 200);
    }
    public function profile(Request $request){
        $validator = Validator::make($request->all(), [
                'cust_basic_details.*' => 'required',
                'cust_cnic_details.*' => 'required',
                'cust_bank_details.*' => 'required',
            ]
            ,
        [
            'cust_basic_details.*.required' => 'This field is required!',
            'cust_cnic_details.*.required' => 'This field is required!',
            'cust_bank_details.*.required' => 'This field is required!',
        ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        if (!$request->verify) {return response()->json(['status' => true], 200);}
        try {
            $cust_basic_details = $request['cust_basic_details'];
            $city_id=$cust_basic_details['city'];
            $amc_city_id=AmcCity::where('ypay_city_id',$city_id)->pluck('amc_city_code');
            $country_id=$cust_basic_details['country'];
            $amc_country_id=AmcCountry::where('ypay_country_id',$country_id)->pluck('amc_country_id');
            $occupation_id=$cust_basic_details['occupation'];
            $amc_occupation_id=AmcOccupation::where('ypay_occupation_id',$occupation_id)->pluck('amc_occupation_id');
            $income_source_id=$cust_basic_details['income_source'];
            $amc_income_source_id=AmcSourceofIncome::where('ypay_source_of_income_id',$income_source_id)->pluck('amc_source_of_income_id');
            $bank_id=$cust_basic_details['bank'];
            $amc_bank_id=AmcBank::where('ypay_bank_id',$bank_id)->pluck('amc_bank_id');
            $cust_basic_details['amc_city_id']=$amc_city_id[0]??'';
            $cust_basic_details['amc_country_id']=$amc_country_id[0]??'';
            $cust_basic_details['amc_occupation_id']=$amc_occupation_id[0]??'';
            $cust_basic_details['amc_income_source_id']=$amc_income_source_id[0]??'';
            $cust_basic_details['amc_bank_id']=$amc_bank_id[0]??'';
            $cust_basic_details['nominee_name'] = $request->nominee_name;
            $cust_basic_details['nominee_cnic'] = $request->nominee_cnic;
            CustBasicDetail::where('user_id', $request->user_id)->delete();
            CustBasicDetail::insertGetId($cust_basic_details);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
        }
        //CNIC//
        try {
           CustCnicDetail::where('user_id', $request->user_id)->delete();
           $cnic_details = $request['cust_cnic_details'];
           $cnic_image   = ['cnic_front' => $cnic_details['cnic_front'], 'cnic_back' => $cnic_details['cnic_back'], 'income' => $request->income];
           foreach ($cnic_image as $key => $value) {
            if($value)
            {
            //  $folderPath     = "storage/uploads/cnic/";
             $image_parts    = explode(";base64,", $value);
             $image_type_aux = explode("image/", $image_parts[0]);
             $image_type     = $image_type_aux[1];
             $image_base64   = base64_decode($image_parts[1]);
             $filename       = $key . '_' . time();
             $file           = $filename . '.'.$image_type;
            //  $file           = $folderPath . $filename . '.'.$image_type;
             $path               = "cnic/".$file;
             $image_url           = s3ImageUploadApi($value, $path);
            //  file_put_contents($file, $image_base64);
             $cnic_details[$key] = $image_url;
         }
        }
        $cnic_details['issue_date']  = date('Y-m-d', strtotime($cnic_details['issue_date']));
        $cnic_details['expiry_date'] = date('Y-m-d', strtotime($cnic_details['expiry_date']));
        CustCnicDetail::insertGetId($cnic_details);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
        }
         //BANK//
        try {
            CustBankDetail::where('user_id', $request->user_id)->delete();
            $cust_bank_details           = $request['cust_bank_details'];
            $amc_bank_name=AmcBank::where('ypay_bank_id',$cust_basic_details['bank'])->pluck('amc_bank_name');
            $cust_bank_details['bank']   = $amc_bank_name[0]??'';
            // $cust_bank_details['branch'] = $request->branch;
            CustBankDetail::insertGetId($cust_bank_details);
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
        }
        //Acccount Details//
        try {
            CustAccountDetail::where('user_id', $request->user_id)->update(array('status' => 0));
        } catch (Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
        }

        $user = User::where('id',$request->user_id)->first();
        $user->phone_no = $request->phone_no;
        $user->save();

        // $fundAmcId = Fund::where('id',$request->fundId)->pluck('amc_id')->first();
        // $amcCustProfiles                = new AmcCustProfile();
        // $amcCustProfiles->amc_id        = $fundAmcId;
        // $amcCustProfiles->user_id       = $request->user_id;
        // $amcCustProfiles->status        = -1;
        // $amcCustProfiles->save();

        // email notificaion 
        $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_mail.php';
        $body = [
                'email'             => $user->email, 
                'name'              => $user->full_name,
                'father_name'       => $user->cust_basic_detail->father_name, 
                'iban'              => $user->cust_bank_detail->iban, 
                'cnic_issue_date'   => $cnic_details['issue_date'],
                'cnic_expiry_date'  => $cnic_details['expiry_date'],
                'address'           => $user->cust_basic_detail->current_address
            ];
        
        sendEmail($body,$url);

        $data = ['message' => 'Make sure to verify your profile to unlock funds and start investing!', 'image' => ''];
        sendNotification($user->fcm_token, $data, $user->id, 'profile_pending');

        // Mail::send('mail.profileCreated', ['name'=> $user->full_name], function($message) use ($user) {
        //       $message->to($user->email, $user->full_name)->subject('Profile Created');
        //       $message->from('hello@ypayfinancial.com', 'YPay');
        //   });

        return response()->json([
            'status' => true,
        ], 200);
    }
    public function high_risk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'RiskProfilePersonalData.*' => 'required',
            'RiskProfileInvestmentData.*' => 'required',
            'RiskProfileRiskData.*' => 'required',
            'RiskProfileAttitudeData.*' => 'required',
            'user_id'=> 'required',
        ]
        ,
        [
            'RiskProfilePersonalData.*.required' => 'This field is required!',
            'RiskProfileInvestmentData.*.required' => 'This field is required!',
            'RiskProfileRiskData.*.required' => 'This field is required!',
            'RiskProfileAttitudeData.*.required' => 'This field is required!',
            'user_id.required' => 'This field is required!',
        ]
    );
    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
    }
        $RiskProfilePersonalData = $request['RiskProfilePersonalData'];
        $RiskProfileInvestmentData = $request['RiskProfileInvestmentData'];
        $RiskProfileRiskData = $request['RiskProfileRiskData'];
        $RiskProfileAttitudeData = $request['RiskProfileAttitudeData'];
        $riskprofilepersonaldatapoints=0;
        $riskprofileinvestmentdatapoints=0;
        $riskprofileriskdatapoints=0;
        $riskprofileattitudedatapoints=0;
        $user_id=$request['user_id'];
        $option_ids=[];
        for($i=0;$i<count($RiskProfilePersonalData);$i++)
        {
            $question=Question::where('id',$RiskProfilePersonalData['question'.$i+1]['question_id'])->first();
            $riskprofilepersonaldatapoints+=(int)$RiskProfilePersonalData['question'.$i+1]['points']*(float)$question->weightage;
            $option_ids[]=$RiskProfilePersonalData['question'.$i+1]['id'];
        }
        for($i=0;$i<count($RiskProfileInvestmentData);$i++)
        {
            $question=Question::where('id',$RiskProfileInvestmentData['question'.$i+1]['question_id'])->first();
            $riskprofileinvestmentdatapoints+=(int)$RiskProfileInvestmentData['question'.$i+1]['points']*(float)$question->weightage;
            $option_ids[]=$RiskProfileInvestmentData['question'.$i+1]['id'];
        }
        for($i=0;$i<count($RiskProfileRiskData);$i++)
        {
            $question=Question::where('id',$RiskProfileRiskData['question'.$i+1]['question_id'])->first();
            $riskprofileriskdatapoints+=(int)$RiskProfileRiskData['question'.$i+1]['points']*(float)$question->weightage;
            $option_ids[]=$RiskProfileRiskData['question'.$i+1]['id'];
        }
        for($i=0;$i<count($RiskProfileAttitudeData);$i++)
        {
            $question=Question::where('id',$RiskProfileAttitudeData['question'.$i+1]['question_id'])->first();
            $riskprofileattitudedatapoints+=(int)$RiskProfileAttitudeData['question'.$i+1]['points']*(float)$question->weightage;
            $option_ids[]=$RiskProfileAttitudeData['question'.$i+1]['id'];
        }
        $total_score=$riskprofilepersonaldatapoints+$riskprofileinvestmentdatapoints+$riskprofileriskdatapoints+$riskprofileattitudedatapoints;
        $riskprofileranks=RiskProfileRank::all();
        foreach($riskprofileranks as $rank)
        {
            if($total_score>=$rank->start_range && $total_score<$rank->end_range)
            {
                $_rank=$rank->rank;
                $message=$rank->message;
                $cust_account=CustAccountDetail::where('user_id',$user_id)->first();
                if(isset($cust_account))
                {
                $cust_account->risk_profile_status=$rank->risk_profile_status;
                $cust_account->save();
                $high_risk_response=HighRiskResponse::where('user_id',$user_id)->first();
                if(!isset($high_risk_response))
                $high_risk_response=new HighRiskResponse;
                $high_risk_response->user_id=$user_id;
                $high_risk_response->rank=$_rank;
                $message=str_replace("&#039;","'",$message);
                $message=str_replace("&amp;#039;","'",$message);
                $high_risk_response->message=$message;
                $high_risk_response->risk_profile_status=$cust_account->risk_profile_status;
                $high_risk_response->total_score=$total_score;
                $high_risk_response->option_ids=$option_ids;
                $high_risk_response->save();
                }
                $message=str_replace("&#039;","'",$message);
                $message=str_replace("&amp;#039;","'",$message);
                $risk_profile_status=$cust_account->risk_profile_status??'';
                return response()->json(['status' => true,'rank'=> $_rank,'message'=> $message,'risk_profile_status'=>$risk_profile_status], 200);
            }
        }
    }
    public function get_profile_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]
        ,
    [
        'user_id.required' => 'This field is required!',
    ]
    );
    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
    }
    $user_image=UserImage::where('user_id',$request->user_id)->first();
    if(isset($user_image))
    return response()->json(['status' => true,'path'=> $user_image->path], 200);
    else
    {
    return response()->json([
        'status' => 'error',
        'errors' => ['user_id' => 'User Image Not Found']
    ], 401);
    }
    }
    public function store_profile_image(Request $request)
    {
       $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'image' => 'required',
            ]
            ,
        [
            'user_id.required' => 'This field is required!',
            'image.required' => 'This field is required!',
        ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user_image=UserImage::where('user_id',$request->user_id)->first();
        //$folderPath     = "storage/uploads/profile/";
        $image_parts    = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $filename       = 'profile_image_' . time();
        $file           = $filename . '.'.$image_type;
        $path               = "profile_images/".$file;
        $image_url           = s3ImageUploadApi($request->image, $path);
        // $file           = $folderPath . $filename . '.'.$image_type;
        // file_put_contents($file, $image_base64);

        if(isset($user_image))
        {
            $user_image->path=$image_url;
            $user_image->save();
        }
        else
        {
        $user_image=new UserImage;
        $user_image->user_id=$request->user_id;
        $user_image->path=$image_url;
        $user_image->save();
        }
       return response()->json(['status' => true,'message'=> "Image Stored Successfully"], 200);
    }
    public function get_risk_profile_questions()
    {
       $questions=Question::select('id','question','weightage','cat_id')->get();
       foreach($questions as $question)
       {
        $options=Options::where('question_id',$question->id)->select('id','_option','question_id','points')->get();
        $question->options=$options->toArray();
       }
       return response()->json(['status' => true,'questions'=> $questions], 200);
    }
    public function delete_users_profile(Request $request)
    {
    $users_ids=[];
    $validator= Validator::make($request->all(),[
        'users_emails.*' => 'required|array'
    ]
    ,
    [
        'users_emails.*.required' => 'This field is required!'
    ]
    );
    $data=$request->all();
    $users_emails=$data['users_emails'];
    $users_ids=User::whereIn('email',$users_emails)->pluck('id');
    $funds_ids=Fund::whereIn('user_id', $users_ids)->pluck('id');
    $Investments_ids=Investment::whereIn('user_id', $users_ids)->pluck('id');
    CustBankDetail::whereIn('user_id', $users_ids)->delete();
    CustCnicDetail::whereIn('user_id', $users_ids)->delete();
    CustAccountDetail::whereIn('user_id', $users_ids)->delete();
    CustBasicDetail::whereIn('user_id', $users_ids)->delete();
    Investment::whereIn('user_id', $users_ids)->delete();
    Amc::whereIn('user_id', $users_ids)->delete();
    Fund::whereIn('user_id', $users_ids)->delete();
    Goal::whereIn('user_id', $users_ids)->delete();
    CustomerGoal::whereIn('user_id', $users_ids)->delete();
    FundAsset::whereIn('fund_id', $funds_ids)->delete();
    FundAssetAllocation::whereIn('fund_id', $funds_ids)->delete();
    FundHolding::whereIn('fund_id', $funds_ids)->delete();
    FundsAdditionalDetail::whereIn('fund_id', $funds_ids)->delete();
    FundsBankDetail::whereIn('fund_id', $funds_ids)->delete();
    Redemption::whereIn('invest_id', $Investments_ids)->delete();
    AmcCustProfile::whereIn('user_id', $users_ids)->delete();
    Notification::whereIn('user_id', $users_ids)->delete();
    Conversion::whereIn('user_id', $users_ids)->delete();
    HighRiskResponse::whereIn('user_id', $users_ids)->delete();
    User::whereIn('email',$users_emails)->delete();
    return response()->json(['status' => true], 200);
    }
    public function getOtpMobile(Request $request)
    {
        $access_key = Configurations::where('name','Access Key')->first();
        $access_key = $access_key->value;
        $validator = Validator::make($request->all(), [
            'phone_no'              => 'required',
            'type'  => 'required',
            'access_key' => 'required|in:'.$access_key,
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $phoneNo = $request->phone_no;

        $ip = $request->ip();
        $otp_send_count=OTPCount::where('phone_number','92'.$phoneNo)->first();
        if(!isset($otp_send_count))
        { 
            $otp_send_count= new OTPCount();
        }

        $user=User::where('phone_no','+92'.$phoneNo)->first();
        if(isset($user))
        {
            return response()->json([
                'status' => 'error',
                'errors' => ['error' => 'Phone Number Already Taken']
            ], 402);
        }
        if($phoneNo[0] == 0){
             $phoneNo = '92'.substr($phoneNo, 1);
        }
        $minutes = 20;
        if (isset($otp_send_count->block_at)) {
            $minutes = Carbon::createFromFormat('Y-m-d H:i:s', $otp_send_count->block_at)->diffInMinutes(Carbon::now());
            $minutes = 20 - $minutes;
            if ($minutes <= 0) {
                $otp_send_count->count = 0;
                $otp_send_count->block_at = null;
                $otp_send_count->save();
            }
        }
        
        if($otp_send_count->count<3)
        {
            if($phoneNo[0] == 3){
                $phoneNo = '92'.($phoneNo);
            }
            if($request->type=="sms")
            {
            $response = Http::post('https://networks.ypayfinancial.com/api/verify/sendotp.php', [
                'to' => '+'.$phoneNo, 
            ]);
            }
            else
            {
                $response = Http::post('https://networks.ypayfinancial.com/api/verify/wa-sendotp.php', [
                    'to' => '+'.$phoneNo, 
                ]);
            }
            // $otp_send_count->ip=$ip;
            $otp_send_count->phone_number=$phoneNo;
            $otp_send_count->count=(int)$otp_send_count->count+1;
            $otp_send_count->block_at = null;
            $otp_send_count->save();
        }
        else
        {
            if (!isset($otp_send_count->block_at)) {
                $otp_send_count->block_at = Carbon::now();
                $otp_send_count->save();
            }
            return response()->json([
                'status' => 'error',
                'errors' => ['error' => 'You have exhausted your OTP generation limit. Please try again in '.$minutes.' minutes.']
            ], 401);
        }
        return response()->json([ 'status' => true ], 200);
    }

    public function otpVerifyMobile(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'code' => 'required'
        ], [
            'code.required' => 'The OTP field is required.',
        ]);

          if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        
        $phoneNo = $request->phone_no;
        if($phoneNo[0] == 0){
             $phoneNo = '92'.substr($phoneNo, 1);
        }
        if($phoneNo[0] == 3){
             $phoneNo = '92'.($phoneNo);
        }
        $response = Http::post('https://networks.ypayfinancial.com/api/verify/checkotp.php', [
            'to'   => '+'.$phoneNo,
            'code' => $request->code
        ]);

        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['status'] == 'approved') {
            return response()->json(['status' => true], 200);
        }

        return response()->json([ 'status' => 'error', 'errors' => ['code' => 'INVALID OTP'] ], 402);
    }

    public function states()
    {
        $states = City::groupBy('state')->get()->pluck('state');
        return response()->json(['status' => true,'states'=> $states], 200);
    }

    public function cities(Request $request)
    {
        $cities = City::select('id','city')->where('state',$request->state)->where('status',1)->get();
        return response()->json(['status' => true,'cities'=> $cities], 200);
    }
    public function countries()
    {
        $countries = Country::where('status',1)->get();
        return response()->json(['status' => true,'countries'=> $countries], 200);
    }
    public function occupations()
    {
        $occupations = Occupation::where('status',1)->get();
        return response()->json(['status' => true,'occupations'=> $occupations], 200);
    }
    public function banks()
    {
        $banks = Bank::where('status',1)->get();
        return response()->json(['status' => true,'banks'=> $banks], 200);
    }
    public function income_sources()
    {
        $income_sources = SourcesofIncome::where('status',1)->get();
        return response()->json(['status' => true,'income_sources'=> $income_sources], 200);
    }
}