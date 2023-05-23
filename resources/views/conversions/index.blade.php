@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Conversions</h4>
          <p>
              <a>Operations</a> /
              <span>Conversions</span>
          </p>
      </div>
      <!-- <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addconversionModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>

        Add Conversion
      </a> -->
    </div>
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <!-- <a id="export-btn"  class="btn btn-primary">
            <i class='fas fa-file-csv' style='font-size:24px;'></i>
              Export Conversions in Excel
            </a> -->
            <a id="conversion_form_download"  class="btn btn-primary">
            <i class='fa fa-file-pdf-o ' style='font-size:24px;'></i>
              Export Conversions in PDF
            </a>
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
                        <label for="inputEmail4" class="form-label">Fund Name</label>
                        <select class="form-control fundSelectFilter" id="filter-fund-id" placeholder="Name" name="filter-fund-id"></select>
                      </div>
                      <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Folio Number</label>
                            <input placeholder="Folio Number" type="text" name="filter-folio-number" class="form-control " id="filter-folio-number">
                          </div>
                      <div class="col-12 col-md-3">
                        <label for="inputEmail4" class="form-label">Transaction Date From</label>
                        <input placeholder="From" class="form-control" type="datetime-local" id="inputEmail4" name="filter-from-date">
                      </div>
                      <div class="col-12 col-md-3">
                        <label for="inputEmail4" class="form-label">Transaction Date To</label>
                        <input placeholder="To" type="datetime-local" class="form-control" id="inputEmail4" name="filter-to-date">
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
                            <th><input class="form-check-input2" type="checkbox" value="" id="select-all-conversions">Select All</th>
                            <th>Transaction ID</th>
                            <th>Conversion Date and Time</th>
                            <th>Customer Name</th>
                            <th>Customer CNIC</th>
                            <th>Source Trx ID</th>
                            <th>Amount</th>
                            <th>Fund Out</th>
                            <th>Fund In</th>
                            <th>Verification</th>
                            <th>Transaction Status</th>
                            <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
                  <br/>
                  <!-- <a id="export-selected-btn"  class="btn btn-primary" hidden>
                  <i class='fa fa-file-pdf-o ' style='font-size:24px;'></i>
                    Export Selected Conversions
                  </a> -->
              </div>
          </div>
      </div>
  </div>
</div>
<input type="hidden" id="conversionId" name="id">
@endsection

@section('modal')
<div class="modal fade" id="addconversionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add conversion</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addconversionForm" onsubmit="event.preventDefault()">
                  <div class="form-group row">
                      <label for="name" class="col-sm-2 col-form-label">Customer</label>
                      <div class="col-sm-10">
                          <select class="form-control customerSelect" id="customerSelect" placeholder="Name" name="user_id" autocomplete="off"></select>
                          <div class="invalid-feedback error hide ">
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
<div class="modal fade" id="editconversionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manage Transaction Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editconversionForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
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
                        <input type="number" class="form-control amount" id="amount" placeholder="Amount" name="amount" autocomplete="off" disabled>
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                </div>
                <div class="approved_date">
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Approved Date</label>
                        <div class="col-sm-10">
                          <input type="date" class="form-control approved_date" id="approved_date" placeholder="Approved Date" name="approved_date" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
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
                <hr>
                <div class="form-group row">
                    <label for="from_fund" class="col-sm-2 col-form-label">Fund Out</label>
                    <div class="col-sm-4">
                    <input type="text" class="form-control from_fund" id="from_fund" placeholder="Fund Out" name="from_fund" autocomplete="off" disabled>
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                    <label for="from_fund_nav" class="col-sm-1 col-form-label">Nav</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control from_fund_nav" id="from_fund_nav" placeholder="Nav" name="from_fund_nav" autocomplete="off" disabled>
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                    <label for="from_fund_units" class="col-sm-1 col-form-label">Units</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control from_fund_units" id="from_fund_units" placeholder="Units" name="from_fund_units" autocomplete="off" disabled>
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                </div>
              <hr id="hr">
              <div id="unitNav">
                  <div class="form-group row">
                      <label for="to_fund" class="col-sm-2 col-form-label">Fund In</label>
                      <div class="col-sm-4">
                      <input type="text" class="form-control to_fund" id="to_fund" placeholder="Fund In" name="to_fund" autocomplete="off" disabled>
                          <div class="invalid-feedback error hide">
                          </div>
                      </div>
                      <label for="to_fund_nav" class="col-sm-1 col-form-label">Nav</label>
                      <div class="col-sm-2">
                          <input type="number" class="form-control to_fund_nav ml-1" id="to_fund_nav" placeholder="Nav" name="to_fund_nav" autocomplete="off">
                          <div class="invalid-feedback error hide">
                          </div>
                      </div>
                      <label for="to_fund_units" class="col-sm-1 col-form-label">Units</label>
                      <div class="col-sm-2">
                          <input type="number" class="form-control to_fund_units" id="to_fund_units" placeholder="Units" name="to_fund_units" autocomplete="off">
                          <div class="invalid-feedback error hide">
                          </div>
                      </div>
                  </div>
                </div>
                <input type="hidden" id="conversion_id" name="conversion_id">
              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Update</button>
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
      $(".approved_date").hide();
      $("#hr").hide();
      $('#status').change(function(){

        if($(this).val() == 1) {
          $("#unitNav").show();
          $(".approved_date").show();
          $(".rejected_reason_div").hide();
          $("#hr").show();
        }
        else if($(this).val() == 2)
        {
          $(".rejected_reason_div").show();
        }
        else {
          $("#unitNav").hide();
          $(".approved_date").hide();
          $(".rejected_reason_div").hide();
          $("#hr").hide();
        }

      });

      var table;
      var selected_conversions = [];
      function getConversions(customerName= '',fund= '',folio_number='',from='',to='') {

        var queryParams = '?&customerName='+customerName+'&folio_number='+folio_number+'&from='+from+'&fund='+fund+'&to='+to;
          var url = "{{route('conversions.getData')}}";

          table = $('.datatable').DataTable({
                "language": {
                    "emptyTable": "No records available"
                },
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: url+queryParams,
                    type: "GET",
                },
                columns:[
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
                  // {
                  //     name: 'user.full_name.toUpperCase()',
                  //     data: 'user.full_name.toUpperCase()',
                  // },
                  {
                      name: 'user.cust_cnic_detail.cnic_number',
                      data: 'user.cust_cnic_detail.cnic_number',
                  },
                  {
                      name: 'investment.transaction_id',
                      data: 'investment.transaction_id',
                      render: function(data, type, row) {
                        if (row.type == 'investment') {
                          return row.investment.transaction_id??'';
                        } else {
                          return row.parent.transaction_id;
                        }
                      }
                  },
                  {
                      name: 'amount',
                      data: 'amount',
                  },
                  {
                      name: 'investment.fund.fund_name',
                      data: 'investment.fund.fund_name',
                      render: function(data, type, row) {
                        if (row.type == 'investment') {
                          return row.investment.fund.fund_name;
                        } else {
                          return row.parent.fund.fund_name;
                        }
                      }
                  },
                  {
                      name: 'fund.fund_name',
                      data: 'fund.fund_name',
                  },
                  {
                      data: 'verification',
                      render: function (data, type, row) {
                              if(row.verified == 0) {
                                return `<select class="form-select conversion_verified" data-conversion_id="${row.id}">
                                          <option value="0" selected>Not Verified</option>
                                          <option value="1">Verified</option>
                                        </select>`;
                              }
                              else{
                                return `<select class="form-select conversion_verified" data-conversion_id="${row.id}">
                                          <option value="0">Not Verified</option>
                                          <option value="1" selected>Verified</option>
                                        </select>`;
                              }
                      },
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
                                  return `<div class="badge badge-primary p-2">On Hold</div>`;
                              }
                              else{
                                return `<div class="badge badge-danger p-2">Rejected</div>`;
                              }
                          },
                  },
                  {
                          data: 'action',
                          render: function (data, type, row) {
                            return `
                            <div class="btn-group dropdown" role="group">
                                <a class="btn btn-sm btn-light text-center edit" type="button" target="_blank"><i class="fas fa-edit text-info fa-lg"></i></a>
                                  </div>`;
                          },
                          searchable: false,
                          // orderable: false
                  },
                ],
                select: true,
                "order": [
                    [2, "desc"]
                ],
                searching: false,
                "iDisplayLength": 10,
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
            if(!selected_conversions.includes(data.id))
            selected_conversions.push(data.id);
            if(selected_conversions!='')
            $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
              let filtered_selected_elements = selected_conversions.filter(function(elem){
                return elem != data.id; 
              });
              selected_conversions=filtered_selected_elements;
              if(selected_conversions=='')
              $('#export-selected-btn').attr('hidden',true);
            }
          });
           $("#conversion_form_download").click(function (e) {
              e.preventDefault();
              if(selected_conversions!='')
              {
                const date = new Date();
                let day = date.getDate();
                let month = date.getMonth() + 1;
                let year = date.getFullYear();
                let currentDate = `${year}${month}${day}`;
                $.ajax({
                        url: "{{ route('export.selected.conversions') }}",
                        type: "POST",
                        data:  {
                        'selected_conversions':selected_conversions,
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
          table.on('change', '#select-all-conversions', function (e) {
            e.preventDefault();
            if($(this).is(":checked"))
            {
              $('body #checkbox').prop('checked',true);
              $('body #checkbox').each(function(i){
                var data = table.row($(this).parents('tr')).data();
                if(data == null)
                data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
                if(!selected_conversions.includes(data.id))
                selected_conversions.push(data.id);
              });
            if(selected_conversions!='')
            $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
            $('body #checkbox').prop('checked',false);
            $('body #checkbox').each(function(i){
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
            let filtered_selected_elements = selected_conversions.filter(function(elem){
              return elem != data.id; 
            });
              selected_conversions=filtered_selected_elements;
            });
            if(selected_conversions=='')
            $('#export-selected-btn').attr('hidden',true);
            }
            });
          $('.datatable').on('change', '.conversion_verified', function (e) {
              e.stopPropagation();
              var verified=$(this).val();
              var conversion_id=$(this).data("conversion_id");
              $.ajax({
                url: "{{ route('verify.conversion') }}",
                type: "POST",
                data:  {
                'verified':verified,
                'conversion_id':conversion_id
                },
                success: function (data) {
                },
                error: function (data) {
                }
              });
          });


          table.on('click', '.edit', function () {
            var current_row = $(this).parents('tr');//Get the current row
            if (current_row.hasClass('child')) {//Check if the current row is a child row
                current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
            }
            var data = table.row(current_row).data();
            console.log("data", data);
            $('#conversion_id').val(data.id);
            if (data.type == "investment") {
              var fund_name = data.investment.fund.fund_name;
              var nav = data.investment.nav;
              var unit = data.investment.unit;
            } else {
              var fund_name = data.parent.fund.fund_name;
              var nav = data.parent.nav;
              var unit = data.parent.unit;
            }
            $('#editconversionForm').find($('input[name="from_fund"]')).val(fund_name);
            $('#editconversionForm').find($('input[name="from_fund_nav"]')).val(nav);
            $('#editconversionForm').find($('input[name="from_fund_units"]')).val(unit);
            $('#editconversionForm').find($('input[name="to_fund"]')).val(data.fund.fund_name);
            $('#editconversionForm').find($('input[name="amount"]')).val(data.amount);
            $('#editconversionForm').find($('select[name="status"]')).val(data.status);
            $('#editconversionModal').modal('show');
            if(data.status == 1){
              $("#unitNav").show();
              $('#editconversionForm').find($('input[name="to_fund_nav"]')).val(data.nav);
              $('#editconversionForm').find($('input[name="to_fund_units"]')).val(data.unit);
              $('#editconversionForm').find($('input[name="approved_date"]')).val(data.approved_date);
            }else{
              $("#unitNav").hide();
            }
          });
          
          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#conversionId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
            });
          }


      function handleValidationErrors(element, error) {
          let navInput = element.find($('input[name="to_fund_nav"]'));
          if(error.nav) {
            navInput.addClass('is-invalid');
            navInput.next('.error').html(error.nav);
            navInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            navInput.removeClass('is-invalid').addClass('is-valid');
            navInput.next('.error').html('');
            navInput.next('.error').removeClass('show').addClass('hide');
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

          let unitInput = element.find($('input[name="to_fund_units"]'));
          if(error.unit) {
            unitInput.addClass('is-invalid');
            unitInput.next('.error').html(error.unit);
            unitInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            unitInput.removeClass('is-invalid').addClass('is-valid');
            unitInput.next('.error').html('');
            unitInput.next('.error').removeClass('show').addClass('hide');
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

          let to_fund_nav = element.find($('input[name="to_fund_nav"]'));
          if(error.to_fund_nav) {
            to_fund_nav.addClass('is-invalid');
            to_fund_nav.next('.error').html(error.to_fund_nav);
            to_fund_nav.next('.error').removeClass('hide').addClass('show');
          }  else {
            to_fund_nav.removeClass('is-invalid').addClass('is-valid');
            to_fund_nav.next('.error').html('');
            to_fund_nav.next('.error').removeClass('show').addClass('hide');
          }

          let to_fund_units = element.find($('input[name="to_fund_units"]'));
          if(error.to_fund_units) {
            to_fund_units.addClass('is-invalid');
            to_fund_units.next('.error').html(error.to_fund_units);
            to_fund_units.next('.error').removeClass('hide').addClass('show');
          }  else {
            to_fund_units.removeClass('is-invalid').addClass('is-valid');
            to_fund_units.next('.error').html('');
            to_fund_units.next('.error').removeClass('show').addClass('hide');
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

      $("#addconversionModal, #editconversionModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addconversionForm').trigger("reset");
          $('#editconversionForm').trigger("reset");
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
          resetValidationErrors($('#addconversionForm'))
          resetValidationErrors($('#editconversionForm'))
      });

      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addconversionForm')[0]);
      $.ajax({
        url: "{{ route('conversions.store') }}",
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
            $('#addconversionModal').modal('hide');
            $('#addconversionForm').trigger("reset");
                      // getCount();
            table.draw();
          }else{
            console.log('error',data.error);
            handleValidationErrors($('#addconversionForm'),data.error);
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
          var form = new FormData($('#editconversionForm')[0]);
          form.append('_method', 'POST');
          var url = "{{route('conversions.update')}}";
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
                      $('#editconversionModal').modal('hide');
                      $('#editconversionForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editconversionForm'), data.error)
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
        var fund = $('#filter-form').find($('select[name="filter-fund-id"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var folio_number = $('#filter-form').find($('input[name="filter-folio-number"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        getConversions(customerName,fund,folio_number,from,to);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#filter-amc-id").val('').trigger('change');
        $("#filter-user-id").val('').trigger('change');
        $("#filter-fund-id").val('').trigger('change');
        $('#filter-form').trigger("reset");
        clearDatatable();
        getConversions();
    });
    function customerSelect() {

            $('#customerSelect').select2({
                width: '100%',
                minimumInputLength: 0,
                dataType: 'json',
                placeholder: 'Select',
                ajax: {
                    url: function () {
                        return "{{ route('conversions.customerdropdown') }}";
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });
            $('#customerSelect2').select2({
                width: '100%',
                minimumInputLength: 0,
                dataType: 'json',
                placeholder: 'Select',
                ajax: {
                    url: function () {
                        return "{{ route('conversions.customerdropdown') }}";
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
function fundSelect() {

$('.to_fund').select2({
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
$('.from_fund').select2({
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
      getConversions();
      fundFilterDropDown();
      customerFilterDropDown();
      customerSelect();
      fundSelect();
    });
  </script>
@endpush
