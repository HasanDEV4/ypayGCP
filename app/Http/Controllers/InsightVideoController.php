<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use App\Models\InsightCategory;
use App\Models\InsightTag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use function App\Libraries\Helpers\s3ImageUpload;

class InsightVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $video = Insight::where('category_id',2)->where('status',1)->count();
      $is_allowed = Insight::where('is_allowed',1)->count();
        return view('insight.video',compact('video','is_allowed'));
    }

  public function getData(Request $request)
  {
   
    $insight = Insight::where('type',1)->with('insight_tag');

    return DataTables::of($this->filter($request,$insight))->order(function ($q) use ($request) {
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

   

    public function saveVideo(Request $request){


      try {
        $validator = Validator::make($request->all(), [
            'title'    => 'required|max:100',
            // 'text'     => 'required|max:50',
            'order_no' => 'required',
            'video_thumbnail' => 'required|image',
            'tag'      => 'required',
            'url'      => 'required',
            'status'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $file             = $request->file('video_thumbnail');
        $fileOriginalName = $file->getClientOriginalName();
        $extension        = $file->getClientOriginalExtension();
        $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        // $filename         = $file->storeAs('public/uploads', $fileNameToStore);
        $path               = "insights_video/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $insight              = new Insight();
        $insight->category_id = null;
        $insight->type        = 1;
        $insight->title       = $request->title;
        $insight->text        = null;
        $insight->tag_id      = $request->tag;
        $insight->order_no    = $request->order_no;
        $insight->url         = $request->url;
        $insight->status      = $request->status;
        $insight->user_id     = auth()->user()->id;
        $insight->logo          = $filename;
        if($request->is_allowed != null){
          $insight->is_allowed  = $request->is_allowed;
        }else{
          $insight->is_allowed  = 0;
        }
        $insight->save();
        return response()->json(['success'=> true, 'message' => 'Video Created successfully!']);
        
        
        

        } catch (\Exception $e) {
          echo '<pre>'; print_r($e->getMessage()); echo '</pre>';

            return ['error' => 'Something went wrong'];
        }
    }


    public function updateVideo(Request $request, Insight $insight)
    {

      $validator = Validator::make($request->all(), [
        // 'category' => 'required',
        'title'    => 'required|max:100',
        // 'text'     => 'required|max:20',
        'order_no' => 'required',
        'video_thumbnail'     => 'sometimes|image',
        'tag'      => 'required',
        'url'      => 'required',
        'status'   => 'required'
    ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

      

          $insight->category_id = null;
          $insight->type        = 1;
          $insight->title       = $request->title;
          $insight->text        = null;
          $insight->order_no    = $request->order_no;
          $insight->tag_id      = $request->tag;
          $insight->url         = $request->url;
          $insight->status      = $request->status;
          $insight->user_id     = auth()->user()->id;
          if($request->is_allowed != null){
            $insight->is_allowed  = $request->is_allowed;
          }else{
            $insight->is_allowed  = 0;
          }
          if ($request->file('video_thumbnail')) {
            $file                     = $request->file('video_thumbnail');
            $fileOriginalName         = $file->getClientOriginalName();
            $extension                = $file->getClientOriginalExtension();
            $fileNameToStore          = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
            // $filename                 = $file->storeAs('/public/uploads', $fileNameToStore);
            $path               = "insight_video/".$fileNameToStore;
            $filename           = s3ImageUpload($file, $path);
            $insight->logo = $filename;
          }
          $insight->save();
        return response()->json(['success'=> true, 'message' => 'Video updated successfully!']);
        
    }

    public function getFields()
    {
      $insight_category = InsightCategory::where('status', 1)->get();
      $insight_tag      = InsightTag::where('status', 1)->get();

      return response()->json(['insight_category' => $insight_category,  'insight_tag' => $insight_tag]);
    }

    public function filter($request, $insight)
    {
      try {
  
        if (isset($request->title)) {
        
          $insight = $insight->where('title', 'like', '%' . $request->title . '%' ); 
            
        }

        
        if (isset($request->tag)) {
  
          $tag = $request->tag;
          $insight = $insight->whereHas('insight_tag', function($q) use($tag) {
            $q->where('name', 'like', '%' . $tag . '%' );
          });
      }

      if (isset($request->from)) {
        $insight = $insight->whereDate('created_at', '>=', Carbon::parse($request->from));
      }


      if (isset($request->to)) {
        $insight = $insight->whereDate('created_at', '<=', Carbon::parse($request->to));
      }

        if (isset($request->status)) {
          $insight = $insight->where('status',$request->status);
        }
  
        return $insight;
      } catch (\Exception $e) {
        return ['error' => 'Something went wrong'];
      }
    }

    public function getSpecificData($id)
    {
      // dd($id);
      $specificData = Insight::where('id',$id)->first();

      return response()->json(['specificData' => $specificData]);
    }
}
