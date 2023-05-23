@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Risk Profiles</h4>
          <p>
          <a>AMC Management</a> /
              <span>Risk Profiles</span>
          </p>
      </div>
      <!-- <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addriskprofileModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>
        Add Risk Profile
      </a> -->
    </div>

    <div class="col-12 py-4">
        <!-- <div class="accordion" id="accordionExample">
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
                            <label for="filter-risk-profile-type" class="form-label">Risk Profile Type</label>
                            <select class="form-control" id="filter-risk-profile-type" placeholder="Name" name="filter-risk-profile-type">
                                <option selected disabled>Select Risk Profile Type</option>
                                <option value="High">High</option>
                                <option value="Low">Low</option>
                            </select>
                          </div>
                          <div class="col-12 col-md-3">
                          <label for="min_transaction_amount" class="form-label">Minimum Transaction Amount</label>
                          <input type="number" class="form-control" id="min_transaction_amount" placeholder="Minimum Transaction Amount" name="filter-min-amount" autocomplete="off">
                          </div>
                          <div class="col-12 col-md-3">
                          <label for="max_transaction_amount" class="form-label">Maximum Transaction Amount</label>
                          <input type="number" class="form-control" id="max_transaction_amount" placeholder="Maximum Transaction Amount" name="filter-max-amount" autocomplete="off">
                          </div>
                          <div class="col-12 col-md-3">
                          <label for="max_investment_amount" class="form-label">Maximum Investment Amount</label>
                          <input type="number" class="form-control" id="max_investment_amount" placeholder="Maximum Investment Amount" name="filter-max-investment-amount" autocomplete="off">
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
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th>Risk Profile Type</th>
                              <th>Risk Profile</th>
                              <th>Minimum Transaction Amount</th>
                              <th>Maximum Transaction Amount</th>
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
<input type="hidden" id="riskprofileId" name="id">
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addriskprofileModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Risk Profile</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addriskprofileForm" onsubmit="event.preventDefault()">
                <div class="form-group row">
                    <label for="risk_profile_type" class="col-sm-2 col-form-label">Risk Profile Type</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="risk_profile_type" placeholder="Risk Profile Type" name="risk_profile_type" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="min_transaction_amount" class="col-sm-2 col-form-label">Minimum Transaction Amount</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control" id="min_transaction_amount" placeholder="Minimum Transaction Amount" name="min_transaction_amount" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="max_transaction_amount" class="col-sm-2 col-form-label">Maximum Transaction Amount</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control" id="max_transaction_amount" placeholder="Maximum Transaction Amount" name="max_transaction_amount" autocomplete="off">
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
                  <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
                </div>
              </form>
      </div>
  </div>
</div>
<!-- Department Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="editriskprofileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Risk Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editriskprofileForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="risk_profile_type" class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="risk_profile_type" placeholder="Risk Profile Type" name="risk_profile_type" autocomplete="off" disabled>
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="min_transaction_amount" class="col-sm-2 col-form-label">Minimum Transaction Amount</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control" id="min_transaction_amount" placeholder="Minimum Transaction Amount" name="min_transaction_amount" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="max_transaction_amount" class="col-sm-2 col-form-label">Maximum Transaction Amount</label>
                  <div class="col-sm-10">
                      <input type="number" class="form-control" id="max_transaction_amount" placeholder="Maximum Transaction Amount" name="max_transaction_amount" autocomplete="off">
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
                <div class="form-group row">
                <label for="max_investment_amount" class="col-sm-2 col-form-label">Risk Profile</label>
                <label class="radio-inline m-2">
                  <input type="radio" name="risk_profile" id="yes" value="1" required>Yes
                </label>
                <label class="radio-inline m-2">
                  <input type="radio" name="risk_profile" id="no" value="0">No
                </label>
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

      function getRiskProfile(risk_profile_type='',min_transaction_amount='',max_transaction_amount='',max_investment_amount='') {

        var queryParams = '?&risk_profile_type='+risk_profile_type+'&min_transaction_amount='+min_transaction_amount+'&max_transaction_amount='+max_transaction_amount+'&max_investment_amount='+max_investment_amount;
          var url = "{{route('risk_profile.getData')}}";

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
                          name: 'type',
                          data: 'type',
                      },
                      {
                          name: 'risk_profile',
                          data: 'risk_profile',
                          render: function (data, type, row) {
                              if(row.risk_profile) {
                                if(row.risk_profile==1)
                                return `<div class="badge badge-success p-2">Yes</div>`;
                              } else {
                                  return `<div class="badge badge-dark p-2">No</div>`;
                              }
                          },
                      }
                      ,
                      {
                          name: 'min_transaction_amount',
                          data: 'min_transaction_amount',
                      },
                      {
                          name: 'max_transaction_amount',
                          data: 'max_transaction_amount',
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
                    [4, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          
          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            console.log("data", data);
            $('#riskprofileId').val(data.id);
            $('#editriskprofileForm').find($('input[name="risk_profile_type"]')).val(data.type);
            $('#editriskprofileForm').find($('input[name="min_transaction_amount"]')).val(data.min_transaction_amount);
            $('#editriskprofileForm').find($('input[name="max_transaction_amount"]')).val(data.max_transaction_amount);
            $('#editriskprofileForm').find($('input[name="max_investment_amount"]')).val(data.max_investment_amount);
            if(data.risk_profile==1)
            $('#editriskprofileForm').find($('#yes')).attr('checked',true);
            else
            $('#editriskprofileForm').find($('#no')).attr('checked',true);
            $('#editriskprofileModal').modal('show');
          });
        }
      function clearDatatable() {
          table.clear();
          table.destroy();
      }

      $("#addriskprofileModal, #editriskprofileModal").on("hidden.bs.modal", function () {
          $('#addriskprofileForm').trigger("reset");
          $('#editriskprofileForm').trigger("reset");
      });

    //   $('#saveBtn').click(function(event) {
    //   event.preventDefault();
    //   var form = new FormData($('#addriskprofileForm')[0]);
    //   $.ajax({
    //     url: "{{ route('risk_profile.store') }}",
    //     type: "POST",
    //     data:  form,
    //     // dataType: 'json',
    //     contentType: false,
    //     cache: false,
    //     processData:false,
    //     enctype: 'multipart/form-data',
    //     success: function (data) {
    //       console.log("data", data.error);
    //       if (!data.error) {
    //         Toast.fire({
    //           icon: 'success',
    //           title: data.message
    //         });
    //         $('#addriskprofileModal').modal('hide');
    //         $('#addriskprofileForm').trigger("reset");
    //         table.draw();
    //       }else{
    //         console.log('error',data.error);
    //       }
    //     },
    //     error: function (data) {
    //       Toast.fire({
    //                     icon: 'error',
    //                     title: 'Oops Something Went Wrong!',
    //                   });      

    //     }
    //   });
    // });

      $('#editBtn').click(function (e) {

          var id = $('#riskprofileId').val();
          var form = new FormData($('#editriskprofileForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('risk_profile.update', '')}}" + "/" + id;
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
                      $('#editriskprofileModal').modal('hide');
                      $('#editriskprofileForm').trigger("reset");
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
        var risk_profile_type = $('#filter-form').find($('select[name="filter-risk-profile-type"]')).val();
        var min_transaction_amount = $('#filter-form').find($('input[name="filter-min-amount"]')).val();
        var max_transaction_amount = $('#filter-form').find($('input[name="filter-max-amount"]')).val();
        var max_investment_amount = $('#filter-form').find($('input[name="filter-max-investment-amount"]')).val();
        getRiskProfile(risk_profile_type,min_transaction_amount,max_transaction_amount,max_investment_amount);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#filter-amc-id").val('').trigger('change');
        $("#filter-user-id").val('').trigger('change');
        $("#filter-fund-id").val('').trigger('change');
        $('#filter-form').trigger("reset");
        clearDatatable();
        getRiskProfile();
    });
    getRiskProfile();
    });
  </script>
@endpush
