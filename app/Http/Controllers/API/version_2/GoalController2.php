<?php

namespace App\Http\Controllers\API\version_2;

use App\Models\Goal;
use App\Models\CustomerGoal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;


class GoalController2 extends Controller
{
    public function getCategories()
    {
        $goal = Goal::where('status', 1)->get();
        $category = [];
        foreach ($goal as $key => $value) {
            $category[$key]['label'] = $value['name'];
            $category[$key]['value'] = $value['id'];
        }
        return response()->json([
            'data'  => $category,
            'status' => true,
        ], 200);
    }

    public function save(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'       => 'required',
                // 'goal_id'    => 'required',
                'goal_id'    => 'required|unique:customer_goals,goal_id,NULL,id,user_id,'.$request->user_id,
                'date'       => 'required',
                'goal_price' => 'required',
            ], [
                'goal_id.required' => 'The category field is required.',
                'goal_id.unique' => 'Category already exist.',
            ]);

          if ($validator->fails()) {
              return response()->json(['errors' => $validator->errors()], 422);
          }
          CustomerGoal::insertGetId($request->all());
          return response()->json([
            'status' => true,
        ], 200);

        } catch (Exception $e) {
            echo "string";
        }
    }

    public function getAllGoals(Request $request)
    {
        $CustomerGoal = CustomerGoal::where('user_id', $request->user_id)->with('goal')->get();
        $goals = [];
        foreach ($CustomerGoal as $key => $value) {
            $goals[$key]['id']     = $value['id'];
            $goals[$key]['name']     = $value['name'];
            $goals[$key]['amount']   = $value['goal_price'];
            $goals[$key]['image']    = $value['goal']['logo'];
            $goals[$key]['category'] = $value['goal']['name'];
            $goals[$key]['created_at'] = $value['created_at']->format('d/m/y');
        }
        return response()->json([
            'status' => true,
            'data'  => $goals
        ], 200);
    } 

}