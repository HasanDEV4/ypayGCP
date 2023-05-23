@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
  <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <a id="export-btn"  class="btn btn-primary">
            <i class='fas fa-file-export'></i>
              Export Redemptions in CSV
            </a>
             <a id="redemption_form_download"  class="btn btn-primary">
            <i class='fa fa-file-pdf-o'></i>
              Export Redemptions in PDF
            </a>
          </div>
        </div>
      </div>
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Redemptions</h4>
          <p>
              <a>Operations</a> /
              <span>Redemptions</span>
          </p>
      </div>
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addRedemptionModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>
        
        Add Redemption
      </a>
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
                            <label for="inputEmail4" class="form-label">Transaction ID</label>
                            <input placeholder="Transaction ID" type="text" name="filter-transaction-id" class="form-control " id="inputEmail4">
                          </div>
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
                            <label for="inputEmail4" class="form-label">Fund</label>
                            <select class="form-control fundSelectFilter" id="filter-fund-id" placeholder="Name" name="filter-fund-id"></select>
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
                            <label for="inputEmail4" class="form-label">CNIC</label>
                            <input type="text" class="form-control" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X"  name="filter-cnic" required="" >
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Redeem Amount</label>
                            <input placeholder="Redeem Amount" type="number" name="filter-redeem-amount" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Folio Number</label>
                            <input placeholder="Folio Number" type="text" name="filter-folio-number" class="form-control " id="filter-folio-number">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Transaction Date From</label>
                            <input placeholder="From" type="datetime-local" class="form-control " id="inputEmail4" name="filter-from-date">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Transaction Date To</label>
                            <input placeholder="To" type="datetime-local" class="form-control " id="inputEmail4" name="filter-to-date">
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
                            <label for="inputEmail4" class="form-label">Refer Code</label>
                            <input placeholder="Refer Code" type="text" name="filter-refer-code" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">Status</label>
                            <select class="mb-2 form-control" name="filter-status" autocomplete="off">
                              <option selected disabled>Select Status</option>
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
                            {{-- <th>ID</th> --}}
                            <th><input class="form-check-input2" type="checkbox" value="" id="select-all-redemptions">Select All</th>
                              <th>Transaction ID</th>
                              <!-- <th>Investment Transaction ID</th> -->
                              <th>Customer</th>
                              <th>CNIC No</th>
                              <th>Fund</th>
                              <th>Redemption Date</th>
                              <th>Amount</th>
                              <th>Redeem Amount</th>
                              <th>Redeem Units</th>
                              <!-- <th>AMC Reference Number</th> -->
                              <th>Approval Date</th>
                              <th>Profie Link</th>
                              <th>Refer Code</th>
                              <th>Verification</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
      <!-- <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <a id="send_redemption_data_amc"  class="btn btn-primary">
              Export Verified Redemptions
            </a>
          </div>
        </div>
      </div>
  </div> -->
</div>
<input type="hidden" id="redemptionId" name="id">
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addRedemptionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Redemption</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addRedemptionForm" onsubmit="event.preventDefault()">

                  <div class="form-group row">
                      <label for="name" class="col-sm-2 col-form-label">Investment Transaction ID</label>
                      <div class="col-sm-10">
                          <select class="form-control investmentSelect" id="invest_id" placeholder="Name" name="invest_id" autocomplete="off"></select>
                          <div class="invalid-feedback error hide">
                          </div>
                      </div>
                  </div>

                  <div id="customerId">
                  <div class="form-group row">
                    <label for="amount" class="col-sm-2 col-form-label">Customer</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control customer" id="customer" placeholder="Customer" name="customer" autocomplete="off" readonly>
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
                  <label for="redeem_units" class="col-sm-2 col-form-label">Redeem Units</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control redeem_units" id="redeem_units" placeholder="Redeem Units" name="redeem_units" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Approved Date</label>
                <div class="col-sm-10">
                  <input type="datetime-local" class="form-control approved_date"  id="approved_date" placeholder="Approved Date" name="approved_date" autocomplete="off">
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
            </div>

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
<div class="modal fade" id="editRedemptionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manage Redemption Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRedemptionForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">      
                   <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="status" autocomplete="off" id="status">
                                  <option selected disabled>Select Status</option>
                                  <option value="0" disabled>Pending</option>
                                  <option value="1">Approve</option>
                                  <option value="2">Reject</option>
                                  <option value="3">On Hold</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="rejected_reason_div">
                          <div class="form-group row">
                              <label for="inputPassword3" class="col-sm-2 col-form-label">Rejection Reason</label>
                              <div class="col-sm-10">
                                <textarea class="form-control rejected_reason" id="rejected_reason" placeholder="Rejection Reason" name="rejected_reason" autocomplete="off"></textarea>
                                  <div class="invalid-feedback error hide">
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div id="unitNav">
                          <div class="form-group row">
                              <label for="redeem_units" class="col-sm-2 col-form-label">Redeem Units</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control redeem_units" id="redeem_units" placeholder="Redeem Units" name="redeem_units" autocomplete="off">
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
                        <input type="number" class="form-control" name="created_at" hidden>
                        <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Approved Date</label>
                          <div class="col-sm-10">
                            <input type="datetime-local" class="form-control approved_date" id="approved_date" placeholder="Approved Date" name="approved_date" autocomplete="off">
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
                    </div>
                      </div>
                      
              </div>
              </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editRedemptionUnitModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Edit Redemption Units for PDF</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="editRedemptionUnitForm" onsubmit="event.preventDefault()">
              <div class="form-group row">
                  <label for="redeem_units" class="col-sm-2 col-form-label">Redeem Units</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control redeem_units" id="redeem_units" placeholder="Redeem Units" name="redeem_units" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
              </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="updateunitsBtn">Save Changes</button>
                </div>
              </form>
      </div>
  </div>
</div>
<!-- Department Edit Modal -->
@endsection

@push('scripts')
  <script>

    $(function () {
      $(":input").inputmask();
      $.fn.dataTable.ext.errMode = 'none';

      $('.datatable').click(function (e) {
        e.stopPropagation();
      });
      $(".rejected_reason_div").hide();
      $("#unitNav").hide();
      $('#status').change(function(){

            if($(this).val() == 1) {
              $("#unitNav").show();
              $(".rejected_reason_div").hide();
            }
            else if($(this).val() == 2)
            {
              $("#unitNav").hide();
              $(".rejected_reason_div").show();
            }
            else {
              $("#unitNav").hide();
              $(".rejected_reason_div").hide();
            }

      });

      var table;
      var ids = [];
      var export_url='';
      var units='';
      var selected_redemptions = [];
      function getRedemption(transactionId = '', customerId= '',amc='', fund= '',refer_code='', min_amount= '',max_amount='',cnic='',redeemAmount= '',from= '',to= '',folio_number='',approvedDateFrom= '',approvedDateTo= '',status= '',verified='') {

        var queryParams = '?status='+status+'&transactionId='+transactionId+'&from='+from+'&to='+to+'&customerId='+customerId+'&amc='+amc+'&fund='+fund+'&refer_code='+refer_code+'&min_amount='+min_amount+'&max_amount='+max_amount+'&redeemAmount='+redeemAmount+'&folio_number='+folio_number+'&approvedDateFrom='+approvedDateFrom+'&approvedDateTo='+approvedDateTo+'&verified='+verified+'&cnic='+cnic;
          var url = "{{route('redemptions.getData')}}";

          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  processing: true,
                  serverSide: true,
                  // dom: 'Bfrtip',
                  // buttons: [{
                  //           extend: 'excel',
                  //           text: 'Export',
                  //           className: 'btn btn-success',
                  //           exportOptions: {
                  //           columns: 'th:not(:last-child)'
                  //           }
                  // }],
                  ajax: {
                      url: url+queryParams,
                      type: "GET",
                  },
                  columns: [
                    //     {
                    //       name: 'id',
                    //       data: 'id',
                          
                    //   },
                      {
                          name: 'id',
                          data: 'id',
                          render: function (data, type, row) {
                           return `<span><input class="form-check-input" type="checkbox" value="" id="checkbox"></span>`;
                          },
                          orderable: false,
                      },
                      {
                          name: 'transaction_id',
                          data: 'transaction_id',
                          
                      },
                      // {
                      //     name: 'investment.transaction_id',
                      //     data: 'investment.transaction_id',
                          
                      // },
                      // {
                      //     name: 'users.full_name',
                      //     data: 'investment.user.full_name',
                      //     render: function(data, type, row) {
                      //       if (row.type == 'investment') {
                      //         return row.investment.user.full_name.toUpperCase();
                      //       } else {
                      //         return row.conversion.user.full_name.toUpperCase();
                      //       }
                      //     }
                          
                          
                      // }
                      {
                          name: 'investment.user.full_name',
                          data: 'investment.user.full_name',
                          render: function (data, type, row) {
                              if(row.investment != null && row.type == 'investment')
                              {
                                if(typeof row.investment.user !== 'undefined' && row.investment.user != null)
                                {
                                  var change_request_count=0;
                                  for(var i in row.investment.user.change_request)
                                  {
                                    if(row.investment.user.change_request[i].status=='0')
                                    {
                                      change_request_count++;
                                    }
                                  }
                                  if(change_request_count!=0)
                                  {
                                    return `<div class="btn-group dropdown" role="group">${row.investment.user.full_name.toUpperCase()}
                                      <a class="btn btn-sm btn-light text-center" type="button" target="_blank"><i class="fa fa-exclamation-triangle  text-info fa-lg change_req_warning" data-toggle="tooltip" data-placement="top" title="This User had Applied for Change Profile"></i></a>
                                        </div>`;
                                  }
                                  else
                                  return `${row.investment.user.full_name.toUpperCase()}`;
                                }
                              }
                              else if(row.conversion != null && row.type == 'conversion')
                              {
                                if(typeof row.conversion.user !== 'undefined' && row.conversion.user != null)
                                {
                                  var change_request_count=0;
                                  for(var i in row.conversion.user.change_request)
                                  {
                                    if(row.conversion.user.change_request[i].status=='0')
                                    {
                                      change_request_count++;
                                    }
                                  }
                                  if(change_request_count!=0)
                                  {
                                    return `<div class="btn-group dropdown" role="group">${row.conversion.user.full_name.toUpperCase()}
                                      <a class="btn btn-sm btn-light text-center" type="button" target="_blank"><i class="fa fa-exclamation-triangle  text-info fa-lg change_req_warning" data-toggle="tooltip" data-placement="top" title="This User had Applied for Change Profile"></i></a>
                                        </div>`;
                                  }
                                  else
                                  return `${row.conversion.user.full_name.toUpperCase()}`;
                               }
                              }
                              else
                              {
                                if(typeof row.dividend.user !== 'undefined' && row.dividend.user != null)
                                {
                                  var change_request_count=0;
                                  for(var i in row.dividend.user.change_request)
                                  {
                                    if(row.dividend.user.change_request[i].status=='0')
                                    {
                                      change_request_count++;
                                    }
                                  }
                                  if(change_request_count!=0)
                                  {
                                    return `<div class="btn-group dropdown" role="group">${row.dividend.user.full_name.toUpperCase()}
                                      <a class="btn btn-sm btn-light text-center" type="button" target="_blank"><i class="fa fa-exclamation-triangle  text-info fa-lg change_req_warning" data-toggle="tooltip" data-placement="top" title="This User had Applied for Change Profile"></i></a>
                                        </div>`;
                                  }
                                  else
                                  return `${row.dividend.user.full_name.toUpperCase()}`;
                               }
                              }
                              // else
                              //   {
                              //     if (row.type == 'investment') {
                              //       return `${row.investment.user.full_name.toUpperCase()}`;
                              //     } else {
                              //       return `${row.conversion.user.full_name.toUpperCase()}`;
                              //     }
                              //   }
                          },
                      }
                      ,{
                          name: 'investment.user.cust_cnic_detail.cnic_number',
                          data: 'investment.user.cust_cnic_detail.cnic_number',
                          orderable: false,
                          render: function(data, type, row) {
                            if (row.type == 'investment') {
                              return row.investment.user.cust_cnic_detail?.cnic_number;
                            }
                            else if(row.type == 'dividend') {
                              return row.dividend.user.cust_cnic_detail?.cnic_number;
                            }
                             else {
                              return row.conversion.user.cust_cnic_detail?.cnic_number;
                            }
                          }
                      }
                      ,{
                          name: 'funds.fund_name',
                          data: 'investment.fund.fund_name',
                          render: function(data, type, row) {
                            if (row.type == 'investment') {
                              return row.investment.fund.fund_name;
                            }
                            else if(row.type == 'dividend') {
                              return row.dividend.fund.fund_name;
                            }
                            else {
                              return row.conversion.fund.fund_name;
                            }
                        }
                          
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
                      }
                      ,{
                          name: 'amount',
                          data: 'amount',
                          
                      },
                      {
                          name: 'redeem_amount',
                          data: 'redeem_amount',
                          render: function (data, type, row) {
                              if(row.redeem_amount) {
                                  return `${row.redeem_amount}`;
                              } else {
                                  return `<div class="p-2">------</div>`;
                              }
                          },
                          
                      },
                      {
                          name: 'redeem_units',
                          data: 'redeem_units',
                      },
                      // {
                      //     name: 'amc_reference_number',
                      //     data: 'amc_reference_number'
                      // },
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
                          name: 'updated_at',
                          data: 'updated_at',
                          render: function (data, type, row) {
                            if (row.type == 'investment' && typeof row.investment.user !== 'undefined' && row.investment.user != null) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('view-customer-details')  
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('cust.details','/')}}/${row.investment.user.id}" target="_blank"><i class="fas fa-ellipsis-v  text-info fa-lg" title="View Customer Profile"></i></a>
                                @endcan
                                  </div>`;
                            }
                            else if(row.type == 'dividend' && typeof row.dividend.user !== 'undefined' && row.dividend.user != null)
                            {
                              return `<div class="btn-group dropdown" role="group">
                                @can('view-customer-details')  
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('cust.details','/')}}/${row.dividend.user.id}" target="_blank"><i class="fas fa-ellipsis-v  text-info fa-lg" title="View Customer Profile"></i></a>
                                @endcan
                                  </div>`;
                            }
                            else
                            {
                              return `<div class="btn-group dropdown" role="group">
                                @can('view-customer-details')  
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('cust.details','/')}}/${row.conversion.user.id}" target="_blank"><i class="fas fa-ellipsis-v  text-info fa-lg" title="View Customer Profile"></i></a>
                                @endcan
                                  </div>`;
                            }
                          },
                      },
                      {
                        name: 'investment.user.refer_code',
                        data: 'investment.user.refer_code',
                        render: function(data, type, row) {
                            if (row.type == 'investment') {
                              return row.investment.user.refer_code;
                            } 
                            else if(row.type == 'dividend') {
                              return row.dividend.user.refer_code;
                            }
                            else {
                              return row.conversion.user.refer_code;
                            }
                        }
                        
                      },
                      {
                          data: 'verification',
                          render: function (data, type, row) {
                                 if(row.verified == 0) {
                                  return `<select class="form-select redemption_verified" data-redemption_id="${row.id}">
                                            <option value="0" selected>Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2">CSV Exported</option>
                                            <option value="3">Sent In API</option>
                                          </select>`;
                                 }
                                 else if(row.verified == 1){
                                 return `<select class="form-select redemption_verified" data-redemption_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1" selected>Verified</option>
                                            <option value="2" >CSV Exported</option>
                                            <option value="3">Sent In API</option>
                                          </select>`;
                                 }
                                 else if(row.verified == 3){
                                 return `<select class="form-select redemption_verified" data-redemption_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2">CSV Exported</option>
                                            <option value="3" selected>Sent In API</option>
                                          </select>`;
                                 }
                                else
                                {
                                return `<select class="form-select redemption_verified" data-redemption_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2" selected>CSV Exported</option>
                                            <option value="3">Sent In API</option>
                                          </select>`;
                                }
                          },
                      }
                      ,
                      {
                          name: 'status',
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
                          data: 'action',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                              @can('edit-redemption')
                              <a class="btn btn-sm btn-light text-center edit" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
                              @endcan
                                      
                                  </div>`;
                          },
                          searchable: false,
                          orderable: false
                      },
                  ],
                  select: true,
                  "order": [
                    [5, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          $('#send_redemption_data_amc').click(function (e) {
              e.preventDefault();
              $.ajax({
                url: "{{ route('send.verified_redemptions') }}",
                type: "POST",
                data:  {},
                success: function (data) {
                  if (!data.error && data.redemption_csvs) {
                    for (const key in data.redemption_csvs) {
                    var hiddenElement = document.createElement('a');  
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(data.redemption_csvs[key]);  
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
          $('.datatable').on('change', '.redemption_verified', function (e) {
              e.stopPropagation();
              var verified=$(this).val();
              var redemption_id=$(this).data("redemption_id");
              $.ajax({
                url: "{{ route('verify.redemption') }}",
                type: "POST",
                data:  {
                'verified':verified,
                'redemption_id':redemption_id
                },
                success: function (data) {
                },
                error: function (data) {
                }
              });
          });
          table.on('change', '#select-all-redemptions', function (e) {
            e.preventDefault();
            if($(this).is(":checked"))
            {
              $('body #checkbox').prop('checked',true);
              $('body #checkbox').each(function(i){
                var data = table.row($(this).parents('tr')).data();
                if(data == null)
                data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
                if(!selected_redemptions.includes(data.id))
                selected_redemptions.push(data.id);
              });
            // if(selected_redemptions!='')
            // $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
            $('body #checkbox').prop('checked',false);
            $('body #checkbox').each(function(i){
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
            let filtered_selected_elements = selected_redemptions.filter(function(elem){
              return elem != data.id; 
            });
              selected_redemptions=filtered_selected_elements;
            });
            // if(selected_redemptions=='')
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
            if(!selected_redemptions.includes(data.id))
            selected_redemptions.push(data.id);
            if(selected_redemptions.length==1)
            units=data.redeem_units;
            // if(selected_redemptions!='')
            // $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
              let filtered_selected_elements = selected_redemptions.filter(function(elem){
                return elem != data.id; 
              });
              selected_redemptions=filtered_selected_elements;
              // if(selected_redemptions=='')
              // $('#export-selected-btn').attr('hidden',true);
            }
            console.log(selected_redemptions);
          });
          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            console.log("data", data);
            $('#redemptionId').val(data.id);
            $('#editRedemptionForm').find($('input[name="entity_name"]')).val(data.entity_name);
            $('#editRedemptionForm').find($('input[name="address"]')).val(data.address);
            $('#editRedemptionForm').find($('input[name="contact_no"]')).val(data.contact_no);
            $('#editRedemptionForm').find($('input[name="compliant_email"]')).val(data.compliant_email);
            $('#editRedemptionForm').find($('input[name="company_registration_number"]')).val(data.company_registration_number);
            $('#editRedemptionForm').find($('input[name="ntn"]')).val(data.ntn);
            //$('#editRedemptionForm').find($('input[name="created_at"]')).val(data.created_at);
            $('#editRedemptionForm').find($('input[name="contact_person"]')).val(data.contact_person);
            $('#editRedemptionForm').find($('input[name="amount"]')).val(data.redeem_amount);
            $('#editRedemptionForm').find($('select[name="status"]')).val(data.status);
            $('#editRedemptionForm').find($('input[name="approved_date"]')).val(data.approved_date);
            $('#editRedemptionForm').find($('input[name="redeem_units"]')).val(data.redeem_units);
            $('#editRedemptionForm').find($('textarea[name="rejected_reason"]')).val(data.rejected_reason);
            $('#editRedemptionForm').find($('input[name="amc_reference_number"]')).val(data.amc_reference_number);
            $('#editRedemptionModal').modal('show');
            if(data.status == 1) {
              $("#unitNav").show();
              $(".rejected_reason_div").hide();
            }
            else if(data.status == 2)
            {
              $(".rejected_reason_div").show();
            }
            else {
              $("#unitNav").hide();
              $(".rejected_reason_div").hide();
            }
          });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#redemptionId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {
          let selectInput = element.find($('select[name="invest_id"]'));
          if(error.invest_id) {
            selectInput.closest('div').find('.select2-selection--single').addClass('is-invalid');
            selectInput.closest('div').find('.error').html(error.invest_id);
            selectInput.closest('div').find('.error').removeClass('hide').addClass('show');
          }  else {
              selectInput.removeClass('is-invalid').addClass('is-valid');
              selectInput.next('.error').html('');
              selectInput.next('.error').removeClass('show').addClass('hide');
          }
          let rejectedreasonInput = element.find($('textarea[name="rejected_reason"]'));
          if(error.rejected_reason) {
            rejectedreasonInput.addClass('is-invalid');
            rejectedreasonInput.next('.error').html(error.rejected_reason);
            rejectedreasonInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            rejectedreasonInput.removeClass('is-invalid').addClass('is-valid');
            rejectedreasonInput.next('.error').html('');
            rejectedreasonInput.next('.error').removeClass('show').addClass('hide');
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
      $("#redemption_form_download").click(function (e) {
        e.preventDefault();
        if(selected_redemptions.length==1)
        {
          $('#editRedemptionUnitModal').modal('show');
          $('#editRedemptionUnitForm').find($('input[name="redeem_units"]')).val(units);
        }
        else if(selected_redemptions!='')
        {
          const date = new Date();
          let day = date.getDate();
          let month = date.getMonth() + 1;
          let year = date.getFullYear();
          let currentDate = `${year}${month}${day}`;
          $.ajax({
                  url: "{{ route('export.selected.redemptions') }}",
                  type: "POST",
                  data:  {
                  'selected_redemptions':selected_redemptions,
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
      function clearDatatable() {
          table.clear();
          table.destroy();
      }

      $("#addRedemptionModal, #editRedemptionModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addRedemptionForm').trigger("reset");
          $('#editRedemptionForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $(".customerSelect").empty().trigger('change');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          $(".investmentSelect").empty().trigger('change');
          resetValidationErrors($('#addRedemptionForm'))
          resetValidationErrors($('#editRedemptionForm'))
      });
      $('#updateunitsBtn').click(function(event) {
      event.preventDefault();
      var redeem_units=$('#editRedemptionUnitForm').find($('input[name="redeem_units"]')).val();
          const date = new Date();
          let day = date.getDate();
          let month = date.getMonth() + 1;
          let year = date.getFullYear();
          let currentDate = `${year}${month}${day}`;
          $('#editRedemptionUnitModal').modal('hide');
          $.ajax({
                  url: "{{ route('export.selected.redemptions') }}",
                  type: "POST",
                  data:  {
                  'selected_redemptions':selected_redemptions,
                  'redeem_units':redeem_units,
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
      });
      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addRedemptionForm')[0]);
      $.ajax({
        url: "{{ route('redemptions.store') }}",
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
            $('#addRedemptionModal').modal('hide');
            $('#addRedemptionForm').trigger("reset");
                      // getCount();
            table.draw();
          }else{
            handleValidationErrors($('#addRedemptionForm'),data.error);
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

          var id = $('#redemptionId').val();
          var form = new FormData($('#editRedemptionForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('redemptions.update', '')}}" + "/" + id;
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
                      $('#editRedemptionModal').modal('hide');
                      $('#editRedemptionForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editRedemptionForm'), data.error)
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
        var transactionId = $('#filter-form').find($('input[name="filter-transaction-id"]')).val();
        var customerId = $('#filter-form').find($('select[name="filter-user-id"]')).val();
        var fund = $('#filter-form').find($('select[name="filter-fund-id"]')).val();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var min_amount = $('#filter-form').find($('input[name="filter-min-amount"]')).val();
        var max_amount = $('#filter-form').find($('input[name="filter-max-amount"]')).val();
        var folio_number = $('#filter-form').find($('input[name="filter-folio-number"]')).val();
        var redeemAmount = $('#filter-form').find($('input[name="filter-redeem-amount"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var approvedDateFrom = $('#filter-form').find($('input[name="filter-approved-date-from"]')).val();
        var approvedDateTo = $('#filter-form').find($('input[name="filter-approved-date-to"]')).val();
        var refer_code = $('#filter-form').find($('input[name="filter-refer-code"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        var verified = $('#filter-form').find($('select[name="filter-verified"]')).val();
        var cnic = $('#filter-form').find($('input[name="filter-cnic"]')).val();
        // console.log('status', status);
        status = status != null ? status : '';
        getRedemption(transactionId, customerId,amc, fund,refer_code, min_amount,max_amount,cnic,redeemAmount,from,to,folio_number,approvedDateFrom,approvedDateTo,status,verified);
        export_url='?status='+status+'&transactionId='+transactionId+'&from='+from+'&to='+to+'&customerId='+customerId+'&amc='+amc+'&fund='+fund+'&refer_code='+refer_code+'&min_amount='+min_amount+'&max_amount='+max_amount+'&redeemAmount='+redeemAmount+'&folio_number='+folio_number+'&approvedDateFrom='+approvedDateFrom+'&approvedDateTo='+approvedDateTo+'&verified='+verified+'&cnic='+cnic;
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getRedemption();
    });
    $('#export-btn').click(function (e) {
        e.preventDefault();
        // if(export_url=="")
        // {
        //     export_url='?&customerName=&status=&fund=&refer_code=&cnic=&approvedDateFrom=&approvedDateTo=&from=&to=&amount=&unit=&nav=&amc=&reference=&verified=';
        // }
        if(selected_redemptions!='')
        {
          $.ajax({
          url: "{{ route('redem.export') }}",
          type: "POST",
          data: {
                'selected_redemptions':selected_redemptions,
                },
          success: function (data) {
            console.log("data", data);
            if (!data.error && data.redemptions_csv) {
              for (const index in data.redemptions_csv) {
              var Element = document.createElement('a');  
                      Element.href = 'data:text/csv;charset=utf-8,' + encodeURI(data.redemptions_csv[index]);  
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
    function investmentSelect() {

    $('.investmentSelect').select2({
          width: '100%',
          minimumInputLength: 0,
          dataType: 'json',
          placeholder: 'Select',
          ajax: {
            url: function () {
                return "{{ route('investment.investmentdropdown') }}";
            },
            data: function (params) {
                        var query = {
                            q: params.term,
                        }
                        return query;
                    },
            processResults: function (data, page) {
              return {
                results: data
              };
            }
      }
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



    $('#invest_id').change(function(){
      var optionDetail = $(this).select2('data')[0];
      $('#customer').val(optionDetail['customer_name']);
    });

      getRedemption();
      customerFilterDropDown();
      fundFilterDropDown();
      investmentSelect();
      customerSelect();
      amcSelectFilter();
    });
    $('[data-toggle="tooltip"]').tooltip();
  </script>
@endpush
