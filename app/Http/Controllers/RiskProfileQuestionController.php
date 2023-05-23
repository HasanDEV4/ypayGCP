<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\Options;
use Illuminate\Support\Facades\Validator;


class RiskProfileQuestionController extends Controller
{
    public function index()
    {
        $categories=QuestionCategory::all();
        return view('risk_profile_questions.index',compact('categories'));
    }
    public function autocomplete(Request $request)
    {
      try {
        $data = [];
        $queryTerm = $request->q;
        $question_categories =  QuestionCategory::where('name', 'like', '%' . $queryTerm . '%')->take(30)->get();;
        foreach ($question_categories as $category) {
            $data[] = ['id' => $category->id, 'text' => $category->name];
        }
        return $data;
    } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
    }
    }
    public function show(Request $request)
    {
        $risk_profile_calculation=Options::query()->with('question.category');
        return DataTables::of($this->filter($request, $risk_profile_calculation))->make(true);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question'  => 'required',
            'category_id'  => 'required',
            'weightage'  => 'required',
            'options*'  => 'required',
            'points*'  => 'required',
        ],
        [
            'question.required' => 'This field is required',
            'options*.required' => 'This field is required',
            'points*.required' => 'This field is required',
            'category_id.required' => 'This field is required',
            'weightage.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $question=new Question;
        $question->cat_id=$request->category_id;
        $question->question=$request->question;
        $question->weightage=$request->weightage;
        $question->save();
        $options=$request->options;
        $points=$request->points;
        foreach($options as $key=>$option)
        {
            $risk_profile_calculation=new Options;
            $risk_profile_calculation->_option=$option;
            $risk_profile_calculation->question_id=$question->id;
            $risk_profile_calculation->points=$points[$key];
            $risk_profile_calculation->save();
        }
        return response()->json(['success'=> true, 'message' => 'Question Created successfully!']);
    }
    public function update(Request $request,$risk_calculation_id)
    {
        $validator = Validator::make($request->all(), [
            'question'  => 'required',
            'category_id'  => 'required',
            'weightage'  => 'required',
            'options*'  => 'required',
            'points*'  => 'required',
        ],
        [
            'question.required' => 'This field is required',
            'options*.required' => 'This field is required',
            'points*.required' => 'This field is required',
            'category_id.required' => 'This field is required',
            'weightage.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $risk_profile_calculation=Options::where('id',$risk_calculation_id)->first();
        $risk_profile_calculation->_option=$request->option;
        $risk_profile_calculation->points=$request->points;
        $risk_profile_calculation->save();
        $risk_question_id=$risk_profile_calculation->question_id;
        $question=Question::where('id',$risk_question_id)->first();
        $question->question=$request->question;
        $question->cat_id=$request->category_id;
        $question->weightage=$request->weightage;
        $question->save();
        return response()->json(['success'=> true, 'message' => 'Risk Profile Question Updated successfully!']);
    }


    public function filter(Request $request,$risk_profile_calculation)
    {
    try{
        if (isset($request->created_at) && $request->created_at != "null") {
            $created_at=date('Y-m-d',strtotime($request->created_at));
            $risk_profile_calculation=$risk_profile_calculation->where('created_at','like',$created_at.'%');
        }
        return $risk_profile_calculation;
    } catch (\Exception $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
         return ['error' => 'Something went wrong'];
       }
    }
}
