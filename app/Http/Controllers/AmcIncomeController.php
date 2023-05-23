<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AmcSourceofIncome;
use App\Models\AmcAPI;
use App\Models\SourcesofIncome;
use Illuminate\Support\Facades\Validator;


class AmcIncomeController extends Controller
{
    public function index()
    {
        $income_sources=SourcesofIncome::where('status',1)->get();
        return view('amc_sources_of_income.index',compact('income_sources'));
    }
    public function getamcincomesources(Request $request)
    {
        // $amc_data_controller = new \App\Http\Controllers\AmcDataController();
        // $amc_data_controller->get_amc_data('income_sources');
        
        $amc_api_data=AmcAPI::where('name','Get Sources of Income Data API')->first();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $amc_api_data->url.'?AccessKey='.$amc_api_data->access_key);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $dom->savexml();
        $ids = $dom->getElementsByTagName('Income_ID');
        for($i=0;$i<count($ids);$i++)
        {
            $income_ids[]=$ids->item($i)->nodeValue;
        }
        $names = $dom->getElementsByTagName('Income_Name');
        for($i=0;$i<count($names);$i++)
        {
            $income_names[]=$names->item($i)->nodeValue;
        }
        for($i=0;$i<count($income_ids);$i++)
        {
          $amc_income=AmcSourceofIncome::where('amc_source_of_income_id',$income_ids[$i])->where('amc_id',2)->first();
          if(isset($amc_income))
          {
            $amc_income->amc_income_name=$income_names[$i];
            $amc_income->save();
          }
          else
          {
          $amc_income=new AmcSourceofIncome();
          $amc_income->amc_id=2;
          $amc_income->amc_source_of_income_id=$income_ids[$i];
          $amc_income->amc_income_name=$income_names[$i];
          $amc_income->save();
          }
        }
        curl_close($curl);
        return response()->json(['success'=> true, 'message' => 'AMC Sources of Income Data Refreshed successfully!']);
    }
    public function update(Request $request,$amc_income_source_id)
    {
          $validator = Validator::make($request->all(), [
            'ypay_source_of_income_id'        => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $amc_income_source=AmcSourceofIncome::where('id',$amc_income_source_id)->first();
          $amc_income_source->ypay_source_of_income_id=$request->ypay_source_of_income_id;
          $amc_income_source->save();
          return response()->json(['success'=> true, 'message' => 'Source of Income Data Updated successfully!']);
    }
    public function show(Request $request)
    {
        $amc_sources_of_income=AmcSourceofIncome::with('amc','incomesources')->get();
        return DataTables::of($this->filter($request, $amc_sources_of_income))->make(true);
    }
    public function filter(Request $request,$amc_sources_of_income)
    {
    try{
        return $amc_sources_of_income;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
