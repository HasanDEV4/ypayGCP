<?php

namespace App\Http\Controllers;

use App\Models\Amc;
use App\Models\Fund;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
  public function index()
  {
    /* total count of amcs */
    $amc = Amc::count();

    /* total count of funds */
    $funds = Fund::count();

    /* total count of users */
    $users = User::where('admin','==', 0)->with('cust_account_detail')->whereHas('cust_account_detail', function ($q)  {
                $q->where('status', 1);
            })->count();

    /* total sum of amount in investment */
    $totalAmount = Investment::where('status', 1)->with('fund','redemption')->get();
    $totalAmount = $totalAmount->toArray();
     $sum = 0;
    foreach($totalAmount as $key => $value)
    {
      if($value['redemption'])
      {
        $sum += $value['amount'];
        foreach ($value['redemption'] as $key2 => $value2) {
          if($value2['status'] == 0 || $value2['status'] == 1){
            unset($totalAmount[$key]);         
          }
        }
      }
    }        
    
    $sum = array_sum(array_map(function($item) { 
      
      if($item['status'] == 1 && ($item['nav'] && $item['unit'] && $item['fund']['nav'])){
        // return round((@$item['amount']*$item['fund']['nav'])/$item['nav'], 2);
        // return ($item['unit']*$item['fund']['nav']);
        return round(((float) $item['unit'] * (float) $item['fund']['nav']), 2);
      }
      else if($item['status'] == 1){
        return @$item['amount'];
      }
      else{
        return 0;
      }
        
  }, $totalAmount));
    
    /* fetch latest 5 investments */
    $investments = Investment::where('status',1)->with('user','fund','redemption')->orderBy('id','desc')->get();
    $investment = $investments->toArray();
    foreach($investment as $key => $value){
      if($value['redemption']){
        foreach ($value['redemption'] as $key2 => $value2) {
          if($value2['status'] == 0 || $value2['status'] == 1){
            unset($investment[$key]);
          }
        }
      }
    }

    $data = collect($investment)->take(5);

    /** latest 5 amcs **/
    $amcs = Amc::take(5)->orderBy('id','desc')->get();

    /** latest 5 customers **/
    $customers = User::where('admin','==', 0)->with('cust_account_detail','cust_basic_detail','cust_cnic_detail')->whereHas('cust_account_detail', function ($q)  {
      $q->where('status', 1);
    })->take(5)->orderBy('id','desc')->get();

    /** top 5 funds with most investments **/
    
      $topFunds = Investment::selectRaw("funds.id,funds.fund_name,funds.fund_image,funds.created_at as created_at,amcs.entity_name as entity_name, COUNT('investments.*') as investment_count,funds_additional_details.status as status")
      ->join('funds', 'funds.id', '=', 'investments.fund_id')
      ->join('amcs', 'amcs.id', '=', 'funds.amc_id')
      ->join('funds_additional_details','funds_additional_details.fund_id','funds.id')
      ->groupBy('funds.id')
      ->orderBy('investment_count', 'desc')
      ->take(5)
      ->whereIn('investments.status',[0,1])
      ->get();

    // dd($topFunds);

    return view('dashboard')->with('data',$data)
                            ->with('amc',$amc)
                            ->with('funds',$funds)
                            ->with('users',$users)
                            ->with('customers',$customers)
                            ->with('amcs',$amcs)
                            ->with('topFunds',$topFunds)
                            ->with('sum',$sum);
  }


}
