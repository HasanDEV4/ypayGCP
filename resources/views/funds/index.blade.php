@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Funds</h4>
          <p>
              <a>AMC Management</a> /
              <span>Funds</span>
          </p>
      </div>
      @can('add-fund')
      <a href="{{route('fund.add')}}"  class="btn btn-primary" >
          <i class="fas fa-plus mr-2"></i>
          Add Funds
      </a>
      @endcan
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
                          <label for="inputEmail4" class="form-label">Fund</label>
                          <select class="form-control fundSelectFilter" id="filter-fund-id" placeholder="Name" name="filter-fund-id"></select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Amc</label>
                            <select class="form-control amcSelectFilter" id="filter-amc-id" placeholder="Name" name="filter-amc-id" autocomplete="off"></select>
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">From</label>
                            <input placeholder="From" type="text" class="datepicker-from form-control " id="inputEmail4" name="filter-from-date">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">To</label>
                            <input placeholder="To" type="text" class="datepicker-to form-control " id="inputEmail4" name="filter-to-date">
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
                              <th>Fund</th>
                              <th>Amc</th>
                              <th>Investments</th>
                              <th>Logo</th>
                              <th>Funds Reference Number</th>
                              <th>Registered On</th>
                              {{-- <th>Popular</th> --}}
                              <th>Status</th>
                              <th>Last Updated Nav</th>
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
<input type="hidden" id="fundId" name="id">
@endsection

@section('modal')
<!-- Fund Add Modal -->
<div class="modal fade" id="addFundModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add Fund</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="addFundForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <div class="form-group row">
                          <label for="Fund" class="col-sm-2 col-form-label">Fund Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control fund_name" id="fund_name" placeholder="Fund Name" name="fund_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="AMC" class="col-sm-2 col-form-label">AMC</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control amcOptions" id="amcOptions" name="amc" autocomplete="off">
                                  <option selected disabled>Select AMC</option>
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
                          <label for="Popular" class="col-sm-2 col-form-label">Popular</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="popular" autocomplete="off">
                                  <option selected disabled>Select Popular</option>
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Status" class="col-sm-2 col-form-label">Status</label>
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
<div class="modal fade" id="editFundModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Fund</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFundForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <div class="form-group row">
                          <label for="Fund name" class="col-sm-2 col-form-label">Fund Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control fund_name" id="fund_name" placeholder="Fund Name" name="fund_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="AMC" class="col-sm-2 col-form-label">AMC</label>
                         <div class="col-sm-10">
                              <select class="mb-2 form-control amcOptions" id="amcOptions" name="amc" autocomplete="off">
                                  <option selected disabled>Select AMC</option>
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
                          <label for="Popular" class="col-sm-2 col-form-label">Popular</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="popular" autocomplete="off">
                                  <option selected disabled>Select Popular</option>
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Status" class="col-sm-2 col-form-label">Status</label>
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

      function getFund(status = '',fund = '',from = '',to = '', amc = '') {

          var queryParams = '?status='+status+'&fund='+fund+'&from='+from+'&to='+to+'&amc='+amc;

          var url = "{{route('fund.getData')}}";

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
                          name: 'fund_name',
                          data: 'fund_name',
                      },
                      {
                          name: 'amcs.entity_name',
                          data: 'amc.entity_name',
                      },
                      {
                          name: 'investments_count',
                          data: 'investments_count',
                      },
                      {
                          name: 'Logo',
                          render: function (data, type, row) {
                              if(row.fund_image!=null) {
                                  return `<div p-2"><a href="${row.fund_image.startsWith('http')?row.fund_image:"{{env('S3_BUCKET_URL')}}"+row.fund_image}" download>Download</a></div>`;
                              } else {
                                  return `<div p-2">------</div>`;
                              }
                          },
                          orderable: false
                          // data: 'logo',
                      },
                      {
                          name: 'amc_reference_number',
                          data: 'amc_reference_number',
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
                          name: 'funds_additional_details.status',
                          render: function (data, type, row) {
                              if(row && row.additional_details && row.additional_details.status == 1) {
                                  return `<div class="badge badge-success p-2">Active</div>`;
                              } else {
                                  return `<div class="badge badge-dark p-2">In-Active</div>`;
                              }
                          },
                      },
                      // {
                      //     data: 'popular',
                      //     render: function (data, type, row) {
                      //       console.log("row", row);
                      //         if(row.parsedStatus == 0) {
                      //             return `<div class="badge badge-success p-2">${row.parsedStatus}</div>`;
                      //         } else {
                      //             return `<div class="badge badge-dark p-2">${row.parsedStatus}</div>`;
                      //         }
                      //     },
                      // },
                      {
                          name: 'last_updated_nav',
                          data: 'last_updated_nav'
                      },
                      {
                          data: 'action',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('edit-fund')
                                <a class="btn btn-sm btn-light text-center edit" type="button" href="{{ url('/') }}/fund/${row.id}/edit"><i class="fas fa-edit text-info fa-lg"></i></a>
                                @endcan
                                  </div>`;
                          },
                          searchable: false,
                          orderable: false
                      },
                  ],
                  select: true,
                  "order": [
                    [5, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });

        //   table.on('click', '.edit', function () {
        //     var data = table.row($(this).parents('tr')).data();
        //     $('#FundId').val(data.id);
        //     $('#editFundForm').find($('input[name="fund_name"]')).val(data.fund_name);
        //     $('#editFundForm').find($('select[name="amc"]')).val(data.amc_id);
        //     $('#editFundForm').find($('select[name="popular"]')).val(data.is_popular);
        //     $('#editFundForm').find($('select[name="status"]')).val(data.status);
        //     $('#editFundModal').modal('show');

        //   });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#FundId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {
          let fund_name = element.find($('input[name="fund_name"]'));
          if(error.fund_name) {
              fund_name.addClass('is-invalid');
              fund_name.next('.error').html(error.fund_name);
              fund_name.next('.error').removeClass('hide').addClass('show');
          } else {
              fund_name.removeClass('is-invalid').addClass('is-valid');
              fund_name.next('.error').html('');
              fund_name.next('.error').removeClass('show').addClass('hide');
          }
          let amcInput = element.find($('select[name="amc"]'));
          if(error.amc) {
              amcInput.addClass('is-invalid');
              amcInput.next('.error').html(error.amc);
              amcInput.next('.error').removeClass('hide').addClass('show');
          }  else {
              amcInput.removeClass('is-invalid').addClass('is-valid');
              amcInput.next('.error').html('');
              amcInput.next('.error').removeClass('show').addClass('hide');
          }
          let logo = element.find($('input[name="logo"]'));
          if(error.logo) {
              logo.addClass('is-invalid');
              logo.next('.error').html(error.logo);
              logo.next('.error').removeClass('hide').addClass('show');
          } else {
              logo.removeClass('is-invalid').addClass('is-valid');
              logo.next('.error').html('');
              logo.next('.error').removeClass('show').addClass('hide');
          }
          let popularInput = element.find($('select[name="popular"]'));
          if(error.popular) {
              popularInput.addClass('is-invalid');
              popularInput.next('.error').html(error.popular);
              popularInput.next('.error').removeClass('hide').addClass('show');
          }  else {
              popularInput.removeClass('is-invalid').addClass('is-valid');
              popularInput.next('.error').html('');
              popularInput.next('.error').removeClass('show').addClass('hide');
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

      $("#addFundModal, #editFundModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addFundForm').trigger("reset");
          $('#editFundForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addFundForm'))
          resetValidationErrors($('#editFundForm'))
      });

      $('#saveBtn').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addFundForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('fund.store') }}",
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
                      
                      $('#addFundModal').modal('hide');
                      $('#addFundForm').trigger("reset");
                      table.draw();
                  } else {
                      console.log('data error', data);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addFundForm'), data.error)
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

          var id = $('#FundId').val();
          var form = new FormData($('#editFundForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('fund.update', '')}}" + "/" + id;
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
                      $('#editFundModal').modal('hide');
                      $('#editFundForm').trigger("reset");
                      table.draw();
                      window.location.href = "{{ route('fund.index') }}";
                  } else {
                      //validation errors
                      handleValidationErrors($('#editFundForm'), data.error)
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
        var fund = $('#filter-form').find($('select[name="filter-fund-id"]')).val();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        // console.log('status', status);
        status = status != null ? status : '';
        getFund(status,fund, from,to,amc );
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getFund();
    });
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
    function fundFilterDropDown() {

        $('#filter-fund-id').select2({
        width: '100%',
        minimumInputLength: 0,
        dataType: 'json',
        placeholder: 'Select',
        ajax: {
            url: function () {
                return "{{ route('funds.autocomplete') }}";
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            }
        }
        });

        }
      getFund();
      fundFilterDropDown();
      amcSelectFilter();
    });
  </script>
@endpush
