<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AmcCity;
use App\Models\AmcCountry;
use App\Models\AmcAPI;
use App\Models\City;
use Illuminate\Support\Facades\Validator;


class AmcCityController extends Controller
{
    public function index()
    {
        $cities=City::where('status',1)->get();
        return view('amc_cities.index',compact('cities'));
    }
    public function getamccities(Request $request)
    {
        // $amc_data_controller = new \App\Http\Controllers\AmcDataController();
        // $amc_data_controller->get_amc_data('cities');

        $amc_api_data=AmcAPI::where('name','Get Cities Data API')->first();
        $amc_countries=AmcCountry::all();
        // foreach($amc_countries as $country)
        // {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $amc_api_data->url.'?AccessKey='.$amc_api_data->access_key.'&Country=Pakistan');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $dom->savexml();
        $codes = $dom->getElementsByTagName('CITY_x0020_CODE');
        for($i=0;$i<count($codes);$i++)
        {
            $city_codes[]=$codes->item($i)->nodeValue;
        }
        $names = $dom->getElementsByTagName('CITY_x0020_NAME');
        for($i=0;$i<count($names);$i++)
        {
            $city_names[]=$names->item($i)->nodeValue;
        }
        for($i=0;$i<count($city_codes);$i++)
        {
          $amc_city=AmcCity::where('amc_city_code',$city_codes[$i])->where('amc_id',2)->first();
          if(isset($amc_city))
          {
            $amc_city->amc_country_name='Pakistan';
            $amc_city->amc_city_name=$city_names[$i];
            $amc_city->save();
          }
          else
          {
          $amc_city=new Amccity();
          $amc_city->amc_id=2;
          $amc_city->amc_city_code=$city_codes[$i];
          $amc_city->amc_country_name='Pakistan';
          $amc_city->amc_city_name=$city_names[$i];
          $amc_city->save();
          }
        }
        curl_close($curl);
        //}
        return response()->json(['success'=> true, 'message' => 'AMC Cities Data Refreshed successfully!']);
    }
    public function show(Request $request)
    {
        $amc_cities=AmcCity::with(['amc','city'])->get();
        return DataTables::of($this->filter($request, $amc_cities))->make(true);
    }
    public function update(Request $request,$amc_city_id)
    {
          $validator = Validator::make($request->all(), [
            'ypay_city_id'        => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $amc_city=AmcCity::where('id',$amc_city_id)->first();
          $amc_city->ypay_city_id=$request->ypay_city_id;
          $amc_city->save();
          return response()->json(['success'=> true, 'message' => 'City Data Updated successfully!']);
    }
    public function filter(Request $request,$amc_cities)
    {
    try{
        return $amc_cities;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
