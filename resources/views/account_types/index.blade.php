@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Account Types</h4>
          <p>
          <a>AMC Management</a> /
              <span>Account Types</span>
          </p>
      </div>
    </div>

    <div class="col-12 py-4">

      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th>Account Type Name</th>
                              <th>Maximum Investment Amount</th>
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
<input type="hidden" id="accounttypeId" name="id">
@endsection

@section('modal')
<!-- Department Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="editaccounttypeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Risk Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editaccounttypeForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="account_name" class="col-sm-2 col-form-label">Account Type</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="account_name" placeholder="Risk Profile Type" name="account_name" autocomplete="off" disabled>
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="max_investment_amount" class="col-sm-2 col-form-label">Maximum Investment Amount</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control" id="max_investment_amount" placeholder="Maximum Investment Amount" name="max_investment_amount" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
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

      function getAccountType(max_investment_amount='') {

        var url = "{{route('account_types.getData')}}";

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
                      url: url,
                      type: "GET",
                  },
                  columns: [
                      {
                          name: 'account_name',
                          data: 'account_name',
                      },
                      {
                          name: 'max_investment_amount',
                          data: 'max_investment_amount',
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
                    [2, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          
          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            console.log("data", data);
            $('#accounttypeId').val(data.id);
            $('#editaccounttypeForm').find($('input[name="risk_profile_type"]')).val(data.type);
            $('#editaccounttypeForm').find($('input[name="max_investment_amount"]')).val(data.max_investment_amount);
            $('#editaccounttypeModal').modal('show');
          });
        }
      function clearDatatable() {
          table.clear();
          table.destroy();
      }

      $("#addriskprofileModal, #editaccounttypeModal").on("hidden.bs.modal", function () {
          $('#addriskprofileForm').trigger("reset");
          $('#editaccounttypeForm').trigger("reset");
      });


      $('#editBtn').click(function (e) {

          var id = $('#accounttypeId').val();
          var form = new FormData($('#editaccounttypeForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('account_type.update', '')}}" + "/" + id;
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
                      $('#editaccounttypeModal').modal('hide');
                      $('#editaccounttypeForm').trigger("reset");
                      table.draw();
                  } else {
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

      $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var max_investment_amount = $('#filter-form').find($('input[name="filter-max-investment-amount"]')).val();
        getAccountType(risk_profile_type,min_transaction_amount,max_transaction_amount,max_investment_amount);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getAccountType();
    });
    getAccountType();
    });
  </script>
@endpush
