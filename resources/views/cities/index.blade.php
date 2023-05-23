@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>YPay Cities</h4>
          <p>
              <!-- <a>Dashboard</a> / -->
              <a>Administration</a> /
              <span>YPay Cities</span>
          </p>
      </div>
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addCityModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>

        Add YPay City
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
                    <label for="city_name" class=" col-form-label">City Name</label>
                    <input type="text" class="form-control" id="city_name" placeholder="Enter City Name" name="city_name" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                        <label for="inputPassword3" class=" col-form-label">Status</label>
                              <select class="mb-2 form-control" id="status" name="status" autocomplete="off">
                                  <option value="" selected disabled>Select Status</option>
                                  <option value="1">Active</option>
                                  <option value="0">In-Active</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
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
                              <th>City Name</th>
                              <th>Country</th>
                              <th>Created At</th>
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
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addCityModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add City</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addCityForm" onsubmit="event.preventDefault()">
              <div class="form-group row">
                    <label for="city_name" class="col-sm-2 col-form-label">City Name</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="city_name" placeholder="Enter City Name" name="city_name" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="country" class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="country" name="country" value="Pakistan" autocomplete="off" disabled>
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
<div class="modal fade" id="editCityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit City</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCityForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="city_name" class="col-sm-2 col-form-label">City Name</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="city_name" placeholder="Enter City Name" name="city_name" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="country" class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="country" name="country" value="Pakistan" autocomplete="off" disabled>
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
                <input type="hidden" id="cityid" name="cityid">
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


      var table;
      var ids = [];

      function getcities(name='',status='') {

        var queryParams = '?&name='+name+'&status='+status;
      
      console.log('queryParams',queryParams)
          var url = "{{route('cities.getData')}}";

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
                          name: 'city',
                          data: 'city',
                      },  
                      {
                          name: 'country',
                          data: 'country',
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
                        data: 'verification',
                          render: function (data, type, row) {
                                 if(row.status == 0) {
                                  return `<select class="form-select city_status" data-city_id="${row.id}">
                                            <option value="0" selected>In-Active</option>
                                            <option value="1">Active</option>
                                          </select>`;
                                 }
                                else
                                {
                                return `<select class="form-select city_status" data-city_id="${row.id}">
                                            <option value="0">In-Active</option>
                                            <option value="1" selected>Active</option>
                                          </select>`;
                                }
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
                    [2, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });

          $('.datatable').on('change', '.city_status', function (e) {
              e.stopPropagation();
              var status=$(this).val();
              var city_id=$(this).data("city_id");
              $.ajax({
                url: "{{ route('city.status.change') }}",
                type: "POST",
                data:  {
                'status':status,
                'city_id':city_id
                },
                success: function (data) {
                    $('.datatable').DataTable().ajax.reload();
                },
                error: function (data) {
                }
              });
          });


          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            console.log("data", data);
            $('#cityid').val(data.id);
            $('#editCityForm').find($('input[name="city_name"]')).val(data.city);
            $('#editCityForm').find($('input[name="country"]')).val(data.country);
            $('#editCityForm').find($('select[name="status"]')).val(data.status);
            $('#editCityModal').modal('show');
          });
          }


      function clearDatatable() {
          table.clear();
          table.destroy();
      }


      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addCityForm')[0]);
      $.ajax({
        url: "{{ route('cities.store') }}",
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
            $('#addCityModal').modal('hide');
            $('#addCityForm').trigger("reset");
                      // getCount();
            table.draw();
          }else{
            console.log('error',data.error);
            handleValidationErrors($('#addCityForm'),data.error);
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

          var id = $('#cityid').val();
          var form = new FormData($('#editCityForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('cities.update')}}";
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
                      $('#editCityModal').modal('hide');
                      $('#editCityForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editCityForm'), data.error)
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
        var cityName = $('#filter-form').find($('input[name="city_name"]')).val();
        var status = $('#filter-form').find($('select[name="status"]')).val();
        getcities(cityName,status);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#city_name").val('').trigger('change');
        $("#status").val('').trigger('change');
        clearDatatable();
        getcities();
    });

    
      getcities();

    });
  </script>
@endpush
