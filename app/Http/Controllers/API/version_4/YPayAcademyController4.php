<?php

namespace App\Http\Controllers\API\version_4;

use App\Models\AcademyChapter;
use App\Models\UserAcademyProgress;
use App\Models\ChapterQuestions;
use App\Models\AcademyOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;


class YPayAcademyController4 extends Controller
{
    public function getChapters(Request $request)
    {
       $academy_chapters=AcademyChapter::all();
       foreach($academy_chapters as $academy_chapter)
       {
        $academy_chapter_progress=UserAcademyProgress::where('user_id',$request->user()->id)->where('chapter_id',$academy_chapter->id)->first();
        if(isset($academy_chapter_progress))
        $academy_chapter->progress=$academy_chapter_progress->progress;
        $academy_chapter->question_count=ChapterQuestions::where('chapter_id',$academy_chapter->id)->count(); 
      }

       return response()->json(['status' => true, 'academy_chapters' => $academy_chapters ], 200);
    }
    public function save_user_progress(Request $request)
    {
      $validator = Validator::make($request->all(), [
       'chapter_id' => 'required',
       'progress'   => 'required',
      ]);

      if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
      }
      $check_chapter=AcademyChapter::where('id',$request->chapter_id)->first();
      if(!isset($check_chapter))
      {
        return response()->json([
          'status' => 'error',
          'errors' => ['error' => 'Chapter Not Found']
        ], 422);
      }
      $user_academy_progress=UserAcademyProgress::where('user_id',$request->user()->id)->where('chapter_id',$request->chapter_id)->first();
      if(!isset($user_academy_progress))
      $user_academy_progress=new UserAcademyProgress;
      $user_academy_progress->user_id=$request->user()->id;
      $user_academy_progress->chapter_id=$request->chapter_id;
      if($user_academy_progress->progress<$request->progress)
      $user_academy_progress->progress=$request->progress;
      $user_academy_progress->save();
      return response()->json([ 'status' => true, 'message' => 'Progress Saved Successfully' ], 200);
    }
    public function get_ac_questions(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'chapter_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        }
        $questions=ChapterQuestions::with('chapter')->where('chapter_id',$request->chapter_id)->get();
       foreach($questions as $question)
       {
        $options=AcademyOption::where('question_id',$question->id)->get();
        $question->options=$options->toArray();
       }
       return response()->json(['status' => true,'questions'=> $questions], 200);
    }
    public function get_user_progress(Request $request)
    {
  // // $validator = Validator::make($request->all(), [
        // // 'chapter_id' => 'required',
        // // ]);
    
        // if ($validator->fails()) {
        //     return response()->json(['status' => 'error', 'errors' => $validator->getMessageBag()->toArray()], 422);
        // }
        $user_academy_progresses=UserAcademyProgress::where('user_id',$request->user()->id)->get();
        return response()->json([ 'status' => true, 'user_academy_progresses' => $user_academy_progresses ], 200);
    }
}