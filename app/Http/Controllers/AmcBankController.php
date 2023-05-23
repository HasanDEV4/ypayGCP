<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AmcBank;
use App\Models\Bank;
use App\Models\AmcAPI;
use Illuminate\Support\Facades\Validator;


class AmcBankController extends Controller
{
    public function index()
    {
        $banks=Bank::where('status',1)->get();
        return view('amc_banks.index',compact('banks'));
    }
    public function getamcbanks(Request $request)
    {
        // $amc_data_controller = new \App\Http\Controllers\AmcDataController();
        // $amc_data_controller->get_amc_data('banks');

        $amc_api_data=AmcAPI::where('name','Get Banks Data API')->first();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $amc_api_data->url.'?AccessKey='.$amc_api_data->access_key);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $dom->savexml();
        $ids = $dom->getElementsByTagName('BANK_x0020_ID');
        for($i=0;$i<count($ids);$i++)
        {
            $bank_ids[]=$ids->item($i)->nodeValue;
        }
        $short_names = $dom->getElementsByTagName('BANK_x0020_SHORTNAME');
        for($i=0;$i<count($short_names);$i++)
        {
            $bank_short_names[]=$short_names->item($i)->nodeValue;
        }
        $names = $dom->getElementsByTagName('BANK_x0020_NAME');
        for($i=0;$i<count($names);$i++)
        {
            $bank_names[]=$names->item($i)->nodeValue;
        }
        for($i=0;$i<count($bank_ids);$i++)
        {
          $amc_bank=AmcBank::where('amc_bank_id',$bank_ids[$i])->where('amc_id',2)->first();
          if(isset($amc_bank))
          {
            $amc_bank->amc_bank_short_name=$bank_short_names[$i];
            $amc_bank->amc_bank_name=$bank_names[$i];
            $amc_bank->save();
          }
          else
          {
          $amc_bank=new AmcBank();
          $amc_bank->amc_id=2;
          $amc_bank->amc_bank_id=$bank_ids[$i];
          $amc_bank->amc_bank_short_name=$bank_short_names[$i];
          $amc_bank->amc_bank_name=$bank_names[$i];
          $amc_bank->save();
          }
        }
        curl_close($curl);
        return response()->json(['success'=> true, 'message' => 'AMC Banks Data Refreshed successfully!']);
    }
    public function update(Request $request,$amc_bank_id)
    {
          $validator = Validator::make($request->all(), [
            'ypay_bank_id'        => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $amc_bank=AmcBank::where('id',$amc_bank_id)->first();
          $amc_bank->ypay_bank_id=$request->ypay_bank_id;
          $amc_bank->save();
          return response()->json(['success'=> true, 'message' => 'Bank Data Updated successfully!']);
    }
    public function show(Request $request)
    {
        $amc_banks=AmcBank::with(['amc','bank'])->get();
        return DataTables::of($this->filter($request, $amc_banks))->make(true);
    }
    public function filter(Request $request,$amc_banks)
    {
    try{
        return $amc_banks;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
