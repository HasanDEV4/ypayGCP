<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CustCnicDetail;
use App\Models\CsvImportLog;
use App\Models\AmcCustProfile;
use Yajra\DataTables\DataTables;
use App\Models\Investment;
use App\Models\Redemption;

class CsvController extends Controller
{
    public function import_csv()
    {
        return view('csv.import');
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
    public function show(Request $request)
    {
        $csvimportslog=CsvImportLog::with("amc")->get();
        return DataTables::of($this->filter($request, $csvimportslog))->make(true);
    }
    public function import_file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_type'         => 'required',
            'amc_id'             => 'required',
            'csv_file'       => 'required|file'
        ],
        [
          'csv_type.required' => 'This field is required',
          'amc_id.required' => 'This field is required',
          'csv_file.required' => 'CSV File is required',
        ]);
        $data=$request->all();
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
        $csv_import_log=new CsvImportLog();
        $csv_import_log->csv_type=$data["csv_type"];
        $amc_id=$data["amc_id"];
        $csv_import_log->amc_id=$amc_id;
        $csv_array=$this->csvToArray($data["csv_file"]);
        if($data["csv_type"]=="kyc")
        {
            try{
            $line_no=0;
            foreach($csv_array as $response)
            {
            $cust_cnic_details=CustCnicDetail::where('cnic_number',$response["CNIC"])->first();
            $amc_custprofile=AmcCustProfile::where("user_id",$cust_cnic_details["user_id"])->where('amc_id',$amc_id)->first();
            if(isset($amc_custprofile))
            {
            $amc_custprofile->status=$response["Profile Status"];
            $amc_custprofile->rejected_reason=$response["Reject Reason"]??NULL;
            $amc_custprofile->account_number=$response["Account Number"];
            $amc_custprofile->save();
            }
            $line_no++;
            }
            $csv_import_log->upload_status=1;
            $csv_import_log->save();

            } catch (\Exception $e) {

                $csv_import_log->upload_status=0;
                $csv_import_log->failure_reason=$e->getMessage()." Line Number:".$line_no;
                $csv_import_log->save();

                return response()->json(['error'=> true, 'message' => 'File Not Imported!']);
            }
        }
        else if($data["csv_type"]=="investment")
        {
            try{
                foreach($csv_array as $response)
                {
                  $cust_cnic_details=CustCnicDetail::where('cnic_number',$response["CNIC"])->first();
                  $investment=Investment::where("transaction_id",$response["Transaction Id"])->where("user_id",$cust_cnic_details["user_id"])->first();
                  if(isset($investment))
                  {
                  $investment->nav=$response["NAV Rate"];
                  $investment->unit=$response["Allotted Units"];
                  $investment->amc_reference_number=$response["AMC Reference Number"];
                  $approved_date=date_create($response["Approval Date"]);
                  $investment->approved_date=date_format($approved_date,"Y-m-d");
                  $investment->status=$response["Investment Status"];
                  $investment->rejected_reason=$response["Reject Reason"]??NULL;
                  $investment->save();
                  }
                }
    
                $csv_import_log->upload_status=1;
                $csv_import_log->save();
    
                } catch (\Exception $e) {
    
                    $csv_import_log->upload_status=0;
                    $csv_import_log->failure_reason=$e->getMessage();
                    $csv_import_log->save();
    
                    return response()->json(['error'=> true, 'message' => 'File Not Imported!']);
                }
        }
        else
        {
            try{
                foreach($csv_array as $response)
                {
                  $cust_cnic_details=CustCnicDetail::where('cnic_number',$response["CNIC"])->first(); 
                  $investment=Investment::where("transaction_id",$response["Investment Transaction Id"])->where("user_id",$cust_cnic_details["user_id"])->first();
                  $redemption=Redemption::where("invest_id",$investment->id)->where("transaction_id",$response["Transaction Id"])->first();
                  if(isset($redemption))
                  {
                  $redemption->amc_reference_number=$response["AMC Reference Number"];
                  $redemption->redeem_amount=$response["Approved Amount"];
                  $approved_date=date_create($response["Approval Date"]);
                  $redemption->approved_date=date_format($approved_date,"Y-m-d");
                  $redemption->status=$response["Redemption Status"];
                  $redemption->rejected_reason=$response["Reject Reason"]??NULL;
                  $redemption->save();
                  }
                }
    
                $csv_import_log->upload_status=1;
                $csv_import_log->save();
    
                } catch (\Exception $e) {
    
                    $csv_import_log->upload_status=0;
                    $csv_import_log->failure_reason=$e->getMessage();
                    $csv_import_log->save();
    
                    return response()->json(['error'=> true, 'message' => 'File Not Imported!']);
                }   
        }
        return response()->json(['success'=> true, 'message' => 'File Imported successfully!']);
    }
    public function filter(Request $request,$csvimportslog)
    {
        $data=$request->all();
      try{
        if(isset($data["amc"]) && $data["amc"] != "null")  
        {
            $amc=$data["amc"];
            $csvimportslog=$csvimportslog->where("amc_id",$amc);
        }
        if(isset($data["csv_type"]) && $data["csv_type"] != "null")  
        {
            $csv_type = $data["csv_type"];
            $csvimportslog=$csvimportslog->where("csv_type",$csv_type);
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
}