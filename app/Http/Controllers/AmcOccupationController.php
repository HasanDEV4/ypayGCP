<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AmcOccupation;
use App\Models\Occupation;
use App\Models\AmcAPI;
use Illuminate\Support\Facades\Validator;


class AmcOccupationController extends Controller
{
    public function index()
    {
        $occupations=Occupation::where('status',1)->get();
        return view('amc_occupations.index',compact('occupations'));
    }
    public function getamcoccupations(Request $request)
    {
        // $amc_data_controller = new \App\Http\Controllers\AmcDataController();
        // $amc_data_controller->get_amc_data('occupations');

        $amc_api_data=AmcAPI::where('name','Get Occupations Data API')->first();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $amc_api_data->url.'?AccessKey='.$amc_api_data->access_key);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $dom->savexml();
        $ids = $dom->getElementsByTagName('Occupation_ID');
        for($i=0;$i<count($ids);$i++)
        {
            $occupation_ids[]=$ids->item($i)->nodeValue;
        }
        $names = $dom->getElementsByTagName('Occupation_Name');
        for($i=0;$i<count($names);$i++)
        {
            $occupation_names[]=$names->item($i)->nodeValue;
        }
        for($i=0;$i<count($occupation_ids);$i++)
        {
          // $ypay_occupation=Occupation::where('name',$occupation_names[$i])->first();
          $amc_occupation=AmcOccupation::where('amc_occupation_id',$occupation_ids[$i])->where('amc_id',2)->first();
          // if(!isset($ypay_occupation))
          // {
          //   $ypay_occupation=new Occupation();
          //   $ypay_occupation->name=$occupation_names[$i];
          //   $ypay_occupation->amc_id=2;
          //   $ypay_occupation->save();
          // }
          if(isset($amc_occupation))
          {
            $amc_occupation->amc_occupation_name=$occupation_names[$i];
            // $amc_occupation->ypay_occupation_id=$ypay_occupation->id;
            $amc_occupation->save();
          }
          else
          {
          $amc_occupation=new AmcOccupation();
          $amc_occupation->amc_id=2;
          $amc_occupation->amc_occupation_id=$occupation_ids[$i];
          // $amc_occupation->ypay_occupation_id=$ypay_occupation->id;
          $amc_occupation->amc_occupation_name=$occupation_names[$i];
          $amc_occupation->save();
          }
        }
        curl_close($curl);
        return response()->json(['success'=> true, 'message' => 'AMC Occupations Data Refreshed successfully!']);
    }
    public function update(Request $request,$amc_occupation_id)
    {
          $validator = Validator::make($request->all(), [
            'ypay_occupation_id'        => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $amc_occupation=AmcOccupation::where('id',$amc_occupation_id)->first();
          $amc_occupation->ypay_occupation_id=$request->ypay_occupation_id;
          $amc_occupation->save();
          return response()->json(['success'=> true, 'message' => 'Occupation Data Updated successfully!']);
    }
    public function show(Request $request)
    {
        $amc_occupations=AmcOccupation::with(['amc','occupation'])->get();
        return DataTables::of($this->filter($request, $amc_occupations))->make(true);
    }
    public function filter(Request $request,$amc_occupations)
    {
    try{
        return $amc_occupations;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
