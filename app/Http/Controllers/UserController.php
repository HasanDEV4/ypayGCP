<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Exports\NewSignupExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Auth;

class UserController extends Controller
{

    // function __construct()
    // {
    //      $this->middleware('permission:user-list');
    //      $this->middleware('permission:user-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('users.index');
    }


    public function getData(Request $request)
    {
      $users = User::select('users.*','roles.name as role_name')
                    ->leftJoin('model_has_roles','model_has_roles.model_id','users.id')
                    ->leftJoin('roles','roles.id','model_has_roles.role_id')->where('admin',0)->where('type',1);
       return DataTables::of($this->filter($request, $users))->order(function ($q) use ($request) {
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

    public function create()
    {
      $roles = Role::pluck('name','name')->all();
      return view('users.add',compact('roles'));
    }

    public function store(Request $request){

      $validator = Validator::make($request->all(), [

        'full_name' => 'required',
        'email'     => 'required|email|unique:users,email',
        'password'  => 'required|min:8',
        'status'    => 'required',
        'role'      => 'required'
      
      ]);
      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }
      
        $user = new User();
        $user->full_name = $request->full_name;
        $user->email     = $request->email;
        $user->password  = Hash::make($request->password);
        $user->status    = $request->status;
        $user->type      = 1;
        $user->save();
        $user->assignRole($request->role);

        return response()->json(['success'=> true, 'message' => 'User Created Successfully!']);
    }

    public function edit($id){

      $user = User::where('id',$id)->first();
      $roles = Role::pluck('name','name')->all();
      $userRole = $user->roles->pluck('name')->first();
    
      return view('users.edit',compact('user','roles','userRole'));
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
          'full_name' => 'required',
          'email'     => 'required|email|unique:users,email,'.$id,
          'status'    => 'required',
          'role'      => 'required'
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $user = User::where('id',$id)->first();
          $user->full_name = $request->full_name;
          $user->email = $request->email;
          $user->status = $request->status;
          \DB::table('model_has_roles')->where('model_id',$id)->delete();
          $user->save();
          $user->assignRole($request->role);
          return response()->json(['success'=> true, 'message' => 'User updated successfully!']);
    }

    public function filter($request, $users)
    {
        try {

            if (isset($request->customerName)) {
              $users = $users->where('full_name', 'like', '%' . $request->customerName . '%');
            }


            if (isset($request->from)) {
              $users = $users->whereDate('created_at', '>=', Carbon::parse($request->from) );
            }


            if (isset($request->to)) {
                $users = $users->whereDate('created_at', '<=', Carbon::parse($request->to));
            }

            if (isset($request->status)) {
                $users = $users->where('status', $request->status);
            }

            if (isset($request->email)) {
              $users = $users->where('email', 'like', '%' . $request->email . '%');
             }

            if (isset($request->contact)) {
            $users = $users->where('phone_no', 'like', '%' . $request->contact . '%');
           }
            return $users;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function changePasswordView(){

      return view('users.changePassword');

    }

    public function changePassword(Request $request)
    {
      $validator = Validator::make($request->all(), [

        'current_password' => 'required',
        'password'         => 'required|same:confirm_password|min:8',
        'confirm_password' => 'required'
      
      ]);
      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }    

      $user = User::where('id',Auth::user()->id)->first();


      $current_password = $user->password;

      if(!Hash::check($request->current_password,$current_password))
      {
          return response()->json(['error1' => true, 'error' => 'Previous Password does not match!']);
      }

      $user->password = Hash::make($request->password);
      $user->save();

      return response()->json(['success'=> true, 'message' => 'Password Changed Successfully!']);

    }

    public function exportUser(Request $request){

      
      $myFile = Excel::raw(new NewSignupExport($request->id), \Maatwebsite\Excel\Excel::XLSX);

      $response =  array(
        'name' => "newSignup", //no extention needed
        'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
     );

     return response()->json(['success'=> true, 'message' => 'User Download successfully!','response' => $response]);
    }

}
