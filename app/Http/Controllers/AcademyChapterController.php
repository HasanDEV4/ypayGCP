<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AcademyChapter;
use Illuminate\Support\Facades\Validator;
use function App\Libraries\Helpers\s3ImageUpload;


class AcademyChapterController extends Controller
{
    public function index()
    {
        return view('chapters.index');
    }
    public function show(Request $request)
    {
        $chapters=AcademyChapter::all();
        return DataTables::of($this->filter($request, $chapters))->make(true);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chapter_name'     => 'required',
            'chapter_image'     => 'required',
        ],
        [
          'chapter_name.required' => 'This field is required',
          'chapter_image.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
        $file             = $request->file('chapter_image');
        $fileOriginalName = $file->getClientOriginalName();
        $extension        = $file->getClientOriginalExtension();
        $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "chapters/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $academy_chapter=new AcademyChapter;
        $academy_chapter->name=$request->chapter_name;
        $academy_chapter->image=$filename;
        $academy_chapter->save();
        return response()->json(['success'=> true, 'message' => 'Academy Chapter created successfully!']);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chapter_name'     => 'required',
        ],
        [
          'chapter_name.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
          }
        if($request->hasFile('chapter_image'))
        {
            $file             = $request->file('chapter_image');
            $fileOriginalName = $file->getClientOriginalName();
            $extension        = $file->getClientOriginalExtension();
            $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
            $path               = "chapters/".$fileNameToStore;
            $filename           = s3ImageUpload($file, $path);
        }
        $academy_chapter=AcademyChapter::where('id',$request->chapter_id)->first();
        $academy_chapter->name=$request->chapter_name;
        if(isset($filename))
        $academy_chapter->image=$filename;
        $academy_chapter->save();
        return response()->json(['success'=> true, 'message' => 'Academy Chapter Updated successfully!']);
    }


    public function filter(Request $request,$chapters)
    {
        try{
            return $chapters;
        } catch (\Exception $e) {
            echo "<pre>";
            print_r($e);
            echo "</pre>";
            return ['error' => 'Something went wrong'];
        }
    }
}
