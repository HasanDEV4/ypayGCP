@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>FAQ's</h4>
          <p>
              <!-- <a>Dashboard</a> / -->
              <a>Administration</a> /
              <span>FAQ's</span>
          </p>
      </div>
      @can('add-faq')
          
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addFaqModal" data-backdrop="true">
          <i class="fas fa-plus mr-2"></i>
          Add FAQ's
        </a>
        @endcan
    </div>

      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th>Question</th>
                              <th>Text</th>
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
<input type="hidden" id="faqId" name="id">
@endsection

@section('modal')
<!-- FAQ Add Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add FAQ</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="addFaqForm" onsubmit="event.preventDefault()">
                    <div class="form-group row">
                          <label for="question" class="col-sm-2 col-form-label">Question</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control question" id="question" placeholder="Question" name="question" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Text</label>
                          <div class="col-sm-10">
                            <textarea rows="5" class="form-control text" id="text" name="text">
                              
                            </textarea>
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
<div class="modal fade" id="editFaqModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit FAQ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="editFaqForm" onsubmit="event.preventDefault()">
                    <div class="form-group row">
                        <label for="question" class="col-sm-2 col-form-label">Question</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control question" id="question" placeholder="Question" name="question" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Text</label>
                          <div class="col-sm-10">
                            <textarea rows="5" class="form-control text" id="text" name="text">
                              
                            </textarea>
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

      function getFaq() {

          var url = "{{route('faqs.getData')}}";

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
                          data: 'question',
                          name: 'question',
                      },
                      {
                          name: 'text',
                          data: 'text',
                      },
                      {
                          data: 'status',
                          render: function (data, type, row) {
                              if(data) {
                                  return `<div class="badge badge-success p-2">${row.parsedStatus}</div>`;
                              } else {
                                  return `<div class="badge badge-dark p-2">${row.parsedStatus}</div>`;
                              }
                          },
                      },
                      {
                          data: 'action',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('edit-faq')  
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
                    [0, "asc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });

          table.on('click', '.edit', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#faqId').val(data.id);
              $('#editFaqForm').find($('input[name="question"]')).val(data.question);
              $('#editFaqForm').find($('.text')).val(data.text);
              $('#editFaqForm').find($('select[name="status"]')).val(data.status);
              $('#editFaqModal').modal('show');
          });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#faqId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {


          let questionInput = element.find($('input[name="question"]'));
          if(error.question) {
              questionInput.addClass('is-invalid');
              questionInput.next('.error').html(error.question);
              questionInput.next('.error').removeClass('hide').addClass('show');
          } else {
              questionInput.removeClass('is-invalid').addClass('is-valid');
              questionInput.next('.error').html('');
              questionInput.next('.error').removeClass('show').addClass('hide');
          }
          let textInput = element.find($('.text'));
          if(error.text) {
              textInput.addClass('is-invalid');
              textInput.next('.error').html(error.text);
              textInput.next('.error').removeClass('hide').addClass('show');
          } else {
              textInput.removeClass('is-invalid').addClass('is-valid');
              textInput.next('.error').html('');
              textInput.next('.error').removeClass('show').addClass('hide');
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

      $("#addFaqModal, #editFaqModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addFaqForm').trigger("reset");
          $('#editFaqForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addFaqForm'))
          resetValidationErrors($('#editFaqForm'))
      });

      $('#saveBtn').click(function (e) {
          e.preventDefault();
          $.ajax({
              data: $('#addFaqForm').serialize(),
              url: "{{ route('faqs.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                  if (!data.error) {
                      Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#addFaqModal').modal('hide');
                      $('#addFaqForm').trigger("reset");
                      table.draw();
                  } else {
                      console.log('data error', data);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addFaqForm'), data.error)
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

          var id = $('#faqId').val();
          var url = "{{route('faqs.update', '')}}" + "/" + id;
          e.preventDefault();
          $.ajax({
              data: $('#editFaqForm').serialize(),
              url: url,
              type: "PUT",
              dataType: 'json',
              success: function (data) {
                  if (!data.error) {
                      Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#editFaqModal').modal('hide');
                      $('#editFaqForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editFaqForm'), data.error)
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

      $('#deleteBtn').click(function (e) {
          e.preventDefault();
          var id = $('#faqId').val();
          var url = "{{route('faqs.destroy', '')}}" + "/" + id;

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

      getFaq();

    });
  </script>
@endpush
