<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Investment;
use App\Models\AmcCustProfile;
use App\Models\User;
use App\Models\UserImage;
use App\Models\AmcAPI;
use App\Models\AmcDataLog;
use App\Exports\CSVExport;
use App\Models\Configurations;
use App\Models\Amc;
use App\Models\AmcBank;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as BaseExcel;
use App\Models\Redemption;
use Carbon\Carbon;
use App\Models\CronJobLog;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\AmcFund;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PDF;
use Mail;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use function App\Libraries\Helpers\s3ImageUpload;
use Config;

class InvestmentController extends Controller
{

  // function __construct()
  //   {
  //        $this->middleware('permission:investment-list');
  //        $this->middleware('permission:investment-edit', ['only' => ['update']]);
  //   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $users=User::with('cust_cnic_detail')->whereHas('cust_account_detail', function ($q){ 
        $q->whereIn('status',[0,1]);
      })->where('type',2)->get();
        return view('investment.index',compact('users'));
    }
    public function sendtoamc(Request $request)
    {
       $verified_investments=Investment::where('verified',1)->where('status',0)->get();
       $timestamp=time();
       foreach($verified_investments as $investment)
       {
        $amc_registered_profile=AmcCustProfile::where('user_id',$investment->user->id)->where('amc_id',$investment->fund->amc_id)->first();
        if($amc_registered_profile==null)
        {
          $amc_cust_profile=new AmcCustProfile;
          $amc_cust_profile->amc_id=$investment->fund->amc_id;
          $amc_cust_profile->user_id=$investment->user->id;
          $amc_cust_profile->status=0;
          $amc_cust_profile->save();
          $kyc_data=array(
            $investment->user->full_name??"",
            $investment->user->email??"",
            $investment->user->phone_no??"",
            $investment->user->cust_basic_detail->father_name??"",
            $investment->user->cust_basic_detail->mother_name??"",
            $investment->user->cust_basic_detail->dob??"",
            $investment->user->cust_basic_detail->current_address??"",
            $investment->user->cust_basic_detail->state??"",
            $investment->user->cust_basic_detail->city??"",
            $investment->user->cust_basic_detail->zakat??"",
            $investment->user->cust_basic_detail->nominee_name??"",
            $investment->user->cust_basic_detail->nominee_cnic??"",
            $investment->user->cust_basic_detail->source_of_income??"",
            $investment->user->cust_cnic_detail->cnic_number??"",
            $investment->user->cust_cnic_detail->issue_date??"",
            $investment->user->cust_cnic_detail->expiry_date??"",
            request()->getHost().'/'.$investment->user->cust_cnic_detail->cnic_front??"",
            request()->getHost().'/'.$investment->user->cust_cnic_detail->cnic_back??"",
            request()->getHost().'/'.$investment->user->cust_basic_detail->income??"",
            $investment->user->cust_bank_detail->bank??"",
            $investment->user->cust_bank_detail->branch??"",
            $investment->user->cust_bank_detail->iban??""
          );
        }
        else if($amc_registered_profile->status=="-1")
        {
          $kyc_data=array(
            $investment->user->full_name??"",
            $investment->user->email??"",
            $investment->user->phone_no??"",
            $investment->user->cust_basic_detail->father_name??"",
            $investment->user->cust_basic_detail->mother_name??"",
            $investment->user->cust_basic_detail->dob??"",
            $investment->user->cust_basic_detail->current_address??"",
            $investment->user->cust_basic_detail->state??"",
            $investment->user->cust_basic_detail->city??"",
            $investment->user->cust_basic_detail->zakat??"",
            $investment->user->cust_basic_detail->nominee_name??"",
            $investment->user->cust_basic_detail->nominee_cnic??"",
            $investment->user->cust_basic_detail->source_of_income??"",
            $investment->user->cust_cnic_detail->cnic_number??"",
            $investment->user->cust_cnic_detail->issue_date??"",
            $investment->user->cust_cnic_detail->expiry_date??"",
            request()->getHost().'/'.$investment->user->cust_cnic_detail->cnic_front??"",
            request()->getHost().'/'.$investment->user->cust_cnic_detail->cnic_back??"",
            request()->getHost().'/'.$investment->user->cust_basic_detail->income??"",
            $investment->user->cust_bank_detail->bank??"",
            $investment->user->cust_bank_detail->branch??"",
            $investment->user->cust_bank_detail->iban??""
          );
        }
        $investment_data=array(
        $investment->fund->amc_reference_number??"",
        $investment->amount??"",
        request()->getHost().'/'.$investment->image??"",
        $investment->pay_method??"",
        $investment->transaction_id??"",
        $investment->user->cust_cnic_detail->cnic_number??"",
        $investment->account_number??"",
        $investment->rrn??"",
        $investment->transaction_status??"",
        $investment->transaction_time??""
        );
        if($investment->fund->amc->through_csv==1)
        {
          if(isset($kyc_data))
          {
          $amc_name=$investment->fund->amc->entity_name;
          $amc_name=strtolower(str_replace(' ','_',$amc_name)); 
          $KYC_folder_path="storage/uploads/kyc/".$amc_name.$timestamp;
          $investment_file_name=$amc_name."_".$timestamp."_kyc.csv";
          $kyc_file_path="storage/uploads/kyc/".$amc_name.$timestamp."/";
          if(!file_exists($KYC_folder_path))
          {
            mkdir($KYC_folder_path,0777,true);
            $kyc_csv = fopen($kyc_file_path.$investment_file_name,"a");
            $heading=array("Full Name","Email Address","Contact Number","Father Name","Mother Name","DOB","Current Address","State","City","Zakat Deduction",
            "Nominee Name","Nominee CNIC","Source of Income","CNIC Number","Issue Date","Expiry Date","CNIC Front","CNIC back","Proof of Income",
          "Bank Name","Branch Name","IBAN");
            fputcsv($kyc_csv, $heading); 
          }
          $kyc_csv = fopen($kyc_file_path.$investment_file_name,"a");
          fputcsv($kyc_csv, $kyc_data);
          rewind($kyc_csv);
          }

          $amc_name=$investment->fund->amc->entity_name;
          $amc_name=strtolower(str_replace(' ','_',$amc_name));
          $investment_folder_path="storage/uploads/investment/".$amc_name."/".$timestamp;
          $investment_file_name=$amc_name."_".$timestamp."_investment.csv";
          $investment_file_path="storage/uploads/investment/".$amc_name."/".$timestamp."/";
          if(!file_exists($investment_folder_path))
          {
            mkdir($investment_folder_path,0777,true);
            $investment_csv = fopen($investment_file_path.$investment_file_name,"a");
            $heading=array("Mutual Fund","Amount","Receipt Image","Payment Method","Transaction Id","Customer CNIC","Account Number","RRN","Transaction Status","Transaction Time");
            fputcsv($investment_csv, $heading); 
          }
          $investment_csv = fopen($investment_file_path.$investment_file_name,"a");
          fputcsv($investment_csv, $investment_data);
          rewind($investment_csv);
        }
        // if($investment->fund->amc->through_email==1)
        // {

        // }
        // else if($investment->fund->amc->through_drive==1)
        // {
          
        // }
        // else
        // {

        // }
      }
      $investment_csvs=array();
      $kyc_csvs=array();
      foreach($verified_investments as $investment)
      {
        if($investment->fund->amc->through_csv==1)
        {
        $amc_name=$investment->fund->amc->entity_name;
        $amc_name=strtolower(str_replace(' ','_',$amc_name)); 
        $KYC_folder_path="storage/uploads/kyc/".$amc_name.$timestamp;
        $investment_file_name=$amc_name."_".$timestamp."_kyc.csv";
        $kyc_file_path="storage/uploads/kyc/".$amc_name.$timestamp."/";
        $investment_folder_path="storage/uploads/investment/".$amc_name."/".$timestamp;
        $investment_file_name=$amc_name."_".$timestamp."_investment.csv";
        $investment_file_path="storage/uploads/investment/".$amc_name."/".$timestamp."/";
        $amc_registered_profile=AmcCustProfile::where('user_id',$investment->user->id)->where('amc_id',$investment->fund->amc_id)->first();
        $investment_csvs[$investment_file_name]=file_get_contents($investment_file_path.$investment_file_name);
        if($amc_registered_profile==null || $amc_registered_profile->status=="-1")
        {
          $kyc_csvs[$investment_file_name]= file_get_contents($kyc_file_path.$investment_file_name);
          $amc_registered_profile->status=0;
          $amc_registered_profile->save();
        }

        $investment->verified=2;
        $investment->save();
        }
      }
      if(count($kyc_csvs)>0)
      return response()->json(['success'=> true, 'message' => 'Data Sent successfully!',"investment_csvs"=>$investment_csvs,"kyc_csvs"=>$kyc_csvs]);
      return response()->json(['success'=> true, 'message' => 'Data Sent successfully!',"investment_csvs"=>$investment_csvs]);
    }
    public function investment_inquiry()
    {
      $pending_investments=Investment::where('status',0)->whereNotNull('amc_reference_number')->whereHas('fund', function ($q){
        $q->where('amc_id',2);
      })->get();
      try{
      $investment_data=[];
      $flag=true;
      $current_date = date('Y_m_d');
      foreach($pending_investments as $investment)
      {
        try{
        $folio_number=AmcCustProfile::where('user_id',$investment->user->id)->where('amc_id',2)->where('status',1)->pluck('account_number');
        echo $folio_number[0];
        if(isset($folio_number[0]))
        {
          $user_folio_number=$folio_number[0];
          $transaction_id=$investment->amc_reference_number;
          $amc_api_data=AmcAPI::where('amc_id',2)->where('name','Unit Allocation API')->first();
          $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name));
          $customer_cnic= $investment->user->cust_cnic_detail->cnic_number;
          $investment_file_name=$current_date.".csv";
          $amc_name = $investment->fund->amc->entity_name;
          $curl = curl_init();
            $url=$amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&Folio='.$user_folio_number.'&FormNo='.$transaction_id;
            $url=str_replace(' ','%20',$url);
            $amc_dataset_log= new AmcDataLog;
            $amc_dataset_log->dataset_url= $url;
            $amc_dataset_log->api_name=$amc_api_data->name;
            $amc_dataset_log->amc_id=$amc_api_data->amc_id;
            $amc_dataset_log->user_id=$investment->user_id;
            $amc_dataset_log->save();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $xml_response = curl_exec($curl);
            var_dump($xml_response);
            if(!file_exists('storage/uploads/technical_log_2'))
            {
              mkdir('storage/uploads/technical_log_2',0777,true);
              chmod('storage/uploads/technical_log_2', 0777);
            }
            if($xml_response==false)
            {
              if(!file_exists('storage/uploads/technical_log_2'))
              {
                mkdir('storage/uploads/technical_log_2',0777,true);
                chmod('storage/uploads/technical_log_2', 0777);
              }
              if(!file_exists('storage/uploads/technical_log_2/'.$investment_file_name))
              {
                $investment_csv = fopen('storage/uploads/technical_log_2/'.$investment_file_name,"a");
                chmod('storage/uploads/technical_log_2/'.$investment_file_name, 0777);
                $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
                fputcsv($investment_csv, $heading);
              }
                $technical_error_detail=array(
                $customer_cnic,
                $amc_name,
                $amc_api_data->name,
                'VPN Not Connected',
                Carbon::now()
                );
                $investment_csv = fopen('public/storage/uploads/investment/'.$investment_file_name,"a");
                fputcsv($investment_csv, $technical_error_detail);
                rewind($investment_csv);
                $technical_error_csv=file_get_contents('public/storage/uploads/investment/'.$investment_file_name);
                Storage::disk('technical-log-ftp')->put($investment_file_name, $technical_error_csv);
                // File::delete('storage/uploads/technical_log_2/'.$investment_file_name);
                // die();
                continue;
            }
            $dom = new \DOMDocument;
            $dom->loadXML($xml_response);
            $dom->formatOutput = TRUE;
            $dom->savexml();
            $status = $dom->getElementsByTagName('Response');
            $status = $status->item(0)->nodeValue;
            if($status=="0")
            {
              $units = $dom->getElementsByTagName('Allocated_x0020_Units');
              $allocated_units = $units->item(0)->nodeValue;
              $nav = $dom->getElementsByTagName('NAV_x0020_per_x0020_Units');
              $nav_per_unit = $nav->item(0)->nodeValue;
              $investment->status=1;
              $investment->nav=$nav_per_unit;
              $investment->approved_date=Carbon::now();
              $investment->unit=$allocated_units;
              $investment->save();
              $user=$investment->user;
              $url = 'https://networks.ypayfinancial.com/api/mailv1/approve_investment.php';
              $body = ['email' => $user->email, 'name'=>$user->full_name];
              sendEmail($body,$url);     
             $data = ['message' => Config::get('messages.investment_request_approved'), 'image' => $investment->fund['fund_image']];
             sendNotification($user->fcm_token, $data, $user->id, 'Congratulations! 🎉Your investment request at [AMC Name] has been approved ✅');
             $investment_obj=new \stdclass();
             $investment_obj->cnic=$investment->user->cust_cnic_detail->cnic_number;
             $investment_obj->transaction_id=$transaction_id;
             $investment_obj->type=$investment->pay_method;
             $investment_obj->rrn=$investment->rrn??"";
             $investment_data[]=$investment_obj;
            }
            else if($status=="1")
            {
              $investment->status=3;
              $investment->rejected_reason='Transaction Cancelled';
              $investment->save();
              $user=$investment->user;
              // $url = 'https://networks.ypayfinancial.com/api/mailv1/investment_request_denied_by_amc.php';
              // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$investment->fund->amc->entity_name];
              // sendEmail($body,$url);
              // $data = ['message' => sprintf(Config::get('messages.investment_request_denied_amc'), $investment->fund->amc->entity_name), 'image' => $investment->fund['fund_image']];
              // sendNotification($user->fcm_token, $data, $user->id, 'investment_rejected');
              $investment_obj=new \stdclass();
              $investment_obj->cnic=$investment->user->cust_cnic_detail->cnic_number;
              $investment_obj->transaction_id=$transaction_id;
              $investment_obj->type=$investment->pay_method;
              $investment_obj->rrn=$investment->rrn??"";
              $investment_data[]=$investment_obj;
            }
            else if($status=="2")
            {
              $investment->status=3;
              $investment->rejected_reason='Transaction Reversed';
              $investment->save();
              $user=$investment->user;
              // $url = 'https://networks.ypayfinancial.com/api/mailv1/investment_request_denied_by_amc.php';
              // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$investment->fund->amc->entity_name];
              // sendEmail($body,$url);
              // $data = ['message' => sprintf(Config::get('messages.investment_request_denied_amc'), $investment->fund->amc->entity_name), 'image' => $investment->fund['fund_image']];
              // sendNotification($user->fcm_token, $data, $user->id, 'investment_rejected');
              $investment_obj=new \stdclass();
              $investment_obj->cnic=$investment->user->cust_cnic_detail->cnic_number;
              $investment_obj->transaction_id=$transaction_id;
              $investment_obj->type=$investment->pay_method;
              $investment_obj->rrn=$investment->rrn??"";
              $investment_data[]=$investment_obj;
            }
            else if($status=="3")
            {
            }
            else if($status=="4")
            {
              $investment->status=3;
              $investment->rejected_reason='No Data Found';
              $investment->save();
              $user=$investment->user;
              // $url = 'https://networks.ypayfinancial.com/api/mailv1/investment_request_denied_by_amc.php';
              // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$investment->fund->amc->entity_name];
              // sendEmail($body,$url);
              // $data = ['message' => sprintf(Config::get('messages.investment_request_denied_amc'), $investment->fund->amc->entity_name), 'image' => $investment->fund['fund_image']];
              // sendNotification($user->fcm_token, $data, $user->id, 'investment_rejected');
              $investment_obj=new \stdclass();
              $investment_obj->cnic=$investment->user->cust_cnic_detail->cnic_number;
              $investment_obj->transaction_id=$transaction_id;
              $investment_obj->type=$investment->pay_method;
              $investment_obj->rrn=$investment->rrn??"";
              $investment_data[]=$investment_obj;
            }
            else if($status=="6")
            {
              if(!file_exists('storage/uploads/technical_log_2'))
              {
                mkdir('storage/uploads/technical_log_2',0777,true);
                chmod('storage/uploads/technical_log_2', 0777);
              }
              if(!file_exists('storage/uploads/technical_log_2/'.$investment_file_name))
              {
                $investment_csv = fopen('storage/uploads/technical_log_2/'.$investment_file_name,"a");
                chmod('storage/uploads/technical_log_2/'.$investment_file_name, 0777);
                $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
                fputcsv($investment_csv, $heading);
              }
              $investment->status=3;
              $investment->rejected_reason='Other Exception -'.$amc_api_data->name;
              $investment->save();

              $user=$investment->user;
              // $url = 'https://networks.ypayfinancial.com/api/mailv1/investment_request_denied_by_amc.php';
              // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$investment->fund->amc->entity_name];
              // sendEmail($body,$url);
              // $data = ['message' => sprintf(Config::get('messages.investment_request_denied_amc'), $investment->fund->amc->entity_name), 'image' => $investment->fund['fund_image']];
              // sendNotification($user->fcm_token, $data, $user->id, 'investment_rejected');
              $technical_error_detail=array(
                $customer_cnic,
                $amc_name,
                $amc_api_data->name,
                $status,
                Carbon::now()
                );
                $investment_csv = fopen('storage/uploads/technical_log_2/'.$investment_file_name,"a");
                fputcsv($investment_csv, $technical_error_detail);
                rewind($investment_csv);
                $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$investment_file_name);
                Storage::disk('technical-log-ftp')->put($investment_file_name, $technical_error_csv);
                // File::delete('storage/uploads/technical_log_2/'.$investment_file_name);
              $investment_obj=new \stdclass();
              $investment_obj->cnic=$investment->user->cust_cnic_detail->cnic_number;
              $investment_obj->transaction_id=$transaction_id;
              $investment_obj->type=$investment->pay_method;
              $investment_obj->rrn=$investment->rrn??"";
              $investment_data[]=$investment_obj;
            }
        }
       }
       catch (\Exception $e) {
        continue;
       }
      }
      $cron_job_log=new CronJobLog;
      $cron_job_log->cron_job_name='Unit Allocation';
      $cron_job_log->amc_id=2;
      $cron_job_log->status=1;
      $cron_job_log->save();
      if(count($pending_investments)>0 && count($investment_data)>0)
      {
      $ypay_email=Configurations::where('name','YPay Email')->pluck('value')->first();
      $url  = 'https://networks.ypayfinancial.com/api/mailv1/investment_sent_request.php';
      $body = ["email" => $ypay_email,"ids"=>$investment_data,"message"=>"The following customer(s) have submitted Investment request(s).
        
      Your swift action is highly appreciated."];
      sendEmail($body,$url);
      }
     }
     catch (\Exception $e) {
      $cron_job_log=new CronJobLog;
      $cron_job_log->cron_job_name='Unit Allocation';
      $cron_job_log->amc_id=2;
      $cron_job_log->status=0;
      $cron_job_log->failure_reason=$e->getMessage();
      $cron_job_log->save();
     }
      //return response(["status"=>200,'message' => 'All Pending Investments Statuses Updated Successfully!']);
    }
    public function get_profile_image(Request $request){
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
  $user_id=$request->user_id;
  $user_image=UserImage::where('user_id',$user_id)->first();
  if(isset($user_image))
  {
      return response(["status"=>200,'message' => 'Image Stored Successfully!','image'=>$user_image->path]);
  }
  else
  {
      return response()->json([
          'status' => 'error',
          'errors' => ['user_id' => 'No Image Found']
      ], 401);
  }
  }
  public function store_profile_image(Request $request){
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
  $user_id=$request->user_id;
  $image=$request->image;
  $user_image=UserImage::where('user_id',$user_id)->first();
  $folderPath     = "storage/uploads/profile/";
  $image_parts    = explode(";base64,", $image);
  $image_type_aux = explode("image/", $image_parts[0]);
  $image_type     = $image_type_aux[1];
  $image_base64   = base64_decode($image_parts[1]);
  $filename       = 'profile_image_'. time();
  $file           = $folderPath . $filename . '.'.$image_type;
  file_put_contents($file, $image_base64);
  if(isset($user_image))
  {
      $user_image->path=$file;
      $user_image->user_id=$user_id;
      $user_image->save();
      return response(["status"=>200,'message' => 'Image Updated Successfully!']);
  }
  else
  {
      $user_image=new UserImage;
      $user_image->path=$file;
      $user_image->user_id=$user_id;
      $user_image->save();
      return response(["status"=>200,'message' => 'Image Stored Successfully!']);
  }
  }
  public function akd_investment()
  {
    $verified_investments=Investment::where('verified',1)->where('status',0)->whereHas('fund', function ($q){
      $q->where('amc_id',8);
    })->get();
    foreach($verified_investments as $investment)
    {
      $payment_proof=str_starts_with($investment->image, 'http')?$investment->image:(str_starts_with($investment->image, '/')?env('S3_BUCKET_URL').$investment->image:env('S3_BUCKET_URL').'/'.$investment->image);
      if(isset($payment_proof) && $payment_proof!='' && $investment->pay_method=="ibft")
      {
      $extention=explode(".",$payment_proof);
      $extention=$extention[count($extention)-1];
      $payment_proof_file=file_get_contents($payment_proof);
      }
      $customer_cnic=$investment->user->cust_cnic_detail->cnic_number;
      $customer_name=strtoupper($investment->user->full_name);
      if(isset($payment_proof) && $payment_proof!='' && $investment->pay_method=="ibft")
      {
      Storage::disk('akd-images-ftp')->put($customer_cnic.' '.$customer_name.'/payment/'.Carbon::now()->timestamp.".".$extention, $payment_proof_file);
      }
    }
  }
    public function investment_process()
    {
      $verified_investments=Investment::where('verified',1)->where('status',0)->whereHas('fund', function ($q){
        $q->where('amc_id',2);
      })->get();
      try{
        $investment_data=[];
        $flag=true;
        $current_date = date('Y_m_d');
      foreach($verified_investments as $investment)
      {
        try{
        $investment_obj=new \stdclass();
        $folio_number=AmcCustProfile::where('user_id',$investment->user->id)->where('amc_id',2)->where('status',1)->pluck('account_number');
        if(isset($folio_number[0]))
        {
          $user_folio_number=$folio_number[0];
          $amc_api_data=AmcAPI::where('amc_id',2)->where('name','Save Investment Transaction API')->first();
          $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name)); 
          $transaction_date=date('Y-m-d',strtotime($investment->created_at));
          // $transaction_date=Carbon::now()->format('Y-m-d');
          $transaction_time=Carbon::now()->timestamp;
          $amc_fund_data=AmcFund::where('ypay_fund_id',$investment->fund_id)->first();
          $amc_fund_unit_type=$amc_fund_data->amc_fund_unit_type;
          $customer_cnic=$investment->user->cust_cnic_detail->cnic_number;
          $customer_name=strtoupper($investment->user->full_name);
          // $customer_cnic=str_replace('-','',$customer_cnic);
          $amc_fund_unit_class=$amc_fund_data->amc_fund_class_type;
          $payment_proof=str_starts_with($investment->image, 'http')?$investment->image:(str_starts_with($investment->image, '/')?env('S3_BUCKET_URL').$investment->image:env('S3_BUCKET_URL').'/'.$investment->image);
          if(isset($payment_proof) && $payment_proof!='' && $investment->pay_method=="ibft")
          {
          $extention=explode(".",$payment_proof);
          $extention=$extention[count($extention)-1];
          $payment_proof_file=file_get_contents($payment_proof);
          }
          $amount=$investment->amount;
          $amc_fund_name=$amc_fund_data->amc_fund_name;
          $rebate_percentage=0;
          $url=$amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&AccCode='.$user_folio_number.'&TranDate='.$transaction_date.
          '&TranTime='.$transaction_time.'&FundName='.$amc_fund_name.'&FundUnitClass='.$amc_fund_unit_class.
          '&FundUnitType='.$amc_fund_unit_type.'&InvestAmount='.$amount.'&RebatePercentage='.$rebate_percentage;
          $url=str_replace(' ','%20',$url);
          $amc_dataset_log= new AmcDataLog;
          $amc_dataset_log->dataset_url= $url;
          $amc_dataset_log->api_name=$amc_api_data->name;
          $amc_dataset_log->amc_id=$amc_api_data->amc_id;
          $amc_dataset_log->user_id=$investment->user_id;
          $amc_dataset_log->save();
          $curl = curl_init();
          $investment_file_name=$current_date.".csv";
          $amc_name=$investment->fund->amc->entity_name;
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $xml_response = curl_exec($curl);
          if(!file_exists('storage/uploads/technical_log_2'))
          {
            mkdir('storage/uploads/technical_log_2',0777,true);
            chmod('storage/uploads/technical_log_2', 0777);
          }
          if($xml_response==false)
          {
          if(!file_exists('storage/uploads/technical_log_2/'.$investment_file_name))
          {
            $investment_csv = fopen('storage/uploads/technical_log_2/'.$investment_file_name,"a");
                chmod('storage/uploads/technical_log_2/'.$investment_file_name, 0777);
                $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
                fputcsv($investment_csv, $heading);
          }
            $technical_error_detail=array(
              $customer_cnic,
              $amc_name,
              $amc_api_data->name,
              'VPN Not Connected',
              Carbon::now()
              );
              $investment_csv = fopen('storage/uploads/technical_log_2/'.$investment_file_name,"a");
              fputcsv($investment_csv, $technical_error_detail);
              rewind($investment_csv);
              $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$investment_file_name);
              Storage::disk('technical-log-ftp')->put($investment_file_name, $technical_error_csv);
              // File::delete('storage/uploads/technical_log_2/'.$investment_file_name);
              // die();
              continue;
          }
          echo $xml_response;
          $dom = new \DOMDocument;
          $dom->loadXML($xml_response);
          $dom->formatOutput = TRUE;
          $dom->savexml();
          $status = $dom->getElementsByTagName('Response');
          $status = $status->item(0)->nodeValue;
          if($status=="1" || $status=="501" || $status=="401")
          {
            $transaction_id = $dom->getElementsByTagName('TransactionID');
            $transaction_id = $transaction_id->item(0)->nodeValue;
            $investment_obj->cnic=$customer_cnic;
            $investment_obj->transaction_id=$transaction_id;
            $investment_obj->type=$investment->pay_method;
            $investment_obj->rrn=$investment->rrn??"";
            $investment_data[]=$investment_obj;
            // $cust_transaction_ids[]=$transaction_id;
            if(isset($payment_proof) && $payment_proof!='' && $investment->pay_method=="ibft")
            {
            Storage::disk('custom-ftp')->put($customer_cnic.' '.$customer_name.'/payment/'.Carbon::now()->timestamp.".".$extention, $payment_proof_file);
            }
            $investment->amc_reference_number=$transaction_id;
            $investment->verified=3;
            $investment->verified_at=Carbon::now();
            $investment->save();
          }
          else if($status=="707" || $status=="708" || $status=="7088" || $status=="709")
          {
            $error_message=Config::get('save_investment_response_errors.'.$status);
            $investment->verified=3;
            $investment->verified_at=Carbon::now();
            $investment->status=2;
            $investment->response_error_message=$error_message;
            $investment->rejected_reason=$status;
            $investment->save();
            $user=$investment->user;
            $url = 'https://networks.ypayfinancial.com/api/mailv1/investment_request_denied_by_amc.php';
            $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$investment->fund->amc->entity_name];
            sendEmail($body,$url);
            $data = ['message' => sprintf(Config::get('messages.investment_request_denied_amc'), $investment->fund->amc->entity_name), 'image' => $investment->fund['fund_image']];
            sendNotification($user->fcm_token, $data, $user->id, 'investment_rejected');
          }
          else
          {
            $error_message=Config::get('save_investment_response_errors.'.$status);
            if(!file_exists('storage/uploads/technical_log_2/'.$investment_file_name))
            {
              $investment_csv = fopen('storage/uploads/technical_log_2/'.$investment_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$investment_file_name, 0777);
              $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
              fputcsv($investment_csv, $heading);
            }
            $investment->verified=3;
            $investment->verified_at=Carbon::now();
            $investment->status=3;
            $investment->response_error_message=$error_message;
            $investment->save();
            $technical_error_detail=array(
              $customer_cnic,
              $amc_name,
              $amc_api_data->name,
              $error_message,
              Carbon::now()
              );
              $investment_csv = fopen('storage/uploads/technical_log_2/'.$investment_file_name,"a");
              fputcsv($investment_csv, $technical_error_detail);
              rewind($investment_csv);
              $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$investment_file_name);
              Storage::disk('technical-log-ftp')->put($investment_file_name, $technical_error_csv);
              // File::delete('storage/uploads/technical_log_2/'.$investment_file_name);
          }
        }
        else
        {
          $investment->status=2;
          $investment->verified=3;
          $investment->verified_at=Carbon::now();
          $investment->rejected_reason='Account with AMC Not Found';
          $investment->save();
          $user=$investment->user;
          $url = 'https://networks.ypayfinancial.com/api/mailv1/investment_request_denied_by_amc.php';
          $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$investment->fund->amc->entity_name];
          sendEmail($body,$url);
          $data = ['message' => sprintf(Config::get('messages.investment_request_denied_amc'), $investment->fund->amc->entity_name), 'image' => $investment->fund['fund_image']];
          sendNotification($user->fcm_token, $data, $user->id, 'investment_rejected');
        }
      }
      catch (\Exception $e) {
      echo $e->getMessage();
       continue;
      }
      }
        $cron_job_log=new CronJobLog;
        $cron_job_log->cron_job_name='Investment Process';
        $cron_job_log->amc_id=2;
        $cron_job_log->status=1;
        $cron_job_log->save();
        if(count($verified_investments)>0 && count($investment_data)>0)
        {
        $amc_email=Amc::where('id',2)->pluck('compliant_email')->first();
        $url  = 'https://networks.ypayfinancial.com/api/mailv1/investment_sent_request.php';
        $body = ["email" => $amc_email,"ids"=>$investment_data,"message"=>"The following customer(s) have submitted Investment request(s).
        
        Your swift action is highly appreciated."];
        sendEmail($body,$url);
        }
    }
    catch (\Exception $e) {
        $cron_job_log=new CronJobLog;
        $cron_job_log->cron_job_name='Investment Process';
        $cron_job_log->amc_id=2;
        $cron_job_log->status=0;
        $cron_job_log->failure_reason=$e->getMessage();
        $cron_job_log->save();
     }
      //return response(["status"=>200,'message' => 'Verified Investments Transaction Requests Sent Successfully!']);
    }

    public function jsil_investment_process()
    {
      $verified_investments=Investment::with('fund.fund_bank')->where('verified',1)->where('status',0)->whereHas('fund', function ($q){
        $q->where('amc_id',1);
      })->get();
      try {
        $investment_data=[];
        $flag=true;
        $current_date = date('Y_m_d');
        $amc_api_data=AmcAPI::where('amc_id',1)->where('name','JSIL Save Investment Transaction API')->first();
        foreach($verified_investments as $investment)
        {
          try {
            $investment_obj=new \stdclass();
            $folio_number=AmcCustProfile::where('user_id',$investment->user->id)->where('amc_id',2)->where('status',1)->pluck('account_number');
            if(isset($folio_number[0]))
            {
              $user_folio_number=$folio_number[0];
              $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name));
              $amc_fund_data=AmcFund::where('ypay_fund_id',$investment->fund_id)->first();
              $transaction_date=Carbon::now()->format('mdHms');
              $customer_cnic=$investment->user->cust_cnic_detail->cnic_number;
              $user_id = str_replace('-', '', $customer_cnic);
              $customer_name=strtoupper($investment->user->full_name);
              $payment_proof=str_starts_with($investment->image, 'http')?$investment->image:(str_starts_with($investment->image, '/')?env('S3_BUCKET_URL').$investment->image:env('S3_BUCKET_URL').'/'.$investment->image);
              if(isset($payment_proof) && $payment_proof!='' && $investment->pay_method=="ibft")
              {
                $extention=explode(".",$payment_proof);
                $extention=$extention[count($extention)-1];
                $payment_proof_file=file_get_contents($payment_proof);
              }
              $amount=$investment->amount;
              $amc_fund_code=$amc_fund_data->amc_fund_code;
              $stan = "016761";
              $payment_mode = "Online";
              $discount = "0.0000";
              $bank_branch = $investment->fund->fund_bank->bank_branch;
              $bank_account_number = $investment->fund->fund_bank->account_number;
              $collection_account_number = $iban=strtoupper($investment->user->cust_bank_detail->iban);
              $bank_id=$investment->user->cust_basic_detail->bank;
              $collection_bank_code=AmcBank::where('amc_id', 1)->where('ypay_bank_id',$bank_id)->pluck('amc_bank_id')->first();
              $unit_class = "XX";
              $unit_plan = "0";
              $narration = "4000000000000".$user_folio_number;
              $curl = curl_init();

              curl_setopt_array($curl, array(
                CURLOPT_URL => $amc_api_data->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{          
                  "folioNumber": "'.$user_folio_number.'",
                  "fundCode": "'.$amc_fund_code.'",
                  "saleAmount": "'.$amount.'",
                  "stan": "'.$stan.'",
                  "transmissionDateAndTime": "'.$transaction_date.'",
                  "paymentMode": "'.$payment_mode.'",
                  "userID": "'.$user_id.'",
                  "discount": "'.$discount.'",
                  "bankBranch": "'.$bank_branch.'",
                  "bankAccountNumber": "'.$bank_account_number.'",
                  "collectionAccountNumber": "'.$collection_account_number.'",
                  "collectionBankCode": "'.$collection_bank_code.'",
                  "unitClass": "'.$unit_class.'",
                  "unitPlan": "'.$unit_plan.'",
                  "narration": "'.$narration.'" 
              }',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json',
                  'apiToken: '. $amc_api_data->access_key
                ),
              ));

              $response = curl_exec($curl);
              $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
              curl_close($curl);
              echo $response;
              $response_json = json_decode($response, true);
              
              if($http_status=="200" && $response_json['saleId'] != "-1")
              {
                $transaction_id = $response_json['saleId'];
                $investment_obj->cnic=$customer_cnic;
                $investment_obj->transaction_id=$transaction_id;
                $investment_obj->type=$investment->pay_method;
                $investment_obj->rrn=$investment->rrn??"";
                $investment_data[]=$investment_obj;
                // $cust_transaction_ids[]=$transaction_id;
                if(isset($payment_proof) && $payment_proof!='' && $investment->pay_method=="ibft")
                {
                  Storage::disk('jsil-ftp')->put($customer_cnic.' '.$customer_name.'/payment/'.Carbon::now()->timestamp.".".$extention, $payment_proof_file);
                }
                $investment->amc_reference_number=$transaction_id;
                $investment->verified=3;
                $investment->verified_at=Carbon::now();
                $investment->save();
              }
              else
              {
                $investment->verified=3;
                $investment->verified_at=Carbon::now();
                $investment->status=3;
                $investment->response_error_message=$response_json['errorMsg'];
                $investment->save();
              }
            }
            else
            {
              $investment->status=2;
              $investment->verified=3;
              $investment->verified_at=Carbon::now();
              $investment->rejected_reason='Account with AMC Not Found';
              $investment->save();
              $user=$investment->user;
              $url = 'https://networks.ypayfinancial.com/api/mailv1/investment_request_denied_by_amc.php';
              $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$investment->fund->amc->entity_name];
              sendEmail($body,$url);
              $data = ['message' => sprintf(Config::get('messages.investment_request_denied_amc'), $investment->fund->amc->entity_name), 'image' => $investment->fund['fund_image']];
              sendNotification($user->fcm_token, $data, $user->id, 'investment_rejected');
            }
          } catch (\Exception $e) {
              echo $e->getMessage();
              $investment->verified=3;
              $investment->verified_at=Carbon::now();
              $investment->status=3;
              $investment->response_error_message=$e->getMessage();
              $investment->save();
              continue;
          }
        }
        if(count($verified_investments)>0 && count($investment_data)>0)
        {
          $amc_email=Amc::where('id',1)->pluck('compliant_email')->first();
          $url  = 'https://networks.ypayfinancial.com/api/mailv1/investment_sent_request.php';
          $body = ["email" => $amc_email,"ids"=>$investment_data,"message"=>"The following customer(s) have submitted Investment request(s).
          
          Your swift action is highly appreciated."];
          sendEmail($body,$url);
        }
      } catch (\Exception $e) {
          $cron_job_log=new CronJobLog;
          $cron_job_log->cron_job_name='Investment Process';
          $cron_job_log->amc_id=2;
          $cron_job_log->status=0;
          $cron_job_log->failure_reason=$e->getMessage();
          $cron_job_log->save();
      }
      //return response(["status"=>200,'message' => 'Verified Investments Transaction Requests Sent Successfully!']);
    }

    public function verifyinvestment(Request $request)
    {
      if(isset($request['investment_id']) && isset($request['verified']))
      {
        Investment::where('id',$request['investment_id'])->update(['verified'=>$request['verified'],'verified_at'=>Carbon::now()]);
        return response(["status"=>200]);
      }
      else
        return response(["error"=>"Error Occured"]);
    }
    public function export(Request $request)
    {
      $selected_investments=$request['selected_investments'];
      foreach($selected_investments as $investment)
      {
        $investment=Investment::with('user','user.cust_cnic_detail', 'fund', 'redemption')->where('id',$investment)->first();
        $filtered_investments[]=$investment;
      }
      $statuses = array("0"=>"Pending", "1"=>"Approved", "2"=>"Rejected", "3"=>"On Hold");
      $verification_status=array("0"=>"Not Verified", "1"=>"Verified", "2"=>"CSV Exported", "3"=>"Sent In API");
      // $investments = Investment::with('user','user.cust_cnic_detail', 'fund', 'redemption')->doesntHave('redemption','or', function($q){
      //   $q->where('status',1)->orWhere('status',0);
      // })->orderBy('id','desc');
      // $filtered_investments=$this->filter($request,$investments)->get();
      $timestamp=Carbon::now()->timestamp;
      $file_name=$timestamp.".csv";
      if(!file_exists('storage/uploads/investment'))
      {
        mkdir('storage/uploads/investment',0777,true);
        chmod('storage/uploads/investment', 0777);
      }
      $investment_csv = fopen('storage/uploads/investment/'.$file_name,"a");
      chmod('storage/uploads/investment/'.$file_name, 0777);
      $heading=array("Transaction Id","Customer Name","CNIC","Fund","Investment Date","Investment Amount","Transaction Status","Approval Date","Verification Status");
      fputcsv($investment_csv, $heading); 
      foreach($filtered_investments as $investment)
      {
        $investment_data=array(
          $investment->transaction_id??"",
          $investment->user->full_name??"",
          $investment->user->cust_cnic_detail->cnic_number??"",
          $investment->fund->fund_name??"",
          $investment->created_at??'',
          $investment->amount??"",
          $statuses[$investment->status]??"",
          $investment->approved_date?date('Y-m-d',strtotime($investment->approved_date)):'',
          $verification_status[$investment->verified]??""
          );
          $investment_csv = fopen('storage/uploads/investment/'.$file_name,"a");
          fputcsv($investment_csv, $investment_data);
      }
      $investments_csv[$file_name]=file_get_contents('storage/uploads/investment/'.$file_name);
      File::delete('storage/uploads/investment/'.$file_name);
      return response()->json(['success'=> true, 'message' => 'Data Exported successfully!',"investment_csv"=>$investments_csv]);
    }
    public function export_selected(Request $request)
    {
      $current_date = date('Ymd');
      $investments=[];
      $selected_investments=$request['selected_investments'];
      foreach($selected_investments as $investment)
      {
        $investment=Investment::with('user','user.cust_cnic_detail','user.amcCustProfile','user.cust_account_detail','user.cust_bank_detail','fund', 'redemption')->where('id',$investment)->first();
        $filtered_investments[]=$investment;
        $payment_proof=str_starts_with($investment->image, 'http')?$investment->image:(str_starts_with($investment->image, '/')?env('S3_BUCKET_URL').$investment->image:env('S3_BUCKET_URL').'/'.$investment->image);
        if(isset($investment->user))
        {
          $cnic_front=str_starts_with($investment->user->cust_cnic_detail->cnic_front, 'http')?$investment->user->cust_cnic_detail->cnic_front:(str_starts_with($investment->user->cust_cnic_detail->cnic_front, '/')?env('S3_BUCKET_URL').$investment->user->cust_cnic_detail->cnic_front:env('S3_BUCKET_URL').'/'.$investment->user->cust_cnic_detail->cnic_front);
          $cnic_back=str_starts_with($investment->user->cust_cnic_detail->cnic_back, 'http')?$investment->user->cust_cnic_detail->cnic_back:(str_starts_with($investment->user->cust_cnic_detail->cnic_back, '/')?env('S3_BUCKET_URL').$investment->user->cust_cnic_detail->cnic_back:env('S3_BUCKET_URL').'/'.$investment->user->cust_cnic_detail->cnic_back);
          if(isset($payment_proof) && $payment_proof!='' && $investment->pay_method=="ibft")
          {
            $customer_cnic=$investment->user->cust_cnic_detail->cnic_number;
            $customer_name=$investment->user->full_name;
            if(isset($investment->user->cust_cnic_detail->cnic_front))
            {
            $cnic_front_file=file_get_contents($cnic_front);
            $cnic_front_ext=explode(".",$cnic_front);
            $cnic_front_ext=$cnic_front_ext[count($cnic_front_ext)-1];
            }
            if(isset($investment->user->cust_cnic_detail->cnic_back))
            {
              $cnic_back_file=file_get_contents($cnic_back);
              $cnic_back_ext=explode(".",$cnic_back);
              $cnic_back_ext=$cnic_back_ext[count($cnic_back_ext)-1];
            }
            $extention=explode(".",$payment_proof);
            $extention=$extention[count($extention)-1];
            $payment_proof_file=file_get_contents($payment_proof);
            Storage::disk('investment-images-ftp')->put($customer_cnic.' '.$customer_name.'/payment/'.Carbon::now()->timestamp.".".$extention, $payment_proof_file);
            Storage::disk('investment-images-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_front.'.$cnic_front_ext, $cnic_front_file);
            Storage::disk('investment-images-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_back.'.$cnic_back_ext, $cnic_back_file);
          }
        }
      }
      foreach($filtered_investments as $investment)
      {
      $amc_profiles[]=AmcCustProfile::where('amc_id',$investment->fund->amc_id)->where('user_id',$investment->user_id)->first();
      }
      $pdf = PDF::loadView('investment_form_pdf', compact('filtered_investments','amc_profiles'));
      $path = public_path();
      $fileName =  $current_date.'.pdf' ;
      $pdf->save($path . '/' . $fileName);
      $pdf = public_path($fileName);
      return response()->download($pdf);
    }
    public function show(Request $request)
    {
      $investments = Investment::with('user','user.cust_cnic_detail','user.change_request','user.amcCustProfiles','user.cust_account_detail','user.cust_bank_detail','fund', 'redemption')->doesntHave('redemption','or', function($q){
        $q->where('status',1)->orWhere('status',0);
      })->doesntHave('conversions');
      return DataTables::of($this->filter($request, $investments))->make(true);
    }
    
    public function download_form(Request $request)
    {
      $current_date = date('Ymd');
      $investments = Investment::with('user','user.cust_cnic_detail','user.cust_account_detail','user.cust_bank_detail','fund', 'redemption')->doesntHave('redemption','or', function($q){
        $q->where('status',1)->orWhere('status',0);
      });
      $filtered_investments=$this->filter($request,$investments)->get();
      foreach($filtered_investments as $investment)
      {
      $amc_profiles[]=AmcCustProfile::where('amc_id',$investment->fund->amc_id)->where('user_id',$investment->user_id)->first();
      }
      PDF::set_paper('letter', 'landscape');
      $pdf = PDF::loadView('investment_form_pdf', compact('filtered_investments','amc_profiles'));
      return $pdf->download($current_date.'.pdf');
    }
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'user_id'        => 'required',
        'fund_id'        => 'required',
        'amount'         => 'required',
        'image'          => 'required',
        'pay_method'     => 'required',
        'nav'            => 'required',
        'unit'           => 'required',
        'approved_date'  => 'required',
        // 'account_number' => 'required',
        // 'reference'      => 'required'
      ]);
      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }

        $file             = $request->file('image');
        $fileOriginalName = $file->getClientOriginalName();
        $extension        = $file->getClientOriginalExtension();
        $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "investments/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);

      $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $string = substr(str_shuffle($chars), 0, 8);
      $amc_fund_data=AmcFund::where('ypay_fund_id',$request->fund_id)->first();
      $investment                 = new Investment;
      $investment->transaction_id = $string;
      $investment->user_id        = $request->user_id;
      $investment->fund_id        = $request->fund_id;
      if(isset($amc_fund_data))
      $investment->amc_fund_id    =$amc_fund_data->id;
      $investment->amount         = $request->amount;
      $investment->admin_comment  = $request->admin_comment;
      $investment->pay_method     = $request->pay_method;
      $investment->image          = $filename;
      $investment->sales_load     = $request->sales_load;
      $investment->frequency     =  "Monthly";
      $investment->pay_option     = "100%";
      $investment->nav            = $request->nav;
      $investment->unit           = $request->unit;
      $investment->approved_date  = $request->approved_date;
      $investment->status         = 1;
      // $investment->amc_reference_number=$request->amc_reference_number;
      // $investment->account_number = $request->account_number;
      // $investment->reference      = $request->reference;
      // $investment->approved_date   = Carbon::now();
      $investment->save();

      return response()->json(['success'=> true, 'message' => 'Investment Created successfully!']);
    }
    public function edit($id)
    {
      $investment = Investment::with('user','user.cust_cnic_detail', 'fund')->where('id',$id)->first();

      return view('investment.investmentDetail',compact('investment'));
    }


    public function update(Request $request, Investment $investment)
    {
      // dd($request->all());
  
      $validator = Validator::make($request->all(), [
        'status'          => 'required',
        'nav'             => 'required_if:status,1',
        'unit'            => 'required_if:status,1',
        'amount'          => 'required',
        //'created_at'      => 'required',
        'approved_date'   => 'required_if:status,1'
        // 'account_number'  => 'required_if:status,1',
        // 'reference'       => 'required_if:status,1',
      ],
      [
        'nav.required_if' => 'This field is required',
        'unit.required_if' => 'This field is required',
        'approved_date.required_if' => 'This field is required',
        'amount.required' => 'This field is required',
        //'approved_date.gte' => 'Approval Date Cannot be Less than Transaction Date',
        // 'account_number.required_if' => 'This field is required',
        // 'reference.required_if' => 'This field is required',

      ]);
      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }
      $oldIvestment = $investment->find($investment->id);

      $user = $investment->user;
      $investment->status         = $request->status ?? 0;
      $investment->nav            = $request->nav;
      $investment->unit           = $request->unit;
      $investment->amount         = $request->amount;
      $investment->sales_load     = $request->sales_load;
      $investment->frequency     =  "Monthly";
      $investment->pay_option     = "100%";
      $investment->admin_comment  = $request->admin_comment;
      $investment->approved_date  = $request->approved_date??Carbon::now();
      // $investment->account_number = $request->account_number;
      // $investment->reference      = $request->reference;
      $investment->save();

    
      if($investment->status == 1 && $oldIvestment->status != 1) {
        // Mail::send('mail.userInvestmentStatusApproved', ['name'=> $user->full_name], function($message) use ($user) {
        //   $message->to($user->email, $user->full_name)->subject('Investment Approved');
        //   $message->from('hello@ypayfinancial.com', 'YPay');
        // });

         // email notificaion 
         $url = 'https://networks.ypayfinancial.com/api/mailv1/approve_investment.php';
         $body = ['email' => $user->email, 'name'=>$user->full_name];
         sendEmail($body,$url);

        $data = ['message' => Config::get('messages.investment_request_approved'), 'image' => $investment->fund['fund_image']];
        sendNotification($user->fcm_token, $data, $user->id, 'Congratulations! 🎉Your investment request at '.$investment->fund->amc->entity_name.' has been approved ✅');
      }
      else if($investment->status == 2 && $oldIvestment->status != 2){
        // Mail::send('mail.userInvestmentStatusRejected', ['name'=> $user->full_name], function($message) use ($user) {
        //   $message->to($user->email, $user->full_name)->subject('Investment Rejected');
        //   $message->from('hello@ypayfinancial.com', 'YPay');
        // });

        // email notificaion 
        $url = 'https://networks.ypayfinancial.com/api/mailv1/reject_investment.php';
        $body = ['email' => $user->email, 'name'=>$user->full_name];
        sendEmail($body,$url);

        $data = ['message' => Config::get('messages.investment_request_denied'), 'image' => $investment->fund['fund_image']];
        sendNotification($user->fcm_token, $data, $user->id, 'Uh-Oh, we have hit a little hiccup 🙃');
      }


      return response()->json(['success'=> true, 'message' => 'Investment updated successfully!']);
    }
    
    public function showRedemption()
    {
      return view('investment.redemption');
    }
    public function getRedemption()
    {
      $investment = Redemption::with('investment','investment.user')->get();
       return DataTables::of($investment)->make(true);
    }

    public function updateRedemption(Request $request)
    {
      $investment = Redemption::with('investment','investment.user')->get();
       return DataTables::of($investment)->make(true);
    }

    public function getInvestmentPdf($id)
    {
      $data = Investment::with('user.cust_cnic_detail','fund')->where('id',$id)->first();
      $customPaper = array(0,0,720,1440);
      $pdf = PDF::loadView('investment.investment-request-form', compact('data'))->setPaper($customPaper,'portrait');

      return $pdf->download('Investment-request-Ypay.pdf');
    }
    public function filter($request, $investments)
    {

     
      try {
        
  
        if (isset($request->customerName) && $request->customerName != "null") {
          $customer = $request->customerName;
            
            $investments = $investments->where('user_id',$customer);
            
          }
          
          
          if (isset($request->fund) && $request->fund != "null") {
            $fund = $request->fund;
        
            $investments = $investments->where('fund_id',$fund);
           
          }
      
          
        if(isset($request->amc) && $request->amc != "null"){
          $amc = $request->amc;
         
          $investments = $investments->whereHas('fund.amc', function ($q) use ($amc) {
            $q->where('id',$amc);
          });

          
        }
        if(isset($request->refer_code) && $request->refer_code != "null"){
          $refer_code = $request->refer_code;
         
          $investments = $investments->whereHas('user', function ($q) use ($refer_code) {
            $q->where('refer_code','like', '%' .$refer_code. '%');
          });

          
        }
        if (isset($request->approvedDateFrom) && isset($request->approvedDateTo) && $request->approvedDateTo != "null" && $request->approvedDateFrom != "null") {
  
          $approvedDateFrom = $request->approvedDateFrom;
          $approvedDateTo = $request->approvedDateTo;
          $investments = $investments->whereBetween('approved_date',[date("Y-m-d H:i:s", strtotime($approvedDateFrom)),date("Y-m-d H:i:s", strtotime($approvedDateTo))]);
        }
        if (isset($request->from) && isset($request->to) && $request->from!= "null" && $request->to!= "null") {       
          $transaction_from=$request->from;
          $transaction_to=$request->to;
          $investments = $investments->whereBetween('created_at',[[date("Y-m-d H:i:s", strtotime($transaction_from)),date("Y-m-d H:i:s", strtotime($transaction_to))]]);
          
        }
        if (isset($request->status) && $request->status != "null") {       
          $investments = $investments->where('status',$request->status);
        }
        if (isset($request->min_amount) && $request->min_amount != "null") {       
           
          $investments = $investments->where('amount','>=',$request->min_amount);
          
        }
        if (isset($request->max_amount) && $request->max_amount != "null") {       
           
          $investments = $investments->where('amount','<=',$request->max_amount);
          
        }
        if (isset($request->unit) && $request->unit != "null") {       
           
          $investments = $investments->where('unit',$request->unit);
          
        }
        if (isset($request->nav) && $request->nav != "null") {       
           
          $investments = $investments->where('nav',$request->nav);
          
        }
        if (isset($request->cnic) && $request->cnic != "null") {
  
          $cnic = $request->cnic;
          $investments = $investments->whereHas('user.cust_cnic_detail', function($q) use($cnic) {
            $q->where('cnic_number', 'like', '%' .$cnic. '%');
          });
        }

        if (isset($request->folio_number) && $request->folio_number != "null") {
  
          $folio_number = $request->folio_number;
          $amc_profile=AmcCustProfile::where('account_number',$folio_number)->first();
          // $investments = $investments->whereHas('user.amcCustProfiles', function($q) use($folio_number) {
          //   $q->where('account_number', 'like', '%' .$folio_number. '%')->;
          // });
          $investments =$investments->where('user_id',$amc_profile->user_id)->whereHas('fund', function ($q)use($amc_profile){
            $q->where('amc_id',$amc_profile->amc_id);
          })->get();
        }

        if (isset($request->reference) && $request->reference != "null") {   
                
          $investments = $investments->where('reference','like', '%' . $request->reference . '%');
          
        }
        if (isset($request->verified) && $request->verified!="null") {          
          $investments = $investments->where('verified',$request->verified);
          
        }
        return $investments;
  
      } catch (\Exception $e) {
       echo "<pre>";
       print_r($e);
       echo "</pre>";
        return ['error' => 'Something went wrong'];
      }
    }

    public function customerDropDownList(Request $request)
    {
        try {
            $data = [];
            $queryTerm = $request->q;
            $customers = User::with('cust_cnic_detail')->whereHas('cust_account_detail', function ($q){ 
              $q->whereIn('status',[0,1]);
            })->where('type',2)->where('full_name', 'like', '%' . $queryTerm . '%')->get();
            foreach ($customers as $customer) {
                $data[] = ['id' => $customer->id, 'text' => $customer->full_name.' - '.$customer->cust_cnic_detail->cnic_number];
            }
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function fundDropDownList(Request $request)
    {
        try {
            $data = [];
            $queryTerm = $request->q;
            $departments =  Fund::whereHas('additional_details', function($q) {
                $q->where('status', 1);
            })->where('fund_name', 'like', '%' . $queryTerm . '%')->get();
            foreach ($departments as $department) {
                $data[] = ['id' => $department->id, 'text' => $department->fund_name];
            }
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function investmentDropDownList(Request $request)
    {
        try {
            $data = [];
            $queryTerm = $request->q;
            $investments =  Investment::with('user')->doesntHave('redemption','or', function($q){
              $q->where('status',1)->orWhere('status',0);
            })
            ->where('status',1)
            ->where( function($q) use ($queryTerm){
              $q->where('transaction_id', 'like',  $queryTerm . '%')
              ->orWhereHas('user', function ($r) use ($queryTerm){
                $r->where('full_name', 'like',  $queryTerm . '%')
                ->orWhereHas('cust_cnic_detail', function ($s) use ($queryTerm){
                  $s->where('cnic_number',  'like',  $queryTerm . '%' );
                });
              });
              
            })
            ->get();
            foreach ($investments as $investment) {
                $data[] = ['id' => $investment->id, 'text' => $investment->transaction_id.' - '.$investment->user->full_name.' - '.'PKR.'.$investment->amount.'-'.$investment->user->cust_cnic_detail->cnic_number , 'customer_name' => $investment->user->full_name];
            }
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
   
}
