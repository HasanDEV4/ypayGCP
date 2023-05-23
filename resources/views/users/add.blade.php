@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div
    class="ml-2 pl-1 col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div>
      <h4>Add New User</h4>
      <p>
        <a>Users </a> /
        <span>Add New User</span>
      </p>
    </div>
  </div>
  {{-- Basic --}}
  <form {{-- action="{{ route('fund.save') }}" --}} {{-- method="POST" --}} id="addUserForm" enctype="multipart/form-data">
    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Add New User</h4>
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
      <div class="structureError1">

    </div>
      {{ csrf_field() }}
      <div class="main-card mb-3 card">
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Name*
              </p>
              <input type="text" class="form-control full_name" id="full_name" placeholder="Enter Full Name" name="full_name" autocomplete="off" value="{{ old('full_name') }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                Email*
              </p>
              <input type="email" class="form-control email" id="email" placeholder="Enter Email" name="email" autocomplete="off" value="{{ old('email') }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
              <p class="font-weight-bold">
                 Password*
              </p>
              <input type="text" class="form-control password" id="password" placeholder="Enter Confirm Password" name="password" autocomplete="off" value="{{ old('password') }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
            <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                   Status*
                </p>
                <select class="mb-2 form-control status" name="status" autocomplete="off">
                    <option selected disabled>Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">In-Active</option>
                </select>
                <div class="invalid-feedback error hide">
                </div>
              </div>
              <div class="col-12 col-md-3">
                <p class="font-weight-bold">
                   Role*
                </p>
                <select class="mb-2 form-control role" name="role" autocomplete="off">
                    <option selected disabled>Select Role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback error hide">
                </div>
              </div>
            
          </div>
        </div>
      </div>
    </div>
    
   
    <div class="col-12 mt-2 mb-4 text-right">
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

    // var id = $('#changePasswordForm').find($('input[name="id"]')).val();
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
      let full_name = $('.full_name');
      if(error.full_name) {
        showError(full_name, error.full_name);
      } else {
        clearError(full_name);
      }
      let password = $('.password');
      if(error.password) {
        showError(password, error.password);
      } else {
        clearError(password);
      }
      let email = $('.email');
      if(error.email) {
        showError(email, error.email);
      } else {
        clearError(email);
      }  
      let status = $('.status');
      if(error.status) {
        showError(status, error.status);
      } else {
        clearError(status);
      }  
      let role = $('.role');
      if(error.role) {
        showError(role, error.role);
      } else {
        clearError(role);
      }  
    

    }


    $('#addUserForm').submit(function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('user.store') }}",
        type: "POST",
        data:  new FormData(this),
        // dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        enctype: 'multipart/form-data',
        success: function (data) {
            if (!data.error) {
                Toast.fire({
                    icon: 'success',
                    title: data.message
                });
                // window.location.href = "{{ route('user.index') }}";
                // $('#changePasswordForm').trigger("reset");
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