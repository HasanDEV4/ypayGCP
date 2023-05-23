<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Facades\Validator;


class CityController extends Controller
{
    public function index()
    {
        return view('cities.index');
    }
    public function show(Request $request)
    {
        $cities=City::all();
        return DataTables::of($this->filter($request, $cities))->make(true);
    }
    public function autocomplete(Request $request)
    {
      try {
        $data = [];
        $queryTerm = $request->q;
        $cities =  City::where('status', 1)->where('city', 'like', '%' . $queryTerm . '%')->get();
        foreach ($cities as $city) {
            $data[] = ['id' => $city->id, 'text' => $city->city];
        }
        return $data;
    } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
    }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_name'        => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $city=new City;
          $city->city=$request->city_name;
          $city->country="Pakistan";
          $city->status=$request->status;
          $city->save();
          return response()->json(['success'=> true, 'message' => 'City Created successfully!']);
    }
    public function change_status(Request $request)
    {
        if(isset($request['city_id']) && isset($request['status']))
        {
        City::where('id',$request['city_id'])->update(['status'=>$request['status']]);
        return response(["status"=>200]);
        }
        else
        return response(["error"=>"Error Occured"]);
    }
    public function update(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'city_name'        => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $city=City::where('id',$request->cityid)->first();
          $city->city=$request->city_name;
          $city->country=$request->country;
          $city->status=$request->status;
          $city->save();
          return response()->json(['success'=> true, 'message' => 'City Data Updated successfully!']);
    }
    public function filter(Request $request,$cities)
    {
        if (isset($request->name) && $request->name != "null") {
            $cities=$cities->where('city','like', '%' .$request->name. '%');
        }
        if (isset($request->status) && $request->status != "null") {
            $cities=$cities->where('status',$request->status);
        }
    try{
        return $cities;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
