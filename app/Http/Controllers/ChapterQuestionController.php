<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AcademyChapter;
use App\Models\AcademyOption;
use App\Models\ChapterQuestions;
use Illuminate\Support\Facades\Validator;
use function App\Libraries\Helpers\s3ImageUpload;


class ChapterQuestionController extends Controller
{
    public function index()
    {
        $academy_chapters=AcademyChapter::all();
        return view('chapter_questions.index',compact('academy_chapters'));
    }
    public function show(Request $request)
    {
        $chapter_questions=ChapterQuestions::with('chapter');
        return DataTables::of($this->filter($request, $chapter_questions))->make(true);
    }
    public function get_question_options($question_id){
        $ac_options = AcademyOption::with('question.chapter')->where('question_id',$question_id)->get();
        if(isset($ac_options))
        {
          return $ac_options;
        }
    }
    public function option_delete(Request $request)
    {
        AcademyOption::where('id',$request->option_id)->delete();
        $ac_options = AcademyOption::with('question.chapter')->where('question_id',$request->question_id)->get();
        if(isset($ac_options))
        {
          return $ac_options;
        }
        return response()->json(['success'=> true, 'message' => 'Academy Question Deleted successfully!']);
    }
    public function delete(Request $request)
    {
        AcademyOption::where('question_id',$request->question_id)->delete();
        ChapterQuestions::where('id',$request->question_id)->delete();
        return response()->json(['success'=> true, 'message' => 'Academy Question Deleted successfully!']);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question'     => 'required',
            'chapter_id'     => 'required',
            'image'     => 'required',
            'options*'  => 'required',
            'description'     => 'required',
        ],
        [
            'question.required' => 'This field is required',
            'chapter_id.required' => 'This field is required',
            'image.required' => 'This field is required',
            'options*'  => 'required',
            'description.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
        $file             = $request->file('image');
        $fileOriginalName = $file->getClientOriginalName();
        $extension        = $file->getClientOriginalExtension();
        $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "chapter_questions/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $academy_question=new ChapterQuestions;
        $academy_question->question=$request->question;
        $academy_question->chapter_id=$request->chapter_id;
        $academy_question->description=$request->description;
        $academy_question->image=$filename;
        $academy_question->save();
        if(isset($request->options))
        {
            foreach($request->options as $key=>$option)
            {
                $academy_option=new AcademyOption;
                $academy_option->option_name=$option;
                $index=$key+1;
                $academy_option->question_id=$academy_question->id;
                $academy_option->is_correct=$request['is_correct_'.$index];
                $academy_option->save();
            }
        }
        return response()->json(['success'=> true, 'message' => 'Academy Question created successfully!']);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question'     => 'required',
            'chapter_id'     => 'required',
            'description'     => 'required',
        ],
        [
            'question.required' => 'This field is required',
            'chapter_id.required' => 'This field is required',
            'description.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
        if($request->hasFile('image'))
        {
            $file             = $request->file('image');
            $fileOriginalName = $file->getClientOriginalName();
            $extension        = $file->getClientOriginalExtension();
            $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
            $path               = "chapter_questions/".$fileNameToStore;
            $filename           = s3ImageUpload($file, $path);
        }
        $academy_question=ChapterQuestions::where('id',$request->question_id)->first();
        $academy_question->question=$request->question;
        $academy_question->chapter_id=$request->chapter_id;
        $academy_question->description=$request->description;
        if(isset($filename))
        $academy_question->image=$filename;
        $academy_question->save();
        if(isset($request->options))
        {
            foreach($request->options as $key=>$option)
            {
                $academy_option=AcademyOption::where('id',$request['option_id_'.$key])->first();
                $academy_option->option_name=$option;
                $academy_option->question_id=$academy_question->id;
                $academy_option->is_correct=$request['is_correct_'.$key];
                $academy_option->save();
            }
        }
        return response()->json(['success'=> true, 'message' => 'Academy Question Updated successfully!']);
    }


    public function filter(Request $request,$chapter_questions)
    {
        try{
            return $chapter_questions;
        } catch (\Exception $e) {
            echo "<pre>";
            print_r($e);
            echo "</pre>";
            return ['error' => 'Something went wrong'];
        }
    }
}
