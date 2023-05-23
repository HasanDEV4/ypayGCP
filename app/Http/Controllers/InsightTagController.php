<?php

namespace App\Http\Controllers;

use App\Models\InsightTag;
use Illuminate\Http\Request;
use Insight;
use Yajra\DataTables\DataTables;
use Validator;

class InsightTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $insightTag = InsightTag::query();

        return DataTables::of($insightTag)->order(function ($q) use ($request) {
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'name'    => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
    
            $insightTag              = new InsightTag();
            $insightTag->name = $request->name;
            $insightTag->status = 1;
            $insightTag->save();
            return response()->json(['success'=> true, 'message' => 'Tag Created successfully!']);
    
            } catch (\Exception $e) {
              echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
    
                return ['error' => 'Something went wrong'];
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $insightTag = InsightTag::where('id',$id)->first();

        return response()->json(['insightTag' => $insightTag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InsightTag $insightTag)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
        ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
    
              $insightTag->name = $request->name;
              $insightTag->status = 1;
              $insightTag->save();
            return response()->json(['success'=> true, 'message' => 'Insight Tag updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $insightTag = InsightTag::where('id',$id)->first();
        $insightTag->delete();

        return response()->json(['success'=> true, 'message' => 'Insight Tag deleted successfully!']);
    }
}
