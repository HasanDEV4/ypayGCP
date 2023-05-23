@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Investments</h4>
          <p>
              <a>Operations</a> /
              <span>Investments</span>
          </p>
      </div>
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addInvestmentModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>

        Add Investment
      </a>
    </div>
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <a id="export-btn"  class="btn btn-primary">
            <i class='fas fa-file-csv' style='font-size:24px;'></i>
              Export Investments in CSV
            </a>
            <a id="investment_form_download"  class="btn btn-primary">
            <i class='fa fa-file-pdf-o ' style='font-size:24px;'></i>
              Export Investments in PDF
            </a>
            <div class="form-group row mt-4" id="export_type_div" hidden>
              <label for="select_export_type" class="col-sm-2 col-form-label">Export Type</label>
              <div class="col-sm-10">
                <select class="mb-2 form-control" name="select_export_type" autocomplete="off" id="select_export_type">
                    <option selected disabled>Select Export Type</option>
                    <option value="all">All</option>
                    <option value="selected">Selected</option>
                </select>
                <div class="invalid-feedback error hide">
                </div>
              </div>
              <div class="col-12 text-right mt-3">
                      <button type="submit" class="btn btn-primary btn-sm" id="confirm_btn">Export</button>
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
                            <label for="inputEmail4" class="form-label">Folio Number</label>
                            <input placeholder="Folio Number" type="text" name="filter-folio-number" class="form-control " id="filter-folio-number">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Min Transaction Amount</label>
                            <input placeholder="Min Transaction Amount" type="number" name="filter-min-amount" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Max Transaction Amount</label>
                            <input placeholder="Max Transaction Amount" type="number" name="filter-max-amount" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Allocated Units</label>
                            <input placeholder="Allocated Units" type="number" name="filter-units" class="form-control " id="inputEmail4">
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
                            <label for="inputEmail4" class="form-label">Initial Nav Rate</label>
                            <input placeholder="Initial Nav Rate" type="number" name="filter-nav" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">CNIC</label>
                            <input type="text" class="form-control" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X"  name="filter-cnic" required="" >
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Approval Date From</label>
                            <input  type="datetime-local" class="form-control " id="inputEmail4" name="filter-approved-date-from">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Approval Date To</label>
                            <input type="datetime-local" class="form-control " id="inputEmail4" name="filter-approved-date-to">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Transaction Reference</label>
                            <input placeholder="Transaction Reference" type="text" name="filter-reference" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Refer Code</label>
                            <input placeholder="Refer Code" type="text" name="filter-refer-code" class="form-control " id="inputEmail4">
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
                          <div class="col-12 col-md-3">
                            <label for="filter-verified" class="form-label">Verified</label>
                            <select class="form-control verificationSelectFilter" id="filter-verified" placeholder="Verified" name="filter-verified">
                            <option selected disabled value="">Select Verification</option>  
                            <option value="0">Not Verified</option>
                              <option value="1">Verified</option>
                              <option value="2">CSV Exported</option>
                              <option value="3">Sent In API</option>
                            </select>
                          </div>

                    </div>
                        <div class="col-12 text-right mt-3">
                            <button type="button" class="btn btn-danger btn-sm btnResetFilter">Reset</button>
                            <button type="submit" class="btn btn-primary btn-sm btnSubmitFilter">Search</button>
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
                  <table class="mb-0 table datatable w-100">
                      <thead>
                          <tr>
                            <th><input class="form-check-input2" type="checkbox" value="" id="select-all-investments">Select All</th>
                            <th>Customer ID</th>
                            <th>AMC Profile Status</th>
                            <th>Transaction Status</th>
                            <th>Customer Name</th>
                            <th>CNIC</th>
                            <th>Fund Name</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Payment Proof</th>
                            <th>Download Profile</th>
                            <th>Profile Link</th>
                            <th>IBAN and Bank Name</th>
                            <th>Verification</th>
                            <th>Phone No</th>
                            <th>Investment Date and Time</th>
                            <th>Transaction Id</th>
                            <th>Approval Date</th>
                            <th>AMC Ref No</th>
                            <th>User's Folio Number</th>
                            <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
                  <br/>
                  <a id="export-selected-btn"  class="btn btn-primary" hidden>
                  <i class='fa fa-file-pdf-o ' style='font-size:24px;'></i>
                    Export Selected Investments
                  </a>
              </div>
              </div>
          </div>
      </div>
  </div>
</div>
<input type="hidden" id="investmentId" name="id">
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addInvestmentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Investment</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addInvestmentForm" onsubmit="event.preventDefault()">
                <div class="form-group row">
                      <label for="name" class="col-sm-2 col-form-label">Customer</label>
                      <div class="col-sm-10">
                          <select class="form-control customerSelect" id="customerSelect" placeholder="Name" name="user_id" autocomplete="off"></select>
                          <div class="invalid-feedback error hide ">
                          </div>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="fund_id" class="col-sm-2 col-form-label">Fund</label>
                    <div class="col-sm-10">
                      <select class="form-control fundSelect" id="fundSelect" placeholder="Name" name="fund_id" autocomplete="off"></select>
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control amount" id="amount" placeholder="Amount" name="amount" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
              </div>
                  <div class="form-group row">
                      <label for="image" class="col-sm-2 col-form-label">Receipt Image</label>
                      <div class="col-sm-10">
                        <input type='file' class="form-control image h-auto" id="image" name="image" />
                        <div class="invalid-feedback error hide">
                          </div>
                      </div>
                  </div>

                  <div class="form-group row">
                    <label for="pay_method" class="col-sm-2 col-form-label">Payment method</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control pay_method" id="pay_method" placeholder="Payment Method" name="pay_method" autocomplete="off">
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="nav" class="col-sm-2 col-form-label">Nav</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control pay_method" id="nav" placeholder="Nav" name="nav" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
              </div>

              <div class="form-group row">
                <label for="unit" class="col-sm-2 col-form-label">Unit</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control pay_method" id="unit" placeholder="Unit" name="unit" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-2 col-form-label">Approved Date</label>
              <div class="col-sm-10">
                <input type="datetime-local" class="form-control approved_date" id="approved_date" placeholder="Approved Date" name="approved_date" autocomplete="off">
                  <div class="invalid-feedback error hide">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label for="inputPassword3" class="col-sm-2 col-form-label">Admin Comment</label>
              <div class="col-sm-10">
                <textarea class="form-control" placeholder="Write Your Comment" name="admin_comment" autocomplete="off"></textarea>
                  <div class="invalid-feedback error hide">
                  </div>
              </div>
          </div>
            <!-- <div class="form-group row">
              <label for="inputPassword3" class="col-sm-2 col-form-label">Account Number</label>
              <div class="col-sm-10">
                <input type="text" class="form-control account_number" id="account_number" placeholder="Account Number" name="account_number" autocomplete="off">
                  <div class="invalid-feedback error hide">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label for="inputPassword3" class="col-sm-2 col-form-label">Reference</label>
              <div class="col-sm-10">
                <input type="text" class="form-control reference" id="reference" placeholder="Reference" name="reference" autocomplete="off">
                  <div class="invalid-feedback error hide">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label for="inputPassword3" class="col-sm-2 col-form-label">AMC Reference Number</label>
              <div class="col-sm-10">
                <input type="text" class="form-control amc_reference_number" id="amc_reference_number" placeholder="AMC Reference Number" name="amc_reference_number" autocomplete="off">
                  <div class="invalid-feedback error hide">
                  </div>
              </div>
          </div> -->
          
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
                </div>
              </form>
      </div>
  </div>
</div>
<!-- Department Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="editInvestmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manage Transaction Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editinvestmentForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
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
                      <div id="commentNav">
                          <div class="form-group row">
                              <label for="inputPassword3" class="col-sm-2 col-form-label">Admin Comment</label>
                              <div class="col-sm-10">
                                <textarea class="form-control" placeholder="Write Your Comment" id="admin_comment" name="admin_comment"></textarea>
                                  <div class="invalid-feedback error hide">
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                            <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control amount" id="amount" placeholder="Amount" name="amount" autocomplete="off">
                                <div class="invalid-feedback error hide">
                                </div>
                            </div>
                      </div>
                      <div class="form-group row">
                            <label for="sales_load" class="col-sm-2 col-form-label">Sales Load</label>
                            <div class="col-sm-10">
                              <select class="mb-2 form-control" name="sales_load" autocomplete="off" id="sales_load">
                                  <option selected disabled>Select Sales Load</option>
                                  <option value="yes">Yes</option>
                                  <option value="no">No</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                            </div>
                      </div>
                  <div id="unitNav">
                        <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Nav</label>
                          <div class="col-sm-10">
                            <input type="number" class="form-control nav" style="padding-left: 0.75rem;" id="nav" placeholder=" Nav" name="nav" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                        </div>
                        <input type="number" class="form-control" name="created_at" hidden>
                          <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Unit</label>
                            <div class="col-sm-10">
                              <input type="number" class="form-control unit" id="unit" placeholder="Unit" name="unit" autocomplete="off">
                                <div class="invalid-feedback error hide">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Approved Date</label>
                          <div class="col-sm-10">
                            <input type="datetime-local" class="form-control approved_date" id="approved_date" placeholder="Approved Date" name="approved_date" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                        <!-- <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Account Number</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control account_number" id="account_number" placeholder="Account Number" name="account_number" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Reference</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control reference" id="reference" placeholder="Reference" name="reference" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">AMC Reference Number</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control amc_reference_number" id="amc_reference_number" placeholder="AMC Reference Number" name="amc_reference_number" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div> -->
                  </div>
              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
  <script>




    $(function () {
      $(":input").inputmask();
      $.fn.dataTable.ext.errMode = 'none';
      $('.datatable').click(function (e) {
        e.stopPropagation();
      });
      // $("#unitNav").hide();
      $('#status').change(function(){

          if($(this).val() == 1) {
            $("#unitNav").show();
          }
          else {
            $("#unitNav").hide();
          }
          if($(this).val() == 2 || $(this).val() == 3) {
            $("#commentNav").show();
          }
          else {
            $("#commentNav").hide();
          }

       });


      var table;
      var ids = [];
      var export_url='';
      var selected_investments = [];
      function getInvestment(customerName= '',status='', fund= '',refer_code='',folio_number='',approvedDateFrom='',approvedDateTo='',from='',to='',amc = '',cnic='', min_amount= '',max_amount='',unit = '',nav = '',reference= '',verified='') {

        var queryParams = '?&customerName='+customerName+'&status='+status+'&fund='+fund+'&refer_code='+refer_code+'&cnic='+cnic+'&folio_number='+folio_number+'&approvedDateFrom='+approvedDateFrom+'&approvedDateTo='+approvedDateTo+'&from='+from+'&to='+to+'&min_amount='+min_amount+'&max_amount='+max_amount+'&unit='+unit+'&nav='+nav+'&amc='+amc+'&reference='+reference+'&verified='+verified;
      
      console.log('queryParams',queryParams)
          var url = "{{route('investments.getData')}}";

          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  processing: true,
                  serverSide: true,
                  responsive: true,
                //   dom: 'Bfrtip',
                //   buttons: [{
                //             extend: 'excel',
                //             text: 'Export',
                //             className: 'btn btn-success',
                //             exportOptions: {
                //             columns: 'th:not(:last-child)'
                //             }
                //   }],
                  ajax: {
                      url: url+queryParams,
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
                          name: 'user.id',
                          data: 'user.id',
                      },
                      {
                          name: 'cust_status',
                          data: 'user.amc_cust_profiles.status',
                          render: function (data, type, row) {
                            if(typeof row.user !== 'undefined' && row.user != null)
                            {
                              if(typeof row.user.amc_cust_profiles !== 'undefined')
                              {
                                for(var i in row.user.amc_cust_profiles)
                                {
                                  if(row.user.amc_cust_profiles[i]?.amc_id==row.fund.amc_id)
                                  {
                                    if(row.user.amc_cust_profiles[i]?.status== -1) {
                                          return `<div class="badge badge-info p-2">Not-Started</div>`;
                                    }else if(row.user.amc_cust_profiles[i]?.status == 0){
                                        return `<div class="badge badge-secondary p-2">In-Process</div>`;
                                    }else if(row.user.amc_cust_profiles[i]?.status == 1){
                                        return `<div class="badge badge-success p-2">Accepted</div>`;
                                    }else if(row.user.amc_cust_profiles[i]?.status == 2){
                                        return `<div class="badge badge-danger p-2">Rejected</div>`;
                                    }
                                    else if(row.user.amc_cust_profiles[i]?.status == 3){
                                        return `<div class="badge badge-warning p-2">On Hold</div>`;
                                    }
                                  }
                                }
                              }
                            }
                          },
                          orderable: false
                      }
                      ,
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
                      }
                      ,
                      {
                          name: 'user.full_name.toUpperCase()',
                          data: 'user.full_name.toUpperCase()',
                          render: function (data, type, row) {
                            if(row.user.change_request.length!=0)
                            {
                              var change_request_count=0;
                              console.log(row.user.change_request);
                              for(var i in row.user.change_request)
                              {
                                if(row.user.change_request[i].status=='0')
                                {
                                  change_request_count++;
                                }
                              }
                              if(change_request_count!=0)
                              {
                                return `<div class="btn-group dropdown" role="group">${row.user.full_name.toUpperCase()}
                                  <a class="btn btn-sm btn-light text-center" type="button" target="_blank"><i class="fa fa-exclamation-triangle  text-info fa-lg change_req_warning" data-toggle="tooltip" data-placement="top" title="This User had Applied for Change Profile"></i></a>
                                    </div>`;
                              }
                              else
                              return `${row.user.full_name.toUpperCase()}`;
                            }
                            else
                                return `${row.user.full_name.toUpperCase()}`;
                          },
                      },
                      {
                          name: 'user.cust_cnic_detail.cnic_number',
                          data: 'user.cust_cnic_detail.cnic_number',
                          orderable: false,
                          
                      }
                      ,
                      {
                          name: 'fund.fund_name',
                          data: 'fund.fund_name',
                          orderable: false,
                      }
                      ,
                      {
                          name: 'amount',
                          data: 'amount',
                      }
                      ,
                      {
                          name: 'pay_method',
                          data: 'pay_method',
                          orderable: false
                      }
                      ,
                      {
                          name: 'image',
                          render: function (data, type, row) {
                              if(row.image!=null) {
                                  return `<div><a class="btn btn-sm btn-light text-center" type="button" href="${row.image.startsWith('http')?row.image:"{{env('S3_BUCKET_URL')}}"+row.image}" download><i class="fa fa-download  text-info fa-lg" title="Download Payment Proof"></i></a></div>`;
                              } else {
                                  return `<div">------</div>`;
                              }
                          },
                          orderable: false
                      },
                      {
                          name: 'profile_download',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                <a class="btn btn-sm btn-light text-center" type="button" target="_blank" id="profile_download_btn"><i class="fa fa-download  text-info fa-lg" title="Download Profile in PDF"></i></a>
                                  </div>`;
                          },
                          orderable: false
                      },
                      {
                          data: 'pay_method',
                          render: function (data, type, row) {
                            if(typeof row.user !== 'undefined' && row.user != null)
                            {
                            return `<div class="btn-group dropdown" role="group">
                                @can('view-customer-details')  
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('cust.details','/')}}/${row.user.id}" target="_blank"><i class="fas fa-ellipsis-v  text-info fa-lg" title="View Customer Profile"></i></a>
                                @endcan
                                  </div>`;
                            }
                          },
                          searchable: false,
                          orderable: false
                      },
                      {
                          name: 'pay_method',
                          data: 'user.cust_bank_detail',
                          render: function (data, type, row) {
                            if(typeof row.user !== 'undefined' && row.user != null)
                            {
                                  return `<div>${row.user.cust_bank_detail?.bank} ${row.user.cust_bank_detail?.iban}</div>`;
                            }
                      },
                          orderable: false
                      }
                      ,
                      {
                          data: 'verification',
                          render: function (data, type, row) {
                                 if(row.verified == 0) {
                                  return `<select class="form-select investment_verified" data-investment_id="${row.id}">
                                            <option value="0" selected>Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2">CSV Exported</option>
                                            <option value="3" >Sent In API</option>
                                          </select>`;
                                 }
                                 else if(row.verified == 1){
                                 return `<select class="form-select investment_verified" data-investment_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1" selected>Verified</option>
                                            <option value="2" >CSV Exported</option>
                                            <option value="3" >Sent In API</option>
                                          </select>`;
                                 }
                                else if(row.verified == 3)
                                {
                                  return `<select class="form-select investment_verified" data-investment_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2">CSV Exported</option>
                                            <option value="3" selected>Sent In API</option>
                                          </select>`;
                                }
                                else
                                {
                                return `<select class="form-select investment_verified" data-investment_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2" selected>CSV Exported</option>
                                            <option value="3" >Sent In API</option>
                                          </select>`;
                                }
                          },
                          orderable: false
                      },
                      {
                          name: 'user.phone_no',
                          data: 'user.phone_no',
                          orderable: false
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
                          name: 'transaction_id',
                          data: 'transaction_id',
                      },
                      {
                          name: 'approved_date',
                          data: 'approved_date',
                          render: function (data, type, row) {
                              if(row.approved_date) {
                                var d = new Date(row.approved_date);
                                  return `${d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate()}`;
                              } else {
                                  return `<div class="p-2">------</div>`;
                              }
                          },
                      },
                      {
                          name: 'amc_reference_number',
                          data: 'amc_reference_number',
                      },
                      {
                          name: 'folio_number',
                          data: 'user.amc_cust_profiles.account_number',
                          render: function (data, type, row) {
                            // console.log(row.user.amc_cust_profiles);
                            //return 'test';
                            if(typeof row.user !== 'undefined' && row.user != null)
                            {
                              if(typeof row.user.amc_cust_profiles !== 'undefined')
                              {
                                for(var i in row.user.amc_cust_profiles)
                                {
                                  if(row.user.amc_cust_profiles[i]?.amc_id==row.fund.amc_id)
                                  {
                                    return `<div>${row.user.amc_cust_profiles[i]?.account_number}</div>`;
                                  }
                                }
                              }
                            }
                          },
                          orderable: false
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
                    [15, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          $('#send_investment_data_amc').click(function (e) {
              e.preventDefault();
              $.ajax({
                url: "{{ route('send.verified_investments') }}",
                type: "POST",
                data:  {},
                success: function (data) {
                  if (!data.error && data.investment_csvs) {
                    if(data.kyc_csvs)
                  {
                    for (const index in data.kyc_csvs) {
                    var Element = document.createElement('a');  
                    Element.href = 'data:text/csv;charset=utf-8,' + encodeURI(data.kyc_csvs[index]);  
                    Element.target = '_blank';  
                      

                    Element.download = index; 
                    Element.click();  
                    };
                  }
                    for (const key in data.investment_csvs) {
                    var hiddenElement = document.createElement('a');  
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(data.investment_csvs[key]);  
                    hiddenElement.target = '_blank';  
                    hiddenElement.download = key; 
                    hiddenElement.click();  
                    };
                    $('.datatable').DataTable().ajax.reload();
                  }else{
                    console.log('error',data.error);
                  }
                },
                error: function (data) {
                }
              });
          });

          $('.datatable').on('change', '.investment_verified', function (e) {
              e.stopPropagation();
              var verified=$(this).val();
              var investment_id=$(this).data("investment_id");
              $.ajax({
                url: "{{ route('verify.investment') }}",
                type: "POST",
                data:  {
                'verified':verified,
                'investment_id':investment_id
                },
                success: function (data) {
                },
                error: function (data) {
                }
              });
          });
          table.on('change', '#select-all-investments', function (e) {
            e.preventDefault();
            if($(this).is(":checked"))
            {
              $('body #checkbox').prop('checked',true);
              $('body #checkbox').each(function(i){
                var data = table.row($(this).parents('tr')).data();
                if(data == null)
                data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
                if(!selected_investments.includes(data.id))
                selected_investments.push(data.id);
              });
            // if(selected_investments!='')
            // $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
            $('body #checkbox').prop('checked',false);
            $('body #checkbox').each(function(i){
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
            let filtered_selected_elements = selected_investments.filter(function(elem){
              return elem != data.id; 
            });
              selected_investments=filtered_selected_elements;
            });
            // if(selected_investments=='')
            // $('#export-selected-btn').attr('hidden',true);
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
            if(!selected_investments.includes(data.id))
            selected_investments.push(data.id);
            // if(selected_investments!='')
            // $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
              let filtered_selected_elements = selected_investments.filter(function(elem){
                return elem != data.id; 
              });
              selected_investments=filtered_selected_elements;
              // if(selected_investments=='')
              // $('#export-selected-btn').attr('hidden',true);
            }
            console.log(selected_investments);
          });
          table.on('click', '.edit', function (e) {
            e.preventDefault();
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            console.log("data", data);
            $('#investmentId').val(data.id);
            $('#editinvestmentForm').find($('input[name="nav"]')).val(data.nav);
            $('#editinvestmentForm').find($('input[name="unit"]')).val(data.unit);
            $('#editinvestmentForm').find($('input[name="amount"]')).val(data.amount);
            //$('#editinvestmentForm').find($('input[name="created_at"]')).val(data.created_at);
            $('#editinvestmentForm').find($('input[name="account_number"]')).val(data.account_number);
            $('#editinvestmentForm').find($('input[name="reference"]')).val(data.reference);
            $('#editinvestmentForm').find($('select[name="status"]')).val(data.status);
            $('#editinvestmentForm').find($('input[name="approved_date"]')).val(data.approved_date);
            $('#editinvestmentForm').find($('select[name="sales_load"]')).val(data.sales_load);
            $('#editinvestmentForm').find($('textarea[name="admin_comment"]')).val(data.admin_comment);
            // $('#editinvestmentForm').find($('input[name="company_registration_number"]')).val(data.company_registration_number);
            // $('#editinvestmentForm').find($('input[name="ntn"]')).val(data.ntn);
            // $('#editinvestmentForm').find($('input[name="contact_person"]')).val(data.contact_person);
            // $('#editinvestmentForm').find($('input[name="url"]')).val(data.url);
            $('#editInvestmentModal').modal('show');
            if(data.status == 2 || data.status == 3) {
              $("#commentNav").show();
            }
            else {
              $("#commentNav").hide();
            }
            if(data.status == 1){
              $("#unitNav").show();
            }else{
              $("#unitNav").hide();
            }
          });
          
          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#investmentId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
            });
            table.on('click', '#profile_download_btn', function (e) {
            e.preventDefault();
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            var user_id=data.user_id;
            $.ajax({
                url: "{{ route('export.profile.pdf') }}",
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
                  link.download = data.user.cust_cnic_detail.cnic_number+".pdf";
                  link.click();
                },
                error: function (data) {
                }
            });
        });
          }
      function handleValidationErrors(element, error) {

        
        let selectInput = element.find($('select[name="user_id"]'));
          if(error.user_id) {
            selectInput.closest('div').find('.select2-selection--single').addClass('is-invalid');
            selectInput.closest('div').find('.error').html(error.user_id);
            selectInput.closest('div').find('.error').removeClass('hide').addClass('show');
          }  else {
              selectInput.removeClass('is-invalid').addClass('is-valid');
              selectInput.next('.error').html('');
              selectInput.next('.error').removeClass('show').addClass('hide');
          }

          let selectFundInput = element.find($('select[name="fund_id"]'));
          if(error.fund_id) {
            selectFundInput.closest('div').find('.select2-selection--single').addClass('is-invalid');
            selectFundInput.closest('div').find('.error').html(error.fund_id);
            selectFundInput.closest('div').find('.error').removeClass('hide').addClass('show');
          }  else {
            selectFundInput.removeClass('is-invalid').addClass('is-valid');
            selectFundInput.next('.error').html('');
            selectFundInput.next('.error').removeClass('show').addClass('hide');
          }

          let amountInput = element.find($('input[name="amount"]'));
          if(error.amount) {
            amountInput.addClass('is-invalid');
            amountInput.next('.error').html(error.amount);
            amountInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            amountInput.removeClass('is-invalid').addClass('is-valid');
            amountInput.next('.error').html('');
            amountInput.next('.error').removeClass('show').addClass('hide');
          }

          let imageInput = element.find($('input[name="image"]'));
          if(error.image) {
            imageInput.addClass('is-invalid');
            imageInput.next('.error').html(error.image);
            imageInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            imageInput.removeClass('is-invalid').addClass('is-valid');
            imageInput.next('.error').html('');
            imageInput.next('.error').removeClass('show').addClass('hide');
          }

          let payMethodInput = element.find($('input[name="pay_method"]'));
          if(error.pay_method) {
            payMethodInput.addClass('is-invalid');
            payMethodInput.next('.error').html(error.pay_method);
            payMethodInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            payMethodInput.removeClass('is-invalid').addClass('is-valid');
            payMethodInput.next('.error').html('');
            payMethodInput.next('.error').removeClass('show').addClass('hide');
          }

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

          let approvedDate = element.find($('input[name="approved_date"]'));
          if(error.approved_date) {
            approvedDate.addClass('is-invalid');
            approvedDate.next('.error').html(error.approved_date);
            approvedDate.next('.error').removeClass('hide').addClass('show');
          }  else {
            approvedDate.removeClass('is-invalid').addClass('is-valid');
            approvedDate.next('.error').html('');
            approvedDate.next('.error').removeClass('show').addClass('hide');
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
      $("#investment_form_download").click(function (e) {
        e.preventDefault();
        if(selected_investments!='')
        {
          const date = new Date();
          let day = date.getDate();
          let month = date.getMonth() + 1;
          let year = date.getFullYear();
          let currentDate = `${year}${month}${day}`;
          $.ajax({
                  url: "{{ route('export.selected.investments') }}",
                  type: "POST",
                  data:  {
                  'selected_investments':selected_investments,
                  },
                  xhrFields: {
                      responseType: 'blob'
                  },
                  success: function (data) {
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = currentDate+".pdf";
                    link.click();
                  },
                  error: function (data) {
                  }
                });
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
    //   $("#investment_form_download").click(function (e) {
    //         e.preventDefault();
    //       //   var export_type=$('#select_export_type').val();
    //       //   $('#selectexporttypeModal').modal('hide');
    //       //   if(export_url=="")
    //       //   {
    //       //       export_url='?&customerName=&status=&fund=&refer_code=&cnic=&approvedDateFrom=&approvedDateTo=&from=&to=&amount=&unit=&nav=&amc=&reference=&verified=';
    //       //   }
    //       // if(export_type=="all")
    //       // window.open("/investments/form/download"+export_url);
    //       // else
    //       // {
    //         table.column(0).visible(true);
    //       // }
    // });
      $("#addInvestmentModal, #editInvestmentModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addInvestmentForm').trigger("reset");
          $('#editinvestmentForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          $(".customerSelect").empty().trigger('change');
          $(".fundSelect").empty().trigger('change');
          $(".amcSelectFilter").empty().trigger('change');
          $(".customerSelectFilter").empty().trigger('change');
          $(".fundSelectFilter").empty().trigger('change');
          $(".verificationSelectFilter").empty().trigger('change');
          resetValidationErrors($('#addInvestmentForm'))
          resetValidationErrors($('#editinvestmentForm'))
      });

      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addInvestmentForm')[0]);
      $.ajax({
        url: "{{ route('investments.store') }}",
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
            $('#addInvestmentModal').modal('hide');
            $('#addInvestmentForm').trigger("reset");
                      // getCount();
            table.draw();
          }else{
            console.log('error',data.error);
            handleValidationErrors($('#addInvestmentForm'),data.error);
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
      $('#editBtn').click(function (e) {

          var id = $('#investmentId').val();
          var form = new FormData($('#editinvestmentForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('investments.update', '')}}" + "/" + id;
          e.preventDefault();
          $.ajax({
              data: form,
              url: url,
              method : 'POST',
              dataType:'JSON',
              contentType: false,
              cache: false,
              processData: false,
              success: function (data) {
                  if (!data.error) {
                      Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#editInvestmentModal').modal('hide');
                      $('#editinvestmentForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editinvestmentForm'), data.error)
                  }
              },
              error: function (data) {
                  // $('#saveBtn').html('Save Changes');
                  Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });
              }
          });
      });

      $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var customerName = $('#filter-form').find($('select[name="filter-user-id"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        var fund = $('#filter-form').find($('select[name="filter-fund-id"]')).val();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var min_amount = $('#filter-form').find($('input[name="filter-min-amount"]')).val();
        var max_amount = $('#filter-form').find($('input[name="filter-max-amount"]')).val();
        var folio_number = $('#filter-form').find($('input[name="filter-folio-number"]')).val();
        var unit = $('#filter-form').find($('input[name="filter-units"]')).val();
        var nav = $('#filter-form').find($('input[name="filter-nav"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var approvedDateFrom = $('#filter-form').find($('input[name="filter-approved-date-from"]')).val();
        var approvedDateTo = $('#filter-form').find($('input[name="filter-approved-date-to"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var reference = $('#filter-form').find($('input[name="filter-reference"]')).val();
        var refer_code = $('#filter-form').find($('input[name="filter-refer-code"]')).val();
        var cnic = $('#filter-form').find($('input[name="filter-cnic"]')).val();
        var verified = $('#filter-form').find($('select[name="filter-verified"]')).val();
        getInvestment(customerName,status,fund,refer_code,folio_number,approvedDateFrom,approvedDateTo,from,to,amc,cnic, min_amount,max_amount,unit,nav,reference,verified);
        export_url='?&customerName='+customerName+'&status='+status+'&fund='+fund+'&refer_code='+refer_code+'&cnic='+cnic+'&folio_number='+folio_number+'&approvedDateFrom='+approvedDateFrom+'&approvedDateTo='+approvedDateTo+'&from='+from+'&to='+to+'&min_amount='+min_amount+'&max_amount='+max_amount+'&unit='+unit+'&nav='+nav+'&amc='+amc+'&reference='+reference+'&verified='+verified;
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#filter-amc-id").val('').trigger('change');
        $("#filter-user-id").val('').trigger('change');
        $("#filter-fund-id").val('').trigger('change');
        $('#filter-form').trigger("reset");
        clearDatatable();
        getInvestment();
    });
    $('#export-btn').click(function (e) {
        e.preventDefault();
        // if(export_url=="")
        // {
        //     export_url='?&customerName=&status=&fund=&refer_code=&cnic=&approvedDateFrom=&approvedDateTo=&from=&to=&amount=&unit=&nav=&amc=&reference=&verified=';
        // }
        if(selected_investments!='')
        {
          $.ajax({
          url: "{{ route('invest.export') }}",
          type: "POST",
          data: {
                'selected_investments':selected_investments,
                },
          success: function (data) {
            console.log("data", data);
            if (!data.error && data.investment_csv) {
              for (const index in data.investment_csv) {
              var Element = document.createElement('a');  
                      Element.href = 'data:text/csv;charset=utf-8,' + encodeURI(data.investment_csv[index]);  
                      Element.target = '_blank';  
                      Element.download = index; 
                      Element.click(); 
              }
            }else{
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
       else
       {
        Swal.fire(
              'Alert',
              "Please Select Atleast 1 Transaction",
              "error"
          )
       }
    });
    function customerSelect() {

            $('#customerSelect').select2({
                width: '100%',
                minimumInputLength: 0,
                dataType: 'json',
                placeholder: 'Select',
                ajax: {
                    url: function () {
                        return "{{ route('investment.customerdropdown') }}";
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });

    }

    function fundSelect() {

        $('.fundSelect').select2({
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

      getInvestment();
      customerFilterDropDown();
      fundFilterDropDown();
      amcSelectFilter();
      customerSelect();
      fundSelect();
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
@endpush
