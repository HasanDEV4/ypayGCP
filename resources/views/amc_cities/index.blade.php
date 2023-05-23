@extends('layouts.app')

@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>AMC Cities</h4>
          <p>
              <a>AMC Management</a> /
              <span>AMC Cities</span>
          </p>
      </div>
      <a class="btn btn-primary" id='getcitiesbtn'>
        <i class="fa fa-refresh mr-2"></i>
        Refresh AMC's Cities Data
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
                      <!-- <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">City</label>
                          <select class="form-control citySelectFilter" id="filter-city-id" placeholder="Name" name="filter-city-id" autocomplete="off"></select>
                      </div> -->
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
                              <th>AMC City ID</th>
                              <th>AMC City Name</th>
                              <th>AMC Country Name</th>
                              <th>YPay City ID</th>
                              <th>YPay City Name</th>
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
<input type="hidden" id="amccityid" name="id">
@endsection
@section('modal')
<div class="modal fade" id="editAmcCityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit AMC City</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAmcCityForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                      <div class="form-group row">
                          <label for="inputEmail4" class="form-label">City</label>
                          <select class="form-control" id="ypay_city_id" placeholder="Name" name="ypay_city_id" autocomplete="off">
                            <option value="" selected>Select City</option>
                            @foreach($cities as $city)
                            <option value={{$city->id}}>{{$city->city}}</option>
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
         $('#editBtn').click(function (e) {

            var id = $('#amccityid').val();
            var form = new FormData($('#editAmcCityForm')[0]);
            form.append('_method', 'PUT');
            var url = "{{route('amc_cities.update', '')}}" + "/" + id;
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
                        $('#editAmcCityModal').modal('hide');
                        $('#editAmcCityForm').trigger("reset");
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
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        getamccities(amc);
        });
        $('.btnResetFilter').click(function (e) {
            e.preventDefault();
            $("#filter-amc-id").val('').trigger('change');
            $("#filter-user-id").val('').trigger('change');
            $("#filter-fund-id").val('').trigger('change');
            $('#filter-form').trigger("reset");
            clearDatatable();
            getamccities();
        });
        $('#getcitiesbtn').click(function (e) {
            e.preventDefault();
            $.ajax({
              data: '',
              url: "{{ route('amc_cities.refresh') }}",
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
        function getamccities(amc='')
        {
        
        var queryParams = '?&amc='+amc;
        console.log('queryParams',queryParams)
          var url = "{{route('amc_cities.getData')}}";
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
                          name: 'amc_city_code',
                          data: 'amc_city_code',
                      },
                      {
                          name: 'amc_city_name',
                          data: 'amc_city_name',
                      },
                      {
                          name: 'amc_country_name',
                          data: 'amc_country_name',
                      },
                      {
                          name: 'city.id',
                          data: 'city.id',
                      }
                      ,
                      {
                          name: 'city.city',
                          data: 'city.city',
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
                    [6, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            console.log("data", data);
                  $('#amccityid').val(data.id);
                  if(data.ypay_city_id!=null)
                  $('#editAmcCityForm').find($('select[name="ypay_city_id"]')).val(data.ypay_city_id);
                  $('#editAmcCityModal').modal('show');
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
        getamccities();
        amcSelectFilter();
    });
  </script>
@endpush
