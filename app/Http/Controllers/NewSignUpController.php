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
use Auth;
use File;
use DB;

class NewSignUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer.newSignUp');
    }
    public function export(Request $request)
    {
      $selected_signups=$request['selected_signups'];
      foreach($selected_signups as $signup)
      {
        $signup= User::select('users.id as id','users.status as status','users.app_version as app_version','users.platform as platform','users.full_name as full_name','users.email as email','cust_account_details.status as cust_status',
        DB::raw('DATE_FORMAT(users.created_at,"%Y-%m-%d %r") as registered_on'),
        'users.phone_no as phone_no',
        'users.updated_at as updated_at','users.refer_code as refer_code','cust_account_details.risk_profile_status as risk_profile_status')
        ->leftjoin('cust_account_details','cust_account_details.user_id','users.id')
        ->where('type',2)->where('cust_account_details.status',-1)->where('users.id',$signup)->first();
        $filtered_users[]=$signup;
      }
      $statuses = array("0"=>"Pending", "1"=>"Approved", "2"=>"Rejected", "3"=>"On Hold", "-1"=> "No Profile");
      // $users = User::select('users.id as id','users.status as status','users.app_version as app_version','users.platform as platform','users.full_name as full_name','users.email as email','cust_account_details.status as cust_status',
      // DB::raw('DATE_FORMAT(users.created_at,"%Y-%m-%d %r") as registered_on'),
      // 'users.phone_no as phone_no',
      // 'users.updated_at as updated_at','users.refer_code as refer_code','cust_account_details.risk_profile_status as risk_profile_status')
      // ->leftjoin('cust_account_details','cust_account_details.user_id','users.id')
      // ->where('type',2)->where('cust_account_details.status',-1);
      // $filtered_users=$this->filter($request,$users)->get();
      $timestamp=Carbon::now()->timestamp;
      $file_name=$timestamp.".csv";
      if(!file_exists('storage/uploads/signups'))
      {
        mkdir('storage/uploads/signups',0777,true);
        chmod('storage/uploads/signups', 0777);
      }
      $users_csv = fopen('storage/uploads/signups/'.$file_name,"a");
      chmod('storage/uploads/signups/'.$file_name, 0777);
      $heading=array("Name","Phone Number","Profile Status","Registered Date","Email");
      fputcsv($users_csv, $heading); 
      foreach($filtered_users as $user)
      {
        if (substr($user->phone_no, 0, 3) != "+92")
          $phone_no = (string) "+92".substr($user->phone_no, 1);
        else
          $phone_no = (string) $user->phone_no;
        $phone_no = preg_replace('/\s+/', '', $phone_no);
        if(count($user->cust_investment)==0)
        {
        $user_data=array(
          $user->full_name??'',
          $phone_no,
          $statuses[$user->cust_account_detail->status]??'',
          $user->registered_on??'',
          $user->email??'',
          );
          $users_csv = fopen('storage/uploads/signups/'.$file_name,"a");
          chmod('storage/uploads/signups/'.$file_name, 0777);
          fputcsv($users_csv, $user_data);
        }
      }
      $user_csv[$file_name]=file_get_contents('storage/uploads/signups/'.$file_name);
      File::delete('storage/uploads/signups/'.$file_name);
      return response()->json(['success'=> true, 'message' => 'Data Sent successfully!',"user_csv"=>$user_csv]);
    }
    public function getData(Request $request)
    {
      $users = User::select('users.id as id','users.status as status','users.app_version as app_version','users.platform as platform','users.full_name as full_name','users.email as email','cust_account_details.status as cust_status',
      DB::raw('DATE_FORMAT(users.created_at,"%Y-%m-%d %r") as registered_on'),
      'users.phone_no as phone_no',
      'users.updated_at as updated_at','users.refer_code as refer_code','cust_account_details.risk_profile_status as risk_profile_status')
      ->leftjoin('cust_account_details','cust_account_details.user_id','users.id')
      ->where('type',2)->where('cust_account_details.status',-1);
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
        //
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
    public function autocomplete(Request $request)
    {
        try {
          $data = [];
          $queryTerm = $request->q;
          $customers = User::whereHas('cust_account_detail', function ($q){ 
            $q->where('status',-1);
          })->where('type',2)->where('full_name', 'like', '%' . $queryTerm . '%')->get();
          foreach ($customers as $customer) {
              $data[] = ['id' => $customer->id, 'text' => $customer->full_name];
          }
          return $data;
      } catch (\Exception $e) {
          return ['error' => 'Something went wrong'];
      }
    }
    public function update(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
          ]);
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
          $user=User::where("id",$user_id)->update(['status'=>$request->status]);
          return response()->json(['success'=> true, 'message' => 'User updated successfully!']);
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

    public function filter($request, $users)
    {
        try {

            if (isset($request->customerId) && $request->customerId!='null') {
              $users = $users->where('users.id',$request->customerId);
            }
            if (isset($request->platform) && $request->platform!='null') {
              $users = $users->where('users.platform','like', '%' .$request->platform. '%');
            }
            if (isset($request->phone_no) && $request->phone_no!='null') {
              $users = $users->where('users.phone_no','like','%'.$request->phone_no.'%');
            }


            if (isset($request->from) && isset($request->to) && $request->from!= "null" && $request->to!= "null") {
              $users = $users->whereBetween('users.created_at',[[date("Y-m-d H:i:s", strtotime($request->from)),date("Y-m-d H:i:s", strtotime($request->to))]]);
            }
            if (isset($request->app_version) && $request->app_version!='null') {
              $users = $users->where('users.app_version','like', '%' .$request->app_version. '%');
            }

            // if (isset($request->to) && $request->to!='null') {
            //     $users = $users->whereBetween('users.created_at', '<=', Carbon::parse($request->to));
            // }

            if (isset($request->status) && $request->status!='null') {
                $users = $users->where('users.status', $request->status);
            }

            if (isset($request->email) && $request->email!='null') {
              $users = $users->where('users.email', 'like', '%' . $request->email . '%');
             }

            if (isset($request->contact) && $request->contact!='null') {
            $users = $users->where('users.phone_no', 'like', '%' . $request->contact . '%');
           }
            return $users;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
}
