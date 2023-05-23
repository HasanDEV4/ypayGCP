@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Permissions</h4>
          <p>
              <a>User</a> /

              <span>Permissions</span>
          </p>
      </div>
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addPermissionModal" data-backdrop="true">
          <i class="fas fa-plus mr-2"></i>
          Add Permission
      </a>
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
                          <label for="inputEmail4" class="form-label">Category</label>
                          <input placeholder="Category" type="text" name="filter-category" class="form-control " id="inputEmail4">
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
      </div> --}}

      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th>Permission Name</th>
                              <th>Guard</th>
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
<input type="hidden" id="permissionId" name="id">
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add Permission</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="addPermissionForm" onsubmit="event.preventDefault()">
                    <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control name" id="name" placeholder="Name" name="name" autocomplete="off">
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
<div class="modal fade" id="editPermissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPermissionForm" onsubmit="event.preventDefault()">
                    <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control name" id="name" placeholder="Name" name="name" autocomplete="off">
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
      var table;
      var ids = [];

      function getGoal(category = '', status = '') {

          var url = "{{route('permission.getData')}}";
          var queryParams = '?status='+status+'&category='+category;

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
                          name: 'Name',
                          data: 'name',
                      },
                      {
                          name: 'guard_name',
                          data: 'guard_name',
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
                    [2, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });

          table.on('click', '.edit', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#permissionId').val(data.id);
              $('#editPermissionForm').find($('input[name="name"]')).val(data.name);
              $('#editPermissionModal').modal('show');
          });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#permissionId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {
       
          let nameInput = element.find($('input[name="name"]'));
          if(error.name) {
              nameInput.addClass('is-invalid');
              nameInput.next('.error').html(error.name);
              nameInput.next('.error').removeClass('hide').addClass('show');
          } else {
              nameInput.removeClass('is-invalid').addClass('is-valid');
              nameInput.next('.error').html('');
              nameInput.next('.error').removeClass('show').addClass('hide');
          }
          let guardNameInput = element.find($('input[name="guard_name"]'));
          if(error.guard_name) {
            guardNameInput.addClass('is-invalid');
            guardNameInput.next('.error').html(error.guard_name);
            guardNameInput.next('.error').removeClass('hide').addClass('show');
          } else {
            guardNameInput.removeClass('is-invalid').addClass('is-valid');
            guardNameInput.next('.error').html('');
            guardNameInput.next('.error').removeClass('show').addClass('hide');
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

      $("#addPermissionModal, #editPermissionModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addPermissionForm').trigger("reset");
          $('#editPermissionForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addPermissionForm'))
          resetValidationErrors($('#editPermissionForm'))
      });

      $('#saveBtn').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addPermissionForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('permission.store') }}",
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
                      $('#addPermissionModal').modal('hide');
                      $('#addPermissionForm').trigger("reset");
                      table.draw();
                  } else {
                      console.log('data error', data.error);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addPermissionForm'), data.error)
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

          var id = $('#permissionId').val();
          var form = new FormData($('#editPermissionForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('permission.update', '')}}" + "/" + id;
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
                      $('#editPermissionModal').modal('hide');
                      $('#editPermissionForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editPermissionForm'), data.error)
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
        var category = $('#filter-form').find($('input[name="filter-category"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        // console.log('status', status);
        status = status != null ? status : '';
        getGoal(category,status);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getGoal();
    });

      getGoal();

    });
  </script>
@endpush
