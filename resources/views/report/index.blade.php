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
          <h4>Unit Statement</h4>
          <p>
              <a>Report Management</a> /

              <span>Unit Statement</span>
          </p>
      </div>
    </div>
  </div>
  <div class="row">
  <div class="col-12 py-4">
        <div class="accordion" id="accordionExample">
            <div class="card">
              <div>
                <div class="card-body">
                    <form id="get-user-data-form">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="customer_cnic" class="form-label">Customer CNIC</label>
                            <select class="form-control customerSelectFilter" id="filter-user-id" placeholder="Name" name="filter-user-id" autocomplete="off">
                            <option selected disabled value="">Select Customer</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{ $user->full_name.'-'.$user->cust_cnic_detail?->cnic_number }}</option>
                            @endforeach
                        </select>
                            <p style="color:red;" id="error" hidden>This Field is Required</p>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="from" class="form-label">From</label>
                            <input type="date" class="form-control" name="from">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="to" class="form-label">To</label>
                            <input type="date" class="form-control" name="to">
                        </div>
                        <!-- <div class="col-12 col-md-4">
                             <label for="transaction_type" class="form-label">Transaction Type</label>
                             <select class="mb-2 form-control" name="transaction_type" id="transaction_type">
                                  <option value="investments" selected>Investments</option>
                                  <option value="redemptions">Redemptions</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                        </div> -->
                    </div>
                        <div class="col-12 text-right mt-3">
                        <button class="btn btn-primary btn-sm btn_generate_report">Generate Report</button> 
                        <!-- <button class="btn btn-primary btn-sm btn_get_user_data">Get Data</button> -->
                        </div>
                      </form>
                </div>
              </div>
            </div>
        </div>
      </div>
</div>

  <div class="col-12 py-4">

      <div class="col-lg-12 datatable_div" hidden>
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable w-100">
                      <thead id="thead">
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
         $('.btn_generate_report').on('click', function(e) {
            e.preventDefault();
            var cnic = $('#get-user-data-form').find($('select[name="customer_cnic"]')).val();
            var transaction_type = $('#get-user-data-form').find($('select[name="transaction_type"]')).val();
            var from = $('#get-user-data-form').find($('input[name="from"]')).val();
            var to = $('#get-user-data-form').find($('input[name="to"]')).val();
            var queryParams = '?cnic='+cnic+'&from='+from+'&to='+to;
            // alert(cnic);
            // alert(transaction_type);
            // alert(from);
            // alert(to);
            if(cnic!=null)
            window.open("/generate-pdf"+queryParams);
            else
            $('#error').removeAttr('hidden');
            // $.ajax({
            //     url: "{{ route('generate.pdf') }}",
            //     type: "GET",
            //     // dataType: 'json',
            //     contentType: false,
            //     cache: false,
            //     processData:false,
            //     enctype: 'multipart/form-data',
            //         success: function (data) {
            //         console.log("data", data);
            //         if (!data.error) {
            //             window.open("/generate-pdf"+queryParams, "_blank");
            //         }else{
            //         }
            //         },
            //         error: function (data) {
            //         Toast.fire({
            //                         icon: 'error',
            //                         title: 'Oops Something Went Wrong!',
            //                     });      

            //         }
            // });
        });
        $('.btn_get_user_data').on('click', function(e) {
            e.preventDefault();
            var cnic = $('#get-user-data-form').find($('select[name="customer_cnic"]')).val();
            // var transaction_type = $('#get-user-data-form').find($('select[name="transaction_type"]')).val();
            var from = $('#get-user-data-form').find($('input[name="from"]')).val();
            var to = $('#get-user-data-form').find($('input[name="to"]')).val();
            if(cnic!='')
            {
                $('#error').attr('hidden',true);
                if(transaction_type=="investments")
                {
                    $('#thead').html(`<tr>
                                    <th>CNIC</th>
                                    <th>Fund Name</th>
                                    <th>Investment Id</th>
                                    <th>Investment Units</th>
                                    <th>Investment Nav</th>
                                    <th>Investment Amount</th>
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
                }
                else
                {
                    $('#thead').html(`<tr>
                                    <th>CNIC</th>
                                    <th>Fund Name</th>
                                    <th>Redemption Id</th>
                                    <th>Redemption Units</th>
                                    <th>Redemption Nav</th>
                                    <th>Redemption Amount</th>
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
                }
                $('.datatable').DataTable().destroy();
                getuserdata(cnic,transaction_type,table_data,from,to);
                export_url = '?cnic='+cnic+'&from='+from+'&to='+to;
                $('.datatable_div').removeAttr('hidden');
                $('.filter_form').removeAttr('hidden');
            }
            else
            {
                $('#error').removeAttr('hidden');
            }
        });

        var table;
        function getuserdata(cnic='',table_data='',from='',to='')
        {
        
        var queryParams = '?cnic='+cnic+'&from='+from+'&to='+to;
          var url = "{{route('reports.getData')}}";
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
                    [6, "desc"]
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
    function customerFilterDropDown() {

        $('#filter-user-id').select2({
            width: '100%',
            // minimumInputLength: 0,
            // dataType: 'json',
            // placeholder: 'Select',
            // ajax: {
            //     url: function () {
            //         return "{{ route('customers.autocomplete') }}";
            //     },
            //     processResults: function (data, page) {
            //         return {
            //             results: data
            //         };
            //     }
            // }
        });

    }

    customerFilterDropDown();
  </script>
@endpush
