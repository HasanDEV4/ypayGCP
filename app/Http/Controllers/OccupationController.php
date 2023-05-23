<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\Occupation;
use Illuminate\Support\Facades\Validator;


class OccupationController extends Controller
{
    public function index()
    {
        return view('occupations.index');
    }
    public function show(Request $request)
    {
        $occupations=Occupation::all();
        return DataTables::of($this->filter($request, $occupations))->make(true);
    }
    public function autocomplete(Request $request)
    {
      try {
        $data = [];
        $queryTerm = $request->q;
        $occupations =  Occupation::where('status', 1)->where('name', 'like', '%' . $queryTerm . '%')->get();
        foreach ($occupations as $occupation) {
            $data[] = ['id' => $occupation->id, 'text' => $occupation->name];
        }
        return $data;
    } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
    }
    }
    public function store(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'occupation_name'        => 'required',
            'status'                 => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $occupation=new Occupation;
          $occupation->name=$request->occupation_name;
          $occupation->status=$request->status;
          $occupation->save();
          return response()->json(['success'=> true, 'message' => 'occupation Created successfully!']);
          
    }
    public function change_status(Request $request)
    {
        if(isset($request['occupation_id']) && isset($request['status']))
        {
        occupation::where('id',$request['occupation_id'])->update(['status'=>$request['status']]);
        return response(["status"=>200]);
        }
        else
        return response(["error"=>"Error Occured"]);
    }
    public function update(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'occupation_name'        => 'required',
            'status'                 => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $occupation=Occupation::where('id',$request->occupationid)->first();
          $occupation->name=$request->occupation_name;
          $occupation->status=$request->status;
          $occupation->save();
          return response()->json(['success'=> true, 'message' => 'Occupation Data Updated successfully!']);
    }
    public function filter(Request $request,$occupations)
    {
        if (isset($request->name) && $request->name != "null") {
            $occupations=$occupations->where('name','like', '%' .$request->name. '%');
        }
        if (isset($request->status) && $request->status != "null") {
            $occupations=$occupations->where('status',$request->status);
        }
    try{
        return $occupations;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
