<?php

namespace App\Http\Controllers\API\version_4;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\CustCnicDetail;
use App\Models\Fund;
use App\Models\AmcCustProfile;
use App\Models\Investment;
use App\Models\Conversion;
use App\Models\Dividend;
use App\Models\Redemption;
use function App\Libraries\Helpers\cal_percentage;
use function App\Libraries\Helpers\userInvestmentSum;
use function App\Libraries\Helpers\userUnitSum;
use App\Models\User;


class UnitStatementController4 extends Controller
{
  public function get_portfolio_data(Request $request)
  {
      $validator = Validator::make($request->all(), [
        'type' => 'required',
      ]);

      if ($validator->fails()) {
          return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
      }
      $user_id = $request->user()->id;
      $current_balance = userInvestmentSum($user_id);
      $total_available_units = userUnitSum($user_id);
      $my_investment_queryset = Investment::where('user_id', $user_id)->where('status', 1)->with('fund.amc', 'redemption','fund.additional_details','conversions')->get();
      $my_conversion_queryset = Conversion::where('user_id', $user_id)->where('status', 1)->with('fund.amc', 'redemption','fund.additional_details','children')->get();
      $my_dividend_queryset = Dividend::where('user_id', $user_id)->where('status', 1)->with('fund.amc', 'redemption','fund.additional_details')->get();
      $my_investment = $my_investment_queryset->toArray();
      $my_conversion = $my_conversion_queryset->toArray();
      $my_dividend = $my_dividend_queryset->toArray();
      foreach ($my_investment as $key => $value) {
        if ($value['redemption']) {
          foreach ($value['redemption'] as $key2 => $value2) {
              if($value2['status'] == 1){
                  unset($my_investment[$key]);
                  continue;
              }
          }
        } else if ($value['conversions']) {
          foreach ($value['conversions'] as $key3 => $value3) {
              if($value3['status'] == 1){
                  unset($my_investment[$key]);
                  continue;
              }
          }
        }
      }

      foreach ($my_conversion as $key => $value) {
        if ($value['redemption']) {
          foreach ($value['redemption'] as $key2 => $value2) {
              if($value2['status'] == 1){
                  unset($my_conversion[$key]);
                  continue;
              }
          }
        } else if ($value['children']) {
          foreach ($value['children'] as $key3 => $value3) {
              if($value3['status'] == 1){
                  unset($my_conversion[$key]);
                  continue;
              }
          }
        }
      }

      foreach ($my_dividend as $key => $value) {
        if ($value['redemption']) {
          foreach ($value['redemption'] as $key2 => $value2) {
              if($value2['status'] == 1){
                  unset($my_dividend[$key]);
                  continue;
              }
          }
        } 
        // else if ($value['children']) {
        //   foreach ($value['children'] as $key3 => $value3) {
        //       if($value3['status'] == 1){
        //           unset($my_conversion[$key]);
        //           continue;
        //       }
        //   }
        // }
      }
      $investment_and_conversion_data = array_merge($my_investment, $my_conversion, $my_dividend);
      array_multisort(array_column($investment_and_conversion_data, 'created_at'), SORT_DESC, $investment_and_conversion_data);
      $required_data=[];
      $ios_required_data=[];
      $current_balance = (float)filter_var($current_balance, FILTER_SANITIZE_NUMBER_INT);
      foreach($investment_and_conversion_data as $investment)
      {
        if($investment['status'] == 1 && ($investment['nav'] && $investment['unit'] && $investment['fund']['nav'])){
            $amount=round(((float) $investment['unit'] * (float) $investment['fund']['nav']), 2);
        }
        else if($investment['status'] == 1){
          $amount=$investment['amount'];
        }
        else{
          $amount=0;
        }
        if($request->type=="1")
        {
          if(isset($required_data[$investment['fund']['amc']['entity_name']]))
          {
            $percentage=cal_percentage($amount,$current_balance);
            $required_data[$investment['fund']['amc']['entity_name']]['percentage']+=$percentage;
          }
          else
          {
            $percentage=cal_percentage($amount,$current_balance);
            $required_data[$investment['fund']['amc']['entity_name']]['percentage']=$percentage;
          }
        }
        elseif($request->type=="2")
        {
          if(isset($required_data[$investment['fund']['fund_name']]))
          {
            $required_data[$investment['fund']['fund_name']]['amount']+=$amount;
            $percentage=cal_percentage($amount,$current_balance);
            $required_data[$investment['fund']['fund_name']]['percentage']+=$percentage;
          }
          else
          {
            $percentage=cal_percentage($amount,$current_balance);
            $required_data[$investment['fund']['fund_name']]['amount']=$amount;
            $required_data[$investment['fund']['fund_name']]['percentage']=$percentage;
            $required_data[$investment['fund']['fund_name']]['nav']=$investment['fund']['nav'];
          }
        }
        else
        {
          if(isset($required_data[$investment['fund']['additional_details']['profile_risk']]))
          {
            $percentage=cal_percentage($amount,$current_balance);
            $required_data[$investment['fund']['additional_details']['profile_risk']]['percentage']+=$percentage;
          }
          else
          {
            $percentage=cal_percentage($amount,$current_balance);
            $required_data[$investment['fund']['additional_details']['profile_risk']]['percentage']=$percentage;
          }
        }
      }
      foreach($required_data as $key => $data) {
        if ($request->type == 1) {
          array_push($ios_required_data, [
            "name" => $key,
            "percentage" => $data['percentage']
          ]);
        } elseif ($request->type == "2") {
          array_push($ios_required_data, [
            "name" => $key,
            "amount" => $data['amount'],
            "percentage" => $data['percentage'],
            "nav" => $data['nav'],
          ]);
        } else {
          array_push($ios_required_data, [
            "name" => $key,
            "percentage" => $data['percentage'],
          ]);
        }
      }
      //investment query was here
      $data = [];
      $data_1 = [];
      $data_2 = [];
      $data_3 = [];
      foreach ($my_investment as $key => $value) {
          if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
          $total_return   = "+".number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
          else
          $total_return   = number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
          $count=0;
          if(count($value['redemption'])!="0")
          {
            foreach ($value['redemption'] as $key2 => $value2) {
              if($value2['status']==1 || $value2['status']==0)
              {
                $count++;
              }
            }
          }
          else if(count($value['conversions'])!="0")
          {
            foreach ($value['conversions'] as $key2 => $value2) {
              if($value2['status']==1 || $value2['status']==0)
              {
                $count++;
              }
            }
          }
          if($count==0)
          {
          array_push($data_1, [
                  'id' => $value['id'],
                  'amount' => number_format($value['amount']),
                  'status' => $value['status'],
                  'transaction_id' => $value['transaction_id'],
                  'logo_link' => $value['fund']['amc']['logo_link'],
                  'fund_name' => $value['fund']['fund_name'],
                  'fund_image' => $value['fund']['fund_image'],
                  'fund_id' => $value['fund']['id'],
                  'return_amount' => number_format($value['unit']*$value['fund']['nav']),
                  'total_return' => $total_return,
                  'investment_date' => date('Y-m-d',strtotime($value['created_at'])),
                  'type' => 'investment'

              ]
          );
          }
      }
      foreach (array_values($my_conversion) as $key => $value) {
          if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
          $total_return   = "+".number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
          else
          $total_return   = number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
          $count=0;
          if(count($value['redemption'])!="0")
          {
            foreach ($value['redemption'] as $key2 => $value2) {
              if($value2['status']==1 || $value2['status']==0)
              {
                $count++;
              }
            }
          }
          else if(count($value['children'])!="0")
          {
            foreach ($value['children'] as $key2 => $value2) {
              if($value2['status']==1 || $value2['status']==0)
              {
                $count++;
              }
            }
          }
          if($count==0)
          {
            array_push($data_2, [
                    'id' => $value['id'],
                    'amount' => number_format($value['amount']),
                    'status' => $value['status'],
                    'transaction_id' => $value['transaction_id'],
                    'logo_link' => $value['fund']['amc']['logo_link'],
                    'fund_name' => $value['fund']['fund_name'],
                    'fund_image' => $value['fund']['fund_image'],
                    'fund_id' => $value['fund']['id'],
                    'return_amount' => number_format($value['unit']*$value['fund']['nav']),
                    'total_return' => $total_return,
                    'investment_date' => date('Y-m-d',strtotime($value['created_at'])),
                    'type' => 'conversion'

                ]
            );
          } 
      }
      foreach ($my_dividend as $key => $value) {
        if(($value['unit']*$value['fund']['nav'])-$value['amount']>=0)
        $total_return   = "+".number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
        else
        $total_return   = number_format(($value['unit']*$value['fund']['nav'])-$value['amount']);
        $count=0;
        if(count($value['redemption'])!="0")
        {
          foreach ($value['redemption'] as $key2 => $value2) {
            if($value2['status']==1 || $value2['status']==0)
            {
              $count++;
            }
          }
        }
        // else if(count($value['conversions'])!="0")
        // {
        //   foreach ($value['conversions'] as $key2 => $value2) {
        //     if($value2['status']==1 || $value2['status']==0)
        //     {
        //       $count++;
        //     }
        //   }
        // }
        if($count==0)
        {
        array_push($data_3, [
                'id' => $value['id'],
                'amount' => number_format($value['amount']),
                'status' => $value['status'],
                'transaction_id' => $value['transaction_id'],
                'logo_link' => $value['fund']['amc']['logo_link'],
                'fund_name' => $value['fund']['fund_name'],
                'fund_image' => $value['fund']['fund_image'],
                'fund_id' => $value['fund']['id'],
                'return_amount' => number_format($value['unit']*$value['fund']['nav']),
                'total_return' => $total_return,
                'investment_date' => date('Y-m-d',strtotime($value['created_at'])),
                'type' => 'dividend'

            ]
        );
        }
    }
    $data = array_merge($data_1, $data_2, $data_3);
    array_multisort(array_column($data, 'investment_date'), SORT_DESC, $data);
      return response()->json([
          'status' => true,
          'data' => $data,
          'portfolio_amount'=>$current_balance,
          'total_available_units'=>$total_available_units,
          'required_data'=>$required_data,
          'ios_required_data'=>$ios_required_data,
      ], 200);
  }
	public function getUnitStatement(Request $request)
    {
        //$transaction_type = $request->transaction_type;
        $user_id = $request->user()->id;
        $from = $request->from?? null;
        $to = $request->to?? null;
        
        $investment_data=Investment::with('fund')->where('status','1')->orderBy('id','desc')->limit(20);
        $investment_data=$this->filter($request, $investment_data, 'investments')->get();
        if (isset($investment_data)) {
          // $user_name = $investment_data[0]->user->full_name;
          $total_invest_amount = $investment_data->sum('amount');
          $total_unit_sum = $investment_data->sum('unit');
        } else {
          $total_invest_amount = 0;
          $total_unit_sum = 0;
        }

        $conversion_data=Conversion::with('fund', 'investment.fund', 'parent.fund')->where('status','1')->orderBy('id','desc')->limit(20);
        $conversion_data=$this->filter($request, $conversion_data, 'investments')->get();
        if (isset($conversion_data)) {
          // $user_name = $investment_data[0]->user->full_name;
          $total_conversion_amount = $conversion_data->sum('amount');
          $total_conversion_unit_sum = $conversion_data->sum('unit');
        } else {
          $total_conversion_amount = 0;
          $total_conversion_unit_sum = 0;
        }

        $dividend_data=Dividend::with('fund')->where('status','1')->orderBy('id','desc')->limit(20);
        $dividend_data=$this->filter($request, $dividend_data, 'investments')->get();
        if (isset($dividend_data)) {
          // $user_name = $investment_data[0]->user->full_name;
          $total_dividend_amount = $dividend_data->sum('amount');
          $total_dividend_unit_sum = $dividend_data->sum('unit');
        } else {
          $total_dividend_amount = 0;
          $total_dividend_unit_sum = 0;
        }

        $redemption_data=Redemption::with('investment.fund','conversion.fund')->where('status','1')->orderBy('id','desc')->limit(20);
        $redemption_data=$this->filter($request, $redemption_data, 'redemptions')->get();
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
        
          $data['total_invest_amount'] = number_format($total_invest_amount);
          $data['portfolio_amount'] = $current_balance;
          $data['cnic_number'] = $cnic_number;
          $data['total_redeem_amount'] = number_format($total_redeem_amount);
          $data['total_unit_sum'] = $total_unit_sum;
          $data['total_conversion_amount'] = number_format($total_conversion_amount);
          $data['total_conversion_unit_sum'] = $total_conversion_unit_sum;
          $data['total_dividend_amount'] = number_format($total_dividend_amount);
          $data['total_dividend_unit_sum'] = $total_dividend_unit_sum;
          $data['total_available_units'] = $total_available_units;
          $data['total_unit_sum'] = $total_unit_sum;
          $data['total_available_units'] = $total_available_units;
          $data['total_redeem_unit_sum'] = $total_redeem_unit_sum;

          $data['user_details'] = $user_details;
          foreach($investment_data as $investment)
          {
            $investment->amount=number_format($investment->amount);
          }
          foreach($redemption_data as $redemption)
          {
            $redemption->amount=number_format($redemption->amount);
          }
          foreach($conversion_data as $conversion)
          {
            $conversion->amount=number_format($conversion->amount);
          }
          foreach($dividend_data as $dividend)
          {
            $dividend->amount=number_format($dividend->amount);
          }
          $data['investment_data'] = $investment_data;
          $data['redemption_data'] = $redemption_data;
          $data['conversion_data'] = $conversion_data;
          $data['dividend'] = $dividend_data;
          return response()->json(['status' => true, 'data' => $data], 200);
          
        } else {
          $data["error"] = "No Data Found";
          return response()->json(['status' => true, 'data' => $data], 200);
          // return redirect()->back()->with('error', 'No Data Found');
        }

    }
   
    public function filter(Request $request,$transaction_data, $transaction_type)
    {
        $data=$request->all();
        $user_id = $request->user()->id;
      // try{
        if (isset($data["from"]) && isset($data["to"])) {
          $transaction_data = $transaction_data->where('created_at', '<=', date("Y-m-d", strtotime($data["to"])))->where('created_at', '>=', date("Y-m-d", strtotime($data["from"])));
        }
        if(isset($user_id) && $user_id != "null")  
        {
            if($transaction_type=="investments") {
              $transaction_data = $transaction_data->where('user_id',$user_id);
            } 
            else {
              // $transaction_data = $transaction_data->whereHas('investment.user', function($q) use($user_id) {
              //   $q->where('id', $user_id);
              // })->orwhereHas('conversion.user', function($q) use($user_id) {
              //   $q->where('id', $user_id);
              // });
              $transaction_data = $transaction_data->where(function($builder) use ($user_id) {
                $builder->whereHas('investment.user', function ($query) use ($user_id) {
                   $query->where('id', $user_id);
                })
                ->orWhereHas('conversion.user', function($query) use ($user_id) {
                   $query->where('id', $user_id);
                })
                ->orWhereHas('dividend.user', function($query) use ($user_id) {
                  $query->where('id', $user_id);
               }); 
            });
            }
        }
       

        return $transaction_data;
    }
}