@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>AMC Funds</h4>
          <p>
              <a>AMC Management</a> /
              <span>AMC Funds</span>
          </p>
      </div>
      <a class="btn btn-primary" id='getfundsbtn'>
        <i class="fa fa-refresh mr-2"></i>
        Refresh AMC's Funds Data
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
                              <th>AMC Fund ID</th>
                              <th>AMC Fund Code</th>
                              <th>AMC Fund Name</th>
                              <th>Ypay Fund Id</th>
                              <th>Ypay Fund Name</th>
                              <th>AMC Fund Unit Type</th>
                              <th>AMC Fund Class Type</th>
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
        var csvtype = $('#filter-form').find($('select[name="filter-csv-type"]')).val();
        var uploadstatus = $('#filter-form').find($('select[name="filter-upload-status"]')).val();
        getamcfunds(amc,csvtype,uploadstatus);
        });
        $('.btnResetFilter').click(function (e) {
            e.preventDefault();
            $("#filter-amc-id").val('').trigger('change');
            $("#filter-user-id").val('').trigger('change');
            $("#filter-fund-id").val('').trigger('change');
            $('#filter-form').trigger("reset");
            clearDatatable();
            getamcfunds();
        });
        $('#getfundsbtn').click(function (e) {
            e.preventDefault();
            $.ajax({
              data: '',
              url: "{{ route('amc_funds.refresh') }}",
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
        function getamcfunds(amc='')
        {
        
        var queryParams = '?&amc='+amc;
        console.log('queryParams',queryParams)
          var url = "{{route('amc_funds.getData')}}";
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
                          name: 'amc_fund_id',
                          data: 'amc_fund_id',
                      },
                      {
                          name: 'amc_fund_code',
                          data: 'amc_fund_code',
                      },
                      {
                          name: 'amc_fund_name',
                          data: 'amc_fund_name',
                      }
                      ,
                      {
                          name: 'fund.id',
                          data: 'fund.id',
                      }
                      ,
                      {
                          name: 'fund.fund_name',
                          data: 'fund.fund_name',
                      },
                      {
                          name: 'amc_fund_unit_type',
                          data: 'amc_fund_unit_type',
                      },
                      {
                          name: 'amc_fund_class_type',
                          data: 'amc_fund_class_type',
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
                      },
                  ],
                  select: true,
                  "order": [
                    [8, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
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
        getamcfunds();
        amcSelectFilter();
    });
  </script>
@endpush
