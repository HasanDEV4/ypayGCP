@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
    <div class="row">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
        <div class="ml-2 pl-1">
            <h4>Customer Profiles</h4>
            <p>
                <!-- <a>Dashboard</a> / -->
                <a>Customer Management</a> /
                <span>Customer Profiles</span>
            </p>
        </div>
        <div>
        <a  class="btn btn-primary" href="{{route('cust.add.form')}}">
        <i class='fa fa-user'></i>
            Add Profile
        </a>
        <!-- <a  class="btn btn-primary" id="import-btn">
        <i class='fas fa-file-import'></i>
            Import
        </a> -->
        </div>
        </div>
    </div>
    <!-- <div class="col-2 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
        <button id="export-btn" class="btn btn-primary btn-lg">Export</button>
      </div>
    </div> -->
    <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <a  class="btn btn-primary" id="export-btn">
             <i class='fas fa-file-export'></i>
             Export Profiles in CSV
            </a>
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
                      <!-- <div class="col-12 col-md-3">
                        <label for="inputEmail4" class="form-label">Customer Name</label>
                        <select class="form-control customerSelectFilter" id="filter-user-id" placeholder="Name" name="filter-user-id"></select>
                      </div> -->
                      <div class="col-12 col-md-3">
                        <label for="inputEmail4" class="form-label">Customer Name</label>
                        <select class="form-control customerSelectFilter" id="filter-user-id" placeholder="Name" name="filter-user-id" autocomplete="off">
                            <option selected disabled value="">Select Customer</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{ $user->full_name.'-'.$user->cust_cnic_detail?->cnic_number }}</option>
                            @endforeach
                        </select>
                      </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Customer Email</label>
                            <input placeholder="Customer Email" type="text" name="filter-customer-email" class="form-control " id="inputEmail4">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Customer CNIC</label>
                            <input type="text" class="form-control" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X"  name="filter-customer-cnic" required="" >
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">From</label>
                            <input placeholder="From" type="datetime-local" class="form-control " id="inputEmail4" name="filter-from-date">
                          </div>
                    </div>
                    <div class="row g-3">
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">To</label>
                            <input placeholder="To" type="datetime-local" class="form-control " id="inputEmail4" name="filter-to-date">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Refer Code</label>
                            <input placeholder="Refer Code" type="text" name="filter-refer-code" class="form-control " id="inputEmail4">
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Platform</label>
                            <select class="mb-2 form-control" name="filter-platform" autocomplete="off">
                              <option value="" selected disabled>Select Platform</option>
                                  <option value="android">Android</option>
                                  <option value="ios">Ios</option>
                          </select>
                          </div>
                          <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">App Version</label>
                            <input placeholder="App Version" type="text" name="filter-app-version" class="form-control " id="inputEmail4">
                          </div>
                        <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">Profile Status</label>
                            <select class="mb-2 form-control" name="filter-status" autocomplete="off">
                              <option value="" selected disabled>Select Profile Status</option>
                              <option value="0">Pending</option>
                                  <option value="1">Approved</option>
                                  {{-- <option value="2">Rejected</option> --}}
                                  <option value="3">On Hold</option>
                          </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">User Status</label>
                            <select class="mb-2 form-control" name="filter-user-status" autocomplete="off">
                              <option selected disabled>Select User Status</option>
                              <option value="0">In-Active</option>
                                  <option value="1">Active</option>
                          </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputStatus4" class="form-label">Risk Profile Status</label>
                            <select class="mb-2 form-control" name="filter-risk-status" autocomplete="off">
                              <option selected disabled>Select Risk Profile Status</option>
                              <option value="0">Empty</option>
                              <option value="1">In-Process</option>
                              <option value="2">Approved</option>
                              <option value="3">Rejected</option>
                          </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Phone No</label>
                            <input type="text" class="form-control phone_no" id="phone_no" placeholder="Enter Phone Number" name="phone_no" autocomplete="off">
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
                  <table class="mb-0 table datatable w-100">
                      <thead>
                          <tr>
                              <th><input class="form-check-input2" type="checkbox" value="" id="select-all-profiles">Select All</th>
                              <th>Customer</th>
                              <th>Email</th>
                              <th>CNIC</th>
                              <th>Registered On</th>
                              <th>Phone No</th>
                              <th>App Version</th>
                              <th>Refer Code</th>
                              <th>Platform</th>
                              <th>View Comments</th>
                              <th>Transaction History</th>
                              <th>Risk Profile Status</th>
                              <th>Profile Status</th>
                              <th>User Status</th>
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
<input type="hidden" id="reqId" name="id">
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
                          <label for="company name" class="col-sm-2 col-form-label">Company Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control company_name" id="company_name" placeholder="Company name" name="company_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="category" class="col-sm-2 col-form-label">Category</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="category" autocomplete="off">
                                  <option selected disabled>Select Category</option>
                                  <option value="Public Limited">Public Limited</option>
                                  <option value="Private Limited">Private Limited</option>
                              </select>
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
                          <label for="Contact Person Name" class="col-sm-2 col-form-label">Contact Person Name</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_name" id="contact_person_name" name="contact_person_name" placeholder="Contact Person Name" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person Role" class="col-sm-2 col-form-label">Contact Person Role</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_role" id="contact_person_role" name="contact_person_role" placeholder="Contact Person Role" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="SECP Number" class="col-sm-2 col-form-label">SECP Number</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control secp_number" id="secp_number" name="secp_number" placeholder="SECP Number" />
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
                <h5 class="modal-title" id="exampleModalLongTitle">Manage Status Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAmcForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <!-- <div class="form-group row">
                          <label for="company name" class="col-sm-2 col-form-label">Company Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control company_name" id="company_name" placeholder="Company name" name="company_name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="category" class="col-sm-2 col-form-label">Category</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="category" autocomplete="off">
                                  <option selected disabled>Select Category</option>
                                  <option value="Public Limited">Public Limited</option>
                                  <option value="Private Limited">Private Limited</option>
                              </select>
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
                          <label for="Contact Person Name" class="col-sm-2 col-form-label">Contact Person Name</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_name" id="contact_person_name" name="contact_person_name" placeholder="Contact Person Name" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Contact Person Role" class="col-sm-2 col-form-label">Contact Person Role</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control contact_person_role" id="contact_person_role" name="contact_person_role" placeholder="Contact Person Role" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="SECP Number" class="col-sm-2 col-form-label">SECP Number</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control secp_number" id="secp_number" name="secp_number" placeholder="SECP Number" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div> -->
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Profile Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="profile_status" autocomplete="off">
                                  <option value="" selected disabled>Select Profile Status</option>
                                  <option value="0">Pending</option>
                                  <option value="1">Approved</option>
                                  {{-- <option value="2">Rejected</option> --}}
                                  <option value="3">On Hold</option>
                              </select>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">User Status</label>
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
                      <div class="form-group row">
                          <label for="inputPassword3" class="col-sm-2 col-form-label">Risk Profile Status</label>
                          <div class="col-sm-10">
                              <select class="mb-2 form-control" name="risk_profile_status" autocomplete="off">
                                  <option selected disabled>Select Risk Profile Status</option>
                                  <option value="0">Empty</option>
                                  <option value="1">In-Process</option>
                                  <option value="2">Approved</option>
                                  <option value="3">Rejected</option>
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
<!-- Amc Add Modal -->
<div class="modal fade" id="importprofileModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Import Profile</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="import_csv_form" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <div class="form-group row">
                          <label for="company name" class="col-sm-2 col-form-label">Import CSV File</label>
                          <div class="col-sm-10">
                              <input type="file" class="form-control h-auto" id="profile_data_file" accept=".csv"  name="profile_data_file" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                    </div>
              </div>
              </form>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="profile-import-btn">Import</button>
              </div>
          </div>
      </div>
</div>
<!-- Department Edit Modal -->
<div class="modal fade" id="editcommentsModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Admin Comments</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body ">
                  <form id="admin_comments_form" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                  <div class="row mb-3">
                    <div class="col-12">
                        <div id="old_comments_div">
                        </div>
                        <br>
                        <div id="comments_div">
                        </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <button type="button" class="btn btn-info add_comment">Add Comment</button>
                    <input type="hidden" id="user_id" name="id">
                  </div>
              </div>
              </form>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <a type="button" id="save_btn" class="btn btn-primary" type="submit" hidden>Save</a>
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
      var export_url='';
      var ids = [];
      var selected_profiles = [];

      function getAmc(customerId = '', profile_status = '',phone_no='', from = '',refer_code='',app_version='',platform='', to = '', email = '', cnic = '',status='',risk_profile_status='') {
        var queryParams = '?profile_status='+profile_status+'&customerId='+customerId+'&phone_no='+phone_no+'&from='+from+'&refer_code='+refer_code+'&app_version='+app_version+'&platform='+platform+'&to='+to+'&email='+email+'&cnic='+cnic+'&status='+status+'&risk_profile_status='+risk_profile_status;
          var url = "{{route('request.getData')}}";

          table = $('.datatable').DataTable({

                  "language": {
                      "emptyTable": "No records available"
                  },
                    "lengthMenu": [ 10, 25, 50, 75, 100 ],
                  processing: true,
                  serverSide: true,
                  responsive: true,
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
                          name: 'id',
                          data: 'id',
                          render: function (data, type, row) {
                           return `<span><input class="form-check-input" type="checkbox" value="" id="checkbox"></span>`;
                          },
                          orderable: false,
                      },
                      {
                          name: 'full_name.toUpperCase()',
                          data: 'full_name.toUpperCase()',
                      },
                      {
                          name: 'email',
                          data: 'email',
                      },
                      {
                          name: 'cnic_number',
                          data: 'cnic_number',
                      },
                      {
                          name: 'registered_on',
                          data: 'registered_on',
                          
                      },
                      {
                          name: 'phone_no',
                          data: 'phone_no',
                          
                      },
                      {
                          name: 'app_version',
                          data: 'app_version',
                          
                      },
                      {
                          name: 'refer_code',
                        render: function (data, type, row) {
                              if(row.refer_code != null) {
                                return `<div class="p-2">${row.refer_code}</div>`;
                              } else {
                                return `<div class="p-2">-------</div>`;
                              }
                          },
                            orderable: false
                      },
                      {
                          name: 'platform',
                          data: 'platform',
                      },
                      {
                          name: 'comments',
                          data: 'comments',
                          render: function (data, type, row) {
                                return `<div class="btn-group dropdown" role="group"> 
                                <a class="btn btn-sm btn-light text-center show_comments_btn" type="button" target="_blank"><i class="fa fa-comments-o text-info fa-lg" title="View or Edit Comments"></i></a>
                                  </div>`;
                          },
                      },
                      {
                          name: 'Transaction History',
                          render: function (data, type, row) {
                            return `<a href="{{route('cust.transaction_history','/')}}/${row.id}"><i class="fas fa-history text-info fa-lg" title="View Transaction History"></i></a>`;
                          }
                      },
                      {
                          name: 'risk_profile_status',
                          data: 'risk_profile_status',
                          render: function (data, type, row) {
                              if(row.risk_profile_status == 0) {
                                return `<div class="badge badge-secondary p-2">Empty</div>`;
                              }
                              else if(row.risk_profile_status == 1) {
                                return `<div class="badge badge-primary p-2">In-Process</div>`;
                              }  
                              else if(row.risk_profile_status == 2) {
                                return `<div class="badge badge-success p-2">Approved</div><div class="btn-group dropdown" role="group"> 
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('risk_profile.details','/')}}/${row.id}" target="_blank"><i class="fas fa-eye  text-info fa-lg" title="View Risk Profile"></i></a>
                                  </div>`;
                              }else{
                                return `<div class="badge badge-danger p-2">Rejected</div><div class="btn-group dropdown" role="group"> 
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('risk_profile.details','/')}}/${row.id}" target="_blank"><i class="fa fa-eye  text-info fa-lg" title="View Risk Profile"></i></a>
                                  </div>`;
                              }
                          },
                      }
                      ,
                      {
                          name: 'cust_status',
                          data: 'cust_status',
                          render: function (data, type, row) {
                              if(row.cust_status == 0) {
                                return `<div class="badge badge-dark p-2">Pending</div>`;
                              }
                              else if(row.cust_status == 1) {
                                return `<div class="badge badge-success p-2">Approved</div>`;
                              }  
                              else if(row.cust_status == 2) {
                                return `<div class="badge badge-danger p-2">Rejected</div>`;
                              }else{
                                return `<div class="badge badge-warning p-2">On Hold</div>`;
                              }
                          },
                          orderable: false
                      },
                      {
                          name: 'status',
                          data: 'status',
                          render: function (data, type, row) {
                              if(row.status == 0) {
                                return `<div class="badge badge-danger p-2">In-Active</div>`
                              }else{
                                return `<div class="badge badge-success p-2">Active</div>`;
                              }
                          },
                          orderable: false
                      },
                      {
                          data: 'updated_at',
                          render: function (data, type, row) {
                            return `<div class="btn-group dropdown" role="group">
                                @can('view-customer-details')  
                                <a class="btn btn-sm btn-light text-center" type="button" href="{{route('cust.details','/')}}/${row.id}" target="_blank"><i class="fas fa-ellipsis-v  text-info fa-lg"></i></a>
                                @endcan
                                  </div>
                                  <div class="btn-group dropdown" role="group">
                              @can('edit-customer-status')
                              <a class="btn btn-sm btn-light text-center edit" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
                              @endcan
                                  </div>`;
                          },
                          searchable: false,
                          orderable: false
                      },
                  ],
                  select: true,
                  "order": [
                    [4, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
                
          });
          table.on('change', '#select-all-profiles', function (e) {
            e.preventDefault();
            if($(this).is(":checked"))
            {
              $('body #checkbox').prop('checked',true);
              $('body #checkbox').each(function(i){
                var data = table.row($(this).parents('tr')).data();
                if(data == null)
                data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
                if(!selected_profiles.includes(data.id))
                selected_profiles.push(data.id);
              });
            // if(selected_profiles!='')
            // $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
            $('body #checkbox').prop('checked',false);
            $('body #checkbox').each(function(i){
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(i-1)).data();
            let filtered_selected_elements = selected_profiles.filter(function(elem){
              return elem != data.id; 
            });
              selected_profiles=filtered_selected_elements;
            });
            // if(selected_profiles=='')
            // $('#export-selected-btn').attr('hidden',true);
            }
            });
          table.on('change', '#checkbox', function (e) {
            e.stopPropagation();
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            $(this).parents('tr').removeClass('dt-hasChild parent');
            $('.child').hide();
            if($(this).is(':checked'))
            {
            if(!selected_profiles.includes(data.id))
            selected_profiles.push(data.id);
            // if(selected_profiles!='')
            // $('#export-selected-btn').attr('hidden',false);
            }
            else
            {
              let filtered_selected_elements = selected_profiles.filter(function(elem){
                return elem != data.id; 
              });
              selected_profiles=filtered_selected_elements;
              // if(selected_profiles=='')
              // $('#export-selected-btn').attr('hidden',true);
            }
            console.log(selected_profiles);
          });
          table.on('click', '.edit', function () {
            var index=$(this).parents('tr').index();
            var data = table.row($(this).parents('tr')).data();
            if(data == null)
            data = table.row($(this).parents('tr').parents('tbody').children().eq(index-1)).data();
            console.log(data);
            $('#reqId').val(data.id);
            // $('#editAmcForm').find($('input[name="company_name"]')).val(data.company_name);
            // $('#editAmcForm').find($('select[name="category"]')).val(data.category);
            // $('#editAmcForm').find($('input[name="contact_no"]')).val(data.contact_no);
            // $('#editAmcForm').find($('input[name="contact_person_name"]')).val(data.contact_person_name);
            // $('#editAmcForm').find($('input[name="contact_person_role"]')).val(data.contact_person_role);
            // $('#editAmcForm').find($('input[name="secp_number"]')).val(data.secp_number);
            $('#editAmcForm').find($('select[name="status"]')).val(data.status);
            $('#editAmcForm').find($('select[name="profile_status"]')).val(data.cust_status);
            $('#editAmcForm').find($('select[name="risk_profile_status"]')).val(data.risk_profile_status);
            $('#editAmcModal').modal('show');
          });
          let new_comments_count=0;
          table.on('click', '.show_comments_btn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var data = table.row($(this).parents('tr')).data();
            var user_id=data.id;
            $('#user_id').val(user_id);
            $.ajax({
              url: "{{ url('/get/comments/') }}"+"/"+user_id,
              type: "GET",
              dataType:'JSON',
              contentType: false,
              cache: false,
              processData: false,
              success: function (data) {
                  if (data.length!=0) {
                      var html="";
                            html+=`<div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-12 col-md-4">
                                                <p class="font-weight-bold">
                                                    Name :
                                                </p>
                                                <p class="text-muted">${data[0].user.full_name}</p>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <p class="font-weight-bold">
                                                    CNIC :
                                                </p>
                                                <p class="text-muted">${data[0].user.cust_cnic_detail.cnic_number}</p>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <p class="font-weight-bold">
                                                    Email :
                                                </p>
                                                <p class="text-muted">${data[0].user.email}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                            html+=`<table class="mb-0 table datatable w-100" style="border: 1px solid black;">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Comment</th>
                              <th>Comment By</th>
                              <th>Comment Date</th>
                          </tr>
                      </thead>
                      <tbody>`;
                      $.each(data,function(index,value){
                        html+=`<tr>
                        <td>${index+1}</td>
                        <td>${value.comment}</td>
                        <td>${value.commented_by.full_name}</td>
                        <td>${value.created_at}</td>
                        </tr>`
                      });
                      html+=`</tbody></table>`;
                      $('#old_comments_div').html(html);
                      $('#comments_div').html('');
                      setTimeout(function(){ 
                        $('#editcommentsModal').modal('show');
                    }, 200);
                  }
                  else
                  {
                    $('#old_comments_div').html('');
                    $('#comments_div').html('');
                    setTimeout(function(){ 
                        $('#editcommentsModal').modal('show');
                    }, 200);
                  }
              },
            });
          });
          let count=$('#comments_div').children().length+1;
          $(document).on('click','.add_comment',function(event) {
            event.preventDefault();
            count=$('#comments_div').children().length+1;
            let option=`<div class="form-group row comment_`+count+`_div col-12">
                            <label for="option" class="col-2 col-form-label">Comment `+count+`</label>
                            <div class="col-8">
                            <textarea class="form-control" id="comment" placeholder="Enter Comment" name="comments[]" autocomplete="off"></textarea>
                            <div class="invalid-feedback error hide">
                            </div>
                            </div>
                            <div class="col-2">
                            <button class="btn btn-danger delete_btn" id="comment_`+count+`">Remove</button>
                            </div>
                        </div>`;
            $('#comments_div').append(option);
            $('#save_btn').removeAttr('hidden');
            count=$('#comments_div').children().length+1;
            new_comments_count++;
            console.log();
        });
        $(document).on('click','#save_btn',function(event) {
            event.preventDefault();
            var form = new FormData($('#admin_comments_form')[0]);
            $.ajax({
                url: "{{ route('save.admin.comments') }}",
                type: "POST",
                data:  form,
                // dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                enctype: 'multipart/form-data',
                success: function (data) {
                if (!data.error) {
                    Toast.fire({
                    icon: 'success',
                    title: data.message
                    });
                    $('#editcommentsModal').modal('hide');
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
        $(document).on('click','.delete_btn',function(event) {
                event.preventDefault();
                let id=$(this).attr('id');
                $('.'+id+'_div').remove();
                count=$('#comments_div').children().length+1;
                new_comments_count--;
                if(new_comments_count==0)
                $('#save_btn').attr('hidden',true);
                else
                $('#save_btn').removeAttr('hidden');
            });
          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#reqId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
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

          var id = $('#reqId').val();
          var form = new FormData($('#editAmcForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('request.update', '')}}" + "/" + id;
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
        var email = $('#filter-form').find($('input[name="filter-customer-email"]')).val();
        var cnic = $('#filter-form').find($('input[name="filter-customer-cnic"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var profile_status = $('#filter-form').find($('select[name="filter-status"]')).val();
        var phone_no = $('#filter-form').find($('input[name="phone_no"]')).val();
        var user_status = $('#filter-form').find($('select[name="filter-user-status"]')).val();
        var refer_code = $('#filter-form').find($('input[name="filter-refer-code"]')).val();
        var app_version = $('#filter-form').find($('input[name="filter-app-version"]')).val();
        var platform = $('#filter-form').find($('select[name="filter-platform"]')).val();
        var risk_profile_status=$('#filter-form').find($('select[name="filter-risk-status"]')).val();
        // console.log('status', status);
        profile_status = profile_status != null ? profile_status : '';
        risk_profile_status = risk_profile_status != null ? risk_profile_status : '';
        getAmc(customerId ,profile_status,phone_no, from,refer_code,app_version,platform, to,email,cnic,user_status,risk_profile_status);
        export_url='?profile_status='+profile_status+'&customerId='+customerId+'&phone_no='+phone_no+'&from='+from+'&refer_code='+refer_code+'&app_version='+app_version+'&platform='+platform+'&to='+to+'&email='+email+'&cnic='+cnic+'&status='+status+'&risk_profile_status='+risk_profile_status;
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        $("#filter-user-id").val('').trigger('change');
        clearDatatable();
        getAmc();
    });
    $('#export-btn').click(function (e) {
        e.preventDefault();
        // if(export_url=="")
        // {
        //     export_url='?&customerName=&status=&fund=&refer_code=&cnic=&approvedDateFrom=&approvedDateTo=&from=&to=&amount=&unit=&nav=&amc=&reference=&verified=';
        // }
        if(selected_profiles!='')
        {
          $.ajax({
          url: "{{ route('cust.export') }}",
          type: "POST",
          data: {
                'selected_profiles':selected_profiles,
                },
          success: function (data) {
            console.log("data", data);
            if (!data.error && data.user_csv) {
              for (const index in data.user_csv) {
              var Element = document.createElement('a');  
                      Element.href = 'data:text/csv;charset=utf-8,' + encodeURI(data.user_csv[index]);  
                      Element.target = '_blank';  
                      Element.download = index; 
                      Element.click(); 
              }
            }else{
            }
          },
          error: function (data) {
            Toast.fire({
                          icon: 'error',
                          title: 'Oops Something Went Wrong!',
                        });      

          }
        });
       }
       else
       {
        Swal.fire(
              'Alert',
              "Please Select Atleast 1 Transaction",
              "error"
          )
       }
    });
        function customerFilterDropDown() {

    $('#filter-user-id').select2({
        width: '100%',
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
      getAmc();
      customerFilterDropDown();
    });
  </script>
@endpush
