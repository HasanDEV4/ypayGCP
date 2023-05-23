@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
    <div class="row">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
        <div class="ml-2 pl-1">
            <h4>Change Profiles Status</h4>
            <p>
                <a>Customer Management</a> /
                <span>Edit Profile Requests</span>
                /
                <span>Change Profiles Status</span>
            </p>
        </div>
        <div>
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
                  <table class="mb-0 table datatable w-100">
                      <thead>
                          <tr>
                              <th>AMC Name</th>
                              <th>Status</th>
                              <th>Download Profile</th>
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
<input type="hidden" id="profileId" name="id" value={{$profile_id}}>
@endsection
@section('modal')
<div class="modal fade" id="editrequeststatusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editrequeststatusForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="status" autocomplete="off" id="status">
                                  <option selected disabled>Select Status</option>
                                  <option value="0">Pending</option>
                                  <option value="1">Approve</option>
                                  <option value="2">Reject</option>
                                  <option value="3">On Hold</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <input type="hidden" id="changestatusId" name="changestatusId">
              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Update</button>
            </div>
            </form>
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
      var export_url='';
      var ids = [];
      var selected_profiles = [];

      function geteditedprofiles(customerId = '',bank='', from = '',to='', cnic = '',status='') {
        var profileId=$('#profileId').val();
        var queryParams = '?bank='+bank+'&customerId='+customerId+'&from='+from+'&to='+to+'&cnic='+cnic+'&status='+status+'&profileId='+profileId;
          var url = "{{route('edit_requests.geteditstatusData')}}";

          table = $('.datatable').DataTable({

                  "language": {
                      "emptyTable": "No records available"
                  },
                    "lengthMenu": [ 10, 25, 50, 75, 100 ],
                  processing: true,
                  serverSide: true,
                  responsive: true,
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
                          name: 'status',
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                return `<div class="badge badge-dark p-2">Pending</div>`;
                              }
                              else if(row.status == 1) {
                                return `<div class="badge badge-success p-2">Approved</div>`;
                              }  
                              else if(row.status == 2) {
                                return `<div class="badge badge-danger p-2">Rejected</div>`;
                              }else{
                                return `<div class="badge badge-warning p-2">On Hold</div>`;
                              }
                          },
                          orderable: false
                      },
                      {
                          name: 'profile_download',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                <a class="btn btn-sm btn-light text-center" type="button" target="_blank" id="profile_download_btn"><i class="fa fa-download  text-info fa-lg" title="Download Profile in PDF"></i></a>
                                  </div>`;
                          },
                          orderable: false
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
                          data: 'updated_at',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                              <a class="btn btn-sm btn-light text-center edit" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
                                  </div>`;
                          },
                          searchable: false,
                          orderable: false
                      },
                  ],
                  select: true,
                  "order": [
                    [3, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
                
          });
          table.on('click', '.edit', function () {
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            console.log(data);
            $('#changestatusId').val(data.id);
            $('#editrequeststatusForm').find($('select[name="status"]')).val(data.status);
            $('#editrequeststatusModal').modal('show');
          });
          table.on('click', '#profile_download_btn', function (e) {
            e.preventDefault();
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            var amc_id=data.amc_id;
            var user_id=data.change_request.user_id;
            var change_request_id=data.change_request_id;
            $.ajax({
                url: "{{ route('export.request.status.pdf') }}",
                type: "POST",
                data:  {
                'amc_id':amc_id,
                'user_id':user_id,
                'change_request_id':change_request_id,
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                  var blob = new Blob([response]);
                  var link = document.createElement('a');
                  link.href = window.URL.createObjectURL(blob);
                  link.download = data.change_request.user.cust_cnic_detail.cnic_number+".pdf";
                  link.click();
                },
                error: function (data) {
                }
            });
        });
      }


      function handleValidationErrors(element, error) {
        let profile_data_file = element.find($('input[name="profile_data_file"]'));
          if(error.profile_data_file) {
            profile_data_file.addClass('is-invalid');
            profile_data_file.next('.error').html(error.profile_data_file);
            profile_data_file.next('.error').removeClass('hide').addClass('show');
          } else {
            profile_data_file.removeClass('is-invalid').addClass('is-valid');
            profile_data_file.next('.error').html('');
            profile_data_file.next('.error').removeClass('show').addClass('hide');
          }
          let email = element.find($('input[name="email"]'));
          if(error.email) {
            email.addClass('is-invalid');
            email.next('.error').html(error.profile_data_file);
            email.next('.error').removeClass('hide').addClass('show');
          } else {
            email.removeClass('is-invalid').addClass('is-valid');
            email.next('.error').html('');
            email.next('.error').removeClass('show').addClass('hide');
          }
          let companyNameInput = element.find($('input[name="company_name"]'));
          if(error.company_name) {
              companyNameInput.addClass('is-invalid');
              companyNameInput.next('.error').html(error.company_name);
              companyNameInput.next('.error').removeClass('hide').addClass('show');
          } else {
              companyNameInput.removeClass('is-invalid').addClass('is-valid');
              companyNameInput.next('.error').html('');
              companyNameInput.next('.error').removeClass('show').addClass('hide');
          }
          let categoryInput = element.find($('select[name="category"]'));
          if(error.category) {
              categoryInput.addClass('is-invalid');
              categoryInput.next('.error').html(error.category);
              categoryInput.next('.error').removeClass('hide').addClass('show');
          } else {
              categoryInput.removeClass('is-invalid').addClass('is-valid');
              categoryInput.next('.error').html('');
              categoryInput.next('.error').removeClass('show').addClass('hide');
          }
          let companyLogoInput = element.find($('input[name="logo"]'));
          if(error.logo) {
              companyLogoInput.addClass('is-invalid');
              companyLogoInput.next('.error').html(error.logo);
              companyLogoInput.next('.error').removeClass('hide').addClass('show');
          } else {
              companyLogoInput.removeClass('is-invalid').addClass('is-valid');
              companyLogoInput.next('.error').html('');
              companyLogoInput.next('.error').removeClass('show').addClass('hide');
          }
          let contactNoInput = element.find($('input[name="contact_no"]'));
          if(error.contact_no) {
              contactNoInput.addClass('is-invalid');
              contactNoInput.next('.error').html(error.contact_no);
              contactNoInput.next('.error').removeClass('hide').addClass('show');
          } else {
              contactNoInput.removeClass('is-invalid').addClass('is-valid');
              contactNoInput.next('.error').html('');
              contactNoInput.next('.error').removeClass('show').addClass('hide');
          }
          let contactPersonNameInput = element.find($('input[name="contact_person_name"]'));
          if(error.contact_person_name) {
              contactPersonNameInput.addClass('is-invalid');
              contactPersonNameInput.next('.error').html(error.contact_person_name);
              contactPersonNameInput.next('.error').removeClass('hide').addClass('show');
          } else {
              contactPersonNameInput.removeClass('is-invalid').addClass('is-valid');
              contactPersonNameInput.next('.error').html('');
              contactPersonNameInput.next('.error').removeClass('show').addClass('hide');
          }
          let contactPersonRoleInput = element.find($('input[name="contact_person_role"]'));
          if(error.contact_person_role) {
              contactPersonRoleInput.addClass('is-invalid');
              contactPersonRoleInput.next('.error').html(error.contact_person_role);
              contactPersonRoleInput.next('.error').removeClass('hide').addClass('show');
          } else {
              contactPersonRoleInput.removeClass('is-invalid').addClass('is-valid');
              contactPersonRoleInput.next('.error').html('');
              contactPersonRoleInput.next('.error').removeClass('show').addClass('hide');
          }
          let secpNumberInput = element.find($('input[name="secp_number"]'));
          if(error.secp_number) {
              secpNumberInput.addClass('is-invalid');
              secpNumberInput.next('.error').html(error.secp_number);
              secpNumberInput.next('.error').removeClass('hide').addClass('show');
          } else {
              secpNumberInput.removeClass('is-invalid').addClass('is-valid');
              secpNumberInput.next('.error').html('');
              secpNumberInput.next('.error').removeClass('show').addClass('hide');
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

      $("#addAmcModal, #editAmcModal, #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addAmcForm').trigger("reset");
          $('#editAmcForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addAmcForm'))
          resetValidationErrors($('#editAmcForm'))
      });

      $('#saveBtn').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addAmcForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('amc.store') }}",
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
                      $('#addAmcModal').modal('hide');
                      $('#addAmcForm').trigger("reset");
                      table.draw();
                  } else {
                      console.log('data error', data);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addAmcForm'), data.error)
                  }
              },
              error: function (data) {

              }
          });
      });

      $('#editBtn').click(function (e) {
          var form = new FormData($('#editrequeststatusForm')[0]);
          var url = "{{route('change_request_status.update')}}";
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
                      $('#editrequeststatusModal').modal('hide');
                      $('#editrequeststatusForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editrequeststatusForm'), data.error)
                  }
              },
              error: function (data) {
                  // $('#saveBtn').html('Save Changes');
              }
          });
      });

      $('#deleteBtn').click(function (e) {
          e.preventDefault();
          var id = $('#reqId').val();
          var url = "{{route('amc.destroy', '')}}" + "/" + id;

          $.ajax({
              url: url,
              type: "DELETE",
              dataType: 'json',
              success: function (data) {
                  if (!data.error) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
                      $('#deleteDepartmentModal').modal('hide');
                      table.draw();
                  }
              },
              error: function (data) {
              }
          });
      });

      $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var customerId = $('#filter-form').find($('select[name="filter-user-id"]')).val();
        var cnic = $('#filter-form').find($('input[name="filter-customer-cnic"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        status = status != null ? status : '';
        geteditedprofiles(customerId,bank, from,to, cnic,status);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        $("#filter-user-id").val('').trigger('change');
        clearDatatable();
        geteditedprofiles();
    });
        function customerFilterDropDown() {

              $('#filter-user-id').select2({
                  width: '100%',
                  minimumInputLength: 0,
                  dataType: 'json',
                  placeholder: 'Select',
                  ajax: {
                      url: function () {
                          return "{{ route('customers.autocomplete') }}";
                      },
                      processResults: function (data, page) {
                          return {
                              results: data
                          };
                      }
                  }
          });

          }
    $('#profile-import-btn').click(function (e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('cust.import.profile') }}",
        type: "POST",
        data:  new FormData($('#import_csv_form')[0]),
        // dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        enctype: 'multipart/form-data',
        success: function (data) {
          console.log("data", data);
          if (!data.error) {
            if(data.already_registered_users.length>0)
            {
                Swal.fire(
                    data.message,
                    "Users Profiles on these Line Numbers Cannot be added because their Email or Phone Number is already taken by some User.\n Line Numbers: ["+String(data.already_registered_users)+"]",
                    "success"
                )
            }
            else
            {
                Toast.fire({
                        icon: 'success',
                        title: data.message
                      });
            }
            $('#importprofileModal').modal('hide');
            $('.datatable').DataTable().ajax.reload();
          }else{
            handleValidationErrors($('#import_csv_form'),data.error);
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
    $('#import-btn').click(function (e) {
        e.preventDefault();
        $('#importprofileModal').modal('show');
        $('#profile_data_file').val('');
    });
      geteditedprofiles();
      customerFilterDropDown();
    });
  </script>
@endpush
