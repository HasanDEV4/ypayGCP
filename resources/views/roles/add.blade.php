@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div
    class="ml-2 pl-1 col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div>
      <h4>Add Role</h4>
      <p>
        <a>Users </a> /
        <span>Add Role</span>
      </p>
    </div>
  </div>
  {{-- Basic --}}
  <form {{-- action="{{ route('fund.save') }}" --}} {{-- method="POST" --}} id="addRoleForm" enctype="multipart/form-data">
    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Add Role</h4>
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
            <div class="col-md-12">
              <p class="font-weight-bold">
                Role Name*
              </p>
              <input type="text" class="form-control name" id="name" placeholder="Enter Role Name" name="name" autocomplete="off" value="{{ old('name') }}">
              <div class="invalid-feedback error hide">
              </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="main-card mb-3 card">
                <div class="card-body">
                  <table class="mb-0 table datatable" style="width: 100%">
                    <thead>
                      
                      <tr>
                        <th><input type="checkbox" name="permission[]" id="checkAll"></th>
                        <th>Permissions</th>
                      </tr>
                       
                    </thead>
                    <tbody>
                      @foreach ($permissions as $permission)
                      <tr>
                        <td><input type="checkbox" name="permission[]" value="{{ $permission->id }}"></td> 
                        <td><span>{{ $permission->name }}</span></td> 
                      </tr>
                      @endforeach
                    </tbody>
                </table>
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

    $("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
    });

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
      let name = $('.name');
      if(error.name) {
        showError(name, error.name);
      } else {
        clearError(name);
      }
      let password = $('.password');
      if(error.password) {
        showError(password, error.password);
      } else {
        clearError(password);
      }
      let confirm_password = $('.confirm_password');
      if(error.confirm_password) {
        showError(confirm_password, error.confirm_password);
      } else {
        clearError(confirm_password);
      }  
    

    }


    $('#addRoleForm').submit(function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('role.store') }}",
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
                location.reload();
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