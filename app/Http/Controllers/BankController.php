<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Validator;


class BankController extends Controller
{
    public function index()
    {
        return view('banks.index');
    }
    public function show(Request $request)
    {
        $banks=Bank::all();
        return DataTables::of($this->filter($request, $banks))->make(true);
    }
    public function autocomplete(Request $request)
    {
      try {
        $data = [];
        $queryTerm = $request->q;
        $banks =  Bank::where('status', 1)->where('name', 'like', '%' . $queryTerm . '%')->get();
        foreach ($banks as $bank) {
            $data[] = ['id' => $bank->id, 'text' => $bank->name];
        }
        return $data;
    } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
    }
    }
    public function store(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'bank_name'        => 'required',
            'status'                 => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $bank=new Bank;
          $bank->name=$request->bank_name;
          $bank->status=$request->status;
          $bank->save();
          return response()->json(['success'=> true, 'message' => 'Bank Created successfully!']);
    }
    public function change_status(Request $request)
    {
        if(isset($request['bank_id']) && isset($request['status']))
        {
        Bank::where('id',$request['bank_id'])->update(['status'=>$request['status']]);
        return response(["status"=>200]);
        }
        else
        return response(["error"=>"Error Occured"]);
    }
    public function update(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'bank_name'        => 'required',
            'status'                 => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $Bank=Bank::where('id',$request->Bankid)->first();
          $Bank->name=$request->bank_name;
          $Bank->status=$request->status;
          $Bank->save();
          return response()->json(['success'=> true, 'message' => 'Bank Data Updated successfully!']);
    }
    public function filter(Request $request,$banks)
    {
        if (isset($request->name) && $request->name != "null") {
            $banks=$banks->where('name','like', '%' .$request->name. '%');
        }
        if (isset($request->status) && $request->status != "null") {
            $banks=$banks->where('status',$request->status);
        }
    try{
        return $banks;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
