@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Videos</h4>
          <p>
              <!-- <a>Dashboard</a> / -->
              <span>Marketing</span> /
              <span>Videos</span>
          </p>
      </div>  
      <div class="d-flex flex-row">    
          <div class="mr-2">
              @can('add-video')
                  
              <a href="#"  class="btn btn-primary addVideo" data-toggle="modal" data-target="#addVideoModal" data-backdrop="true">
                  <i class="fas fa-plus"></i>
                  Add Video
                </a>
                @endcan
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
                          <label for="inputEmail4" class="form-label">Title</label>
                          <input placeholder="Title" type="text" name="filter-title" class="form-control " id="inputEmail4">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="inputEmail4" class="form-label">Tag</label>
                            <input placeholder="Tag" type="text" name="filter-tag" class="form-control " id="inputEmail4">
                          </div>
                        <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">From</label>
                          <input placeholder="From" type="text" class="datepicker-from form-control " id="inputEmail4" name="filter-from-date">
                        </div>
                        <div class="col-12 col-md-3">
                          <label for="inputEmail4" class="form-label">To</label>
                          <input placeholder="To" type="text" class="datepicker-to form-control " id="inputEmail4" name="filter-to-date">
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
                              <th>Order No</th>
                              <th>Title</th>
                              <th>URL</th>
                              <th>Tags</th>
                              <th>Thumbnail</th>
                              <th>Created At</th>
                              <th>Status</th>
                              <th>On App Dashboard</th>
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
<input type="hidden" id="insightId" name="id">
@endsection

@section('modal')

<!-- Video Add Modal -->
<div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addVideoForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="Title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control title" id="title" placeholder="Title" name="title" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                          <label for="Title" class="col-sm-2 col-form-label">Order No</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control title" id="order_no" placeholder="Order" name="order_no" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                    <div class="form-group row">
                        <label for="video_thumbnail" class="col-sm-2 col-form-label">Video Thumbnail</label>
                        <div class="col-sm-10">
                          <input type='file' class="form-control video_thumbnail h-auto" id="video_thumbnail" name="video_thumbnail" />
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                  
                    <div class="form-group row">
                        <label for="Complaint Email" class="col-sm-2 col-form-label">Tags</label>
                        <div class="col-sm-10">
                          <select class="mb-2 form-control tag" id="tag" name="tag" autocomplete="off">
                            <option selected disabled>Select Tag</option>
                          </select>
                          <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="URL" class="col-sm-2 col-form-label">Youtube Video ID</label>
                        <div class="col-sm-10">
                          <input type='text' class="form-control url" id="url" name="url" placeholder="Youtube Video ID" />
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
                    <div class="form-group row" style="{{  $is_allowed <= 5 ? 'display:block;':'display:none;' }}">
                        <div class="col-lg-12 mt-2 mb-4">
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_allowed" value="1">
                            <label class="form-check-label" for="exampleCheck1">Show on app dashboard</label>
                             </div>
                        </div>
                  </div>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBtnVideo">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Video Add Modal -->

<!-- Video Edit Modal -->
<div class="modal fade" id="editVideoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editVideoForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    {{-- <div class="form-group row">
                          <label for="Category" class="col-sm-2 col-form-label">Category</label>
                          <div class="col-sm-10">
                            <select class="mb-2 form-control category" id="category" name="category" autocomplete="off">
                              <option selected disabled>Select Category</option>
                            </select>
                            <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div> --}}
                      <div class="form-group row">
                          <label for="Title" class="col-sm-2 col-form-label">Title</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control title" id="title" placeholder="Title" name="title" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="video_thumbnail" class="col-sm-2 col-form-label">Video Thumbnail</label>
                        <div class="col-sm-10">
                          <input type='file' class="form-control video_thumbnail h-auto" id="video_thumbnail" name="video_thumbnail" />
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                          <label for="Title" class="col-sm-2 col-form-label">Order No</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control title" id="order_no" placeholder="Order" name="order_no" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="Complaint Email" class="col-sm-2 col-form-label">Tags</label>
                          <div class="col-sm-10">
                            <select class="mb-2 form-control tag" id="tag" name="tag" autocomplete="off">
                              <option selected disabled>Select Tag</option>
                            </select>
                            <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="URL" class="col-sm-2 col-form-label">Youtube Video Id</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control url" id="url" name="url" placeholder="Youtube Video ID" />
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
                      <div class="form-group row video-allowed">
                        <div class="col-lg-12 mt-2 mb-4">
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_allowed" value="1">
                            <label class="form-check-label" for="exampleCheck1">Show on app dashboard</label>
                             </div>
                        </div>
                  </div>
              </div>
              </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtnVideo">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Video Edit Modal -->
<input type="hidden" name="popular" id="popular" value="{{ $is_allowed }}">
@endsection

@push('scripts')
  <script>
    $(function () {
    $.fn.dataTable.ext.errMode = 'none';
    $('.datatable').click(function (e) {
    e.stopPropagation();
    });
    var isAllowedCount = $('#popular').val();
    // var id = $('#insightId').val();
    var isAllowedChecked = $('input[name=is_allowed]:checked').length;

      var table;
      var ids = [];

      function getInsight(title = '', status = '', from = '', to = '',tag = '') {

        var queryParams = '?status='+status+'&title='+title+'&from='+from+'&to='+to+'&tag='+tag;
         
          var url = "{{route('insight.video.getData')}}";

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
                          name: 'order_no',
                          data: 'order_no',
                          
                      },
                      {
                          name: 'title',
                          data: 'title',
                          
                      },
                      {
                          name:'url',
                          data:'url'
                          
                      },
                      {
                          name:'insight_tag.name',
                          data:'insight_tag.name'
                          
                      },
                      {
                          name:'video_thumbnail',
                          render: function (data, type, row) {
                              if(row.logo == null) {
                                  return `<div class="p-2">------</div>`;
                            } else {
                                  return `<div p-2"><a href="${row.logo.startsWith('http')?row.logo:"{{env('S3_BUCKET_URL')}}"+row.logo}" download>Download</a></div>`;
                              }
                          },
                          
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
                          name: 'status',
                          render: function (data, type, row) {
                              console.log(row);
                              if(row.status == 1) {
                                  return `<div class="badge badge-success p-2">Active</div>`;
                              } else {
                                  return `<div class="badge badge-dark p-2">In-Active</div>`;
                              }
                          },
                      },
                      {
                          name: 'is_allowed',
                          render: function (data, type, row) {
                              console.log(row);
                              if(row.is_allowed == 1) {
                                  return `<div class="badge badge-success p-2">Yes</div>`;
                              } else {
                                  return `<div class="badge badge-dark p-2">No</div>`;
                              }
                          },
                          orderable: false
                      },
                      {
                          data: 'action',
                          render: function (data, type, row) {
                            if(row.type == 1){
                                return `<div class="btn-group dropdown" role="group">
                                    @can('edit-video')
                                    <a class="btn btn-sm btn-light text-center editVideo" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
                                    @endcan
                                  </div>`;
                            }
                            
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

          

          table.on('click', '.editVideo', function () {
            var data = table.row($(this).parents('tr')).data();
            
             $('#insightId').val(data.id);
            $('#editVideoForm').find($('select[name="category"]')).val(data.category_id);
            $('#editVideoForm').find($('input[name="title"]')).val(data.title);
            $('#editVideoForm').find($('input[name="text"]')).val(data.text);
            $('#editVideoForm').find($('select[name="tag"]')).val(data.tag_id);
            $('#editVideoForm').find($('input[name="url"]')).val(data.url);
            $('#editVideoForm').find($('select[name="status"]')).val(data.status);
            $('#editVideoForm').find($('input[name="order_no"]')).val(data.order_no);
             if(data.is_allowed == 1){
             $('#editVideoForm').find($('input[name="is_allowed"]')).prop('checked',true);
             }else if(data.is_allowed == 0){
                $('#editVideoForm').find($('input[name="is_allowed"]')).prop('checked',false);
             }

             if(isAllowedCount >= 6){
                if(data.is_allowed == 1){
                    
                    $('.video-allowed').show();
                }else{
                    $('.video-allowed').hide();
                    
                }
                 
            }else if(isAllowedCount <= 6){
                if(data.is_allowed == 0){
                    
                    $('.video-allowed').show();
                }else{
                    $('.video-allowed').show();
                    
                }
            }
        
            $('#editVideoModal').modal('show');
           
          });

          table.on('click', '.delete', function () {
              var data = table.row($(this).parents('tr')).data();
              $('#insightId').val(data.id);
              $('#deleteDepartmentModal').modal('show');
          });
      }


      function handleValidationErrors(element, error) {
        //   let category = element.find($('select[name="category"]'));
        //   if(error.category) {
        //       category.addClass('is-invalid');
        //       category.next('.error').html(error.category);
        //       category.next('.error').removeClass('hide').addClass('show');
        //   } else {
        //       category.removeClass('is-invalid').addClass('is-valid');
        //       category.next('.error').html('');
        //       category.next('.error').removeClass('show').addClass('hide');
        //   }
          let title = element.find($('input[name="title"]'));
          if(error.title) {
              title.addClass('is-invalid');
              title.next('.error').html(error.title);
              title.next('.error').removeClass('hide').addClass('show');
          } else {
              title.removeClass('is-invalid').addClass('is-valid');
              title.next('.error').html('');
              title.next('.error').removeClass('show').addClass('hide');
          }
          let video_thumbnail = element.find($('input[name="video_thumbnail"]'));
          if(error.video_thumbnail) {
            video_thumbnail.addClass('is-invalid');
            video_thumbnail.next('.error').html(error.video_thumbnail);
            video_thumbnail.next('.error').removeClass('hide').addClass('show');
          } else {
            video_thumbnail.removeClass('is-invalid').addClass('is-valid');
            video_thumbnail.next('.error').html('');
            video_thumbnail.next('.error').removeClass('show').addClass('hide');
          }
          let text = element.find($('textarea[name="text"]'));
          if(error.text) {
              text.addClass('is-invalid');
              text.next('.error').html(error.text);
              text.next('.error').removeClass('hide').addClass('show');
          } else {
              text.removeClass('is-invalid').addClass('is-valid');
              text.next('.error').html('');
              text.next('.error').removeClass('show').addClass('hide');
          }
          let tag = element.find($('select[name="tag"]'));
          if(error.tag) {
              tag.addClass('is-invalid');
              tag.next('.error').html(error.tag);
              tag.next('.error').removeClass('hide').addClass('show');
          } else {
              tag.removeClass('is-invalid').addClass('is-valid');
              tag.next('.error').html('');
              tag.next('.error').removeClass('show').addClass('hide');
          }
        //   let url = element.find($('input[name="url"]'));
        //   if(error.url) {
        //       url.addClass('is-invalid');
        //       url.next('.error').html(error.url);
        //       url.next('.error').removeClass('hide').addClass('show');
        //   } else {
        //       url.removeClass('is-invalid').addClass('is-valid');
        //       url.next('.error').html('');
        //       url.next('.error').removeClass('show').addClass('hide');
        //   }
          let author_name = element.find($('input[name="author_name"]'));
          if(error.author_name) {
            author_name.addClass('is-invalid');
            author_name.next('.error').html(error.author_name);
            author_name.next('.error').removeClass('hide').addClass('show');
          } else {
            author_name.removeClass('is-invalid').addClass('is-valid');
            author_name.next('.error').html('');
            author_name.next('.error').removeClass('show').addClass('hide');
          }
          let reading_time = element.find($('input[name="reading_time"]'));
          if(error.reading_time) {
            reading_time.addClass('is-invalid');
            reading_time.next('.error').html(error.reading_time);
            reading_time.next('.error').removeClass('hide').addClass('show');
          } else {
            reading_time.removeClass('is-invalid').addClass('is-valid');
            reading_time.next('.error').html('');
            reading_time.next('.error').removeClass('show').addClass('hide');
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

      $(" #addVideoModal, #editVideoModal #deleteDepartmentModal").on("hidden.bs.modal", function () {
          $('#addVideoForm').trigger("reset");
          
          $('#editVideoForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addVideoForm'))
          resetValidationErrors($('#editVideoForm'))
      });


      $('#saveBtnVideo').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addVideoForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('saveVideo') }}",
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
                      $('#addVideoModal').modal('hide');
                      $('#addVideoForm').trigger("reset");
                      location.reload();
                      table.draw();
                  } else {
                   console.log('error',data.error);
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                      });
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addVideoForm'), data.error)
                      
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

     

      $('#editBtnVideo').click(function (e) {
        
                var id = $('#insightId').val();
                var form = new FormData($('#editVideoForm')[0]);
                form.append('_method', 'PUT');
                var url = "{{route('updateVideo', '')}}" + "/" + id;
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
                            $('#editVideoModal').modal('hide');
                            $('#editVideoForm').trigger("reset");
                            location.reload();
                            table.draw();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                            //validation errors
                            handleValidationErrors($('#editVideoForm'), data.error)
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


      $('#deleteBtn').click(function (e) {
          e.preventDefault();
          var id = $('#insightId').val();
          var url = "{{route('insight.destroy', '')}}" + "/" + id;

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

      function getFields(){
        $.ajax({
          url: "{{route('insight.getFields')}}",
          type: "GET",
          dataType: 'json',
          success: function (data) {
            if (data && data.insight_category) {
              $.map(data.insight_category,function(elem){
                $('<option/>')
                .val(elem.id)
                .text(elem.name)
                .appendTo('.category')
              })
            }
            if (data && data.insight_tag) {
              $.map(data.insight_tag,function(elem){
                $('<option/>')
                .val(elem.id)
                .text(elem.name)
                .appendTo('.tag')
              })
            }
          },
          error: function (data) {
            console.log("data", data);
          }
        });
      }
      getFields()

      $('.btnSubmitFilter').on('click', function(e) {
        e.preventDefault();
        clearDatatable();
        var title = $('#filter-form').find($('input[name="filter-title"]')).val();
        var tag = $('#filter-form').find($('input[name="filter-tag"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        // console.log('status', status);
        status = status != null ? status : '';
        getInsight(title, status, from, to,tag);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getInsight();
    });
      getInsight();

    });
  </script>
@endpush
