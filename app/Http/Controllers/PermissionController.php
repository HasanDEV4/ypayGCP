<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    // function __construct()
    // {
    //      $this->middleware('permission:permissions-list');
    //      $this->middleware('permission:permissions-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:permissions-edit', ['only' => ['edit','update']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permissions.index');

    }

    public function getData(Request $request)
    {
        $permissions = Permission::query();
  
       return DataTables::of($permissions)->order(function ($q) use ($request) {
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
                'name'   => 'required',
            ]);
            if ($validator->fails()) {
              // return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
              return response()->json(['error' => $validator->errors()]);
            }
  
            $permission              = new Permission();
            $permission->name        = $request->name;
            $permission->guard_name  = 'web';
            $permission->save();
  
            return response()->json(['success'=> true, 'message' => 'Permission Created successfully!']);
  
        } catch (\Exception $e) {
          echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
            return ['error' => true, 'message' => 'Something went wrong!'];
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            // 'guard_name' => 'required'
        ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()/* ->all() */]);
    }

        $permission             = Permission::where('id',$id)->first();
        $permission->name       = $request->name;
        $permission->guard_name = 'web';
        $permission->save();

    return response()->json(['success'=> true, 'message' => 'Permission updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
