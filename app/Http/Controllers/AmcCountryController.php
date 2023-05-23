<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AmcAPI;
use App\Models\AmcCountry;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;


class AmcCountryController extends Controller
{
    public function index()
    {
        return view('amc_countries.index');
    }
    public function getamccountries(Request $request)
    {
        // $amc_data_controller = new \App\Http\Controllers\AmcDataController();
        // $amc_data_controller->get_amc_data('countries');

        $amc_api_data=AmcAPI::where('name','Get Countries Data API')->first();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $amc_api_data->url.'?AccessKey='.$amc_api_data->access_key);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $dom->savexml();
        $ids = $dom->getElementsByTagName('COUNTRY_x0020_ID');
        for($i=0;$i<count($ids);$i++)
        {
            $country_ids[]=$ids->item($i)->nodeValue;
        }
        $names = $dom->getElementsByTagName('COUNTRY_x0020_NAME');
        for($i=0;$i<count($names);$i++)
        {
            $country_names[]=$names->item($i)->nodeValue;
        }
        for($i=0;$i<count($country_ids);$i++)
        {
          $amc_country=AmcCountry::where('amc_country_id',$country_ids[$i])->where('amc_id',2)->first();
          if(isset($amc_country))
          {
            $amc_country->amc_country_name=$country_names[$i];
            $amc_country->save();
          }
          else
          {
          $amc_country=new AmcCountry();
          $amc_country->amc_id=2;
          $amc_country->amc_country_id=$country_ids[$i];
          $amc_country->amc_country_name=$country_names[$i];
          $amc_country->save();
          }
        }
        curl_close($curl);
        return response()->json(['success'=> true, 'message' => 'AMC Countries Data Refreshed successfully!']);
    }
    public function show(Request $request)
    {
        $amc_countries=AmcCountry::with(['amc','country'])->get();
        return DataTables::of($this->filter($request, $amc_countries))->make(true);
    }
    public function filter(Request $request,$amc_countries)
    {
    try{
        return $amc_countries;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
