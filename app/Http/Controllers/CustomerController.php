<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustAccountDetail;
use App\Models\Amc;
use App\Models\AmcCity;
use App\Models\AmcCountry;
use App\Models\AmcOccupation;
use App\Models\AmcBank;
use PDF;
use App\Models\AmcSourceofIncome;
use App\Models\City;
use App\Models\Country;
use App\Models\FactaCRS;
use App\Models\Question;
use App\Models\HighRiskResponse;
use App\Models\RiskProfileRank;
use App\Models\Options;
use App\Models\Occupation;
use App\Models\Bank;
use App\Models\SourcesofIncome;
use App\Models\CustBankDetail;
use App\Models\CustBasicDetail;
use App\Models\AdminComments;
use App\Models\CustCnicDetail;
use App\Models\CitizenshipStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use function App\Libraries\Helpers\s3ImageUpload;
use Config;
use Auth;
class CustomerController extends Controller
{

  // function __construct()
  //   {
  //        $this->middleware('permission:customer-list');
  //       //  $this->middleware('permission:amc-create', ['only' => ['store']]);
  //        $this->middleware('permission:customer-edit', ['only' => ['update']]);
  //   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer.index');
    }

    public function show(Request $request)
    {
      $users = User::where('type', 2)->with('cust_account_detail','cust_cnic_detail')->whereHas('cust_account_detail', function ($q){
        $q->whereIn('status', [1]);
      });
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
    public function addcustform(){
      $states=[];
      $cities=City::where('status',1)->get();
      foreach($cities as $city)
      {
        if(!in_array($city->state,$states))
        $states[]=$city->state;
      }
      $income_sources=SourcesofIncome::where('status',1)->get();
      $occupations=Occupation::where('status',1)->get();
      $banks=Bank::where('status',1)->get();
      $countries=Country::where('status',1)->get();
      $citizenship_statuses=CitizenshipStatus::where('status',1)->get();
      return view('customer.addprofile',compact('cities','income_sources','occupations','states','banks','countries','citizenship_statuses'));
    }
    public function update_response(Request $request){
                $user_id=$request->user_id;
                $high_risk_response=HighRiskResponse::where('user_id',$user_id)->first();
                $high_risk_response->user_id=$user_id;
                $high_risk_response->rank=$request->rank;
                $high_risk_response->message=$request->message;
                $high_risk_response->risk_profile_status=$request->risk_profile_status;
                $high_risk_response->total_score=$request->score;
                $high_risk_response->option_ids=$request->selection;
                $high_risk_response->save();
                return redirect()->back()->with('message', 'Response Updated successfully!');
    }
    public function edit_risk_prof_Details($id){
      $response = HighRiskResponse::where('user_id',$id)->with('user')->first();
      if(isset($response))
      {
      $options=[];
      foreach($response->option_ids as $option_id)
      {
        $options[]=Options::where('id',$option_id)->with('question')->first();
      }
      $questions=Question::with('option')->get();
      $ranks=RiskProfileRank::all();
      return view('risk_profile.edit', compact('response','options','questions','ranks'));
      }
    }
    public function risk_prof_Details($id){
      $response = HighRiskResponse::where('user_id',$id)->with('user')->first();
      if(isset($response))
      {
      $options=[];
      foreach($response->option_ids as $option_id)
      {
        $options[]=Options::where('id',$option_id)->with('question')->first();
      }
      return view('risk_profile.details', compact('response','options'));
      }
      else
      {
      $no_data='';
      return view('risk_profile.details', compact('no_data'));
      }
    }
    public function get_admin_comments($id){
      $admin_comments = AdminComments::with('user.cust_cnic_detail','commented_by')->where('user_id',$id)->get();
      if(isset($admin_comments))
      {
        return $admin_comments;
      }
    }
    public function save_admin_comments(Request $request){
      if(isset($request->comments))
      {
        $comments=$request->comments;
        $comment_by=Auth::user();
        foreach($comments as $comment)
        {
          if($comment!='')
          {
          $admin_comment=new AdminComments;
          $admin_comment->comment=$comment;
          $admin_comment->user_id=$request->id;
          $admin_comment->comment_by=$comment_by->id;
          $admin_comment->save();
          }
        }
        return response()->json(['success'=> true, 'message' => 'Comments Added successfully!']);
      }
    }
    public function export_risk_profile_resp($id){
      $response = HighRiskResponse::where('user_id',$id)->with('user')->first();
      foreach($response->option_ids as $option_id)
      {
        $options[]=Options::where('id',$option_id)->with('question')->first();
      }
      $user=User::where('id',$id)->first();
      $current_date = date('Ymd');
      $cnic_number=$user->cust_cnic_detail->cnic_number;
      $pdf = PDF::loadView('risk_profile_response_pdf', compact('response','options'));
      return $pdf->download(str_replace('-', '', $cnic_number).'_'.$current_date.'.pdf');
    }
    public function custDetails($id){
      $user = User::whereId($id)->with('cust_basic_detail.cities','cust_basic_detail.countries','cust_basic_detail.banks','cust_basic_detail.occupations','cust_basic_detail.income_sources','cust_cnic_detail.citizenshipstatus', 'cust_bank_detail', 'cust_account_detail')->first();
      $admin_comments=AdminComments::with('user.cust_cnic_detail','commented_by')->where('user_id',$id)->get();
      $facta_response=FactaCRS::where('user_id',$user->id)->orderBy('question_id','ASC')->get();
      $facta_crs_questions=Config::get('facta_questions');
      return view('customer.details', compact('user','admin_comments','facta_crs_questions','facta_response'));
    }

    public function edit_facta_details($id){
      $user = User::whereId($id)->with('cust_basic_detail.cities','cust_basic_detail.countries','cust_basic_detail.banks','cust_basic_detail.occupations','cust_basic_detail.income_sources','cust_cnic_detail.citizenshipstatus', 'cust_bank_detail', 'cust_account_detail')->first();
      $facta_response=FactaCRS::where('user_id',$user->id)->get();
      $facta_crs_questions=Config::get('facta_questions');
      return view('customer.edit_facta_details', compact('user','facta_crs_questions','facta_response'));
    }
    public function facta_details_update(Request $request,  $user_id)
    {
      $facta_details=FactaCRS::where('user_id',$user_id)->get();
      foreach($facta_details as $index=>$facta_detail)
      {
       $facta_detail->answer=$request->answers[$index];
       $facta_detail->save();
      }
      return redirect()->back()->with('success', 'Facta Details Updated successfully!');
    }
    public function export_facta_details(Request $request){
      $current_date = date('Ymd');
      $user = User::whereId($request->user_id)->with('cust_basic_detail.cities','cust_basic_detail.countries','cust_basic_detail.banks','cust_basic_detail.occupations','cust_basic_detail.income_sources','cust_cnic_detail.citizenshipstatus', 'cust_bank_detail', 'cust_account_detail')->first();
      $facta_response=FactaCRS::where('user_id',$user->id)->orderBy('question_id','ASC')->get();
      $facta_crs_questions=Config::get('facta_questions');
      $pep_questions=Config::get('pep_questions');
      $pdf = PDF::loadView('facta_crs_detail_pdf', compact('user','facta_crs_questions','facta_response','pep_questions'));
      $path = public_path();
      $fileName =  $current_date.'.pdf' ;
      $pdf->save($path . '/' . $fileName);
      $pdf = public_path($fileName);
      return response()->download($pdf);
    }
    public function custDetailsStatus(Request $request){

      $cust_account_detail              = CustAccountDetail::whereId($request->id)->first();
      $cust_account_detail->status      = $request->status ?? 0;
      $cust_account_detail->action_time = date('Y-m-d H:i:s');
      $cust_account_detail->save();
      
        $user = User::where('id',$cust_account_detail->user_id)->first();
        if($cust_account_detail->status == 1) {

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
        else if($cust_account_detail->status == 2) {
           
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
        }else if($cust_account_detail->status == 3){

           // email notificaion 
        $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_onhold.php';
        $body = ['email' => $user->email, 'name'=>$user->full_name];
        sendEmail($body,$url);

          // $data = ['message' => 'Dear Customer, your profile status is On-hold, kindly coordinate our customer support for more details.', 'image' => ''];
          // sendNotification($user->fcm_token, $data, $user->id, 'profile_on_hold');
        }

      return response()->json(['success'=> true, 'message' => 'Status Updated successfully!']);
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
    

    public function edit($id){
       $states=[];
       $user = User::whereId($id)->with('cust_basic_detail.cities', 'cust_cnic_detail', 'cust_bank_detail', 'cust_account_detail')->first();

       $cities = City::all();
       foreach($cities as $city)
      {
        if(!in_array($city->state,$states))
        $states[]=$city->state;
      }
       $countries=Country::all();
       $banks=Bank::all();
       $income_sources=SourcesofIncome::all();
       $occupations=Occupation::all();
       $admin_comments=AdminComments::where('user_id',$id)->get();
       $citizenship_statuses=CitizenshipStatus::all();
       return view('customer.editDetails',compact('user','cities','states','countries','banks','occupations','income_sources','admin_comments','citizenship_statuses'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
    public function export_profile(Request $request)
    {
      $user_id=$request->user_id;
      $user = User::whereId($user_id)->with('cust_basic_detail.cities','cust_basic_detail.countries','cust_basic_detail.banks','cust_basic_detail.occupations','cust_basic_detail.income_sources','cust_cnic_detail', 'cust_bank_detail', 'cust_account_detail')->first();
      $admin_comments=AdminComments::with('user.cust_cnic_detail','commented_by')->where('user_id',$user_id)->get();
      $pdf = PDF::loadView('cust_profile_pdf', compact('user','admin_comments'));
      $path = public_path();
      $fileName =  $user->cust_cnic_detail->cnic_number.'_'.time().'.pdf' ;
      $pdf->save($path . '/' . $fileName);
      $pdf = public_path($fileName);
      return response()->download($pdf);
    }
    public function importprofile(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'profile_data_file'       => 'required|file'
    ],
    [
      'profile_data_file.required' => 'CSV File is required',
    ]);
    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()]);
    }
      $data=$request->all();
      $profile_array=$this->csvToArray($data["profile_data_file"]);
      $line_number=2;
      $already_registered_users=[];
      foreach($profile_array as $profile)
      {
      $user1=User::where('phone_no','+'.$profile['Phone No'])->first();
      $user2=User::where('email',$profile['Email'])->first();
      if($user1==null && $user2==null)
      {
      $user = new User;
      $user->full_name        = $profile['Name'];
      $user->secret_pin       = Hash::make($profile['Pin']);
      $user->email            = $profile['Email'];
      $user->phone_no         = '+'.$profile['Phone No'];
      $user->refer_code       = $profile['Refer Code'];
      $user->type       = 2;
      $user->save();
      $data = ['message' => 'Thanks for signing up at YPay, you are one step closer to financial freedom now! YPay!', 'image' => ''];
      sendNotification($request->fcm_token, $data, $user->id, 'welcome');
      $url = 'https://networks.ypayfinancial.com/api/mailv1/signup_mail.php';
      $body = ['email' => $user->email, 'name'=> $user->full_name];
      sendEmail($body,$url);
      // // /**Customer Basic Details **/
      $cust_basic_detail                   = new CustBasicDetail;
      $cust_basic_detail->user_id          = $user->id;
      $cust_basic_detail->father_name      = $profile['Father Name'];
      $cust_basic_detail->mother_name      = $profile['Mother Name'];
      $cust_basic_detail->dob              = date('Y-m-d',strtotime($profile['D.O.B']));
      $cust_basic_detail->current_address  = $profile['Current Address'];
      $cust_basic_detail->gender           = $profile['Gender'];
      $cust_basic_detail->nationality      = "Pakistani";
      $cust_basic_detail->nominee_name     = $profile['Nominee'];
      $cust_basic_detail->nominee_cnic     = $profile['Nominee CNIC Number'];
      $cust_basic_detail->source_of_income = $profile['Source of Income'];
      $city_state=City::where('id',$profile['City'])->pluck('state')->first();
      $amc_city_id=AmcCity::where('ypay_city_id',$profile['City'])->pluck('amc_city_code')->first();
      $cust_basic_detail->amc_city_id      = $amc_city_id??null;
      $amc_country_id=AmcCountry::where('ypay_country_id','1')->pluck('amc_country_id')->first();
      $cust_basic_detail->amc_country_id   = $amc_country_id??null;
      $amc_bank_id=AmcBank::where('ypay_bank_id',$profile['Bank'])->pluck('amc_bank_id')->first();
      $cust_basic_detail->amc_bank_id   = $amc_bank_id??null;
      $amc_occupation_id=AmcOccupation::where('ypay_occupation_id',$profile['Occupation'])->pluck('amc_occupation_id')->first();
      $cust_basic_detail->amc_occupation_id   = $amc_occupation_id??null;
      $amc_income_source_id=AmcSourceofIncome::where('ypay_source_of_income_id',$profile['Source of Income'])->pluck('amc_source_of_income_id')->first();
      $cust_basic_detail->amc_income_source_id   = $amc_income_source_id??null;
      $cust_basic_detail->city             = $profile['City'];
      $cust_basic_detail->country          = "Pakistan";
      $cust_basic_detail->occupation       = $profile['Occupation'];
      $cust_basic_detail->income_source    = $profile['Source of Income'];
      $cust_basic_detail->bank             = $profile['Bank'];
      $cust_basic_detail->state            = $city_state;
      $cust_basic_detail->zakat            = $profile['Zakat'];
      $cust_basic_detail->save();

      // // /**Customer CNIC Details **/
      $cust_cnic_detail               = new CustCnicDetail;
      $cust_cnic_detail->user_id      = $user->id;
      $cust_cnic_detail->cnic_number  = $profile['CNIC'];
      $cust_cnic_detail->issue_date   = date('Y-m-d',strtotime($profile['CNIC ISSUE DATE']));
      $cust_cnic_detail->expiry_date  = date('Y-m-d',strtotime($profile['CNIC EXPIRY DATE']));
      // if ($request->file('cnic_front')) {
      //   $file               = $request->file('cnic_front');
      //   $fileOriginalName   = $file->getClientOriginalName();
      //   $extension          = $file->getClientOriginalExtension();
      //   $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
      //   $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
      //   $cust_cnic_detail->cnic_front          = '/storage/uploads/'.$fileNameToStore;
      //   // $cust_cnic_detail->original_name = $fileOriginalName;
      // }
      
      // if ($request->file('cnic_back')) {
      //   $file               = $request->file('cnic_back');
      //   $fileOriginalName   = $file->getClientOriginalName();
      //   $extension          = $file->getClientOriginalExtension();
      //   $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
      //   $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
      //   $cust_cnic_detail->cnic_back          = '/storage/uploads/'.$fileNameToStore;
      //   // $cust_cnic_detail->original_name = $fileOriginalName;
      // }
      // if ($request->file('income')) {
      //   $file               = $request->file('income');
      //   $fileOriginalName   = $file->getClientOriginalName();
      //   $extension          = $file->getClientOriginalExtension();
      //   $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
      //   $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
      //   $cust_cnic_detail->income          = '/storage/uploads/'.$fileNameToStore;
      
      //   // $cust_cnic_detail->original_name = $fileOriginalName;
      // }
      $cust_cnic_detail->save();

      // /**Customer Bank Details **/

      $cust_bank_detail = new CustBankDetail;
      $cust_bank_detail->user_id= $user->id;
      $cust_bank_detail->iban = $profile['IBAN'];
      $amc_bank_name=AmcBank::where('ypay_bank_id',$profile['Bank'])->pluck('amc_bank_name')->first();
      $cust_bank_detail->bank = $amc_bank_name??null;
      $cust_bank_detail->bank_account_number = $profile['Bank Account Number'];
      $cust_bank_detail->branch = $profile['Branch'];
      $cust_bank_detail->save();
      $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $string = substr(str_shuffle($chars), 0, 8);
      $cus_account_details          = new CustAccountDetail();
      $cus_account_details->user_id = $user->id;
      $cus_account_details->status  = 0;
      $cus_account_details->refer   = $string;
      $cus_account_details->save();
      $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_mail.php';
      $body = [
              'email'             => $user->email, 
              'name'              => $user->full_name,
              'father_name'       => $user->cust_basic_detail->father_name, 
              'iban'              => $user->cust_bank_detail->iban, 
              'cnic_issue_date'   => $user->cust_cnic_detail->issue_date??'',
              'cnic_expiry_date'  => $user->cust_cnic_detail->expiry_date??'',
              'address'           => $user->cust_basic_detail->current_address
          ];
      
      sendEmail($body,$url);

      $data = ['message' => 'Make sure to verify your profile to unlock funds and start investing!', 'image' => ''];
      sendNotification($user->fcm_token, $data, $user->id, 'profile_pending');
      $user            = User::whereId($user->id)->first();
      $token           = $user->createToken('mobile-app', ['all'])->plainTextToken;
      $user->fcm_token = $request->fcm_token??"";
      $user->save();
      }
      else
      {
        if($user1!=null)
        $already_registered_users[]=$line_number;
        else if($user2!=null)
        $already_registered_users[]=$line_number;
      }
      $line_number++;
      }
      return response()->json(['success'=> true, 'message' => 'Customer Profiles Added successfully!','already_registered_users'=> $already_registered_users]);
    }
    public function custadd(Request $request)
    {
      $validator = Validator::make($request->all(), [
              'full_name'         => 'required',
              'email'             => 'required|unique:users,email',
              'pin'             =>   'required',
              'father_name'       => 'required',
              'gender'            => 'required',
              'cnic_front'        => 'required',
              'cnic_back'        =>  'required',
              'phone_no'          => 'required|unique:users,phone_no',
              'current_address'   => 'required',
              'city'              => 'required',
              'country_of_residence' => 'required',
              'citizenship_status' => 'required',
              'passport_number' => 'required',
              'taxpayer_identification_number' => 'required',
              'bank_new'          => 'required',
              'bank_account_number'=> 'required',
              'income_source'     => 'required',
              'occupation'        => 'required',
              // 'state'             => 'required',
              'cnic_number'       => 'required',
              'iban'              => 'required',
              'branch'            => 'required'
          ]);
      if ($validator->fails()) {
        return redirect()->back()->withInput()->with('errors', $validator->errors());
      }
      $user = new User;
      $user->full_name        = $request->full_name;
      $user->secret_pin       = Hash::make($request->pin);
      $user->email            = $request->email;
      $user->phone_no         = $request->phone_no;
      $user->refer_code       = $request->refer_code;
      $user->type       = 2;
      $user->save();
      $data = ['message' => 'Thanks for signing up at YPay, you are one step closer to financial freedom now! YPay!', 'image' => ''];
      sendNotification($request->fcm_token, $data, $user->id, 'welcome');
      $url = 'https://networks.ypayfinancial.com/api/mailv1/signup_mail.php';
      $body = ['email' => $user->email, 'name'=> $user->full_name];
      sendEmail($body,$url);
      // // /**Customer Basic Details **/
      $cust_basic_detail                   = new CustBasicDetail;
      $cust_basic_detail->user_id          = $user->id;
      $cust_basic_detail->father_name      = $request->father_name;
      $cust_basic_detail->mother_name      = $request->mother_name;
      $cust_basic_detail->dob              = $request->dob;
      $cust_basic_detail->current_address  = $request->current_address;
      $cust_basic_detail->gender           = $request->gender;
      $cust_basic_detail->nationality      = "Pakistani";
      $cust_basic_detail->nominee_name     = $request->nominee_name;
      $cust_basic_detail->nominee_cnic     = $request->nominee_cnic;
      $cust_basic_detail->source_of_income = $request->source_of_income;
      $amc_city_id=AmcCity::where('ypay_city_id',$request->city)->pluck('amc_city_code')->first();
      $cust_basic_detail->amc_city_id      = $amc_city_id??null;
      $amc_country_id=AmcCountry::where('ypay_country_id','1')->pluck('amc_country_id')->first();
      $cust_basic_detail->amc_country_id   = $amc_country_id??null;
      $amc_bank_id=AmcBank::where('ypay_bank_id',$request->bank_new)->pluck('amc_bank_id')->first();
      $cust_basic_detail->amc_bank_id   = $amc_bank_id??null;
      $amc_occupation_id=AmcOccupation::where('ypay_occupation_id',$request->occupation)->pluck('amc_occupation_id')->first();
      $cust_basic_detail->amc_occupation_id   = $amc_occupation_id??null;
      $amc_income_source_id=AmcSourceofIncome::where('ypay_source_of_income_id',$request->income_source)->pluck('amc_source_of_income_id')->first();
      $cust_basic_detail->amc_income_source_id   = $amc_income_source_id??null;
      $cust_basic_detail->city             = $request->city;
      $cust_basic_detail->country          = $request->country;
      $cust_basic_detail->occupation       = $request->occupation;
      $cust_basic_detail->income_source    = $request->income_source;
      $cust_basic_detail->bank             = $request->bank_new;
      $cust_basic_detail->state            = $request->state;
      $cust_basic_detail->zakat            = $request->zakat;
      $cust_basic_detail->save();

      // // /**Customer CNIC Details **/
      $cust_cnic_detail               = new CustCnicDetail;
      $cust_cnic_detail->user_id          = $user->id;
      $cust_cnic_detail->cnic_number  = $request->cnic_number;
      $cust_cnic_detail->issue_date   = $request->issue_date;
      $cust_cnic_detail->expiry_date  = $request->expiry_date;
      if ($request->file('cnic_front')) {
        $file               = $request->file('cnic_front');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "cnic/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $cust_cnic_detail->cnic_front          = $filename;
        // $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
        // $cust_cnic_detail->cnic_front          = '/storage/uploads/'.$fileNameToStore;
        // $cust_cnic_detail->original_name = $fileOriginalName;
      }
      
      if ($request->file('cnic_back')) {
        $file               = $request->file('cnic_back');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "cnic/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $cust_cnic_detail->cnic_back          = $filename;
        // $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
        // $cust_cnic_detail->cnic_back          = '/storage/uploads/'.$fileNameToStore;
        // $cust_cnic_detail->original_name = $fileOriginalName;
      }
      if ($request->file('income')) {
        $file               = $request->file('income');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "cnic/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $cust_cnic_detail->income          = $filename;
        // $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
        // $cust_cnic_detail->income          = '/storage/uploads/'.$fileNameToStore;
        // $cust_cnic_detail->original_name = $fileOriginalName;
      }
      $cust_cnic_detail->country_of_residence=$request->country_of_residence;
      $cust_cnic_detail->citizenship_status=$request->citizenship_status;
      $cust_cnic_detail->passport_number=$request->passport_number;
      $cust_cnic_detail->taxpayer_identification_number=$request->taxpayer_identification_number;
      $cust_cnic_detail->save();

      // /**Customer Bank Details **/

      $cust_bank_detail = new CustBankDetail;
      $cust_bank_detail->user_id          = $user->id;
      $cust_bank_detail->iban = $request->iban;
      $amc_bank_name=AmcBank::where('ypay_bank_id',$request->bank_new)->pluck('amc_bank_name')->first();
      $cust_bank_detail->bank = $amc_bank_name??null;
      $cust_bank_detail->bank_account_number = $request->bank_account_number;
      $cust_bank_detail->branch = $request->branch;
      $cust_bank_detail->save();
      $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $string = substr(str_shuffle($chars), 0, 8);
      $cus_account_details          = new CustAccountDetail();
      $cus_account_details->user_id = $user->id;
      $cus_account_details->status  = 0;
      $cus_account_details->refer   = $string;
      $cus_account_details->save();
      $url = 'https://networks.ypayfinancial.com/api/mailv1/profile_mail.php';
      $body = [
              'email'             => $user->email, 
              'name'              => $user->full_name,
              'father_name'       => $user->cust_basic_detail->father_name, 
              'iban'              => $user->cust_bank_detail->iban, 
              'cnic_issue_date'   => $user->cust_cnic_detail->issue_date??'',
              'cnic_expiry_date'  => $user->cust_cnic_detail->expiry_date??'',
              'address'           => $user->cust_basic_detail->current_address
          ];
      
      sendEmail($body,$url);

      $data = ['message' => 'Make sure to verify your profile to unlock funds and start investing!', 'image' => ''];
      sendNotification($user->fcm_token, $data, $user->id, 'profile_pending');
      $user            = User::whereId($user->id)->first();
      $token           = $user->createToken('mobile-app', ['all'])->plainTextToken;
      $user->fcm_token = $request->fcm_token??"";
      $user->save();
      return redirect()->back()->with('success', 'Customer Profile Added successfully!');
      // return response()->json(['success'=> true, 'message' => 'Customer Updated Successfully!']);
    }
     public function custUpdate(Request $request,  $id)
    {
    

      $validator = Validator::make($request->all(), [
              'full_name'         => 'required',
              'email'             => 'required|unique:users,email,'.$id,
              'father_name'       => 'required',
              'gender'            => 'required',
              'nationality'       => 'required',
              'phone_no'          => 'required',
              'current_address'   => 'required',
              'city'              => 'required',
              'country_of_residence' => 'required',
              'citizenship_status' => 'required',
              'passport_number' => 'required',
              'taxpayer_identification_number' => 'required',
              // 'country'           => 'required',
              'bank_new'          => 'required',
              'bank_account_number'=> 'required',
              'income_source'     => 'required',
              'occupation'        => 'required',
              // 'state'             => 'required',
              'cnic_number'       => 'required',
              'iban'              => 'required',
              'branch'            => 'required'
          ]);

          // dd($request->all());

          if ($validator->fails()) {
            return redirect()->back()->withInput()->with('errors', $validator->errors());
          }
      
      $user = User::where('id',$id)->first();
      $user->full_name        = $request->full_name;
      $user->email            = $request->email;
      $user->phone_no         = $request->phone_no;
      $user->refer_code       = $request->refer_code;
      $user->save();

      // // /**Customer Basic Details **/
      $cust_basic_detail                   = CustBasicDetail::where('user_id',$user->id)->first();
      $cust_basic_detail->father_name      = $request->father_name;
      $cust_basic_detail->mother_name      = $request->mother_name;
      $cust_basic_detail->dob              = $request->dob;
      $cust_basic_detail->current_address  = $request->current_address;
      $cust_basic_detail->gender           = $request->gender;
      $cust_basic_detail->nationality      = $request->nationality;
      $cust_basic_detail->nominee_name     = $request->nominee_name;
      $cust_basic_detail->nominee_cnic     = $request->nominee_cnic;
      $cust_basic_detail->source_of_income = $request->source_of_income;
      $amc_city_id=AmcCity::where('ypay_city_id',$request->city)->pluck('amc_city_code')->first();
      $cust_basic_detail->amc_city_id      = $amc_city_id??null;
      $amc_country_id=AmcCountry::where('ypay_country_id',$request->country)->pluck('amc_country_id')->first();
      $cust_basic_detail->amc_country_id   = $amc_country_id??null;
      $amc_bank_id=AmcBank::where('ypay_bank_id',$request->bank_new)->pluck('amc_bank_id')->first();
      $cust_basic_detail->amc_bank_id   = $amc_bank_id??null;
      $amc_occupation_id=AmcOccupation::where('ypay_occupation_id',$request->occupation)->pluck('amc_occupation_id')->first();
      $cust_basic_detail->amc_occupation_id   = $amc_occupation_id??null;
      $amc_income_source_id=AmcSourceofIncome::where('ypay_source_of_income_id',$request->income_source)->pluck('amc_source_of_income_id')->first();
      $cust_basic_detail->amc_income_source_id   = $amc_income_source_id??null;
      $cust_basic_detail->city             = $request->city;
      $cust_basic_detail->country          = $request->country;
      $cust_basic_detail->occupation       = $request->occupation;
      $cust_basic_detail->income_source    = $request->income_source;
      $cust_basic_detail->bank             = $request->bank_new;
      $cust_basic_detail->state            = $request->state;
      $cust_basic_detail->zakat            = $request->zakat;
      $cust_basic_detail->save();

      // // /**Customer CNIC Details **/
      $cust_cnic_detail               = CustCnicDetail::where('user_id',$user->id)->first();
      $cust_cnic_detail->cnic_number  = $request->cnic_number;
      $cust_cnic_detail->issue_date   = $request->issue_date;
      $cust_cnic_detail->expiry_date  = $request->expiry_date;

      
      if ($request->file('cnic_front')) {
        $file               = $request->file('cnic_front');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "cnic/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $cust_cnic_detail->cnic_front          = $filename;
        // $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
        // $cust_cnic_detail->cnic_front          = '/storage/uploads/'.$fileNameToStore;
        // $cust_cnic_detail->original_name = $fileOriginalName;
      }
      
      if ($request->file('cnic_back')) {
        $file               = $request->file('cnic_back');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "cnic/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $cust_cnic_detail->cnic_back          = $filename;
        // $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
        // $cust_cnic_detail->cnic_back          = '/storage/uploads/'.$fileNameToStore;
        // $cust_cnic_detail->original_name = $fileOriginalName;
      }
      if ($request->file('income')) {
        $file               = $request->file('income');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "cnic/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $cust_cnic_detail->income          = $filename;
        // $filename           = $file->storeAs('/public/uploads/', $fileNameToStore);
        // $cust_cnic_detail->income          = '/storage/uploads/'.$fileNameToStore;
        // $cust_cnic_detail->original_name = $fileOriginalName;
      }
      $cust_cnic_detail->country_of_residence=$request->country_of_residence;
      $cust_cnic_detail->citizenship_status=$request->citizenship_status;
      $cust_cnic_detail->passport_number=$request->passport_number;
      $cust_cnic_detail->taxpayer_identification_number=$request->taxpayer_identification_number;
      $cust_cnic_detail->save();

      // /**Customer Bank Details **/

      $cust_bank_detail = CustBankDetail::where('user_id',$user->id)->first();
      $cust_bank_detail->iban = $request->iban;
      $cust_bank_detail->bank = $request->bank;
      $cust_bank_detail->bank_account_number = $request->bank_account_number;
      $cust_bank_detail->branch = $request->branch;

      if(isset($request->comments))
      {
        $comments=$request->comments;
        $comment_by=Auth::user();
        foreach($comments as $comment)
        {
          if($comment!='')
          {
          $admin_comment=new AdminComments;
          $admin_comment->comment=$comment;
          $admin_comment->user_id=$user->id;
          $admin_comment->comment_by=$comment_by->id;
          $admin_comment->save();
          }
        }
      }
      $cust_bank_detail->save();
      return redirect()->back()->with('success', 'Customer Updated successfully!');
      // return response()->json(['success'=> true, 'message' => 'Customer Updated Successfully!']);
    }
    


    public function cities(Request $request)
    {

      // dd($request->state); 

      $cities = City::select('id','city')->where('state',$request->state)->get();

      return $cities;

    }

    public function autocomplete(Request $request)
    {
        try {
          $data = [];
          $queryTerm = $request->q;
          $customers = User::with('cust_cnic_detail')->whereHas('cust_account_detail', function ($q){ 
            $q->whereIn('status',[1]);
          })->where('type',2)->where('full_name', 'like', '%' . $queryTerm . '%')->get();
          foreach ($customers as $customer) {
              $data[] = ['id' => $customer->id, 'text' => $customer->full_name.'-'.$customer->cust_cnic_detail?->cnic_number];
          }
          return $data;
      } catch (\Exception $e) {
          return ['error' => 'Something went wrong'];
      }
    }
    


    public function filter($request, $users)
    {
        try {

            if (isset($request->customerName)) {
              $users = $users->where('full_name', 'like', '%' . $request->customerName . '%');
            }

            if (isset($request->status)) {
                $users = $users->where('status', $request->status);
            }

            if (isset($request->cnic)) {
              $cnic = $request->cnic;
              $users = $users->whereHas('cust_cnic_detail', function ($q) use ($cnic){
                $q->where('cnic_number', 'like', '%' . $cnic . '%');
              });
             }

            if (isset($request->email)) {
              $users = $users->where('email', 'like', '%' . $request->email . '%');
             }

            if (isset($request->contact)) {
            $users = $users->where('phone_no', 'like', '%' . $request->contact . '%');
           }
            return $users;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    

    // public function getCount()
    // {
    //   // return User::count();
    // }

}
