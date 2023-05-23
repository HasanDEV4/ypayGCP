@extends('layouts.app')

<style>
    .msg-column {
        word-wrap: break-word;
        word-break: break-all;
        width: 50%;

    }
</style>


@section('content')
@if (session('success'))
     <div class="alert alert-success">
         {{ session('success') }}
     </div>
@endif
@if (session('error'))
     <div class="alert alert-danger">
         {{ session('error') }}
     </div>
@endif
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1 col-7">
          <h4>Dividends</h4>
          <p>
              <a>Operations</a> /
              <span>Dividends</span>
          </p>
      </div>
      <div class="col-5 text-right">
        <a  class="btn btn-primary" href="{{route('dividend.import.log')}}">
            <i class="fas fa-history"></i>
            View Import Log
        </a>
        <a  class="btn btn-primary" id="import-btn">
            <i class="fas fa-file-import"></i>
            Import
        </a>
        <a  class="btn btn-primary" id="add-dividend-btn">
            <i class="fas fa-plus"></i>
            Add New Dividend
        </a>
      </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="form-group row mt-4">
            <label for="selected_status" class="form-label col-sm-3">Transaction Status</label>
              <div class="col-sm-5">
                <select class="form-control" id="selected_status" name="selected_status">
                      <option selected disabled value="">Select Transaction Status</option>
                      <option value="0">Pending</option>
                      <option value="1">Approved</option>
                      <option value="2">Rejected</option>
                      <option value="3">On Hold</option>
                </select>
                <div class="invalid-feedback error hide">
                </div>
              </div>
              <div class="col-12 text-right mt-3">
                      <button type="submit" class="btn btn-primary btn-sm" id="change-status-btn">Change Status</button>
              </div>
          </div>
        </div>
      </div>
    <div class="col-12 py-4">
        <div class="accordion" id="accordionExample">
            <div class="card">
              <div class="card-header bg-secondary" id="headingOne">
                <h2 class="mb-0">
                  <button class="accordion-button btn btn-link btn-block text-left text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Filter
                    <i class="fas fa-filter text-white float-right"></i>
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <form id="filter-form">
                    <div class="row g-3">
                        <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">Customer Name</label>
                          <select class="form-control customerSelectFilter" id="filter-user-id" placeholder="Name" name="filter-user-id" autocomplete="off">
                            <option selected disabled value="">Select Customer</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{ $user->full_name.'-'.$user->cust_cnic_detail?->cnic_number }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">AMC</label>
                            <select class="form-control amcSelectFilter" id="filter-amc-id" placeholder="Name" name="filter-amc-id" autocomplete="off"></select>
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">Fund Name</label>
                          <select class="form-control fundSelectFilter" id="filter-fund-id" placeholder="Name" name="filter-fund-id"></select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Transaction Date From</label>
                            <input placeholder="From" class="form-control" type="datetime-local" id="inputEmail4" name="filter-from-date">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Transaction Date To</label>
                            <input placeholder="To" type="datetime-local" class="form-control" id="inputEmail4" name="filter-to-date">
                          </div>
                        <div class="col-12 col-md-3">
                            <label for="filter-status" class="form-label">Transaction Status</label>
                            <select class="form-control" id="filter-status" name="filter-status">
                                  <option selected disabled>Select Transaction Status</option>
                                  <option value="0">Pending</option>
                                  <option value="1">Approved</option>
                                  <option value="2">Rejected</option>
                                  <option value="3">On Hold</option>
                            </select>
                          </div>
                        </div>
                      </div>
                        <div class="col-12 text-right mt-2">
                            <button type="button" class="btn btn-danger btn-sm btnResetFilter mb-1">Reset</button>
                            <button type="submit" class="btn btn-primary btn-sm btnSubmitFilter mb-1">Search</button>
                        </div>
                      </form>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th><input class="form-check-input2" type="checkbox" value="" id="select-all-dividends">Select All</th>
                              <th>Name</th>
                              <th>CNIC</th>
                              <th>AMC</th>
                              <th>Fund</th>
                              <th>NAV</th>
                              <th>Dividend Units</th>
                              <th>Capital gain tax on Dividend</th>
                              <th>Final Distributed Dividend</th>
                              <th>Status</th>
                              <th>Registered On</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Import CSV</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form action="{{route('import.dividend.csv')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group row">
                    <label for="amc_id" class="col-sm-3 col-form-label">AMC*</label>
                    <div class="col-sm-9">
                      <select class="mb-2 form-control" id="amc_id" name="amc_id">
                        <option value="" selected disabled>Select AMC</option>
                        @foreach($amcs as $amc)
                          <option value="{{$amc->id}}">{{$amc->entity_name}}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Fund IDs Available in file?</label>
                    <div class="col-sm-9">
                      <label class="radio-inline">
                        <input type="radio" name="fund_ids_available" id="fund_ids_available2" value="1" required>Yes
                      </label>
                      <label class="radio-inline">
                        <input type="radio" class="ml-1" name="fund_ids_available" id="fund_ids_available" value="0" checked>No
                      </label>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
              <div class="form-group row">
                    <label for="fund_id" class="col-sm-3 col-form-label">Fund</label>
                    <div class="col-sm-9">
                      <select class="mb-2 form-control" id="fund_id" name="fund_id">
                        <option value="" selected disabled>Select Fund ID*</option>
                        @foreach($funds as $fund)
                          <option value="{{$fund->id}}">{{$fund->fund_name}}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Upload CSV</label>
                    <div class="col-sm-9">
                      <input type="file" class="form-control h-auto" accept=".csv"  name="csv_file" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
          </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="import-csv-btn">Import</button>
                </div>
              </form>
      </div>
  </div>
</div>
<div class="modal fade" id="adddividendModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Dividend</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="adddividendform" onsubmit="event.preventDefault()" enctype="multipart/form-data">
              @csrf
              <div class="form-group row">
                    <label for="user_id" class="col-sm-3 col-form-label">User*</label>
                    <div class="col-sm-9">
                      <select class="mb-2 form-control" id="user_id" name="user_id">
                        <option value="" selected disabled>Select User</option>
                        @foreach ($users as $user)
                            <option value="{{$user->id}}">{{ $user->full_name.'-'.$user->cust_cnic_detail?->cnic_number }}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="fund_id" class="col-sm-3 col-form-label">Fund</label>
                    <div class="col-sm-9">
                      <select class="mb-2 form-control" id="fund_id3" name="fund_id">
                        <option value="" selected disabled>Select Fund ID*</option>
                        @foreach($funds as $fund)
                          <option value="{{$fund->id}}">{{$fund->fund_name}}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">NAV*</label>
                    <div class="col-sm-9">
                      <input type="number" placeholder="Enter NAV" class="form-control h-auto"  name="nav" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div> 
              <div class="form-group row">
                    <label for="unit" class="col-sm-3 col-form-label">Dividend Unit*</label>
                    <div class="col-sm-9">
                      <input type="number" placeholder="Enter Dividend Unit" class="form-control h-auto"  name="unit" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>
              <div class="form-group row">
                  <label for="status" class="col-sm-3 col-form-label">Status</label>
                  <div class="col-sm-9">
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
                    <label for="capital_gain_tax" class="col-sm-3 col-form-label">CAPITAL GAIN TAX ON DIVIDEND*</label>
                    <div class="col-sm-9">
                      <input type="text" placeholder="Enter CAPITAL GAIN TAX ON DIVIDEND" class="form-control h-auto"  name="capital_gain_tax" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
              <div class="form-group row">
                    <label for="final_distributed_dividend" class="col-sm-3 col-form-label">FINAL DISTRIBUTED DIVIDEND*</label>
                    <div class="col-sm-9">
                      <input type="text" placeholder="Enter FINAL DISTRIBUTED DIVIDEND" class="form-control h-auto"  name="final_distributed_dividend" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
              <div class="form-group row">
                    <label for="distribution_date" class="col-sm-3 col-form-label">Transaction Date*</label>
                    <div class="col-sm-9">
                      <input type="date" class="form-control h-auto"  name="distribution_date" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
          </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
              </form>
      </div>
  </div>
</div>
<div class="modal fade" id="editdividendModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Dividend</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="editdividendform" onsubmit="event.preventDefault()" enctype="multipart/form-data">
              @csrf
              <div class="form-group row">
                    <label for="user_id1" class="col-sm-3 col-form-label">User*</label>
                    <div class="col-sm-9">
                      <select class="mb-2 form-control" id="user_id1" name="user_id">
                          @foreach ($users as $user)
                              <option value="{{$user->id}}">{{ $user->full_name.'-'.$user->cust_cnic_detail?->cnic_number }}</option>
                          @endforeach
                      </select>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="fund_id" class="col-sm-3 col-form-label">Fund</label>
                    <div class="col-sm-9">
                      <select class="mb-2 form-control" id="fund_id1" name="fund_id">
                        <option value="" selected disabled>Select Fund ID*</option>
                        @foreach($funds as $fund)
                          <option value="{{$fund->id}}">{{$fund->fund_name}}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">NAV*</label>
                    <div class="col-sm-9">
                      <input type="number" placeholder="Enter NAV" class="form-control h-auto"  name="nav" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div> 
              <div class="form-group row">
                    <label for="unit" class="col-sm-3 col-form-label">Dividend Unit*</label>
                    <div class="col-sm-9">
                      <input type="number" placeholder="Enter Dividend Unit" class="form-control h-auto"  name="unit" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
              <div class="form-group row">
                  <label for="status" class="col-sm-3 col-form-label">Status</label>
                  <div class="col-sm-9">
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
                    <label for="capital_gain_tax" class="col-sm-3 col-form-label">CAPITAL GAIN TAX ON DIVIDEND*</label>
                    <div class="col-sm-9">
                      <input type="text" placeholder="Enter CAPITAL GAIN TAX ON DIVIDEND" class="form-control h-auto"  name="capital_gain_tax" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
              <div class="form-group row">
                    <label for="final_distributed_dividend" class="col-sm-3 col-form-label">FINAL DISTRIBUTED DIVIDEND*</label>
                    <div class="col-sm-9">
                      <input type="text" placeholder="Enter FINAL DISTRIBUTED DIVIDEND" class="form-control h-auto"  name="final_distributed_dividend" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
              <div class="form-group row">
                    <label for="distribution_date" class="col-sm-3 col-form-label">Transaction Date*</label>
                    <div class="col-sm-9">
                      <input type="date" class="form-control h-auto"  name="distribution_date" required>
                      <div class="invalid-feedback error hide ">
                      </div>
                    </div>
              </div>  
              <input type="text" class="form-control h-auto"  name="id" hidden>
          </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="updateBtn">Update</button>
                </div>
              </form>
      </div>
  </div>
</div>
@endsection


@push('scripts')
  <script>

    $(function () {
      $('#fund_id').select2({
        width: '100%',
        minimumInputLength: 0,
        dataType: 'json',
        placeholder: 'Select',
        ajax: {
            url: function () {
                return "{{ route('funds.autocomplete') }}";
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            }
        }
      });
      $('#fund_id3').select2({
        width: '100%',
        minimumInputLength: 0,
        dataType: 'json',
        placeholder: 'Select',
        ajax: {
            url: function () {
                return "{{ route('funds.autocomplete') }}";
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            }
        }
      });
      $('#amc_id').select2({
        width: '100%',
        minimumInputLength: 0,
        dataType: 'json',
        placeholder: 'Select',
        ajax: {
            url: function () {
                return "{{ route('amc.autocomplete') }}";
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            }
        }
      });
        $('#fund_id1').select2({
          width: '100%',
          minimumInputLength: 0,
          dataType: 'json',
          placeholder: 'Select',
          ajax: {
              url: function () {
                  return "{{ route('funds.autocomplete') }}";
              },
              processResults: function (data, page) {
                  return {
                      results: data
                  };
              }
          }
        });
      $('#user_id1').select2({
            width: '100%',
            // minimumInputLength: 0,
            // dataType: 'json',
            // placeholder: 'Select',
            // ajax: {
            //     url: function () {
            //         return "{{ route('customers.autocomplete') }}";
            //     },
            //     processResults: function (data, page) {
            //         return {
            //             results: data
            //         };
            //     }
            // }
        });
        $('#user_id').select2({
            width: '100%',
            // minimumInputLength: 0,
            // dataType: 'json',
            // placeholder: 'Select',
            // ajax: {
            //     url: function () {
            //         return "{{ route('customers.autocomplete') }}";
            //     },
            //     processResults: function (data, page) {
            //         return {
            //             results: data
            //         };
            //     }
            // }
        });
      var image_file='';
      $.fn.dataTable.ext.errMode = 'none';
      $('#add-dividend-btn').click(function (e) {
        e.preventDefault();
        $('#adddividendform').trigger("reset");
        $('#adddividendform').find($('select[name="user_id"]')).val('').trigger('change');
        $('#adddividendform').find($('select[name="fund_id"]')).val('').trigger('change');
        $('#adddividendModal').modal('show');
      });
      $('#updateBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#editdividendform')[0]);
      $.ajax({
        url: "{{ route('dividend.edit') }}",
        type: "POST",
        data:  form,
        // dataType: 'json',
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
            $('#editdividendModal').modal('hide');
            $('#editdividendform').trigger("reset");
                      // getCount();
            table.draw();
          }
        },
        error: function (data) {
          Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });      
          handleValidationErrors($('#editdividendform'),JSON.parse(data.responseText).errors);
        }
      });
    });
      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#adddividendform')[0]);
      $.ajax({
        url: "{{ route('dividend.add') }}",
        type: "POST",
        data:  form,
        // dataType: 'json',
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
            $('#adddividendModal').modal('hide');
            $('#adddividendform').trigger("reset");
                      // getCount();
            table.draw();
          }
        },
        error: function (data) {
          // Toast.fire({
          //               icon: 'error',
          //               title: 'Oops Something Went Wrong!',
          //             });      
          handleValidationErrors($('#adddividendform'),JSON.parse(data.responseText).errors);
        }
      });
    });
      $('#import-btn').click(function (e) {
        e.preventDefault();
        if($('#fund_ids_available2').is(':checked'))
        $('#fund_id').attr('disabled',true);
          else
        $('#fund_id').attr('disabled',false);
        $('#importModal').modal('show');
      });
      $('input[type="radio"]').on('change', function(e) {
          if($(this).val()==0)
          $('#fund_id').attr('disabled',false);
          else
          $('#fund_id').attr('disabled',true);
      });
      $('.datatable').click(function (e) {
        e.stopPropagation();
        });
      var table;
      var export_url='';
      var ids = [];
      var selected_dividends=[];

      function getDividends(customerName='',status='',fund='',amc='',to='',from='') {
          var url = "{{route('dividend.getData')}}";
          var query_params="?&customerName="+customerName+'&status='+status+'&fund='+fund+'&amc='+amc+'&to='+to+'&from='+from;
          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  processing: true,
                  serverSide: true,
                  ajax: {
                      url: url+query_params,
                      type: "GET",
                  },
                  columns: [
                      {
                          name: 'id',
                          data: 'id',
                          render: function (data, type, row) {
                           return `<span><input class="form-check-input" type="checkbox" value="" id="checkbox"></span>`;
                          },
                          orderable: false,
                      },
                      {
                          data: 'user.full_name',
                          name: 'user.full_name',
                      },
                      {
                          name: 'user.cust_cnic_detail.cnic_number',
                          data: 'user.cust_cnic_detail.cnic_number',
                      },
                      {
                          name: 'fund.amc.entity_name',
                          data: 'fund.amc.entity_name'
                      },
                      {
                          name: 'fund.fund_name',
                          data: 'fund.fund_name',
                      },
                      {
                          name: 'nav',
                          data: 'nav'
                      },
                      {
                          name: 'unit',
                          data: 'unit'
                      },
                      {
                          name: 'capital_gain_tax',
                          data: 'capital_gain_tax'
                      },
                      {
                          name: 'final_distributed_dividend',
                          data: 'final_distributed_dividend'
                      },
                      {
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                  return `<div class="badge badge-dark p-2">Pending</div>`;
                              } else if ( row.status == 1 ) {
                                  return `<div class="badge badge-success p-2">Approved</div>`;
                              }
                              else if ( row.status == 3 ) {
                                  return `<div class="badge badge-primary p-2" data-toggle="tooltip" data-placement="top" title="${row.response_error_message}">On Hold</div>`;
                              }
                              else{
                                return `<div class="badge badge-danger p-2" data-toggle="tooltip" data-placement="top" title="${row.response_error_message}">Rejected</div>`;
                              }
                          },
                      },
                      {
                          name: 'created_at',
                          data: 'created_at',
                          render: function (data, type, row) {
                              var date=convertUTCDateToLocalDate(new Date(row.created_at));
                              var year = date.getFullYear();
                              var month = date.getMonth() + 1;
                              var day = date.getDate();
                              var hours = date.getHours()% 12 || 12; 
                              var minutes = date.getMinutes();
                              var seconds = date.getSeconds();
                              var ampm = date.getHours() >= 12 ? 'pm' : 'am';
                              return `${year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds+' '+ampm}`;
                          },
                      },
                      {
                          data: 'action',
                          render: function (data, type, row) {
                            return `
                            <div class="btn-group dropdown" role="group">
                              <a class="btn btn-sm btn-light text-center edit" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
                                  </div>`;
                          },
                          searchable: false,
                          // orderable: false
                      },
                  ],
                  
                  select: true,
                  "order": [
                  [10, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          table.on('click', '.edit', function (e) {
            e.preventDefault();
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            console.log("data", data);
            $('#editdividendform').find($('input[name="id"]')).val(data.id);
            $('#editdividendform').find($('input[name="nav"]')).val(data.nav);
            $('#editdividendform').find($('input[name="distribution_date"]')).val(data.distribution_date);
            $('#editdividendform').find($('input[name="final_distributed_dividend"]')).val(data.final_distributed_dividend);
            $('#editdividendform').find($('input[name="capital_gain_tax"]')).val(data.capital_gain_tax);
            $('#editdividendform').find($('input[name="unit"]')).val(data.unit);
            var $newOption = $("<option selected='selected'></option>").val(data.user.id).text(data.user.full_name)
            $("#user_id1").append($newOption).trigger('change');
            // $('#user_id1').val(data.user.id).trigger('change');
            $('#editdividendform').find($('select[name="status"]')).val(data.status);
            $('#editdividendform').find($('select[name="fund_id"]')).val(data.fund.id).trigger('change');
            $('#editdividendModal').modal('show');
          });
          table.on('change', '#select-all-dividends', function (e) {
            e.preventDefault();
            if($(this).is(":checked"))
            {
              $('body #checkbox').prop('checked',true);
              $('body #checkbox').each(function(i){
                var data = table.row($(this).parents('tr')).data();
                if(data == null)
                data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
                if(!selected_dividends.includes(data.id))
                selected_dividends.push(data.id);
              });
            if(selected_dividends!='')
            $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
            $('body #checkbox').prop('checked',false);
            $('body #checkbox').each(function(i){
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
            let filtered_selected_elements = selected_dividends.filter(function(elem){
              return elem != data.id; 
            });
              selected_dividends=filtered_selected_elements;
            });
            if(selected_dividends=='')
            $('#export-selected-btn').attr('hidden',true);
            }
            });
          table.on('change', '#checkbox', function (e) {
            e.stopPropagation();
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            $(this).parents('tr').removeClass('dt-hasChild parent');
            $('.child').hide();
            if($(this).is(':checked'))
            {
            if(!selected_dividends.includes(data.id))
            selected_dividends.push(data.id);
            if(selected_dividends!='')
            $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
              let filtered_selected_elements = selected_dividends.filter(function(elem){
                return elem != data.id; 
              });
              selected_dividends=filtered_selected_elements;
              if(selected_dividends=='')
              $('#export-selected-btn').attr('hidden',true);
            }
          });
      }


      function handleValidationErrors(element, error) {
          let userInput = element.find($('select[name="user_id"]'));
          if(error.user_id) {
            userInput.addClass('is-invalid');
            userInput.next('.error').html(error.user_id);
            userInput.next('.error').removeClass('hide').addClass('show');
          } else {
            userInput.removeClass('is-invalid').addClass('is-valid');
            userInput.next('.error').html('');
            userInput.next('.error').removeClass('show').addClass('hide');
          }

          let fundInput = element.find($('select[name="fund_id"]'));
          if(error.fund_id) {
            fundInput.addClass('is-invalid');
            fundInput.next('.error').html(error.fund_id);
            fundInput.next('.error').removeClass('hide').addClass('show');
          } else {
            fundInput.removeClass('is-invalid').addClass('is-valid');
            fundInput.next('.error').html('');
            fundInput.next('.error').removeClass('show').addClass('hide');
          }

          let navInput = element.find($('input[name="nav"]'));
          if(error.nav) {
            navInput.addClass('is-invalid');
            navInput.next('.error').html(error.nav);
            navInput.next('.error').removeClass('hide').addClass('show');
          } else {
            navInput.removeClass('is-invalid').addClass('is-valid');
            navInput.next('.error').html('');
            navInput.next('.error').removeClass('show').addClass('hide');
          }

          let unitInput = element.find($('input[name="unit"]'));
          if(error.unit) {
            unitInput.addClass('is-invalid');
            unitInput.next('.error').html(error.unit);
            unitInput.next('.error').removeClass('hide').addClass('show');
          } else {
            unitInput.removeClass('is-invalid').addClass('is-valid');
            unitInput.next('.error').html('');
            unitInput.next('.error').removeClass('show').addClass('hide');
          }
          
          let capInput = element.find($('input[name="capital_gain_tax"]'));
          if(error.capital_gain_tax) {
            capInput.addClass('is-invalid');
            capInput.next('.error').html(error.capital_gain_tax);
            capInput.next('.error').removeClass('hide').addClass('show');
          } else {
            capInput.removeClass('is-invalid').addClass('is-valid');
            capInput.next('.error').html('');
            capInput.next('.error').removeClass('show').addClass('hide');
          }

          let statusInput = element.find($('input[name="status"]'));
          if(error.status) {
            statusInput.addClass('is-invalid');
            statusInput.next('.error').html(error.status);
            statusInput.next('.error').removeClass('hide').addClass('show');
          } else {
            statusInput.removeClass('is-invalid').addClass('is-valid');
            statusInput.next('.error').html('');
            statusInput.next('.error').removeClass('show').addClass('hide');
          }

          let fin_dividendInput = element.find($('input[name="final_distributed_dividend"]'));
          if(error.final_distributed_dividend) {
            fin_dividendInput.addClass('is-invalid');
            fin_dividendInput.next('.error').html(error.final_distributed_dividend);
            fin_dividendInput.next('.error').removeClass('hide').addClass('show');
          } else {
            fin_dividendInput.removeClass('is-invalid').addClass('is-valid');
            fin_dividendInput.next('.error').html('');
            fin_dividendInput.next('.error').removeClass('show').addClass('hide');
          }

          let dateInput = element.find($('input[name="distribution_date"]'));
          if(error.distribution_date) {
            dateInput.addClass('is-invalid');
            dateInput.next('.error').html(error.distribution_date);
            dateInput.next('.error').removeClass('hide').addClass('show');
          } else {
            dateInput.removeClass('is-invalid').addClass('is-valid');
            dateInput.next('.error').html('');
            dateInput.next('.error').removeClass('show').addClass('hide');
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

      function clearDatatable() {
          table.clear();
          table.destroy();
      }
    $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var customerName = $('#filter-form').find($('select[name="filter-user-id"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        var fund = $('#filter-form').find($('select[name="filter-fund-id"]')).val();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        getDividends(customerName,status,fund,amc,to,from);
    });
    
    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getDividends();
    });
    $('#change-status-btn').click(function (e) {
        e.preventDefault();
        var status=$("#selected_status").val();
        if(selected_dividends!='' && status!=null)
        {
          $.ajax({
          url: "{{ route('dividend.status.change') }}",
          type: "POST",
          data: {
                'status':status,
                'selected_dividends':selected_dividends,
                },
          success: function (data) {
            if (!data.error) {
                      Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('.datatable').DataTable().ajax.reload();
                      selected_dividends=[];
                      $('#select-all-dividends').prop('checked',false);
                      $('#checkbox').prop('checked',false);
                  } else {
                  }
          },
          error: function (data) {
            Toast.fire({
                          icon: 'error',
                          title: 'Oops Something Went Wrong!',
                        });      

          }
        });
       }
       else if(status==null)
       {
        Swal.fire(
              'Alert',
              "Please Select Transaction Status",
              "error"
          )
       }
       else
       {
        Swal.fire(
              'Alert',
              "Please Select Atleast 1 Transaction",
              "error"
          )
       }
    });
    function amcSelectFilter() {

$('.amcSelectFilter').select2({
  width: '100%',
  minimumInputLength: 0,
  dataType: 'json',
  placeholder: 'Select',
  ajax: {
      url: function () {
          return "{{ route('amc.autocomplete') }}";
      },
      processResults: function (data, page) {
          return {
              results: data
          };
      }
  }
});

}
function customerFilterDropDown() {

$('#filter-user-id').select2({
    width: '100%',
    // minimumInputLength: 0,
    // dataType: 'json',
    // placeholder: 'Select',
    // ajax: {
    //     url: function () {
    //         return "{{ route('customers.autocomplete') }}";
    //     },
    //     processResults: function (data, page) {
    //         return {
    //             results: data
    //         };
    //     }
    // }
});

}

function fundFilterDropDown() {

$('#filter-fund-id').select2({
  width: '100%',
  minimumInputLength: 0,
  dataType: 'json',
  placeholder: 'Select',
  ajax: {
      url: function () {
          return "{{ route('funds.autocomplete') }}";
      },
      processResults: function (data, page) {
          return {
              results: data
          };
      }
  }
});

}
    getDividends();
    fundFilterDropDown();
    customerFilterDropDown();
    amcSelectFilter();
    });
  </script>
@endpush
