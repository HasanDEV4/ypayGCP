@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div class="ml-2 pl-1">
      <h4>Requests Detail</h4>
      <p>
        <a>Dashboard</a> /
        <a>Customers</a> /
        <span>Requests Detail</span>
      </p>
    </div>
  </div>
  {{-- Basic --}}
<div class="col-lg-12">
  <div class="d-flex flex-row justify-content-between mb-2">
  <h4 class="font-weight-bold mb-0">Basic Details</h4>
  @can('edit-customer-details') 
  <div><a class="btn btn-sm btn-warning" href="{{ route('cust.edit.details', $user->id) }}">Edit</a>
  @endcan
  @can('edit-customer-status')
  <button class="btn btn-sm btn-primary edit">Change Status</button>
  @endcan
  </div>
  
  </div>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Name:
          </p>
          <p class="text-muted">{{ $user->full_name }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Email:
          </p>
          <p class="text-muted">{{ $user->email }}</p>
        </div>
        
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Father Name:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['father_name'] }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Mother Name:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['mother_name'] ? $user->cust_basic_detail['mother_name'] : ''}}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Gender:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['gender'] ? $user->cust_basic_detail['gender'] : ''}}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            D.O.B:
          </p>
          <p class="text-muted">{{\Carbon\Carbon::parse($user->cust_basic_detail['dob'])->format('d/m/y')  }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Nationality:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['nationality'] ? $user->cust_basic_detail['nationality'] : ''}}</p>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12">
          <p class="font-weight-bold">
            Current Address:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['current_address'] }}</p>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Source of income(old):
          </p>
          <p class="text-muted">{{ ucwords($user->cust_basic_detail['source_of_income']) }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Nominee:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['nominee_name'] }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Nominee CNIC Number:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['nominee_cnic'] }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Source of Income (New):
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail->income_sources['income_name']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Occupation:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail->occupations['name']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            City
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail->cities['city']??'' }}</p>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            State:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail->cities['state']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Phone No:
          </p>
          <p class="text-muted">{{ $user->phone_no }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Refer Code:
          </p>
          <p class="text-muted">{{ $user->refer_code ? $user->refer_code : '-'}}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
          Zakat Deduction:
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail['zakat'] == 1 ? "YES" : "NO"}}</p>
        </div>
        @if(isset($user->cust_basic_detail['cz_form']))
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CZ Form:
          </p>
          <p class="text-muted"><a href="{{ str_starts_with($user->cust_basic_detail['cz_form'], 'http')?$user->cust_basic_detail['cz_form']:env('S3_BUCKET_URL').'/'.$user->cust_basic_detail['cz_form'] }}" download>Download</a></span>
        </div>
        @endif
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
            CNIC Number:
          </p>
          <p class="text-muted">{{ $user->cust_cnic_detail['cnic_number'] }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Citizenship Status:
          </p>
          <p class="text-muted">{{ $user->cust_cnic_detail['citizenshipstatus']['status'] ? $user->cust_cnic_detail['citizenshipstatus']['status'] :''}}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Country of Residence:
          </p>
          <p class="text-muted">{{ $user->cust_cnic_detail['country_of_residence'] ? $user->cust_cnic_detail['country_of_residence'] : ''}}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            TaxPayer Identification Number:
          </p>
          <p class="text-muted">{{ (strtolower($user->cust_cnic_detail['country_of_residence'])=="usa" || $user->cust_cnic_detail['citizenship_status']=="2") ? $user->cust_cnic_detail['taxpayer_identification_number'] : 'N/A'}}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Passport Number:
          </p>
          <p class="text-muted">{{ $user->cust_cnic_detail['passport_number'] ??''}}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Issue Date:
          </p>
          <p class="text-muted">{{\Carbon\Carbon::parse($user->cust_cnic_detail['issue_date'])->format('d/m/y') }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Expiry Date:
          </p>
          <p class="text-muted">{{\Carbon\Carbon::parse($user->cust_cnic_detail['expiry_date'])->format('d/m/y') }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Front Image:
          </p>
          <p class="text-muted"><a href="{{ str_starts_with($user->cust_cnic_detail['cnic_front'], 'http')?$user->cust_cnic_detail['cnic_front']:env('S3_BUCKET_URL').'/'.$user->cust_cnic_detail['cnic_front'] }}" download>Download</a></span>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            CNIC Back Image:
          </p>
          @if($user->cust_cnic_detail['cnic_back'])
          <p class="text-muted"><a href="{{ str_starts_with($user->cust_cnic_detail['cnic_back'], 'http')?$user->cust_cnic_detail['cnic_back']:env('S3_BUCKET_URL').'/'.$user->cust_cnic_detail['cnic_back'] }}" download>Download</a></span>
            @else
            ---------------
          @endif
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
          Proof of Income / Salary Slip:
          </p>
          @if($user->cust_cnic_detail['income'])
          <p class="text-muted"><a href="{{ str_starts_with($user->cust_cnic_detail['income'], 'http')?$user->cust_cnic_detail['income']:env('S3_BUCKET_URL').'/'.$user->cust_cnic_detail['income'] }}" download>Download</a></span>
            @else
            ---------------
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
            IBAN:
          </p>
          <p class="text-muted">{{ $user->cust_bank_detail['iban'] }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Bank(old):
          </p>
          <p class="text-muted">{{ $user->cust_bank_detail['bank'] }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Bank Account Number:
          </p>
          <p class="text-muted">{{ $user->cust_bank_detail['bank_account_number']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Bank (New):
          </p>
          <p class="text-muted">{{ $user->cust_basic_detail->banks['name']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Branch:
          </p>
          <p class="text-muted">{{ $user->cust_bank_detail['branch'] }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
  {{-- Account --}}
<div class="col-lg-12">
  <h4 class="font-weight-bold">Account Details</h4>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Registered On:
          </p>
          <p class="text-muted">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/y') }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Last Action:
          </p>
          <p class="text-muted">{{\Carbon\Carbon::parse($user->cust_account_detail['action_time'])->format('d/m/y H:i:s') ?? 'N/A' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Profile Status:
          </p>
          <p class="text-muted">{!! $user->cust_account_detail['status'] == 0 ? '<div class="badge badge-primary p-2">Pending</div>' : ($user->cust_account_detail['status'] == 1 ? '<div class="badge badge-success p-2">Approved</div>' : ($user->cust_account_detail['status'] == 2 ? '<div class="badge badge-danger p-2">Rejected</div>': '<div class="badge badge-danger p-2">On Hold</div>'))  !!}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@if(count($admin_comments)!=0)
<div class="col-lg-12">
  <h4 class="font-weight-bold mb-0">Comments</h4>
  </div>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12">
        <div id="comments_div">
        <table class="mb-0 table datatable w-100" style="border: 1px solid black;">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Comment</th>
                  <th>Comment By</th>
                  <th>Comment Date</th>
              </tr>
          </thead>
          <tbody>
          @foreach($admin_comments as $index=>$value)
          <tr>
                        <td>{{$index+1}}</td>
                        <td>{{$value->comment}}</td>
                        <td>{{$value->commented_by->full_name}}</td>
                        <td>{{date('Y-m-d',strtotime($value->created_at))}}</td>
          </tr>
          @endforeach
          </tbody></table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
  @if(count($facta_response)!=0)
  <div class="col-lg-12">
    <h4 class="font-weight-bold">Facta/CRS</h4>
    <div class="main-card mb-3 card">
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-12">
          <div id="comments_div">
          <table class="mb-0 table datatable w-100" style="border: 1px solid black;">
            <thead>
                <tr>
                    <th colspan="2" class="text-center">Facta/CRS Details</th>
                </tr>
            </thead>
            <tbody>
            <tr>
              <td>Citizenship Status</td>
              <td>{{strtoupper($user->cust_cnic_detail['citizenshipstatus']['status'])}}</td>
            </tr>
            <tr>
              <td>Country of Residance</td>
              <td>{{strtoupper($user->cust_cnic_detail['country_of_residence'])}}</td>
            </tr>
            <tr>
              <td>Passport Number</td>
              <td>{{$user->cust_cnic_detail['passport_number']??''}}</td>
            </tr>
            @foreach($facta_response as $index=>$response)
            <tr>
              <td>{{$facta_crs_questions[$response->question_id]}}</td>
              @if($response->answer=="0")
              <td>No</td>
              @else
              <td>Yes</td>
              @endif
            </tr>
            @endforeach
            <tr>
              <td>Taxpayer Identification Number</td>
              @if(strtolower($user->cust_cnic_detail['country_of_residence'])=="usa" || $user->cust_cnic_detail['citizenship_status']=="2")
                <td>{{$user->cust_cnic_detail['taxpayer_identification_number']}}</td>
              @else
                <td>N/A</td>
              @endif
            </tr>
            </tbody></table>
          </div>
          </div>
        </div>
        <a id="export-facta-details-btn"  class="btn btn-primary"  data-userid="{{$user->id}}">
          <i class='fa fa-file-pdf-o' style='font-size:24px;'></i>
          Export FACTA/CRS DETAILS
        </a>
        <a  class="btn btn-primary"  href="{{route('edit.facta.details','').'/'.$user->id}}">
          <i class='fas fa-edit' style='font-size:24px;'></i>
          Edit FACTA/CRS DETAILS
        </a>
      </div>
    </div>
  </div>
</div>
@endif
@endsection
@section('modal')
<!-- Amc Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change Status Request</h5> &emsp;
                <span class="text-muted">{!! $user->cust_account_detail['status'] == 0 ? '<div class="badge badge-primary p-2">Pending</div>' : ($user->cust_account_detail['status'] == 1 ? '<div class="badge badge-success p-2">Approved</div>' : '<div class="badge badge-danger p-2">On Hold</div>') !!}</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <form id="statusForm" onsubmit="event.preventDefault()">
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                      <select class="mb-2 form-control" name="status" autocomplete="off">
                        <option selected disabled>Select Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Approve</option>
                        {{-- <option value="2">Rejected</option> --}}
                        <option value="3">On Hold</option>
                      </select>
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                  </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Update</button>
            </div>
            <input type="hidden" id="reqId" name="id" value="{{ $user->cust_account_detail['id'] }}">
            </form>
        </div>
    </div>
</div>
<!-- Department Edit Modal -->
@endsection
@push('scripts')
<script>
   $('#export-facta-details-btn').on('click', function () {
     var user_id=$(this).data('userid');
                $.ajax({
                url: "{{ route('export.facta.details') }}",
                type: "POST",
                data:  {
                'user_id':user_id,
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                  var blob = new Blob([response]);
                  var link = document.createElement('a');
                  link.href = window.URL.createObjectURL(blob);
                  link.download = "facta_crs_details.pdf";
                  link.click();
                },
                error: function (data) {
                }
            });
   });
  $('.edit').on('click', function () {
    $('#statusModal').modal('show');
    $('#statusForm').find($('select[name="status"]')).val({{ $user->cust_account_detail['status']}});
  });
   $('#editBtn').click(function (e) {
          var id = $('#reqId').val();
          var url = "{{route('details.status')}}";
          e.preventDefault();
          $.ajax({
              data: $('#statusForm').serialize(),
              url: url,
              type: "POST",
              dataType: 'json',
              success: function (data) {
                  if (!data.error) {
                      Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#statusModal').modal('hide'); 
                      location.reload();
                  }
              },
              error: function (data) {
                // alert('something went wrong!')
                  // $('#saveBtn').html('Save Changes');

                  Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });
              }
          });
      });
</script>
@endpush