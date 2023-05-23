<?php

namespace App\Http\Controllers\API\version_4;

use App\Models\User;
use App\Models\CustAccountDetail;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\Notification;
use DateTime;
use App\Models\Configurations;
use App\Models\Insight;
use App\Models\InsightTag;
use App\Models\ChangeRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;
use App\Models\UserFcm;
use Mail;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\userInvestmentSum;
use function App\Libraries\Helpers\sendEmail;
use Auth;

class AuthController4 extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
        //     // 'email'    => 'required|email',
        //     // 'password' => 'required|string'
        //     // 'pin'                   => 'required',
        'phone_no'              => 'size:11',
        ]);
         
        // if ($validator->fails()) {
        //     return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        // }

        // $user = User::with('cust_account_detail')->where('email', $request->email)->first();

        // if (!$user || !Hash::check($request->password, @$user->password)) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => ['email' => 'Wrong Credentials']
        //     ], 401);
        // }
        if(isset($request->signature))
        {
            return response()->json([
                'status' => 'error',
                'errors' => ['error' => 'This feature is not available at the moment. Please use your phone number and PIN to access the application.']
            ], 401);
            $users=User::all();
            $_user='';
            foreach($users as $user)
            {
            if(Hash::check($request->signature, @$user->biometric_signature))
            {
                $_user=$user;
            }
            }
            if($_user=='')
            {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['error' => 'Wrong Signature']
                ], 401);
            }
            $user=$_user;
            if (!isset($user) || (isset($user->status) && $user->status == 0 || $user->type == 1)) { //in-active
                return response()->json([
                    'status' => 'error',
                    'errors' => ['error' => 'No access, kindly contact admin']
                ], 401);
            }
        }
        else
        {
        $user=User::where('phone_no','+92'.$request->phone_no)->first();
        if(!isset($user))
        {
            return response()->json([
                'status' => 'error',
                'errors' => ['error' => 'User with Phone Number Not Found']
            ], 401);
        }
        if(!Hash::check($request->pin, @$user->secret_pin))
        {
            return response()->json([
                        'status' => 'error',
                        'errors' => ['error' => 'Wrong Pin']
                    ], 402);
        }
        if ((isset($user->status) && $user->status == 0 || $user->type == 1)) { //in-active
            return response()->json([
                'status' => 'error',
                'errors' => ['error' => 'No access, kindly contact admin']
            ], 401);
        }
        }

        // $response = Http::post('https://networks.ypayfinancial.com/api/verify/sendotp.php', [
        //     'to' => $user->phone_no, 
        // ]);

        $user->tokens()->where('name', 'mobile-app')->delete();
        $token = $user->createToken('mobile-app')->accessToken;
        if (isset($request->platform)) {
            $user->platform = $request->platform;
        }
        if (isset($request->app_version)) {
            $user->app_version = $request->app_version;
        }
        $user->save();

        Auth::login($user);
        return response()->json([ 'status' => true, 'user' => $user, 'token' => $token ], 200);
    }
    public function get_conf_val(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $conf_value=Configurations::where('name',$request->name)->pluck('value')->first();
        if (!isset($conf_value)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['name' => 'Wrong Name']
            ], 401);
        }
        return response()->json([
            'status'       => 'success',
            'value' => $conf_value,
        ]);
    }
    public function update_signature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'signature' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        // return response()->json([
        //     'status' => 'error',
        //     'errors' => ['signature' => "This feature is not available at the moment. Please use your phone number and PIN to access the application."]
        // ], 401);
        $user=User::where('id',$request->user_id)->first();
        // $user->biometric_signature=Hash::make($request->signature);
        // $user->save();
        $token           = $user->createToken('mobile-app', ['all'])->plainTextToken;
        return response()->json([ 'status' => true,'message' => 'Signature Updated Successfully', 'user' => $user, 'token' => $token ], 200);
    }
    public function checkemail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user=User::where('email',$request->email)->first();
        if(isset($user))
        {
            return response()->json([
                'status' => 'error',
                'errors' => ['email' => 'Email Already Taken']
            ], 401);
        }
        return response()->json([ 'status' => true ], 200);
    }
    public function check_phone_no(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required|size:11',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user=User::where('phone_no','+92'.$request->phone_no)->first();
        if(!isset($user))
        {
            return response()->json([
                'status' => 'error',
                'errors' => ['phone_no' => 'User with Phone Number Not Found']
            ], 401);
        }
        return response()->json([ 'status' => true ], 200);
    }
    public function saveFcm(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        $userTokenExist = UserFcm::where('user_id', $user->id)->first();
        if($userTokenExist){ 
            $fcm_token_array = json_decode($userTokenExist->fcm_token);
            if(!in_array($request->fcm_token, $fcm_token_array)){
                array_push($fcm_token_array, $request->fcm_token);
                $userTokenExist->fcm_token = json_encode(($fcm_token_array));
                $userTokenExist->save();
            }
            return response()->json([ 'status' => true ], 200);
        }
            $userToken            = new UserFcm();
            $userToken->user_id   = $user->id;
            $userToken->fcm_token = json_encode([$request->fcm_token]);
            $userToken->save();
            return response()->json([ 'status' => true ], 200);
    }

    public function otpLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }

        $user = User::where(["email" => $request->email])->first();

        if (!isset($user)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['email' => 'Wrong Credentials']
            ], 401);
        }

        if (!isset($user) || (isset($user->status) && $user->status == 0) || ($user->type == 3)) { //in-active
            return response()->json([
                'status' => 'error',
                'errors' => ['email' => 'You account has been deactivated, kindly contact admin']
            ], 422);
        }

        // Generate otp
        $code = mt_rand(1000, 9999);

        // Add a row of otp in database against this user
        Otp::where('user_id', $user->id)->delete();

        $otp = new Otp;
        $otp->user_id = $user->id;
        $otp->code = $code;
        $otp->save();

        // Send the code through sms (pending api required)
        $response = Http::post('https://api.unifonic.com/rest/Messages/Send', [
            'AppSid'    => 'Xgp4dWmHcxcwtWe74wNsxhaK6xYF95',
            'Recipient' => $user->phone_no, 
            'Body'      => $code, 
            'SenderID'  => 'PMU', 
        ]);
        
        return response()->json([
            'status' => 'success',
            'otp' => $code, // to be removed later
            'otp_response' => $response->json() // to be removed later
        ]);
    }

        public function otpVerify(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'code' => 'required'
            ], [
                'code.required' => 'The OTP field is required.',
                'code.numeric' => 'The OTP field must be numeric.'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
            }

            $user = User::with('cust_account_detail')->where('email', $request->email)->whereHas('user_otp', function ($q) use ($request) {
                $q->where('code', $request->code);
            })->first();
            if(!$user) {
                return response()->json(['status' => 'error', 'errors' => ['code' => 'Invalid OTP.']], 401);
            }

            // $response = Http::post('https://networks.ypayfinancial.com/api/verify/checkotp.php', [
            //     'to'   => $user->phone_no, 
            //     'code' => $request->code
            // ]);

            // $responseBody = json_decode($response->getBody(), true);
            // if ($responseBody['status'] == 'approved') {
             // if($request->type == 2){
                return response()->json(['status' => true, 'data' => $user ], 200);
            // }
            // $user->tokens()->where('name', 'mobile-app')->delete();
            // $token = $user->createToken('mobile-app', ['all'])->plainTextToken;
            // return response()->json([ 'status' => true, 'user' => $user, 'token' => $token ], 200);
        // }

        // return response()->json([ 'status' => 'error', 'errors' => ['otp' => 'Invalid OTP.'] ], 401);
    }
    public function check_email_and_number(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user = User::where(["phone_no" => '+92'.$request->phone_no])->first();
        if(isset($user))
        {
            return response()->json(['status' => 'error', 'errors' => ['phone_no' => 'Phone No Already Taken.']], 402);
        }
        $user= User::where(["email" => $request->email])->first();
        if (!isset($user)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['email' => 'User with this Email not found']
            ], 401);
        }
        if (!isset($user) || (isset($user->status) && $user->status == 2 || $user->type == 1)) { //in-active
            return response()->json([
                'status' => 'error',
                'errors' => ['email' => 'No access, kindly contact admin']
            ], 422);
        }
        $code = mt_rand(100000, 999999);
        Otp::where('user_id', $user->id)->delete();

        $otp          = new Otp;
        $otp->user_id = $user->id;
        $otp->email = $user->email;
        $otp->code    = $code;
        $otp->save();
        $url = 'https://networks.ypayfinancial.com/api/mailv1/mobile_number_registration_request.php';
        $body = ['email' => $user->email, 'name'=>$user->full_name, 'token' => $otp->code,'phone_no'=>'+92'.$request->phone_no];
        sendEmail($body,$url);

        return response()->json([
            'status'       => 'success',
            'data'         => ['user' => $user],
            'code'         => $code
        ]);
    }
    public function forgot_pin_send_mail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user = User::where(["phone_no" => '+92'.$request->phone_no])->first();

        if (!isset($user)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['phone_no' => 'Phone Number not found']
            ], 401);
        }
            if (!isset($user) || (isset($user->status) && $user->status == 2 || $user->type == 1)) { //in-active
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => 'No access, kindly contact admin']
                ], 422);
            }

            // Generate otp
            $code = mt_rand(100000, 999999);

            // Add a row of otp in database against this user
            Otp::where('user_id', $user->id)->delete();

            $otp          = new Otp;
            $otp->user_id = $user->id;
            $otp->email = $user->email;
            $otp->code    = $code;
            $otp->save();

            // email notification 
            $url = 'https://networks.ypayfinancial.com/api/mailv1/forgot_password.php';
            $body = ['email' => $user->email, 'name'=>$user->full_name, 'token' => $otp->code];
            sendEmail($body,$url);

            return response()->json([
                'status'       => 'success',
                'data'         => ['user' => $user],
                'code'         => $code
            ]);
    }


    public function register(Request $request) {
       $validator = Validator::make($request->all(), [
        'full_name'             => 'required',
        'email'                 => 'required',
        // 'password'              => 'required|min:8',
        // 'password_confirmation' => 'required|min:8|same:password',
        'pin'                   => 'required',
        'phone_no'              => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
    }
    $check_phone_no=User::where('phone_no','+92'.$request->phone_no)->first();
    if(isset($check_phone_no))
    {
        return response()->json(['status' => 'error', 'errors' => ['error' => 'Phone No Already Taken.']], 401);
    }
    $email=$request->email;
    $check_email=User::where('email',$email)->first();
    if(isset($check_email))
    {
        return response()->json(['status' => 'error', 'errors' => ['error' => 'Email Already Exist.']], 401);
    }
    $user             = new User();
    // $users=User::all();
    // $_user='';
    // foreach($users as $user_)
    // {
    // if(Hash::check($request->signature, @$user_->biometric_signature))
    // {
    //     $_user=$user_;
    // }
    // }
    // if($_user!='')
    // {
    //     return response()->json([
    //         'status' => 'error',
    //         'errors' => ['signature' => 'Signature Already Used']
    //     ], 401);
    // }
    // if(isset($request->signature))
    // {
    //     $user->biometric_signature=Hash::make($request->signature);
    // }
    $user->full_name  = $request->full_name;
    $user->email      = $request->email;
    //$user->password   = Hash::make($request->password);
    $user->secret_pin=Hash::make($request->pin);
    $user->phone_no='+92'.$request->phone_no;
    $user->refer_code = $request->refer_code;
    $user->type       = 2;
    if (isset($request->platform)) {
        $user->platform = $request->platform;
    }
    if (isset($request->app_version)) {
        $user->app_version = $request->app_version;
    }
    $user->save();


    $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $string = substr(str_shuffle($chars), 0, 8);
    $cus_account_details          = new CustAccountDetail();
    $cus_account_details->user_id = $user->id;
    $cus_account_details->status  = -1;
    $cus_account_details->refer   = $string;
    $cus_account_details->save();

    $data = ['message' => 'Thanks for signing up at YPay, you are one step closer to financial freedom now! YPay!', 'image' => ''];
    sendNotification($request->fcm_token, $data, $user->id, 'welcome');
    $url = 'https://networks.ypayfinancial.com/api/mailv1/signup_mail.php';
    $body = ['email' => $user->email, 'name'=> $user->full_name];
    sendEmail($body,$url);

    $user            = User::whereId($user->id)->first();
    $token           = $user->createToken('mobile-app')->accessToken;
    $user->fcm_token = $request->fcm_token;
    $user->save();
    Auth::login($user);
    return response()->json([ 'status' => true, 'user' => $user, 'token' => $token ], 200);
    }
    public function change_pin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'pin' => 'required'
        ], [
            'pin.required' => 'The Pin field is required.',
            'pin.numeric' => 'The Pin field must be numeric.'
        ]);
        $user=User::where('id',$request->user_id)->first();
        $user->secret_pin=Hash::make($request->pin);
        $user->save();
        return response()->json([ 'status' => true, 'message' => 'Pin Changed Successfully' ], 200);
    }
    public function change_pin_and_number(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone_no'=>'required',
            'pin' => 'required'
        ], [
            'phone_no.required' => 'The Phone Number field is required.',
            'pin.required' => 'The Pin field is required.',
            'pin.numeric' => 'The Pin field must be numeric.'
        ]);
        $user=User::where('id',$request->user_id)->first();
        $user->phone_no='+92'.$request->phone_no;
        $user->secret_pin=Hash::make($request->pin);
        $user->save();
        return response()->json([ 'status' => true, 'message' => 'Pin and Phone Number Changed Successfully' ], 200);
    }
    public function verify_pin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'pin' => 'required'
        ], [
            'pin.required' => 'The Pin field is required.',
            'pin.numeric' => 'The Pin field must be numeric.'
        ]);
        $user=User::where('id',$request->user_id)->first();
        if(!Hash::check($request->pin, @$user->secret_pin))
        {
            return response()->json([
                        'status' => 'error',
                        'errors' => ['pin' => 'Wrong Pin']
                    ], 402);
        }
        return response()->json(['status' => true,], 200);
    }
     
    public function registerVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required'
        ], [
            'code.required' => 'The OTP field is required.',
            'code.numeric' => 'The OTP field must be numeric.'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }

        $check_otp = Otp::where('email', $request->email)->where('code', $request->code)->first();
        if(!$check_otp) {
            return response()->json(['status' => 'error', 'errors' => ['code' => 'Invalid OTP.']], 401);
        }
        Otp::where('email', $request->email)->delete();

        // $user             = new User();
        // $user->full_name  = $request->full_name;
        // $user->email      = $request->email;
        // $user->password   = Hash::make($request->password);
        // $user->refer_code = $request->refer_code;
        // $user->type       = 2;
        // $user->save();


        // $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        // $string = substr(str_shuffle($chars), 0, 8);
        // $cus_account_details          = new CustAccountDetail();
        // $cus_account_details->user_id = $user->id;
        // $cus_account_details->status  = -1;
        // $cus_account_details->refer   = $string;
        // $cus_account_details->save();

        // $data = ['message' => 'Thanks for signing up at YPay, you are one step closer to financial freedom now! YPay!', 'image' => ''];
        // sendNotification($request->fcm_token, $data, $user->id, 'welcome');
        // $url = 'https://networks.ypayfinancial.com/api/mailv1/signup_mail.php';
        // $body = ['email' => $user->email, 'name'=> $user->full_name];
        // sendEmail($body,$url);

        // $user            = User::whereId($user->id)->first();
        // $token           = $user->createToken('mobile-app', ['all'])->plainTextToken;
        // $user->fcm_token = $request->fcm_token;
        // $user->save();

        return response()->json([ 'status' => true, 'message' => 'OTP Verified Successfully' ], 200);
    }


    public function resetPwd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }

        $user = User::where(["email" => $request->email])->first();

        if (!isset($user)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['email' => 'Email not found']
            ], 401);
        }
            if (!isset($user) || (isset($user->status) && $user->status == 2 || $user->type == 1)) { //in-active
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => 'No access, kindly contact admin']
                ], 422);
            }

            // Generate otp
            $code = mt_rand(100000, 999999);

            // Add a row of otp in database against this user
            Otp::where('user_id', $user->id)->delete();

            $otp          = new Otp;
            $otp->user_id = $user->id;
            $otp->code    = $code;
            $otp->save();

            // email notification 
            $url = 'https://networks.ypayfinancial.com/api/mailv1/forgot_password.php';
            $body = ['email' => $user->email, 'name'=>$user->full_name, 'token' => $otp->code];
            sendEmail($body,$url);


            /* send otp in email */
            // $data = array('name'=> $user->full_name,'otp'=>$otp->code,);
            // try{
            // Mail::send('mail.otpEmail', $data, function($message) use ($user) {
            //     $message->to($user->email)->subject('Reset Password');
            //     $message->from('hello@ypayfinancial.com', 'YPay');
            // });

            // Send the code through sms (pending api required)
            // $response = Http::post('https://api.unifonic.com/rest/Messages/Send', [
            //     'AppSid'    => 'Xgp4dWmHcxcwtWe74wNsxhaK6xYF95',
            //     'Recipient' => $user->phone_no, 
            //     'Body'      => $code, 
            //     'SenderID'  => 'PMU', 
            // ]);
            // $response = Http::post('https://networks.ypayfinancial.com/api/verify/sendotp.php', [
            //     'to' => $user->phone_no, 
            // ]);
            

        // }  catch(\Swift_TransportException $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => ['email' => 'Must be valid email']
        //     ], 401);
        // }


            return response()->json([
                'status'       => 'success',
                'data'         => ['user' => $user],
                'code'         => $code
            ]);
    }

    public function setPwd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $user = User::whereId($request->id)->update(['password'=> Hash::make($request->password)]);
        return response()->json([
            'data'  => 'Successfully Password Changed!',
            'status' => true,
        ], 200);
    }

    public function refUser(Request $request)
    {
       $user         = User::whereId($request->user_id)->with(['cust_account_detail' => function($q){
           $q->select('id', 'user_id', 'status', 'risk_profile_status');
       }])->select('id','full_name','phone_no','email','fcm_token')->first();
       $notification= Notification::where('user_id',$request->user_id)->get();
       $read         = array_keys(array_column($notification->toArray(), 'is_read'), 0);
       $insight_data = [];
       $insight     = Insight::with(['insight_tag' => function($q){
           $q->select('id', 'name', 'status');
       }],['insight_tag' => function($q){
           $q->select('id', 'name', 'status');
       }])->where('status', 1)->where('type', 2)->select('id','category_id','title','text','order_no','tag_id','url','logo','status','reading_time','is_allowed','type','user_id')->orderBy('order_no','asc')->get();

       $insight_tag = InsightTag::get();
       $insight_video  = Insight::with('insight_tag')->where('status', 1)->where('type', 1)->get();
       // $insight      = Insight::where('status', 1)->where('is_allowed', 1)->get();
       // foreach ($insight as $key => $value) {
       //     $insight_data[$key]['title']        = $value['title'];
       //     $insight_data[$key]['text']         = $value['text'];
       //     $insight_data[$key]['type']         = $value['type'];
       //     $insight_data[$key]['url']          = $value['url'];
       //     $insight_data[$key]['author_name']  = $value['author_name'];
       //     $insight_data[$key]['reading_time'] = $value['reading_time'];
       //     $insight_data[$key]['logo']         = $value['logo'];
       //     $insight_data[$key]['heading'] = $value['type'] == 1 ? 'Video' : 'Article';
       // }
           $userTokenExist = UserFcm::where('user_id',$request->user_id)->first();
           if (!$userTokenExist && $user->fcm_token) {
               $userToken            = new UserFcm();
               $userToken->user_id   = $request->user_id;
               $userToken->fcm_token = json_encode([$user->fcm_token]);
               $userToken->save();
           }

       return response()->json([
           'status'       => true,
           'data'         => $user,
           'notification' => $read ? true : false,
           'sum'          => userInvestmentSum($request->user_id),
           'insight'  => $insight,
           // 'insight_tag'  => $insight_tag,
           // 'insight_video'  => $insight_video,
           //'insight_data' => $insight_data,
       ], 200);
    } 


     public function getProfile(Request $request)
     {
        $user_id = $request->user()->id;
        $change_request = ChangeRequest::where('user_id', $user_id)->where('status', '0')->first();
        // dd($change_request);
        if (!empty($change_request))
            $user = User::whereId($user_id)->with('cust_account_detail', 'cust_basic_detail.banks', 'cust_cnic_detail', 'change_request.bank')->with('amcCustProfile', function($query) {
                $query->where('status', 1);
             })->with('change_request', function($q){
                $q->where('status', 0);
             })->first();
        else
            $user = User::whereId($user_id)->with('cust_account_detail', 'cust_basic_detail.banks', 'cust_cnic_detail', 'cust_bank_detail')->with('amcCustProfile', function($query) {
                $query->where('status', 1);
             })->first();        
            $user->cust_basic_detail->amc_bank_id=$user->cust_basic_detail->bank;
            return response()->json([
            'status'       => true,
            'data'         => $user
        ], 200);
     }

     public function resendOtp(Request $request)
     {
     // try{
         $code = mt_rand(100000, 999999);
         Otp::where('user_id', $request->data['id'])->delete();

         $otp          = new Otp;
         $otp->user_id = $request->data['id'];
         $otp->code    = $code;
         $otp->save();
         /* send otp in email */
         $url  = 'https://networks.ypayfinancial.com/api/mailv1/forgot_password.php';
         $body = ['email' => $request->data['email'], 'name' => $request->data['full_name'], 'token' => $otp->code];
         sendEmail($body,$url);
        //  $data = array('name'=> $request->data['full_name'],'otp'=>$otp->code);
        //  Mail::send('mail.otpEmail', $data, function($message) use ($request) {
        //     $message->to($request->data['email'])->subject('Reset Password');
        //     $message->from('hello@ypayfinancial.com', 'YPay');
        // });
         return response()->json([
            'status' => true,
        ], 200);
     }

     public function resendSignUpOtp(Request $request)
     {
         $code = mt_rand(100000, 999999);
         // Add a row of otp in database against this user
         Otp::where('email', $request->email)->delete();
 
         $otp        = new Otp;
         $otp->email = $request->email;
         $otp->code  = $code;
         $otp->save();
         /* send otp in email */
         $url  = 'https://networks.ypayfinancial.com/api/mailv1/email_verification.php';
         $body = ['email' => $request->email, 'name' => $request->full_name, 'token' => $otp->code];
         sendEmail($body,$url);

         return response()->json([
            'status' => true,
        ], 200);
     }

     public function getNotification(Request $request)
     {
        $notification = Notification::where('user_id', $request->user_id)->orderBy('id', 'desc')->latest()->take(20)->get();
        $read         = array_keys(array_column($notification->toArray(), 'is_read'), 0);

        $data = [];
        $ids = [];
        $timestamps = [];
        $notification =  $notification->toArray();
        foreach ($notification as $key => $value) {
            $noti                  = json_decode($value['data'], true)['data'];
            $ids[$key]             = $value['id'];
            $timestamps[$key]      = $value['created_at'];
            $data[$key]['id']      = $value['id'];
            $data[$key]['status']  = true;
            $data[$key]['timestamp']  = date("Y-m-d h:i:s a",strtotime($value['created_at']));
            // $currentDate = new DateTime(date('m/d/y h:i:s a'));
            // $userDefineDate = $currentDate->format('m/d/y h:i:s a');  
            // $start = date_create($userDefineDate);
            // $end = date_create(date('m/d/y h:i:s a', strtotime($value['created_at'])));              
            // $diff=date_diff($start,$end);
            // $data[$key]['time_difference']=$diff;
            $data[$key]['read']    = $value['is_read'];
            $data[$key]['title']   = json_decode($value['data'], true)['notification']['title'];
            $data[$key]['message'] = $noti['message'];
            $data[$key]['image']   = $noti['image']??'';
            $data[$key]['type']    = $value['type'];
        }
        return response()->json([
            'data'   => $data,
        ], 200);
     }

     public function markallRead(Request $request)
     { 
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
         $notification = Notification::where('user_id', $request->user_id)->update(['is_read' => 1]);
         return response()->json([
            'status' => true,
            'message' => 'User Notification Read Status Changed Successfully'
        ], 200);
     }
     public function markindvidualRead(Request $request)
     { 
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
         $notification = Notification::where('id', $request->id)->update(['is_read' => 1]);
         return response()->json([
            'status' => true,
            'message' => 'Notification Read Status Changed Successfully'
        ], 200);
     }
     public function notificationRead(Request $request)
     {
         $notification = Notification::whereIn('id', $request->ids)->update(['is_read' => 1]);
         return response()->json([
            'status' => true,
        ], 200);
     }

     public function changePassword(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password'         => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }

        $user = User::where('id',$request->user_id)->first();

        $current_password = $user->password;

        if(!Hash::check($request->current_password,$current_password))
        {
            return response()->json(['status' => 'error', 'errors' => ['current_password' => 'Previous Password does not match!']], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([  'status' => true, ], 200);


     }

     public function logout(Request $request)
     {
        $user_id = $request->user()->id;
        $user = User::whereId($user_id)->first();
        $user->fcm_token = NULL;
        $user->save();
        $user->tokens()->where('name', 'mobile-app')->delete();
        $request->user()->token()->revoke();
        return response()->json([
            'status' => true,
        ], 200);
     }
}