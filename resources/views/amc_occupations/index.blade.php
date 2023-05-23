@extends('layouts.app')

@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>AMC Occupations</h4>
          <p>
              <a>AMC Management</a> /
              <span>AMC Occupations</span>
          </p>
      </div>
      <a  class="btn btn-primary" id="getoccupationsbtn">
        <i class="fa fa-refresh mr-2" ></i>
        Refresh AMC's Occupations Data
      </a>
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
                          <label for="inputEmail4" class="form-label">AMC</label>
                          <select class="form-control amcSelectFilter" id="filter-amc-id" placeholder="Name" name="filter-amc-id" autocomplete="off"></select>
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
                              <th>AMC Occupation ID</th>
                              <th>AMC Occupation Name</th>
                              <th>YPay Occupation ID</th>
                              <th>YPay Occupation Name</th>
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

<input type="hidden" id="amcoccupationid" name="id">    
@endsection
@section('modal')
<div class="modal fade" id="editAmcOccupationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit AMC Occupation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAmcOccupationForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                @csrf  
                <div class="form-group row">
                          <label for="inputEmail4" class="form-label">Occupation</label>
                          <select class="form-control" id="ypay_occupation_id" placeholder="Name" name="ypay_occupation_id" autocomplete="off">
                            <option value="" selected>Select Occupation</option>
                            @foreach($occupations as $occupation)
                            <option value={{$occupation->id}}>{{$occupation->name}}</option>
                            @endforeach
                          </select>
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
@endsection

@push('scripts')
  <script>

    $(function () {
        $.fn.dataTable.ext.errMode = 'none';
        $('.datatable').click(function (e) {
        e.stopPropagation();
         });
        $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        getamcoccupations(amc);
        });
        $('#editBtn').click(function (e) {

          var id = $('#amcoccupationid').val();
          var form = new FormData($('#editAmcOccupationForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('amc_occupations.update', '')}}" + "/" + id;
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
                      $('#editAmcOccupationModal').modal('hide');
                      $('#editAmcOccupationForm').trigger("reset");
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
        $('.btnResetFilter').click(function (e) {
            e.preventDefault();
            $("#filter-amc-id").val('').trigger('change');
            $("#filter-user-id").val('').trigger('change');
            $("#filter-fund-id").val('').trigger('change');
            $('#filter-form').trigger("reset");
            clearDatatable();
            getamcoccupations();
        });
        $('#getoccupationsbtn').click(function (e) {
            e.preventDefault();
            $.ajax({
              data: '',
              url: "{{ route('amc_occupations.refresh') }}",
              type: "GET",
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
                      $('.datatable').DataTable().ajax.reload();
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
        var table;
        function getamcoccupations(amc='')
        {
        
        var queryParams = '?&amc='+amc;
        console.log('queryParams',queryParams)
          var url = "{{route('amc_occupations.getData')}}";
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
                      },
                      {
                          name: 'amc_occupation_id',
                          data: 'amc_occupation_id',
                      },
                      {
                          name: 'amc_occupation_name',
                          data: 'amc_occupation_name',
                      },
                      {
                          name: 'occupation.id',
                          data: 'occupation.id',
                      }
                      ,
                      {
                          name: 'occupation.name',
                          data: 'occupation.name',
                      }
                      ,
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
                      }
                      ,
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
                  $('#amcoccupationid').val(data.id);
                  if(data.ypay_occupation_id!=null)
                  $('#editAmcOccupationForm').find($('select[name="ypay_occupation_id"]')).val(data.ypay_occupation_id);
                  $('#editAmcOccupationModal').modal('show');
          });
        }

        function clearDatatable() {
          table.clear();
          table.destroy();
      }
        function amcSelectFilter() {

            $('.amcSelectFilter').select2({
            width: '100%',
            minimumInputLength: 0,
            dataType: 'json',
            placeholder: 'Select',
            ajax: {
                url: function () {
                    return "{{ route('amc.autocomplete') }}";
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
            });

        }
        getamcoccupations();
        amcSelectFilter();
    });
  </script>
@endpush
