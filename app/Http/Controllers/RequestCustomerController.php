<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustAccountDetail;
use App\Models\AdminComments;
use App\Models\Amc;
use App\Models\Investment;
use App\Models\Redemption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use Illuminate\Support\Facades\Validator;
use File;
use Config;
use PDF;
use DB;

class RequestCustomerController extends Controller
{
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
        return view('customer.requests',compact('users'));
    }
    public function export(Request $request)
    {
      $selected_profiles=$request['selected_profiles'];
      foreach($selected_profiles as $profile)
      {
        $profile= User::with('cust_bank_detail','cust_cnic_detail','cust_basic_detail','cust_account_detail','cust_investment')->where('type',2)->whereHas('cust_account_detail', function ($q){
            $q->whereIn('status',[0,1,2,3]);
          })->where('id',$profile)->first();
        $filtered_users[]=$profile;
      }
      $statuses = array("0"=>"Pending", "1"=>"Approved", "2"=>"Rejected", "3"=>"On Hold", "-1"=> "No Profile");
      // $users = User::with('cust_bank_detail','cust_cnic_detail','cust_basic_detail','cust_account_detail','cust_investment')->where('type',2)->whereHas('cust_account_detail', function ($q){
      //   $q->whereIn('status',[0,1,2,3]);
      // });
      // $filtered_users=$this->filter($request,$users)->get();
      $timestamp=Carbon::now()->timestamp;
      $file_name=$timestamp.".csv";
      if(!file_exists('storage/uploads/investment'))
      {
        mkdir('storage/uploads/investment',0777,true);
        chmod('storage/uploads/investment', 0777);
      }
      $users_csv = fopen('storage/uploads/investment/'.$file_name,"a");
      chmod('storage/uploads/investment/'.$file_name, 0777);
      // $heading=array("Name","Phone Number","Profile Status","City","Nationality","Gender","Occupation","Source of Income","Registered Date","Email","CNIC","Registered Bank Name","IBAN","Bank Account Number","Fund","Investment ID (Transaction ID)","Investment Units","Investment Amount","Investment Date","Payment Type","Redemption ID (Transaction ID)","Redemption Amount","Redemption Date");
      $heading=array("Name","Phone Number","Profile Status","City","Nationality","Gender","Occupation","Source of Income","Registered Date","Email","CNIC","Registered Bank Name","IBAN","Bank Account Number");
      fputcsv($users_csv, $heading); 
      foreach($filtered_users as $user)
      {
        if (substr($user->phone_no, 0, 3) != "+92")
          $phone_no = (string) "+92".substr($user->phone_no, 1);
        else
          $phone_no = (string) $user->phone_no;
        $phone_no = preg_replace('/\s+/', '', $phone_no);
        // if(count($user->cust_investment)==0)
        // {
        // $user_data=array(
        //   $user->full_name??'',
        //   $phone_no,
        //   $statuses[$user->cust_account_detail->status]??'',
        //   $user->cust_basic_detail->cities->city??'',
        //   $user->cust_basic_detail->nationality??'',
        //   $user->cust_basic_detail->gender??'',
        //   $user->cust_basic_detail->occupations->name??'',
        //   $user->cust_basic_detail->income_sources->income_name??'',
        //   $user->created_at??'',
        //   $user->email??'',
        //   preg_replace("/^1?(\d{5})(\d{7})(\d{1})$/","$1-$2-$3", $user->cust_cnic_detail->cnic_number??''),
        //   $user->cust_basic_detail->bank??'',
        //   $user->cust_bank_detail->iban??'',
        //   $user->cust_bank_detail->bank_account_number??'',
        //   '',
        //   '',
        //   '',
        //   '',
        //   '',
        //   '',
        //   '',
        //   '',
        //   '',
        //   );
        $user_data=array(
          $user->full_name??'',
          $phone_no,
          $statuses[$user->cust_account_detail->status]??'',
          $user->cust_basic_detail->cities->city??'',
          $user->cust_basic_detail->nationality??'',
          $user->cust_basic_detail->gender??'',
          $user->cust_basic_detail->occupations->name??'',
          $user->cust_basic_detail->income_sources->income_name??'',
          $user->cust_cnic_detail->created_at??'',
          $user->email??'',
          preg_replace("/^1?(\d{5})(\d{7})(\d{1})$/","$1-$2-$3", $user->cust_cnic_detail->cnic_number??''),
          $user->cust_basic_detail->bank??'',
          $user->cust_bank_detail->iban??'',
          $user->cust_bank_detail->bank_account_number??'',
          );
          $users_csv = fopen('storage/uploads/investment/'.$file_name,"a");
          fputcsv($users_csv, $user_data);
        // }
        // else
        // {
        //  foreach($user->cust_investment as $investment)
        //  {
        //   if(count($investment->redemption)==0)
        //   {
        //   $user_data=array(
        //     $user->full_name??'',
        //     $phone_no,
        //     $statuses[$user->cust_account_detail->status]??'',
        //     $user->cust_basic_detail->cities->city??'',
        //     $user->cust_basic_detail->nationality??'',
        //     $user->cust_basic_detail->gender??'',
        //     $user->cust_basic_detail->occupations->name??'',
        //     $user->cust_basic_detail->income_sources->income_name??'',
        //     $user->created_at??'',
        //     $user->email??'',
        //     preg_replace("/^1?(\d{5})(\d{7})(\d{1})$/","$1-$2-$3", $user->cust_cnic_detail->cnic_number??''),
        //     $user->cust_basic_detail->bank??'',
        //     $user->cust_bank_detail->iban??'',
        //     $user->cust_bank_detail->bank_account_number??'',
        //     $investment->fund->fund_name,
        //     $investment->transaction_id??'',
        //     $investment->unit??'',
        //     $investment->amount??'',
        //     date('Y-m-d',strtotime($investment->created_at))??'',
        //     $investment->pay_method??'',
        //     '',
        //     '',
        //     ''
        //    );
        //    if(!file_exists('storage/uploads/investment'))
        //    {
        //      mkdir('storage/uploads/investment',0777,true);
        //      chmod('storage/uploads/investment', 0777);
        //    }
        //    $users_csv = fopen('storage/uploads/investment/'.$file_name,"a");
        //    chmod('storage/uploads/investment/'.$file_name, 0777);
        //    fputcsv($users_csv, $user_data);
        //   }
        //   else
        //   {
        //     foreach($investment->redemption as $redemption)
        //     {
        //       $user_data=array(
        //         $user->full_name??'',
        //         $phone_no,
        //         $statuses[$user->cust_account_detail->status]??'',
        //         $user->cust_basic_detail->cities->city??'',
        //         $user->cust_basic_detail->nationality??'',
        //         $user->cust_basic_detail->gender??'',
        //         $user->cust_basic_detail->occupations->name??'',
        //         $user->cust_basic_detail->income_sources->income_name??'',
        //         $user->created_at??'',
        //         $user->email??'',
        //         preg_replace("/^1?(\d{5})(\d{7})(\d{1})$/","$1-$2-$3", $user->cust_cnic_detail->cnic_number??''),
        //         $user->cust_basic_detail->banks->name??'',
        //         $user->cust_bank_detail->iban??'',
        //         $user->cust_bank_detail->bank_account_number??'',
        //         $investment->fund->fund_name,
        //         $investment->transaction_id??'',
        //         $investment->unit??'',
        //         $investment->amount??'',
        //         date('Y-m-d',strtotime($investment->created_at))??'',
        //         $investment->pay_method??'',
        //         $redemption->transaction_id??'',
        //         $redemption->amount,
        //         date('Y-m-d',strtotime($redemption->created_at))??'',
        //        );
        //        if(!file_exists('storage/uploads/investment'))
        //        {
        //          mkdir('storage/uploads/investment',0777,true);
        //          chmod('storage/uploads/investment', 0777);
        //        }
        //        $users_csv = fopen('storage/uploads/investment/'.$file_name,"a");
        //        chmod('storage/uploads/investment/'.$file_name, 0777);
        //        fputcsv($users_csv, $user_data);
        //     }
        //   }
        //   }
        //  }
      }
      $user_csv[$file_name]=file_get_contents('storage/uploads/investment/'.$file_name);
      File::delete('storage/uploads/investment/'.$file_name);
      return response()->json(['success'=> true, 'message' => 'Data Sent successfully!',"user_csv"=>$user_csv]);
    }
    public function show(Request $request)
    {
    //   $users = User::where('type', 2)->with('cust_account_detail','cust_cnic_detail')->whereHas('cust_account_detail', function ($q){
    //     $q->whereIn('status', [0, 2]);
    //   });
      $users = User::with('comments')->select('users.id as id','users.status as status','users.platform as platform','users.app_version as app_version','users.full_name as full_name','users.email as email','cust_cnic_details.cnic_number as cnic_number',
      'cust_account_details.status as cust_status',
      DB::raw('DATE_FORMAT(cust_cnic_details.created_at,"%Y-%m-%d %r") as registered_on'),
      'users.phone_no as phone_no',
      'users.updated_at as updated_at','users.refer_code as refer_code','cust_account_details.risk_profile_status as risk_profile_status')
      ->leftjoin('cust_account_details','cust_account_details.user_id','users.id')
      ->leftjoin('cust_cnic_details','cust_cnic_details.user_id','users.id')
      ->where('type',2)
      ->whereIn('cust_account_details.status',[0,1,2,3]);
    //   dd($users);
       return DataTables::of($this->filter($request, $users))->order(function ($q) use ($request) {
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {
          $validator = Validator::make($request->all(), [
              'company_name'        => 'required',
              'category'            => 'required',
              'logo'                => 'required|image',
              'contact_no'          => 'required',
              'contact_person_name' => 'required',
              'contact_person_role' => 'required',
              'secp_number'         => 'required',
              'status'              => 'required',
          ]);

          if ($validator->fails()) {
              return response()->json(['error' => $validator->errors()]);
          }

          $file             = $request->file('logo');
          $fileOriginalName = $file->getClientOriginalName();
          $extension        = $file->getClientOriginalExtension();
          $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
          $filename         = $file->storeAs('public/uploads', $fileNameToStore);

          $amc                      = new Amc();
          $amc->company_name        = $request->company_name;
          $amc->category            = $request->category;
          $amc->logo                = $filename;
          $amc->original_name       = $fileOriginalName;
          $amc->contact_no          = $request->contact_no;
          $amc->contact_person_name = $request->contact_person_name;
          $amc->contact_person_role = $request->contact_person_role;
          $amc->secp_number         = $request->secp_number;
          $amc->status              = $request->status;
          $amc->user_id             = auth()->user()->id;
          $amc->save();

          return response()->json(['success'=> true, 'message' => 'AMC Created successfully!']);

      } catch (\Exception $e) {
        echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
          return ['error' => 'Something went wrong'];
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Amc $amc,$user_id)
    {

      $validator = Validator::make($request->all(), [
              // 'company_name'        => 'required',
              // 'category'            => 'required',
              // 'logo'                => 'required|mimes:png,jpg,jpeg',
              // 'contact_no'          => 'required',
              // 'contact_person_name' => 'required',
              // 'contact_person_role' => 'required',
              // 'secp_number'         => 'required',
              'status'              => 'required',
              'profile_status'      => 'required',
              'risk_profile_status'      => 'required',
          ]);

          if ($validator->fails()) {
              return response()->json(['error' => $validator->errors()]);
          }

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()/* ->all() */]);
      }
      CustAccountDetail::where('user_id',$user_id)->update(['status'=>$request->profile_status,'risk_profile_status'=>$request->risk_profile_status]);
      $user=User::where("id",$user_id)->update(['status'=>$request->status]);
      $user=User::where("id",$user_id)->first();
      if($request->profile_status == 1) {

        // email notificaion 
      $url = 'https://networks.ypayfinancial.com/api/mailv1/approve_profile.php';
      $body = ['email' => $user->email, 'name'=>$user->full_name];
      sendEmail($body,$url);
        // Mail::send('mail.userStatusApproved', ['name'=> $user->full_name], function($message) use ($user) {
        //   $message->to($user->email, $user->full_name)->subject('Profile Accepted');
        //   $message->from('hello@ypayfinancial.com', 'YPay');
        // });
        $data = ['message' => Config::get('messages.profile_approved_successfull'), 'image' => ''];
        sendNotification($user->fcm_token, $data, $user->id, 'Wootwoot! 🦉Your investment profile has just been approved 💰');
      }
      else if($request->profile_status == 2) {
         
        // email notificaion 
      $url = 'https://networks.ypayfinancial.com/api/mailv1/reject_profile.php';
      $body = ['email' => $user->email, 'name'=>$user->full_name];
      sendEmail($body,$url);
        // Mail::send('mail.userStatusRejection', ['name'=> $user->full_name], function($message) use ($user) {
        //   $message->to($user->email, $user->full_name)->subject('Profile Rejected');
        //   $message->from('hello@ypayfinancial.com', 'YPay');
        // });
        $data = ['message' => Config::get('messages.profile_verification_denied'), 'image' => ''];
        sendNotification($user->fcm_token, $data, $user->id, 'profile_rejected');
      }else if($request->profile_status == 3){

         // email notificaion 
      $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_onhold.php';
      $body = ['email' => $user->email, 'name'=>$user->full_name];
      sendEmail($body,$url);

        // $data = ['message' => 'Dear Customer, your profile status is On-hold, kindly coordinate our customer support for more details.', 'image' => ''];
        // sendNotification($user->fcm_token, $data, $user->id, 'profile_on_hold');
      }

      return response()->json(['success'=> true, 'message' => 'User updated successfully!']);
      // $amc->company_name        = $request->company_name;
      // $amc->category            = $request->category;
      // // $amc->logo                = $filename;
      // // $amc->original_name       = $fileOriginalName;
      // $amc->contact_no          = $request->contact_no;
      // $amc->contact_person_name = $request->contact_person_name;
      // $amc->contact_person_role = $request->contact_person_role;
      // $amc->secp_number         = $request->secp_number;
      // $amc->status              = $request->status;
      // $amc->save();
    }



    public function filter($request, $users)
    {
        try {
            if (isset($request->customerId) && $request->customerId!='null') {
              $users = $users->where('users.id',$request->customerId);
            }
            if (isset($request->refer_code) && $request->refer_code!='null') {
              $users = $users->where('users.refer_code','like', '%' .$request->refer_code. '%');
            }
            if (isset($request->platform) && $request->platform!='null') {
              $users = $users->where('users.platform','like', '%' .$request->platform. '%');
            }
            if (isset($request->app_version) && $request->app_version!='null') {
              $users = $users->where('users.app_version','like', '%' .$request->app_version. '%');
            }

            if (isset($request->cnic) && $request->cnic!='null') {
                $cnic = $request->cnic;
                $users = $users->whereHas('cust_cnic_detail', function ($q) use ($cnic){
                  $q->where('cnic_number', 'like', '%' . $cnic . '%');
                });
               }
            


            if (isset($request->from) && isset($request->to) && $request->from!= "null" && $request->to!= "null") {
                $dateFrom = $request->from;
                $dateTo = $request->to;
                $users = $users->whereHas('cust_cnic_detail', function($q) use($dateFrom,$dateTo) {
                  $q->whereBetween('created_at',[[date("Y-m-d H:i:s", strtotime($dateFrom)),date("Y-m-d H:i:s", strtotime($dateTo))]]);
                });
            }


            // if (isset($request->to) && $request->to!='null') {
            //     $dateTo = $request->to;
            //     $users = $users->whereHas('cust_cnic_detail', function($q) use($dateTo) {
            //       $q->whereDate('created_at', '<=', Carbon::parse($dateTo) );
            //     });
            // }

            if(isset($request->status) && $request->status!='null')
            {
              $userStatus = $request->status;
              $users = $users->where('users.status',$userStatus);
            }
            if (isset($request->phone_no) && $request->phone_no!='null') {
              $users = $users->where('users.phone_no','like','%'.$request->phone_no.'%');
            }
            if (isset($request->profile_status) && $request->profile_status!='null') {
                $profileStatus = $request->profile_status;
                $users = $users->whereHas('cust_account_detail', function($q) use($profileStatus) {
                  $q->where('status', $profileStatus);
                });
            }
            if (isset($request->risk_profile_status) && $request->risk_profile_status!='null') {
              $risk_profile_status = $request->risk_profile_status;
              $users = $users->whereHas('cust_account_detail', function($q) use($risk_profile_status) {
                $q->where('risk_profile_status', $risk_profile_status);
              });
            }

            if (isset($request->email)) {
              $users = $users->where('email', 'like', '%' . $request->email . '%');
             }
            return $users;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function transaction_history(Request $request, $id) {
      return view('customer.transaction_history', compact('id'));
    }

    public function get_transaction_history_data(Request $request, $id) {
      // dd($request->transaction_type);
      $transaction_type = $request->transaction_type;
      if($transaction_type=="investments")
        $data = Investment::with('fund','redemption','user', 'user.cust_cnic_detail');
      else
        $data = Redemption::with('investment.user','investment.user.cust_cnic_detail','investment.fund');
      // $redemption_data = [];
      // $investment_data = [];
      // foreach($investment as $key => $value)
      // {
      //     $investment_data[$key]['invest_id'] = $value['id'];
      //     $investment_data[$key]['date']      = $value['created_at']->format('Y-m-d H:i:s');
      //     $investment_data[$key]['type']      = 'Investment';
      //     $investment_data[$key]['funds']     = $value['fund']['fund_name'];
      //     $investment_data[$key]['amount']    = $value['amount'];
      //     $investment_data[$key]['status']    = $value['status'];  
      //     $investment_data[$key]['transaction_id']    = $value['transaction_id']; 
      //     $investment_data[$key]['rejected_reason']    = $value['rejected_reason'];  
      //     if($value['redemption'])
      //     {
      //         foreach($value['redemption'] as $key2 => $value2){
      //             $redemption_data[$key]['invest_id'] = $value['id'];
      //             $redemption_data[$key]['date']      = $value2['created_at']->format('Y-m-d H:i:s');
      //             $redemption_data[$key]['type']      = 'Redemption';
      //             $redemption_data[$key]['funds']     = $value['fund']['fund_name'];
      //             $redemption_data[$key]['amount']    = isset($value2['redeem_amount']) ? $value2['redeem_amount'] : $value2['amount'];
      //             $redemption_data[$key]['status']    = $value2['status'];
      //             $redemption_data[$key]['transaction_id']    = $value2['transaction_id'];   
      //             $redemption_data[$key]['rejected_reason']    = $value2['rejected_reason'];   
      //         }
      //     }
      // }
      // $data = array_merge($investment_data, $redemption_data);
      // array_multisort(array_column($data, 'date'), SORT_DESC, $data);
      // $tableData = [];
      // foreach ($data as $key => $value) {
      //     $tableData[$key]['date'] = date('d M,Y',strtotime($value['date']));
      //     $tableData[$key]['transaction_id'] = $value['transaction_id'];
      //     $tableData[$key]['type'] = $value['type'];
      //     $tableData[$key]['funds'] = strlen($value['funds']) > 10 ? substr($value['funds'],0,10)."..." : $value['funds'];
      //     $tableData[$key]['amount'] = 'Rs. '.$value['amount'];
      //     $tableData[$key]['status'] = ($value['status'] == 0 ? 'Pending' : ($value['status'] == 1 ? 'Successful ' : 'Rejected'));
      //     if($value['status']==2)
      //     $tableData[$key]['rejected_reason']=$value['rejected_reason'];
      //     $tableData[$key]['investment_id'] = $value['invest_id'];
      // }
      // dd($tableData);

      return DataTables::of($this->transaction_filter($request, $data, $transaction_type, $id))->make(true);
    }

    public function transaction_filter($request,$transaction_data, $transaction_type, $user_id)
    {
        $data=$request->all();
        if($transaction_type=="investments") {
          $transaction_data = $transaction_data->where('user_id',$user_id);
          if (isset($request->fund) && $request->fund != "null") {
            $fund = $request->fund;
        
            $transaction_data = $transaction_data->where('fund_id',$fund);
           
          }
      
          
          if(isset($request->amc) && $request->amc != "null"){
            $amc = $request->amc;
          
            $transaction_data = $transaction_data->whereHas('fund.amc', function ($q) use ($amc) {
              $q->where('id',$amc);
            })->get();
          }
          if (isset($request->approvedDateFrom) && isset($request->approvedDateTo) && $request->approvedDateTo != "null" && $request->approvedDateFrom != "null") {
    
            $approvedDateFrom = $request->approvedDateFrom;
            $approvedDateTo = $request->approvedDateTo;
            $transaction_data = $transaction_data->whereBetween('approved_date',[date("Y-m-d", strtotime($approvedDateFrom)),date("Y-m-d", strtotime($approvedDateTo))]);
          }
          if (isset($request->from) && isset($request->to) && $request->from!= "null" && $request->to!= "null") {       
            $transaction_from=$request->from;
            $transaction_to=$request->to;
            $transaction_data = $transaction_data->whereBetween('created_at',[[date("Y-m-d", strtotime($transaction_from)),date("Y-m-d", strtotime($transaction_to))]]);
            
          }
          if (isset($request->status) && $request->status != "null") {       
            $transaction_data = $transaction_data->where('status',$request->status);
          }
        } else {
          if (isset($request->approvedDateFrom) && isset($request->approvedDateTo)) {
  
            $approvedDateFrom = $request->approvedDateFrom;
            $approvedDateTo = $request->approvedDateTo;
            $transaction_data = $transaction_data->whereBetween('redemptions.approved_date',[date("Y-m-d", strtotime($approvedDateFrom)),date("Y-m-d", strtotime($approvedDateTo))]);
          }
        
        
              if (isset($request->from) && isset($request->to)) {       
                $transaction_from=$request->from;
                $transaction_to=$request->to;
                $transaction_data = $transaction_data->whereBetween('redemptions.created_at',[date("Y-m-d", strtotime($transaction_from)),date("Y-m-d", strtotime($transaction_to))]);
                
              }
        
              if (isset($request->fund) && $request->fund!="null") {
                $fund = $request->fund;
                $transaction_data = $transaction_data->whereHas('investment.fund', function($q) use($fund) {
                  $q->where('id',$fund);
                });
              }
              if (isset($request->status)) {
                $transaction_data = $transaction_data->where('redemptions.status',$request->status);
              }
          $transaction_data = $transaction_data->whereHas('investment.user', function($q) use($user_id) {
            $q->where('id', $user_id);
          });
        }

        return $transaction_data;
      // } catch (\Exception $e) {
      //   echo "<pre>";
      //   print_r($e);
      //   echo "</pre>";
      //    return ['error' => 'Something went wrong'];
      //  }
    }

}
