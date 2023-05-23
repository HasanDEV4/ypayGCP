@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div class="ml-2 pl-1">
      <h4>Investment Detail</h4>
      <p>
        <a>Dashboard</a> /
        <a>Investments</a> /
        <span>Investments Detail</span>
      </p>
    </div>
  </div>
  {{-- Basic --}}
<div class="col-lg-12">
  <div class="d-flex flex-row justify-content-between mb-2">
  <h4 class="font-weight-bold mb-0">Basic Details</h4>
  <button class="btn btn-sm btn-primary edit">Edit Details</button>
  </div>
  
  </div>
  <div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Transaction ID:
          </p>
          <p class="text-muted">{{ $investment['transaction_id']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Customer:
          </p>
          <p class="text-muted">{{ $investment['user']['full_name']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Cnic Number:
          </p>
          <p class="text-muted">{{ $investment['user']['cust_cnic_detail']['cnic_number']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Fund Name:
          </p>
          <p class="text-muted">{{ $investment['fund']['fund_name']??'' }}</p>
        </div>
        <div class="col-12 col-md-3">
          <p class="font-weight-bold">
            Payment Proof:
          </p>
          @if($investment['image'])
          <p class="text-muted"><a target="_blank" href="{{ str_starts_with($investment['image'], 'http')?$investment['image']:(str_starts_with($investment['image'], '/')?env('S3_BUCKET_URL').$investment['image']:env('S3_BUCKET_URL').'/'.$investment['image']) }}" download>Download</a></span>
            @else
            ---------------
          @endif
        </div>
        <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Amount:
            </p>
            <p class="text-muted">{{ $investment['amount']??'' }}</p>
          </div>
          @if($investment['status'] == 1)
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Nav:
            </p>
            <p class="text-muted">{{ $investment['nav']??'' }}</p>
          </div>
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Approved Date:
            </p>
            <p class="text-muted">{{ $investment['approved_date']??'' }}</p>
          </div>
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Unit:
            </p>
            <p class="text-muted">{{ $investment['unit']??'' }}</p>
          </div>
          @endif
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Payment Method:
            </p>
            <p class="text-muted">{{ $investment['pay_method']??'' }}</p>
          </div>
          @if(strtolower($investment['pay_method'])=="nift")
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              RRN:
            </p>
            <p class="text-muted">{{ $investment['rrn']??'' }}</p>
          </div>
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Transaction Status:
            </p>
            <p class="text-muted">{{ $investment['transaction_status']??'' }}</p>
          </div>
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Transaction Time:
            </p>
            <p class="text-muted">{{ $investment['transaction_time']??'' }}</p>
          </div>
          @endif
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Investment Status:
            </p>
            <p class="text-muted">{!! $investment['status'] == 0 ? '<div class="badge badge-dark p-2">Pending</div>' : ($investment['status'] == 1 ? '<div class="badge badge-success p-2">Approved</div>' : ($investment['status'] == 3 ? '<div class="badge badge-primary p-2">On Hold</div>':'<div class="badge badge-danger p-2">Rejected</div>')) !!}</p>
          </div>
          @if($investment['status'] == 2)
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              Rejected Reason:
            </p>
            <p class="text-muted">{{ $investment['rejected_reason'] }}</p>
          </div>
          @endif
          @if(isset($investment['amc_reference_number']))
          <div class="col-12 col-md-3">
            <p class="font-weight-bold">
              AMC Investment Reference Number:
            </p>
            <p class="text-muted">{{ $investment['amc_reference_number'] }}</p>
          </div>
          @endif
      </div>
     
    </div>
  </div>
</div>

</div>
@endsection
@section('modal')
<!-- Amc Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change Request Status</h5> &emsp;
                <span class="text-muted">{!! $investment['status'] == 0 ? '<div class="badge badge-dark p-2">Pending</div>' : ($investment['status'] == 1 ? '<div class="badge badge-success p-2">Approved</div>' : ($investment['status'] == 3 ? '<div class="badge badge-primary p-2">On Hold</div>':'<div class="badge badge-danger p-2">Rejected</div>')) !!}</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body">
                <form id="statusForm" onsubmit="event.preventDefault()">
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                      <select class="mb-2 form-control" name="status" autocomplete="off" id="status">
                        <option selected disabled>Select Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Approve</option>
                        <option value="2">Reject</option>
                        <option value="3">On Hold</option>
                      </select>
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                  <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control amount" id="amount" value="{{ $investment['amount'] }}" placeholder="Amount" name="amount" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
                  </div>
                  <div id="unitNav">
                    <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label">Nav</label>
                      <div class="col-sm-10">
                        <input type="number" class="form-control nav" id="nav" placeholder="Nav" value="{{ $investment['nav'] }}" name="nav" autocomplete="off">
                          <div class="invalid-feedback error hide">
                          </div>
                      </div>
                    </div>
                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Unit</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control unit" id="unit" placeholder="Unit" value="{{ $investment['unit'] }}" name="unit" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Account Number</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control account_number" id="account_number" value="{{ $investment['account_number'] }}" placeholder="Account Number" name="account_number" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Approved Date</label>
                        <div class="col-sm-10">
                          <input type="date" class="form-control account_number" id="approved_date" value="{{ $investment['approved_date'] }}" placeholder="Approved Date" name="approved_date" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Reference</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control reference" id="reference" value="{{ $investment['reference'] }}" placeholder="Reference" name="reference" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Update</button>
            </div>
            <input type="hidden" id="reqId" name="id" value="{{ $investment['id'] }}">
            </form>
        </div>
    </div>
</div>
<!-- Department Edit Modal -->
@endsection
@push('scripts')
<script>
    
    $("#unitNav").hide();
    $('#status').change(function(){


            if($(this).val() == 1) {
            $("#unitNav").show();
            }
            else {
            $("#unitNav").hide();
            }

    });

    function handleValidationErrors(element, error) {

          let navInput = element.find($('input[name="nav"]'));
          if(error.nav) {
            navInput.addClass('is-invalid');
            navInput.next('.error').html(error.nav);
            navInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            navInput.removeClass('is-invalid').addClass('is-valid');
            navInput.next('.error').html('');
            navInput.next('.error').removeClass('show').addClass('hide');
          }

          let unitInput = element.find($('input[name="unit"]'));
          if(error.unit) {
            unitInput.addClass('is-invalid');
            unitInput.next('.error').html(error.unit);
            unitInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            unitInput.removeClass('is-invalid').addClass('is-valid');
            unitInput.next('.error').html('');
            unitInput.next('.error').removeClass('show').addClass('hide');
          }

          let accountNumberInput = element.find($('input[name="account_number"]'));
          if(error.account_number) {
            accountNumberInput.addClass('is-invalid');
            accountNumberInput.next('.error').html(error.account_number);
            accountNumberInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            accountNumberInput.removeClass('is-invalid').addClass('is-valid');
            accountNumberInput.next('.error').html('');
            accountNumberInput.next('.error').removeClass('show').addClass('hide');
          }

          let referenceInput = element.find($('input[name="reference"]'));
          if(error.reference) {
            referenceInput.addClass('is-invalid');
            referenceInput.next('.error').html(error.reference);
            referenceInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            referenceInput.removeClass('is-invalid').addClass('is-valid');
            referenceInput.next('.error').html('');
            referenceInput.next('.error').removeClass('show').addClass('hide');
          }

      }

      function resetValidationErrors(element)
      {
          element.find($('input')).each(function(index, el) {
              console.log('el', el)
              var el = $(el);
              el.removeClass('is-valid is-invalid');
              el.next('.error').html('');
              el.next('.error').removeClass('show').addClass('hide');
          });
          element.find($('select')).each(function(index, el) {
              var el = $(el);
              el.removeClass('is-valid is-invalid');
              el.next('.error').html('');
              el.next('.error').removeClass('show').addClass('hide');
          });
      }

  $('.edit').on('click', function () {
    $('#statusModal').modal('show');
    $('#statusForm').find($('select[name="status"]')).val({{ $investment['status']}});
    if($('#statusForm').find($('select[name="status"]')).val()=="1")
    $("#unitNav").show();
  });
   $('#editBtn').click(function (e) {
          var id = $('#reqId').val();
          var url = "{{route('investments.update', '')}}" + "/" + id;
          e.preventDefault();
          $.ajax({
              data: $('#statusForm').serialize(),
              url: url,
              type: "PUT",
              dataType: 'json',
              success: function (data) {
                  if (!data.error) {
                      Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#statusModal').modal('hide'); 
                      location.reload();
                  }else{
                    handleValidationErrors($('#statusForm'),data.error);
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