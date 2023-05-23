@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Existing Customers</h4>
          <p>
              <a>Dashboard</a> /
              <a>Customers</a> /
              <span>Existing Customers</span>
          </p>
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
                          <input placeholder="Customer Name" type="text" name="filter-customer-name" class="form-control " id="inputEmail4">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Customer Email</label>
                            <input placeholder="Customer Email" type="text" name="filter-customer-email" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Customer Contact</label>
                            <input placeholder="Customer Contact" type="text" name="filter-customer-contact" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Customer Cnic</label>
                            <input placeholder="Customer Cnic" type="text" name="filter-customer-cnic" class="form-control " id="inputEmail4">
                            </div>
                        
                          <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">Status</label>
                            <select class="mb-2 form-control" name="filter-status" autocomplete="off">
                              <option selected disabled>Select Status</option>
                              <option value="1">Active</option>
                              <option value="0">In-Active</option>
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
                              <th>Customer</th>
                              <th>Email</th>
                              <th>CNIC No</th>
                              <th>Phone No</th>
                              <th>Refer Code</th>
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
  </div>
</div>
<input type="hidden" id="user_Id" name="id">
<input type="hidden" id="custId" name="id">
@endsection

@section('modal')
<!-- Amc Add Modal -->
<div class="modal fade" id="addAmcModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add AMC</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="addAmcForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <div class="form-group row">
                          <label for="company name" class="col-sm-2 col-form-label">Company Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control company_name" id="company_name" placeholder="Company name" name="company_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="category" class="col-sm-2 col-form-label">Category</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="category" autocomplete="off">
                                  <option selected disabled>Select Category</option>
                                  <option value="Public Limited">Public Limited</option>
                                  <option value="Private Limited">Private Limited</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Logo" class="col-sm-2 col-form-label">Logo</label>
                          <div class="col-sm-10">
                            <input type='file' class="form-control logo h-100" id="logo" name="logo" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact no" class="col-sm-2 col-form-label">Contact No</label>
                          <div class="col-sm-10">
                            <input type='number' class="form-control contact_no" id="contact_no" name="contact_no" placeholder="Contact No" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person Name" class="col-sm-2 col-form-label">Contact Person Name</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_name" id="contact_person_name" name="contact_person_name" placeholder="Contact Person Name" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person Role" class="col-sm-2 col-form-label">Contact Person Role</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_role" id="contact_person_role" name="contact_person_role" placeholder="Contact Person Role" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="SECP Number" class="col-sm-2 col-form-label">SECP Number</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control secp_number" id="secp_number" name="secp_number" placeholder="SECP Number" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="status" autocomplete="off">
                                  <option selected disabled>Select Status</option>
                                  <option value="1">Active</option>
                                  <option value="0">In-Active</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
              </div>
              </form>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
              </div>
          </div>
      </div>
</div>
<!-- Department Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="editAmcModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manage Status Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAmcForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <!-- <div class="form-group row">
                          <label for="company name" class="col-sm-2 col-form-label">Company Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control company_name" id="company_name" placeholder="Company name" name="company_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="category" class="col-sm-2 col-form-label">Category</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="category" autocomplete="off">
                                  <option selected disabled>Select Category</option>
                                  <option value="Public Limited">Public Limited</option>
                                  <option value="Private Limited">Private Limited</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Logo" class="col-sm-2 col-form-label">Logo</label>
                          <div class="col-sm-10">
                            <input type='file' class="form-control logo h-100" id="logo" name="logo" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact no" class="col-sm-2 col-form-label">Contact No</label>
                          <div class="col-sm-10">
                            <input type='number' class="form-control contact_no" id="contact_no" name="contact_no" placeholder="Contact No" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person Name" class="col-sm-2 col-form-label">Contact Person Name</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_name" id="contact_person_name" name="contact_person_name" placeholder="Contact Person Name" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person Role" class="col-sm-2 col-form-label">Contact Person Role</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_role" id="contact_person_role" name="contact_person_role" placeholder="Contact Person Role" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="SECP Number" class="col-sm-2 col-form-label">SECP Number</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control secp_number" id="secp_number" name="secp_number" placeholder="SECP Number" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div> -->
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="status" autocomplete="off">
                                  <option selected disabled>Select Status</option>
                                  <option value="1">Active</option>
                                  <option value="0">In-Active</option>
                              </select>
                              <div class="invalid-feedback error hide">
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
<!-- Department Edit Modal -->
@endsection

@push('scripts')
  <script>

    $(function () {
        $.fn.dataTable.ext.errMode = 'none';
        $('.datatable').click(function (e) {
        e.stopPropagation();
        });
      var table;
      var ids = [];

      function getAmc(customerName = '', status = '',email = '', contact = '',cnic = '') {

        var queryParams = '?status='+status+'&customerName='+customerName+'&email='+email+'&contact='+contact+'&cnic='+cnic;
          var url = "{{route('customer.getData')}}";

          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  processing: true,
                  serverSide: true,
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
                          name: 'Cusotmer',
                          data: 'full_name',
                      },
                      {
                          name: 'Email',
                          data: 'email',
                      },
                      {
                          name: 'cust_cnic_detail.cnic_number',
                          data: 'cust_cnic_detail.cnic_number',
                      },
                      {
                          name: 'Phone No',
                          data: 'phone_no',
                      },
                      {
                          name: 'refer_code',
                        render: function (data, type, row) {
                              if(row.refer_code != null) {
                                return `<div class="p-2">${row.refer_code}</div>`;
                              } else {
                                return `<div class="p-2">-------</div>`;
                              }
                          },
                            orderable: false
                      },
                     
                      {
                          name: 'status',
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                return `<div class="badge badge-danger p-2">In-Active</div>`;
                              } else {
                                return `<div class="badge badge-success p-2">Active</div>`;
                              }
                          },
                      },
                      {
                          data: 'updated_at',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('view-customer-details')
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('cust.details','/')}}/${row.id}" target="_blank"><i class="fas fa-ellipsis-v text-info fa-lg"></i></a>
                                @endcan
                                  </div>
                                  <div class="btn-group dropdown" role="group">
                              @can('edit-customer-status')
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
                    [6, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });

          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            $('#custId').val(data.id);
            $('#user_Id').val(data.id);
            $('#editAmcForm').find($('input[name="company_name"]')).val(data.company_name);
            $('#editAmcForm').find($('select[name="category"]')).val(data.category);
            $('#editAmcForm').find($('input[name="contact_no"]')).val(data.contact_no);
            $('#editAmcForm').find($('input[name="contact_person_name"]')).val(data.contact_person_name);
            $('#editAmcForm').find($('input[name="contact_person_role"]')).val(data.contact_person_role);
            $('#editAmcForm').find($('input[name="secp_number"]')).val(data.secp_number);
            $('#editAmcForm').find($('select[name="status"]')).val(data.status);
            $('#editAmcModal').modal('show');
          });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#custId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {


          let companyNameInput = element.find($('input[name="company_name"]'));
          if(error.company_name) {
              companyNameInput.addClass('is-invalid');
              companyNameInput.next('.error').html(error.company_name);
              companyNameInput.next('.error').removeClass('hide').addClass('show');
          } else {
              companyNameInput.removeClass('is-invalid').addClass('is-valid');
              companyNameInput.next('.error').html('');
              companyNameInput.next('.error').removeClass('show').addClass('hide');
          }
          let categoryInput = element.find($('select[name="category"]'));
          if(error.category) {
              categoryInput.addClass('is-invalid');
              categoryInput.next('.error').html(error.category);
              categoryInput.next('.error').removeClass('hide').addClass('show');
          } else {
              categoryInput.removeClass('is-invalid').addClass('is-valid');
              categoryInput.next('.error').html('');
              categoryInput.next('.error').removeClass('show').addClass('hide');
          }
          let companyLogoInput = element.find($('input[name="logo"]'));
          if(error.logo) {
              companyLogoInput.addClass('is-invalid');
              companyLogoInput.next('.error').html(error.logo);
              companyLogoInput.next('.error').removeClass('hide').addClass('show');
          } else {
              companyLogoInput.removeClass('is-invalid').addClass('is-valid');
              companyLogoInput.next('.error').html('');
              companyLogoInput.next('.error').removeClass('show').addClass('hide');
          }
          let contactNoInput = element.find($('input[name="contact_no"]'));
          if(error.contact_no) {
              contactNoInput.addClass('is-invalid');
              contactNoInput.next('.error').html(error.contact_no);
              contactNoInput.next('.error').removeClass('hide').addClass('show');
          } else {
              contactNoInput.removeClass('is-invalid').addClass('is-valid');
              contactNoInput.next('.error').html('');
              contactNoInput.next('.error').removeClass('show').addClass('hide');
          }
          let contactPersonNameInput = element.find($('input[name="contact_person_name"]'));
          if(error.contact_person_name) {
              contactPersonNameInput.addClass('is-invalid');
              contactPersonNameInput.next('.error').html(error.contact_person_name);
              contactPersonNameInput.next('.error').removeClass('hide').addClass('show');
          } else {
              contactPersonNameInput.removeClass('is-invalid').addClass('is-valid');
              contactPersonNameInput.next('.error').html('');
              contactPersonNameInput.next('.error').removeClass('show').addClass('hide');
          }
          let contactPersonRoleInput = element.find($('input[name="contact_person_role"]'));
          if(error.contact_person_role) {
              contactPersonRoleInput.addClass('is-invalid');
              contactPersonRoleInput.next('.error').html(error.contact_person_role);
              contactPersonRoleInput.next('.error').removeClass('hide').addClass('show');
          } else {
              contactPersonRoleInput.removeClass('is-invalid').addClass('is-valid');
              contactPersonRoleInput.next('.error').html('');
              contactPersonRoleInput.next('.error').removeClass('show').addClass('hide');
          }
          let secpNumberInput = element.find($('input[name="secp_number"]'));
          if(error.secp_number) {
              secpNumberInput.addClass('is-invalid');
              secpNumberInput.next('.error').html(error.secp_number);
              secpNumberInput.next('.error').removeClass('hide').addClass('show');
          } else {
              secpNumberInput.removeClass('is-invalid').addClass('is-valid');
              secpNumberInput.next('.error').html('');
              secpNumberInput.next('.error').removeClass('show').addClass('hide');
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

      $("#addAmcModal, #editAmcModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addAmcForm').trigger("reset");
          $('#editAmcForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addAmcForm'))
          resetValidationErrors($('#editAmcForm'))
      });

      $('#saveBtn').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addAmcForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('amc.store') }}",
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
                      $('#addAmcModal').modal('hide');
                      $('#addAmcForm').trigger("reset");
                      table.draw();
                  } else {
                      console.log('data error', data);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addAmcForm'), data.error)
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

        //   var id = $('#custId').val();
          var id=$('#user_Id').val();
          var form = new FormData($('#editAmcForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('newsignup.update', '')}}" + "/" + id;
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
                      $('#editAmcModal').modal('hide');
                      $('#editAmcForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editAmcForm'), data.error)
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
          var id = $('#custId').val();
          var url = "{{route('amc.destroy', '')}}" + "/" + id;

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
        var customerName = $('#filter-form').find($('input[name="filter-customer-name"]')).val();
        var email = $('#filter-form').find($('input[name="filter-customer-email"]')).val();
        var contact = $('#filter-form').find($('input[name="filter-customer-contact"]')).val();
        var cnic = $('#filter-form').find($('input[name="filter-customer-cnic"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        console.log('contact', contact);
        status = status != null ? status : '';
        getAmc(customerName, status,email,contact,cnic);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getAmc();
    });


      getAmc();

    });
  </script>
@endpush
