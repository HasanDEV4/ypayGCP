@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div
    class="ml-2 pl-1 col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div>
      <h4>Push Notification</h4>
      <p>
        <a>Dashboard</a> /
        <span>Push Notification</span>
      </p>
    </div>
  </div>
  {{-- Basic --}}
  <form {{-- action="{{ route('fund.save') }}" --}} {{-- method="POST" --}} id="addAmcForm" enctype="multipart/form-data">
    <div class="col-lg-12">
      <div class="d-flex flex-row justify-content-between mb-2">
        <h4 class="font-weight-bold mb-0">Notification</h4>
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
            <div class="col-12 col-md-6">
              <p class="font-weight-bold">
                Title*
              </p>
              <input type="text" class="form-control title" id="title" placeholder="Enter title" name="title" autocomplete="off" >
              <div class="invalid-feedback error hide">
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <p class="font-weight-bold">
                Image
              </p>
              <input type='file' name="image" class="form-control image h-auto" id="image" autocomplete="off">
              <div class="invalid-feedback error hide">
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12 col-md-12">
                <p class="font-weight-bold">
                  Message*
                </p>
                <textarea  class="form-control message" id="message" placeholder="Enter Message" name="message" rows="4" cols="50"></textarea>
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
      let title = $('.title');
      if(error.title) {
        showError(title, error.title);
      } else {
        clearError(title);
      }
      let message = $('.message');
      if(error.message) {
        showError(message, error.message);
      } else {
        clearError(message);
      }

      let image = $('.image');
      if(error.image) {
        showError(image, error.image);
      } else {
        clearError(image);
      }
     

    }



    $('#addAmcForm').submit(function(event) {
      event.preventDefault();

      $.ajax({
        url: "{{ route('send.notification') }}",
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
            $('#addAmcForm').trigger("reset");
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



</script>
@endpush