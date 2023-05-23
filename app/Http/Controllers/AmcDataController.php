<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AmcAPI;
use App\Models\AmcBank;
use App\Models\AmcCity;
use App\Models\AmcCountry;
use App\Models\AmcSourceofIncome;
use App\Models\AmcOccupation;
class AmcDataController extends Controller
{
    public function get_amc_data($type='all')
    {
        $curl = curl_init();
        $post_data=[
            "countryCode" => "001"
        ];
        $payload = json_encode($post_data);
        $amc_api_data=AmcAPI::where('amc_id',1)->where('name','JSIL Combo Api')->first();
        $headers  = [
            'apiToken: '.$amc_api_data->access_key,
            'Content-Type: application/json'
        ];
        curl_setopt($curl, CURLOPT_URL, $amc_api_data->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$payload);
        $response = curl_exec($curl);
        $data=json_decode($response);
        // dd($data->expInvestAmountBrackets);
        $amc_religions=$data->religions;
        $amc_countries=$data->countries;
        $amc_occupations=$data->occupations;
        $amc_income_sources=$data->incomeSources;
        $amc_banks=$data->banks;
        $amc_cities=$data->pakCities;


        $amc_invest_amntbrkts=$data->expInvestAmountBrackets;
        $amc_annual_income_brackets=$data->annualIncomeBrackets;
        $amc_peps_info_lists=$data->pepsInfoList;
        $amc_mobile_registered_with_list=$data->mobileRegisteredWithList;
        $amc_fatca_info_list=$data->fatcaInfoList;
        $amc_nationalities=$data->nationalities;
        $amc_age_rpq_list=$data->ageRpqList;
        $amc_marital_status_rpq_list=$data->maritalStatusRpqList;
        $amc_no_of_dependents_rpq_list=$data->noOfDependentsRpqList;
        $amc_occupation_rpq_list=$data->occupationRpqList;
        $qualification_rpq_list=$data->qualificationRpqList;
        $risk_appetite_rpq_list=$data->riskAppetiteRpqList;
        $invest_obj_rpq_list=$data->investObjRpqList;
        $invest_horizon_rpq_list=$data->investHorizonRpqList;
        $invest_knowledge_rpq_list=$data->investKnowledgeRpqList;
        $financial_position_rpq_list=$data->financialPositionRpqList;
        $account_type_list=$data->accountTypeList;
        $minor_list=$data->minorList;
        $title_list=$data->titleList;
        $resident_status_list=$data->residentStatusList;
        $marital_status_list=$data->maritalStatusList;
        $zakat_exemption_list=$data->zakatExemptionList;
        $retirement_age_list=$data->retirementAgeList;
        $relation_with_principle_list=$data->relationWithPrincipleList;
        $dividend_mandate_list=$data->dividendMandateList;
        $trans_mode_list=$data->transModeList;
        $expected_turnover_in_acc_type_list=$data->expectedTurnoverInAccTypeList;
        $tax_res_countries_other_than_pak_list=$data->taxResCountriesOtherThanPakList;
        if($type=="countries" || $type=="all")
        {
            foreach($amc_countries as $amc_country)
            {
            $amccountry=AmcCountry::where('amc_country_id',$amc_country->value)->where('amc_id',1)->first();
            if(!isset($amccountry))
            {
                $amccountry=new AmcCountry();
            }
            $amccountry->amc_id=1;
            $amccountry->amc_country_id=$amc_country->value;
            $amccountry->amc_country_name=$amc_country->label;
            $amccountry->save();
            }
        }
        if($type=="occupations" || $type=="all")
        {
            foreach($amc_occupations as $amc_occupation)
            {
                $amcoccupation=AmcOccupation::where('amc_occupation_id',$amc_occupation->occupoationCode)->where('amc_id',1)->first();
                if(!isset($amcoccupation))
                {
                    $amcoccupation=new AmcOccupation();
                }
                $amcoccupation->amc_id=1;
                $amcoccupation->amc_occupation_id=$amc_occupation->occupoationCode;
                $amcoccupation->amc_occupation_name=$amc_occupation->occupoationName;
                $amcoccupation->save();
            }
        }
        if($type=="income_sources" || $type=="all")
        {
            foreach($amc_income_sources as $amc_income_source)
            {
                $amc_income=AmcSourceofIncome::where('amc_source_of_income_id',$amc_income_source->incomeSourceId)->where('amc_id',1)->first();
                if(!isset($amc_income))
                {
                $amc_income=new AmcSourceofIncome();
                }
                $amc_income->amc_id=1;
                $amc_income->amc_source_of_income_id=$amc_income_source->incomeSourceId;
                $amc_income->amc_income_name=$amc_income_source->incomeSourceName;
                $amc_income->save();
            }
        }
        if($type=="banks" || $type=="all")
        {
            foreach($amc_banks as $amc_bank)
            {
                $amcbank=AmcBank::where('amc_bank_id',$amc_bank->bankCode)->where('amc_id',1)->first();
                if(!isset($amcbank))
                {
                $amcbank=new AmcBank();
                }
                $amcbank->amc_id=1;
                $amcbank->amc_bank_id=$amc_bank->bankCode;
                $amcbank->amc_bank_name=$amc_bank->bankName;
                $amcbank->save();
            }
        }
        if($type=="cities" || $type=="all")
        {
            foreach($amc_cities as $amc_city)
            {
            $amccity=AmcCity::where('amc_city_code',$amc_city->cityCode)->where('amc_id',1)->first();
            if(!isset($amccity))
            {
            $amccity=new Amccity();
            }
            $amccity->amc_id=1;
            $amccity->amc_city_code=$amc_city->cityCode;
            $amccity->amc_country_name='Pakistan';
            $amccity->amc_city_name=$amc_city->cityName;
            $amccity->save();
            }
        }
    }
}
