@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Risk Profile Ranks</h4>
          <p>
          <a>AMC Management</a> /
              <span>Risk Profile Ranks</span>
          </p>
      </div>
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addriskprofilerankModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>
        Add Risk Profile Rank
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
                          <label for="created_at" class="form-label">Creation Date</label>
                          <input type="date" class="form-control" id="created_at" placeholder="Creation Date" name="filter-created-at" autocomplete="off">
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
                              <th>Rank</th>
                              <th>Message</th>
                              <th>Start Range</th>
                              <th>End Range</th>
                              <th>Risk Profile Status</th>
                              <th>Created At</th>
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
<input type="hidden" id="riskprofilerankId" name="id">
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addriskprofilerankModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Risk Profile Rank</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addriskprofilerankForm" onsubmit="event.preventDefault()">
                <div class="form-group row">
                    <label for="rank" class="col-sm-2 col-form-label">Rank</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="rank" placeholder="Enter Rank" name="rank" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="rank" class="col-sm-2 col-form-label">Message</label>
                    <div class="col-sm-10">
                    <textarea class="form-control" id="message" placeholder="Enter Message" name="message" autocomplete="off"></textarea>
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="start_range" class="col-sm-2 col-form-label">Start Range</label>
                    <div class="col-sm-10">
                    <input type="number" class="form-control" id="start_range" placeholder="Enter Starting Range" name="start_range" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="end_range" class="col-sm-2 col-form-label">End Range</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="end_range" placeholder="Enter Ending Range" name="end_range" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                  <div class="form-group row">
                            <label for="risk_profile_status" class="col-sm-2 col-form-label">Risk Profile Status</label>
                            <div class="col-sm-10">
                                <select class="mb-2 form-control" name="risk_profile_status" autocomplete="off">
                                    <option selected disabled>Select Risk Profile Status</option>
                                    <option value="2">Approve</option>
                                    <option value="3">Reject</option>
                                </select>
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
<div class="modal fade" id="editriskprofilerankModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Risk Profile Rank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editriskprofilerankForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="rank" class="col-sm-2 col-form-label">Rank</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="rank" placeholder="Enter Rank" name="rank" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="option" class="col-sm-2 col-form-label">Message</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" id="message" name="message"></textarea>
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="start_range" class="col-sm-2 col-form-label">Start Range</label>
                    <div class="col-sm-10">
                    <input type="number" class="form-control" id="start_range" step="0.01" placeholder="Enter Starting Range" name="start_range" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="end_range" class="col-sm-2 col-form-label">End Range</label>
                    <div class="col-sm-10">
                    <input type="number" class="form-control" id="end_range" step="0.01" placeholder="Enter Ending Range" name="end_range" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                  <div class="form-group row">
                            <label for="risk_profile_status" class="col-sm-2 col-form-label">Risk Profile Status</label>
                            <div class="col-sm-10">
                                <select class="mb-2 form-control" name="risk_profile_status" autocomplete="off">
                                    <option selected disabled>Select Risk Profile Status</option>
                                    <option value="2">Approve</option>
                                    <option value="3">Reject</option>
                                </select>
                                <div class="invalid-feedback error hide">
                                </div>
                            </div>
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
      function getRiskProfilerank(created_at='') {

        var queryParams = '?&created_at='+created_at;
          var url = "{{route('risk_profile_ranks.getData')}}";

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
                          name: 'rank',
                          data: 'rank',
                      },
                      {
                          name: 'message',
                          data: 'message',
                      },
                      {
                          name: 'start_range',
                          data: 'start_range',
                      },
                      {
                          name: 'end_range',
                          data: 'end_range',
                      },
                      {
                          name: 'risk_profile_status',
                          data: 'risk_profile_status',
                          render: function (data, type, row) {
                              if(row.risk_profile_status == 2) {
                                return `<div class="badge badge-success p-2">Approve</div>`;
                              }
                              if(row.risk_profile_status == 3) {
                                return `<div class="badge badge-danger p-2">Reject</div>`
                              }
                              else{
                                return `<div></div>`;
                              }
                          },
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
                    [5, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          
          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            console.log("data", data);
            $('#riskprofilerankId').val(data.id);
            $('#editriskprofilerankForm').find($('input[name="rank"]')).val(data.rank);
            $('#editriskprofilerankForm').find($('textarea[name="message"]')).val(data.message);
            $('#editriskprofilerankForm').find($('input[name="start_range"]')).val(data.start_range);
            $('#editriskprofilerankForm').find($('input[name="end_range"]')).val(data.end_range);
            if(data.risk_profile_status)
            $('#editriskprofilerankForm').find($('select[name="risk_profile_status"]')).val(data.risk_profile_status);
            $('#editriskprofilerankModal').modal('show');
          });
        }
      function clearDatatable() {
          table.clear();
          table.destroy();
      }

      $("#addriskprofilerankModal, #editriskprofilerankModal").on("hidden.bs.modal", function () {
          $('#addriskprofilerankForm').trigger("reset");
          $('#editriskprofilerankForm').trigger("reset");
      });
      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addriskprofilerankForm')[0]);
      $.ajax({
        url: "{{ route('risk_profile_ranks.store') }}",
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
            $('#addriskprofilerankModal').modal('hide');
            $('#addriskprofilerankForm').trigger("reset");
            table.draw();
          }else{
            console.log('error',data.error);
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

          var id = $('#riskprofilerankId').val();
          var form = new FormData($('#editriskprofilerankForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('risk_profile_ranks.update', '')}}" + "/" + id;
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
                      $('#editriskprofilerankForm').trigger("reset");
                      $('#editriskprofilerankModal').modal('hide');
                      table.draw();
                  } else {
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
        var creation_date = $('#filter-form').find($('input[name="filter-created-at"]')).val();
        getRiskProfilerank(creation_date);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#created_at").val('').trigger('change');
        clearDatatable();
        getRiskProfilerank();
    });
    getRiskProfilerank();
    });
  </script>
@endpush
