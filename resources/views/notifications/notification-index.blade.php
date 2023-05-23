@extends('layouts.app')

<style>
    .msg-column {
        word-wrap: break-word;
        word-break: break-all;
        width: 50%;

    }
</style>


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Notifications</h4>
          <p>
              <a>Marketing</a> /
              <span>Notifications</span>
          </p>
      </div>
      <a href="{{ route('notification.history') }}"  class="btn btn-primary">
          <i class="fas fa-history"></i>
          Notifications History
      </a>
      <!-- <a href="{{ route('notification.create') }}"  class="btn btn-primary">
          <i class="fas fa-plus mr-2"></i>
          Send Notification
      </a> -->
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
                            <label for="cohort" class="form-label">Cohort</label>
                            <select class="mb-2 form-control" id="filter-cohort" name="filter-cohort" autocomplete="off">
                              <option selected disabled>Select Cohort</option>
                              <option value="all">All Users</option>
                              <option value="-1">Sign Ups</option>
                              <option value="0">Profile Pending</option>
                          </select>
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="from" class="form-label">From</label>
                          <input placeholder="From" type="datetime-local" class="form-control " id="from" name="filter-from-date">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="to" class="form-label">To</label>
                          <input placeholder="To" type="datetime-local" class="form-control " id="to" name="filter-to-date">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Platform (IOS/Android)</label>
                            <select class="mb-2 form-control" name="filter-platform" autocomplete="off">
                              <option value="" selected disabled>Select Platform</option>
                              <option value="android">Android</option>
                              <option value="ios">Ios</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">App Version</label>
                            <input placeholder="App Version" type="text" name="filter-app-version" class="form-control " id="inputEmail4">
                        </div>
                          <div class="col-12 col-md-3 profile_div">
                              <label for="gender" class="form-label">Gender*</label>
                              <select class="mb-2 form-control" name="filter-gender" autocomplete="off">
                                <option value="" selected disabled>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                                <option value="I prefer not to disclose">I prefer not to disclose</option>
                              </select>
                          </div>
                          <div class="col-12 col-md-3 profile_div">
                              <label for="age" class="form-label">Age*</label>
                              <select class="mb-2 form-control" name="filter-age" autocomplete="off">
                                <option value="" selected disabled>Select Age Range</option>
                                <option value="18-23">18-23</option>
                                <option value="24-35">24-35</option>
                                <option value="35+">35+</option>
                              </select>
                          </div>
                          <div class="col-12 col-md-3 profile_div">
                              <label for="source_of_income" class="form-label">Source of Income*</label>
                              <select class="mb-2 form-control" id="source_of_income" name="filter-source_of_income">
                                <option value="" selected disabled>Select Source of Income</option>
                                @foreach($income_sources as $source)
                                  <option value="{{$source->id}}">{{$source->income_name}}</option>
                                @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="row g-3 profile_div">
                          <div class="col-12 col-md-3">
                              <label for="filter-citizen-status" class="form-label">Citizenship Status*</label>
                              <select class="mb-2 form-control" name="filter-citizen-status" autocomplete="off">
                              <option selected disabled>Select Citizenship Status</option>
                                @foreach($citizenship_statuses as $citizenship_status)
                                 <option value="{{$citizenship_status->id}}">{{$citizenship_status->status}}</option>
                                @endforeach
                              </select>
                          </div>
                          <div class="col-12 col-md-3">
                              <label for="city" class="form-label">City*</label>
                              <select class="mb-2 form-control" id="city" name="filter-city">
                                <option value="" selected disabled>Select City</option>
                                @foreach($cities as $city)
                                  <option value="{{$city->id}}">{{$city->city}}</option>
                                @endforeach
                              </select>
                          </div>
                      </div>
                    </div>
                        <div class="col-12 text-right mt-2">
                            <button type="button" class="btn btn-danger btn-sm btnResetFilter mb-1">Reset</button>
                            <button type="submit" class="btn btn-primary btn-sm btnSubmitFilter mb-1">Search</button>
                        </div>
                      </form>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="col-lg-12 m-2 d-flex flex-row-reverse">
        <a href="#"  class="btn btn-primary mr-1" id="export-btn">
            <i class="fas fa-file-csv mr-2"></i>
            Export CSV
        </a>
        <a  class="btn btn-primary mr-1" id="send_notification">
            <i class="fas fa-plus mr-2"></i>
            Send Notification
        </a>
        <a id="send_sms"  class="btn btn-primary mr-1">
            <i class="fas fa-sms mr-2"></i>
            Send SMS
        </a>
        <a id="send_otp"  class="btn btn-primary mr-1">
            <i class="fas fa-sms mr-2"></i>
            Send OTP to User
        </a>
      </div>
      <div class="col-lg-12">
          <div class="main-card mb-3 card">
              <div class="card-body">
                  <table class="mb-0 table datatable">
                      <thead>
                          <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Number</th>
                              <th>Registration Date</th>
                              <th>App Version</th>
                              <th>Platform</th>
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

@section('modal')
<div class="modal fade" id="sendNotificationModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Send Notification</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="sendNotificationForm" enctype="multipart/form-data">
              <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Title*</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control title" id="title" placeholder="Enter title" name="title" autocomplete="off" >
                        <div class="invalid-feedback error hide ">
                        </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Image*</label>
                    <div class="col-sm-10">
                    <input type='file' name="image" class="form-control image h-auto" id="image" autocomplete="off">
                        <div class="invalid-feedback error hide ">
                        </div>
                    </div>
              </div>  
              <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Message*</label>
                    <div class="col-sm-10">
                    <textarea  class="form-control message" id="message" placeholder="Enter Message" name="message" rows="4" cols="50"></textarea>
                        <div class="invalid-feedback error hide ">
                        </div>
                    </div>
              </div>  
          </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="sendBtn">Send Notification</button>
                </div>
              </form>
      </div>
  </div>
</div>
<div class="modal fade" id="sendsmsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Send SMS</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="sendsmsForm" enctype="multipart/form-data">
              <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Message*</label>
                    <div class="col-sm-10">
                    <textarea  class="form-control message" id="message" placeholder="Enter Message" name="message" rows="4" cols="50"></textarea>
                        <div class="invalid-feedback error hide ">
                        </div>
                    </div>
              </div>  
          </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="sendBtn">Send SMS</button>
                </div>
              </form>
      </div>
  </div>
</div>
<div class="modal fade" id="sendotpModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Send OTP</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="sendotpForm" enctype="multipart/form-data">
              <div class="form-group row">
                    <label for="phone_number" class="col-sm-2 col-form-label">Phone Number*</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control phone_number" id="phone_number" placeholder="Enter Phone Number" name="phone_number" autocomplete="off" >
                        <div class="invalid-feedback error hide ">
                        </div>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">OTP*</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control otp" id="otp" placeholder="Enter OTP" name="otp" autocomplete="off" >
                        <div class="invalid-feedback error hide ">
                        </div>
                    </div>
              </div>  
          </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="sendBtn">Send OTP</button>
                </div>
              </form>
      </div>
  </div>
</div>
@endsection


@push('scripts')
  <script>

    $(function () {
      var image_file='';
      $.fn.dataTable.ext.errMode = 'none';
      $('#send_notification').click(function (e) {
        e.preventDefault();
        $('#sendNotificationModal').modal('show');
      });
      $('#send_otp').click(function (e) {
        e.preventDefault();
        $('#sendotpModal').modal('show');
      });
      $('#send_sms').click(function (e) {
        e.preventDefault();
        $('#sendsmsModal').modal('show');
      });
      const input = document.getElementById("image");

      const convertBase64 = (file) => {
          return new Promise((resolve, reject) => {
              const fileReader = new FileReader();
              fileReader.readAsDataURL(file);

              fileReader.onload = () => {
                  resolve(fileReader.result);
              };

              fileReader.onerror = (error) => {
                  reject(error);
              };
          });
      };

      const uploadImage = async (event) => {
          const file = event.target.files[0];
          const base64 = await convertBase64(file);
          image_file = base64;
          console.log(image_file);
      };

      input.addEventListener("change", (e) => {
          uploadImage(e);
      });
      $('#sendNotificationForm').submit(function(event) {
      event.preventDefault();
      var cohort = $('#filter-form').find($('select[name="filter-cohort"]')).val();
      var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
      var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
      var platform = $('#filter-form').find($('select[name="filter-platform"]')).val();
      var app_version = $('#filter-form').find($('input[name="filter-app-version"]')).val();
      var gender = $('#filter-form').find($('select[name="filter-gender"]')).val();
      var age = $('#filter-form').find($('select[name="filter-age"]')).val();
      var source_of_income = $('#filter-form').find($('select[name="filter-source_of_income"]')).val();
      var city = $('#filter-form').find($('select[name="filter-city"]')).val();
      var citizenship_status=$('#filter-form').find($('select[name="filter-citizen-status"]')).val();
      var title=$('#title').val();
      var image=$('#image');
      var message=$('#sendNotificationForm').find($('textarea[name="message"]')).val();
      var data={
        'cohort':cohort,
        'from':from,
        'to':to,
        'platform':platform,
        'app_version':app_version,
        'gender':gender,
        'age':age,
        'source_of_income':source_of_income,
        'city':city,
        'citizenship_status':citizenship_status,
        'title':title,
        'image':image_file,
        'message':message,
      };
      $.ajax({
        url: "{{ route('send.notification') }}",
        type: "POST",
        data: data,
        dataType: 'json',
        success: function (data) {
          console.log("data", data);
          if (!data.error) {
            Toast.fire({
              icon: 'success',
              title: data.message
            });
            $('#sendNotificationForm').trigger("reset");
            $('#sendNotificationModal').modal('hide');
          }else{
            handleValidationErrors($('#sendNotificationForm'),data.error);
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
      $('#sendotpForm').submit(function(event) {
          event.preventDefault();
          var otp=$('#sendotpForm').find($('input[name="otp"]')).val();
          var phone=$('#sendotpForm').find($('input[name="phone_number"]')).val();
          var data={
          'otp':otp,
          'phone_number':phone,
          };
          $.ajax({
            url: "{{ route('send.otp') }}",
            type: "POST",
            data: data,
            dataType: 'json',
            success: function (data) {
              console.log("data", data);
              if (!data.error) {
                Toast.fire({
                  icon: 'success',
                  title: data.message
                });
                $('#sendotpForm').trigger("reset");
                $('#sendotpModal').modal('hide');
              }else{
                handleValidationErrors($('#sendotpForm'),data.error);
              }
            },
            error: function (data) {
              Toast.fire({
                            icon: 'error',
                            title: data.error,
                          });      

            }
          });
      });
      $('#sendsmsForm').submit(function(event) {
      event.preventDefault();
      var cohort = $('#filter-form').find($('select[name="filter-cohort"]')).val();
      var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
      var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
      var platform = $('#filter-form').find($('select[name="filter-platform"]')).val();
      var app_version = $('#filter-form').find($('input[name="filter-app-version"]')).val();
      var gender = $('#filter-form').find($('select[name="filter-gender"]')).val();
      var age = $('#filter-form').find($('select[name="filter-age"]')).val();
      var source_of_income = $('#filter-form').find($('select[name="filter-source_of_income"]')).val();
      var city = $('#filter-form').find($('select[name="filter-city"]')).val();
      var citizenship_status=$('#filter-form').find($('select[name="filter-citizen-status"]')).val();
      var message=$('#sendsmsForm').find($('textarea[name="message"]')).val();
      var data={
        'cohort':cohort,
        'from':from,
        'to':to,
        'platform':platform,
        'app_version':app_version,
        'gender':gender,
        'age':age,
        'source_of_income':source_of_income,
        'city':city,
        'citizenship_status':citizenship_status,
        'message':message,
      };
      $.ajax({
        url: "{{ route('send.sms') }}",
        type: "POST",
        data: data,
        dataType: 'json',
        success: function (data) {
          console.log("data", data);
          if (!data.error) {
            Toast.fire({
              icon: 'success',
              title: data.message
            });
            $('#sendsmsForm').trigger("reset");
            $('#sendsmsModal').modal('hide');
          }else{
            handleValidationErrors($('#sendsmsForm'),data.error);
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
      $('.datatable').click(function (e) {
        e.stopPropagation();
        });
      $('.profile_div').hide();
      var table;
      var export_url='';
      var ids = [];

      function getUsers(cohort = '', from = '', to = '',platform = '',app_version='',gender = '', age = '',source_of_income = '',citizenship_status = '',city = '') {

        var queryParams = '?cohort='+cohort+'&from='+from+'&to='+to+'&platform='+platform+'&app_version='+app_version+'&gender='+gender+'&age='+age+'&source_of_income='+source_of_income+'&citizenship_status='+citizenship_status+'&city='+city;
          var url = "{{route('filter_user.getData')}}";

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
                          data: 'full_name',
                          name: 'full_name',
                      },
                      {
                          name: 'email',
                          data: 'email',
                      },
                      {
                          name: 'phone_no',
                          data: 'phone_no',
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
                          name: 'app_version',
                          data: 'app_version'
                      },
                      {
                          name: 'platform',
                          data: 'platform'
                      },
                  ],
                  
                  select: true,
                  "order": [
                  [3, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
      }


      function handleValidationErrors(element, error) {

          let titleInput = element.find($('input[name="title"]'));
          if(error.title) {
            titleInput.addClass('is-invalid');
            titleInput.next('.error').html(error.title);
            titleInput.next('.error').removeClass('hide').addClass('show');
          } else {
            titleInput.removeClass('is-invalid').addClass('is-valid');
            titleInput.next('.error').html('');
            titleInput.next('.error').removeClass('show').addClass('hide');
          }

          let messageInput = element.find($('textarea[name="message"]'));
          if(error.message) {
            messageInput.addClass('is-invalid');
            messageInput.next('.error').html(error.message);
            messageInput.next('.error').removeClass('hide').addClass('show');
          } else {
            messageInput.removeClass('is-invalid').addClass('is-valid');
            messageInput.next('.error').html('');
            messageInput.next('.error').removeClass('show').addClass('hide');
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
          element.find($('textarea')).each(function(index, el) {
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
      $('#filter-cohort').change(function(){
        if($(this).val()==0)
          $('.profile_div').show();
        else
          $('.profile_div').hide();
      });
    $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var cohort = $('#filter-form').find($('select[name="filter-cohort"]')).val();
        // if(cohort==0)
        //   $('.profile_div').show();
        // else
        //   $('.profile_div').hide();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var platform = $('#filter-form').find($('select[name="filter-platform"]')).val();
        var app_version = $('#filter-form').find($('input[name="filter-app-version"]')).val();
        var gender = $('#filter-form').find($('select[name="filter-gender"]')).val();
        var age = $('#filter-form').find($('select[name="filter-age"]')).val();
        var source_of_income = $('#filter-form').find($('select[name="filter-source_of_income"]')).val();
        var city = $('#filter-form').find($('select[name="filter-city"]')).val();
        var citizenship_status=$('#filter-form').find($('select[name="filter-citizen-status"]')).val();
        getUsers(cohort, from, to,platform,app_version,gender,age,source_of_income,citizenship_status,city);
        export_url='?cohort='+cohort+'&from='+from+'&to='+to+'&platform='+platform+'&app_version='+app_version+'&gender='+gender+'&age='+age+'&source_of_income='+source_of_income+'&citizenship_status='+citizenship_status+'&city='+city;
    });
    $('#export-btn').click(function (e) {
        e.preventDefault();
        if(export_url=="")
        {
            export_url='?cohort=&from=&to=&platform=&app_version=&gender=&age=&source_of_income=&citizenship_status=&city=';
        }
        $.ajax({
        url: "{{ route('filter_user.export') }}/"+export_url,
        type: "GET",
        // dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        enctype: 'multipart/form-data',
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
    });
    
    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getUsers();
    });
      
    getUsers();

    });
  </script>
@endpush
