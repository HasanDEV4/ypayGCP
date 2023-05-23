@extends('layouts.app')

<style>
    .msg-column {
        word-wrap: break-word;
        word-break: break-all;
        width: 50%;

    }
</style>


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Notifications</h4>
          <p>
              <a>Marketing</a> /
              <span>Notifications</span>
          </p>
      </div>
      <!-- <a href="{{ route('notification.create') }}"  class="btn btn-primary">
          <i class="fas fa-plus mr-2"></i>
          Send Notification
      </a> -->
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
                          <label for="inputEmail4" class="form-label">Title</label>
                          <input placeholder="Title" type="text" name="filter-title" class="form-control " id="inputEmail4">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">From</label>
                          <input placeholder="From" type="text" class="datepicker-from form-control " id="inputEmail4" name="filter-from-date">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">To</label>
                          <input placeholder="To" type="text" class="datepicker-to form-control " id="inputEmail4" name="filter-to-date">
                        </div>
                        {{-- <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">Status</label>
                            <select class="mb-2 form-control" name="filter-status" autocomplete="off">
                              <option selected disabled>Select Status</option>
                              <option value="1">Active</option>
                              <option value="0">In-Active</option>
                          </select>
                        </div> --}}

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
                              <th>Title</th>
                              <th>Message</th>
                              <th>Image</th>
                              <th>Created At</th>
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

      function getNotification(title = '', from = '', to = '') {

        var queryParams = '?title='+title+'&from='+from+'&to='+to;
          var url = "{{route('notification.list')}}";

          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  columnDefs: [{
                    targets: 1,
                    className: 'msg-column'
                    }],

                  processing: true,
                  serverSide: true,
                  ajax: {
                      url: url+queryParams,
                      type: "GET",
                  },
                  columns: [
                      {
                          data: 'title',
                          name: 'title',
                      },
                      {
                          name: 'message',
                          data: 'message',
                          "columnDefs": [
                            { "width": "50%" }
                        ],
                      },
                      {
                          data: 'id',
                          render: function (data, type, row) {
                            if(row.image) {
                                var logo = row.image;
                                  return `<div p-2"><a href="${logo.startsWith('http')?logo:"{{env('S3_BUCKET_URL')}}"+logo}" download>Download</a></div>`;
                              } else {
                                  return `<div class="p-2">------</div>`;
                              }
                          },
                          searchable: false,
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
                  ],
                  
                  select: true,
                  "order": [
                  [3, "desc"]
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

    $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var title = $('#filter-form').find($('input[name="filter-title"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        getNotification(title, from, to);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getNotification();
    });
      
      getNotification();

    });
  </script>
@endpush
