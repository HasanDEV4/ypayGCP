<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CustCnicDetail;
use App\Models\AmcCustProfile;
use PDF;
use Yajra\DataTables\DataTables;
use App\Models\Investment;
use App\Models\Redemption;
use App\Models\Conversion;
use function App\Libraries\Helpers\userInvestmentSum;
use function App\Libraries\Helpers\userUnitSum;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
      $users=User::with('cust_cnic_detail')->whereHas('cust_account_detail', function ($q){ 
        $q->whereIn('status',[0,1]);
      })->where('type',2)->get();
        return view('report.index',compact('users'));
    }
    public function generatePDF(Request $request)
    {
        $transaction_type = $request->transaction_type;
        $user_id = $request->cnic;
        $from = $request->from?? null;
        $to = $request->to?? null;
        
        $investment_data=Investment::with('user.cust_cnic_detail','fund')->where('status', 1)->limit(20);
        $investment_data=$this->filter($request, $investment_data, 'investments')->get();
        if (isset($investment_data)) {
          // $user_name = $investment_data[0]->user->full_name;
          $total_invest_amount = $investment_data->sum('amount');
          $total_unit_sum = $investment_data->sum('unit');
        } else {
          $total_invest_amount = 0;
          $total_unit_sum = 0;
        }
        // $total_nav_sum = $investment_data->sum('nav');
        // $total_new_amount = $total_unit_sum * $total_nav_sum;

        $conversion_data=Conversion::with('fund', 'investment.fund', 'parent.fund')->orderBy('id','desc')->where('status', 1)->limit(20);
        $conversion_data=$this->filter($request, $conversion_data, 'investments')->get();
        if (isset($conversion_data)) {
          // $user_name = $investment_data[0]->user->full_name;
          $total_conversion_amount = $conversion_data->sum('amount');
          $total_conversion_unit_sum = $conversion_data->sum('unit');
        } else {
          $total_conversion_amount = 0;
          $total_conversion_unit_sum = 0;
        }
        
        $redemption_data=Redemption::with('investment.user','investment.user.cust_cnic_detail','investment.fund','conversion.user','conversion.user.cust_cnic_detail','conversion.fund')->where('status', 1)->limit(20);
          $redemption_data=$this->filter($request, $redemption_data, 'redemptions')->get();
          // dd($redemption_data);
          if (isset($redemption_data)) {
          $total_redeem_amount = $redemption_data->sum('redeem_amount');
          $total_redeem_unit_sum = $redemption_data->sum('investment.unit');
        } else {
          $total_redeem_amount = 0;
          $total_redeem_unit_sum = 0;
        }
        $current_date = date('Ymd');
        $user_details = User::with('cust_basic_detail','cust_cnic_detail')->where('id', '=', $user_id)->first();
        // $cust_cnic_detail=CustCnicDetail::where('cnic_number',$cnic)->first();
        if (isset($user_details)) {
          $current_balance = userInvestmentSum($user_id);
          $total_available_units = userUnitSum($user_id);
          $cnic_number = $user_details->cust_cnic_detail->cnic_number;
          // $user_name = $user_details->full_name;
          $pdf = PDF::loadView('myPDF', compact('investment_data', 'redemption_data',  'conversion_data', 'transaction_type', 'total_invest_amount', 'current_balance', 'cnic_number', 'total_redeem_amount', 'total_unit_sum', 'total_conversion_amount', 'total_conversion_unit_sum', 'total_available_units', 'total_redeem_unit_sum', 'user_details', 'from', 'to'));
          return $pdf->download(str_replace('-', '', $cnic_number).'_'.$current_date.'.pdf');
        } else {
          return redirect()->back()->with('error', 'No Data Found');
        }

        // dd($current_balance);
        // if($transaction_type=="investments") {
        //   $data=Investment::with('user.cust_cnic_detail','fund');
        // }
        //   else {
        //   $data=Redemption::with('investment.user','investment.user.cust_cnic_detail','investment.fund');
        // }
          
          
        //   $data=$this->filter($request, $data)->get();
        //   $user_name = $data[0]->user->full_name;
        //   dd($user_name);
           
        // dd($data);
        // foreach($data as $investment)
        // {
        
        // }
        // dd($data);
        // return view("myPDF", compact('investment_data', 'redemption_data',  'transaction_type', 'total_invest_amount', 'user_name'));
    }
    public function show(Request $request)
    {
        if($request->transaction_type=="investments")
        $data=Investment::with('user','user.cust_cnic_detail','fund');
        else
          $data=Redemption::with('investment.user','investment.user.cust_cnic_detail','investment.fund');
        return DataTables::of($this->filter($request, $data, $request->transaction_type))->make(true);
    }
    public function filter(Request $request,$transaction_data, $transaction_type)
    {
        $data=$request->all();
      // try{
        if(isset($data["cnic"]) && $data["cnic"] != "null")  
        {
            $user_id=$data["cnic"];
            if($transaction_type=="investments") {
              if(isset($user_id))
              $transaction_data = $transaction_data->where('user_id',$user_id);
              // $transaction_data = $transaction_data->where('status', '1');
            } else {
              $transaction_data = $transaction_data->whereHas('investment.user', function($q) use($user_id) {
                $q->where('id', $user_id);
              })->orWhereHas('conversion.user', function($q) use($user_id) {
                $q->where('id', $user_id);
              });;
              // $transaction_data = $transaction_data->whereIn('status', ['0','1']);
            }
        }
        if (isset($data["from"]) && isset($data["to"])) {
          $transaction_data = $transaction_data->whereDate('created_at', '>=', $data["from"])
          ->whereDate('created_at', '<=', $data["to"]);
        }
        // $transaction_data = $transaction_data->where('status', '1');

        return $transaction_data;
      // } catch (\Exception $e) {
      //   echo "<pre>";
      //   print_r($e);
      //   echo "</pre>";
      //    return ['error' => 'Something went wrong'];
      //  }
    }
}