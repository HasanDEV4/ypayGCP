@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Policies</h4>
          <p>
              <!-- <a>Dashboard</a> / -->
              <a>Administration</a> /
              <span>Policies</span>
          </p>
      </div>
      {{-- <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addDepartmentModal" data-backdrop="true">
          <i class="fas fa-plus mr-2"></i>
          Add Department
      </a> --}}
    </div>

      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th>Name</th>
                              <th>Last Updated</th>
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
<input type="hidden" id="policyId" name="id">
@endsection

@section('modal')
<!-- Department Edit Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="editDepartmentForm" onsubmit="event.preventDefault()">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control name" id="name" placeholder="Name" name="name" autocomplete="off" readonly="">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Text</label>
                        <div class="col-sm-10">
                          <textarea rows="5" class="form-control description" id="description" placeholder="Description" name="description" autocomplete="off">
                            
                          </textarea>
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

      function getDepartments(department = '', status = '', from = '', to = '') {

          var url = "{{route('policies.getData')}}";

          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  processing: true,
                  serverSide: true,
                  ajax: {
                      url: url,
                      type: "GET",
                  },
                  columns: [
                      {
                          data: 'name',
                          name: 'name',
                      },
                      {
                          name: 'updated_at',
                          data: 'updated_at'
                      },

                      {
                          data: 'action',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('edit-policies') 
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
                  [1, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });

          table.on('click', '.edit', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#policyId').val(data.id);
              $('#editDepartmentForm').find($('input[name="name"]')).val(data.name);
              $('#editDepartmentForm').find($('.description')).val(data.description);
              $('#editDepartmentModal').modal('show');
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

          let selectInput = element.find($('.description'));
          if(error.description) {
              selectInput.addClass('is-invalid');
              selectInput.next('.error').html(error.description);
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

      $("#addDepartmentModal, #editDepartmentModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addDepartmentForm').trigger("reset");
          $('#editDepartmentForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addDepartmentForm'))
          resetValidationErrors($('#editDepartmentForm'))
      });


      $('#editBtn').click(function (e) {

          var id = $('#policyId').val();
          var url = "{{route('policies.update', '')}}" + "/" + id;
          e.preventDefault();
          $.ajax({
              data: $('#editDepartmentForm').serialize(),
              url: url,
              type: "PUT",
              dataType: 'json',
              success: function (data) {
                  if (!data.error) {
                      Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#editDepartmentModal').modal('hide');
                      $('#editDepartmentForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editDepartmentForm'), data.error)
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

    // $('#filter-form').on('submit', function() {
    //   console.log('aaaa');
    //     e.preventDefault();
    //     clearDatatable();
    //     var departmentName = $(this).find($('input[name="department-name"]')).val();
    //     var from = $(this).find($('input[name="filter-from"]')).val();
    //     var to = $(this).find($('input[name="filter-to"]')).val();
    //     var status = $(this).find($('select[name="filter-status"]')).val();
    //     status = status != null ? status : '';
    //     getDepartments(departmentName, status, from, to);
    // });
    $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var departmentName = $('#filter-form').find($('input[name="filter-department-name"]')).val();
        console.log('departmentName', departmentName);
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        status = status != null ? status : '';
        getDepartments(departmentName, status, from, to);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getDepartments();
    });
      // getCount();
      getDepartments();

    });
  </script>
@endpush
