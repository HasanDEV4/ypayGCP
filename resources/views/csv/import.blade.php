@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>CSV Import</h4>
          <p>
              <a>Operations</a> /

              <span>CSV Import</span>
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
                    <form id="csv_import_form">
                    <div class="row g-3">
                    <div class="col-12 col-md-3">
                            <label class="form-label">Csv Type</label>
                            <select class="mb-2 form-control" id="csv_type" name="csv_type" autocomplete="off">
                              <option selected disabled>Select Csv Type</option>
                                  <option value="kyc" required>KYC</option>
                                  <option value="investment">Investment</option>
                                  <option value="redemption">Redemption</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label  class="form-label">AMC</label>
                            <select class="form-control amcSelectFilter" name="amc_id" autocomplete="off" required></select>
                        </div>
                        <div class="col-12 col-md-3">
                        <label class="form-label">CSV File</label>
                        <input type="file" class="form-control h-auto"  name="csv_file" required>
                        </div>
                    </div>
                        <div class="col-12 text-right mt-3">
                            <button type="submit" class="btn btn-primary btn-sm btn_import_csv">Import</button>
                        </div>
                      </form>
                </div>
              </div>
            </div>
        </div>
      </div>
  </div>
  <div class="col-12 py-4" id="template_div" hidden>
  <div class="card">
  <div class="card-header bg-secondary">
    <p>CSV Template</p>
  </div>
  <div class="card-body">
  <button type="button" id="template_download_button" class="btn btn-primary btn-sm"></button>
  </div>
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
                            <label class="form-label">Csv Type</label>
                            <select class="mb-2 form-control" name="filter-csv-type" autocomplete="off">
                              <option value="" selected disabled>Select Csv Type</option>
                                  <option value="kyc" required>KYC</option>
                                  <option value="investment">Investment</option>
                                  <option value="redemption">Redemption</option>
                            </select>
                        </div>
                      <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">AMC</label>
                          <select class="form-control amcSelectFilter" id="filter-amc-id" placeholder="Name" name="filter-amc-id" autocomplete="off"></select>
                      </div>
                       
                      <div class="col-12 col-md-3">
                           <label for="inputEmail4" class="form-label">Upload Status</label>
                           <select class="form-control uploadstatusSelectFilter" id="filter-fund-id"  name="filter-upload-status">
                           <option selected disabled value="">Select Upload Status</option>  
                              <option value="0">Failed</option>
                              <option value="1">Successful</option>
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
                              <th>AMC</th>
                              <th>CSV Type</th>
                              <th>Upload Status</th>
                              <th>Failed Reason</th>
                              <th>Created At</th>
                              <th>Updated At</th>
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
         function JSON2CSV(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';
    var line = '';

    if ($("#labels").is(':checked')) {
        var head = array[0];
        if ($("#quote").is(':checked')) {
            for (var index in array[0]) {
                var value = index + "";
                line += '"' + value.replace(/"/g, '""') + '",';
            }
        } else {
            for (var index in array[0]) {
                line += index + ',';
            }
        }

        line = line.slice(0, -1);
        str += line + '\r\n';
    }

    for (var i = 0; i < array.length; i++) {
        var line = '';

        if ($("#quote").is(':checked')) {
            for (var index in array[i]) {
                var value = array[i][index] + "";
                line += '"' + value.replace(/"/g, '""') + '",';
            }
        } else {
            for (var index in array[i]) {
                line += array[i][index] + ',';
            }
        }

        line = line.slice(0, -1);
        str += line + '\r\n';
    }
    return str;
}
        var json_pre='',json='',csv='',blob='',url='',downloadLink='',csv_type='';
        
        $('#csv_type').change(function(){
            csv_type=$('#csv_type').val();
            $('#template_div').attr('hidden',false);
            if(csv_type=="kyc")
            {
                csv_type=csv_type.toUpperCase();
                json_pre = '[{"1":"Profile Status","2":"Reject Reason","3":"Account Number","4":"CNIC","5":"Description"},{"Profile Status":0,"Reject Reason":"Invalid CNIC Number","Account Number":"34385473743","CNIC":"42201-3698521-4","Description":"AMC Profile Statuses"},{"Profile Status":"","Reject Reason":"","Account Number":"","CNIC":"","Description":"not-started=-1 in-process=0 accepted=1 rejected=2 on-hold=3"},{"Profile Status":"","Reject Reason":"","Account Number":"","CNIC":"","Description":""},{"Profile Status":"","Reject Reason":"","Account Number":"","CNIC":"","Description":"CNIC Format"},{"Profile Status":"","Reject Reason":"","Account Number":"","CNIC":"","Description":"84845-4548484-8"}]';
            }
            else if(csv_type=="investment")
            {
            csv_type=csv_type.charAt(0).toUpperCase() + csv_type.slice(1);
            json_pre = '[{"1":"Transaction Id","2":"NAV Rate","3":"Allotted Units","4":"Approval Date","5":"Investment Status","6":"Reject Reason","7":"AMC Reference Number","8":"CNIC","9":"Description"},{"1":"I5ufkQGx","2":"50.555","3":"40","4":"4/24/2022","5":"1","6":"","7":"","8":"84845-4548484-8","9":"Investment Statuses"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"0=pending 1=approved 2=rejected"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":""},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"CNIC Format"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"84845-4548484-8"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":""},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"Transaction Id"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"This is the Investment Transaction Id"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":""},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"AMC Reference Number"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"It is the field stored in AMC System that is used for mapping"}]';
            }
            else
            {
            csv_type=csv_type.charAt(0).toUpperCase() + csv_type.slice(1);
            json_pre = '[{"1":"Transaction Id","2":"Approved Amount","3":"Approval Date","4":"Redemption Status","5":"Reject Reason","6":"AMC Reference Number","7":"CNIC","8":"Investment Transaction Id","9":"Description"},{"1":"D43je3d","2":"4000","3":"4/24/2022","4":"2","5":"Invalid Transaction Id","6":"","7":"84845-4548484-8","8":"OAdQhCBV","9":"Redemption Statuses"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"0=pending 1=approved 2=rejected"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":""},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"CNIC Format"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"12200-4040484-8"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":""},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"Transaction Id"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"This is the Redemption Transaction Id"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":""},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"AMC Reference Number"},{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"It is the field stored in AMC System that is used for mapping"}]';
            }
            $('#template_download_button').text("Download "+csv_type+" Template");
            });

        $('#template_download_button').on('click', function(e) {
            json = $.parseJSON(json_pre);
            csv = JSON2CSV(json);
            downloadLink = document.createElement("a");
            blob = new Blob(["\ufeff", csv]);
            url = URL.createObjectURL(blob);
            downloadLink.href = url;
            downloadLink.download = csv_type+"_template.csv";
            downloadLink.click();    
        });
        $('#csv_import_form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('import.csvfile') }}",
                type: "POST",
                data:  new FormData(this),
                // dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                enctype: 'multipart/form-data',
                success: function (data) {
                console.log("data", data);
                if (!data.error) {
                    Toast.fire({
                    icon: 'success',
                    title: data.message
                    });
                    resetValidationErrors($('#csv_import_form'))
                    $('.datatable').DataTable().ajax.reload();
                }else{
                    handleValidationErrors($('#csv_import_form'), data.error)
                    $('.datatable').DataTable().ajax.reload();
                }
                },
                error: function (data) {
                Toast.fire({
                                icon: 'error',
                                title: data.message,
                            });  
                $('.datatable').DataTable().ajax.reload();    

                }
            });
        });
        $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var csvtype = $('#filter-form').find($('select[name="filter-csv-type"]')).val();
        var uploadstatus = $('#filter-form').find($('select[name="filter-upload-status"]')).val();
        getcsvimports(amc,csvtype,uploadstatus);
        });
        $('.btnResetFilter').click(function (e) {
            e.preventDefault();
            $("#filter-amc-id").val('').trigger('change');
            $("#filter-user-id").val('').trigger('change');
            $("#filter-fund-id").val('').trigger('change');
            $('#filter-form').trigger("reset");
            clearDatatable();
            getcsvimports();
        });
        var table;
        function getcsvimports(amc='',csv_type='',upload_status='')
        {
        
        var queryParams = '?&amc='+amc+'&csv_type='+csv_type+'&upload_status='+upload_status;
        console.log('queryParams',queryParams)
          var url = "{{route('csvimport.getData')}}";
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
                          name: 'csv_type',
                          data: 'csv_type',
                      },
                      {
                          name: 'upload_status',
                          data: 'upload_status',
                          render: function (data, type, row) {
                              if(row.upload_status==0 || row.upload_status==1) {
                                if(row.upload_status==1)
                                return `<div class="badge badge-success p-2">Successfull</div>`;
                                else
                                return `<div class="badge badge-danger p-2">Failed</div>`;
                              } else {
                                  return `<div class="p-2">------</div>`;
                              }
                          },
                      },
                      {
                          name: 'failure_reason',
                          data: 'failure_reason',
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
                      ,{
                          name: 'updated_at',
                          data: 'updated_at',
                      },
                  ],
                  select: true,
                  "order": [
                    [4, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
        }
        function handleValidationErrors(element, error) {

                
        let csv_type = element.find($('select[name="csv_type"]'));
        if(error.csv_type) {
            csv_type.closest('div').find('.select2-selection--single').addClass('is-invalid');
            csv_type.closest('div').find('.error').html(error.user_id);
            csv_type.closest('div').find('.error').removeClass('hide').addClass('show');
        }  else {
            csv_type.removeClass('is-invalid').addClass('is-valid');
            csv_type.next('.error').html('');
            csv_type.next('.error').removeClass('show').addClass('hide');
        }

        
        let csv_file = element.find($('input[name="csv_file"]'));
        if(error.csv_file) {
            csv_file.addClass('is-invalid');
            csv_file.next('.error').html(error.csv_file);
            csv_file.next('.error').removeClass('hide').addClass('show');
        }  else {
            csv_file.removeClass('is-invalid').addClass('is-valid');
            csv_file.next('.error').html('');
            csv_file.next('.error').removeClass('show').addClass('hide');
        }


        }
        function clearDatatable() {
          table.clear();
          table.destroy();
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
        getcsvimports();
        amcSelectFilter();
    });
  </script>
@endpush
