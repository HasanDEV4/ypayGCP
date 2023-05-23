@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Funds Data</h4>
          <p>
              <a>AMC Management</a> /
              <span>Funds Data</span>
          </p>
      </div>
      <a  class="btn btn-primary" id='getfundsdatabtn'>
        <i class="fa fa-refresh mr-2"></i>
        Refresh Funds Data
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
                            <label for="inputEmail4" class="form-label">Fund Name</label>
                            <input placeholder="Fund Name" type="text" name="filter-fund-name" class="form-control">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="mb-2 form-control" id="status" name="status" autocomplete="off">
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
                              <th>Fund Data Name</th>
                              <th>NAV</th>
                              <th>YTD</th>
                              <th>YPay Fund ID</th>
                              <th>YPay Fund Name</th>
                              <th>Last Updated</th>
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
                <form id="editFunddataForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="fund-id" class="form-label col-sm-2">Fund</label>
                            <div class="col-sm-10">
                                <!-- <select class="form-control fundSelectFilter" id="fund-id" name="ypay_fund_id"></select> -->
                                <select class="form-control" name="ypay_fund_id">
                                    <option value="">Select Fund</option>
                                    @foreach ($funds as $fund)
                                    <option value="{{$fund->id}}">{{$fund->fund_name}}</option>
                                    @endforeach
                                </select>
                                    <div class="invalid-feedback error hide">
                                    </div>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label for="status" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" id="status" name="status" autocomplete="off">
                                  <option selected disabled>Select Status</option>
                                  <option value="1">Active</option>
                                  <option value="0">In-Active</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                        <input type="hidden" id="funddataid" name="fund_data_id">
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
    $('#getfundsdatabtn').click(function (e) {
            e.preventDefault();
            $.ajax({
              data: '',
              url: "{{ route('funds_data.refresh') }}",
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
      var ids = [];

      function getFundData(fund_name='',status='') {
          var queryParams = '?&fund_name='+fund_name+'&status='+status;
          var url = "{{route('funds.getfundsData')}}";

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
                          name: 'fund_name',
                          data: 'fund_name',
                      },
                      {
                          name: 'nav',
                          data: 'nav',
                      },
                      {
                          name: 'ytd',
                          data: 'ytd',
                      },
                      {
                          name: 'ypay_fund.id',
                          data: 'ypay_fund.id',
                      },
                      {
                          name: 'ypay_fund.fund_name',
                          data: 'ypay_fund.fund_name',
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
                          name: 'status',
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                return `<div class="badge badge-danger p-2">In-Active</div>`
                              }else{
                                return `<div class="badge badge-success p-2">Active</div>`;
                              }
                          },
                          orderable: false
                      },
                      {
                          data: 'action',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('edit-fund')
                                <a class="btn btn-sm btn-light text-center edit" type="button"><i class="fas fa-edit text-info fa-lg"></i></a>
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

          table.on('click', '.edit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var data = table.row($(this).parents('tr')).data();
            console.log(data);
            if(data.ypay_fund!=null)
            $('#editFunddataForm').find($('select[name="ypay_fund_id"]')).val(data.ypay_fund.id);
            $('#editFunddataForm').find($('input[name="fund_data_id"]')).val(data.id);
            $('#editFunddataForm').find($('select[name="status"]')).val(data.status);
            $('#editFundModal').modal('show');
          });

      }


      function handleValidationErrors(element, error) {
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

      $("#editFundModal").on("hidden.bs.modal", function () {
          $('#addFundForm').trigger("reset");
          $('#editFunddataForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addFundForm'))
          resetValidationErrors($('#editFunddataForm'))
      });

      $('#editBtn').click(function (e) {
          var form = new FormData($('#editFunddataForm')[0]);
          var url = "{{route('fund_data.update')}}";
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
                      $('#editFunddataForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editFunddataForm'), data.error)
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
      $('.btnSubmitFilter').click(function (e) {
        e.preventDefault();
        clearDatatable();
        var fund_name = $('#filter-form').find($('input[name="filter-fund-name"]')).val();
        var status = $('#filter-form').find($('select[name="status"]')).val();
        getFundData(fund_name,status);
    });
    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getFundData();
    });
    function fundFilterDropDown() {

        $('#fund-id').select2({
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
      getFundData();
      fundFilterDropDown();
    });
  </script>
@endpush
