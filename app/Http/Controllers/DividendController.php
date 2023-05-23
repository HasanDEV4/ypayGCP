<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AmcFund;
use App\Models\CustCnicDetail;
use App\Models\CsvImportLog;
use App\Models\Question;
use App\Models\Dividend;
use App\Models\DividendTransaction;
use App\Models\Fund;
use App\Models\Amc;
use App\Models\RiskProfileRank;
use Illuminate\Support\Facades\Validator;
use function App\Libraries\Helpers\is_Date;

class DividendController extends Controller
{
    public function index()
    {
        $amcs=Amc::where('status',1)->get();
        // $users=User::where('status',1)->where('type',2)->get();
        $funds=Fund::with('additional_details')->whereHas('additional_details', function($q) {
            $q->where('status', 1);
        })->get();
        $users=User::with('cust_cnic_detail')->whereHas('cust_account_detail', function ($q){ 
          $q->whereIn('status',[0,1]);
        })->where('type',2)->get();
        return view('dividend.index',compact('amcs','funds','users'));
    }
    public function getimportlog(Request $request)
    {
      $csvimportslog=CsvImportLog::with("amc")->where('csv_type','dividend')->get();
      return DataTables::of($this->csvfilter($request, $csvimportslog))->make(true);
    }
    public function csv_log()
    {
        return view('dividend.import_log');
    }
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
    public function change_selected_status(Request $request)
    {
      $status=$request->status;
      $selected_dividends=$request->selected_dividends;
      foreach($selected_dividends as $selected_dividend_id)
      {
        Dividend::where('id',$selected_dividend_id)->update(['status'=>$status]);
      }
      return response()->json(['success'=> true, 'message' => 'Status Updated successfully']);
    }
    public function edit_dividend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
            'fund_id'  => 'required',
            'nav'  => 'required',
            'unit'  => 'required',
            'status'=>'required',
            'capital_gain_tax'  => 'required',
            'final_distributed_dividend'  => 'required',
            'distribution_date'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $dividend=Dividend::where('id',$request->id)->first();
        $dividend->transaction_id=$string;
        $dividend->fund_id=$request->fund_id;
        $dividend->user_id=$request->user_id;
        $dividend->status=0;
        $dividend->unit=$request->unit;
        $dividend->nav=$request->nav;
        $dividend->status=$request->status;
        $dividend->amount=(float)$request->nav*(float)$request->unit;
        $dividend->capital_gain_tax=$request->capital_gain_tax;
        $dividend->final_distributed_dividend=$request->final_distributed_dividend;
        $dividend->distribution_date=$request->distribution_date;
        $dividend->save();
        return response()->json(['success'=> true, 'message' => 'Dividend Updated successfully']);
    }
    public function add_dividend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
            'fund_id'  => 'required',
            'nav'  => 'required',
            'unit'  => 'required',
            'status'=>'required',
            'capital_gain_tax'  => 'required',
            'final_distributed_dividend'  => 'required',
            'distribution_date'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = substr(str_shuffle($chars), 0, 8);
        $dividend=Dividend::where('fund_id',$request->fund_id)->where('user_id',$request->user_id)->first();
        if (isset($dividend)) {
          $date = date('Y-m-d',strtotime($request->distribution_date));
          $month = date('m',strtotime($request->distribution_date));
          $day_dividend=DividendTransaction::where('fund_id',$request->fund_id)->where('user_id',$request->user_id)->whereDate('distribution_date', $date)->first();
          // $day_dividend = $day_dividend->whereDate('distribution_date', $date);
          $month_dividend = $dividend->whereMonth('distribution_date', $month);
          if (isset($day_dividend) && $day_dividend->count() > 0) {
            $month_dividend = $month_dividend->first();
            $month_dividend->unit = ($month_dividend->unit - $day_dividend-> unit) + $request->unit;
            $month_dividend->save();

            $day_dividend->transaction_id=$string;
            $day_dividend->fund_id=$request->fund_id;
            $day_dividend->user_id=$request->user_id;
            $day_dividend->unit=$request->unit;
            $day_dividend->nav=$request->nav;
            $day_dividend->status=$request->status;
            $day_dividend->amount=(float)$request->nav*(float)$request->unit;
            $day_dividend->capital_gain_tax=$request->capital_gain_tax;
            $day_dividend->final_distributed_dividend=$request->final_distributed_dividend;
            $day_dividend->distribution_date=$request->distribution_date;
            $day_dividend->save();
          } else if ($month_dividend->count() > 0) {
            $dividend->transaction_id=$string;
            $dividend->fund_id=$request->fund_id;
            $dividend->user_id=$request->user_id;
            $dividend->unit=$dividend->unit + $request->unit;
            $dividend->nav=$request->nav;
            $dividend->status=$request->status;
            $dividend->amount=$dividend->amount + (float)$request->nav*(float)$request->unit;
            $dividend->capital_gain_tax=$dividend->capital_gain_tax + $request->capital_gain_tax;
            $dividend->final_distributed_dividend=$dividend->final_distributed_dividend + $request->final_distributed_dividend;
            $dividend->distribution_date=$request->distribution_date;
            $dividend->save();

            $dividend_transaction = new DividendTransaction;
            $dividend_transaction->transaction_id=$string;
            $dividend_transaction->fund_id=$request->fund_id;
            $dividend_transaction->user_id=$request->user_id;
            $dividend_transaction->unit=$request->unit;
            $dividend_transaction->nav=$request->nav;
            $dividend_transaction->status=$request->status;
            $dividend_transaction->amount=(float)$request->nav*(float)$request->unit;
            $dividend_transaction->capital_gain_tax=$request->capital_gain_tax;
            $dividend_transaction->final_distributed_dividend=$request->final_distributed_dividend;
            $dividend_transaction->distribution_date=$request->distribution_date;
            $dividend_transaction->save();

          } else {
            $dividend=new Dividend;
            $dividend->transaction_id=$string;
            $dividend->fund_id=$request->fund_id;
            $dividend->user_id=$request->user_id;
            $dividend->unit=$request->unit;
            $dividend->nav=$request->nav;
            $dividend->status=$request->status;
            $dividend->amount=(float)$request->nav*(float)$request->unit;
            $dividend->capital_gain_tax=$request->capital_gain_tax;
            $dividend->final_distributed_dividend=$request->final_distributed_dividend;
            $dividend->distribution_date=$request->distribution_date;
            $dividend->save();

            $dividend_transaction = new DividendTransaction;
            $dividend_transaction->transaction_id=$string;
            $dividend_transaction->fund_id=$request->fund_id;
            $dividend_transaction->user_id=$request->user_id;
            $dividend_transaction->unit=$request->unit;
            $dividend_transaction->nav=$request->nav;
            $dividend_transaction->status=$request->status;
            $dividend_transaction->amount=(float)$request->nav*(float)$request->unit;
            $dividend_transaction->capital_gain_tax=$request->capital_gain_tax;
            $dividend_transaction->final_distributed_dividend=$request->final_distributed_dividend;
            $dividend_transaction->distribution_date=$request->distribution_date;
            $dividend_transaction->save();
          }
        } else {
          $dividend=new Dividend;
          $dividend->transaction_id=$string;
          $dividend->fund_id=$request->fund_id;
          $dividend->user_id=$request->user_id;
          $dividend->unit=$request->unit;
          $dividend->nav=$request->nav;
          $dividend->status=$request->status;
          $dividend->amount=(float)$request->nav*(float)$request->unit;
          $dividend->capital_gain_tax=$request->capital_gain_tax;
          $dividend->final_distributed_dividend=$request->final_distributed_dividend;
          $dividend->distribution_date=$request->distribution_date;
          $dividend->save();

          $dividend_transaction = new DividendTransaction;
          $dividend_transaction->transaction_id=$string;
          $dividend_transaction->fund_id=$request->fund_id;
          $dividend_transaction->user_id=$request->user_id;
          $dividend_transaction->unit=$request->unit;
          $dividend_transaction->nav=$request->nav;
          $dividend_transaction->status=$request->status;
          $dividend_transaction->amount=(float)$request->nav*(float)$request->unit;
          $dividend_transaction->capital_gain_tax=$request->capital_gain_tax;
          $dividend_transaction->final_distributed_dividend=$request->final_distributed_dividend;
          $dividend_transaction->distribution_date=$request->distribution_date;
          $dividend_transaction->save();
        }
        return response()->json(['success'=> true, 'message' => 'Dividend Added successfully']);
    }
    public function import_csv(Request $request)
    {
      $error_count=0;
            try{
            $csv_data=$this->csvToArray($request->csv_file);
            try{
              $line_no=2;
              foreach($csv_data as $data)
              {
                if(is_Date(date('Y/m/d',strtotime($data['Date']))) && date('Y/m/d',strtotime($data['Date']))!="1970/01/01")
                {
                  $user_id=CustCnicDetail::where('cnic_number',$data['CNIC'])->get()->pluck('user_id');
                  
                  if(isset($user_id[0]))
                  {
                    $userid=$user_id[0];
                  }
                  else
                  {
                    $error_count++;
                    $csv_import_log=new CsvImportLog();
                    $csv_import_log->csv_type="dividend";
                    $csv_import_log->amc_id=$request->amc_id;
                    $csv_import_log->upload_status=0;
                    $csv_import_log->failure_reason="Wrong CNIC at Line Number:".$line_no;
                    $csv_import_log->save();
                  }
                  $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                  $string = substr(str_shuffle($chars), 0, 8);
                  if($request->fund_ids_available=="1" && !isset($request->fund_id))
                  {
                    $fund_id=AmcFund::where('amc_fund_code',$data['Fund ID'])->get()->pluck('ypay_fund_id');
                    if(isset($fund_id[0]))
                    {
                      $fund_id=$fund_id[0];
                    }
                    else
                    {
                      $error_count++;
                      $csv_import_log=new CsvImportLog();
                      $csv_import_log->csv_type="dividend";
                      $csv_import_log->amc_id=$request->amc_id;
                      $csv_import_log->upload_status=0;
                      $csv_import_log->failure_reason="Wrong Fund ID at Line Number:".$line_no;
                      $csv_import_log->save();
                    }
                  }
                  else
                  $fund_id=$request->fund_id;
                  if(isset($fund_id) && isset($userid) && count($user_id)!=0 && isset($data['Date']))
                  {
                    $date = date('Y-m-d',strtotime($data['Date']));
                    $month = date('m',strtotime($data['Date']));
                    $dividend=Dividend::where('fund_id',$fund_id)->where('user_id',$userid)->first();
                    if (isset($dividend)) {
                      $day_dividend=DividendTransaction::where('fund_id',$fund_id)->where('user_id',$userid)->whereDate('distribution_date', $date)->first();
                      // $day_dividend = $day_dividend->whereDate('distribution_date', $date);
                      $month_dividend = $dividend->whereMonth('distribution_date', $month);
                      if (isset($day_dividend) && $day_dividend->count() > 0) {
                        $month_dividend = $month_dividend->first();
                        $month_dividend->unit = ($month_dividend->unit - $day_dividend->unit) + $data['DIVIDEND UNITS'];
                        $month_dividend->save();

                        $day_dividend->transaction_id=$string;
                        $day_dividend->fund_id=$fund_id;
                        $day_dividend->user_id=$userid;
                        $day_dividend->status=0;
                        $day_dividend->unit=$data['DIVIDEND UNITS'];
                        $day_dividend->nav=$data['NAV'];
                        $day_dividend->amount=(float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                        $day_dividend->capital_gain_tax=$data['CAPITAL GAIN TAX ON DIVIDEND'];
                        $day_dividend->final_distributed_dividend=$data['FINAL DISTRIBUTED DIVIDEND'];
                        $day_dividend->distribution_date=$date;
                        $day_dividend->save();
                      } else if ($month_dividend->count() > 0) {
                        $dividend->transaction_id=$string;
                        $dividend->fund_id=$fund_id;
                        $dividend->user_id=$userid;
                        $dividend->status=0;
                        $dividend->unit=$dividend->unit + $data['DIVIDEND UNITS'];
                        $dividend->nav=$data['NAV'];
                        $dividend->amount=$dividend->amount + (float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                        $dividend->capital_gain_tax=$dividend->capital_gain_tax + $data['CAPITAL GAIN TAX ON DIVIDEND'];
                        $dividend->final_distributed_dividend=$dividend->final_distributed_dividend + $data['FINAL DISTRIBUTED DIVIDEND'];
                        $dividend->distribution_date=$date;
                        $dividend->save();

                        $dividend_transaction = new DividendTransaction;
                        $dividend_transaction->transaction_id=$string;
                        $dividend_transaction->fund_id=$fund_id;
                        $dividend_transaction->user_id=$userid;
                        $dividend_transaction->status=0;
                        $dividend_transaction->unit=$data['DIVIDEND UNITS'];
                        $dividend_transaction->nav=$data['NAV'];
                        $dividend_transaction->amount=(float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                        $dividend_transaction->capital_gain_tax=$data['CAPITAL GAIN TAX ON DIVIDEND'];
                        $dividend_transaction->final_distributed_dividend=$data['FINAL DISTRIBUTED DIVIDEND'];
                        $dividend_transaction->distribution_date=$date;
                        $dividend_transaction->save();

                      } else {
                        $dividend=new Dividend;
                        $dividend->transaction_id=$string;
                        $dividend->fund_id=$fund_id;
                        $dividend->user_id=$userid;
                        $dividend->status=0;
                        $dividend->unit=$data['DIVIDEND UNITS'];
                        $dividend->nav=$data['NAV'];
                        $dividend->amount=(float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                        $dividend->capital_gain_tax=$data['CAPITAL GAIN TAX ON DIVIDEND'];
                        $dividend->final_distributed_dividend=$data['FINAL DISTRIBUTED DIVIDEND'];
                        $dividend->distribution_date=$date;
                        $dividend->save();

                        $dividend_transaction = new DividendTransaction;
                        $dividend_transaction->transaction_id=$string;
                        $dividend_transaction->fund_id=$fund_id;
                        $dividend_transaction->user_id=$userid;
                        $dividend_transaction->status=0;
                        $dividend_transaction->unit=$data['DIVIDEND UNITS'];
                        $dividend_transaction->nav=$data['NAV'];
                        $dividend_transaction->amount=(float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                        $dividend_transaction->capital_gain_tax=$data['CAPITAL GAIN TAX ON DIVIDEND'];
                        $dividend_transaction->final_distributed_dividend=$data['FINAL DISTRIBUTED DIVIDEND'];
                        $dividend_transaction->distribution_date=$date;
                        $dividend_transaction->save();
                      }
                    } else {
                      $dividend=new Dividend;
                      $dividend->transaction_id=$string;
                      $dividend->fund_id=$fund_id;
                      $dividend->user_id=$userid;
                      $dividend->status=0;
                      $dividend->unit=$data['DIVIDEND UNITS'];
                      $dividend->nav=$data['NAV'];
                      $dividend->amount=(float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                      $dividend->capital_gain_tax=$data['CAPITAL GAIN TAX ON DIVIDEND'];
                      $dividend->final_distributed_dividend=$data['FINAL DISTRIBUTED DIVIDEND'];
                      $dividend->distribution_date=$date;
                      $dividend->save();

                      $dividend_transaction = new DividendTransaction;
                      $dividend_transaction->transaction_id=$string;
                      $dividend_transaction->fund_id=$fund_id;
                      $dividend_transaction->user_id=$userid;
                      $dividend_transaction->status=0;
                      $dividend_transaction->unit=$data['DIVIDEND UNITS'];
                      $dividend_transaction->nav=$data['NAV'];
                      $dividend_transaction->amount=(float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                      $dividend_transaction->capital_gain_tax=$data['CAPITAL GAIN TAX ON DIVIDEND'];
                      $dividend_transaction->final_distributed_dividend=$data['FINAL DISTRIBUTED DIVIDEND'];
                      $dividend_transaction->distribution_date=$date;
                      $dividend_transaction->save();
                    }
                    
                    // if(!isset($dividend))
                    // $dividend=new Dividend;
                    // $dividend->transaction_id=$string;
                    // $dividend->fund_id=$fund_id;
                    // $dividend->user_id=$userid;
                    // $dividend->status=0;
                    // $dividend->unit=$data['DIVIDEND UNITS'];
                    // $dividend->nav=$data['NAV'];
                    // $dividend->amount=(float)$data['NAV']*(float)$data['DIVIDEND UNITS'];
                    // $dividend->capital_gain_tax=$data['CAPITAL GAIN TAX ON DIVIDEND'];
                    // $dividend->final_distributed_dividend=$data['FINAL DISTRIBUTED DIVIDEND'];
                    // $dividend->distribution_date=$data['Date'];
                    // $dividend->save();
                  }
                }
                else
                {
                  $error_count++;
                  $csv_import_log=new CsvImportLog();
                  $csv_import_log->csv_type="dividend";
                  $csv_import_log->amc_id=$request->amc_id;
                  $csv_import_log->upload_status=0;
                  $csv_import_log->failure_reason="Wrong Date format at Line Number:".$line_no;
                  $csv_import_log->save();
                }
                  $line_no++;
              }
    
            } catch (\Exception $e) {
              $error_count++;
              $csv_import_log=new CsvImportLog();
              $csv_import_log->csv_type="dividend";
              $csv_import_log->amc_id=$request->amc_id;
              $csv_import_log->upload_status=0;
              $csv_import_log->failure_reason=$e->getMessage()." Line Number:".$line_no;
              $csv_import_log->save();
            }
      }
      catch (\Exception $e) {
    
          $csv_import_log=new CsvImportLog();
          $csv_import_log->csv_type="dividend";
          $csv_import_log->amc_id=$request->amc_id;
          $csv_import_log->upload_status=0;
          $csv_import_log->failure_reason=$e->getMessage();
          $csv_import_log->save();

          return redirect()->back()->with('error', 'Wrong CSV Format');
      }
            if($error_count==0)
            {
            $csv_import_log=new CsvImportLog();
            $csv_import_log->csv_type="dividend";
            $csv_import_log->amc_id=$request->amc_id;
            $csv_import_log->upload_status=1;
            $csv_import_log->save();  
            return redirect()->back()->with('success', 'Csv Imported successfully!');
            }
            else
            {
              return redirect()->back()->with('error', 'Some of the Rows are not imported, Please check Log for details');
            }
    }
    public function show(Request $request)
    {
        $dividend=Dividend::with('user.cust_cnic_detail','fund.amc');
        return DataTables::of($this->filter($request, $dividend))->make(true);
    }
    public function store(Request $request)
    {
    }
    public function update(Request $request,$risk_rank_id)
    {
    }
    public function csvfilter(Request $request,$csvimportslog)
    {
        $data=$request->all();
      try{
        if(isset($data["amc"]) && $data["amc"] != "null")  
        {
            $amc=$data["amc"];
            $csvimportslog=$csvimportslog->where("amc_id",$amc);
        }
        if(isset($data["upload_status"]) && $data["upload_status"] != "null")  
        {
            $upload_status = $data["upload_status"];
            $csvimportslog=$csvimportslog->where("upload_status",$upload_status);
        }
        return $csvimportslog;
      } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
    public function filter(Request $request,$dividends)
    {
    try{
        if (isset($request->customerName) && $request->customerName != "null") {
        $customer = $request->customerName;
            $dividends = $dividends->where('user_id',$customer);
        }
        if (isset($request->fund) && $request->fund != "null") {
            $fund = $request->fund;
            $dividends = $dividends->where('fund_id',$fund);
          }
        if(isset($request->amc) && $request->amc != "null"){
            $amc = $request->amc;
            $dividends = $dividends->whereHas('fund.amc', function ($q) use ($amc) {
              $q->where('id',$amc);
            }); 
          }
          if (isset($request->from) && isset($request->to) && $request->from!= "null" && $request->to!= "null") {       
            $transaction_from=$request->from;
            $transaction_to=$request->to;
            $dividends = $dividends->whereBetween('created_at',[[date("Y-m-d H:i:s", strtotime($transaction_from)),date("Y-m-d H:i:s", strtotime($transaction_to))]]);
          }
          if (isset($request->status) && $request->status != "null") {       
            $dividends = $dividends->where('status',$request->status);
          }
        return $dividends;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
