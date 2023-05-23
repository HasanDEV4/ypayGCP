@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>CSV Import Log</h4>
          <p>
               <a>Operations</a> /
              <span>Dividends/</span>
              <span>CSV Import Log</span>
          </p>
      </div>
    </div>
  </div>
  <div class="row">

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
        $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var amc = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var uploadstatus = $('#filter-form').find($('select[name="filter-upload-status"]')).val();
        getcsvimports(amc,uploadstatus);
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
        function getcsvimports(amc='',upload_status='')
        {
        var queryParams = '?&amc='+amc+'&upload_status='+upload_status;
        console.log('queryParams',queryParams)
          var url = "{{route('dividend.getimportlog')}}";
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
