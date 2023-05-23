@extends('layouts.app')



@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Roles</h4>
          <p>
              <a>User Management</a> /
              <span>Roles</span>
          </p>
      </div>
      @can('add-role')
          
      <a href="{{ route('role.create') }}"  class="btn btn-primary" >
        <i class="fas fa-plus mr-2"></i>
        Add Roles
    </a>
    @endcan
    </div>

    {{-- <div class="col-12 py-4">
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
                            <label for="inputEmail4" class="form-label">From</label>
                            <input placeholder="From" type="text" class="datepicker-from form-control " id="inputEmail4" name="filter-from-date">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">To</label>
                            <input placeholder="To" type="text" class="datepicker-to form-control " id="inputEmail4" name="filter-to-date">
                          </div>
                     </div>
                      <div class="row g-3">
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
      </div> --}}

      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable" id="datatable">
                    {{-- <button class="btn btn-primary" onclick="ExportToExcel('xlsx')">Export All</button>
    
                    <button class="btn btn-primary" id="ExportSelectedButton">Export Selected</button> --}}
                      <thead>
                          <tr>
                              <th>Role</th>
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
<input type="hidden" id="reqId" name="id">
@endsection

@section('modal')
<!-- Department Edit Modal -->
{{-- <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manage User Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
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
</div> --}}
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

      function getAmc(customerName = '', status = '', from = '', to = '', email = '', contact = '') {
        var queryParams = '?status='+status+'&customerName='+customerName+'&from='+from+'&to='+to+'&email='+email+'&contact='+contact;

          var url = "{{route('role.getData')}}";

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
                  select: true,
                  ajax: {
                      url: url+queryParams,
                      type: "GET",
                  },
                 
                  columns: [
                    
                      {
                          name: 'name',
                          data: 'name',
                      },
                   
                     
                      
                      {
                          data: 'id',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                            
                                @can('edit-role')
                                <a class="btn btn-sm btn-light text-center edit" type="button" href="{{ url('/') }}/role/edit/${row.id}"><i class="fas fa-edit text-info fa-lg"></i></a>
                                @endcan
                                  </div>`;
                          },
                          searchable: false,
                          orderable: false
                      },
                  ],
                 
                  
                  select: true,
                  "order": [
                    [1, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
                  
          });

          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            $('#reqId').val(data.id);
            $('#editUserForm').find($('select[name="status"]')).val(data.status);
            $('#editUserModal').modal('show');
          });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#reqId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });

          table.on('click', '.checkboxAll', function () {
              var data = table.row($(this).parents('tr')).data();
              console.log('data',data);
            //   ExportSelectedToExcel(data)
            //   $('#reqId').val(data.id);
            //   $('#deleteDepartmentModal').modal('show');
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

              }
          });
      });

      $('#editBtn').click(function (e) {

          var id = $('#reqId').val();
          var form = new FormData($('#editUserForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('user.update', '')}}" + "/" + id;
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
                      $('#editUserModal').modal('hide');
                      $('#editUserForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editUserForm'), data.error)
                  }
              },
              error: function (data) {
                  // $('#saveBtn').html('Save Changes');
              }
          });
      });

      $('#deleteBtn').click(function (e) {
          e.preventDefault();
          var id = $('#reqId').val();
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
              }
          });
      });

      $(function(){
          
        $('#ExportSelectedButton').click(function(e){
            e.preventDefault();
        var val = [];
        $(':checkbox:checked').each(function(i){
          val[i] = $(this).val();
        });

        $.ajax({
                data: {
                    id: val
                },
              url: "{{ route('user.exportUser') }}",
              type: "GET",
              dataType: 'json',
              success: function (data) {
                if (!data.error) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      table.draw();
                  }
                var a = document.createElement("a");
                a.href = data.response.file; 
                a.download = data.response.name;
                document.body.appendChild(a);
                a.click();
                a.remove();
                  
              },
              error: function (data) {
              }
          });

      });

    });

      $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var customerName = $('#filter-form').find($('input[name="filter-customer-name"]')).val();
        var email = $('#filter-form').find($('input[name="filter-customer-email"]')).val();
        var contact = $('#filter-form').find($('input[name="filter-customer-contact"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        // console.log('status', status);
        status = status != null ? status : '';
        getAmc(customerName, status, from, to,email,contact);
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
