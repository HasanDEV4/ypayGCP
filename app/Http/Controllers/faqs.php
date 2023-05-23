<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('policies.index');
    }

    public function show(Request $request)
    {
      $policy = Policy::query();
      return DataTables::of($this->filter($request, $policy))
              ->addIndexColumn()
              ->make(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Policy $policy)
    {
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'description' => 'required',
      ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()/* ->all() */]);
      }
      
      $policy->name = $request->name;
      $policy->description = $request->description;
      $policy->save();

      return response()->json(['success'=> true, 'message' => 'Policy updated successfully!']);
    }

}
