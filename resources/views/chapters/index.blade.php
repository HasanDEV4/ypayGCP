@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Academy Chapters</h4>
          <p>
              <a>Administration</a> /
              <span>Academy Chapters</span>
          </p>
      </div>
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addchapterModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>

        Add Academy Chapter
      </a>
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
                        <label for="chapter" class="form-label">Chapter Name</label>
                        <input placeholder="Chapter Name" type="text" name="filter-chapter-name" class="form-control " id="filter-chapter-name">
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
                            <th>Chapter Name</th>
                            <th>Download Image</th>
                            <th>Creation Date</th>
                            <th>Action</th>
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
<input type="hidden" id="chapterId" name="id">
@endsection

@section('modal')
<div class="modal fade" id="addchapterModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Chapter</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addchapterForm" onsubmit="event.preventDefault()">
                  <div class="form-group row">
                      <label for="chapter_name" class="col-sm-2 col-form-label">Chapter Name</label>
                      <div class="col-sm-10">
                        <input placeholder="Chapter Name" type="text" name="chapter_name" class="form-control chapter_name" id="chapter_name">
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="chapter_image" class="col-sm-2 col-form-label">Chapter Image</label>
                      <div class="col-sm-10">
                        <input type="file" name="chapter_image" class="form-control chapter_image" id="chapter_image">
                        <div class="invalid-feedback error hide ">
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
<div class="modal fade" id="editchapterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Chapter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editchapterForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                      <label for="chapter_name" class="col-sm-2 col-form-label">Chapter Name</label>
                      <div class="col-sm-10">
                        <input placeholder="Chapter Name" type="text" name="chapter_name" class="form-control chapter_name" id="chapter_name">
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="chapter_image" class="col-sm-2 col-form-label">Chapter Image</label>
                      <div class="col-sm-10">
                        <input type="file" name="chapter_image" class="form-control chapter_image" id="chapter_image">
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                <input type="hidden" id="chapter_id" name="chapter_id">
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
      $(":input").inputmask();
      $.fn.dataTable.ext.errMode = 'none';
      $('.datatable').click(function (e) {
        e.stopPropagation();
      });
      var table;
      function getchapters(chapterName= '') {

        var queryParams = '?&chapterName='+chapterName;
          var url = "{{route('chapters.getData')}}";

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
                columns:[
                  {
                          name: 'name',
                          data: 'name',
                          orderable: false,
                  },
                  {
                      name: 'image',
                      render: function (data, type, row) {
                          if(row.image!=null) {
                              return `<div><a class="btn btn-sm btn-light text-center" type="button" href="${row.image.startsWith('http')?row.image:"{{env('S3_BUCKET_URL')}}"+row.image}" download><i class="fa fa-download  text-info fa-lg" title="Download Chapter Image"></i></a></div>`;
                          } else {
                              return `<div">------</div>`;
                          }
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
                          data: 'action',
                          render: function (data, type, row) {
                            return `
                            <div class="btn-group dropdown" role="group">
                                <a class="btn btn-sm btn-light text-center edit" type="button" target="_blank"><i class="fas fa-edit text-info fa-lg"></i></a>
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
            var current_row = $(this).parents('tr');//Get the current row
            if (current_row.hasClass('child')) {//Check if the current row is a child row
                current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
            }
            var data = table.row(current_row).data();
            $('#chapter_id').val(data.id);
            $('#editchapterForm').find($('input[name="chapter_name"]')).val(data.name);
            $('#editchapterModal').modal('show');
          });

          }


      function handleValidationErrors(element, error) {
         
          let chapter_name = element.find($('input[name="chapter_name"]'));
          if(error.chapter_name) {
            chapter_name.addClass('is-invalid');
            chapter_name.next('.error').html(error.chapter_name);
            chapter_name.next('.error').removeClass('hide').addClass('show');
          }  else {
            chapter_name.removeClass('is-invalid').addClass('is-valid');
            chapter_name.next('.error').html('');
            chapter_name.next('.error').removeClass('show').addClass('hide');
          }
          
          let chapter_image = element.find($('input[name="chapter_image"]'));
          if(error.chapter_image) {
            chapter_image.addClass('is-invalid');
            chapter_image.next('.error').html(error.chapter_image);
            chapter_image.next('.error').removeClass('hide').addClass('show');
          }  else {
            chapter_image.removeClass('is-invalid').addClass('is-valid');
            chapter_image.next('.error').html('');
            chapter_image.next('.error').removeClass('show').addClass('hide');
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

      $("#addchapterModal, #editchapterModal").on("hidden.bs.modal", function () {
          $('#addchapterForm').trigger("reset");
          $('#editchapterForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          $(".customerSelect").empty().trigger('change');
          $(".fundSelect").empty().trigger('change');
          $(".amcSelectFilter").empty().trigger('change');
          $(".customerSelectFilter").empty().trigger('change');
          $(".fundSelectFilter").empty().trigger('change');
          $(".verificationSelectFilter").empty().trigger('change');
          resetValidationErrors($('#addchapterForm'))
          resetValidationErrors($('#editchapterForm'))
      });

      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addchapterForm')[0]);
      $.ajax({
        url: "{{ route('chapters.store') }}",
        type: "POST",
        data:  form,
        // dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        enctype: 'multipart/form-data',
        success: function (data) {
          console.log("data", data.error);
          if (!data.error) {
            Toast.fire({
              icon: 'success',
              title: data.message
            });
            $('#addchapterModal').modal('hide');
            $('#addchapterForm').trigger("reset");
                      // getCount();
            table.draw();
          }else{
            console.log('error',data.error);
            handleValidationErrors($('#addchapterForm'),data.error);
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
          var form = new FormData($('#editchapterForm')[0]);
          form.append('_method', 'POST');
          var url = "{{route('chapters.update')}}";
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
                      $('#editchapterModal').modal('hide');
                      $('#editchapterForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editchapterForm'), data.error)
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
        var chapterName = $('#filter-form').find($('select[name="filter-chapter-name"]')).val();

        getchapters(chapterName);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#filter-amc-id").val('').trigger('change');
        $("#filter-user-id").val('').trigger('change');
        $("#filter-fund-id").val('').trigger('change');
        $('#filter-form').trigger("reset");
        clearDatatable();
        getchapters();
    });
    getchapters();
    });
  </script>
@endpush
