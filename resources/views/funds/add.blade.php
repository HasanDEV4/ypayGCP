@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div
    class="ml-2 pl-1 col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div>
      <h4>Add New Fund</h4>
      <p>
        <a>Dashboard</a> /
        <a>Funds</a> /
        <span>Add New Fund</span>
      </p>
    </div>
  </div>
  {{-- Basic --}}
  <form {{-- action="{{ route('fund.save') }}" --}} {{-- method="POST" --}} id="addFundForm">
    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Basic Details</h4>
      </div>
      @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      {{ csrf_field() }}
      <div class="main-card mb-3 card">
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Fund Name*
              </p>
              <input type="text" class="form-control fund_name" id="fund_name" placeholder="Fund Name" name="fund_name" autocomplete="off" value="{{ @$data->fund_name }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                AMC*
              </p>
              <select class="mb-2 form-control amc" id="amc" name="amc" autocomplete="off">
                <option selected disabled>Select AMC</option>
                @foreach ($amc as $key => $record)
                  @if($key == @$data->amc->id)
                    <option selected value={{ $key }}>{{ $record }}</option>
                  @else
                    <option value={{ $key }}>{{ $record }}</option>
                  @endif
                @endforeach
              </select>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Fund Size*
              </p>
              <input type="number" class="form-control fund_size" id="fund_size" placeholder="Fund Size" name="fund_size" autocomplete="off" value="{{ @$data->fund_size }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                NAV*
              </p>
              <input type="number" class="form-control nav" style="padding-left: 0.75rem;" id="nav" placeholder="NAV" name="nav" step="any" autocomplete="off" value="{{ @$data->nav }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12 col-md-6">
              <p class="font-weight-bold">
                Return Rate*
              </p>
              <input type="number" class="form-control return_rate" id="return_rate" placeholder="Return Rate" name="return_rate" autocomplete="off" step="any"  value="{{ @$data->return_rate }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <p class="font-weight-bold">
                Fund Image*
              </p>
              <input type="file" class="form-control fund_image h-auto" id="fund_image" placeholder="Fund Image" name="fund_image" autocomplete="off" >
              <div class="invalid-feedback error hide">
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                URL
              </p>
              <input type="text" class="form-control url" id="url" placeholder="https://www.example.com" name="url" autocomplete="off" value="{{ @$data->url }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                AMC Reference Number*
              </p>
              <input type="text" class="form-control amc_reference_number" id="amc_reference_number" placeholder="AMC Reference Number" name="amc_reference_number" autocomplete="off"   value="{{ @$data->amc_reference_number }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3 mb-2">
                <p class="font-weight-bold">Minimum Transaction Amount</p>
                <input type="number" class="form-control" id="min_transaction_amount" placeholder="Minimum Transaction Amount" name="min_transaction_amount" autocomplete="off" value="{{ @$data->min_transaction_amount }}">
                <div class="invalid-feedback error hide">
                </div>
            </div>
            <div class="col-12 col-md-3 mb-2">
                <p class="font-weight-bold">Maximum Transaction Amount</p>
                <input type="number" class="form-control" id="max_transaction_amount" placeholder="Maximum Transaction Amount" name="max_transaction_amount" autocomplete="off" value="{{ @$data->max_transaction_amount }}">
                <div class="invalid-feedback error hide">
                </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12 col-md-6">
              <div class="form-group">
                  <label for="risk_profile" class="col-sm-2 col-form-label mr-5">Risk Profile</label>
                    <label class="radio-inline ml-5">
                      <input type="radio" class="ml-5" name="risk_profile" id="yes" value="1" required {{ @$data->risk_profile ? 'checked' : '' }}>Yes
                    </label>
                    <label class="radio-inline m-2">
                      <input type="radio" class="ml-1" name="risk_profile" id="no" value="0" {{ @$data->risk_profile ? '' : 'checked' }}>No
                    </label>
              </div>
            </div>
          </div>
          <div class="row mb-3">
          <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="online_payment" class="col-sm-4 col-form-label">Accept eNIFT Payment</label>
                    <label class="radio-inline m-2">
                      <input type="radio" name="online_payment" id="yes" value="1" required {{ @$data->online_payment ? 'checked' : '' }}>Yes
                    </label>
                    <label class="radio-inline m-2">
                      <input type="radio" name="online_payment" id="no" value="0" {{ @$data->online_payment ? '' : 'checked' }}>No
                    </label>
                </div>
                <div class="invalid-feedback error hide">
                </div>
          </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Bank --}}
    <div class="col-lg-12">
      <h4 class="font-weight-bold">Additional Details</h4>
      <div class="main-card mb-3 card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-3 mb-3">
              <p class="font-weight-bold">
                Objective*
              </p>
              <input type="text" class="form-control objective" id="objective" placeholder="Objective" name="objective"
                autocomplete="off" value="{{ @$data->additional_details->objective }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3 mb-3">
              <p class="font-weight-bold">
                Fund Type*
              </p>
              <input type="text" class="form-control type" id="type" placeholder="Type" name="type" autocomplete="off" value="{{ @$data->additional_details->type }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3 mb-3">
              <p class="font-weight-bold">
                Category*
              </p>
              <select class="mb-2 form-control category" name="category" autocomplete="off">
                <option selected disabled>Select Category</option>
                @foreach(['shariah' => 'Shariah', 'conventional' => 'Conventional'] as $key => $record)
                  @if($key == @$data->additional_details->category)
                    <option selected value="{{ $key }}">{{ $record }}</option>
                  @else
                    <option value="{{ $key }}">{{ $record }}</option>
                  @endif
                @endforeach
              </select>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Sales Load*
              </p>
              <input type="text" class="form-control fund_ratings" id="fund_ratings" placeholder="Sales Load"
                name="fund_ratings" autocomplete="off" value="{{ @$data->additional_details->fund_ratings }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Profile Risk*
              </p>
              <select class="mb-2 form-control profile_risk" value="{{ @$data->additional_details->profile_risk }}" name="profile_risk" autocomplete="off">
                <option selected disabled>Select Profile Risk</option>
                @foreach($risk_profiles as $risk_profile)
                    @if(@$data->additional_details->profile_risk==$risk_profile->type)
                    <option selected value="{{ $risk_profile->type }}">{{ $risk_profile->type }}</option>
                    @else
                    <option value="{{ $risk_profile->type }}">{{ $risk_profile->type }}</option>
                    @endif
                @endforeach
              </select>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Benchmark*
              </p>
              <input type="text" class="form-control benchmark" id="benchmark" placeholder="Benchmark" name="benchmark"
                autocomplete="off" value="{{ @$data->additional_details->benchmark }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Status*
              </p>
              <select class="mb-2 form-control status" name="status" autocomplete="off">
                <option selected disabled>Select Status</option>
                @foreach(['1' => 'Active', '0' => 'In-Active'] as $key => $record)
                  @if($key == @$data->additional_details->status)
                    <option selected value="{{ $key }}">{{ $record }}</option>
                  @else
                    <option value="{{ $key }}">{{ $record }}</option>
                  @endif
                @endforeach
              </select>
              <div class="invalid-feedback error hide">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- Bank details --}}
    <div class="col-lg-12">
      <h4 class="font-weight-bold">Bank Details</h4>
      <div class="main-card mb-3 card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Bank Name*
              </p>
              <input type="text" class="form-control bank_name" id="bank_name" placeholder="Bank Name" name="bank_name" autocomplete="off" value="{{ @$data->fund_bank->bank_name }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Account Number*
              </p>
              <input type="text" class="form-control account_title" id="account_title" placeholder="Account Number" name="account_title" autocomplete="off" value="{{ @$data->fund_bank->account_title }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                IBAN Number*
              </p>
              <input type="text" class="form-control iban_number" id="iban_number" placeholder="Iban Number" name="iban_number" autocomplete="off" value="{{ @$data->fund_bank->iban_number }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Asset Allocation</h4>
      </div>
      <div class="invalid-feedback assetErr hide">
        <p>All asset fields are required!</p>
      </div>
      <div class="invalid-feedback assetErrPercent hide">
        <p>The sum of all percentages must be equal to 100.</p>
      </div>
      <div class="invalid-feedback assetErrLength hide">
        <p>You cannot add more than 10 elements.</p>
      </div>
      <div class="main-card mb-3 card assetDiv">
        @if(@$data->asset && @$data->asset->count())
        @foreach($data->asset as $key => $asset)
          <div class="card-body" data-id="{{ $key }}">
            <div class="row mb-3">
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Asset*
                </p>
                <input type="text" class="form-control asset" id="asset" placeholder="Asset" name="asset[{{$key}}][asset]" value="{{ @$asset->asset }}">
                <div class="invalid-feedback error hide"></div>
              </div>
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Share Percent*
                </p>
                <input type="number" step="any" min="0" max="100" class="form-control share_percent" id="share_percent" placeholder="Share Percent" name="asset[{{$key}}][share_percent]" value="{{ @$asset->share_percent }}">
                <div class="invalid-feedback error hide"></div>
              </div>
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Select Color*
                </p>
                <input type="color" class="form-control color" id="color" name="asset[{{$key}}][color]" value="{{ @$asset->color }}">
                <div class="invalid-feedback error hide"></div>
              </div>
            </div>
            <input type="hidden" name="asset[{{$key}}][id]" value="{{ @$asset->id }}">
            <a href="#" class="float-right btn btn-sm btn-danger  delete_row">Delete</a>
          </div>
        @endforeach
        @else
          <div class="card-body" data-id="0">
            <div class="row mb-3">
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Asset*
                </p>
                <input type="text" class="form-control asset" id="asset" placeholder="Asset" name="asset[0][asset]">
                <div class="invalid-feedback error hide"></div>
              </div>
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Share Percent*
                </p>
                <input type="number" step="any" min="0" max="100" class="form-control share_percent" id="share_percent" placeholder="Share Percent" name="asset[0][share_percent]">
                <div class="invalid-feedback error hide"></div>
              </div>
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Select Color*
                </p>
                <input type="color" class="form-control color" id="color" name="asset[0][color]">
                <div class="invalid-feedback error hide"></div>
              </div>
            </div>
          </div>
        @endif
        
      
      </div>
      <div class="text-right">
          <button class="btn btn-sm btn-primary addAsset">Add Assets</button>    
        </div>
    </div>
    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Top 10 Holdings</h4>
      </div>
      <div class="invalid-feedback holdingErr hide">
        <p>All holdings fields are required!</p>
      </div>
      <div class="invalid-feedback holdingErrPercent hide">
        <p>The sum of all percentages must be equal to 100.</p>
      </div>
      <div class="invalid-feedback holdingErrLength hide">
        <p>You cannot add more than 10 elements.</p>
      </div>
      <div class="main-card mb-3 card holdingDiv">
        @if(@$data->holdings && @$data->holdings->count())
        @foreach($data->holdings as $key => $holding)
          <div class="card-body" data-id="{{ $key }}">
            <div class="row mb-3">
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Type*
                </p>
                <input type="text" class="form-control holding_type" id="type" placeholder="Type" name="holding[{{$key}}][type]" value="{{ @$holding->type }}">
                <div class="invalid-feedback error hide"></div>
              </div>
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Share Percent*
                </p>
                <input type="number" step="any" min="0" max="100" class="form-control holding_share_percent"
                  id="holding_share_percent" placeholder="Share Percent" name="holding[{{$key}}][share_percent]" value="{{ @$holding->share_percent }}">
                <div class="invalid-feedback error hide"></div>
              </div>
            </div>
            <input type="hidden" name="holding[{{$key}}][id]" value="{{ @$holding->id }}">
            <a href="#" class="float-right btn btn-sm btn-danger delete_row">Delete</a>
          </div>
        @endforeach
        @else
          <div class="card-body" data-id="0">
            <div class="row mb-3">
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Type*
                </p>
                <input type="text" class="form-control holding_type" id="type" placeholder="Type" name="holding[0][type]">
                <div class="invalid-feedback error hide"></div>
              </div>
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                  Share Percent*
                </p>
                <input type="number" step="any" min="0" max="100" class="form-control holding_share_percent" id="holding_share_percent" placeholder="Share Percent" name="holding[0][share_percent]">
                <div class="invalid-feedback error hide"></div>
              </div>
            </div>
          </div>
        @endif
      </div>

      <div class="text-right">
        <button class="btn btn-sm btn-primary addHolding">Add Holdings</button>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Performance Data</h4>
      </div>
      <div class="row">
        <div class="col-12">
          @php
          $returnAssetAllocation = $assetAllocations['return'];
          $annualAssetAllocation = $assetAllocations['annualized_return'];
          $fyReturnAssetAllocation = $assetAllocations['fyreturn'];

          $headerNames = [
            'month_1' => '1 Month',
            'month_3' => '3 Month',
            'month_6' => '6 Month',
            'ytd' => 'YTD',
            'year_1' => '1 Year',
            'year_3' => '3 Year',
            'year_5' => '5 Year'
          ];
          foreach(array_keys($fyReturnAssetAllocation) as $headerKey) {
            $headerNames[$headerKey] = 'FY' . $headerKey;
          }
          @endphp
          <table class="table table-bordered">
            <thead>
              <tr>
                <th></th>
                <th colspan="{{ count($returnAssetAllocation) }}">Return (%)</th>
                <th colspan="{{ count($annualAssetAllocation) }}">Annualized Return (%)</th>
              </tr>
              <tr>
                <th></th>
                @foreach($returnAssetAllocation as $head => $returnAsset)
                  <th>{{ @$headerNames[$head] }}</th>
                @endforeach
                @foreach($annualAssetAllocation as $head => $annualAsset)
                  <th>{{ @$headerNames[$head] }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Return</td>
                @foreach($returnAssetAllocation as $head => $returnAsset)
                  <td>
                    <input type="text" class="form-control perc_return" name="return[{{$head}}][return]" placeholder="Enter Per %" value="{{ $returnAsset['return'] ? $returnAsset['return'] : ''}}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
                @foreach($annualAssetAllocation as $head => $annualAsset)
                  <td>
                    <input type="text" class="form-control perc_return" name="annualized_return[{{$head}}][return]" placeholder="Enter Per %" value="{{ $annualAsset['return'] ? $annualAsset['return'] : ''}}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
              </tr>
              <tr>
                <td>Peer Average</td>
                @foreach($returnAssetAllocation as $head => $returnAsset)
                @php
                @endphp
                  <td>
                    <input type="text" class="form-control perc_peer_average" name="return[{{$head}}][peer_average]" placeholder="Enter Per %" value="{{ $returnAsset['peer_average'] ? $returnAsset['peer_average'] : ''}}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
                @foreach($annualAssetAllocation as $head => $annualAsset)
                  <td>
                    <input type="text" class="form-control perc_peer_average" name="annualized_return[{{$head}}][peer_average]" placeholder="Enter Per %" value="{{ $annualAsset['peer_average'] ? $annualAsset['peer_average'] : ''}}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
              </tr>
              <!-- <tr>
                <td>Rank</td>
                @foreach($returnAssetAllocation as $head => $returnAsset)
                  <td>
                    <input type="text" class="form-control perc_rank" name="return[{{$head}}][rank]" placeholder="Enter Per %" value="{{ $returnAsset['rank'] ? $returnAsset['rank'] : ''}}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
                @foreach($annualAssetAllocation as $head => $annualAsset)
                  <td>
                    <input type="text" class="form-control perc_rank" name="annualized_return[{{$head}}][rank]" placeholder="Enter Per %" value="{{ $annualAsset['rank'] ? $annualAsset['rank'] : '' }}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
              </tr> -->
              <tr>
                <td colspan="4">Financial Year Return (%)</td>
                @foreach($fyReturnAssetAllocation as $head => $fyReturnAsset)
                  <th>{{ @$headerNames[$head] }}</th>
                @endforeach
              </tr>
              <tr>
                <td colspan="{{ count($returnAssetAllocation) }}">Return</td>
                @foreach($fyReturnAssetAllocation as $head => $fyReturnAsset)
                  <td>
                    <input type="text" class="form-control perc_return" name="fyreturn[{{$head}}][return]" placeholder="Enter Per %" value="{{ $fyReturnAsset['return'] ? $fyReturnAsset['return'] : '' }}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
              </tr>
              <tr>
                <td colspan="{{ count($returnAssetAllocation) }}">Peer Average</td>
                @foreach($fyReturnAssetAllocation as $head => $fyReturnAsset)
                  <td>
                    <input type="text" class="form-control perc_peer_average" name="fyreturn[{{$head}}][peer_average]" placeholder="Enter Per %" value="{{ $fyReturnAsset['peer_average'] ? $fyReturnAsset['peer_average'] : ''}}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
              </tr>
              <!-- <tr>
                <td colspan="{{ count($returnAssetAllocation) }}">Rank</td>
                @foreach($fyReturnAssetAllocation as $head => $fyReturnAsset)
                  <td>
                    <input type="text" class="form-control perc_rank" name="fyreturn[{{$head}}][rank]" placeholder="Enter Per %" value="{{ $fyReturnAsset['rank'] ? $fyReturnAsset['rank'] : ''}}">
                    <div class="invalid-feedback error hide"></div>
                  </td>
                @endforeach
              </tr> -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-12 mt-2 mb-4 new-check">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="is_new" {{ @$data->is_new ? 'checked' : '' }}>
        <label class="form-check-label ml-3" for="exampleCheck1">New</label>
      </div>
    </div>
    <div class="col-lg-12 mt-2 mb-4 popular-check">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="is_popular" {{ @$data->is_popular ? 'checked' : '' }}>
        <label class="form-check-label ml-3" for="exampleCheck1">Popular</label>
      </div>
    </div>
    <div class="col-12 mt-2 mb-4 text-right">
      <input type="hidden" name="id" value="{{ @$data->id }}">
      <input type="hidden" name="popular" id="popular" value="{{ $popular??'' }}">
      <input type="hidden" name="new" id="new" value="{{ $new??'' }}">
      <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
  </form>
</div>
@endsection
@section('modal')
<!-- Amc Add Modal -->
@endsection
@push('scripts')
<script>
  $( document ).ready(function() {

    var popular = $('#popular').val();
    var _new = $('#new').val();
    var id = "{{ @$data->id }}";
    var isPopularChecked = $('input[name=is_popular]:checked').length;
    var isNewChecked = $('input[name=is_new]:checked').length;

    
//edit
    // if(id && popular != 5 && isPopularChecked) {
      
    //   $('.is_popular').show();
    // }
    // if(id && popular == 5 && !isPopularChecked) {
    //   $('.popular-check').hide();
    // }
    

    // if(!id && popular == 5) { 
    //   $('.popular-check').hide();
    // }else{
    //   $('.is_popular').show();
    // }

    // if(id && _new != 5 && isNewChecked) {
    //   $('.new-check').show();
    // }
    // if(id && _new == 5 && !isNewChecked) {
    //   $('.new-check').hide();
    // }
    

    // if(!id && _new == 5) { 
    //   $('.new-check').hide();
    // }else{
    //   $('.new-check').show();
    // }
    

    function showError(elem, error) {
      elem.addClass('is-invalid');
      elem.next('.error').html(error);
      elem.next('.error').removeClass('hide').addClass('show');
    }

    function clearError(elem) {
      elem.removeClass('is-invalid').addClass('is-valid');
      elem.next('.error').html('');
      elem.next('.error').removeClass('show').addClass('hide');
    }

    function handleValidationErrors( error ) {
      let amc_reference_number = $('.amc_reference_number');
      if(error.amc_reference_number) {
        showError(amc_reference_number, error.amc_reference_number);
      } else {
        clearError(amc_reference_number);
      }
      let fund_name = $('.fund_name');
      if(error.fund_name) {
        showError(fund_name, error.fund_name);
      } else {
        clearError(fund_name);
      }
      let fund_size = $('.fund_size');
      if(error.fund_size) {
        showError(fund_size, error.fund_size);
      } else {
        clearError(fund_size);
      }

      let nav = $('.nav');
      if(error.nav) {
        showError(nav, error.nav);
      } else {
        clearError(nav);
      }
      let return_rate = $('.return_rate');
      if(error.return_rate) {
        showError(return_rate, error.return_rate);
      } else {
        clearError(return_rate);
      }
      let objective = $('.objective');
      if(error.objective) {
        showError(objective, error.objective);
      } else {
        clearError(objective);
      }
      let type = $('.type');
      if(error.type) {
        showError(type, error.type);
      } else {
        clearError(type);
      }
      let fund_ratings = $('.fund_ratings');
      if(error.fund_ratings) {
        showError(fund_ratings, error.fund_ratings);
      } else {
        clearError(fund_ratings);
      }
      let benchmark = $('.benchmark');
      if(error.benchmark) {
        showError(benchmark, error.benchmark);
      } else {
        clearError(benchmark);
      }
      let status = $('.status');
      if(error.status) {
        showError(status, error.status);
      }  else {
        clearError(status);
      }
      let category = $('.category');
      if(error.category) {
        showError(category, error.category);
      }  else {
        clearError(category);
      }
      let profile_risk = $('.profile_risk');
      if(error.profile_risk) {
        showError(profile_risk, error.profile_risk);
      }  else {
        clearError(profile_risk);
      }
      let bank_name = $('.bank_name');
      if(error.bank_name) {
        showError(bank_name, error.bank_name);
      }  else {
        clearError(bank_name);
      }
      let account_title = $('.account_title');
      if(error.account_title) {
        showError(account_title, error.account_title);
      }  else {
        clearError(account_title);
      }
      let iban_number = $('.iban_number');
      if(error.iban_number) {
        showError(iban_number, error.iban_number);
      }  else {
        clearError(iban_number);
      }
      let amc = $('.amc');
      if(error.amc) {
        showError(amc, error.amc);
      }  else {
        clearError(amc);
      }

      let fund_image = $('.fund_image');
      if(error.fund_image) {
        showError(fund_image, error.fund_image);
      }  else {
        clearError(fund_image);
      }

      var asset = $('.asset');
      var share_percent = $('.share_percent');
      var color = $('.color');

      for(var i=0; i < asset.length; i++) {
        if(error.asset && error.asset[i] && error.asset[i].asset) {
          showError($(asset[i]), error.asset[i].asset);
        } else {
          clearError($(asset[i]));
        }

        if(error.asset && error.asset[i] && error.asset[i].share_percent) {
          showError($(share_percent[i]), error.asset[i].share_percent);
        } else {
          clearError($(share_percent[i]));
        }

        if(error.asset && error.asset[i] && error.asset[i].color) {
          showError($(color[i]), error.asset[i].color);
        } else {
          clearError($(color[i]));
        }
      }

      var holding_type = $('.holding_type');
      var holding_share_percent = $('.holding_share_percent');

      for(var i=0; i < holding_type.length; i++) {
        if(error.holding && error.holding[i] && error.holding[i].type) {
          showError($(holding_type[i]), error.holding[i].type);
        } else {
          clearError($(holding_type[i]));
        }

        if(error.holding && error.holding[i] && error.holding[i].share_percent) {
          showError($(holding_share_percent[i]), error.holding[i].share_percent);
        } else {
          clearError($(holding_share_percent[i]));
        }
      }

      var perc_return = $('.perc_return');
      var perc_peer_average = $('.perc_peer_average');
      var perc_rank = $('.perc_rank');
      console.log('perc_return',perc_return);
      for(var i = 0; i < perc_return.length; i++) {
        var keys = $(perc_return[i]).attr('name').match(/\[[^\][]*]/g);
        keys = keys.map(function(item) {
          return item.replace('[', '').replace(']', '');
        });
        keys.unshift($(perc_return[i]).attr('name').replace(/\[[^\][]*]/g, ''));
        var errorValue = [];
        while(keys.length) {
            var key = keys.shift();
            errorValue = errorValue[key] ? errorValue[key] : (error[key] ? error[key] : '');
        }
        errorValue = errorValue ? errorValue[0] : null;

        if(errorValue) {
          showError($(perc_return[i]), errorValue);
        } else {
          clearError($(perc_return[i]));
        }
      }

      for(var i = 0; i < perc_peer_average.length; i++) {
        var keys = $(perc_peer_average[i]).attr('name').match(/\[[^\][]*]/g);
        keys = keys.map(function(item) {
          return item.replace('[', '').replace(']', '');
        });
        keys.unshift($(perc_peer_average[i]).attr('name').replace(/\[[^\][]*]/g, ''));
        var errorValue = [];
        while(keys.length) {
            var key = keys.shift();
            errorValue = errorValue[key] ? errorValue[key] : (error[key] ? error[key] : '');
        }
        errorValue = errorValue ? errorValue[0] : null;

        if(errorValue) {
          showError($(perc_peer_average[i]), errorValue);
        } else {
          clearError($(perc_peer_average[i]));
        }
      }

      for(var i = 0; i < perc_rank.length; i++) {
        var keys = $(perc_rank[i]).attr('name').match(/\[[^\][]*]/g);
        keys = keys.map(function(item) {
          return item.replace('[', '').replace(']', '');
        });
        keys.unshift($(perc_rank[i]).attr('name').replace(/\[[^\][]*]/g, ''));
        var errorValue = [];
        while(keys.length) {
            var key = keys.shift();
            errorValue = errorValue[key] ? errorValue[key] : (error[key] ? error[key] : '');
        }
        errorValue = errorValue ? errorValue[0] : null;

        if(errorValue) {
          showError($(perc_rank[i]), errorValue);
        } else {
          clearError($(perc_rank[i]));
        }
      }

    }
    function roundToTwo(num) {
      return +(Math.round(num + "e+2")  + "e-2");
    }
    function validatePercent(element) {
      var sumPercent = 0;

      $(element).each(function() {
        sumPercent += parseFloat($(this).val());
      });
      console.log(sumPercent);
      sumPercent=roundToTwo(sumPercent);
      console.log(sumPercent);
      return { sumPercent: sumPercent, length: $(element).length };
    }

    $('form').submit(function(event) {
      event.preventDefault();
      $('.assetErr').hide();
      $('.holdingErr').hide();
      $('.assetErrPercent').hide();
      $('.holdingErrPercent').hide();

      var preventSubmit = false;
      if(validatePercent('.share_percent').sumPercent !== 100) {
        $('.assetErrPercent').show();
        preventSubmit = true;
      }
      // alert(validatePercent('.share_percent').length)
      // if(validatePercent('.share_percent').length >= 10) {
      //   $('.assetErrLength').show();
      //   preventSubmit = true;
      // }
      if(validatePercent('.holding_share_percent').sumPercent !== 100) {
        $('.holdingErrPercent').show();
        preventSubmit = true;
      }

      // if(validatePercent('.holding_share_percent').length >= 10) {
      //   $('.holdingErrLength').show();
      //   preventSubmit = true;
      // }

      if(preventSubmit) {
        return;
      }
      var form = new FormData(this);

      $.ajax({
        // data: $('#addFundForm').serialize(),
        data: form,
        url: "{{ route('fund.save') }}",
        type: "POST",
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        enctype: 'multipart/form-data',
        success: function (data) {
          console.log("data", data);
          if (!data.error) {
            Toast.fire({
              icon: 'success',
              title: data.message
            });
          }
          // window.location.href = "{{ route('fund.index') }}";
        },
        error: function (data) {
          Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });
          if (data.responseJSON.error == 'asset') {
            $('.assetErr').show();
            return;
          }
          if (data.responseJSON.error == 'holding') {
            $('.holdingErr').show();
            return;
          }
          
          handleValidationErrors(data.responseJSON.error);
         

        }
      });
    });

    $('body').on("click", ".delete_row", function(e) {
      e.preventDefault();
      var row = $(this).parent('div');
      var nextRow = $(this).parent('div').next();
      if($(row).parent().hasClass('assetDiv')) {
        $('.assetErrLength').hide();
        $('.assetErrPercent').hide();
      }
      if($(row).parent().hasClass('holdingDiv')) {
        $('.holdingErrLength').hide();
        $('.holdingErrPercent').hide();
      }

      var index = parseInt($(row).attr('data-id'));
      // console.log('id', index);
      $(row).remove();
      while($(nextRow).attr('data-id')) {
        $(nextRow).attr('data-id', index);
        // console.log('nxt id', $(nextRow).attr('data-id'));
        nextRow = $(nextRow).next();
        index++;
      }
    });

    $(".addAsset").on('click',function(e) {
      e.preventDefault();

      if(validatePercent('.share_percent').sumPercent >= 100) {
        $('.assetErrPercent').show();
        return;
      }
        var index = $('.assetDiv').children().last().attr('data-id');
        
        if (index == 9) {
            alert("Just 10 assets are allowed")
            $('.assetErrLength').show();
          return
        }
        $(".assetDiv").append(`<div class="card-body" data-id="${parseInt(index)+1}"><div class="row mb-3">
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Asset*
            </p>
            <input type="text" class="form-control asset" id="asset" placeholder="Asset" name="asset[${parseInt(index)+1}][asset]">
            <div class="invalid-feedback error hide"></div>
          </div>
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Share Percent*
            </p>
            <input type="number" step="any" min="0" max="100" class="form-control share_percent" id="share_percent" placeholder="Share Percent" name="asset[${parseInt(index)+1}][share_percent]">
            <div class="invalid-feedback error hide"></div>
          </div>
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Select Color*
            </p>
            <input type="color" class="form-control color" id="color" name="asset[${parseInt(index)+1}][color]">
            <div class="invalid-feedback error hide"></div>
          </div>

        </div><a href="#" class="float-right btn btn-sm btn-danger delete_row">Delete</a></div>`)
    });

    $(".addHolding").on('click',function(e) {
      e.preventDefault();
      console.log(validatePercent('.holding_share_percent').sumPercent);
      if(validatePercent('.holding_share_percent').sumPercent >= 100) {
        $('.holdingErrPercent').show();
        return;
      }
      var index = $('.holdingDiv').children().last().attr('data-id');
      
      if (index == 9) {
          $('.holdingErrLength').show();
        return;
      }
      $(".holdingDiv").append(`<div class="card-body" data-id="${parseInt(index)+1}"><div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Type*
          </p>
          <input type="text" class="form-control holding_type" id="" placeholder="Type" name="holding[${parseInt(index)+1}][type]">
          <div class="invalid-feedback error hide"></div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Share Percent*
          </p>
          <input type="number" step="any" min="0" max="100" class="form-control holding_share_percent" id="holding_share_percent" placeholder="Share Percent" name="holding[${parseInt(index)+1}][share_percent]">
          <div class="invalid-feedback error hide"></div>
        </div>

      </div><a href="#" class="float-right btn btn-sm btn-danger delete_row">Delete</a></div>`)
    });
  });

 // $(".share_percent").on('keypress', function() {
 //  $(".share_percent")
 //              .map(function(){
 //                 console.log($(this).val())
 //              }).get();
 // // $(".share_percent").each(function() {
 // //      var share_percent_val = $(this).val();
 // //      console.log("share_percent_val", share_percent_val);
 // //  });
 //  });
</script>
@endpush