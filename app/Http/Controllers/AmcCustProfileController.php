<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AmcCustProfile;
use App\Models\AmcCity;
use App\Models\AmcCountry;
use App\Models\AmcOccupation;
use App\Models\AmcDataLog;
use App\Models\AmcSourceofIncome;
use App\Models\AmcBank;
use App\Models\AccountType;
use App\Models\Amc;
use App\Models\Configurations;
use App\Models\Fund;
use App\Models\Investment;
use App\Models\AmcAPI;
use App\Models\CronJobLog;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use File;
use Carbon\Carbon;
use function App\Libraries\Helpers\sendNotification;
use Illuminate\Support\Facades\Storage;
use function App\Libraries\Helpers\sendEmail;
use Illuminate\Validation\Rule;
use Config;

class AmcCustProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account_types=AccountType::all();
        return view('customer.amc-cust-profile',compact('account_types'));
    }

    public function getData(Request $request)
    {
        $amcCustProfiles = AmcCustProfile::with('amc','user.cust_cnic_detail');
        // dd($amcCustProfiles);
       return DataTables::of($this->filter($request,$amcCustProfiles))->order(function ($q) use ($request) {
      if (count($request->order)) {
        foreach ($request->order as $order) {
          $column = @$request->columns[@$order['column']]['data'];
          $dir = @$order['dir'];
          if ($column && $dir) {
            $q->orderBy($column, $dir);
          }
        }
      }
    })->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifycustAmcProfile(Request $request)
    {
      $amc_profile=AmcCustProfile::where('id',$request['profile_id'])->first();
      if(isset($request['profile_id']) && isset($request['verified']) && $amc_profile->amc_id =="2")
      {
        AmcCustProfile::where('id',$request['profile_id'])->update(['verified'=>$request['verified'],'verified_at'=>Carbon::now()]);
        $url = 'https://networks.ypayfinancial.com/api/mailv1/kyc_request_to_amc.php';
        $body = ['email' => $amc_profile->user->email, 'cc' => $amc_profile->amc->compliant_email, 'cnic_number'=>$amc_profile->user->cust_cnic_detail->cnic_number,'amc_name'=>$amc_profile->amc->entity_name, 'amc_contact_no' => $amc_profile->amc->contact_no];
        sendEmail($body,$url);
        return response(["status"=>200,'message' => 'Customer Amc Profile Verification Status Changed Successfully!']);
      }
      else if(isset($request['profile_id']) && isset($request['verified']) && $amc_profile->amc_id !="2")
      {
        AmcCustProfile::where('id',$request['profile_id'])->update(['verified'=>$request['verified'],'verified_at'=>Carbon::now()]);
        return response(["status"=>200,'message' => 'Customer Amc Profile Verification Status Changed Successfully!']);
      }
      else
      return response(["error"=>"Error Occured"]);
    }
    public function accounts_opening_confirmation()
    {
      $amcCustProfiles=AmcCustProfile::with('user')->where('status','0')->whereNotNull('amc_reference_number')->where('amc_id','2')->get();
      try{
      $cust_cnics=[];
      $flag=true;
      $current_date = date('Y_m_d');
      var_dump($current_date);
      foreach($amcCustProfiles as $cust_profile)
      {
          try{
          $transaction_id=$cust_profile->amc_reference_number;
          $transaction_id= str_replace(' ','%20',$transaction_id);
          $customer_cnic=$cust_profile->user->cust_cnic_detail->cnic_number;
          // $customer_cnic=str_replace('-','',$customer_cnic);
          $amc_api_data=AmcAPI::where('amc_id',2)->where('name','Account Opening Confirmation API')->first();
          $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name)); 
          // $kyc_file_name=$amc_api_data_name."_".$customer_cnic.".csv";
          $kyc_file_name = $current_date.".csv";
          $curl = curl_init();
          $url=$amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&CNIC='.$customer_cnic.'&Form_Number='.$transaction_id;
          $amc_dataset_log= new AmcDataLog;
          $amc_dataset_log->dataset_url= $url;
          $amc_dataset_log->api_name=$amc_api_data->name;
          $amc_dataset_log->amc_id=$amc_api_data->amc_id;
          $amc_dataset_log->user_id=$cust_profile->user_id;
          $amc_dataset_log->save();
          curl_setopt($curl, CURLOPT_URL, $amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&CNIC='.$customer_cnic.'&Form_Number='.$transaction_id);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $xml_response = curl_exec($curl);
          var_dump($xml_response);
          var_dump($current_date);
          $amc_name = $cust_profile->amc->entity_name; 
          if(!file_exists('storage/uploads/technical_log_2'))
          {
            mkdir('storage/uploads/technical_log_2',0777,true);
            chmod('storage/uploads/technical_log_2', 0777);
          }
          if($xml_response==false)
          {
            if(!file_exists('storage/uploads/technical_log_2/'.$kyc_file_name))
            {
              
              $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
              $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
              fputcsv($kyc_csv, $heading);
            }
            $url  = 'https://networks.ypayfinancial.com/api/mailv1/error_report_email.php';
            $ypay_email=Configurations::where('name','YPay Email')->pluck('value')->first();
            $error_message="VPN Not Connected";
            $body = ["email" => $ypay_email,"amc"=>$cust_profile->amc->entity_name,"message"=>$error_message];
            sendEmail($body,$url);
            $technical_error_detail=array(
              $customer_cnic,
              $amc_name,
              $amc_api_data->name,
              $error_message,
              Carbon::now()
              );
              $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
              fputcsv($kyc_csv, $technical_error_detail);
              rewind($kyc_csv);
              $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$kyc_file_name);
              Storage::disk('technical-log-ftp')->put($kyc_file_name, $technical_error_csv);
              // File::delete('storage/uploads/technical_log_2/'.$kyc_file_name);
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
            $folio_number = $dom->getElementsByTagName('Folio_x0020_Number');
            $folio_number = $folio_number->item(0)->nodeValue;
            $cust_profile->account_number=$folio_number;
            $cust_profile->status=1;
            $cust_profile->save();
          }
          else if($status=="1")
          {
          }
          else if($status=="2")
          {
            $cust_profile->status=3;
            $cust_profile->response_error_message=$status."- No Data Found";
            $cust_profile->save();
            if(!file_exists('storage/uploads/technical_log_2'))
            {
              mkdir('storage/uploads/technical_log_2',0777,true);
              chmod('storage/uploads/technical_log_2', 0777);
            }
            if(!file_exists('storage/uploads/technical_log_2/'.$kyc_file_name))
            {
              
              $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
              $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
              fputcsv($kyc_csv, $heading);
            }
            $technical_error_detail=array(
              $customer_cnic,
              $amc_name,
              $amc_api_data->name,
              $status."- No Data Found",
              Carbon::now()
              );
              $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
              fputcsv($kyc_csv, $technical_error_detail);
              rewind($kyc_csv);
              $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$kyc_file_name);
              Storage::disk('technical-log-ftp')->put($kyc_file_name, $technical_error_csv);
              // File::delete('storage/uploads/technical_log_2/'.$kyc_file_name);
          }
          else
          {
            $cust_profile->status=3;
            $cust_profile->response_error_message=$status;
            $cust_profile->save();
            if(!file_exists('storage/uploads/technical_log_2'))
            {
              mkdir('storage/uploads/technical_log_2',0777,true);
              chmod('storage/uploads/technical_log_2', 0777);
            }
            if(!file_exists('storage/uploads/technical_log_2/'.$kyc_file_name))
            {
              
              $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
              $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
              fputcsv($kyc_csv, $heading);
            }
            $technical_error_detail=array(
              $customer_cnic,
              $amc_name,
              $amc_api_data->name,
              $status,
              Carbon::now()
              );
              $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
              chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
              fputcsv($kyc_csv, $technical_error_detail);
              rewind($kyc_csv);
              $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$kyc_file_name);
              Storage::disk('technical-log-ftp')->put($kyc_file_name, $technical_error_csv);
              // File::delete('storage/uploads/technical_log_2/'.$kyc_file_name);
          }
        }
        catch (\Exception $e) {
          continue;
         }
      }
        $cron_job_log=new CronJobLog;
        $cron_job_log->cron_job_name='Account Opening Confirmation';
        $cron_job_log->amc_id=2;
        $cron_job_log->status=1;
        $cron_job_log->save();
        if(count($amcCustProfiles)>0 && count($cust_cnics)>0)
        {
        $url  = 'https://networks.ypayfinancial.com/api/mailv1/kyc_process_email.php';
        $ypay_email=Configurations::where('name','YPay Email')->pluck('value')->first();
        $body = ["email" => $ypay_email,"ids"=>$cust_cnics,"message"=>"The following customer(s) have submitted Profile request(s).
        
        Your swift action is highly appreciated."];
        sendEmail($body,$url);
        }
      }
      catch (\Exception $e) {
        $cron_job_log=new CronJobLog;
        $cron_job_log->cron_job_name='Account Opening Confirmation';
        $cron_job_log->amc_id=2;
        $cron_job_log->status=0;
        $cron_job_log->failure_reason=$e->getMessage();
        $cron_job_log->save();
       }
      //return response(["status"=>200,'message' => 'All In-Process AMC Profiles Statuses Updated Successfully!']);
    }
    public function akd_kyc()
    {
      $amcCustProfiles=AmcCustProfile::with('user')->where('verified','1')->where('status','-1')->where('amc_id','8')->get();
      foreach($amcCustProfiles as $cust_profile)
      {
          $cnic_front=str_starts_with($cust_profile->user->cust_cnic_detail->cnic_front, 'http')?$cust_profile->user->cust_cnic_detail->cnic_front:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->cnic_front;
          $cnic_back=str_starts_with($cust_profile->user->cust_cnic_detail->cnic_back, 'http')?$cust_profile->user->cust_cnic_detail->cnic_back:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->cnic_back;
          $income_proof=str_starts_with($cust_profile->user->cust_cnic_detail->income, 'http')?$cust_profile->user->cust_cnic_detail->income:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->income;
          if(isset($cust_profile->user->cust_cnic_detail->cnic_front))
          {
          $cnic_front_file=file_get_contents($cnic_front);
          $cnic_front_ext=explode(".",$cnic_front);
          $cnic_front_ext=$cnic_front_ext[count($cnic_front_ext)-1];
          }
          if(isset($cust_profile->user->cust_cnic_detail->cnic_back))
          {
            $cnic_back_file=file_get_contents($cnic_back);
            $cnic_back_ext=explode(".",$cnic_back);
            $cnic_back_ext=$cnic_back_ext[count($cnic_back_ext)-1];
          }
          if(isset($cust_profile->user->cust_cnic_detail->income))
          {
          $income_proof_ext=explode(".",$income_proof);
          $income_proof_ext=$income_proof_ext[count($income_proof_ext)-1];
          $income_proof_file=file_get_contents($income_proof);
          }
          $customer_name=strtoupper($cust_profile->user->full_name);
          $customer_cnic=$cust_profile->user->cust_cnic_detail->cnic_number;
          if(isset($cust_profile->user->cust_cnic_detail->cnic_front))
          Storage::disk('akd-images-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_front.'.$cnic_front_ext, $cnic_front_file);
          if(isset($cust_profile->user->cust_cnic_detail->cnic_back))
          Storage::disk('akd-images-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_back.'.$cnic_back_ext, $cnic_back_file);
          if(isset($cust_profile->user->cust_cnic_detail->income))
          Storage::disk('akd-images-ftp')->put($customer_cnic.' '.$customer_name.'/profile/income_proof.'.$income_proof_ext, $income_proof_file);
      }
    }
    public function kyc_process()
    {
            $amcCustProfiles=AmcCustProfile::with('user')->where('verified','1')->where('status','-1')->where('amc_id','2')->get();
            try{
            $cust_cnics=[];
            $flag=true;
            $current_date = date('Y_m_d');
            foreach($amcCustProfiles as $cust_profile)
            {
              try{
              $customer_name=strtoupper($cust_profile->user->full_name);
              $customer_cnic=$cust_profile->user->cust_cnic_detail->cnic_number;
              $cust_cnics[]=$customer_cnic;
              // $customer_cnic=str_replace('-','',$customer_cnic);
              $cnic_expiry_date=$cust_profile->user->cust_cnic_detail->expiry_date;
              $cnic_front=str_starts_with($cust_profile->user->cust_cnic_detail->cnic_front, 'http')?$cust_profile->user->cust_cnic_detail->cnic_front:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->cnic_front;
              $cnic_back=str_starts_with($cust_profile->user->cust_cnic_detail->cnic_back, 'http')?$cust_profile->user->cust_cnic_detail->cnic_back:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->cnic_back;
              $income_proof=str_starts_with($cust_profile->user->cust_cnic_detail->income, 'http')?$cust_profile->user->cust_cnic_detail->income:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->income;
              if(isset($cust_profile->user->cust_cnic_detail->cnic_front))
              {
              $cnic_front_file=file_get_contents($cnic_front);
              $cnic_front_ext=explode(".",$cnic_front);
              $cnic_front_ext=$cnic_front_ext[count($cnic_front_ext)-1];
              }
              if(isset($cust_profile->user->cust_cnic_detail->cnic_back))
              {
                $cnic_back_file=file_get_contents($cnic_back);
                $cnic_back_ext=explode(".",$cnic_back);
                $cnic_back_ext=$cnic_back_ext[count($cnic_back_ext)-1];
              }
              if(isset($cust_profile->user->cust_cnic_detail->income))
              {
              $income_proof_ext=explode(".",$income_proof);
              $income_proof_ext=$income_proof_ext[count($income_proof_ext)-1];
              $income_proof_file=file_get_contents($income_proof);
              }
              $cnic_issue_date=$cust_profile->user->cust_cnic_detail->issue_date;
              $father_name=strtoupper($cust_profile->user->cust_basic_detail->father_name);
              $gender=$cust_profile->user->cust_basic_detail->gender;
              // $occupation=$cust_profile->user->cust_basic_detail->amc_occupation_id;
              // $income_source=$cust_profile->user->cust_basic_detail->amc_income_source_id;
              $email=$cust_profile->user->email;
              $mobile=str_replace('+92','',$cust_profile->user->phone_no);
              $address=$cust_profile->user->cust_basic_detail->current_address;
              $dob=$cust_profile->user->cust_basic_detail->dob;
              $city_id=$cust_profile->user->cust_basic_detail->city;
              $country_id=$cust_profile->user->cust_basic_detail->country;
              $bank_id=$cust_profile->user->cust_basic_detail->bank;
              $occupation_id=$cust_profile->user->cust_basic_detail->occupation;
              $income_source_id=$cust_profile->user->cust_basic_detail->income_source;
              
              $income_source=AmcSourceofIncome::where('ypay_source_of_income_id',$income_source_id)->pluck('amc_source_of_income_id')->first();
              $country=AmcCountry::where('ypay_country_id',$country_id)->pluck('amc_country_id')->first();
              $city=AmcCity::where('ypay_city_id',$city_id)->pluck('amc_city_code')->first();
              $occupation=AmcOccupation::where('ypay_occupation_id',$occupation_id)->pluck('amc_occupation_id')->first();
              $bank=AmcBank::where('ypay_bank_id',$bank_id)->pluck('amc_bank_id')->first();
              // $city=$cust_profile->user->cust_basic_detail->amc_city_id;
              // $country=$cust_profile->user->cust_basic_detail->amc_country_id;
              $nationality=$cust_profile->user->cust_basic_detail->nationality;
              // $bank=$cust_profile->user->cust_basic_detail->amc_bank_id;
              $bank_account_no=$cust_profile->user->cust_bank_detail->bank_account_number;
              $iban=strtoupper($cust_profile->user->cust_bank_detail->iban);
              $zakat=$cust_profile->user->cust_basic_detail->zakat=="0"?"Inactive":"Active";
              $amc_api_data=AmcAPI::where('amc_id',2)->where('name','Account Inquiry API')->first();
              $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name)); 
              $kyc_file_name=$current_date.".csv";
              $amc_name = $cust_profile->amc->entity_name;
              // $kyc_file_path="storage/uploads/kyc_process/".$amc_api_data_name."_".$customer_cnic."/";
              // $kyc_folder_path="storage/uploads/kyc_process/".$amc_api_data_name."_".$customer_cnic."/";
              // if(!file_exists($kyc_folder_path))
              // {
              //   mkdir($kyc_folder_path,0777,true);
              // if($flag)
              // {
              //   $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
              //   $heading=array("CNIC","API Name","Error Code with Description","Date");
              //   fputcsv($kyc_csv, $heading); 
              //   $flag=false;
              // }
              // }
              $curl = curl_init();
              $url=$amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&CNIC='.$customer_cnic;
              $amc_dataset_log= new AmcDataLog;
              $amc_dataset_log->dataset_url= $url;
              $amc_dataset_log->api_name=$amc_api_data->name;
              $amc_dataset_log->amc_id=$amc_api_data->amc_id;
              $amc_dataset_log->user_id=$cust_profile->user_id;
              $amc_dataset_log->save();
              curl_setopt($curl, CURLOPT_URL, $url);
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
              $xml_response = curl_exec($curl);
              echo $xml_response;
              $url  = 'https://networks.ypayfinancial.com/api/mailv1/error_report_email.php';
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
                if(!file_exists('storage/uploads/technical_log_2/'.$kyc_file_name))
                {
                  $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                  chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                  $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
                  fputcsv($kyc_csv, $heading);
                }
                $error_message="VPN Not Connected";
                $ypay_email=Configurations::where('name','YPay Email')->pluck('value')->first();
                $body = ["email" => $ypay_email,"amc"=>$cust_profile->amc->entity_name,"message"=>$error_message];
                sendEmail($body,$url);
                $technical_error_detail=array(
                  $customer_cnic,
                  $amc_name,
                  $amc_api_data->name,
                  $error_message,
                  Carbon::now()
                  );
                  $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                  chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                  fputcsv($kyc_csv, $technical_error_detail);
                  rewind($kyc_csv);
                  $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$kyc_file_name);
                  Storage::disk('technical-log-ftp')->put($kyc_file_name, $technical_error_csv);
                  // File::delete('storage/uploads/technical_log_2/'.$kyc_file_name);
                  // die();
                  continue;
              }
              $dom = new \DOMDocument;
              $dom->loadXML($xml_response);
              $dom->formatOutput = TRUE;
              $dom->savexml();
              $status = $dom->getElementsByTagName('Response');
              $status = $status->item(0)->nodeValue;
              if($status=='0')
              {
                $cust_profile->verified=2;
                $cust_profile->verified_at=Carbon::now();
                $cust_profile->status=2;
                $cust_profile->rejected_reason='Account with AMC Already Exist';
                $cust_profile->save();
                $user=$cust_profile->user;
                $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_request_denied_by_amc.php';
                $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$cust_profile->amc->entity_name];
                sendEmail($body,$url);
                $data = ['message' => sprintf(Config::get('messages.profile_request_denied_amc'), $cust_profile->amc->entity_name), 'image' => ''];
                sendNotification($user->fcm_token, $data, $user->id, 'profile_rejected');
              }
              else if($status=='1')
              {
                $cust_profile->status=2;
                $cust_profile->verified=2;
                $cust_profile->verified_at=Carbon::now();
                $cust_profile->rejected_reason='You have already processed your account at Alfalah GHP outside the YPay app';
                $cust_profile->save();
                $user=$cust_profile->user;
                $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_request_denied_by_amc.php';
                $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$cust_profile->amc->entity_name];
                sendEmail($body,$url);
                $data = ['message' => sprintf(Config::get('messages.profile_request_denied_amc'), $cust_profile->amc->entity_name), 'image' => ''];
                sendNotification($user->fcm_token, $data, $user->id, 'profile_rejected');
              }
              else if($status=='2')
              {
              $customer_cnic=rawurlencode($customer_cnic);
              $endoded_customer_name=rawurlencode($customer_name);
              $father_name=rawurlencode($father_name);
              $mobile=str_replace(' ','',$mobile);
              $mobile='0'.$mobile;
              $mobile=rawurlencode($mobile);
              $address=rawurlencode($address);
              $amc_api_data=AmcAPI::where('amc_id',2)->where('name','Create New Account API')->first();
              $amc_api_data_name=strtolower(str_replace(' ','_',$amc_api_data->name)); 
              $url=$amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&CNIC='.$customer_cnic.'&Name='.$endoded_customer_name.
              '&CNIC_ExpiryDate='.$cnic_expiry_date.'&Father_Name='.$father_name.'&Gender='.$gender.'&DOB='.$dob.'&Occupation='.$occupation.
              '&SourceofIncome='.$income_source.'&Email='.$email.'&Mobile='.$mobile.'&Address='.$address.'&City='.$city.'&Country='.$country.
              '&Nationality='.$nationality.'&Bank_Name='.$bank.'&BankAccountNumber='.$bank_account_no.'&IBAN_Number='.$iban.
              '&Zakat_Status='.$zakat.'&Issuance_Date='.$cnic_issue_date.'&DistributorCode=YPFSL&DistributorBranchCode=YPFSL&FacilitatorCode=YPFSL';
              $curl = curl_init();
              // $url=str_replace(' ','%20',$url);
              // $url=str_replace(',','%2C',$url);
              // $url=str_replace('#','%23',$url);
              // $url=str_replace('/','%2F',$url);
              // $url=str_replace(',','%2C',$url);
              // $url=str_replace('#','%23',$url);
              // $url=str_replace('-','%2D',$url);
              // $url=str_replace("'",'%27',$url);
              // $url=str_replace('"','%22',$url);
              // $url=str_replace('?','%3F',$url);
              curl_setopt($curl, CURLOPT_URL,$url);
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
              $amc_dataset_log= new AmcDataLog;
              $amc_dataset_log->dataset_url= $url;
              $amc_dataset_log->api_name=$amc_api_data->name;
              $amc_dataset_log->amc_id=$amc_api_data->amc_id;
              $amc_dataset_log->user_id=$cust_profile->user_id;
              $amc_dataset_log->save();
              $xml_response = curl_exec($curl);
              echo $xml_response;
              if($xml_response==false)
              {
                if(!file_exists('storage/uploads/technical_log_2'))
                {
                  mkdir('storage/uploads/technical_log_2',0777,true);
                  chmod('storage/uploads/technical_log_2', 0777);
                }
                if(!file_exists('storage/uploads/technical_log_2/'.$kyc_file_name))
                {
                  
                  $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                  chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                  $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
                  fputcsv($kyc_csv, $heading);
                }
                $error_message="VPN Not Connected";
                $ypay_email=Configurations::where('name','YPay Email')->pluck('value')->first();
                $body = ["email" => $ypay_email,"amc"=>$cust_profile->amc->entity_name,"message"=>$error_message];
                sendEmail($body,$url);
                $technical_error_detail=array(
                  $customer_cnic,
                  $amc_name,
                  $amc_api_data->name,
                  $error_message,
                  Carbon::now()
                  );
                  $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                  chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                  fputcsv($kyc_csv, $technical_error_detail);
                  rewind($kyc_csv);
                  $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$kyc_file_name);
                  Storage::disk('technical-log-ftp')->put($kyc_file_name, $technical_error_csv);
                  // File::delete('storage/uploads/technical_log_2/'.$kyc_file_name);
                  // die();
                  continue;
              }
              $kyc_file_name=$current_date.".csv";
              $dom = new \DOMDocument;
              $dom->loadXML($xml_response);
              $dom->formatOutput = TRUE;
              $dom->savexml();
              $status = $dom->getElementsByTagName('Response');
              $status = $status->item(0)->nodeValue;
              
              if($status=="100")
              {
                $transaction_id = $dom->getElementsByTagName('TransactionID');
                $transaction_id= $transaction_id->item(0)->nodeValue;
                $cust_profile->amc_reference_number=$transaction_id;
                $cust_profile->status=0;
                $cust_profile->verified=2;
                $cust_profile->verified_at=Carbon::now();
                $cust_profile->save();
                if(isset($cust_profile->user->cust_cnic_detail->cnic_front))
                Storage::disk('custom-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_front.'.$cnic_front_ext, $cnic_front_file);
                if(isset($cust_profile->user->cust_cnic_detail->cnic_back))
                Storage::disk('custom-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_back.'.$cnic_back_ext, $cnic_back_file);
                if(isset($cust_profile->user->cust_cnic_detail->income))
                Storage::disk('custom-ftp')->put($customer_cnic.' '.$customer_name.'/profile/income_proof.'.$income_proof_ext, $income_proof_file);
              }
              else if($status=="404" || $status=="405")
              {
                $cust_profile->verified=2;
                $cust_profile->verified_at=Carbon::now();
                $cust_profile->status=2;
                $cust_profile->rejected_reason=$status." -CNIC Already Exist";
                $cust_profile->save();
                $user=$cust_profile->user;
                $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_request_denied_by_amc.php';
                $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$cust_profile->amc->entity_name];
                sendEmail($body,$url);
                $data = ['message' => sprintf(Config::get('messages.profile_request_denied_amc'), $cust_profile->amc->entity_name), 'image' => ''];
                sendNotification($user->fcm_token, $data, $user->id, 'profile_rejected');
              }
              else
              {
                if(!file_exists('storage/uploads/technical_log_2/'.$kyc_file_name))
                {
                  
                  $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                  chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                  $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
                  fputcsv($kyc_csv, $heading);
                }
                $cust_profile->status=3;
                $cust_profile->verified=2;
                $cust_profile->verified_at=Carbon::now();
                $error_message=Config::get('account_creation_response_errors.'.$status);
                $technical_error_detail=array(
                  $customer_cnic,
                  $amc_name,
                  $amc_api_data->name,
                  $error_message,
                  Carbon::now()
                  );
                $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                fputcsv($kyc_csv, $technical_error_detail);
                rewind($kyc_csv);
                $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$kyc_file_name);
                Storage::disk('technical-log-ftp')->put($kyc_file_name, $technical_error_csv);
                // File::delete('storage/uploads/technical_log_2/'.$kyc_file_name);
                $cust_profile->response_error_message=$error_message;
                $cust_profile->save();
              }
              }
              else
              {
                if(!file_exists('storage/uploads/technical_log_2'))
                {
                  mkdir('storage/uploads/technical_log_2',0777,true);
                  chmod('storage/uploads/technical_log_2', 0777);
                }
                if(!file_exists('storage/uploads/technical_log_2/'.$kyc_file_name))
                {
                  
                  $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                  chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                  $heading=array("CNIC","AMC","API Name","Error Code with Description","Date");
                  fputcsv($kyc_csv, $heading);
                }
                $cust_profile->status=2;
                $cust_profile->verified=2;
                $cust_profile->verified_at=Carbon::now();
                $cust_profile->response_error_message=$status;
                $technical_error_detail=array(
                  $customer_cnic,
                  $amc_name,
                  $amc_api_data->name,
                  $status,
                  Carbon::now()
                  );
                $kyc_csv = fopen('storage/uploads/technical_log_2/'.$kyc_file_name,"a");
                chmod('storage/uploads/technical_log_2/'.$kyc_file_name, 0777);
                fputcsv($kyc_csv, $technical_error_detail);
                rewind($kyc_csv);
                $technical_error_csv=file_get_contents('storage/uploads/technical_log_2/'.$kyc_file_name);
                Storage::disk('technical-log-ftp')->put($kyc_file_name, $technical_error_csv);
                // File::delete('storage/uploads/technical_log_2/'.$kyc_file_name);
                $cust_profile->rejected_reason='Other Exception-'.$amc_api_data->name;
                $cust_profile->save();
                $user=$cust_profile->user;
                $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_request_denied_by_amc.php';
                $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$cust_profile->amc->entity_name];
                sendEmail($body,$url);
                $data = ['message' => sprintf(Config::get('messages.profile_request_denied_amc'), $cust_profile->amc->entity_name), 'image' => ''];
                sendNotification($user->fcm_token, $data, $user->id, 'profile_rejected');
              }
            }
              catch (\Exception $e) {
                echo $e->getMessage();
                  continue;
              }
            }
            $cron_job_log=new CronJobLog;
            $cron_job_log->cron_job_name='KYC Process';
            $cron_job_log->amc_id=2;
            $cron_job_log->status=1;
            $cron_job_log->save();
            if(count($amcCustProfiles)>0 && count($cust_cnics)>0)
            {
                $amc_email=Amc::where('id',2)->pluck('compliant_email')->first();
                $url  = 'https://networks.ypayfinancial.com/api/mailv1/kyc_process_email.php';
                $body = ["email" => $amc_email,"ids"=>$cust_cnics,"message"=>"The following customer(s) have submitted Profile request(s).
                Your swift action is highly appreciated."];
                sendEmail($body,$url);
            }
          }
          catch (\Exception $e) {
            $cron_job_log=new CronJobLog;
            $cron_job_log->cron_job_name='KYC Process';
            $cron_job_log->amc_id=2;
            $cron_job_log->status=0;
            $cron_job_log->failure_reason=$e->getMessage();
            $cron_job_log->save();
           }
            //return response(["status"=>200,'message' => 'Customer Amc Profile Verification Process Completed Successfully!']);
    }
    public function jsil_kyc_process()
    {
      $amcCustProfiles=AmcCustProfile::with('user')->where('verified','1')->where('status','-1')->where('amc_id','1')->get();
      // try{
        $current_date = date('Y_m_d');
        $amc_cnic_check_api=AmcAPI::where('amc_id',1)->where('name','JSIL CNIC Check API')->first();
        $amc_account_opening_api=AmcAPI::where('amc_id',1)->where('name','JSIL Account Opening API')->first();
        foreach($amcCustProfiles as $cust_profile)
        {
          $customer_name=strtoupper($cust_profile->user->full_name);
          $customer_cnic=$cust_profile->user->cust_cnic_detail->cnic_number;
          $user_id = str_replace('-', '', $customer_cnic);
          $cust_cnics[]=$customer_cnic;
          $religion= "01";
          $resident= "001";
          // $customer_cnic=str_replace('-','',$customer_cnic);
          $cnic_expiry_date=$cust_profile->user->cust_cnic_detail->expiry_date;
          $cnic_expiry_date=Carbon::parse($cnic_expiry_date)->format('d/m/Y');
          $cnic_front=str_starts_with($cust_profile->user->cust_cnic_detail->cnic_front, 'http')?$cust_profile->user->cust_cnic_detail->cnic_front:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->cnic_front;
          $cnic_back=str_starts_with($cust_profile->user->cust_cnic_detail->cnic_back, 'http')?$cust_profile->user->cust_cnic_detail->cnic_back:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->cnic_back;
          $income_proof=str_starts_with($cust_profile->user->cust_cnic_detail->income, 'http')?$cust_profile->user->cust_cnic_detail->income:env('S3_BUCKET_URL').'/'.$cust_profile->user->cust_cnic_detail->income;
          if(isset($cust_profile->user->cust_cnic_detail->cnic_front))
          {
          $cnic_front_file=file_get_contents($cnic_front);
          $cnic_front_ext=explode(".",$cnic_front);
          $cnic_front_ext=$cnic_front_ext[count($cnic_front_ext)-1];
          }
          if(isset($cust_profile->user->cust_cnic_detail->cnic_back))
          {
            $cnic_back_file=file_get_contents($cnic_back);
            $cnic_back_ext=explode(".",$cnic_back);
            $cnic_back_ext=$cnic_back_ext[count($cnic_back_ext)-1];
          }
          if(isset($cust_profile->user->cust_cnic_detail->income))
          {
          $income_proof_ext=explode(".",$income_proof);
          $income_proof_ext=$income_proof_ext[count($income_proof_ext)-1];
          $income_proof_file=file_get_contents($income_proof);
          }
          $cnic_issue_date=$cust_profile->user->cust_cnic_detail->issue_date;
          $cnic_issue_date=Carbon::parse($cnic_issue_date)->format('d/m/Y');
          $father_name=strtoupper($cust_profile->user->cust_basic_detail->father_name);
          $mother_name=strtoupper($cust_profile->user->cust_basic_detail->mother_name);
          $gender=$cust_profile->user->cust_basic_detail->gender=="Male"?"M":"F";
          if ($gender == "M")
          $title = "Mr";
          else
          $title = "Ms";
          $income_bracket = "01";
          $marital_status= "M";
          $dividend_instruction="R";
          // $occupation=$cust_profile->user->cust_basic_detail->amc_occupation_id;
          // $income_source=$cust_profile->user->cust_basic_detail->amc_income_source_id;
          $email=$cust_profile->user->email;
          $mobile=str_replace('+92','0',$cust_profile->user->phone_no);
          $mobile=str_replace(' ','',$mobile);
          $address=$cust_profile->user->cust_basic_detail->current_address;
          $dob=$cust_profile->user->cust_basic_detail->dob;
          $dob=Carbon::parse($dob)->format('d/m/Y');
          $city_id=$cust_profile->user->cust_basic_detail->city;
          $country_id=$cust_profile->user->cust_basic_detail->country;
          $bank_id=$cust_profile->user->cust_basic_detail->bank;
          $occupation_id=$cust_profile->user->cust_basic_detail->occupation;
          $income_source_id=$cust_profile->user->cust_basic_detail->income_source;
          
          $income_source=AmcSourceofIncome::where('amc_id', 1)->where('ypay_source_of_income_id',$income_source_id)->pluck('amc_source_of_income_id')->first();
          $country=AmcCountry::where('amc_id', 1)->where('ypay_country_id',$country_id)->pluck('amc_country_id')->first();
          $city=AmcCity::where('amc_id', 1)->where('ypay_city_id',$city_id)->pluck('amc_city_code')->first();
          $occupation=AmcOccupation::where('amc_id', 1)->where('ypay_occupation_id',$occupation_id)->pluck('amc_occupation_id')->first();
          $bank=AmcBank::where('amc_id', 1)->where('ypay_bank_id',$bank_id)->pluck('amc_bank_id')->first();
          // $city=$cust_profile->user->cust_basic_detail->amc_city_id;
          // $country=$cust_profile->user->cust_basic_detail->amc_country_id;
          $nationality=$cust_profile->user->cust_basic_detail->nationality;
          // $bank=$cust_profile->user->cust_basic_detail->amc_bank_id;
          $bank_account_no=$cust_profile->user->cust_bank_detail->bank_account_number;
          $iban=strtoupper($cust_profile->user->cust_bank_detail->iban);
          $zakat=$cust_profile->user->cust_basic_detail->zakat=="0"?"N":"Y";
          
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => $amc_cnic_check_api->url.'?NIC='.$customer_cnic,
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
          // echo $response;
          if ($response == 'Fresh User') {

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $amc_account_opening_api->url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "bank": "'.$bank.'",
                "cell": "'.$mobile.'",
                "city": "'.$city.'",
                "cnic": "'.$customer_cnic.'",
                "iban": "'.$iban.'",
                "name": "'.$customer_name.'",
                "email": "'.$email.'",
                "gender": "'.$gender.'",
                "userid": "'.$user_id.'",
                "address": "'.$address.'",
                "country": "'.$country.'",
                "religion": "'.$religion.'",
                "resident": "'.$resident.'",
                "birthdate": "'.$dob.'",
                "mothername": "'.$mother_name.'",
                "occupation": "'.$occupation.'",
                "cnicisuedate": "'.$cnic_issue_date.'",
                "incomesource": "'.$income_source_id.'",
                "incomebracket": "'.$income_bracket.'",
                "maritalstatus": "'.$marital_status.'",
                "cnicexpirydate": "'.$cnic_expiry_date.'",
                "mobile": "'.$mobile.'",
                "zakatdeduction": "'.$zakat.'",
                "internetbanking": "Y",
                "bankaccounttitle": "'.$customer_name.'",
                "bankaccountnumber": "'.$bank_account_no.'",
                "dividendinstruction": "'.$dividend_instruction.'",
                "fatherorhusbandname": "'.$father_name.'",
                "title": "'.$title.'"
            }
            ',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'apiToken:'. $amc_account_opening_api->access_key
              ),
            ));

            $response = curl_exec($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            echo $response;
            $response_json = json_decode($response, true);
            if($http_status=="200" && $response_json['RESPONSE_MESSAGE'] == "Data Inserted Successfully!")
            {
              $cust_profile->amc_reference_number=$response_json['CLIENT_ID'];
              $cust_profile->status=0;
              $cust_profile->verified=2;
              $cust_profile->verified_at=Carbon::now();
              $cust_profile->save();
              if(isset($cust_profile->user->cust_cnic_detail->cnic_front))
              Storage::disk('jsil-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_front.'.$cnic_front_ext, $cnic_front_file);
              if(isset($cust_profile->user->cust_cnic_detail->cnic_back))
              Storage::disk('jsil-ftp')->put($customer_cnic.' '.$customer_name.'/profile/cnic_back.'.$cnic_back_ext, $cnic_back_file);
              if(isset($cust_profile->user->cust_cnic_detail->income))
              Storage::disk('jsil-ftp')->put($customer_cnic.' '.$customer_name.'/profile/income_proof.'.$income_proof_ext, $income_proof_file);
            } else {
              $cust_profile->verified=2;
              $cust_profile->verified_at=Carbon::now();
              $cust_profile->status=3;
              $cust_profile->rejected_reason=$response_json['RESPONSE_MESSAGE'];
              $cust_profile->save();
            }
          } else {
            $cust_profile->verified=2;
            $cust_profile->verified_at=Carbon::now();
            $cust_profile->status=2;
            $cust_profile->rejected_reason='Account with AMC Already Exist';
            $cust_profile->save();
            $user=$cust_profile->user;
            // $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_request_denied_by_amc.php';
            // $body = ['email' => $user->email, 'name'=>$user->full_name,'amc_name'=>$cust_profile->amc->entity_name];
            // sendEmail($body,$url);
            // $data = ['message' => sprintf(Config::get('messages.profile_request_denied_amc'), $cust_profile->amc->entity_name), 'image' => ''];
            // sendNotification($user->fcm_token, $data, $user->id, 'profile_rejected');
          }
        }
      // }
      // catch (\Exception $e) {
      //   $cron_job_log=new CronJobLog;
      //   $cron_job_log->cron_job_name='Account Opening Confirmation';
      //   $cron_job_log->amc_id=1;
      //   $cron_job_log->status=0;
      //   $cron_job_log->failure_reason=$e->getMessage();
      //   $cron_job_log->save();
      // }
    }
    public function jsil_accounts_opening_confirmation()
    {
      $amcCustProfiles=AmcCustProfile::with('user')->where('status','0')->whereNotNull('amc_reference_number')->where('amc_id','2')->get();
      try{
        $cust_cnics=[];
        // $flag=true;
        // $current_date = date('Y_m_d');
        // var_dump($current_date);
        $amc_api_data=AmcAPI::where('amc_id',1)->where('name','JSIL Account Opening Confirmation API')->first();
        foreach($amcCustProfiles as $cust_profile)
        {
          try {
            $customer_cnic=$cust_profile->user->cust_cnic_detail->cnic_number;
            $cust_cnics[]=$customer_cnic;
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $amc_api_data->url.'?nicPassport='.$customer_cnic,
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
            $response_json = json_decode($response, true);
            if ($response_json['Status'] == 1) {
              $cust_profile->account_number=$response_json['FolioNumber'];
              $cust_profile->status=1;
              $cust_profile->save();
            } else if ($response_json['Status'] == 2) {
              $cust_profile->rejected_reason=$response_json['response_message'];
              $cust_profile->status=2;
              $cust_profile->save();
            }
          }
          catch (\Exception $e) {
            $cust_profile->rejected_reason=$e->getMessage();
            $cust_profile->status=3;
            $cust_profile->save();
            continue;
          }
        }
        if(count($amcCustProfiles)>0 && count($cust_cnics)>0)
        {
          $url  = 'https://networks.ypayfinancial.com/api/mailv1/kyc_process_email.php';
          $ypay_email=Configurations::where('name','YPay Email')->pluck('value')->first();
          $body = ["email" => $ypay_email,"ids"=>$cust_cnics,"message"=>"The following customer(s) have submitted Profile request(s).
          
          Your swift action is highly appreciated."];
          sendEmail($body,$url);
        }
      }
      catch (\Exception $e) {
        $cron_job_log=new CronJobLog;
        $cron_job_log->cron_job_name='JSIL Account Opening Confirmation';
        $cron_job_log->amc_id=1;
        $cron_job_log->status=0;
        $cron_job_log->failure_reason=$e->getMessage();
        $cron_job_log->save();
       }
      //return response(["status"=>200,'message' => 'All In-Process AMC Profiles Statuses Updated Successfully!']);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amc_id'   => 'required',
            'user_id'   => 'required',
            'account_type'          => 'required',
            // 'status' => 'required',
        //     'rejected_reason' => 'required_if:status,2'
        ]
        // ,[
        //   'rejected_reason.required_if' => 'this field is required',
        // ]
      );
        if ($validator->fails()) {
          // return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
          return response()->json(['error' => $validator->errors()]);
        }

        $oldAmcCustProfile = AmcCustProfile::where('amc_id',$request->amc_id)->where('user_id',$request->user_id)->first();
        if($oldAmcCustProfile)
        {
            return response()->json(['error1' => true,'error' => 'Profile Already exists to this Amc']);
        }

        $amcCustProfiles                        = new AmcCustProfile();
        $amcCustProfiles->amc_id                = $request->amc_id;
        $amcCustProfiles->user_id               = $request->user_id;
        $amcCustProfiles->status                = 1;
        $amcCustProfile->account_type           = $request->account_type;
        // $amcCustProfiles->rejected_reason       = $request->rejected_reason;
        $amcCustProfiles->account_number        = $request->account_number;
        $amcCustProfiles->reference             = $request->reference;
        $amcCustProfiles->save();

        return response()->json(['success'=> true, 'message' => 'Customer Amc Profile Created Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            // 'amc_id'          => 'required',
            // 'user_id'         => 'required',
            'status'          => 'required',
            'account_type'          => 'required',
            'rejected_reason' => 'required_if:status,2',
        ],[
          'rejected_reason.required_if' => 'This field is required',
          'account_type.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
          // return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
          return response()->json(['error' => $validator->errors()]);
        }

        // $amcCustProfile->amc_id = $request->amc_id;
        // $amcCustProfile->amc_id = $request->user_id;
        $amcCustProfile                  = AmcCustProfile::find($id);
        $amcCustProfile->status          = $request->status;
        $amcCustProfile->account_number  = $request->account_number;
        $amcCustProfile->rejected_reason = $request->rejected_reason;
        $amcCustProfile->account_type    = $request->account_type;
        $amcCustProfile->reference       = $request->reference;
        $amcCustProfile->save();

        return response()->json(['success'=> true, 'message' => 'Customer Amc Profile Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function amcList(Request $request)
    {
        try {
            $data = [];
            $queryTerm = $request->q;
            $amcs =  Amc::where('entity_name', 'like', '%' . $queryTerm . '%')->get();
            // dd($amcs);
            foreach ($amcs as $amc) {
                $data[] = ['id' => $amc->id, 'text' => $amc->entity_name];
            }
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function customerDropDownList(Request $request)
    {
        try {
            $data = [];
            $queryTerm = $request->q;
            $users = User::with('cust_cnic_detail')->whereHas('cust_account_detail', function ($q){ 
              $q->whereIn('status',[0,1]);
            })->where( function($r) use ($queryTerm){
                $r->where('full_name', 'like', '%' . $queryTerm . '%')
                ->orWhereHas('cust_cnic_detail', function ($s) use ($queryTerm){
                    $s->where('cnic_number',  'like',  $queryTerm . '%' );
                  });  
              })->where('type',2)->get();
            foreach ($users as $user) {
                $data[] = ['id' => $user->id, 'text' => $user->full_name.' - '.$user->cust_cnic_detail->cnic_number];
            }
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function filter($request, $amcCustProfiles)
    {

     
      try {
        
  
        if (isset($request->customer) && $request->customer != "null") {
          $customer = $request->customer;
            
            $amcCustProfiles = $amcCustProfiles->where('user_id',$customer);
            
          }
          
      
          
        if(isset($request->amc) && $request->amc != "null"){
          $amc = $request->amc;
         
          $amcCustProfiles = $amcCustProfiles->where('amc_id',$amc);

          
        }

        if (isset($request->status)) {   
                
          $amcCustProfiles = $amcCustProfiles->where('status',$request->status);
          
        }
        if (isset($request->folio_number)) {   
                
          $amcCustProfiles = $amcCustProfiles->where('account_number',$request->folio_number);
          
        }

        if (isset($request->account_number)) {   

            // $fundId = Investment::where('account_number',$request->account_number)->first();
            // $amcId  = Fund::where('id',$fundId->fund_id)->pluck('amc_id')->first();

            // $amcCustProfiles = $amcCustProfiles->where('amc_id',$amcId)->where('user_id',$fundId->user_id);
            
            $amcCustProfiles = $amcCustProfiles->where('account_number','like', '%' .$request->account_number. '%');
            
          }

          if (isset($request->rejected_reason)) {   
                
            $amcCustProfiles = $amcCustProfiles->where('rejected_reason','like', '%' . $request->rejected_reason . '%');
            
          }

          if (isset($request->reference)) {   
                
            // $fundId = Investment::where('reference','like', '%' . $request->reference . '%')->first();
            
            // $amcId  = Fund::where('id',$fundId->fund_id)->pluck('amc_id')->first();
            // $amcCustProfiles = $amcCustProfiles->where('amc_id',$amcId)->where('user_id',$fundId->user_id);
            
            $amcCustProfiles = $amcCustProfiles->where('reference','like', '%' .$request->reference. '%');
          }
        return $amcCustProfiles;
  
      } catch (\Exception $e) {
       echo "<pre>";
       print_r($e);
       echo "</pre>";
        return ['error' => 'Something went wrong'];
      }
    }

}
