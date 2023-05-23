@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>AMC Profiles</h4>
          <p>
              <a>AMC Management</a> /
              <span>AMC Profiles</span>
          </p>
      </div>
      @can('add-amc')
      <a href="{{route('add.amc')}}"  class="btn btn-primary" >
        <i class="fas fa-plus mr-2"></i>
        Add AMC
    </a>
    @endcan
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
                          <label for="inputEmail4" class="form-label">Entity</label>
                          <select class="form-control amcSelectFilter" id="filter-amc-id" placeholder="Entity" name="filter-amc-id" autocomplete="off"></select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Complaint Email</label>
                            <input placeholder="Complaint Email" type="text" name="filter-complaint-email" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Contact Number</label>
                            <input placeholder="Contact Number" type="text" name="filter-contact-number" class="form-control " id="inputEmail4">
                          </div>
                        <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">Status</label>
                            <select class="mb-2 form-control" name="filter-status" autocomplete="off">
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
                              <th>Entity</th>
                              {{-- <th>Address</th> --}}
                              <th>Logo</th>
                              <th>Complaint Email</th>
                              <th>Contact No</th>
                              {{-- <th>(CRN)</th> --}}
                              {{-- <th>NTN</th> --}}
                              {{-- <th>Contact Person</th> --}}
                              {{-- <th>URL</th> --}}
                              {{-- <th>Bank</th>
                              <th>Account</th>
                              <th>Iban</th> --}}
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
<input type="hidden" id="amcId" name="id">
@endsection

@section('modal')
<!-- Amc Add Modal -->
<div class="modal fade" id="addAmcModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add AMC</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="addAmcForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <div class="form-group row">
                          <label for="Entity name" class="col-sm-2 col-form-label">Entity Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control entity_name" id="entity_name" placeholder="Entity Name" name="entity_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Address" class="col-sm-2 col-form-label">Address</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control address" id="address" placeholder="Address" name="address" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Logo" class="col-sm-2 col-form-label">Logo</label>
                          <div class="col-sm-10">
                            <input type='file' class="form-control logo h-100" id="logo" name="logo" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact no" class="col-sm-2 col-form-label">Contact No</label>
                          <div class="col-sm-10">
                            <input type='number' class="form-control contact_no" id="contact_no" name="contact_no" placeholder="Contact No" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Complaint Email" class="col-sm-2 col-form-label">Complaint Email</label>
                          <div class="col-sm-10">
                            <input type='email' class="form-control compliant_email" id="compliant_email" name="compliant_email" placeholder="Complaint Email" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Company Registration Number (CRN)" class="col-sm-2 col-form-label">(CRN)</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control company_registration_number" id="company_registration_number" name="company_registration_number" placeholder="Company Registration Number (CRN)" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="NTN" class="col-sm-2 col-form-label">NTN</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control ntn" id="ntn" name="ntn" placeholder="NTN" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person" class="col-sm-2 col-form-label">Contact Person</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person" id="contact_person" name="contact_person" placeholder="Contact Person" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="URL" class="col-sm-2 col-form-label">URL</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control url" id="url" name="url" placeholder="https://ypayfinancial.com/" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="Bank Name" class="col-sm-2 col-form-label">Bank Name</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control bank_name" id="bank_name" name="bank_name" placeholder="Bank Name" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="Account Title" class="col-sm-2 col-form-label">Account Title</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control url" id="account_title" name="account_title" placeholder="Account Title" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="Iban Number" class="col-sm-2 col-form-label">IBAN Number</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control iban_number" id="iban_number" name="iban_number" placeholder="Iban Number" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="status" autocomplete="off">
                                  <option selected disabled>Select Status</option>
                                  <option value="1">Active</option>
                                  <option value="0">In-Active</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
              </div>
              </form>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
              </div>
          </div>
      </div>
</div>
<!-- Department Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="editAmcModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Amc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAmcForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <div class="form-group row">
                          <label for="Entity name" class="col-sm-2 col-form-label">Entity Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control entity_name" id="entity_name" placeholder="Entity Name" name="entity_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Address" class="col-sm-2 col-form-label">Address</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control address" id="address" placeholder="Address" name="address" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Logo" class="col-sm-2 col-form-label">Logo</label>
                          <div class="col-sm-10">
                            <input type='file' class="form-control logo h-100" id="logo" name="logo" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact no" class="col-sm-2 col-form-label">Contact No</label>
                          <div class="col-sm-10">
                            <input type='number' class="form-control contact_no" id="contact_no" name="contact_no" placeholder="Contact No"/>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Complaint Email" class="col-sm-2 col-form-label">Complaint Email</label>
                          <div class="col-sm-10">
                            <input type='email' class="form-control compliant_email" id="compliant_email" name="compliant_email" placeholder="Complaint Email" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Company Registration Number (CRN)" class="col-sm-2 col-form-label">(CRN)</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control company_registration_number" id="company_registration_number" name="company_registration_number" placeholder="Company Registration Number (CRN)" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="NTN" class="col-sm-2 col-form-label">NTN</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control ntn" id="ntn" name="ntn" placeholder="NTN" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person" class="col-sm-2 col-form-label">Contact Person</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person" id="contact_person" name="contact_person" placeholder="Contact Person" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="URL" class="col-sm-2 col-form-label">URL</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control url" id="url" name="url" placeholder="URL" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="Bank Name" class="col-sm-2 col-form-label">Bank Name</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control bank_name" id="bank_name" name="bank_name" placeholder="Bank Name" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="Account Title" class="col-sm-2 col-form-label">Account Title</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control url" id="account_title" name="account_title" placeholder="Account Title" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="Iban Number" class="col-sm-2 col-form-label">Iban Number</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control iban_number" id="iban_number" name="iban_number" placeholder="Iban Number" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="status" autocomplete="off">
                                  <option selected disabled>Select Status</option>
                                  <option value="1">Active</option>
                                  <option value="0">In-Active</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
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
      var table;
      var ids = [];

      function getAmc(status='',amcid='',complaintEmail='',contactNumber='',crnNumber='',ntnNumber='') {


        var queryParams = '?status='+status+'&amcid='+amcid+'&complaintEmail='+complaintEmail+'&contactNumber='+contactNumber+'&crnNumber='+crnNumber+'&ntnNumber='+ntnNumber;

          var url = "{{route('amc.getData')}}";

          table = $('.datatable').DataTable({
                  "language": {
                      "emptyTable": "No records available"
                  },
                  processing: true,
                  serverSide: true,
                //   dom: 'Bfrtip',
                //   buttons: ['excel'],
                  ajax: {
                      url: url+queryParams,
                      type: "GET",
                  },
                  columns: [
                      {
                          name: 'Entity Name',
                          data: 'entity_name',
                      },
                    //   {
                    //       name: 'Address',
                    //       data: 'address',
                    //   },
                      {
                          name: 'Logo',
                          data: 'logo',
                          render: function (data, type, row) {
                              if(row) {
                                var logo = row.logo;
                                  return `<div p-2"><a href="${row.logo.startsWith('http')?row.logo:"{{env('S3_BUCKET_URL')}}"+row.logo}" download>Download</a></div>`;
                              } else {
                                  return `<div class="badge badge-dark p-2">------</div>`;
                              }
                          },
                          orderable: false
                          // data: 'logo',
                      },
                      {
                          name: 'Complaint Email',
                          data: 'compliant_email',
                      }
                      ,{
                          name: 'Contact No',
                          data: 'contact_no',
                      },
                    //   ,{
                    //       name: 'Company Registration Number (CRN)',
                    //       data: 'company_registration_number',
                    //   }
                    //   ,{
                    //       name: 'NTN',
                    //       data: 'ntn',
                    //   }
                    //   ,
                    //   {
                    //       name: 'Contact Person',
                    //       data: 'contact_person',
                    //   },
                    //   {
                    //       name: 'URL',
                    //       data: 'url',
                    //   }
                    //   ,
                      // {
                      //     name: 'Bank Name',
                      //     data: 'bank_name',
                      // },
                      // {
                      //     name: 'Account Title',
                      //     data: 'account_title',
                      // },
                      // {
                      //     name: 'Iban Number',
                      //     data: 'iban_number',
                      // },
                      {
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                return `<div class="badge badge-dark p-2">${row.parsedStatus}</div>`;
                              } else {
                                return `<div class="badge badge-success p-2">${row.parsedStatus}</div>`;
                                  
                              }
                          },
                      },
                      {
                          data: 'created_at',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('edit-amc')
                                      <a class="btn btn-sm btn-light text-center edit" type="button" href="{{ url('/') }}/edit/amc/${row.id}"><i class="fas fa-edit text-info fa-lg"></i></a>
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

        //   table.on('click', '.edit', function () {
        //     var data = table.row($(this).parents('tr')).data();
        //     $('#amcId').val(data.id);
        //     $('#editAmcForm').find($('input[name="entity_name"]')).val(data.entity_name);
        //     $('#editAmcForm').find($('input[name="address"]')).val(data.address);
        //     $('#editAmcForm').find($('input[name="contact_no"]')).val(data.contact_no);
        //     $('#editAmcForm').find($('input[name="compliant_email"]')).val(data.compliant_email);
        //     $('#editAmcForm').find($('input[name="company_registration_number"]')).val(data.company_registration_number);
        //     $('#editAmcForm').find($('input[name="ntn"]')).val(data.ntn);
        //     $('#editAmcForm').find($('input[name="contact_person"]')).val(data.contact_person);
        //     $('#editAmcForm').find($('input[name="url"]')).val(data.url);
        //     $('#editAmcForm').find($('input[name="bank_name"]')).val(data.bank_name);
        //     $('#editAmcForm').find($('input[name="account_title"]')).val(data.account_title);
        //     $('#editAmcForm').find($('input[name="iban_number"]')).val(data.iban_number);
        //     $('#editAmcForm').find($('select[name="status"]')).val(data.status);
        //     $('#editAmcModal').modal('show');
        //   });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#amcId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {
          let entity_name = element.find($('input[name="entity_name"]'));
          if(error.entity_name) {
              entity_name.addClass('is-invalid');
              entity_name.next('.error').html(error.entity_name);
              entity_name.next('.error').removeClass('hide').addClass('show');
          } else {
              entity_name.removeClass('is-invalid').addClass('is-valid');
              entity_name.next('.error').html('');
              entity_name.next('.error').removeClass('show').addClass('hide');
          }
          let address = element.find($('input[name="address"]'));
          if(error.address) {
              address.addClass('is-invalid');
              address.next('.error').html(error.address);
              address.next('.error').removeClass('hide').addClass('show');
          } else {
              address.removeClass('is-invalid').addClass('is-valid');
              address.next('.error').html('');
              address.next('.error').removeClass('show').addClass('hide');
          }
          let logo = element.find($('input[name="logo"]'));
          if(error.logo) {
              logo.addClass('is-invalid');
              logo.next('.error').html(error.logo);
              logo.next('.error').removeClass('hide').addClass('show');
          } else {
              logo.removeClass('is-invalid').addClass('is-valid');
              logo.next('.error').html('');
              logo.next('.error').removeClass('show').addClass('hide');
          }
          let contact_no = element.find($('input[name="contact_no"]'));
          if(error.contact_no) {
              contact_no.addClass('is-invalid');
              contact_no.next('.error').html(error.contact_no);
              contact_no.next('.error').removeClass('hide').addClass('show');
          } else {
              contact_no.removeClass('is-invalid').addClass('is-valid');
              contact_no.next('.error').html('');
              contact_no.next('.error').removeClass('show').addClass('hide');
          }
          let compliant_email = element.find($('input[name="compliant_email"]'));
          if(error.compliant_email) {
              compliant_email.addClass('is-invalid');
              compliant_email.next('.error').html(error.compliant_email);
              compliant_email.next('.error').removeClass('hide').addClass('show');
          } else {
              compliant_email.removeClass('is-invalid').addClass('is-valid');
              compliant_email.next('.error').html('');
              compliant_email.next('.error').removeClass('show').addClass('hide');
          }
          let company_registration_number = element.find($('input[name="company_registration_number"]'));
          if(error.company_registration_number) {
              company_registration_number.addClass('is-invalid');
              company_registration_number.next('.error').html(error.company_registration_number);
              company_registration_number.next('.error').removeClass('hide').addClass('show');
          } else {
              company_registration_number.removeClass('is-invalid').addClass('is-valid');
              company_registration_number.next('.error').html('');
              company_registration_number.next('.error').removeClass('show').addClass('hide');
          }
          let ntn = element.find($('input[name="ntn"]'));
          if(error.ntn) {
              ntn.addClass('is-invalid');
              ntn.next('.error').html(error.ntn);
              ntn.next('.error').removeClass('hide').addClass('show');
          } else {
              ntn.removeClass('is-invalid').addClass('is-valid');
              ntn.next('.error').html('');
              ntn.next('.error').removeClass('show').addClass('hide');
          }
          let contact_person = element.find($('input[name="contact_person"]'));
          if(error.contact_person) {
              contact_person.addClass('is-invalid');
              contact_person.next('.error').html(error.contact_person);
              contact_person.next('.error').removeClass('hide').addClass('show');
          } else {
              contact_person.removeClass('is-invalid').addClass('is-valid');
              contact_person.next('.error').html('');
              contact_person.next('.error').removeClass('show').addClass('hide');
          }
          let url = element.find($('input[name="url"]'));
          if(error.url) {
              url.addClass('is-invalid');
              url.next('.error').html(error.url);
              url.next('.error').removeClass('hide').addClass('show');
          } else {
              url.removeClass('is-invalid').addClass('is-valid');
              url.next('.error').html('');
              url.next('.error').removeClass('show').addClass('hide');
          }
          // let bank_name = element.find($('input[name="bank_name"]'));
          // if(error.bank_name) {
          //     bank_name.addClass('is-invalid');
          //     bank_name.next('.error').html(error.bank_name);
          //     bank_name.next('.error').removeClass('hide').addClass('show');
          // } else {
          //     bank_name.removeClass('is-invalid').addClass('is-valid');
          //     bank_name.next('.error').html('');
          //     bank_name.next('.error').removeClass('show').addClass('hide');
          // }
          let account_title = element.find($('input[name="account_title"]'));
          if(error.account_title) {
              account_title.addClass('is-invalid');
              account_title.next('.error').html(error.account_title);
              account_title.next('.error').removeClass('hide').addClass('show');
          } else {
              account_title.removeClass('is-invalid').addClass('is-valid');
              account_title.next('.error').html('');
              account_title.next('.error').removeClass('show').addClass('hide');
          }
          let iban_number = element.find($('input[name="iban_number"]'));
          if(error.iban_number) {
              iban_number.addClass('is-invalid');
              iban_number.next('.error').html(error.iban_number);
              iban_number.next('.error').removeClass('hide').addClass('show');
          } else {
              iban_number.removeClass('is-invalid').addClass('is-valid');
              iban_number.next('.error').html('');
              iban_number.next('.error').removeClass('show').addClass('hide');
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
                    //   console.log('data error', data);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addAmcForm'), data.error)
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

      $('#editBtn').click(function (e) {

          var id = $('#amcId').val();
          var form = new FormData($('#editAmcForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('amc.update', '')}}" + "/" + id;
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
                      $('#editAmcModal').modal('hide');
                      $('#editAmcForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editAmcForm'), data.error)
                  }
              },
              error: function (data) {

                Toast.fire({
                        icon: 'error',
                        title: 'Oops Something Went Wrong!',
                      });
                  // $('#saveBtn').html('Save Changes');
              }
          });
      });

      $('#deleteBtn').click(function (e) {
          e.preventDefault();
          var id = $('#amcId').val();
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
        var amcid = $('#filter-form').find($('select[name="filter-amc-id"]')).val();
        var complaintEmail = $('#filter-form').find($('input[name="filter-complaint-email"]')).val();
        var contactNumber = $('#filter-form').find($('input[name="filter-contact-number"]')).val();
        var crnNumber = $('#filter-form').find($('input[name="filter-crn-number"]')).val();
        var ntnNumber = $('#filter-form').find($('input[name="filter-ntn-number"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        status = status != null ? status : '';
        getAmc(status,amcid,complaintEmail,contactNumber,crnNumber,ntnNumber);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getAmc();
    });
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
      getAmc();

    });
  </script>
@endpush
