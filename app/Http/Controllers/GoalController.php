<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('goal.index');
    }

  public function show(Request $request)
  {
    $goal = Goal::query();
    return DataTables::of($this->filter($request,$goal))->order(function ($q) use ($request) {
      if (count($request->order)) {
        foreach ($request->order as $order) {
          $column = @$request->columns[@$order['column']]['data'];
          $dir = @$order['dir'];
          if ($column && $dir) {
            $q->orderBy($column, $dir);
          }
        }
      }
    })->make(true);
  }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {

          $validator = Validator::make($request->all(), [
              'name'   => 'required|max:20',
              'logo'   => 'required|image',
              'status' => 'required',
          ]);
          if ($validator->fails()) {
            // return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
            return response()->json(['error' => $validator->errors()]);
          }
          $file             = $request->file('logo');
          $fileOriginalName = $file->getClientOriginalName();
          $extension        = $file->getClientOriginalExtension();
          $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
          $filename         = $file->storeAs('public/uploads', $fileNameToStore);

          $goal          = new Goal();
          $goal->name    = $request->name;
          $goal->logo     = '/storage/uploads/'.$fileNameToStore;
          $goal->status  = $request->status;
          $goal->user_id = auth()->user()->id;
          $goal->save();

          return response()->json(['success'=> true, 'message' => 'Goal Created successfully!']);

      } catch (\Exception $e) {
        echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
          return ['error' => true, 'message' => 'Something went wrong!'];
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $goal)
    {
      $validator = Validator::make($request->all(), [
              'name'   => 'required',
              'logo'   => 'sometimes|image',
              'status' => 'required',
          ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()/* ->all() */]);
      }

          $goal->name    = $request->name;
          if ($request->file('logo')) {
            $file               = $request->file('logo');
            $fileOriginalName   = $file->getClientOriginalName();
            $extension          = $file->getClientOriginalExtension();
            $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
            $filename           = $file->storeAs('/public/uploads', $fileNameToStore);
            $goal->logo          = '/storage/uploads/'.$fileNameToStore;
          }
          $goal->status  = $request->status;
          $goal->user_id = auth()->user()->id;
          $goal->save();

      return response()->json(['success'=> true, 'message' => 'Goal updated successfully!']);
    }

    public function filter($request, $goal)
    {
      try {
  
        if (isset($request->category)) {
        
          $goal = $goal->where('name', 'like', '%' . $request->category . '%' ); 
            
        }

        if (isset($request->status)) {
          $goal = $goal->where('status',$request->status);
        }
  
        return $goal;
      } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
      }
    }
}
