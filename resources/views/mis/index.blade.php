@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>MIS Reports</h4>
          <p>
              <a>Report Management</a> /

              <span>MIS Reports</span>
          </p>
      </div>
    </div>
  </div>
  <div class="row">
  <div class="col-12 py-4">
        <div class="accordion" id="accordionExample">
            <div class="card">
              <div>
                <div class="card-body">
                    <form action="{{route('mis.export')}}" method="GET">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="to" class="form-label">Select AMCs</label>
                            <select class="form-control" placeholder="Name" name="amc_ids[]" autocomplete="off">
                            @foreach($amcs as $amc)
                            <option value={{$amc->id}}>{{$amc->entity_name}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" name="date">
                        </div>
                        <!-- <div class="col-12 col-md-4 mt-4">
                            <label for="status" class="form-label">Transaction Status</label>
                            <select class="form-control" id="transaction_status" name="transaction_status">
                                  <option selected disabled>Select Transaction Status</option>
                                  <option value="0">Pending</option>
                                  <option value="1">Approved</option>
                                  <option value="2">Rejected</option>
                                  <option value="3">On Hold</option>
                            </select>
                        </div> -->
                        <div class="col-12 col-md-4">
                            <label for="verified" class="form-label">Verification Status</label>
                            <select class="form-control verificationSelectFilter" id="verified" placeholder="Verified" name="verified">
                            <option selected disabled value="">Select Verification Status</option>  
                            <option value="0">Not Verified</option>
                              <option value="1">Verified</option>
                              <option value="2">CSV Exported</option>
                              <option value="3">Sent In API</option>
                            </select>
                        </div>
                    </div>
                        <div class="col-12 text-right mt-3">
                        <a><button type="submit" class="btn btn-primary btn-sm">Generate Report</button></a>
                        </div>
                      </form>
                </div>
              </div>
            </div>
        </div>
      </div>
</div>
    
@endsection

@push('scripts')
  <script>
    $(function () {
      $('.btn_generate_report').click(function (e) {
        e.preventDefault();
        var form = new FormData($('#filter_users_form')[0]);
        $.ajax({
            url: "{{ route('mis.export') }}",
            type: "POST",
            data:  form,
            contentType: false,
            cache: false,
            processData:false,
            enctype: 'multipart/form-data',
            success: function (data) {
            console.log("data", data.error);
            if (!data.error) {
                Toast.fire({
                icon: 'success',
                title: data.message
                });
                $('#filter_users_form').trigger("reset");
                     var hiddenElement = document.createElement('a');  
                     hiddenElement.href = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURI(data);  
                     hiddenElement.target = '_blank';  
                     hiddenElement.download = 'mis_report.xlsx'; 
                     hiddenElement.click();  
            }else{
                console.log('error',data.error);
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
