@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Transaction History</h4>
          <p>
              <a>Customer</a> /

              <span>Transaction History</span>
          </p>
      </div>
    </div>
  </div>
  <div class="col-12 py-3">
    <div class="col-12 col-md-4">
        <label for="transaction_type" class="form-label">Transaction Type</label>
        <select class="mb-2 form-control" name="transaction_type" id="transaction_type">
            <option value="investments" selected>Investments</option>
            <option value="redemptions">Redemptions</option>
        </select>
        <div class="invalid-feedback error hide">
        </div>
    </div>
  </div>
  <div class="col-12 py-2">
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
                    <div class="col-12 col-md-3" id="amc_filter_div">
                          <label for="inputEmail4" class="form-label">AMC</label>
                          <select class="form-control amcSelectFilter" id="filter-amc-id" placeholder="Name" name="filter-amc-id" autocomplete="off"></select>
                      </div>
                       
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Fund Name</label>
                            <select class="form-control fundSelectFilter" id="filter-fund-id" placeholder="Name" name="filter-fund-id"></select>
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Transaction Date From</label>
                            <input placeholder="From" type="text" class="datepicker-from form-control " id="inputEmail4" name="filter-from-date">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Transaction Date To</label>
                            <input placeholder="To" type="text" class="datepicker-to form-control " id="inputEmail4" name="filter-to-date">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Approval Date From</label>
                            <input  type="date" class="form-control " id="inputEmail4" name="filter-approved-date-from">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Approval Date To</label>
                            <input type="date" class="form-control " id="inputEmail4" name="filter-approved-date-to">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="filter-status" class="form-label">Transaction Status</label>
                            <select class="form-control" id="filter-status" name="filter-status">
                                  <option selected disabled>Select Transaction Status</option>
                                  <option value="0">Pending</option>
                                  <option value="1">Approved</option>
                                  <option value="2">Rejected</option>
                                  <option value="3">On Hold</option>
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
  <div class="col-12 py-2">

      <div class="col-lg-12 datatable_div">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable w-100">
                      <thead id="thead">
                        <tr>
                            <th>CNIC</th>
                            <th>Fund Name</th>
                            <th>Investment Id</th>
                            <th>Investment Units</th>
                            <th>Investment Nav</th>
                            <th>Investment Amount</th>
                            <th>Investment Status</th>
                            <th>Investment Date</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>

    
@endsection

@push('scripts')
  <script>
    $(":input").inputmask();
    $(function () {
        $.fn.dataTable.ext.errMode = 'none';
        $('.datatable').click(function (e) {
        e.stopPropagation();
         });
        var export_url='';
        var table_data=[
            {
                name: 'user.cust_cnic_detail.cnic_number',
                data: 'user.cust_cnic_detail.cnic_number',
            },
            {
                name: 'fund.fund_name',
                data: 'fund.fund_name',
            },
            {
                name: 'transaction_id',
                data: 'transaction_id',
            },
            {
                name: 'unit',
                data: 'unit',
            }
            ,
            {
                name: 'nav',
                data: 'nav',
            }
            ,{
                name: 'amount',
                data: 'amount',
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    if(row.status == 0) {
                        return `<div class="badge badge-dark p-2">Pending</div>`;
                    } else if ( row.status == 1 ) {
                        return `<div class="badge badge-success p-2">Approved</div>`;
                    }
                    else if ( row.status == 3 ) {
                        return `<div class="badge badge-primary p-2">On Hold</div>`;
                    }
                    else{
                    return `<div class="badge badge-danger p-2">Rejected</div>`;
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
        ];
        var transaction_type = "investments"
        getuserdata(table_data, transaction_type);
         $('#transaction_type').on('change', function(e) {
            e.preventDefault();
            transaction_type = $(this).val();
            $('#error').attr('hidden',true);
            if(transaction_type=="investments") {
                $('#thead').html(`<tr>
                                <th>CNIC</th>
                                <th>Fund Name</th>
                                <th>Investment Id</th>
                                <th>Investment Units</th>
                                <th>Investment Nav</th>
                                <th>Investment Amount</th>
                                <th>Investment Status</th>
                                <th>Investment Date</th>
                            </tr>`);
                table_data=[
                        {
                            name: 'user.cust_cnic_detail.cnic_number',
                            data: 'user.cust_cnic_detail.cnic_number',
                        },
                        {
                            name: 'fund.fund_name',
                            data: 'fund.fund_name',
                        },
                        {
                            name: 'transaction_id',
                            data: 'transaction_id',
                        },
                        {
                            name: 'unit',
                            data: 'unit',
                        }
                        ,
                        {
                            name: 'nav',
                            data: 'nav',
                        }
                        ,{
                            name: 'amount',
                            data: 'amount',
                        },
                        {
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                  return `<div class="badge badge-dark p-2">Pending</div>`;
                              } else if ( row.status == 1 ) {
                                  return `<div class="badge badge-success p-2">Approved</div>`;
                              }
                              else if ( row.status == 3 ) {
                                  return `<div class="badge badge-primary p-2">On Hold</div>`;
                              }
                              else{
                                return `<div class="badge badge-danger p-2">Rejected</div>`;
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
                    ];
                    $('#amc_filter_div').show();
            } else {
                transaction_type = "redemptions"
                $('#thead').html(`<tr>
                                <th>CNIC</th>
                                <th>Fund Name</th>
                                <th>Redemption Id</th>
                                <th>Redemption Units</th>
                                <th>Redemption Nav</th>
                                <th>Redemption Amount</th>
                                <th>Redemption Status</th>
                                <th>Redemption Date</th>
                            </tr>`);
                table_data=[
                        {
                            name: 'investment.user.cust_cnic_detail.cnic_number',
                            data: 'investment.user.cust_cnic_detail.cnic_number',
                        },
                        {
                            name: 'investment.fund.fund_name',
                            data: 'investment.fund.fund_name',
                        },
                        {
                            name: 'transaction_id',
                            data: 'transaction_id',
                        },
                        {
                            name: 'investment.unit',
                            data: 'investment.unit',
                        }
                        ,
                        {
                            name: 'investment.nav',
                            data: 'investment.nav',
                        }
                        ,{
                            name: 'redeem_amount',
                            data: 'redeem_amount',
                        },
                        {
                          name: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                  return `<div class="badge badge-dark p-2">Pending</div>`;
                              } else if ( row.status == 1 ) {
                                  return `<div class="badge badge-success p-2">Approved</div>`;
                              }
                              else if ( row.status == 3 ) {
                                  return `<div class="badge badge-primary p-2">On Hold</div>`;
                              }
                              else{
                                  return `<div class="badge badge-danger p-2">Rejected</div>`;
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
                    ];
                    $('#amc_filter_div').hide();
            }
            $('.datatable').DataTable().destroy();
            getuserdata(table_data, transaction_type);
        });

        var table;
            $('.btnSubmitFilter').on('click', function(e) {
            e.preventDefault();
            clearDatatable();
            var status = $('#filter-form').find($('select[name="filter-status"]')).val();
            var fund = $('#filter-form').find($('select[name="filter-fund-id"]')).val();
            var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
            var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
            var approvedDateFrom = $('#filter-form').find($('input[name="filter-approved-date-from"]')).val();
            var approvedDateTo = $('#filter-form').find($('input[name="filter-approved-date-to"]')).val();
            var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
            getuserdata(table_data,transaction_type,status,fund,approvedDateFrom,approvedDateTo,from,to,amc);
        });

        $('.btnResetFilter').click(function (e) {
            e.preventDefault();
            $("#filter-amc-id").val('').trigger('change');
            $("#filter-fund-id").val('').trigger('change');
            $('#filter-form').trigger("reset");
            clearDatatable();
            getuserdata(table_data,transaction_type);
        });
        function getuserdata(table_data,transaction_type,status="",fund="",approvedDateFrom="",approvedDateTo="",from="",to="",amc="") {
        var queryParams = '?transaction_type='+transaction_type+'&status='+status+'&fund='+fund+'&approvedDateFrom='+approvedDateFrom+'&approvedDateTo='+approvedDateTo+'&from='+from+'&to='+to+'&amc='+amc;
          var url = "{{route('cust.get_transaction_history', $id)}}";
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
                  columns: table_data,
                  select: true,
                  "order": [
                    [7, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
        }
        
        function clearDatatable() {
          table.clear();
          table.destroy();
      }
    });
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

amcSelectFilter();
fundFilterDropDown();
  </script>
@endpush
