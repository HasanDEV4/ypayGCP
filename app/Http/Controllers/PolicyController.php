<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class PolicyController extends Controller
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
            ->order(function ($q) use ($request) {
                if (count($request->order)) {
                    foreach ($request->order as $order) {
                        $column = @$request->columns[@$order['column']]['data'];
                        $dir = @$order['dir'];
                        if ($column && $dir) {
                            $q->orderBy($column, $dir);
                        }
                    }
                }
            })
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

    public function filter($request, $departments)
    {
        try {

            if (isset($request->department)) {
              $departments = $departments->where('name', 'like', '%' . $request->department . '%');
            }


            if (isset($request->from)) {
              $departments = $departments->whereDate('created_at', '>=', Carbon::parse($request->from) );
            }


            if (isset($request->to)) {
                $departments = $departments->whereDate('created_at', '<=', Carbon::parse($request->to));
            }

            if (isset($request->status)) {
                $departments = $departments->where('status', $request->status);
            }

            return $departments;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

}
