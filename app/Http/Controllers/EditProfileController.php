<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustAccountDetail;
use App\Models\AdminComments;
use App\Models\ChangeRequest;
use App\Models\ChangeRequestStatus;
use App\Models\CustBasicDetail;
use App\Models\Amc;
use App\Models\CustBankDetail;
use App\Models\AmcCustProfile;
use App\Models\Bank;
use App\Models\AmcBank;
use App\Models\Investment;
use App\Models\Redemption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use function App\Libraries\Helpers\sendNotification;
use function App\Libraries\Helpers\sendEmail;
use Illuminate\Support\Facades\Validator;
use File;
use Config;
use PDF;
use DB;

class EditProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks=Bank::where('status',1)->get();
        $users=User::with('cust_cnic_detail')->whereHas('cust_account_detail', function ($q){ 
          $q->whereIn('status',[0,1]);
        })->where('type',2)->get();
        return view('edit_requests.index',compact('banks'));
    }
    public function export_profile(Request $request)
    {
      $user_id=$request->user_id;
      $amc_id=$request->amc_id;
      $change_request_id=$request->change_request_id;
      $change_request=ChangeRequest::where('id',$change_request_id)->first();
      $amc_profile=AmcCustProfile::where('user_id',$user_id)->where('amc_id',$amc_id)->first();
      $user = User::whereId($user_id)->with('cust_basic_detail.banks','cust_cnic_detail', 'cust_bank_detail', 'cust_account_detail')->first();
      $pdf = PDF::loadView('change_req_profile_pdf', compact('user','amc_profile','change_request'));
      $path = public_path();
      $fileName =  $user->cust_cnic_detail->cnic_number.'_'.time().'.pdf' ;
      $pdf->save($path . '/' . $fileName);
      $pdf = public_path($fileName);
      return response()->download($pdf);
    }
    public function change_request_status(Request $request,$profile_id)
    {
      return view('edit_requests.change_req_status_index',compact('profile_id'));
    }
    public function show(Request $request)
    {
      $changeRequests=ChangeRequest::with('user.cust_cnic_detail','bank');
      return DataTables::of($this->filter($request,$changeRequests))->make(true);
    }
    public function editprofileshow(Request $request)
    {
      $changerequeststatuses=ChangeRequestStatus::with('amc','change_request.user.cust_cnic_detail');
      return DataTables::of($this->changerequestfilter($request,$changerequeststatuses))->make(true);
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

      } catch (\Exception $e) {
        echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
          return ['error' => 'Something went wrong'];
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function change_request_status_update(Request $request)
    {
      $status=$request->status;
      $change_request_status=ChangeRequestStatus::where('id',$request->changestatusId)->first();
      $change_request_status->status=$status;
      $change_request_status->save();
      return response()->json(['success'=> true, 'message' => 'Status Updated successfully!']);
    }
     public function update(Request $request)
    {
      $status=$request->status;
      $changerequest=ChangeRequest::where('id',$request->profileId)->first();
      $changerequest->status=$status;
      $changerequest->iban=$request->iban;
      $changerequest->bank_id=$request->bank;
      $changerequest->bank_account_number=$request->account_number;
      $changerequest->branch=$request->branch;
      $changerequest->save();
      $user_id=$changerequest->user_id;
      if($status=="1")
      {
        $amc_bank_name=AmcBank::where('ypay_bank_id',$request->bank)->pluck('amc_bank_name');
        $cust_bank_detail=CustBankDetail::where('user_id',$user_id)->first();
        $cust_bank_detail->bank = $amc_bank_name[0];
        $cust_bank_detail->bank_account_number=$request->account_number;
        $cust_bank_detail->branch=$request->branch;
        $cust_bank_detail->iban=$request->iban;
        $cust_bank_detail->save();

        $cust_basic_detail=CustBasicDetail::where('user_id',$user_id)->first();
        $cust_basic_detail->bank=$request->bank;
        $cust_basic_detail->save();
      }
      return response()->json(['success'=> true, 'message' => 'Profile Data Updated successfully!']);
    }


    public function changerequestfilter($request, $users)
    {
      try {
        $users=$users->where('change_request_id',$request->profileId);
        return $users;

      } catch (\Exception $e) {
          return ['error' => 'Something went wrong'];
      }
    }
    public function filter($request, $users)
    {
        try {
            if (isset($request->customerId) && $request->customerId!='null') {
              $users = $users->where('users.id',$request->customerId);
            }
            if (isset($request->cnic) && $request->cnic!='null') {
                $cnic = $request->cnic;
                $users = $users->whereHas('cust_cnic_detail', function ($q) use ($cnic){
                  $q->where('cnic_number', 'like', '%' . $cnic . '%');
                });
               }
            


            if (isset($request->from) && $request->from!='null') {
                $dateFrom = $request->from;
                $users = $users->whereHas('cust_cnic_detail', function($q) use($dateFrom) {
                  $q->whereDate('created_at', '>=', Carbon::parse($dateFrom) );
                });
            }


            if (isset($request->to) && $request->to!='null') {
                $dateTo = $request->to;
                $users = $users->whereHas('cust_cnic_detail', function($q) use($dateTo) {
                  $q->whereDate('created_at', '<=', Carbon::parse($dateTo) );
                });
            }

            if(isset($request->status) && $request->status!='null')
            {
              $userStatus = $request->status;
              $users = $users->where('users.status',$userStatus);
            }
            if (isset($request->phone_no) && $request->phone_no!='null') {
              $users = $users->where('users.phone_no','like','%'.$request->phone_no.'%');
            }
            if (isset($request->profile_status) && $request->profile_status!='null') {
                $profileStatus = $request->profile_status;
                $users = $users->whereHas('cust_account_detail', function($q) use($profileStatus) {
                  $q->where('status', $profileStatus);
                });
            }
            if (isset($request->risk_profile_status) && $request->risk_profile_status!='null') {
              $risk_profile_status = $request->risk_profile_status;
              $users = $users->whereHas('cust_account_detail', function($q) use($risk_profile_status) {
                $q->where('risk_profile_status', $risk_profile_status);
              });
            }

            if (isset($request->email)) {
              $users = $users->where('email', 'like', '%' . $request->email . '%');
             }
            return $users;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

}
