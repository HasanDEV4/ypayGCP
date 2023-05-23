<?php

namespace App\Http\Controllers;

use App\Models\Amc;
use App\Models\Fund;
use App\Models\FundsAdditionalDetail;
use App\Models\FundsBankDetail;
use App\Models\FundData;
use App\Models\FundAsset;
use App\Models\FundAssetAllocation;
use App\Models\FundHolding;
use App\Models\RiskProfile;
use App\Models\Investment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use function App\Libraries\Helpers\s3ImageUpload;

use function App\Libraries\Helpers\generateAssetAllocation;
use Goutte\Client;
class FundController extends Controller
{
  private $fund_names=[];
  private $ytds=[];
  private $navs=[];
  private $old_count=1;
  private $new_count=11;
  // function __construct()
  //   {
  //        $this->middleware('permission:fund-list');
  //        $this->middleware('permission:fund-create', ['only' => ['addFund','saveFund']]);
  //        $this->middleware('permission:fund-edit', ['only' => ['editFund','saveFund']]);
  //   }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('funds.index');
  }
  public function funds_data_index()
  {
    $funds=Fund::whereHas('additional_details', function($q) {
      $q->where('status', 1);
    })->get();
    return view('funds.funds_data_index',compact('funds'));
  }

  public function show(Request $request)
  {
    // $funds = Fund::query()->with('amc', 'additional_details');
    $funds = Fund::query()->select(\DB::raw('funds.*','amcs.entity_name','funds_additional_details.status'))
    ->with('amc', 'additional_details')
    // ->with('investments', function ($q){
    //   $q->whereIn('status',[0,1]);
    // })
    ->withCount(['investments' => function($q){
      $q->whereIn('status',[0,1]);
    }])
    ->join('amcs','amcs.id','=','funds.amc_id')
    ->join('funds_additional_details','funds_additional_details.fund_id','=','funds.id');

    // $funds = Investment::selectRaw("funds.id,funds.fund_name,funds.fund_image,funds.created_at as created_at,amcs.entity_name as entity_name, COUNT('investments.*') as investment_count,funds_additional_details.status as status")
    // ->join('funds', 'funds.id', '=', 'investments.fund_id')
    // ->join('amcs', 'amcs.id', '=', 'funds.amc_id')
    // ->join('funds_additional_details','funds_additional_details.fund_id','funds.id')
    // ->groupBy('funds.id')
    // ->orderBy('investment_count', 'desc')
    // ->whereIn('investments.status',[0,1]);
    // ->get();

// dd($funds);
    return DataTables::of($this->filter($request, $funds))->order(function($q) use($request) {
      if (count($request->order)) {
        foreach ($request->order as $order) {
          $column = @$request->columns[@$order['column']]['name'];
          $dir = @$order['dir'];
          if ($column && $dir) {
            $q->orderBy($column, $dir);
          }
        }
      }
    })->make(true);
  }

  public function addFund()
  {
    $amc = Amc::where('status', 1)->pluck('entity_name', 'id');
    // $fundAssetAllocations = FundAssetAllocation::where('fund_id', 6)->get()->toArray();
    $assetAllocations = generateAssetAllocation(/* $fundAssetAllocations */);
    // dd($assetAllocations);
    $popular = Fund::where('is_popular',1)->count();
    $new = Fund::where('is_new',1)->count();
    $risk_profiles=RiskProfile::all();

    return view('funds.add', compact('amc', 'assetAllocations','popular','new','risk_profiles'));
  }

  public function editFund($id)
  {
    $amc = Amc::where('status', 1)->pluck('entity_name', 'id');
    $data = Fund::with('amc', 'additional_details', 'asset', 'asset_allocations', 'holdings', 'fund_bank')
    /*->whereHas('additional_details', function ($q) {
      $q->where('status', 1);
    })*/->find($id);

    // $nav = Nav::where('fund_id',$id)->latest()->first();

    if (!$data) {
      return redirect()->back();
    }
    // $asset = [];
    // foreach ($data['asset'] as $key => $value) {
    //   $asset[$key]['name']            =  $value['asset'];
    //   $asset[$key]['value']           =  json_decode($value['share_percent']);
    //   $asset[$key]['color']           =  $value['color'];
    // }
    // $holding = [];
    // foreach ($data['holdings'] as $key => $value) {
    //   $holding[$key]['name']  =  $value['type'];
    //   $holding[$key]['value'] =  $value['share_percent'];
    // }
    // $data['asset']    = $asset;
    // $data['holding'] = $holding;
    // $fundAssetAllocations = FundAssetAllocation::where('fund_id', 6)->get()->toArray();
    $assetAllocations = generateAssetAllocation($data['asset_allocations']);
    // dd($assetAllocations);
    // $popular = Fund::where('is_popular',1)->count();
    // $new = Fund::where('is_new',1)->count();
    $risk_profiles=RiskProfile::all();
    return view('funds.add', compact('amc', 'assetAllocations', 'data','risk_profiles'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function refreshfundsData()
  {
    $client=new Client();
    $url="https://www.mufap.com.pk/nav_returns_performance.php?tab=01";
    $page=$client->request('GET',$url);
    $page->filter('.fundname ')->each(function($item){
     if($item->text() != "* Fund Name")
     {
       $this->fund_names[]=$item->text();
     }
    });
     $page->filter('.boder_ps ')->each(function($item){
       $this->ytds[]=$item->text();
     });
     $page->filter('.border_ps')->each(function($item){
       $text= $item->text();
       $text=str_replace('(','',$text);
       $text=str_replace(')','',$text);
       $text=str_replace(',','',$text);
       if(is_numeric($text) || $text=="N/A")
       {
         if($this->old_count==1)
         $this->navs[]=$item->text();
         $this->old_count+=1;
         if($this->old_count==$this->new_count)
         {
           $this->navs[]=$item->text();
           $this->new_count+=9;
         }
       }
     });
      foreach($this->fund_names as $index=>$value)
      {
        $funds_data=FundData::with('ypay_fund')->where('fund_name',$value)->first();
        if(!isset($funds_data))
        $funds_data=new FundData;
        $funds_data->fund_name=$value;
        $funds_data->nav=$this->navs[$index];
        $this->ytds[$index]=str_replace('(','',$this->ytds[$index]);
        $this->ytds[$index]=str_replace(')','',$this->ytds[$index]);
        $funds_data->ytd=$this->ytds[$index];
        $funds_data->save();
      }
      $funds_data=FundData::all();
      $fund_ids=[];
      $funds=[];
      foreach($funds_data as $index=>$fund_data)
      {
        if(isset($fund_data->ypay_fund_id))
        {
          if(!in_array($fund_data->ypay_fund_id,$fund_ids))
          {
          $fund_ids[$index]['fund_id']=$fund_data->ypay_fund_id;
          $fund_ids[$index]['fund_data_nav']=$fund_data->nav;
          $fund_ids[$index]['fund_data_ytd']=$fund_data->ytd;
          }
        }
      }
      foreach($fund_ids as $fund_id)
      {
        $fund=Fund::where('id',$fund_id['fund_id'])->first();
        $fund->nav=$fund_id['fund_data_nav'];
        $fund->return_rate=$fund_id['fund_data_ytd'];
        $fund->save();
      }
     return response()->json(['success' => true, 'message' => 'Fund Data Refreshed Successfully!']);
  }
  public function update_funds_data()
  {
    $client=new Client();
    $url="https://www.mufap.com.pk/nav_returns_performance.php?tab=01";
    $page=$client->request('GET',$url);
    $page->filter('.fundname ')->each(function($item){
     if($item->text() != "* Fund Name")
     {
       $this->fund_names[]=$item->text();
     }
    });
     $page->filter('.boder_ps ')->each(function($item){
       $this->ytds[]=$item->text();
     });
     $page->filter('.border_ps')->each(function($item){
       $text= $item->text();
       $text=str_replace('(','',$text);
       $text=str_replace(')','',$text);
       $text=str_replace(',','',$text);
       if(is_numeric($text) || $text=="N/A")
       {
         if($this->old_count==1)
         $this->navs[]=$item->text();
         $this->old_count+=1;
         if($this->old_count==$this->new_count)
         {
           $this->navs[]=$item->text();
           $this->new_count+=9;
         }
       }
     });
      foreach($this->fund_names as $index=>$value)
      {
        $funds_data=FundData::with('ypay_fund')->where('fund_name',$value)->first();
        if(!isset($funds_data))
        $funds_data=new FundData;
        $funds_data->fund_name=$value;
        $funds_data->nav=$this->navs[$index];
        if (str_contains($this->ytds[$index], '(')) {
          $this->ytds[$index]=str_replace('(','',$this->ytds[$index]);
          $this->ytds[$index]=str_replace(')','',$this->ytds[$index]);
          $this->ytds[$index] = '-'.$this->ytds[$index];
        }
        $funds_data->ytd=$this->ytds[$index];
        $funds_data->save();
      }
      $funds_data=FundData::all();
      $fund_ids=[];
      $funds=[];
      foreach($funds_data as $index=>$fund_data)
      {
        if(isset($fund_data->ypay_fund_id))
        {
          if(!in_array($fund_data->ypay_fund_id,$fund_ids))
          {
          $fund_ids[$index]['fund_id']=$fund_data->ypay_fund_id;
          $fund_ids[$index]['fund_data_nav']=$fund_data->nav;
          $fund_ids[$index]['fund_data_ytd']=$fund_data->ytd;
          }
        }
      }
      foreach($fund_ids as $fund_id)
      {
        $fund=Fund::where('id',$fund_id['fund_id'])->first();
        $fund->nav=$fund_id['fund_data_nav'];
        $fund->return_rate=$fund_id['fund_data_ytd'];
        $fund->save();
      }
  }
  public function editFundData(Request $request)
  {
    $ypay_fund_id=$request->ypay_fund_id;
    $fund_data_id=$request->fund_data_id;
    $status=$request->status;
    $funds_data=FundData::with('ypay_fund')->where('id',$fund_data_id)->first();
    if(isset($funds_data) && isset($ypay_fund_id))
    {
      $ypay_fund=Fund::where('id',$ypay_fund_id)->first();
      $ypay_fund->nav=$funds_data->nav;
      $ypay_fund->return_rate=$funds_data->ytd;
      $ypay_fund->save();
      $funds_data->ypay_fund_id=$ypay_fund_id;
    }
    else if($ypay_fund_id=='')
    {
      $funds_data->ypay_fund_id=null;
    }
    $funds_data->status=$status;
    $funds_data->save();
    return response()->json(['success' => true, 'message' => 'Fund Data Updated Successfully!']);
  }
  public function getfundsData(Request $request)
  {
      $funds_data=FundData::with('ypay_fund');
      if(isset($request->fund_name) && $request->fund_name!="null")
      $funds_data=$funds_data->where('fund_name','like','%'.$request->fund_name.'%');
      if(isset($request->status) && $request->status!="null")
      $funds_data=$funds_data->where('status',$request->status);
      return DataTables::of($funds_data)->make(true);
  }
  public function saveFund(Request $request)
  {
    try {
   

        $validator = Validator::make($request->all(), [
          'fund_name'                        => 'required|max:100|unique:funds,fund_name,'.$request->id,
          'amc'                              => 'required',
          'fund_size'                        => 'required|max:15',
          //'fund_image'                       => 'image',Rule::requiredIf($request->id == null),
          'nav'                              => 'required|max:15|between:0,99.99',
          'return_rate'                      => 'required|max:7|between:0,99.99',
          'objective'                        => 'required',
          'type'                             => 'required|max:30',
          'category'                         => 'required',
          'fund_ratings'                     => 'required',
          'amc_reference_number'             => 'required',
          'online_payment'                   => 'required',
          'min_transaction_amount'           => 'required',
          'max_transaction_amount'           => 'required',
          'profile_risk'                     => 'required',
          'benchmark'                        => 'required|max:20',
          'bank_name'                        => 'required|max:50',
          'account_title'                    => 'required|max:25',
          'iban_number'                      => 'required|max:25',
          'status'                           => 'required',
          'asset.*.asset'                    => 'required',
          'asset.*.share_percent'            => 'required',
          'asset.*.color'                    => 'required',
          'holding.*.type'                   => 'required',
          'holding.*.share_percent'          => 'required',
          'return.*.return'                  => 'required',
          'return.*.peer_average'            => 'required',
          // 'return.*.rank'                    => 'required',
          'annualized_return.*.return'       => 'required',
          'annualized_return.*.peer_average' => 'required',
          // 'annualized_return.*.rank'         => 'required',
          'fyreturn.*.return'                => 'required',
          'fyreturn.*.peer_average'          => 'required',
          // 'fyreturn.*.rank'                  => 'required'
        ], [
          'fund_name.required'                        => 'This field is required.',
          'amc_reference_number.required'             => 'This field is required.',
          'amc.required'                              => 'This field is required.',
          'fund_size.required'                        => 'This field is required.',
          'nav.required'                              => 'This field is required.',
          'return_rate.required'                      => 'This field is required.',
          'min_transaction_amount.required'           => 'This field is required',
          'max_transaction_amount.required'           => 'This field is required',
          //'fund_image.required'                     => 'This field is required.',
          'objective.required'                        => 'This field is required.',
          'type.required'                             => 'This field is required.',
          'online_payment.required'                   => 'This field is required.',
          'category.required'                         => 'This field is required.',
          'fund_ratings.required'                     => 'This field is required.',
          'profile_risk.required'                     => 'This field is required.',
          'benchmark.required'                        => 'This field is required.',
          'bank_name.required'                        => 'This field is required.',
          'account_title.required'                    => 'This field is required.',
          'iban_number.required'                      => 'This field is required.',
          'status.required'                           => 'This field is required.',
          'asset.*.asset.required'                    => 'This field is required.',
          'asset.*.share_percent.required'            => 'This field is required.',
          'asset.*.color.required'                    => 'This field is required.',
          'holding.*.type.required'                   => 'This field is required.',
          'holding.*.share_percent.required'          => 'This field is required.',
          'return.*.return.required'                  => 'This field is required.',
          'return.*.peer_average.required'            => 'This field is required.',
          // 'return.*.rank.required'                    => 'This field is required.',
          'annualized_return.*.return.required'       => 'This field is required.',
          'annualized_return.*.peer_average.required' => 'This field is required.',
          // 'annualized_return.*.rank.required'         => 'This field is required.',
          'fyreturn.*.return.required'                => 'This field is required.',
          'fyreturn.*.peer_average.required'          => 'This field is required.',
          // 'fyreturn.*.rank.required'                  => 'This field is required.'
        ]);
  
        // echo print_r($request->is_popular); die;
  
        if ($validator->fails()) {
          return response()->json(['error' => Arr::undot($validator->errors()->toArray())], 422);
          // return response()->json(['error' => $validator->errors()], 422);
        }


      $funds = new Fund();

      if($request->id) {
        $funds = Fund::with('amc', 'additional_details', 'asset', 'asset_allocations', 'holdings', 'fund_bank')
        /*->whereHas('additional_details', function ($q) {
          $q->where('status', 1);
        })*/->find($request->id);
      }
      

      $funds->fund_name   = $request->fund_name;
      $funds->amc_id      = $request->amc;
      $funds->fund_size   = $request->fund_size;
      $funds->nav   = $request->nav;
      $funds->risk_profile= $request->risk_profile;
      $funds->max_transaction_amount = $request->max_transaction_amount;
      $funds->min_transaction_amount = $request->min_transaction_amount;
      $funds->return_rate = $request->return_rate;
      $funds->amc_reference_number = $request->amc_reference_number;
      $funds->is_popular  = $request->is_popular ? 1 : 0;
      $funds->is_new  = $request->is_new ? 1 : 0;
      $funds->online_payment  = $request->online_payment ? 1 : 0;
      $funds->user_id     = auth()->user()->id;
      $funds->url         = $request->url;
      if ($request->file('fund_image')) {
        $file               = $request->file('fund_image');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        // $filename           = $file->storeAs('/public/uploads', $fileNameToStore);
        $path               = "funds/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $funds->fund_image          = $filename;
        $funds->original_name = $fileOriginalName;
      }
      $funds->save();

      // $nav = new Nav();
      // $nav->fund_id = $funds->id;
      // $nav->nav = $request->nav;
      // $nav->save();

      $funds_add_details = new FundsAdditionalDetail();

      if($funds->additional_details) {
        $funds_add_details = $funds->additional_details;
      }

      $funds_add_details->fund_id      = $funds->id;
      $funds_add_details->objective    = $request->objective;
      $funds_add_details->type         = $request->type;
      $funds_add_details->category     = $request->category;
      $funds_add_details->fund_ratings = $request->fund_ratings;
      $funds_add_details->profile_risk = $request->profile_risk;
      $funds_add_details->benchmark    = $request->benchmark;
      $funds_add_details->status       = $request->status;
      $funds_add_details->save();

      $funds_bank_detail = new FundsBankDetail();

      if($funds->fund_bank) {
        $funds_bank_detail = $funds->fund_bank;
      }

      $funds_bank_detail->fund_id       = $funds->id;
      $funds_bank_detail->bank_name     = $request->bank_name;
      $funds_bank_detail->account_title = $request->account_title;
      $funds_bank_detail->iban_number   = $request->iban_number;
      $funds_bank_detail->save();

      $asset = [];
      $existingAssets = $funds->asset->keyBy('id');
      $deleteAssetIds = array_diff($existingAssets->keys()->toArray(), Arr::pluck($request->asset, 'id'));

      foreach ($request->asset as $key => $value) {
        if (@$value['id']) {
          $existingAsset = $existingAssets->get($value['id']);
          if($existingAsset) {
            $existingAsset->asset = $value['asset'];
            $existingAsset->share_percent = $value['share_percent'];
            $existingAsset->color = $value['color'];
            $existingAsset->save();
          }
        } else {
          $asset[] = $value + ['fund_id' => $funds->id];
        }
      }

      $holding = [];
      $existingHoldings = $funds->holdings->keyBy('id');
      $deleteHoldingIds = array_diff($existingHoldings->keys()->toArray(), Arr::pluck($request->holding, 'id'));

      foreach ($request->holding as $key => $value) {
        if (@$value['id']) {
          $existingHolding = $existingHoldings->get($value['id']);
          if($existingHolding) {
            $existingHolding->type = $value['type'];
            $existingHolding->share_percent = $value['share_percent'];
            $existingHolding->save();
          }
        } else {
          $holding[] = $value + ['fund_id' => $funds->id];
        }
      }

      if(count($asset)) {
        FundAsset::insert($asset);
      }

      if(count($holding)) {
        FundHolding::insert($holding);
      }

      if(count($deleteAssetIds)) {
        FundAsset::whereIn('id', $deleteAssetIds)->where('fund_id', $funds->id)->delete();
      }

      if(count($deleteHoldingIds)) {
        FundHolding::whereIn('id', $deleteHoldingIds)->where('fund_id', $funds->id)->delete();
      }

      $assetAllocationData = $request->only(['return', 'annualized_return', 'fyreturn']);
      $assetAllocationData = Arr::dot($assetAllocationData);
      $existingAssetAllocations = $funds->asset_allocations->keyBy('key');
      $assetAllocationIns = [];
      foreach ($assetAllocationData as $key => $assetAllocationDt) {
        $existingAssetAllocation = $existingAssetAllocations->get($key);
        if($existingAssetAllocation) {
          $existingAssetAllocation->value = $assetAllocationDt;
          $existingAssetAllocation->save();
        } else {
          $assetAllocationIns[] = [
            'fund_id' => $funds->id,
            'key' => $key,
            'value' => $assetAllocationDt,
            'created_at' => now(),
            'updated_at' => now()
          ];
        }
      }
      if(count($assetAllocationIns)) {
        FundAssetAllocation::insert($assetAllocationIns);
      }
    
      return response()->json(['success' => true, 'message' => 'The fund has been '.($request->id ? 'updated' : 'created').'.']);
      
    } catch (\Exception $e) {
      echo '<pre>';
      print_r($e->getMessage());
      echo '</pre>';
      return ['error' => 'Something went wrong'];
    }
  }

  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'fund_name' => 'required',
        'amc'       => 'required',
        'logo'      => 'required|image',
        'popular'   => 'required',
        'status'    => 'required',
      ]);

      if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
      }

      $file             = $request->file('logo');
      $fileOriginalName = $file->getClientOriginalName();
      $extension        = $file->getClientOriginalExtension();
      $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
      // $filename         = $file->storeAs('public/uploads', $fileNameToStore);
      $path               = "funds/".$fileNameToStore;
      $filename           = s3ImageUplaod($file, $path);

      $amc                = new Fund();
      $amc->fund_name     = $request->fund_name;
      $amc->amc_id        = $request->amc;
      $amc->is_new    = $request->is_new;
      $amc->is_popular    = $request->popular;
      $amc->logo          = $filename;
      $amc->original_name = $fileOriginalName;
      $amc->amc_reference_number = $request->amc_reference_number;
      $amc->status        = $request->status;
      $amc->user_id       = auth()->user()->id;
      $amc->save();
      return response()->json(['success' => true, 'message' => 'Fund Created successfully!']);
    } catch (\Exception $e) {
      echo '<pre>';
      print_r($e->getMessage());
      echo '</pre>';

      return ['error' => 'Something went wrong'];
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\data  $data
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Amc $amc)
  {

    $validator = Validator::make($request->all(), [
      'entity_name'                 => 'required',
      'address'                     => 'required',
      'logo'                        => 'sometimes|image',
      'contact_no'                  => 'required',
      'compliant_email'             => 'required|email',
      'company_registration_number' => 'required',
      'ntn'                         => 'required',
      'contact_person'              => 'required',
      'url'                         => 'required',
      'status'                      => 'required',
      'amc_reference_number'        =>  'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()]);
    }

    $amc->entity_name                 = $request->entity_name;
    $amc->address                     = $request->address;
    $amc->contact_no                  = $request->contact_no;
    $amc->compliant_email             = $request->compliant_email;
    $amc->company_registration_number = $request->company_registration_number;
    $amc->ntn                         = $request->ntn;
    $amc->amc_reference_number        = $request->amc_reference_number;
    $amc->contact_person              = $request->contact_person;
    $amc->url                         = $request->url;
    $amc->status                      = $request->status;
    if ($request->file('logo')) {
      $file               = $request->file('logo');
      $fileOriginalName   = $file->getClientOriginalName();
      $extension          = $file->getClientOriginalExtension();
      $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
      // $filename           = $file->storeAs('/public/uploads', $fileNameToStore);
      $path               = "funds/".$fileNameToStore;
      $filename           = s3ImageUplaod($file, $path);
      $amc->logo          = $filename;
      $amc->original_name = $fileOriginalName;
    }
    $amc->save();
   
    return response()->json(['success' => true, 'message' => 'AMC updated successfully!']);
  }

  public function autocomplete(Request $request)
  {
    try {
      $data = [];
      $queryTerm = $request->q;
      $funds =  Fund::whereHas('additional_details', function($q) {
          $q->where('status', 1);
      })->where('fund_name', 'like', '%' . $queryTerm . '%')->get();
      foreach ($funds as $fund) {
          $data[] = ['id' => $fund->id, 'text' => $fund->fund_name];
      }
      return $data;
  } catch (\Exception $e) {
      return ['error' => 'Something went wrong'];
  }
  }
  
  public function filter($request, $funds)
  {
    try {

      if (isset($request->fund) && $request->fund != "null") {
        $funds = $funds->where('funds.id', $request->fund);
      }

      if (isset($request->amc) && $request->amc != "null") {
        $amc = $request->amc;
        $funds = $funds->where('amc_id',$amc);
    }


      if (isset($request->from)) {
        $funds = $funds->whereDate('funds.created_at', '>=', Carbon::parse($request->from) );
      }


      if (isset($request->to)) {
        $funds = $funds->whereDate('funds.created_at', '<=', Carbon::parse($request->to));
      }

      if (isset($request->status)) {
        $status = $request->status;
        $funds = $funds->whereHas('additional_details', function($q) use($status) {
          $q->where('status', $status);
        });
      }
      return $funds;
    } catch (\Exception $e) {
      return ['error' => 'Something went wrong'];
    }
  }


}