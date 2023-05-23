<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\RiskProfileRank;
use Illuminate\Support\Facades\Validator;


class RiskProfileRankController extends Controller
{
    public function index()
    {
        return view('risk_profile_ranks.index');
    }
    public function show(Request $request)
    {
        $risk_profile_ranks=RiskProfileRank::query();
        return DataTables::of($this->filter($request, $risk_profile_ranks))->make(true);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rank'  => 'required',
            'message'  => 'required',
            'start_range'  => 'required',
            'end_range'  => 'required',
            'risk_profile_status'  => 'required',
        ],
        [
            'rank.required' => 'This field is required',
            'message.required' => 'This field is required',
            'start_range.required' => 'This field is required',
            'end_range.required' => 'This field is required',
            'risk_profile_status.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $risk_profile_rank=new RiskProfileRank;
        $risk_profile_rank->rank=$request->rank;
        $risk_profile_rank->message=$request->message;
        $risk_profile_rank->start_range=$request->start_range;
        $risk_profile_rank->end_range=$request->end_range;
        $risk_profile_rank->risk_profile_status=$request->risk_profile_status;
        $risk_profile_rank->save();
        return response()->json(['success'=> true, 'message' => 'Risk Profile Rank Created successfully!']);
    }
    public function update(Request $request,$risk_rank_id)
    {
        $validator = Validator::make($request->all(), [
            'rank'  => 'required',
            'message'  => 'required',
            'start_range'  => 'required',
            'end_range'  => 'required',
            'risk_profile_status'  => 'required',
        ],
        [
            'rank.required' => 'This field is required',
            'message.required' => 'This field is required',
            'start_range.required' => 'This field is required',
            'end_range.required' => 'This field is required',
            'risk_profile_status.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $risk_profile_rank=RiskProfileRank::where('id',$risk_rank_id)->first();
        $risk_profile_rank->rank=$request->rank;
        $risk_profile_rank->message=$request->message;
        $risk_profile_rank->start_range=$request->start_range;
        $risk_profile_rank->end_range=$request->end_range;
        $risk_profile_rank->risk_profile_status=$request->risk_profile_status;
        $risk_profile_rank->save();
        return response()->json(['success'=> true, 'message' => 'Risk Profile Rank Updated successfully!']);
    }


    public function filter(Request $request,$risk_profile_rank)
    {
    try{
        if (isset($request->created_at) && $request->created_at != "null") {
            $created_at=date('Y-m-d',strtotime($request->created_at));
            $risk_profile_rank=$risk_profile_rank->where('created_at','like',$created_at.'%');
        }
        return $risk_profile_rank;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
