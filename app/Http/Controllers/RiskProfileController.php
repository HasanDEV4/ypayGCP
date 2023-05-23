<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\RiskProfile;
use Illuminate\Support\Facades\Validator;


class RiskProfileController extends Controller
{
    public function index()
    {
        return view('risk_profile.index');
    }
    public function show(Request $request)
    {
        $risk_profiles=RiskProfile::all();
        return DataTables::of($this->filter($request, $risk_profiles))->make(true);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'risk_profile_type'         => 'required',
            'min_transaction_amount'    => 'required',
            'max_transaction_amount'    => 'required',
            'max_investment_amount'     => 'required'
        ],
        [
          'risk_profile_type.required' => 'This field is required',
          'min_transaction_amount.required' => 'This field is required',
          'max_transaction_amount.required' => 'This field is required',
          'max_investment_amount.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $risk_profile=new RiskProfile();
        $risk_profile->type=$request->risk_profile_type;
        $risk_profile->min_transaction_amount=$request->min_transaction_amount;
        $risk_profile->max_transaction_amount=$request->max_transaction_amount;
        $risk_profile->max_investment_amount=$request->max_investment_amount;
        $risk_profile->save();

        return response()->json(['success'=> true, 'message' => 'Risk Profile created successfully!']);
    }
    public function update(Request $request,$risk_type_id)
    {
        $validator = Validator::make($request->all(), [
            // 'risk_profile_type'         => 'required',
            'min_transaction_amount'    => 'required',
            'max_transaction_amount'    => 'required',
            'max_investment_amount'     => 'required',
            'risk_profile'     => 'required',
        ],
        [
          'risk_profile_type.required' => 'This field is required',
          'risk_profile.required' => 'This field is required',
          'min_transaction_amount.required' => 'This field is required',
          'max_transaction_amount.required' => 'This field is required',
          'max_investment_amount.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $risk_profile=RiskProfile::where('id',$risk_type_id)->first();
        //$risk_profile->type=$request->risk_profile_type;
        $risk_profile->min_transaction_amount=$request->min_transaction_amount;
        $risk_profile->max_transaction_amount=$request->max_transaction_amount;
        $risk_profile->max_investment_amount =$request->max_investment_amount;
        $risk_profile->risk_profile =$request->risk_profile;
        $risk_profile->save();

        return response()->json(['success'=> true, 'message' => 'Risk Profile Data Updated successfully!']);
    }


    public function filter(Request $request,$risk_profiles)
    {
    try{
        if(isset($request->risk_profile_type) && $request->risk_profile_type!="null")
        $risk_profiles=$risk_profiles->where('type','like', '%' .$request->risk_profile_type. '%');

        if(isset($request->min_transaction_amount) && $request->min_transaction_amount!="null")
        $risk_profiles=$risk_profiles->where('min_transaction_amount','like', '%' .$request->min_transaction_amount. '%');

        if(isset($request->max_transaction_amount) && $request->max_transaction_amount!="null")
        $risk_profiles=$risk_profiles->where('max_transaction_amount','like', '%' .$request->max_transaction_amount. '%');

        if(isset($request->max_investment_amount) && $request->max_investment_amount!="null")
        $risk_profiles=$risk_profiles->where('max_investment_amount','like', '%' .$request->max_investment_amount. '%');

        return $risk_profiles;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
