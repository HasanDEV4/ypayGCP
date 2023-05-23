<?php

namespace App\Http\Controllers\API\version_4;

use App\Models\User;
use App\Models\Insight;
use App\Models\InsightCategory;
use App\Models\InsightTag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;


class InsightController4 extends Controller
{
	public function show(Request $request)
	{
		$insight     = Insight::with(['insight_tag' => function($q){
            $q->select('id', 'name', 'status');
        }],['insight_tag' => function($q){
            $q->select('id', 'name', 'status');
        }])->where('status', 1)->where('type', 2)->select('id','category_id','title','order_no','text','tag_id','url','logo','status','reading_time','is_allowed','type','user_id')->orderBy('order_no','asc')->get();

		$insight_tag = InsightTag::select('id', 'name', 'status')->get();
		$insight_video     = Insight::with(['insight_tag' => function($q){
            $q->select('id', 'name', 'status');
        }],['insight_tag' => function($q){
            $q->select('id', 'name', 'status');
        }])->where('status', 1)->where('type',1)->select('id','category_id','title','order_no','text','tag_id','url','logo','status','reading_time','is_allowed','type','user_id')->orderBy('order_no','asc')->get();
		// echo '<pre>'; print_r($insight->toArray()); echo '</pre>';
		// $data_finance = [];
		// $data = [];
		// $count = 0;
		// foreach ($insight as $key => $value) {
		// 	// echo '<pre>'; print_r($value['insight_category']->toArray()); echo '</pre>';
		// 	if(isset($value['insight_category'])){
		// 	foreach ($value['insight_category']->toArray() as $key2 => $value2) {
		// 		if($value['insight_category']['name'] == "Finance Eduction Portal"){
		// 			$data_finance[$value['insight_category']['name']][$count] = $value;
		// 			continue;
		// 		}
		// 		$data[$value['insight_category']['name']][$count] = $value;
		// 	}
		// 		$count++;
		// 	}
		// }
		return response()->json([
            'status' => true,
            'insight'  => $insight,
            'insight_tag'  => $insight_tag,
            'insight_video'  => $insight_video,
			// 'data_finance' => $data_finance
        ], 200);
	}

	public function filter(Request $request)
	{

		$insight = Insight::with('insight_tag')->where('status', 1);
		if($request->tag){
			// $insight_tag = InsightTag::where('id',$request->tag)->first();
			if($request->tag == 'video'){
				$insight = $insight->where('type',1)->orderBy('order_no','asc');
			}else if($request->tag == 'blog'){
				$insight = $insight->where('type',2)->orderBy('order_no','asc');
			}else{
				$insight = $insight->whereHas('insight_tag', function ($q) use ($request) {
					$q->where('id', $request->tag);
				})->orderBy('order_no','asc');
			}
		}
		$insight = $insight->get();
		// return $insight;
		// $data_finance = [];
		// $data = [];
		// $count = 0;
		// foreach ($insight as $key => $value) {
		// 	// echo '<pre>'; print_r($value['insight_category']->toArray()); echo '</pre>';
		// 	foreach ($value['insight_category']->toArray() as $key2 => $value2) {
		// 		if($value['insight_category']['name'] == "Finance Eduction Portal"){
		// 			$data_finance[$value['insight_category']['name']][$count] = $value;
		// 			continue;
		// 		}
		// 		$data[$value['insight_category']['name']][$count] = $value;
		// 	}
		// $count++;
		// }
		return response()->json([
			'status' => true,
			'insight'  => $insight,
            // 'data_finance'  => $data_finance,
		], 200);
	}
}