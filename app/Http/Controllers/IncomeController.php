<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\SourcesofIncome;
use Illuminate\Support\Facades\Validator;


class IncomeController extends Controller
{
    public function index()
    {
        return view('income_sources.index');
    }
    public function show(Request $request)
    {
        $income_sources=SourcesofIncome::all();
        return DataTables::of($this->filter($request, $income_sources))->make(true);
    }
    public function autocomplete(Request $request)
    {
      try {
        $data = [];
        $queryTerm = $request->q;
        $income_sources =  SourcesofIncome::where('status', 1)->where('income_name', 'like', '%' . $queryTerm . '%')->get();
        foreach ($income_sources as $income_source) {
            $data[] = ['id' => $income_source->id, 'text' => $income_source->income_name];
        }
        return $data;
    } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
    }
    }
    public function store(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'income_source_name'        => 'required',
            'status'                 => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $income_source=new SourcesofIncome;
          $income_source->name=$request->income_source_name;
          $income_source->status=$request->status;
          $income_source->save();
          return response()->json(['success'=> true, 'message' => 'income_source Created successfully!']);
    }
    public function change_status(Request $request)
    {
        if(isset($request['income_source_id']) && isset($request['status']))
        {
        SourcesofIncome::where('id',$request['income_source_id'])->update(['status'=>$request['status']]);
        return response(["status"=>200]);
        }
        else
        return response(["error"=>"Error Occured"]);
    }
    public function update(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'income_source_name'        => 'required',
            'status'                 => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $income_source=SourcesofIncome::where('id',$request->incomesourceid)->first();
          $income_source->income_name=$request->income_source_name;
          $income_source->status=$request->status;
          $income_source->save();
          return response()->json(['success'=> true, 'message' => 'income_source Data Updated successfully!']);
    }
    public function filter(Request $request,$income_sources)
    {
        if (isset($request->name) && $request->name != "null") {
            $income_sources=$income_sources->where('income_name','like', '%' .$request->name. '%');
        }
        if (isset($request->status) && $request->status != "null") {
            $income_sources=$income_sources->where('status',$request->status);
        }
    try{
        return $income_sources;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
