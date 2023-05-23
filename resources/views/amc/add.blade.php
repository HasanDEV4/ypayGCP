@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div
    class="ml-2 pl-1 col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div>
      <h4> Add New AMC</h4>
      <p>
        <a>Dashboard</a> /
        <a>AMC </a> /
        <span>Add New AMC</span>
      </p>
    </div>
  </div>
  {{-- Basic --}}
  <form {{-- action="{{ route('fund.save') }}" --}} {{-- method="POST" --}} id="addAmcForm" enctype="multipart/form-data">
    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Add New AMC</h4>
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
                Entity Name*
              </p>
              <input type="text" class="form-control entity_name" id="entity_name" placeholder="Entity Name" name="entity_name" autocomplete="off" value="{{ @$data->entity_name }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Address*
              </p>
              <input type="text" class="form-control address" id="address" placeholder="Address" name="address" autocomplete="off" value="{{ @$data->address }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                logo*
              </p>
              <input type='file' name="logo" class="form-control logo h-auto" id="logo" autocomplete="off" value="{{ @$data->logo }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Contact*
              </p>
              <input type='number' class="form-control contact_no" id="contact_no" name="contact_no" placeholder="Contact No" value="{{ @$data->contact_no }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Complaint Email*
              </p>
              <input type='text' class="form-control compliant_email" id="compliant_email" name="compliant_email" placeholder="Complaint Email" value="{{ @$data->compliant_email }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                (CRN)*
              </p>
              <input type='text' class="form-control company_registration_number" id="company_registration_number" name="company_registration_number" placeholder="Company Registration Number (CRN)" value="{{ @$data->company_registration_number }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                NTN*
              </p>
              <input type='text' class="form-control ntn" id="ntn" name="ntn" placeholder="NTN" value="{{ @$data->ntn }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Contact Person*
              </p>
              <input type='text' class="form-control contact_person" id="contact_person" name="contact_person" placeholder="Contact Person" value="{{ @$data->contact_person }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                URL*
              </p>
              <input type='text' class="form-control url" id="url" name="url" placeholder="https://ypayfinancial.com/" value="{{ @$data->url }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Bank Name
              </p>
              <input type='text' class="form-control bank_name" id="bank_name" name="bank_name" placeholder="Bank Name" value="{{ @$data->bank_name }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Account Title*
              </p>
              <input type='text' class="form-control account_title" id="account_title" name="account_title" placeholder="Account Title" value="{{ @$data->account_title }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                IBAN Number*
              </p>
              <input type='text' class="form-control iban_number" id="iban_number" name="iban_number" placeholder="IBAN Number" value="{{ @$data->iban_number }}"/>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Status*
              </p>
              <select class="mb-2 form-control" name="status" autocomplete="off">
                <option selected disabled>Select Status</option>
                <option value="1" {{@$data->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{@$data->status == 0 ? 'selected' : '' }}>In-Active</option>
              </select>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Method to send data*
              </p>
              <select class="mb-2 form-control" id="select_data_send_method" name="select_data_send_method" autocomplete="off">
                <option selected>Select Data Send Method</option>
                <option value="CSV" {{@$data->through_csv == 1 ? 'selected' : '' }}>CSV</option>
                <option value="API" {{@$data->through_api == 1 ? 'selected' : '' }}>API</option>
              </select>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3 send_csv" hidden>
              <p class="font-weight-bold">
                Method to send CSV*
              </p>
              <select class="mb-2 form-control" id="select_csv_send_method" name="select_csv_send_method" autocomplete="off">
                <option selected>Select CSV Send Method</option>
                <option value="Email" {{@$data->through_email == 1 ? 'selected' : '' }}>Through Email</option>
                <option value="G Drive" {{@$data->through_drive == 1 ? 'selected' : '' }}>Through Shared Drive</option>
              </select>
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3 csv_emails" hidden>
              <p class="font-weight-bold">
                Enter Emails to receive CSV*
              </p>
              <input type='text' class="form-control emails" id="emails" name="emails" placeholder="Enter Emails Comma Separated" value="{{ @$data->csv_emails }}"/>" 
              <div class="invalid-feedback error hide">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <h4 class="font-weight-bold">Configurations</h4>
      <div class="main-card mb-3 card">
        <div class="card-body">
          <div class="row">
          <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="online_payment" class="col-sm-4 col-form-label">Fund Units Convertable?</label>
                    <label class="radio-inline m-2">
                      <input type="radio" name="units_convertable" id="yes" value="1" required {{ @$data->units_convertable ? 'checked' : '' }}>Yes
                    </label>
                    <label class="radio-inline m-2">
                      <input type="radio" name="units_convertable" id="no" value="0" {{ @$data->units_convertable ? '' : 'checked' }}>No
                    </label>
                </div>
                <div class="invalid-feedback error hide">
                </div>
          </div>
          </div>
        </div>
      </div>
    </div>

   
    <div class="col-12 mt-2 mb-4 text-right">
      <input type="hidden" name="id" value="{{ @$data->id }}">
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
    if($('#select_data_send_method').val()=="CSV")
    $('.send_csv').attr('hidden',false);
    if($('#select_csv_send_method').val()=="Email")
    $('.csv_emails').attr('hidden',false);
    $('#select_data_send_method').change(function(){
      if($(this).val()=="CSV")
      $('.send_csv').attr('hidden',false);
      else
      $('.send_csv').attr('hidden',true);
    });
    $('#select_csv_send_method').change(function(){
      if($(this).val()=="Email")
      $('.csv_emails').attr('hidden',false);
      else
      $('.csv_emails').attr('hidden',true);
    });

    var id = $('#addAmcForm').find($('input[name="id"]')).val();
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
      let entity_name = $('.entity_name');
      if(error.entity_name) {
        showError(entity_name, error.entity_name);
      } else {
        clearError(entity_name);
      }
      let address = $('.address');
      if(error.address) {
        showError(address, error.address);
      } else {
        clearError(address);
      }
      let compliant_email = $('.compliant_email');
      if(error.compliant_email) {
        showError(compliant_email, error.compliant_email);
      } else {
        clearError(compliant_email);
      }
      let logo = $('.logo');
      if(error.logo) {
        showError(logo, error.logo);
      } else {
        clearError(logo);
      }
      let contact_no = $('.contact_no');
      if(error.contact_no) {
        showError(contact_no, error.contact_no);
      } else {
        clearError(contact_no);
      }
      let company_registration_number = $('.company_registration_number');
      if(error.company_registration_number) {
        showError(company_registration_number, error.company_registration_number);
      } else {
        clearError(company_registration_number);
      }
      let ntn = $('.ntn');
      if(error.ntn) {
        showError(ntn, error.ntn);
      } else {
        clearError(ntn);
      }
      let contact_person = $('.contact_person');
      if(error.contact_person) {
        showError(contact_person, error.contact_person);
      } else {
        clearError(contact_person);
      }
      let url = $('.url');
      if(error.url) {
        showError(url, error.url);
      } else {
        clearError(url);
      }
      let status = $('.status');
      if(error.status) {
        showError(status, error.status);
      }  else {
        clearError(status);
      }
      let select_data_send_method = $('#select_data_send_method');
      if(error.select_data_send_method) {
        showError(select_data_send_method, error.status);
      }  else {
        clearError(select_data_send_method);
      }
      let select_csv_send_method = $('#select_csv_send_method');
      if(error.select_csv_send_method) {
        showError(select_csv_send_method, error.status);
      }  else {
        clearError(select_csv_send_method);
      }

    }

if(id == ''){
    $('#addAmcForm').submit(function(event) {
      event.preventDefault();
      // $('#sortpicture').prop('files')[0];
      // console.log($(this).filter('#logo').prop('files'));
      $('.assetErr').hide();
      $('.holdingErr').hide();
      $('.assetErrPercent').hide();
      $('.holdingErrPercent').hide();

      // if(preventSubmit) {
      //   return;
      // }

      $.ajax({
        url: "{{ route('amc.store') }}",
        type: "POST",
        data:  new FormData(this),
        // dataType: 'json',
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
            // window.location.href = "{{ route('amc.index') }}";
          }else{
            handleValidationErrors(data.error);
          }
        },
        error: function (data) {
          Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });      

        }
      });
    });
 }else{

  $('#addAmcForm').submit(function(event) {
      event.preventDefault();
      // $('#sortpicture').prop('files')[0];
      // console.log($(this).filter('#logo').prop('files'));
      $('.assetErr').hide();
      $('.holdingErr').hide();
      $('.assetErrPercent').hide();
      $('.holdingErrPercent').hide();

      // if(preventSubmit) {
      //   return;
      // }

      var form = new FormData(this);
      form.append('_method', 'PUT');
      
      $.ajax({
        url: "{{route('amc.update', '')}}" + "/" + id,
        type: "POST",
        data: form ,
        // dataType: 'json',
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
            // window.location.href = "{{ route('amc.index') }}";
          }else{
            handleValidationErrors(data.error);
          }
        },
        error: function (data) {
          Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });      

        }
      });
    });

 }

    
    
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
  // });
</script>
@endpush