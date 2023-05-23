@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>OTP Vendors</h4>
          <p>
              <a>Administration</a> /
              <span>OTP Vendors</span>
          </p>
      </div>
    </div>
    <!-- <div class="col-12 py-4">
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
                        <label for="inputEmail4" class="form-label">Customer Name</label>
                        <select class="form-control customerSelectFilter" id="filter-user-id" placeholder="Name" name="filter-user-id"></select>
                      </div>
                      <div class="col-12 col-md-3">
                        <label for="inputEmail4" class="form-label">Fund Name</label>
                        <select class="form-control fundSelectFilter" id="filter-fund-id" placeholder="Name" name="filter-fund-id"></select>
                      </div>
                      <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Folio Number</label>
                            <input placeholder="Folio Number" type="text" name="filter-folio-number" class="form-control " id="filter-folio-number">
                          </div>
                      <div class="col-12 col-md-3">
                        <label for="inputEmail4" class="form-label">Transaction Date From</label>
                        <input placeholder="From" class="form-control" type="datetime-local" id="inputEmail4" name="filter-from-date">
                      </div>
                      <div class="col-12 col-md-3">
                        <label for="inputEmail4" class="form-label">Transaction Date To</label>
                        <input placeholder="To" type="datetime-local" class="form-control" id="inputEmail4" name="filter-to-date">
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
      </div> -->

      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable w-100">
                      <thead>
                          <tr>
                            <th>Vendor Name</th>
                            <th>SMS Active</th>
                            <th>Whatsapp Active</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
                  <br/>
              </div>
          </div>
      </div>
  </div>
</div>
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
      function getVendors() {
          var url = "{{route('vendors.getData')}}";

          table = $('.datatable').DataTable({
                "language": {
                    "emptyTable": "No records available"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "GET",
                },
                columns:[
                  {
                      name: 'name',
                      data: 'name',
                  },
                  {
                      name: 'sms_active',
                      data: 'sms_active',
                      render: function (data, type, row) {
                            if(row.sms_active == 0) {
                                  return `<select class="form-control activate_sms" data-vendor_id="${row.id}">
                                            <option value="0" selected>In-Active</option>
                                            <option value="1">Active</option>
                                          </select>`;
                                 }
                                 else{
                                 return `<select class="form-control activate_sms" data-vendor_id="${row.id}">
                                            <option value="0">In-Active</option>
                                            <option value="1" selected>Active</option>
                                          </select>`;
                                 }
                          },
                  }
                  ,
                  {
                      name: 'whatsapp_active',
                      data: 'whatsapp_active',
                      render: function (data, type, row) {
                            if(row.whatsapp_active == 0) {
                                  return `<select class="form-control activate_whatsapp" data-vendor_id="${row.id}">
                                            <option value="0" selected>In-Active</option>
                                            <option value="1">Active</option>
                                          </select>`;
                                 }
                                 else{
                                 return `<select class="form-control activate_whatsapp" data-vendor_id="${row.id}">
                                            <option value="0">In-Active</option>
                                            <option value="1" selected>Active</option>
                                          </select>`;
                                 }
                          },
                  }
                ],
                select: true,
                "order": [
                    [0, "desc"]
                ],
                searching: false,
                "iDisplayLength": 10,
          });
          $('.datatable').on('change', '.activate_whatsapp', function (e) {
              e.stopPropagation();
              var status=$(this).val();
              var vendor_id=$(this).data("vendor_id");
              $.ajax({
                url: "{{ route('activate.whatsapp') }}",
                type: "POST",
                data:  {
                'status':status,
                'vendor_id':vendor_id
                },
                success: function (data) {
                  Toast.fire({
                    icon: 'success',
                    title: data.message
                  });
                  $('.datatable').DataTable().ajax.reload();
                },
                error: function (data) {
                }
              });
          });
          $('.datatable').on('change', '.activate_sms', function (e) {
              e.stopPropagation();
              var status=$(this).val();
              var vendor_id=$(this).data("vendor_id");
              $.ajax({
                url: "{{ route('activate.sms') }}",
                type: "POST",
                data:  {
                'status':status,
                'vendor_id':vendor_id
                },
                success: function (data) {
                  Toast.fire({
                    icon: 'success',
                    title: data.message
                  });
                  $('.datatable').DataTable().ajax.reload();
                },
                error: function (data) {
                }
              });
          });
          }


    
      getVendors();
    });
  </script>
@endpush
