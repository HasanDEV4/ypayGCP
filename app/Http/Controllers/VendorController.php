<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\OTPVendor;
use Illuminate\Support\Facades\Validator;


class VendorController extends Controller
{
    public function index()
    {
        return view('vendors.index');
    }
    public function show(Request $request)
    {
        $vendors=OTPVendor::all();
        return DataTables::of($this->filter($request, $vendors))->make(true);
    }
    public function store(Request $request)
    {
        return response()->json(['success'=> true, 'message' => 'Vendor created successfully!']);
    }
    public function activatewhatsapp(Request $request)
    {
      if(isset($request['vendor_id']) && isset($request['status']))
      {
        $otp_vendor=OTPVendor::where('whatsapp_active',1)->first();
        if(isset($otp_vendor))
        {
        $otp_vendor->whatsapp_active=0;
        $otp_vendor->save();
        }
        OTPVendor::where('id',$request['vendor_id'])->update(['whatsapp_active'=>$request['status']]);
        return response()->json(['success'=> true, 'message' => 'WhatsApp Activated successfully!']);
      }
      else
        return response(["error"=>"Error Occured"]);
    }
    public function activatesms(Request $request)
    {
      if(isset($request['vendor_id']) && isset($request['status']))
      {
        $otp_vendor=OTPVendor::where('sms_active',1)->first();
        if(isset($otp_vendor))
        {
        $otp_vendor->sms_active=0;
        $otp_vendor->save();
        }
        OTPVendor::where('id',$request['vendor_id'])->update(['sms_active'=>$request['status']]);
        return response()->json(['success'=> true, 'message' => 'SMS Activated successfully!']);
      }
      else
        return response(["error"=>"Error Occured"]);
    }
    public function update(Request $request,$account_type_id)
    {
        $validator = Validator::make($request->all(), [
            'max_investment_amount'     => 'required',
        ],
        [
          'max_investment_amount.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $account_type=AccountType::where('id',$account_type_id)->first();
        $account_type->max_investment_amount =$request->max_investment_amount;
        $account_type->save();
        return response()->json(['success'=> true, 'message' => 'Account Type Data Updated successfully!']);
    }


    public function filter(Request $request,$vendors)
    {
    try{
        return $vendors;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
