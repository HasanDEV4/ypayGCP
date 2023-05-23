<?php

namespace App\Http\Controllers;
use App\Models\Conversion;
use App\Models\AmcCustProfile;
use App\Models\User;
use App\Models\Fund;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Config;
use PDF;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
class ConversionController extends Controller
{

  // function __construct()
  //   {
  //        $this->middleware('permission:conversion-list');
  //        $this->middleware('permission:conversion-edit', ['only' => ['update']]);
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
        return view('conversions.index',compact('users'));
    }


    public function verifyconversion(Request $request)
    {
      if(isset($request['conversion_id']) && isset($request['verified']))
      {
        Conversion::where('id',$request['conversion_id'])->update(['verified'=>$request['verified']]);
        return response(["status"=>200]);
      }
      else
        return response(["error"=>"Error Occured"]);
    }
    public function show(Request $request)
    {
      $conversions = Conversion::with('user.cust_cnic_detail','user.change_request','user.amcCustProfiles','investment.fund','fund', 'parent.fund');
      return DataTables::of($this->filter($request, $conversions))->make(true);
    }

    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'user_id'        => 'required',
        'fund_id'        => 'required',
      ]);
      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }
      $conversion                 = new Conversion;
      $conversion->save();

      return response()->json(['success'=> true, 'message' => 'Conversion Created successfully!']);
    }


    public function update(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'conversion_id'   => 'required',
        'status'          => 'required',
        'approved_date'   => 'required_if:status,1',
        'to_fund_units'   => 'required_if:status,1',
        'to_fund_nav'     => 'required_if:status,1',
        'rejected_reason'     => 'required_if:status,2',
      ],
      [
        'rejected_reason.required_if'   => 'This field is required',
        'to_fund_nav.required_if'   => 'This field is required',
        'to_fund_units.required_if' => 'This field is required',
        'approved_date.required_if' => 'This field is required',
      ]);
      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }
      $conversion_id=$request->conversion_id;
      $conversion = Conversion::where('id',$conversion_id)->first();
      $user = $conversion->user;
      $conversion->status         = $request->status ?? 0;
      $conversion->nav            = $request->to_fund_nav;
      $conversion->rejected_reason= $request->rejected_reason;
      $conversion->unit           = $request->to_fund_units;
      $conversion->approved_date  = $request->approved_date??Carbon::now();
      $conversion->is_latest=1;
      $conversion->save();
      Conversion::where('id','!=',$conversion->id)->where('investment_id',$conversion->investment_id)->update(['is_latest'=> 0]);
    
      if($conversion->status == 1) {
         $url = 'https://networks.ypayfinancial.com/api/mailv1/approve_conversion.php';
         $body = ['email' => $user->email, 'name'=>$user->full_name];
         sendEmail($body,$url);

        $data = ['message' => Config::get('messages.conversion_request_approved'), 'image' => $conversion->fund['fund_image']];
        sendNotification($user->fcm_token, $data, $user->id, 'Hooray! 🎉 Your conversion request has been given the green light 🚦');
      }
      else if($conversion->status == 2){
        $url = 'https://networks.ypayfinancial.com/api/mailv1/reject_conversion.php';
        $body = ['email' => $user->email, 'name'=>$user->full_name];
        sendEmail($body,$url);

        $data = ['message' => Config::get('messages.conversion_request_denied'), 'image' => $conversion->fund['fund_image']];
        sendNotification($user->fcm_token, $data, $user->id, 'Uh-Oh, we have hit a little hiccup 🙃');
      }

      return response()->json(['success'=> true, 'message' => 'Conversion Updated successfully!']);
    }
    
    public function export_selected(Request $request)
    {
      $current_date = date('Ymd');
      $conversions=[];
      $selected_conversions=$request['selected_conversions'];
      if(isset($selected_conversions))
      {
        foreach($selected_conversions as $conversion)
        {
          $conversion = Conversion::with('user.cust_cnic_detail','investment.fund','fund.additional_details', 'parent.fund')->where('id',$conversion)->first();
          $filtered_conversions[]=$conversion;
        }
      }
      foreach($filtered_conversions as $conversion)
      {
      $amc_profiles[]=AmcCustProfile::where('amc_id',$conversion->fund->amc_id)->where('user_id',$conversion->user_id)->pluck('account_number')->first();
      }
      $pdf = PDF::loadView('conversion_form_pdf', compact('filtered_conversions','amc_profiles'));
      $path = public_path();
      $fileName =  $current_date.'.pdf' ;
      $pdf->save($path . '/' . $fileName);
      $pdf = public_path($fileName);
      return response()->download($pdf);
    }
    public function filter($request, $conversions)
    {
      try {
        if (isset($request->from) && isset($request->to) && $request->from!= "null" && $request->to!= "null") {       
          $transaction_from=$request->from;
          $transaction_to=$request->to;
          $conversions = $conversions->whereBetween('created_at',[[date("Y-m-d H:i:s", strtotime($transaction_from)),date("Y-m-d H:i:s", strtotime($transaction_to))]]);
        }
        if (isset($request->customerName) && $request->customerName != "null") {
          $customer = $request->customerName;
          $conversions = $conversions->where('user_id',$customer);
        }
        if (isset($request->fund) && $request->fund != "null" && $request->folio_number == "null") {
          $fund = $request->fund;
          $conversions = $conversions->where('fund_id',$fund);
        }
        if (isset($request->folio_number) && $request->folio_number != "null") {
          
          $folio_number = $request->folio_number;
          if (isset($request->fund) && $request->fund != "null") {
          $fund=Fund::where('id',$request->fund)->first();
          $amc_profile=AmcCustProfile::where('account_number',$folio_number)->where('amc_id',$fund->amc_id)->first();
          $conversions =$conversions->where('user_id',$amc_profile->user_id)->where('fund_id',$request->fund);
          }
          else
          {
            $amc_profile=AmcCustProfile::where('account_number',$folio_number)->first();
            // $conversions = $conversions->whereHas('user.amcCustProfiles', function($q) use($folio_number) {
            //   $q->where('account_number', 'like', '%' .$folio_number. '%');
            // });
            $conversions =$conversions->where('user_id',$amc_profile->user_id)->whereHas('fund', function ($q)use($amc_profile){
              $q->where('amc_id',$amc_profile->amc_id);
            });
          }
        }
        return $conversions;
  
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
   
}
