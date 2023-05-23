<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Investment;
use App\Models\AmcCustProfile;
use App\Models\Redemption;
use App\Models\Amc;
use App\Models\User;
use App\Models\AmcAPI;
use App\Models\AmcDataLog;
use App\Models\AmcFund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Configurations;
use Illuminate\Support\Arr;
use File;
use App\Models\CronJobLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PDF;
use Mail;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use Config;

class RedemptionController extends Controller
{
  // function __construct()
  //   {
  //        $this->middleware('permission:redemption-list');
  //        $this->middleware('permission:redemption-edit', ['only' => ['update']]);
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
        return view('investment.redemption',compact('users'));
    }
    public function sendtoamc(Request $request)
    {
       $verified_redemptions=Redemption::where('verified',1)->get();
       $timestamp=time();
       foreach($verified_redemptions as $redemption)
       {

        $redemption_data=array(
        $redemption->investment->fund->amc_reference_number??"",
        $redemption->amount??"",
        $redemption->investment->transaction_id??"",
        $redemption->transaction_id??"",
        $redemption->investment->user->cust_cnic_detail->cnic_number??"",
        $redemption->investment->account_number??"",
        );
        if($redemption->investment->fund->amc->through_csv==1)
        {

          $amc_name=$redemption->investment->fund->amc->entity_name;
          $amc_name=strtolower(str_replace(' ','_',$amc_name));
          $redemption_folder_path="storage/uploads/redemption/".$amc_name."/".$timestamp;
          $redemption_file_name=$amc_name."_".$timestamp."_redemption.csv";
          $redemption_file_path="storage/uploads/redemption/".$amc_name."/".$timestamp."/";
          if(!file_exists($redemption_folder_path))
          {
            mkdir($redemption_folder_path,0777,true);
            $redemption_csv = fopen($redemption_file_path.$redemption_file_name,"a");
            $heading=array("Mutual Fund","Request Amount","Investment Transaction Id","Transaction Id","Customer CNIC");
            fputcsv($redemption_csv, $heading); 
          }
          $redemption_csv = fopen($redemption_file_path.$redemption_file_name,"a");
          fputcsv($redemption_csv, $redemption_data);
          rewind($redemption_csv);
        }
        // if($redemption->investment->fund->amc->through_email==1)
        // {

        // }
        // else if($redemption->investment->fund->amc->through_drive==1)
        // {
          
        // }
        // else
        // {

        // }
      }
      $redemption_csvs=array();
      foreach($verified_redemptions as $redemption)
      {
        if($redemption->investment->fund->amc->through_csv==1)
        {
        $amc_name=$redemption->investment->fund->amc->entity_name;
        $amc_name=strtolower(str_replace(' ','_',$amc_name)); 
        $redemption_folder_path="storage/uploads/redemption/".$amc_name."/".$timestamp;
        $redemption_file_name=$amc_name."_".$timestamp."_redemption.csv";
        $redemption_file_path="storage/uploads/redemption/".$amc_name."/".$timestamp."/";
        $redemption_csvs[$redemption_file_name]=file_get_contents($redemption_file_path.$redemption_file_name);
        $redemption->verified=2;
        $redemption->verified_at=Carbon::now();
        $redemption->save();
        }
      }
      return response()->json(['success'=> true, 'message' => 'Data Sent successfully!',"redemption_csvs"=>$redemption_csvs]);
    }
    public function verifyredemption(Request $request)
    {
      if(isset($request['redemption_id']) && isset($request['verified']))
      {
      Redemption::where('id',$request['redemption_id'])->update(['verified'=>$request['verified'],'verified_at'=>Carbon::now()]);
      return response(["status"=>200]);
      }
      else
      return response(["error"=>"Error Occured"]);
    }
    public function redemption_inquiry()
    {
      $pending_redemptions=Redemption::where('status',0)->whereNotNull('amc_reference_number')->whereHas('investment', function ($q){
        $q->whereHas('fund', function ($qry){
          $qry->where('amc_id',2);
        });
      })->get();
      try{
      $redemption_data=[];
      $flag=true;
      $current_date = date('Y_m_d');
      foreach($pending_redemptions as $redemption)
      {
        try{
        $folio_number=AmcCustProfile::where('user_id',$redemption->investment->user->id)->where('amc_id',2)->where('status',1)->pluck('account_number');
        if(isset($folio_number[0]))
        {
        $user_folio_number=$folio_number[0]??'';
        $transaction_id=$redemption->	amc_reference_number;
        $amc_api_data=AmcAPI::where('amc_id',2)->where('name','Redemption Inquiry API')->first();
        $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name)); 
        $redemption_file_name=$current_date.".csv";
        $customer_cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
        $amc_name=$redemption->investment->fund->amc->entity_name;
        $curl = curl_init();
        $url=$amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&Folio='.$user_folio_number.'&FormNo='.$transaction_id;
        $url=str_replace(' ','%20',$url);
        $amc_dataset_log= new AmcDataLog;
        $amc_dataset_log->dataset_url= $url;
        $amc_dataset_log->api_name=$amc_api_data->name;
        $amc_dataset_log->amc_id=$amc_api_data->amc_id;
        $amc_dataset_log->user_id=$redemption->user_id;
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
          if(!file_exists('storage/uploads/technical_log_2/'.$redemption_file_name))
          {
              $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$redemption_file_name, 0777);
              $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
              fputcsv($redemption_csv, $heading);
          }
          $technical_error_detail=array(
            $customer_cnic,
            $amc_name,
            $amc_api_data->name,
            'VPN Not Connected',
            Carbon::now()
            );
            $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
            fputcsv($redemption_csv, $technical_error_detail);
            rewind($redemption_csv);
            $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$redemption_file_name);
            Storage::disk('technical-log-ftp')->put($redemption_file_name, $technical_error_csv);
            // File::delete('storage/uploads/technical_log_2/'.$redemption_file_name);
            die();
        }
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $dom->savexml();
        $status = $dom->getElementsByTagName('Response');
        $status = $status->item(0)->nodeValue;
        if($status=="0")
        {
          $redemption->status=1;
          $units = $dom->getElementsByTagName('Redemption_x0020_Units');
          $redeem_units = $units->item(0)->nodeValue;
          // $amount = $dom->getElementsByTagName('Redemption_x0020_Amount');
          // $redeem_amount = $amount->item(0)->nodeValue;
          $redemption->redeem_units=$redeem_units;
          // $redemption->redeem_amount=$redeem_amount;
          $redemption->approved_date=Carbon::now();
          $redemption->save();
          $user=$redemption->investment->user;
          $url = 'https://networks.ypayfinancial.com/api/mailv1/approve_redemption.php';
          $body = ['email' => $user->email??'', 'name'=>$user->full_name??''];
          sendEmail($body,$url);
          $data = ['message' => Config::get('messages.redemption_request_approved'), 'image' => $redemption->investment->fund['fund_image']??''];
          sendNotification($user->fcm_token, $data, $user->id??"", 'Congratulations! 🎉Your redemption request has been approved ✅');
          $redemption_obj=new \stdclass();
          $redemption_obj->cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
          $redemption_obj->transaction_id=$transaction_id;
          $redemption_data[]=$redemption_obj;
        }
        else if($status=="1")
        {
          $redemption->status=3;
          $redemption->rejected_reason='Transaction Cancelled';
          $redemption->save();
          $user=$redemption->investment->user;
          // $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
          // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
          // sendEmail($body,$url);
          // $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
          // sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
          $redemption_obj=new \stdclass();
          $redemption_obj->cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
          $redemption_obj->transaction_id=$transaction_id;
          $redemption_data[]=$redemption_obj;
        }
        else if($status=="2")
        {
          $redemption->status=3;
          $redemption->rejected_reason='Transaction Reversed';
          $redemption->save();
          $user=$redemption->investment->user;
          // $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
          // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
          // sendEmail($body,$url);
          // $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
          // sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
          $redemption_obj=new \stdclass();
          $redemption_obj->cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
          $redemption_obj->transaction_id=$transaction_id;
          $redemption_data[]=$redemption_obj;
        }
        else if($status=="3")
        {
        }
        else if($status=="4")
        {
          $redemption->status=3;
          $redemption->rejected_reason='No Data Found';
          $redemption->save();
          $user=$redemption->investment->user;
          // $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
          // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
          // sendEmail($body,$url);
          // $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
          // sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
          $redemption_obj=new \stdclass();
          $redemption_obj->cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
          $redemption_obj->transaction_id=$transaction_id;
          $redemption_data[]=$redemption_obj;
        }
        else if($status=="5")
        {
          if(!file_exists('storage/uploads/technical_log_2'))
          {
            mkdir('storage/uploads/technical_log_2',0777,true);
            chmod('storage/uploads/technical_log_2', 0777);
          }
          if(!file_exists('storage/uploads/technical_log_2/'.$redemption_file_name))
          {
            $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
            chmod('storage/uploads/technical_log_2/'.$redemption_file_name, 0777);
            $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
            fputcsv($redemption_csv, $heading);
          }
          $redemption->status=3;
          $redemption->rejected_reason='Other Exception -'.$amc_api_data->name;
          $redemption->save();
          $user=$redemption->investment->user;
          // $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
          // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
          // sendEmail($body,$url);
          // $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
          // sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
          $redemption_obj=new \stdclass();
          $redemption_obj->cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
          $redemption_obj->transaction_id=$transaction_id;
          $redemption_data[]=$redemption_obj; 
          $technical_error_detail=array(
            $customer_cnic,
            $amc_name,
            $amc_api_data->name,
            $status,
            Carbon::now()
            );
            $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
            fputcsv($redemption_csv, $technical_error_detail);
            rewind($redemption_csv);
            $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$redemption_file_name);
            Storage::disk('technical-log-ftp')->put($redemption_file_name, $technical_error_csv);
            // File::delete('storage/uploads/technical_log_2/'.$redemption_file_name);
        }
        }
      }
      catch (\Exception $e) {
       continue;
      }
      }
      $cron_job_log=new CronJobLog;
      $cron_job_log->cron_job_name='Redemption Inquiry';
      $cron_job_log->amc_id=2;
      $cron_job_log->status=1;
      $cron_job_log->save();
      if(count($pending_redemptions)>1 && count($redemption_data)>0)
      {
      $ypay_email=Configurations::where('name','YPay Email')->pluck('value')->first();
      $url  = 'https://networks.ypayfinancial.com/api/mailv1/redemption_sent_request.php';
      $body = ["email" => $ypay_email,"ids"=>$redemption_data,"message"=>"The following customer(s) have submitted Redemption request(s).
      
      Your swift action is highly appreciated."];
      sendEmail($body,$url);
      }
    }
    catch (\Exception $e) {
      $cron_job_log=new CronJobLog;
      $cron_job_log->cron_job_name='Redemption Inquiry';
      $cron_job_log->amc_id=2;
      $cron_job_log->status=0;
      $cron_job_log->failure_reason=$e->getMessage();
      $cron_job_log->save();
     }
      //return response(["status"=>200,'message' => 'All Pending Redemptions Statuses Updated Successfully!']);
    }
    public function redemption_process()
    {
      $verified_redemptions=Redemption::where('verified',1)->where('status',0)->whereHas('investment', function ($q){
        $q->whereHas('fund', function ($qry){
          $qry->where('amc_id',2);
        });
      })->get();
      try{
      $redemption_data=[];
      $flag=true;
      $current_date = date('Y_m_d');
      foreach($verified_redemptions as $redemption)
      {
        try{
          $amc_api_data=AmcAPI::where('amc_id',2)->where('name','Save Redemption Transaction API')->first();
          $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name)); 
          $folio_number=AmcCustProfile::where('user_id',$redemption->investment->user->id)->where('amc_id',2)->where('status',1)->pluck('account_number');
          if(isset($folio_number[0]))
          {
          $user_folio_number=$folio_number[0];
          $customer_cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
          // $customer_cnic=str_replace('-','',$customer_cnic);
          $transaction_date=$redemption->created_at;
          $transaction_date=date('Y-m-d',strtotime($transaction_date));
          $transaction_time=Carbon::now()->format('H:i');
          $transaction_time=str_replace(':','',$transaction_time);
          $amc_fund_data=AmcFund::where('ypay_fund_id',$redemption->investment->fund_id)->first();
          $fund_name=$amc_fund_data->amc_fund_name;
          $fund_unit=$amc_fund_data->amc_fund_unit_type;
          $fund_class=$amc_fund_data->amc_fund_class_type;
          $redeem_units=(float) $redemption->investment->unit;
          $redeem_amount=(float) "0.0";
          $redeem_by=$redemption->redeem_by;
          $redemption_file_name=$current_date.".csv";
          $amc_name=$redemption->investment->fund->amc->entity_name;
          $curl = curl_init();
          $url=$amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&AccCode='.$user_folio_number.'&TranDate='.$transaction_date.
          '&TranTime='.$transaction_time.'&FundName='.$fund_name.'&FundUnitClass='.$fund_class.'&FundUnitType='.$fund_unit.
          '&RedeemUnits='.$redeem_units.'&RedeemAmount='.	$redeem_amount.'&RedeemBy='.$redeem_by;
          $url=str_replace(' ','%20',$url);
          $amc_dataset_log= new AmcDataLog;
          $amc_dataset_log->dataset_url= $url;
          $amc_dataset_log->api_name=$amc_api_data->name;
          $amc_dataset_log->amc_id=$amc_api_data->amc_id;
          $amc_dataset_log->user_id=$redemption->user_id;
          $amc_dataset_log->save();
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
            if(!file_exists('storage/uploads/technical_log_2'))
            {
              mkdir('storage/uploads/technical_log_2',0777,true);
              chmod('storage/uploads/technical_log_2', 0777);
            }
            if(!file_exists('storage/uploads/technical_log_2/'.$redemption_file_name))
            {
              $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$redemption_file_name, 0777);
              $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
              fputcsv($redemption_csv, $heading);
            }
            $technical_error_detail=array(
              $customer_cnic,
              $amc_name,
              $amc_api_data->name,
              'VPN Not Connected',
              Carbon::now()
              );
              $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
              fputcsv($redemption_csv, $technical_error_detail);
              rewind($redemption_csv);
              $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$redemption_file_name);
              Storage::disk('technical-log-ftp')->put($redemption_file_name, $technical_error_csv);
              // File::delete('storage/uploads/technical_log_2/'.$redemption_file_name);
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
          echo $status;
          if($status=="1")
          {
            $transaction_id = $dom->getElementsByTagName('TransactionID');
            $transaction_id = $transaction_id->item(0)->nodeValue;
            $redemption_obj=new \stdclass();
            $redemption_obj->cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
            $redemption_obj->transaction_id=$transaction_id;
            $redemption_data[]=$redemption_obj;
            $redemption->amc_reference_number=$transaction_id;
            $redemption->verified=3;
            $redemption->verified_at=Carbon::now();
            $redemption->save();
          }
          else
          {
            $redemption->status=3;
            $redemption->verified=3;
            $redemption->verified_at=Carbon::now();
            if(!file_exists('storage/uploads/technical_log_2/'.$redemption_file_name))
            {
              $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$redemption_file_name, 0777);
              $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
              fputcsv($redemption_csv, $heading);
            }
            $error_message=Config::get('save_redemption_response_errors.'.$status);
            $redemption->response_error_message=$error_message;
            $technical_error_detail=array(
              $customer_cnic,
              $amc_name,
              $amc_api_data->name,
              $error_message,
              Carbon::now()
              );
              $redemption_csv = fopen('storage/uploads/technical_log_2/'.$redemption_file_name,"a");
              fputcsv($redemption_csv, $technical_error_detail);
              rewind($redemption_csv);
              $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$redemption_file_name);
              Storage::disk('technical-log-ftp')->put($redemption_file_name, $technical_error_csv);
              // File::delete('storage/uploads/technical_log_2/'.$redemption_file_name);
            $redemption->rejected_reason='Other Exception -'.$amc_api_data->name;
            $redemption->save();
            $user=$redemption->investment->user;
            $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
            $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
            sendEmail($body,$url);
            $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
            sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
          }
          }
          else
          {
            $redemption->status=2;
            $redemption->verified=3;
            $redemption->verified_at=Carbon::now();
            $redemption->rejected_reason='Account with AMC Not Found';
            $redemption->save();
            $user=$redemption->investment->user;
            $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
            $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
            sendEmail($body,$url);
            $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
            sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
          }
        }
        catch (\Exception $e) {
        continue;
        }  
      }
      $cron_job_log=new CronJobLog;
      $cron_job_log->cron_job_name='Redemption Process';
      $cron_job_log->amc_id=2;
      $cron_job_log->status=1;
      $cron_job_log->save();
      if(count($verified_redemptions)>0 && count($redemption_data)>0)
      {
      $amc_email=Amc::where('id',2)->pluck('compliant_email')->first();
      $url  = 'https://networks.ypayfinancial.com/api/mailv1/redemption_sent_request.php';
      $body = ["email" => $amc_email,"ids"=>$redemption_data,"message"=>"The following customer(s) have submitted Redemption request(s).
      
      Your swift action is highly appreciated."];
      sendEmail($body,$url);
      }
     }
     catch (\Exception $e) {
      $cron_job_log=new CronJobLog;
      $cron_job_log->cron_job_name='Redemption Process';
      $cron_job_log->amc_id=2;
      $cron_job_log->status=0;
      $cron_job_log->failure_reason=$e->getMessage();
      $cron_job_log->save();
     }
      //return response(["status"=>200,'message' => 'Verified Redemptions Transaction Requests Sent Successfully!']);
    }
    public function jsil_redemption_process()
    {
      $verified_redemptions=Redemption::where('verified',1)->where('status',0)->whereHas('investment', function ($q){
        $q->whereHas('fund', function ($qry){
          $qry->where('amc_id',1);
        });
      })->get();
      try{
        $redemption_data=[];
        $flag=true;
        $current_date = date('Y_m_d');
        $amc_api_data=AmcAPI::where('amc_id',1)->where('name','JSIL Save Redemption Transaction API')->first();
        foreach($verified_redemptions as $redemption)
        {
          try{
            $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name)); 
            $folio_number=AmcCustProfile::where('user_id',$redemption->investment->user->id)->where('amc_id',1)->where('status',1)->pluck('account_number');
            if(isset($folio_number[0]))
            {
              $user_folio_number=$folio_number[0];
              $customer_cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
              $user_id = str_replace('-', '', $customer_cnic);
              // $customer_cnic=str_replace('-','',$customer_cnic);
              // $transaction_date=$redemption->created_at;
              // $transaction_date=date('Y-m-d',strtotime($transaction_date));
              // $transaction_time=Carbon::now()->format('H:i');
              // $transaction_time=str_replace(':','',$transaction_time);
              $amc_fund_data=AmcFund::where('ypay_fund_id',$redemption->investment->fund_id)->first();
              $amc_fund_code=$amc_fund_data->amc_fund_code;
              // $fund_name=$amc_fund_data->amc_fund_name;
              // $fund_unit=$amc_fund_data->amc_fund_unit_type;
              // $fund_class=$amc_fund_data->amc_fund_class_type;
              $redeem_units=(float) $redemption->investment->unit;
              $redeem_amount=(float) "00";
              $redeem_by=$redemption->redeem_by;
              // $redemption_file_name=$current_date.".csv";
              $amc_name=$redemption->investment->fund->amc->entity_name;
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
                  "redemptionTypeUnitsAmt":"U",
                  "unCertifiedQty":"'.$redeem_units.'",
                  "redemptionAmount":"'.$redeem_amount.'",
                  "userID": "'.$user_id.'",
                  "unitClass": "XX",
                  "unitPlan": "0",
                  "paymentInstruction":"SD"
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

              if($http_status=="200" && $response_json['redemptionId'] != "-1")
              {
                $transaction_id = $response_json['redemptionId'];
                $redemption_obj=new \stdclass();
                $redemption_obj->cnic=$redemption->investment->user->cust_cnic_detail->cnic_number;
                $redemption_obj->transaction_id=$transaction_id;
                $redemption_data[]=$redemption_obj;
                $redemption->amc_reference_number=$transaction_id;
                $redemption->verified=3;
                $redemption->verified_at=Carbon::now();
                $redemption->save();
              }
              else
              {
                $redemption->status=3;
                $redemption->verified=3;
                $redemption->verified_at=Carbon::now();
                $redemption->response_error_message=$response_json['errorMsg'];
                $redemption->rejected_reason=$response_json['errorMsg'];
                $redemption->save();
                // $user=$redemption->investment->user;
                // $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
                // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
                // sendEmail($body,$url);
                // $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
                // sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
              }
            }
            else
            {
              $redemption->status=2;
              $redemption->verified=3;
              $redemption->verified_at=Carbon::now();
              $redemption->rejected_reason='Account with AMC Not Found';
              $redemption->save();
              $user=$redemption->investment->user;
              $url = 'https://networks.ypayfinancial.com/api/mailv1/redemption_request_denied_by_amc.php';
              $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$redemption->investment->fund->amc->entity_name];
              sendEmail($body,$url);
              $data = ['message' => sprintf(Config::get('messages.redemption_request_denied_amc'), $redemption->investment->fund->amc->entity_name), 'image' => $redemption->investment->fund['fund_image']];
              sendNotification($user->fcm_token, $data, $user->id, 'redemption_rejected');
            }
          }
          catch (\Exception $e) {
            $redemption->status=3;
            $redemption->verified=3;
            $redemption->verified_at=Carbon::now();
            $redemption->rejected_reason=$e->getMessage();;
            $redemption->save();
            continue;
          }  
        }
        if(count($verified_redemptions)>0 && count($redemption_data)>0)
        {
          $amc_email=Amc::where('id',1)->pluck('compliant_email')->first();
          $url  = 'https://networks.ypayfinancial.com/api/mailv1/redemption_sent_request.php';
          $body = ["email" => $amc_email,"ids"=>$redemption_data,"message"=>"The following customer(s) have submitted Redemption request(s).
          
          Your swift action is highly appreciated."];
          sendEmail($body,$url);
        }
      }
      catch (\Exception $e) {
        $cron_job_log=new CronJobLog;
        $cron_job_log->cron_job_name='JSIL Redemption Process';
        $cron_job_log->amc_id=1;
        $cron_job_log->status=0;
        $cron_job_log->failure_reason=$e->getMessage();
        $cron_job_log->save();
      }
      //return response(["status"=>200,'message' => 'Verified Redemptions Transaction Requests Sent Successfully!']);
    }
    public function export_selected(Request $request)
    {
      $current_date = date('Ymd');
      $filtered_redemptions=[];
      $selected_redemptions=$request['selected_redemptions'];
      foreach($selected_redemptions as $redemption)
      {
        $redemption=Redemption::query()
        ->select(\DB::raw('redemptions.*','investments.transaction_id','users.full_name','funds.fund_name'))
        ->with('investment','investment.user.cust_cnic_detail','investment.fund','conversion', 'conversion.user.cust_cnic_detail','conversion.fund')
        ->leftJoin('investments','investments.id','=','redemptions.invest_id')
        ->leftJoin('users','users.id','=','investments.user_id')
        ->leftJoin('funds','funds.id','=','investments.fund_id')->where('redemptions.id',$redemption)->first();
        $filtered_redemptions[]=$redemption;
      }
      foreach($filtered_redemptions as $redemption)
      {
      if($redemption->type== "investment")
      $amc_profiles[]=AmcCustProfile::where('amc_id',$redemption->investment->fund->amc_id)->where('user_id',$redemption->investment->user_id)->first();
      else
      $amc_profiles[]=AmcCustProfile::where('amc_id',$redemption->conversion->fund->amc_id)->where('user_id',$redemption->conversion->user_id)->first();
      }
      if(count($filtered_redemptions)==1 && isset($request['redeem_units']))
      {
      $redeem_units=$request['redeem_units'];
      $pdf = PDF::loadView('redemption_form_pdf', compact('filtered_redemptions','redeem_units','amc_profiles'));
      }
      else
      $pdf = PDF::loadView('redemption_form_pdf', compact('filtered_redemptions','amc_profiles'));
      $path = public_path();
      $fileName =  $current_date.'.pdf' ;
      $pdf->save($path . '/' . $fileName);
      $pdf = public_path($fileName);
      return response()->download($pdf);
    }
    public function export(Request $request)
    {
      $statuses = array("0"=>"Pending", "1"=>"Approved", "2"=>"Rejected", "3"=>"On Hold");
      $selected_redemptions=$request['selected_redemptions'];
      $verification_status=array("0"=>"Not Verified", "1"=>"Verified", "2"=>"CSV Exported", "3"=>"Sent In API");
      // $redemptions = Redemption::query()
      // ->select(\DB::raw('redemptions.*','investments.transaction_id','users.full_name','funds.fund_name'))
      // ->with('investment','investment.user.cust_cnic_detail','investment.fund')
      // ->leftJoin('investments','investments.id','=','redemptions.invest_id')
      // ->leftJoin('users','users.id','=','investments.user_id')
      // ->leftJoin('funds','funds.id','=','investments.fund_id')->orderBy('redemptions.id','desc');
      // $filtered_redemptions=$this->filter($request,$redemptions)->get();
      foreach($selected_redemptions as $redemption)
      {

        $redemption = Redemption::query()
        ->select(\DB::raw('redemptions.*','investments.transaction_id','users.full_name','funds.fund_name'))
        ->with('investment','investment.user.amcCustProfiles','investment.user.cust_cnic_detail','investment.user.change_request','investment.fund','conversion', 'conversion.user.cust_cnic_detail','conversion.user.change_request','conversion.user.amcCustProfiles','conversion.fund')
        ->leftJoin('investments','investments.id','=','redemptions.invest_id')
        ->leftJoin('users','users.id','=','investments.user_id')
        ->leftJoin('funds','funds.id','=','investments.fund_id')->orderBy('redemptions.id','desc')->where('redemptions.id',$redemption)->first();
        $filtered_redemptions[]=$redemption;
      }
      $timestamp=Carbon::now()->timestamp;
      $file_name=$timestamp.".csv";
      if(!file_exists('storage/uploads/investment'))
      {
        mkdir('storage/uploads/investment',0777,true);
        chmod('storage/uploads/investment', 0777);
      }
      $redemption_csv = fopen('storage/uploads/investment/'.$file_name,"a");
      chmod('storage/uploads/investment/'.$file_name, 0777);
      $heading=array("Transaction Id","Customer Name","CNIC","Fund","Redemption Date","Amount","Redeem Amount","Transaction Status","Approval Date","Verification Status");
      fputcsv($redemption_csv, $heading); 
      foreach($filtered_redemptions as $redemption)
      {
        if($redemption->type=="investment")
        {
          $redemption_data=array(
            $redemption->transaction_id??"",
            $redemption->investment->user->full_name??"",
            $redemption->investment->user->cust_cnic_detail->cnic_number??"",
            $redemption->investment->fund->fund_name??"",
            $redemption->created_at??'',
            $redemption->amount??"",
            $redemption->redeem_amount??"",
            $statuses[$redemption->status]??"",
            $redemption->approved_date?date('Y-m-d',strtotime($redemption->approved_date)):'',
            $verification_status[$redemption->verified]??""
          );
        }
        else if($redemption->type=="dividend")
        {
          $redemption_data=array(
            $redemption->transaction_id??"",
            $redemption->dividend->user->full_name??"",
            $redemption->dividend->user->cust_cnic_detail->cnic_number??"",
            $redemption->dividend->fund->fund_name??"",
            $redemption->created_at??'',
            $redemption->amount??"",
            $redemption->redeem_amount??"",
            $statuses[$redemption->status]??"",
            $redemption->approved_date?date('Y-m-d',strtotime($redemption->approved_date)):'',
            $verification_status[$redemption->verified]??""
          );
        }
        else
        {
          $redemption_data=array(
            $redemption->transaction_id??"",
            $redemption->conversion->user->full_name??"",
            $redemption->conversion->user->cust_cnic_detail->cnic_number??"",
            $redemption->conversion->fund->fund_name??"",
            $redemption->created_at??'',
            $redemption->amount??"",
            $redemption->redeem_amount??"",
            $statuses[$redemption->status]??"",
            $redemption->approved_date?date('Y-m-d',strtotime($redemption->approved_date)):'',
            $verification_status[$redemption->verified]??""
          );
        }
          $redemption_csv = fopen('storage/uploads/investment/'.$file_name,"a");
          fputcsv($redemption_csv, $redemption_data);
      }
      $redemptions_csv[$file_name]=file_get_contents('storage/uploads/investment/'.$file_name);
      File::delete('storage/uploads/investment/'.$file_name);
      return response()->json(['success'=> true, 'message' => 'Data Exported successfully!',"redemptions_csv"=>$redemptions_csv]);
    }
    public function show(Request $request)
    {
      //  $redemption = Redemption::with('investment','investment.user','investment.fund')->orderBy('id','desc');
       $redemption = Redemption::query()
       ->select(\DB::raw('redemptions.*','investments.transaction_id','users.full_name','funds.fund_name'))
       ->with('investment','investment.user.amcCustProfiles','investment.user.cust_cnic_detail','investment.user.change_request','investment.fund','conversion', 'conversion.user.cust_cnic_detail','conversion.user.change_request','conversion.user.amcCustProfiles','conversion.fund','dividend', 'dividend.user.cust_cnic_detail','dividend.user.change_request','dividend.user.amcCustProfiles','dividend.fund')
       ->leftJoin('investments','investments.id','=','redemptions.invest_id')
       ->leftJoin('users','users.id','=','investments.user_id')
       ->leftJoin('funds','funds.id','=','investments.fund_id');
      //  ->with('investment','investment.user','investment.fund')->orderBy('id','desc');
       return DataTables::of($this->filter($request,$redemption))->make(true);
    }


    public function store(Request $request)
    {

      $validator = Validator::make($request->all(), [
        'invest_id' => 'required',
        'amount' => 'required',
        'approved_date' => 'required'
      ]);

      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }

      $fundId = Investment::where('id',$request->invest_id)->pluck('fund_id')->first();
      $fundNav = Fund::where('id',$fundId)->pluck('nav')->first();
      $investUnit = Investment::where('id',$request->invest_id)->pluck('unit')->first();
      $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $string = substr(str_shuffle($chars), 0, 8);
      $redemption = new Redemption();
      $redemption->invest_id = $request->invest_id;
      $redemption->transaction_id=$string;
      $redemption->redeem_amount = $request->amount;
      $redemption->redeem_units = $request->redeem_units;
      $redemption->approved_date = $request->approved_date;
      $redemption->amc_reference_number = $request->amc_reference_number;
      $redemption->amount = $fundNav * $investUnit;
      $redemption->redeem_by="Amount";
      $redemption->status = 1;
      $redemption->save();

      return response()->json(['success'=> true, 'message' => 'Redemption created successfully!']);

    }


    public function update(Request $request, Redemption $redemption)
    {
      $validator = Validator::make($request->all(), [
        'status' => 'required',
        'amount' => 'required_if:status,1',
        //'created_at'      => 'required',
        'approved_date'   => 'required_if:status,1',
        'rejected_reason'     => 'required_if:status,2',
      ],[
        'rejected_reason.required_if'   => 'This field is required',
        'amount.required_if' => 'This field is required',
        'approved_date.required_if' => 'This field is required',
        //'approved_date.gte' => 'Approval Date Cannot be Less than Transaction Date',
      ]);
      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }
      if($redemption->transaction_id==null)
      {
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $redemption->transaction_id=$string;
      }
      $redemption->status                      = $request->status ?? 0;
      $redemption->redeem_amount               = $request->amount;
      $redemption->rejected_reason             = $request->rejected_reason;
      $redemption->redeem_units                = $request->redeem_units;
      $redemption->approved_date               = $request->approved_date;
      $redemption->amc_reference_number        = $request->amc_reference_number;
      $redemption->save();

    //   try{
      if ($redemption->type == "investment") {
        $fund = $redemption->investment->fund;
        $user = $redemption->investment->user??'';
      } else {
        $fund = $redemption->conversion->fund;
        $user = $redemption->conversion->user??'';
      }
      if($redemption->status == 1) {
        // Mail::send('mail.userRedemptionStatusApproved', ['name'=> $user->full_name], function($message) use ($user) {
        //   $message->to($user->email, $user->full_name)->subject('Redemption Approved');
        //   $message->from('hello@ypayfinancial.com', 'YPay');
        // });

         // email notificaion 
         $url = 'https://networks.ypayfinancial.com/api/mailv1/approve_redemption.php';
         $body = ['email' => $user->email??'', 'name'=>$user->full_name??''];
         sendEmail($body,$url);

        $data = ['message' => Config::get('messages.redemption_request_approved'), 'image' => $fund['fund_image']??''];
        sendNotification($user->fcm_token, $data, $user->id??"", 'Congratulations! 🎉Your redemption request has been approved ✅');
      }
      else if($redemption->status == 2){
        // Mail::send('mail.userRedemptionStatusRejected', ['name'=> $user->full_name], function($message) use ($user) {
        //   $message->to($user->email, $user->full_name)->subject('Redemption Rejected');
        //   $message->from('hello@ypayfinancial.com', 'YPay');
        // });

         // email notificaion 
         $url = 'https://networks.ypayfinancial.com/api/mailv1/reject_redemption.php';
         $body = ['email' => $user->email, 'name'=>$user->full_name];
         sendEmail($body,$url);

        $data = ['message' => Config::get('messages.redemption_request_denied'), 'image' => $fund['fund_image']];
        sendNotification($user->fcm_token, $data, $user->id, 'Uh-Oh, we have hit a little hiccup 🙃');
      }

    return response()->json(['success'=> true, 'message' => 'Redemption updated successfully!']);
  }

    public function getRedemptionPdf($id)
    {
      $data = Redemption::with('investment','investment.user','investment.fund')->where('id',$id)->first();
      $customPaper = array(0,0,720,1440);
      $pdf = PDF::loadView('investment.redemption-request-form', compact('data'))->setPaper($customPaper,'portrait');

      return $pdf->download('redemption-request-Ypay.pdf');
    }

    public function filter($request, $redemption)
    {
      try {
        if (isset($request->transactionId)) {
          $transactionId = $request->transactionId;
          $redemption=$redemption->where('redemptions.transaction_id', 'like', '%' . $transactionId . '%' );
          // $redemption = $redemption->whereHas('investment', function($q) use($transactionId) {
          //   $q->where('transaction_id', 'like', '%' . $transactionId . '%' );
          // });
        }
        if(isset($request->amc) && $request->amc != "null" && $request->folio_number == "null"){
          $amc = $request->amc;
         
          $redemption = $redemption->whereHas('investment.fund.amc', function ($q) use ($amc) {
            $q->where('id',$amc);
          })->orWhereHas('conversion.fund.amc', function($q) use($amc) {
            $q->where('id',$amc);
          })->orWhereHas('dividend.fund.amc', function($q) use($amc) {
            $q->where('id',$amc);
          });

          
        }
        if (isset($request->customerId) && $request->customerId!="null") {
  
          $user = $request->customerId;
          $redemption = $redemption->whereHas('investment.user', function($q) use($user) {
            $q->where('id',$user);
          })->orWhereHas('conversion.user', function($q) use($user) {
            $q->where('id', $user);
          })->orWhereHas('dividend.user', function($q) use($user) {
            $q->where('id', $user);
          });
      }
      if (isset($request->refer_code) && $request->refer_code!="null") {
  
        $refer_code = $request->refer_code;
        $redemption = $redemption->whereHas('investment.user', function($q) use($refer_code) {
          $q->where('refer_code','like', '%' .$refer_code. '%');
        })->orWhereHas('conversion.user', function($q) use($refer_code) {
          $q->where('refer_code', 'like', '%' .$refer_code. '%');
        })->orWhereHas('dividend.user', function($q) use($refer_code) {
          $q->where('refer_code', 'like', '%' .$refer_code. '%');
        });
    }
      if (isset($request->cnic)) {
  
        $cnic = $request->cnic;
        $redemption = $redemption->whereHas('investment.user.cust_cnic_detail', function($q) use($cnic) {
          $q->where('cnic_number', 'like', '%' .$cnic. '%');
        })->orWhereHas('conversion.user.cust_cnic_detail', function($q) use($cnic) {
          $q->where('cnic_number', 'like', '%' .$cnic. '%');
        })->orWhereHas('dividend.user.cust_cnic_detail', function($q) use($cnic) {
          $q->where('cnic_number', 'like', '%' .$cnic. '%');
        });
      }
      if (isset($request->folio_number) && $request->folio_number != "null" && $request->amc == "null") {
  
        $folio_number = $request->folio_number;
          $amc_profile=AmcCustProfile::where('account_number',$folio_number)->first();
        // $redemption = $redemption->whereHas('investment.user.amcCustProfiles', function($q) use($folio_number) {
        //   $q->where('account_number', 'like', '%' .$folio_number. '%');
        // })->orWhereHas('conversion.user.amcCustProfiles', function($q) use($folio_number) {
        //   $q->where('account_number', 'like', '%' .$folio_number. '%');
        // });
          $redemption = $redemption->whereHas('investment.user', function ($q)use($amc_profile){
            $q->where('id',$amc_profile?->user_id);
          })->whereHas('investment', function ($q)use($amc_profile){
            $q->whereHas('fund', function ($qry)use($amc_profile){
              $qry->where('amc_id',$amc_profile?->amc_id);
            });
          })->orWhereHas('conversion.user', function ($q)use($amc_profile){
            $q->where('id',$amc_profile?->user_id);
          })->whereHas('conversion', function ($q)use($amc_profile){
            $q->whereHas('fund', function ($qry)use($amc_profile){
              $qry->where('amc_id',$amc_profile?->amc_id);
            });
          })->orWhereHas('dividend.user', function ($q)use($amc_profile){
            $q->where('id',$amc_profile?->user_id);
          })->whereHas('dividend', function ($q)use($amc_profile){
            $q->whereHas('fund', function ($qry)use($amc_profile){
              $qry->where('amc_id',$amc_profile?->amc_id);
            });
          });
      }
      else if (isset($request->folio_number) && $request->folio_number != "null" && isset($request->amc) && $request->amc != "null") {
  
        $folio_number = $request->folio_number;
        if(isset($request->amc) && $request->amc != "null"){
          $amc_profile=AmcCustProfile::where('account_number',$folio_number)->where('amc_id',$request->amc)->first();
                // $redemption = $redemption->whereHas('investment.user.amcCustProfiles', function($q) use($folio_number) {
        //   $q->where('account_number', 'like', '%' .$folio_number. '%');
        // })->orWhereHas('conversion.user.amcCustProfiles', function($q) use($folio_number) {
        //   $q->where('account_number', 'like', '%' .$folio_number. '%');
        // });
        $redemption = $redemption->whereHas('investment.user', function ($q)use($amc_profile){
          $q->where('id',$amc_profile?->user_id);
        })->whereHas('investment', function ($q)use($amc_profile){
          $q->whereHas('fund', function ($qry)use($amc_profile){
            $qry->where('amc_id',$amc_profile?->amc_id);
          });
        })->orWhereHas('conversion.user', function ($q)use($amc_profile){
          $q->where('id',$amc_profile?->user_id);
        })->whereHas('conversion', function ($q)use($amc_profile){
          $q->whereHas('fund', function ($qry)use($amc_profile){
            $qry->where('amc_id',$amc_profile?->amc_id);
          });
        })->orWhereHas('dividend.user', function ($q)use($amc_profile){
          $q->where('id',$amc_profile?->user_id);
        })->whereHas('dividend', function ($q)use($amc_profile){
          $q->whereHas('fund', function ($qry)use($amc_profile){
            $qry->where('amc_id',$amc_profile?->amc_id);
          });
        });
        }
      }
    if (isset($request->approvedDateFrom) && isset($request->approvedDateTo)) {
  
      $approvedDateFrom = $request->approvedDateFrom;
      $approvedDateTo = $request->approvedDateTo;
      $redemption = $redemption->whereBetween('redemptions.approved_date',[date("Y-m-d H:i:s", strtotime($approvedDateFrom)),date("Y-m-d H:i:s", strtotime($approvedDateTo))]);
    }
  
  
        // if (isset($request->from)) {
        
        //   $redemption = $redemption->whereDate('redemptions.updated_at', '>=', Carbon::parse($request->from));
        // }
  
  
        // if (isset($request->to)) {
          
        //   $redemption = $redemption->whereDate('redemptions.updated_at', '<=', Carbon::parse($request->to));
        // }
        if (isset($request->from) && isset($request->to)) {       
          $transaction_from=$request->from;
          $transaction_to=$request->to;
          $redemption = $redemption->whereBetween('redemptions.created_at',[date("Y-m-d H:i:s", strtotime($transaction_from)),date("Y-m-d H:i:s", strtotime($transaction_to))]);
          
        }
  
        if (isset($request->fund) && $request->fund!="null") {
          $fund = $request->fund;
          $redemption = $redemption->whereHas('investment.fund', function($q) use($fund) {
            $q->where('id',$fund);
          })->orWhereHas('conversion.fund', function($q) use($fund) {
            $q->where('id',$fund);
          })->orWhereHas('dividend.fund', function($q) use($fund) {
            $q->where('id',$fund);
          });
        }

        if (isset($request->min_amount)) {

          $min_amount = $request->min_amount;
          $redemption = $redemption->where('redemptions.amount','>=',$min_amount);
         
        }

        if (isset($request->max_amount)) {

          $max_amount = $request->max_amount;
          $redemption = $redemption->where('redemptions.amount','<=',$max_amount);
         
        }

        if (isset($request->redeemAmount)) {

          $redeemAmount = $request->redeemAmount;
          $redemption = $redemption->where('redemptions.redeem_amount',$redeemAmount);
         
        }
        if (isset($request->verified) && $request->verified!="null") {          
          $redemption = $redemption->where('redemptions.verified',$request->verified);
        }

        if (isset($request->status)) {
          $redemption = $redemption->where('redemptions.status',$request->status);
        }
  
        return $redemption;
      } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
      }
    }
}
