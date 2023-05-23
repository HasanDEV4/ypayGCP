<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AccountType;
use Illuminate\Support\Facades\Validator;


class AccountTypeController extends Controller
{
    public function index()
    {
        return view('account_types.index');
    }
    public function show(Request $request)
    {
        $account_types=AccountType::all();
        return DataTables::of($this->filter($request, $account_types))->make(true);
    }
    public function store(Request $request)
    {
        return response()->json(['success'=> true, 'message' => 'Account Type created successfully!']);
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


    public function filter(Request $request,$account_types)
    {
    try{

        if(isset($request->max_investment_amount) && $request->max_investment_amount!="null")
        $account_types=$account_types->where('max_investment_amount','like', '%' .$request->max_investment_amount. '%');

        return $account_types;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
