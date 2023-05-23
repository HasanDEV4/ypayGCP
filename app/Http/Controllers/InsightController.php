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
class InsightController extends Controller
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
      $insight_tags = InsightTag::get();
        return view('insight.index',compact('video','is_allowed','insight_tags'));
    }

  public function show(Request $request)
  {
   
    // $insight = Insight::with('insight_category', 'insight_tag');
    $insight = Insight::select(\DB::raw('insights.*,insight_categories.name AS category_name,insight_tags.name'))
    ->leftJoin('insight_categories','insight_categories.id','insights.category_id')
    ->leftJoin('insight_tags','insight_tags.id','insights.tag_id') 
   ->where('insights.type',2);

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {
          $validator = Validator::make($request->all(), [
              'category' => 'required',
              'title'    => 'required',
              'logo'     => 'required|image',
              'text'     => 'required',
              'tag'      => 'required',
              'url'      => 'required',
              'status'   => 'required',
          ]);

          if ($validator->fails()) {
              return response()->json(['error' => $validator->errors()]);
          }

          $file             = $request->file('logo');
          $fileOriginalName = $file->getClientOriginalName();
          $extension        = $file->getClientOriginalExtension();
          $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
          $filename         = $file->storeAs('public/uploads', $fileNameToStore);

          $insight              = new Insight();
          $insight->category_id = $request->category;
          $insight->title       = $request->title;
          $insight->text        = $request->text;
          $insight->tag_id      = $request->tag;
          $insight->url         = $request->url;
          $insight->status      = $request->status;
          $insight->user_id     = auth()->user()->id;
          $insight->logo        = '/storage/uploads/'.$fileNameToStore;
          $insight->save();
          return response()->json(['success'=> true, 'message' => 'Insight Created successfully!']);

      } catch (\Exception $e) {
        echo '<pre>'; print_r($e->getMessage()); echo '</pre>';

          return ['error' => 'Something went wrong'];
      }
    }

    public function saveBlog(Request $request){


      try {
        $validator = Validator::make($request->all(), [
            'title'    => 'required|max:100',
            'logo'     => 'required|image|max:2100',
            'text'     => 'required',
            'tag'      => 'required',
            'author_name' => 'required',
            'reading_time' => 'required',
            'order_no' => 'required',
            // 'url'      => 'required',
            'status'   => 'required',
        ],[
          'order_no.required' => 'Order No is required',
          'logo.required' => 'Image is required',
          'logo.max'  => 'Image size should be less than 2mb'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

       

        $file             = $request->file('logo');
        $fileOriginalName = $file->getClientOriginalName();
        $extension        = $file->getClientOriginalExtension();
        $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $filename         = $file->storeAs('public/uploads', $fileNameToStore);

        $insight               = new Insight();
        $insight->category_id  = null;
        $insight->type         = 2;
        $insight->title        = $request->title;
        $insight->order_no        = $request->order_no;
        $insight->author_name  = $request->author_name;
        $insight->reading_time = $request->reading_time;
        $insight->text         = $request->text;
        $insight->tag_id       = $request->tag;
        $insight->status       = $request->status;
        $insight->url          = null;
        $insight->user_id      = auth()->user()->id;
        if($request->is_allowed != null){
          $insight->is_allowed  = $request->is_allowed;
        }else{
          $insight->is_allowed  = 0;
        }
        $insight->logo        = '/storage/uploads/'.$fileNameToStore;
        $insight->save();
        return response()->json(['success'=> true, 'message' => 'Blog Created successfully!']);

        } catch (\Exception $e) {
          echo '<pre>'; print_r($e->getMessage()); echo '</pre>';

            return ['error' => 'Something went wrong'];
        }
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Insight $insight)
    {

      $validator = Validator::make($request->all(), [
              'category' => 'required',
              'title'    => 'required',
              'logo'     => 'sometimes|image',
              'text'     => 'required',
              'tag'      => 'required',
              'order_no' => 'required',
              'url'      => 'required',
              'status'   => 'required'
          ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()]);
      }

        $insight->category_id = $request->category;
        $insight->title       = $request->title;
        $insight->text        = $request->text;
        $insight->tag_id      = $request->tag;
        $insight->order_no    = $request->order_no;
        $insight->url         = $request->url;
        $insight->status      = $request->status;
        $insight->user_id     = auth()->user()->id;
      if ($request->file('logo')) {
        $file               = $request->file('logo');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $filename           = $file->storeAs('/public/uploads', $fileNameToStore);
        $insight->logo          = '/storage/uploads/'.$fileNameToStore;
      }
      $insight->save();
      return response()->json(['success'=> true, 'message' => 'Insight updated successfully!']);
    }

    public function updateBlog(Request $request, Insight $insight)
    {

      $validator = Validator::make($request->all(), [
        'title'    => 'required|max:100',
        'logo'     => 'sometimes|image|max:2100',
        'text'     => 'required',
        'tag'      => 'required',
        'order_no' => 'required',
        'author_name' => 'required',
        'reading_time' => 'required',
        'status'   => 'required'
    ],[
      'logo.required' => 'Image is required',
      'order_no.required' => 'Order No is required',
      'logo.max'  => 'Image size should be less than 2mb'
    ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()]);
      }

        $insight->category_id = null;
        $insight->type        = 2;
        $insight->title       = $request->title;
        $insight->text        = $request->text;
        $insight->order_no        = $request->order_no;
        $insight->tag_id      = $request->tag;
        $insight->status      = $request->status;
        $insight->url         = null;
        $insight->author_name  = $request->author_name;
        $insight->reading_time = $request->reading_time;
        $insight->user_id     = auth()->user()->id;
        if($request->is_allowed != null){
          $insight->is_allowed  = $request->is_allowed;
        }else{
          $insight->is_allowed  = 0;
        }
      if ($request->file('logo')) {
        $file               = $request->file('logo');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        $path               = "insights/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        // $filename           = $file->storeAs('/public/uploads', $fileNameToStore);
        $insight->logo          = $filename;
      }
      $insight->save();
      return response()->json(['success'=> true, 'message' => 'Blog updated successfully!']);

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

        
        if (isset($request->category)) {
  
          $category = $request->category;
          $insight = $insight->whereHas('insight_category', function($q) use($category) {
            $q->where('name', 'like', '%' . $category . '%' );
          });
      }

      if (isset($request->from)) {
        $insight = $insight->whereDate('insights.created_at', '>=', Carbon::parse($request->from));
      }


      if (isset($request->to)) {
        $insight = $insight->whereDate('insights.created_at', '<=', Carbon::parse($request->to));
      }

        if (isset($request->status)) {
          $insight = $insight->where('insights.status',$request->status);
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
