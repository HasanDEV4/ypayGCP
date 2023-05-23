@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div class="ml-2 pl-1">
      <h4>Add Profile</h4>
      <p>
        <a>Dashboard</a> /
        <a>Customers</a> /
        <span>Add Profile</span>
      </p>
    </div>
  </div>
  <form action="{{ route('cust.add') }}" method="POST" id="addCustomerForm" enctype="multipart/form-data">
    @if (session('success'))
     <div class="alert alert-success">
         {{ session('success') }}
     </div>
    @elseif(session('error'))
    <div class="alert alert-danger">
         {{ session('error') }}
     </div>
    @endif
    @csrf
  @method('PUT')
    {{-- Basic --}}
<div class="col-lg-12">
  <div class="d-flex flex-row justify-content-between mb-2">
  <h4 class="font-weight-bold mb-0">Basic Details</h4>
    {{-- <button class="btn btn-sm btn-primary edit">Change Status</button> --}}
  </div>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Name*
          </p>
          <input type="text" class="form-control full_name" id="full_name" placeholder="Enter Full Name" name="full_name" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('full_name'))
              <div class="is-invalid error text-danger">
              {{ $errors->first('full_name') }}
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Pin*
          </p>
          <input type="password" class="form-control pin" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"  id="pin" maxlength="4" placeholder="Enter Pin" name="pin" autocomplete="new-password">
          <div class="col-md-12">
            @if ($errors->has('pin'))
              <div class="is-invalid error text-danger">
              {{ $errors->first('pin') }}
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Email*
          </p>
          <input type="email" class="form-control email" id="email" placeholder="Enter Email Address" name="email" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('email'))
            <div class="is-invalid error text-danger">
              {{ $errors->first('email') }}
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Father Name*
          </p>
          <input type="text" class="form-control father_name" id="father_name" placeholder="Enter Father Name" name="father_name" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('father_name'))
            <div class="is-invalid error text-danger">
              {{ $errors->first('father_name') }}
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Mother Name
          </p>
          <input type="text" class="form-control mother_name" id="mother_name" placeholder="Enter Mother Name" name="mother_name" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('mother_name'))
            <div class="is-invalid error text-danger">
              {{ $errors->first('mother_name') }}
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Gender*
          </p>
          <select class="mb-2 form-control" name="gender" autocomplete="off">
            <option value="" selected disabled>Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
            <option value="I prefer not to disclose">I prefer not to disclose</option>
          </select>
          <div class="col-md-12">
            @if ($errors->has('gender'))
            <div class="is-invalid error text-danger">
              {{ $errors->first('gender') }}
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Nationality*
          </p>
          <input type="text" class="form-control nationality" id="nationality" value="Pakistani" name="nationality" autocomplete="off" disabled>
          <div class="col-md-12">
            @if ($errors->has('nationality'))
            <div class="is-invalid error text-danger">
              {{ $errors->first('nationality') }}
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            D.O.B
          </p>
          <input type="date" class="form-control dob" id="dob" placeholder="Enter Full Name" name="dob" autocomplete="off" >
          <div class="invalid-feedback error hide">
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <p class="font-weight-bold">
            Current Address*
          </p>
          <input type="text" class="form-control current_address" id="current_address" placeholder="Enter Current Address" name="current_address" autocomplete="off" >
          <div class="col-md-12">
            @if ($errors->has('current_address'))
            <div class="is-invalid error text-danger">
              {{ $errors->first('current_address') }}
              </div>
            @endif
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Source of income(old)
          </p>
          {{-- <input type="text" class="form-control source_of_income" id="source_of_income" placeholder="Enter Source of Income" name="source_of_income" autocomplete="off" value="{{ ucwords($user->cust_basic_detail['source_of_income']) }}"> --}}
          <select class="form-control source_of_income" name="source_of_income">
            <option value="salary">Salary</option>
            <option value="business-income">Business-Income</option>
            <option value="commission">Commission</option>
            <option value="inheritence">Inheritence</option>
            <option value="gift">Gift</option>
            <option value="investment-income">Investment-Income</option>
            <option value="rental-income">Rental-Income</option>
            <option value="remittence">Remittence</option>
            <option value="retirement-benefits">Retirement-Benefits</option>
            <option value="savings">Savings</option>
            <option value="other">Other</option>
          </select>
          <div class="col-md-12">
            @if ($errors->has('source_of_income'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('source_of_income') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Nominee
          </p>
          <input type="text" class="form-control nominee_name" id="nominee_name" placeholder="Enter Nominee Name" name="nominee_name" autocomplete="off">
          <div class="invalid-feedback error hide">
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Nominee CNIC Number
          </p>
          <input type="text" class="form-control nominee_cnic" id="nominee_cnic" placeholder="Enter Nominee CNIC" name="nominee_cnic" autocomplete="off" >
          <div class="invalid-feedback error hide">
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            City*
          </p>
          <select class="mb-2 form-control city" id="city" name="city">
            <option value="" selected disabled>Select City</option>
            @foreach ( $cities as $city)
            <option value="{{ $city->id }}">{{ $city->city }}</option>
            @endforeach  
          </select>
          <!-- <select class="form-control citySelectFilter" id="city" name="city"></select> -->
          <div class="col-md-12">
            @if ($errors->has('city'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('city') }}
            </div>
          @endif
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            State
          </p>
          <select class="mb-2 form-control state" id="state" name="state">
            @foreach ( $states as $state)
            <option value="{{ $state}}">{{ $state }}</option>
            @endforeach  
          </select>
          <div class="col-md-12">
            @if ($errors->has('state'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('state') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Phone No*
          </p>
          <input type="text" class="form-control phone_no" id="phone_no" placeholder="Enter Phone Number" name="phone_no" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('phone_no'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('phone_no') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Refer Code
          </p>
          {{-- <p class="text-muted">{{ $user->refer_code ? $user->refer_code : '-'}}</p> --}}
          <input type="text" class="form-control refer_code" id="refer_code" placeholder="Enter Refer Code" name="refer_code" autocomplete="off">
          <div class="invalid-feedback error hide">
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Zakat Deduction
          </p>
          <select class="mb-2 form-control" id="zakat" name="zakat">
              <option value="0" >No</option> 
              <option value="1" >Yes</option> 
          </select>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Sources of Income (New)*
          </p>
          <select class="mb-2 form-control" id="income_source" name="income_source">
             <option value="" selected disabled>Select Income Source</option>
             @foreach($income_sources as $source)
              <option value="{{$source->id}}">{{$source->income_name}}</option>
            @endforeach
          </select>
          <!-- <select class="form-control incomeSelectFilter" id="income_source" name="income_source"></select> -->
          <div class="col-md-12">
            @if ($errors->has('income_source'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('income_source') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Occupation*
          </p>
          <select class="mb-2 form-control" id="occupation" name="occupation">
             <option value="" selected disabled>Select Occupation</option>
             @foreach($occupations as $occupation)
              <option value="{{$occupation->id}}">{{$occupation->name}}</option>
            @endforeach
          </select>
          <!-- <select class="form-control occupationSelectFilter" id="occupation" name="occupation"></select> -->
          <div class="col-md-12">
            @if ($errors->has('occupation'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('occupation') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Country
          </p>
          <select class="mb-2 form-control" id="country" name="country" disabled>
             <!-- @foreach($countries as $country) -->
              <option value="1">Pakistan</option>
            <!-- @endforeach -->
          </select>
        </div>
      </div>
    </div>
  </div>
</div>
  {{-- CNIC --}}
<div class="col-lg-12">
  <h4 class="font-weight-bold">CNIC Details</h4>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Number*
          </p>
          <input type="text" class="form-control cnic_number" id="cnic_number" placeholder="Enter CNIC Number" name="cnic_number" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('cnic_number'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('cnic_number') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Issue Date
          </p>
          <input type="date" class="form-control issue_date" id="issue_date" placeholder="Enter Issue Date" name="issue_date" autocomplete="off">
          <div class="invalid-feedback error hide">
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Expiry Date
          </p>
          <input type="date" class="form-control expiry_date" id="expiry_date" placeholder="Enter Expiry Date" name="expiry_date" autocomplete="off">
          <div class="invalid-feedback error hide">
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Front Image*
          </p>
            <input type="file" class="form-control h-auto cnic_front" id="cnic_front" name="cnic_front">
            <div class="col-md-12">
              @if ($errors->has('cnic_front'))
              <div class="is-invalid error text-danger">
              {{ $errors->first('cnic_front') }}
              </div>
            @endif
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Back Image*
          </p>
          <input type="file" class="form-control h-auto cnic_back" id="cnic_back" name="cnic_back">
          <div class="col-md-12">
            @if ($errors->has('cnic_back'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('cnic_back') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
          Proof of Income / Salary Slip
          </p>
          <input type="file" class="form-control h-auto income" id="income" name="income">
          <div class="col-md-12">
            @if ($errors->has('income'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('income') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Citizenship Status*
          </p>
          <select class="mb-2 form-control" name="citizenship_status" autocomplete="off">
            <option selected disabled>Select Citizenship Status</option>
            @foreach($citizenship_statuses as $citizenship_status)
              <option value="{{$citizenship_status->id}}" </option>
            @endforeach
          </select>
            @if ($errors->has('citizenship_status'))
              <div class="is-invalid error text-danger">
              {{ $errors->first('citizenship_status') }}
              </div>
            @endif
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Country of Residence*
          </p>
          <select class="mb-2 form-control" name="country_of_residence" autocomplete="off">
            <option selected disabled>Select Country of Residence</option>
            <option value="pakistan">Pakistan</option>
            <option value="usa">USA</option>
            <option value="other">Other</option>
          </select>
          @if ($errors->has('country_of_residence'))
              <div class="is-invalid error text-danger">
              {{ $errors->first('country_of_residence') }}
              </div>
            @endif
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
          TaxPayer Identification Number*
          </p>
          <input type="text" class="form-control h-auto taxpayer_identification_number" placeholder="Enter TaxPayer Identification Number" id="taxpayer_identification_number" name="taxpayer_identification_number">
          @if ($errors->has('taxpayer_identification_number'))
              <div class="is-invalid error text-danger">
              {{ $errors->first('taxpayer_identification_number') }}
              </div>
            @endif
          </div>
          <div class="col-12 col-md-3">
          <p class="font-weight-bold">
          Passport Number*
          </p>
          <input type="text" class="form-control h-auto passport_number" placeholder="Enter Passport Number" id="passport_number" name="passport_number">
          @if ($errors->has('passport_number'))
              <div class="is-invalid error text-danger">
              {{ $errors->first('passport_number') }}
              </div>
            @endif
          </div>
      </div>
    </div>
  </div>
</div>
  {{-- Bank --}}
<div class="col-lg-12">
  <h4 class="font-weight-bold">Bank Account Details</h4>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row">
        
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            IBAN*
          </p>
          <input type="text" class="form-control iban" id="iban" placeholder="Enter Iban Number" name="iban" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('iban'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('iban') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Bank(New)*
          </p>
          <select class="mb-2 form-control" id="bank_new" name="bank_new">
             <option value="" selected disabled>Select Bank</option>
             @foreach($banks as $bank)
              <option value="{{$bank->id}}">{{$bank->name}}</option>
            @endforeach
          </select>
          <!-- <select class="form-control bankSelectFilter" id="bank_new" name="bank_new"></select> -->
          <div class="col-md-12">
            @if ($errors->has('bank_new'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('bank_new') }}
            </div>
            @endif
        </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Bank(old)
          </p>
          <input type="text" class="form-control bank" id="bank" placeholder="Enter Bank Name" name="bank" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('bank'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('bank') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Branch*
          </p>
          <input type="text" class="form-control branch" id="branch" placeholder="Enter Branch Name" name="branch" autocomplete="off" >
          <div class="col-md-12">
            @if ($errors->has('branch'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('branch') }}
            </div>
          @endif
          </div>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Bank Account Number*
          </p>
          <input type="text" class="form-control bank_account_number" id="bank_account_number" placeholder="Enter Bank Accouunt Number" name="bank_account_number" autocomplete="off">
          <div class="col-md-12">
            @if ($errors->has('bank_account_number'))
            <div class="is-invalid error text-danger">
            {{ $errors->first('bank_account_number') }}
            </div>
          @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="d-flex flex-row justify-content-between mb-2">
  <h4 class="font-weight-bold mb-0">Comments</h4>
  </div>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12">
        <div id="comments_div">
        </div>
        <div class="form-group row">
        <button type="button" class="btn btn-info add_comment">Add Comment</button>
        </div>
        </div>
      </div>
    </div>
  </div>
<div class="col-12 mt-2 mb-4 text-right">
    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
</div>
</form>
@endsection

@push('scripts')
<script>

$(document).ready(function(){
  let count=$('#comments_div').children().length+1;
    $('.add_comment').click(function(event) {
        event.preventDefault();
        let option=`<div class="form-group row comment_`+count+`_div col-12">
                      <label for="option" class="col-2 col-form-label">Comment `+count+`</label>
                      <div class="col-8">
                        <textarea class="form-control" id="comment" placeholder="Enter Comment" name="comments[]" autocomplete="off"></textarea>
                        <div class="invalid-feedback error hide">
                        </div>
                      </div>
                      <div class="col-2">
                      <button class="btn btn-danger delete_btn" id="comment_`+count+`">Remove</button>
                      </div>
                  </div>`;
        $('#comments_div').append(option);
        count=$('#comments_div').children().length+1;
        $('.delete_btn').click(function(event) {
          event.preventDefault();
          let id=$(this).attr('id');
          $('.'+id+'_div').remove();
          count=$('#comments_div').children().length+1;
        });
    });
      $('.alert-success').fadeIn().delay(1000).fadeOut();
      });
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
      let pin = $('.pin');
      if(error.pin) {
        showError(pin, error.pin);
      } else {
        clearError(pin);
      }
      let occupation = $('#occupation');
      if(error.occupation) {
        showError(occupation, error.occupation);
      } else {
        clearError(occupation);
      }
      let bank_new = $('#bank_new');
      if(error.bank_new) {
        showError(bank_new, error.bank_new);
      } else {
        clearError(bank_new);
      }
      let income_source = $('#income_source');
      if(error.income_source) {
        showError(income_source, error.income_source);
      } else {
        clearError(income_source);
      }
      let full_name = $('.full_name');
      if(error.full_name) {
        showError(full_name, error.full_name);
      } else {
        clearError(full_name);
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
      let citizenship_status = $('.citizenship_status');
      if(error.citizenship_status) {
        showError(citizenship_status, error.citizenship_status);
      }  else {
        clearError(citizenship_status);
      }
      let country_of_residence = $('.country_of_residence');
      if(error.country_of_residence) {
        showError(country_of_residence, error.country_of_residence);
      }  else {
        clearError(country_of_residence);
      }
      let passport_number = $('.passport_number');
      if(error.passport_number) {
        showError(passport_number, error.passport_number);
      }  else {
        clearError(passport_number);
      }
      let taxpayer_identification_number = $('.taxpayer_identification_number');
      if(error.taxpayer_identification_number) {
        showError(taxpayer_identification_number, error.taxpayer_identification_number);
      }  else {
        clearError(taxpayer_identification_number);
      }
      let fund_image = $('.fund_image');
      if(error.fund_image) {
        showError(fund_image, error.fund_image);
      }  else {
        clearError(fund_image);
      }

    }
    function cityFilterDropDown() {
      $('#city').select2();
// $('#city').select2({
// width: '100%',
// minimumInputLength: 0,
// dataType: 'json',
// placeholder: 'Select',
// ajax: {
//     url: function () {
//         return "{{ route('city.autocomplete') }}";
//     },
//     processResults: function (data, page) {
//         return {
//             results: data
//         };
//     }
// }
// });

}

function incomeFilterDropDown() {
  $('#income_source').select2();
// $('#income_source').select2({
// width: '100%',
// minimumInputLength: 0,
// dataType: 'json',
// placeholder: 'Select',
// ajax: {
//     url: function () {
//         return "{{ route('income.autocomplete') }}";
//     },
//     processResults: function (data, page) {
//         return {
//             results: data
//         };
//     }
// }
// });

}
function occupationFilterDropDown() {
  $('#occupation').select2();
// $('#occupation').select2({
// width: '100%',
// minimumInputLength: 0,
// dataType: 'json',
// placeholder: 'Select',
// ajax: {
//     url: function () {
//         return "{{ route('occupation.autocomplete') }}";
//     },
//     processResults: function (data, page) {
//         return {
//             results: data
//         };
//     }
// }
// });

}
function bankFilterDropDown() {
  $('#bank_new').select2();
// $('#bank_new').select2({
// width: '100%',
// minimumInputLength: 0,
// dataType: 'json',
// placeholder: 'Select',
// ajax: {
//     url: function () {
//         return "{{ route('bank.autocomplete') }}";
//     },
//     processResults: function (data, page) {
//         return {
//             results: data
//         };
//     }
// }
// });

}

cityFilterDropDown();
incomeFilterDropDown();
occupationFilterDropDown();
bankFilterDropDown();
</script>
@endpush