@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Customer Verification</h4>
          <p>
              <a>AMC Management</a> /

              <span>Customer Verification</span>
          </p>
      </div>
       
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addCustAmcModal" data-backdrop="true">
          <i class="fas fa-plus mr-2"></i>
          Add Customer Amc Profile
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
                          <label for="inputEmail4" class="form-label">Amc</label>
                          <select class="form-control amcFilterSelect" id="filter-amc-id" placeholder="Name" name="filter-amc-id" autocomplete="off"></select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Customer</label>
                            <select class="form-control customerFilterSelect" id="filter-user-id" placeholder="Name" name="filter-user-id" autocomplete="off"></select>
                          </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Account Number</label>
                            <input type="text" class="form-control account_number" id="filter-account-number" placeholder="Account Number" name="filter-account-number" autocomplete="off">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Folio Number</label>
                            <input placeholder="Folio Number" type="text" name="filter-folio-number" class="form-control " id="filter-folio-number">
                          </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Rejected Reason</label>
                            <input type="text" class="form-control reference" id="filter-rejected-reason" placeholder="Rejected Reason" name="filter-rejected-reason" autocomplete="off">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Reference</label>
                            <input type="text" class="form-control reference" id="filter-reference" placeholder="Reference" name="filter-reference" autocomplete="off">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">Status</label>
                            <select class="mb-2 form-control" name="filter-status" autocomplete="off">
                              <option selected disabled>Select Status</option>
                              <option selected disabled>Select Status</option>
                              <option value="-1">Not Started</option>
                              <option value="0">In Process</option>
                              <option value="1">Approved</option>
                              <option value="2">Rejected</option>
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
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th>AMC</th>
                              <th>Customer</th>
                              <th>CNIC</th>
                              <th>Rejected Reason</th>
                              <th>Status</th>
                              <th>Verification</th>
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
<input type="hidden" id="custAmcId" name="id">
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addCustAmcModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add Customer Amc Profile</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              
              <div class="modal-body">
                <div class="invalid-feedback error1 hide text-center">
                    <p>Profile Already exists to this Amc.</p>
                </div>
                  <form id="addCustAmcForm" onsubmit="event.preventDefault()">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Amc</label>
                        <div class="col-sm-10">
                            <select class="form-control amcSelect" id="amc_id" placeholder="Name" name="amc_id" autocomplete="off"></select>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10">
                            <select class="form-control customerSelect" id="user_id" placeholder="Name" name="user_id" autocomplete="off"></select>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>

                      {{-- <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="status" id="status" autocomplete="off">
                                  <option selected disabled>Select Status</option>
                                  <option value="-1">Not Started</option>
                                  <option value="0">In Process</option>
                                  <option value="1">Accepted</option>
                                  <option value="2">Rejected</option>
                                  <option value="3">On Hold</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row" id="rejectedField">
                        <label for="name" class="col-sm-2 col-form-label">Rejected Reason</label>
                        <div class="col-sm-10">
                            <textarea class="form-control name" id="name" placeholder="Please enter reason of rejection" name="rejected_reason"></textarea>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Account Number</label>
                        <div class="col-sm-10">
                            <input class="form-control account_number" id="account_number" placeholder="Account Number" name="account_number">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">AMC Reference Number</label>
                        <div class="col-sm-10">
                            <input class="form-control reference" id="reference" placeholder="Reference Id" name="reference">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="account_type" class="col-sm-2 col-form-label">Account Type*</label>
                        <div class="col-sm-10">
                            <select class="mb-2 form-control" id="account_type" name="account_type">
                                <option value="" selected disabled>Select Account Type</option>
                                @foreach ( $account_types as $account_type)
                                <option value="{{ $account_type->id}}">{{ $account_type->account_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
              </div>
          </div>
      </div>
</div>
<!-- Department Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="editCustAmcModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Customer Amc Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCustAmcForm" onsubmit="event.preventDefault()">
                    {{-- <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Amc</label>
                        <div class="col-sm-10">
                            <select class="form-control amcSelect" id="amc_id" placeholder="Name" name="amc_id" autocomplete="off"></select>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control customerSelect" id="user_id" placeholder="Name" name="user_id" autocomplete="off" disabled>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div> --}}

                      <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="mb-2 form-control" id="editStatus" name="status" autocomplete="off">
                                <option selected disabled>Select Status</option>
                                <option value="-1">Not Started</option>
                                <option value="0">In Process</option>
                                <option value="1">Accepted</option>
                                <option value="2">Rejected</option>
                                <option value="3">On Hold</option>
                                
                            </select>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div id="rejectedEditField">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Rejected Reason</label>
                        <div class="col-sm-10">
                            <textarea class="form-control name" id="rejected_reason" placeholder="Please enter reason of rejection" name="rejected_reason"></textarea>
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Account Number</label>
                    <div class="col-sm-10">
                        <input class="form-control account_number" id="account_number" placeholder="Account Number" name="account_number">
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">AMC Reference Number</label>
                    <div class="col-sm-10">
                        <input class="form-control reference" id="reference" placeholder="Reference Id" name="reference">
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="account_type" class="col-sm-2 col-form-label">Account Type*</label>
                    <div class="col-sm-10">
                        <select class="mb-2 form-control" id="account_type" name="account_type">
                            <option value="" selected disabled>Select Account Type</option>
                            @foreach ( $account_types as $account_type)
                            <option value="{{ $account_type->id}}">{{ $account_type->account_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback error hide">
                        </div>
                    </div>
                </div>
                  </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- Department Edit Modal -->
@endsection

@push('scripts')
  <script>
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';
        $('.datatable').click(function (e) {
            e.stopPropagation();
        });
        $("#rejectedField").hide();
        $('#status').change(function(){

            if($(this).val() == 2) {
                $("#rejectedField").show();
                $("#rejectedEditField").show();
                }else {
                $("#rejectedField").hide();
                }
            });

            $('#editStatus').change(function(){

                if($(this).val() == 2) {
                    $("#rejectedEditField").show();
                    }else {
                    $("#rejectedEditField").hide();
                    }
                });


      var table;
      var ids = [];

      function getGoal(amc = '',customer = '' ,account_number = '',folio_number='',rejected_reason = '',reference = '',status = '') {

          var url = "{{route('custAmcProfile.getData')}}";
          var queryParams = '?status='+status+'&customer='+customer+'&amc='+amc+'&account_number='+account_number+'&folio_number='+folio_number+'&rejected_reason='+rejected_reason+'&reference='+reference;
            console.log('queryParams',queryParams)
          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  processing: true,
                  serverSide: true,
                  ajax: {
                      url: url+queryParams,
                      type: "GET",
                  },
                  columns: [
                      {
                          name: 'amc.entity_name',
                          data: 'amc.entity_name',
                          orderable: false
                      },
                      {
                          name: 'user.full_name',
                          data: 'user.full_name',
                          orderable: false
                      },
                      {
                          name: 'user.cust_cnic_detail.cnic_number',
                          data: 'user.cust_cnic_detail.cnic_number',
                          orderable: false
                      },
                      {
                          name: 'rejected_reason',
                          data: 'rejected_reason',
                          render: function (data, type, row) {
                            if(row.rejected_reason != null) {
                                return `<div class="p-2">${row.rejected_reason}</div>`;
                              } else {
                                return `<div class="p-2">-------</div>`;
                              }
                          },
                          orderable: false
                      },
                    //   {
                    //       name: 'account_number',
                    //       data: 'account_number',
                    //       render: function (data, type, row) {
                    //         if(row.account_number != null) {
                    //             return `<div class="p-2">${row.account_number}</div>`;
                    //           } else {
                    //             return `<div class="p-2">-------</div>`;
                    //           }
                    //       },
                    //   },
                    //   {
                    //       name: 'reference',
                    //       data: 'reference',
                    //       render: function (data, type, row) {
                    //         if(row.reference != null) {
                    //             return `<div class="p-2">${row.reference}</div>`;
                    //           } else {
                    //             return `<div class="p-2">-------</div>`;
                    //           }
                    //       },
                    //   },
                      {
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == -1) {
                                  return `<div class="badge badge-info p-2">Not-Started</div>`;
                              }else if(row.status == 0){
                                  return `<div class="badge badge-secondary p-2">In-Process</div>`;
                              }else if(row.status == 1){
                                  return `<div class="badge badge-success p-2">Accepted</div>`;
                              }else if(row.status == 2){
                                  if(row.response_error_message!=null)
                                  return `<div class="badge badge-danger p-2" data-toggle="tooltip" data-placement="top" title="${row.response_error_message}">Rejected</div>`;
                                  else
                                  return `<div class="badge badge-danger p-2">Rejected</div>`;
                              }
                              else if(row.status == 3){
                                if(row.response_error_message!=null)
                                  return `<div class="badge badge-warning p-2" data-toggle="tooltip" data-placement="top" title="${row.response_error_message}">On Hold</div>`;
                                else
                                return `<div class="badge badge-warning p-2">On Hold</div>`;
                              }
                          },
                      },
                      {
                          name: 'verified',
                          data: 'verified',
                          render: function (data, type, row) {
                                 if(row.verified == 0) {
                                  return `<select class="form-select profile_verified" data-profile_id="${row.id}">
                                            <option value="0" selected>Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2">Data Sent in API</option>
                                          </select>`;
                                 }
                                 else if(row.verified == 1){
                                 return `<select class="form-select profile_verified" data-profile_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1" selected>Verified</option>
                                            <option value="2" >Data Sent in API</option>
                                          </select>`;
                                 }
                                else
                                {
                                return `<select class="form-select profile_verified" data-profile_id="${row.id}">
                                            <option value="0">Not Verified</option>
                                            <option value="1">Verified</option>
                                            <option value="2" selected>Data Sent in API</option>
                                          </select>`;
                                }
                          },
                      },
                      {
                          data: 'created_at',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                               
                                <a class="btn btn-sm btn-light text-center edit" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
                            
                                  </div>`;
                          },
                          searchable: false,
                          orderable: false
                      },
                  ],
                  select: true,
                  "order": [
                    [6, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          $('.datatable').on('change', '.profile_verified', function (e) {
              e.stopPropagation();
              var verified=$(this).val();
              var profile_id=$(this).data("profile_id");
              $.ajax({
                url: "{{ route('verify.custAmcProfile') }}",
                type: "POST",
                data:  {
                'verified':verified,
                'profile_id':profile_id
                },
                success: function (data) {
                    if(!data.error)
                    {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                    }
                },
                error: function (data) {
                }
              });
          });
          table.on('click', '.edit', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#custAmcId').val(data.id);
              $('#editCustAmcForm').find($('input[name="account_number"]')).val(data.account_number);
              $('#editCustAmcForm').find($('input[name="reference"]')).val(data.amc_reference_number);
              $('#editCustAmcForm').find($('textarea[name="rejected_reason"]')).val(data.rejected_reason);
              $('#editCustAmcForm').find($('select[name="account_type"]')).val(data.account_type);
              $('#editCustAmcForm').find($('select[name="status"]')).val(data.status);
              $('#editCustAmcModal').modal('show');
            //   $("#rejectedField").hide();
              if(data.status == 2){
              $("#rejectedEditField").show();
            }else{
              $("#rejectedEditField").hide();
            }
          });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#custAmcId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {
       
          let amcInput = element.find($('select[name="amc_id"]'));
          if(error.amc_id) {
            amcInput.closest('div').find('.select2-selection--single').addClass('is-invalid');
            amcInput.closest('div').find('.error').html(error.amc_id);
            amcInput.closest('div').find('.error').removeClass('hide').addClass('show');
          } else {
            amcInput.removeClass('is-invalid').addClass('is-valid');
            amcInput.next('.error').html('');
            amcInput.next('.error').removeClass('show').addClass('hide');
          }
          let userInput = element.find($('select[name="user_id"]'));
          if(error.user_id) {
            userInput.closest('div').find('.select2-selection--single').addClass('is-invalid');
            userInput.closest('div').find('.error').html(error.user_id);
            userInput.closest('div').find('.error').removeClass('hide').addClass('show');
          } else {
            userInput.removeClass('is-invalid').addClass('is-valid');
            userInput.next('.error').html('');
            userInput.next('.error').removeClass('show').addClass('hide');
          }
          let selectInput = element.find($('select[name="status"]'));
          if(error.status) {
              selectInput.addClass('is-invalid');
              selectInput.next('.error').html(error.status);
              selectInput.next('.error').removeClass('hide').addClass('show');
          }  else {
              selectInput.removeClass('is-invalid').addClass('is-valid');
              selectInput.next('.error').html('');
              selectInput.next('.error').removeClass('show').addClass('hide');
          }
          let selectaccount_type = element.find($('select[name="account_type"]'));
          if(error.status) {
            selectaccount_type.addClass('is-invalid');
            selectaccount_type.next('.error').html(error.account_type);
            selectaccount_type.next('.error').removeClass('hide').addClass('show');
          }  else {
            selectaccount_type.removeClass('is-invalid').addClass('is-valid');
            selectaccount_type.next('.error').html('');
            selectaccount_type.next('.error').removeClass('show').addClass('hide');
          }
          let rejectedReasonInput = element.find($('textarea[name="rejected_reason"]'));
          if(error.rejected_reason) {
            rejectedReasonInput.addClass('is-invalid');
            rejectedReasonInput.next('.error').html(error.rejected_reason);
            rejectedReasonInput.next('.error').removeClass('hide').addClass('show');
          }  else {
            rejectedReasonInput.removeClass('is-invalid').addClass('is-valid');
            rejectedReasonInput.next('.error').html('');
            rejectedReasonInput.next('.error').removeClass('show').addClass('hide');
          }
      }

      function resetValidationErrors(element)
      {
          element.find($('textarea')).each(function(index, el) {
              console.log('el', el)
              var el = $(el);
              el.removeClass('is-valid is-invalid');
              el.next('.error').html('');
              el.next('.error').removeClass('show').addClass('hide');
          });
          element.find($('select')).each(function(index, el) {
              var el = $(el);
              el.removeClass('is-valid is-invalid');
              el.closest('div').find('.error').html('');
              el.closest('div').find('.error').removeClass('show').addClass('hide');
          });
      }

      function clearDatatable() {
          table.clear();
          table.destroy();
      }

      $("#addCustAmcModal, #editCustAmcModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addCustAmcForm').trigger("reset");
          $('#editCustAmcForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.error1').hide();
          $('.customerSelect').empty().trigger('change');
          $('.amcSelect').empty().trigger('change');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addCustAmcForm'))
          resetValidationErrors($('#editCustAmcForm'))
      });

      $('#saveBtn').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addCustAmcForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('custAmcProfile.store') }}",
              type: "POST",
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
                      $('#addCustAmcModal').modal('hide');
                      $('#addCustAmcForm').trigger("reset");
                      table.draw();
                  }else if(data.error1){
                      
                      $('.error1').show();
                  }else {
                      console.log('data error', data.error);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addCustAmcForm'), data.error)
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

          var id = $('#custAmcId').val();
          var form = new FormData($('#editCustAmcForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('custAmcProfile.update', '')}}" + "/" + id;
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
                      $('#editCustAmcModal').modal('hide');
                      $('#editCustAmcForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editCustAmcForm'), data.error)
                  }
              },
              error: function (data) {
                Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });
                  // $('#saveBtn').html('Save Changes');
              }
          });
      });

      $('#deleteBtn').click(function (e) {
          e.preventDefault();
          var id = $('#goalId').val();
          var url = "{{route('goal.destroy', '')}}" + "/" + id;

          $.ajax({
              url: url,
              type: "DELETE",
              dataType: 'json',
              success: function (data) {
                  if (!data.error) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#deleteDepartmentModal').modal('hide');
                      table.draw();
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

      $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var customer = $('#filter-form').find($('select[name="filter-user-id"]')).val();
        var account_number = $('#filter-form').find($('input[name="filter-account-number"]')).val();
        var rejected_reason = $('#filter-form').find($('input[name="filter-rejected-reason"]')).val();
        var reference = $('#filter-form').find($('input[name="filter-reference"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        var folio_number = $('#filter-form').find($('input[name="filter-folio-number"]')).val();
        // console.log('status', status);
        status = status != null ? status : '';
        getGoal(amc,customer,account_number,folio_number,rejected_reason,reference,status);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#filter-amc-id").val('').trigger('change');
        $("#filter-user-id").val('').trigger('change');
        $('#filter-form').trigger("reset");
        clearDatatable();
        getGoal();
    });

    function customerSelect() {

        $('.customerSelect').select2({
            width: '100%',
            minimumInputLength: 0,
            dataType: 'json',
            placeholder: 'Select',
            ajax: {
                url: function () {
                    return "{{ route('custAmcProfile.customerdropdown') }}";
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        });

    }

    function amcSelect() {

        $('.amcSelect').select2({
            width: '100%',
            minimumInputLength: 0,
            dataType: 'json',
            placeholder: 'Select',
            ajax: {
                url: function () {
                    return "{{ route('custAmcProfile.amcList') }}";
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        });

    }

    function customerFilterSelect() {

            $('#filter-user-id').select2({
                width: '100%',
                minimumInputLength: 0,
                dataType: 'json',
                placeholder: 'Select',
                ajax: {
                    url: function () {
                        return "{{ route('custAmcProfile.customerdropdown') }}";
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });

        }

        function amcFilterSelect() {

        $('#filter-amc-id').select2({
            width: '100%',
            minimumInputLength: 0,
            dataType: 'json',
            placeholder: 'Select',
            ajax: {
                url: function () {
                    return "{{ route('custAmcProfile.amcList') }}";
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        });

        }

      getGoal();
      customerSelect();
      amcSelect();
      customerFilterSelect();
      amcFilterSelect();
    });
    $('[data-toggle="tooltip"]').tooltip();
  </script>
@endpush
