<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AmcFund;
use App\Models\AmcAPI;
use App\Models\Fund;
use App\Models\AmcFundDetail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use function App\Libraries\Helpers\sendEmail;


class AmcFundController extends Controller
{
    public function index()
    {
        return view('amc_funds.index');
    }
    public function getAmcFunds(Request $request)
    {
        $amc_api_data=AmcAPI::where('name','Get Investment Info API')->first();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $amc_api_data->url.'?AccessKey='.$amc_api_data->access_key);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $dom->savexml();
        $codes = $dom->getElementsByTagName('Fund_x0020_Code');
        for($i=0;$i<count($codes);$i++)
        {
            $fund_codes[]=$codes->item($i)->nodeValue;
        }
        $ids = $dom->getElementsByTagName('Fund_x0020_ID');
        for($i=0;$i<count($ids);$i++)
        {
            $fund_ids[]=$ids->item($i)->nodeValue;
        }
        $names = $dom->getElementsByTagName('List_x0020_Of_x0020_Funds');
        for($i=0;$i<count($names);$i++)
        {
            $fund_names[]=$names->item($i)->nodeValue;
        }
        $units = $dom->getElementsByTagName('Unit_x0020_Type');
        for($i=0;$i<count($units);$i++)
        {
            $fund_unit_types[]=$units->item($i)->nodeValue;
        }
        $classes = $dom->getElementsByTagName('Class_x0020_Type');
        for($i=0;$i<count($classes);$i++)
        {
            $fund_classes[]=$classes->item($i)->nodeValue;
        }
        for($i=0;$i<count($fund_ids);$i++)
        {
          $amc_fund=AmcFund::where('amc_fund_id',$fund_ids[$i])->where('amc_fund_code',$fund_codes[$i])->where('amc_id',2)->first();
          if(isset($amc_fund))
          {
            $amc_fund->amc_fund_name=$fund_names[$i];
            $amc_fund->amc_fund_unit_type=$fund_unit_types[$i];
            $amc_fund->amc_fund_class_type=$fund_classes[$i];
            $amc_fund->save();
          }
          else
          {
          $amc_fund=new AmcFund();
          $amc_fund->amc_id=2;
          $amc_fund->amc_fund_code=$fund_codes[$i];
          $amc_fund->amc_fund_id=$fund_ids[$i];
          $amc_fund->amc_fund_name=$fund_names[$i];
          $amc_fund->amc_fund_unit_type=$fund_unit_types[$i];
          $amc_fund->amc_fund_class_type=$fund_classes[$i];
          $amc_fund->save();
          }
        }
        curl_close($curl);
        return response()->json(['success'=> true, 'message' => 'AMC Funds Data Refreshed successfully!']);
    }
    public function show(Request $request)
    {
        $amc_funds=AmcFund::with(['amc','fund'])->get();
        return DataTables::of($this->filter($request, $amc_funds))->make(true);
    }
    public function filter(Request $request,$amc_funds)
    {
    try{
        return $amc_funds;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
    public function update_fund_data() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://alfalahghp.com/api/nav');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $funds = json_decode($xml_response);
        // dd($funds);
        $funds = $funds->response->data;
        // dd($funds);
        foreach($funds as $fund) {
            var_dump($fund);
            echo "<br /><br />";
            $date_time = Carbon::now();
            $date_time = $date_time->toDateTimeString();
            var_dump($fund->fund_name);
            echo "<br /><br />";
            $amc_fund = AmcFund::where('amc_fund_name', $fund->fund_name)->first();
            var_dump($amc_fund);
            echo "<br /><br />";
            if(isset($amc_fund)) {
                echo "in if <br /><br />";
                $fund_db = Fund::where('id', $amc_fund->ypay_fund_id)->first();
                $fund_db->nav = $fund->nav_per_unit;
                $fund_db->last_updated_nav=$date_time;
                $fund_db->save();
            }
        }
    }

    public function get_funds_data() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://192.168.103.115:84/Portfolio.asmx/FundDetails');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $xml_response = curl_exec($curl);
        $dom = new \DOMDocument;
        $dom->loadXML($xml_response);
        $dom->formatOutput = TRUE;
        $category_id_elements = $dom->getElementsByTagName('CategoryID');
        $fund_id_elements = $dom->getElementsByTagName('FundID');
        $fund_name_elements = $dom->getElementsByTagName('FundName');
        $amc_id_elements = $dom->getElementsByTagName('AMCID');
        $amc_name_elements = $dom->getElementsByTagName('AMCName');
        $category_name_elements = $dom->getElementsByTagName('CategoryName');
        $category_type_elements = $dom->getElementsByTagName('CategoryType');
        $nav_elements = $dom->getElementsByTagName('NAV');
        $validity_date_elements = $dom->getElementsByTagName('ValidityDate');
        $offer_elements = $dom->getElementsByTagName('Offer');
        $ytd_elements = $dom->getElementsByTagName('YTD');
        $mtd_elements = $dom->getElementsByTagName('MTD');
        $c1day_elements = $dom->getElementsByTagName('C1Day');
        $c15day_elements = $dom->getElementsByTagName('C15Days');
        $c30day_elements = $dom->getElementsByTagName('C30Days');
        $c90day_elements = $dom->getElementsByTagName('C90Days');
        $c180day_elements = $dom->getElementsByTagName('C180Days');
        $c270day_elements = $dom->getElementsByTagName('C270Days');
        $c360day_elements = $dom->getElementsByTagName('C360Days');
        $c365day_elements = $dom->getElementsByTagName('C365Days');
        $aum_elements = $dom->getElementsByTagName('AUM');
        $fund_rating_elements = $dom->getElementsByTagName('FundRating');
        $benchmark_elements = $dom->getElementsByTagName('Benchmark');
        $inception_date_elements = $dom->getElementsByTagName('InceptionDate');
        $fund_short_code_elements = $dom->getElementsByTagName('FundShortCode');
        for($i=0;$i<count($category_id_elements);$i++)
        {
            // $category_ids[]=$category_id_elements->item($i)->nodeValue;
            // $fund_ids[]=$fund_id_elements->item($i)->nodeValue;
            // $fund_names[]=$fund_name_elements->item($i)->nodeValue;
            // $amc_ids[]=$amc_id_elements->item($i)->nodeValue;
            // $amc_names[]=$amc_name_elements->item($i)->nodeValue;
            // $category_names[]=$category_name_elements->item($i)->nodeValue;
            // $category_types[]=$category_type_elements->item($i)->nodeValue;
            // $navs[]=$nav_elements->item($i)->nodeValue;
            // $validity_dates[]=$validity_date_elements->item($i)->nodeValue;
            // $offers[]=$offer_elements->item($i)->nodeValue;
            // $ytds[]=$ytd_elements->item($i)->nodeValue;
            // $mtds[]=$mtd_elements->item($i)->nodeValue;
            // $c1days[]=$c1day_elements->item($i)->nodeValue;
            // $c15days[]=$c15day_elements->item($i)->nodeValue;
            // $c30days[]=$c30day_elements->item($i)->nodeValue;
            // $c90days[]=$c90day_elements->item($i)->nodeValue;
            // $c180days[]=$c180day_elements->item($i)->nodeValue;
            // $c270days[]=$c270day_elements->item($i)->nodeValue;
            // $c360days[]=$c360day_elements->item($i)->nodeValue;
            // $c365days[]=$c365day_elements->item($i)->nodeValue;
            // $aums[]=$aum_elements->item($i)->nodeValue;
            // $fund_ratings[]=$fund_rating_elements->item($i)->nodeValue;
            // $benchmarks[]=$benchmark_elements->item($i)->nodeValue;
            // $inception_dates[]=$inception_date_elements->item($i)->nodeValue;
            // $fund_short_codes[]=$fund_short_code_elements->item($i)->nodeValue;
            $amc_fund=AmcFundDetail::where('fund_id',$fund_id_elements->item($i)->nodeValue)->where('ypay_amc_id',2)->first();
            if(isset($amc_fund)) {
                $amc_fund->category_id = $category_id_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_id = $fund_id_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_name = $fund_name_elements->item($i)->nodeValue??NULL;
                $amc_fund->amc_id = $amc_id_elements->item($i)->nodeValue??NULL;
                $amc_fund->amc_name = $amc_name_elements->item($i)->nodeValue??NULL;
                $amc_fund->category_name = $category_name_elements->item($i)->nodeValue??NULL;
                $amc_fund->category_type = $category_type_elements->item($i)->nodeValue??NULL;
                $amc_fund->nav = $navs[]=$nav_elements->item($i)->nodeValue??NULL;
                $amc_fund->validity_date = $validity_date_elements->item($i)->nodeValue??NULL;
                $amc_fund->offer = $offer_elements->item($i)->nodeValue??NULL;
                $amc_fund->ytd = $ytd_elements->item($i)->nodeValue??NULL;
                $amc_fund->mtd = $mtd_elements->item($i)->nodeValue??NULL;
                $amc_fund->c1day = $c1day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c15days = $c15day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c30days = $c30day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c90days = $c90day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c180days = $c180day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c270days = $c270day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c360days = $c360day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c365days = $c365day_elements->item($i)->nodeValue??NULL;
                $amc_fund->aum = $aum_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_rating = $fund_rating_elements->item($i)->nodeValue??NULL;
                $amc_fund->benchmark = $benchmark_elements->item($i)->nodeValue??NULL;
                $amc_fund->inception_date = $inception_date_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_short_code = $fund_short_code_elements->item($i)->nodeValue??NULL;
                $amc_fund->ypay_amc_id = 2;
                $amc_fund->save();
            } else {
                $amc_fund=new AmcFundDetail();
                $amc_fund->category_id = $category_id_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_id = $fund_id_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_name = $fund_name_elements->item($i)->nodeValue??NULL;
                $amc_fund->amc_id = $amc_id_elements->item($i)->nodeValue??NULL;
                $amc_fund->amc_name = $amc_name_elements->item($i)->nodeValue??NULL;
                $amc_fund->category_name = $category_name_elements->item($i)->nodeValue??NULL;
                $amc_fund->category_type = $category_type_elements->item($i)->nodeValue??NULL;
                $amc_fund->nav = $navs[]=$nav_elements->item($i)->nodeValue??NULL;
                $amc_fund->validity_date = $validity_date_elements->item($i)->nodeValue??NULL;
                $amc_fund->offer = $offer_elements->item($i)->nodeValue??NULL;
                $amc_fund->ytd = $ytd_elements->item($i)->nodeValue??NULL;
                $amc_fund->mtd = $mtd_elements->item($i)->nodeValue??NULL;
                $amc_fund->c1day = $c1day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c15days = $c15day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c30days = $c30day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c90days = $c90day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c180days = $c180day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c270days = $c270day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c360days = $c360day_elements->item($i)->nodeValue??NULL;
                $amc_fund->c365days = $c365day_elements->item($i)->nodeValue??NULL;
                $amc_fund->aum = $aum_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_rating = $fund_rating_elements->item($i)->nodeValue??NULL;
                $amc_fund->benchmark = $benchmark_elements->item($i)->nodeValue??NULL;
                $amc_fund->inception_date = $inception_date_elements->item($i)->nodeValue??NULL;
                $amc_fund->fund_short_code = $fund_short_code_elements->item($i)->nodeValue??NULL;
                $amc_fund->ypay_amc_id = 2;
                $amc_fund->save();
            }
        }
        // for($i=0;$i<count($fund_ids);$i++)
        // {
        //     $amc_fund=AmcFundDetail::where('fund_id',$fund_ids[$i])->where('amc_id',2)->first();
        //     if(isset($amc_fund)) {
        //         $amc_fund->category_id = $category_ids[$i];
        //         $amc_fund->fund_id = $fund_ids[$i];
        //         $amc_fund->fund_name = $fund_names[$i];
        //         $amc_fund->amc_id = $amc_ids[$i];
        //         $amc_fund->amc_name = $amc_names[$i];
        //         $amc_fund->category_name = $category_names[$i];
        //         $amc_fund->category_type = $category_types[$i];
        //         $amc_fund->nav = $navs[]=$nav_elements->item($i)->nodeValue;
        //         $amc_fund->validity_date = $validity_dates[$i];
        //         $amc_fund->offer = $offers[$i];
        //         $amc_fund->ytd = $ytds[$i];
        //         $amc_fund->mtd = $mtds[$i];
        //         $amc_fund->c1day = $c1days[$i];
        //         $amc_fund->c15days = $c15days[$i];
        //         $amc_fund->c30days = $c30days[$i];
        //         $amc_fund->c90days = $c90days[$i];
        //         $amc_fund->c180days = $c180days[$i];
        //         $amc_fund->c270days = $c270days[$i];
        //         $amc_fund->c360days = $c360days[$i];
        //         $amc_fund->c365days = $c365days[$i];
        //         $amc_fund->aum = $aums[$i];
        //         $amc_fund->fund_rating = $fund_ratings[$i];
        //         $amc_fund->benchmark = $benchmarks[$i];
        //         $amc_fund->inception_date = $inception_dates[$i];
        //         $amc_fund->fund_short_code = $fund_short_codes[$i];
        //         $amc_fund->ypay_amc_id = 2;
        //         $amc_fund->save();
        //     } else {
        //         $amc_fund=new AmcFundDetail();
        //         $amc_fund->category_id = $category_ids[$i];
        //         $amc_fund->fund_id = $fund_ids[$i];
        //         $amc_fund->fund_name = $fund_names[$i];
        //         $amc_fund->amc_id = $amc_ids[$i];
        //         $amc_fund->amc_name = $amc_names[$i];
        //         $amc_fund->category_name = $category_names[$i];
        //         $amc_fund->category_type = $category_types[$i];
        //         $amc_fund->nav = $navs[]=$nav_elements->item($i)->nodeValue;
        //         $amc_fund->validity_date = $validity_dates[$i];
        //         $amc_fund->offer = $offers[$i];
        //         $amc_fund->ytd = $ytds[$i];
        //         $amc_fund->mtd = $mtds[$i];
        //         $amc_fund->c1day = $c1days[$i];
        //         $amc_fund->c15days = $c15days[$i];
        //         $amc_fund->c30days = $c30days[$i];
        //         $amc_fund->c90days = $c90days[$i];
        //         $amc_fund->c180days = $c180days[$i];
        //         $amc_fund->c270days = $c270days[$i];
        //         $amc_fund->c360days = $c360days[$i];
        //         $amc_fund->c365days = $c365days[$i];
        //         $amc_fund->aum = $aums[$i];
        //         $amc_fund->fund_rating = $fund_ratings[$i];
        //         $amc_fund->benchmark = $benchmarks[$i];
        //         $amc_fund->inception_date = $inception_dates[$i];
        //         $amc_fund->fund_short_code = $fund_short_codes[$i];
        //         $amc_fund->ypay_amc_id = 2;
        //         $amc_fund->save();
        //     }
        // }
        curl_close($curl);
    }

    public function check_eb_jobs()
    {
        // email notificaion 
        $url  = 'https://networks.ypayfinancial.com/api/mailv1/email_verification.php';
        $body = ['email' => 'kk442242@gmail.com', 'name' => 'Karan Kumar', 'token' => '1234'];
        sendEmail($body,$url);
    }
}
