<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\CitizenshipStatus;
use App\Models\MarketingNotification;
use App\Models\SourcesofIncome;
use App\Models\User;
use App\Models\UserFcm;
use App\Models\Otp;
use App\Models\OTPVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Validator;
use Yajra\DataTables\DataTables;
use function App\Libraries\Helpers\marketingNotification;
use function App\Libraries\Helpers\s3ImageUpload;
use function App\Libraries\Helpers\s3ImageUploadApi;
use Carbon\Carbon;
use DB;
use File;
class NotificationController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function notificationHistory()
    {
        return view('notifications.history');
    }
    
   public function notificationIndex()
   {
       $income_sources=SourcesofIncome::where('status',1)->get();
       $cities=City::where('status',1)->get();
       $citizenship_statuses=CitizenshipStatus::all();
       return view('notifications.notification-index',compact('income_sources','cities','citizenship_statuses'));
   }
   public function getData(Request $request)
   {
     $users=User::with(['cust_basic_detail' => function($q){
        $q->select(DB::raw('now(),dob,user_id,income_source,city,gender,YEAR(now()) - YEAR(dob) as age'));
    }],'cust_account_detail','cust_cnic_detail')->where('status',1)->where('type',2);
     return DataTables::of($this->users_filter($request, $users))->make(true);
   }
   public function export(Request $request)
   {
     $statuses = array("0"=>"Pending", "1"=>"Approved", "2"=>"Rejected", "3"=>"On Hold", "-1"=> "No Profile");
     $users=User::with(['cust_basic_detail' => function($q){
      $q->select(DB::raw('now(),dob,user_id,income_source,city,gender,YEAR(now()) - YEAR(dob) as age'));
  }],'cust_account_detail','cust_cnic_detail')->where('status',1)->where('type',2);
     $filtered_users=$this->users_filter($request,$users)->get();
     $timestamp=Carbon::now()->timestamp;
     $file_name=$timestamp.".csv";
     if(!file_exists('storage/uploads/notification'))
     {
       mkdir('storage/uploads/notification',0777,true);
       chmod('storage/uploads/notification', 0777);
     }
     $users_csv = fopen('storage/uploads/notification/'.$file_name,"a");
     chmod('storage/uploads/notification/'.$file_name, 0777);
     $heading=array("Name","Email","Number","Registered Date","Profile Status","App Version","Platform");
     fputcsv($users_csv, $heading); 
     foreach($filtered_users as $user)
     {
       if (substr($user->phone_no, 0, 3) != "+92")
         $phone_no = (string) "+92".substr($user->phone_no, 1);
       else
         $phone_no = (string) $user->phone_no;
       $phone_no = preg_replace('/\s+/', '', $phone_no);
       $user_data=array(
         $user->full_name??'',
         $user->email??'',
         $phone_no,
         $user->created_at??'',
         $statuses[$user->cust_account_detail->status]??'',
         $user->app_version??'',
         $user->platform??'',
         );
         $users_csv = fopen('storage/uploads/notification/'.$file_name,"a");
         fputcsv($users_csv, $user_data);
     }
     $user_csv[$file_name]=file_get_contents('storage/uploads/notification/'.$file_name);
     File::delete('storage/uploads/notification/'.$file_name);
     return response()->json(['success'=> true, 'message' => 'Data Sent successfully!',"user_csv"=>$user_csv]);
   }
   public function notificationShow(Request $request)
   {
    $marketing = MarketingNotification::query();
    return DataTables::of($this->filter($request,$marketing))
          ->order(function ($q) use ($request) {
              if (count($request->order)) {
                  foreach ($request->order as $order) {
                      $column = @$request->columns[@$order['column']]['data'];
                      $dir = @$order['dir'];
                      if ($column && $dir) {
                          $q->orderBy($column, $dir);
                      }
                  }
              }
          })
          ->addIndexColumn()
          ->make(true);
   }

    public function index()
    {
        return view('notifications.index');
    }
    public function send_otp(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'otp'               => 'required',
          'phone_number'      => 'required|size:11',
      ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()]);
      }
      // $user=User::where('phone_no','like','%'.$request->phone_number.'%')->first();
      // if(isset($user))
      // {
        // $phoneNo=$user->phone_number;
        $phoneNo=$request->phone_number;
        $otp_vendor = OTPVendor::where('sms_active', 1)->first();
        if(isset($otp_vendor))
        {
          if ($otp_vendor->id == 1) {
              $response = Http::post('https://networks.ypayfinancial.com/api/verify/sendotp.php', [
                  'to' => '+'.$phoneNo, 
              ]);
          } else {
              $phone_no = str_replace(' ', '', $phoneNo);
              $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
              $msg_id = substr(str_shuffle($chars), 0, 8);
              
              Otp::where('phone_no', $phone_no)->delete();
              $otp = new Otp;
              $otp->phone_no = $phone_no;
              $otp->code = $request->otp;
              $otp->msg_id = $msg_id;
              $otp->save();
              $response = $this->send_and_save_otp($otp_vendor, $phone_no, $request->otp, $msg_id);
          }
        }
        else{
          return response()->json([
            'status' => 'error',
            'errors' => ['error' => 'No Active SMS Vendor Yet']
          ], 422);
        }
        return response()->json(['success'=> true, 'message' => 'OTP Successfully Sent']);
      // }
      return response()->json([
        'status' => 'error',
        'errors' => ['error' => 'User with Phone Number Not Found']
      ], 401);
    }
    public function send_and_save_otp($otp_vendor, $phone_no, $message, $msg_id)
    {
        if ($otp_vendor->id == 2) {
            $postData = [ 
                "mobileno" => $phone_no,
                "msgid" => $msg_id,
                "sender" => $otp_vendor->sender,
                "message" =>$message
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $otp_vendor->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>json_encode($postData),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: '.$otp_vendor->api_key,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        }
    }
    public function send_sms(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'message'               => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
    }
        $message=$request->message;
        $users=User::with(['cust_basic_detail' => function($q){
            $q->select(DB::raw('now(),dob,user_id,income_source,city,gender,YEAR(now()) - YEAR(dob) as age'));
        }],'cust_account_detail','cust_cnic_detail')->where('status',1)->where('type',2);

        $filtered_user_numbers=$this->users_filter($request, $users)->pluck('phone_no');
        $number_string="";
        $count=0;
        foreach($filtered_user_numbers->toArray() as $number)
        {
          if($number!="null" && str_replace(' ','',$number)!='')
          {
            $number=str_replace('+','',$number);
            if($number[0]==3)
            {
              $number = '92'.($number);
            }
            if($number_string=='')
            $number_string.=str_replace(' ','',$number);
            else
            $number_string.=",".str_replace(' ','',$number);
            $count++;
          }
        }
        // foreach($filtered_users as $user)
        // {
            // if($user->phone_no!="" && $user->phone_no!="null")
            // {
            // $phone_no = str_replace(' ', '', $user->phone_no);
            $otp_vendor = OTPVendor::where('sms_active', 1)->first();
            $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $msg_id = substr(str_shuffle($chars), 0, 8);
            if(isset($otp_vendor))
            {
              if ($otp_vendor->id == 2) {
                $postData = [ 
                    "mobileno" => $phone_no,
                    "msgid" => $msg_id,
                    "sender" => $otp_vendor->sender,
                    "message" => $message
                ];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $otp_vendor->url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>json_encode($postData),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: '.$otp_vendor->api_key,
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
              }
              else if($otp_vendor->id == 3)
              {
                if($count<50)
                {
                  return response()->json([
                    'status' => 'error',
                    'errors' => ['error' => 'Users Count Must be Greater than or Equals to 50']
                  ], 422);
                }
                $number_string=urlencode($number_string);
                $message=urlencode($message);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $otp_vendor->bulk_api_url.'?key='.$otp_vendor->bulk_api_key.'&receiver='.$number_string.'&sender=Alpha&msgdata='.$message.'&camp=Camp12',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                echo $response;
              }
              else
              {
                return response()->json([
                  'status' => 'error',
                  'errors' => ['error' => 'No Active SMS Vendor Yet']
                ], 422);
              }
            }
            else{
              return response()->json([
                'status' => 'error',
                'errors' => ['error' => 'No Active SMS Vendor Yet']
              ], 422);
            }
          // }
        // }
        return response()->json(['success'=> true, 'message' => 'SMSes Successfully Sent']);
    }
    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'                 => 'required',
            'message'               => 'required',
            // 'image'                 => 'max:1024|mimes:jpg,jpeg'
        ],);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $marketingNoti = new MarketingNotification();
        $marketingNoti->title = $request->title;
        $marketingNoti->message = $request->message;

        if($request->image){
            // $file             = $request->image;
            // $fileOriginalName = $file->getClientOriginalName();
            // $extension        = $file->getClientOriginalExtension();
            // $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
            // // $filename         = $file->storeAs('public/uploads/marketing/', $fileNameToStore);
            // $path               = "marketing_notifications/".$fileNameToStore;
            // $filename           = s3ImageUpload($file, $path);
            $folderPath     = "storage/uploads/marketing_notifications/";
            $image_parts    = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type     = $image_type_aux[1];
            $image_base64   = base64_decode($image_parts[1]);
            $filename       = 'marketing_notification_image_' . time();
            $file           = $filename . '.'.$image_type;
            //file_put_contents($file, $image_base64);
            $path               = "marketing_notifications/".$file;
            $image_url           = s3ImageUploadApi($request->image, $path);
            $marketingNoti->image = $image_url;
        }
        $users_ids=[];
        $marketingNoti->save();
        $users=User::with(['cust_basic_detail' => function($q){
            $q->select(DB::raw('now(),dob,user_id,income_source,city,gender,YEAR(now()) - YEAR(dob) as age'));
        }],'cust_account_detail','cust_cnic_detail')->where('status',1)->where('type',2);

        $filtered_users=$this->users_filter($request, $users)->get();

        foreach($filtered_users as $user)
        {
          $users_ids[]=User::where('id',$user->id)->pluck('id')->first();
        }
        $fcm_data = UserFcm::select('fcm_token','user_id')->whereIn('user_id',$users_ids)->get();
        if($fcm_data){
            $fcm_token = [];
            foreach($fcm_data->pluck('fcm_token') as $value){
                    foreach(json_decode($value) as $value2){
                        array_push($fcm_token, $value2);
                    }
            }
            $flag=true;
            $regIdChunk=array_chunk($fcm_token,1000);
            foreach($regIdChunk as $RegId){
                if($request->image){
                $message_status = marketingNotification($RegId, $fcm_data,$path,$marketingNoti->toArray(), 'marketing',$flag);
                }
                else
                $message_status = marketingNotification($RegId, $fcm_data,'',$marketingNoti->toArray(), 'marketing',$flag);
                $flag=false;
            }
            // marketingNotification( $fcm_token, $fcm_data, $marketingNoti->toArray(), 'marketing');
        }
        return response()->json(['success'=> true, 'message' => 'Notifications Successfully Sent']);
        
    }
    public function users_filter($request, $users)
    {
        try {
              if (isset($request->cohort) && $request->cohort!='null') {
                if($request->cohort!="all")
                {
                    $cohort=$request->cohort;
                    $users = $users->whereHas('cust_account_detail', function ($q) use ($cohort){
                    $q->where('status',$cohort);
                  });
                }
              }
              if (isset($request->platform) && $request->platform!='null') {
                $users = $users->where('platform','like', '%' .$request->platform. '%');
              }
              if (isset($request->phone_no) && $request->phone_no!='null') {
                $users = $users->where('phone_no','like','%'.$request->phone_no.'%');
              }
              if (isset($request->from) && isset($request->to) && $request->from!= "null" && $request->to!= "null") {
                $users = $users->whereBetween('created_at',[[date("Y-m-d H:i:s", strtotime($request->from)),date("Y-m-d H:i:s", strtotime($request->to))]]);
              }
              if (isset($request->app_version) && $request->app_version!='null') {
                $users = $users->where('app_version','like', '%' .$request->app_version. '%');
              }
              // if (isset($request->to) && $request->to!='null') {
              //     $users = $users->whereDate('created_at', '<=', Carbon::parse($request->to));
              // }
              if (isset($request->age) && $request->age!='null') {
                $age=$request->age;
                if(!isset(explode("-", $age)[1]))
                {
                    $year=date('Y-m-d',strtotime('-35 Years'));
                    $users = $users->whereHas('cust_basic_detail', function ($q) use ($year){
                        $q->whereDate('dob','<',Carbon::parse($year));
                    });
                }
                else
                {
                  $start=explode("-", $age)[0];
                  $end=explode("-", $age)[1];
                  $start_year=date('Y-m-d',strtotime('-'.$start.' Years'));
                  $end_year=date('Y-m-d',strtotime('-'.$end.' Years'));
                  $users = $users->whereHas('cust_basic_detail', function ($q) use ($start_year,$end_year){
                    $q->whereBetween('dob',[date("Y-m-d", strtotime($end_year)),date("Y-m-d", strtotime($start_year)),]);
                  });
                }
              } 
              if (isset($request->citizenship_status) && $request->citizenship_status!='null') {
                $citizenship_status=$request->citizenship_status;
                $users = $users->whereHas('cust_cnic_detail', function ($q) use ($citizenship_status){
                $q->where('citizenship_status',$citizenship_status);
                });
              }  
              if (isset($request->gender) && $request->gender!='null') {
                $gender=$request->gender;
                $users = $users->whereHas('cust_basic_detail', function ($q) use ($gender){
                    $q->where('gender','like', '%' .$gender. '%');
                  });
              }  
              if (isset($request->source_of_income) && $request->source_of_income!='null') {
                $source_of_income=$request->source_of_income;
                $users = $users->whereHas('cust_basic_detail', function ($q) use ($source_of_income){
                    $q->where('income_source',$source_of_income);
                  });
              }  
              if (isset($request->city) && $request->city!='null') {
                $city=$request->city;
                $users = $users->whereHas('cust_basic_detail', function ($q) use ($city){
                    $q->where('city',$city);
                  });
              }  
            return $users;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
    public function filter($request, $marketing)
    {
      try {
  
        if (isset($request->title)) {
        
          $marketing = $marketing->where('title', 'like', '%' . $request->title . '%' ); 
            
        }


      if (isset($request->from)) {
        $marketing = $marketing->whereDate('created_at', '>=', Carbon::parse($request->from));
      }


      if (isset($request->to)) {
        $marketing = $marketing->whereDate('created_at', '<=', Carbon::parse($request->to));
      }

  
        return $marketing;
      } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
      }
    }
    
}
