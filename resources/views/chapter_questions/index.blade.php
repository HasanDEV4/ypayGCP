@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Academy Questions</h4>
          <p>
              <a>Administration</a> /
              <span>Academy Questions</span>
          </p>
      </div>
      <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addquestionModal" data-backdrop="true">
        <i class="fas fa-plus mr-2"></i>

        Add Academy Question
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
                        <label for="filter-chapter-name" class="form-label">Chapter</label>
                        <select class="mb-2 form-control" name="filter-chapter-name" id="filter-chapter-name" autocomplete="off">
                          <option val='' selected disabled>Select Chapter Name</option>
                          @foreach ($academy_chapters as $chapter)
                          <option val="{{$chapter->id}}">{{$chapter->name}}</option>
                          @endforeach
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
                  <table class="mb-0 table datatable w-100">
                      <thead>
                          <tr>
                            <th>Chapter Name</th>
                            <th>Question</th>
                            <th>Download Question Image</th>
                            <th>Description</th>
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
@endsection

@section('modal')
<div class="modal fade" id="addquestionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Question</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addquestionForm" onsubmit="event.preventDefault()">
                  <div class="form-group row">
                      <label for="chapter_name" class="col-sm-2 col-form-label">Chapter</label>
                      <div class="col-sm-10">
                      <select class="mb-2 form-control chapter_id" name="chapter_id" autocomplete="off">
                          <option value='' selected disabled>Select Chapter</option>
                          @foreach ($academy_chapters as $chapter)
                          <option value="{{$chapter->id}}">{{$chapter->name}}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="question" class="col-sm-2 col-form-label">Question</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="Enter Question" name="question" class="form-control question" id="question">
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="image" class="col-sm-2 col-form-label">Image</label>
                      <div class="col-sm-10">
                        <input type="file" name="image" class="form-control image" id="image">
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="description" class="col-sm-2 col-form-label">Description</label>
                      <div class="col-sm-10">
                        <textarea type="text" placeholder="Enter Description" name="description" class="form-control question" id="description"></textarea>
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div id="option_div"></div>
                    <div class="form-group row">
                    <button type="button" class="btn btn-info add_option">Add Option</button>
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
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Confirm Question Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="confirmdeletionForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                      <label class="col-form-label">Are You Sure You want to delete this Question with Options?</label>
                  </div>
                <input type="hidden" id="question_id" name="question_id">
              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editquestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editquestionForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                      <label for="chapter_name" class="col-sm-2 col-form-label">Chapter</label>
                      <div class="col-sm-10">
                      <select class="mb-2 form-control chapter_id" name="chapter_id" autocomplete="off">
                          <option value='' selected disabled>Select Chapter</option>
                          @foreach ($academy_chapters as $chapter)
                          <option value="{{$chapter->id}}">{{$chapter->name}}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="question" class="col-sm-2 col-form-label">Question</label>
                      <div class="col-sm-10">
                        <input type="text" name="question" class="form-control question" id="question">
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="image" class="col-sm-2 col-form-label">Image</label>
                      <div class="col-sm-10">
                        <input type="file" name="image" class="form-control image" id="image">
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="description" class="col-sm-2 col-form-label">Description</label>
                      <div class="col-sm-10">
                        <textarea type="text" name="description" class="form-control question" id="description"></textarea>
                        <div class="invalid-feedback error hide ">
                        </div>
                      </div>
                  </div>
                  <div id="option_div2"></div>
                    <!-- <div class="form-group row">
                    <button type="button" class="btn btn-info add_option2">Add Option</button>
                    </div> -->
                <input type="hidden" id="question_id" name="question_id">
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
      let count=$('#option_div').children().length+1;
      $('.add_option2').click(function(event) {
      event.preventDefault();
      let option=`<div class="form-group row option_`+count+`_div p-1 border">
                    <label for="option2" class="col-sm-2 col-form-label">Option `+count+`</label>
                    <div class="col-sm-10 row mt-3">
                      <input type="text" class="form-control" id="option2" placeholder="Enter Option" name="options[]" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                    <div class="col-sm-12 row mt-3">
                      <label for="options" class="col-sm-2 col-form-label">Is Correct</label>
                      <label class="radio-inline mt-1">
                        <input type="radio" name="is_correct_`+count+`" id="yes`+count+`" value="1" required>Yes
                      </label>
                      <label class="radio-inline mt-1 ml-3">
                        <input type="radio" name="is_correct_`+count+`" id="no`+count+`" value="0">No
                      </label>
                    </div>
                    <div class="col-sm-12 row ml-1 mt-3">
                    <button class="btn btn-danger delete_btn2" id="option_`+count+`">Remove</button>
                    </div>
                </div>`;
      $('#option_div2').append(option);
      count=$('#option_div2').children().length+1;
      $('.delete_btn2').click(function(event) {
        event.preventDefault();
        let id=$(this).attr('id');
        $('.'+id+'_div').remove();
        count=$('#option_div2').children().length+1;
      });
      });   
      $('.add_option').click(function(event) {
      event.preventDefault();
      let option=`<div class="form-group row option_`+count+`_div p-1 border">
                    <label for="option" class="col-sm-2 col-form-label">Option `+count+`</label>
                    <div class="col-sm-10 row mt-3">
                      <input type="text" class="form-control" id="option" placeholder="Enter Option" name="options[]" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                    <div class="col-sm-12 row mt-3">
                      <label for="options" class="col-sm-2 col-form-label">Is Correct</label>
                      <label class="radio-inline mt-1">
                        <input type="radio" name="is_correct_`+count+`" id="yes`+count+`" value="1" required>Yes
                      </label>
                      <label class="radio-inline mt-1 ml-3">
                        <input type="radio" name="is_correct_`+count+`" id="no`+count+`" value="0">No
                      </label>
                    </div>
                    <div class="col-sm-12 row ml-1 mt-3">
                    <button class="btn btn-danger delete_btn" id="option_`+count+`">Remove</button>
                    </div>
                </div>`;
      $('#option_div').append(option);
      count=$('#option_div').children().length+1;
      $('.delete_btn').click(function(event) {
        event.preventDefault();
        let id=$(this).attr('id');
        $('.'+id+'_div').remove();
        count=$('#option_div').children().length+1;
      });
      });

      var table;
      function getQuestions(chapterName= '') {

        var queryParams = '?&chapterName='+chapterName;
          var url = "{{route('chapter_questions.getData')}}";

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
                          name: 'chapter.name',
                          data: 'chapter.name',
                  },
                  {
                          name: 'question',
                          data: 'question',
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
                          name: 'description',
                          data: 'description',
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
                          visible:false,
                  },
                  {
                          data: 'action',
                          render: function (data, type, row) {
                            return `
                            <div class="btn-group dropdown" role="group">
                                <a class="btn btn-sm btn-light text-center edit" type="button" target="_blank"><i class="fas fa-edit text-info fa-lg"></i></a>
                                <a class="btn btn-sm btn-danger text-center delete" type="button" href="#"><i class="fas fa-trash  fa-lg"></i></a>
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
          
          table.on('click', '.delete', function () {
            var current_row = $(this).parents('tr');//Get the current row
            if (current_row.hasClass('child')) {//Check if the current row is a child row
                current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
            }
            var data = table.row(current_row).data();
            $('#confirmdeletionForm').find($('input[name="question_id"]')).val(data.id);
            $('#confirmDeleteModal').modal('show');
          });

          table.on('click', '.edit', function () {
            var current_row = $(this).parents('tr');//Get the current row
            if (current_row.hasClass('child')) {//Check if the current row is a child row
                current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
            }
            var data = table.row(current_row).data();
            $('#editquestionForm').find($('input[name="question_id"]')).val(data.id);
            $('#editquestionForm').find($('select[name="chapter_id"]')).val(data.chapter.id);
            $('#editquestionForm').find($('textarea[name="description"]')).val(data.description);
            $('#editquestionForm').find($('input[name="question"]')).val(data.question);
            $('#option_div2').html('');
            var question_id=data.id;
            $.ajax({
              url: "{{ url('/get/question/options') }}"+"/"+question_id,
              type: "GET",
              dataType:'JSON',
              contentType: false,
              cache: false,
              processData: false,
              success: function (data) {
                  if (data.length!=0) {
                    var html='';
                    $.each(data,function(index,value){
                        html+=`<div class="form-group row option_`+index+`_div p-1 border">
                    <label for="option" class="col-sm-2 col-form-label">Option `+(index+1)+`</label>
                    <div class="col-sm-10 row mt-3">
                      <input type="text" class="form-control" id="option" placeholder="Enter Option" name="options[]" autocomplete="off" value="${value.option_name}">
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                    <div class="col-sm-12 row mt-3">
                      <label for="options" class="col-sm-2 col-form-label">Is Correct</label>
                      <label class="radio-inline mt-1">
                        <input type="radio" name="is_correct_`+index+`" ${value.is_correct==1?"checked":""} id="yes`+index+`" value="1" required>Yes
                      </label>
                      <label class="radio-inline mt-1 ml-3">
                        <input type="radio" name="is_correct_`+index+`" ${value.is_correct==0?"checked":""} id="no`+index+`" value="0">No
                      </label>
                    </div>
                    <div class="col-sm-12 row ml-1 mt-3">
                    <button class="btn btn-danger remove_btn" data-question-id="`+value.question_id+`" id="`+value.id+`">Remove</button>
                    </div>
                    <input type="hidden" value="${value.id}" name="option_id_${index}">
                </div>`
                      });
                      $('#option_div2').html(html);
                      $('.remove_btn').click(function(event) {
                        event.preventDefault();
                        var option_id=$(this).attr('id');
                        var question_id=$(this).data('question-id');
                        $.ajax({
                          url: "{{ route('chapter_questions.option.delete') }}",
                          type: "POST",
                          data:  {
                            'option_id':option_id,
                            'question_id':question_id,
                          },
                          success: function (data) {
                            if (data.length!=0) {
                                var html='';
                                $.each(data,function(index,value){
                                    html+=`<div class="form-group row option_`+index+`_div p-1 border">
                                <label for="option" class="col-sm-2 col-form-label">Option `+(index+1)+`</label>
                                <div class="col-sm-10 row mt-3">
                                  <input type="text" class="form-control" id="option" placeholder="Enter Option" name="options[]" autocomplete="off" value="${value.option_name}">
                                  <div class="invalid-feedback error hide">
                                  </div>
                                </div>
                                    <div class="col-sm-12 row mt-3">
                                      <label for="options" class="col-sm-2 col-form-label">Is Correct</label>
                                      <label class="radio-inline mt-1">
                                        <input type="radio" name="is_correct_`+index+`" ${value.is_correct==1?"checked":""} id="yes`+index+`" value="1" required>Yes
                                      </label>
                                      <label class="radio-inline mt-1 ml-3">
                                        <input type="radio" name="is_correct_`+index+`" ${value.is_correct==0?"checked":""} id="no`+index+`" value="0">No
                                      </label>
                                    </div>
                                    <div class="col-sm-12 row ml-1 mt-3">
                                    <button class="btn btn-danger remove_btn" data-question-id="`+value.question_id+`" id="`+value.id+`">Remove</button>
                                    </div>
                                    <input type="hidden" value="${value.id}" name="option_id_${index}">
                                </div>`
                                  });
                                  $('#option_div2').html(html);
                              }
                              else
                              {
                                $('#option_div2').html('');
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
                  }
                  else
                  {
                    $('#option_div2').html('');
                  }
              },
            });
            setTimeout(function(){ 
                $('#editquestionModal').modal('show');
            }, 200);
          });

          }


      function handleValidationErrors(element, error) {
         
          let description = element.find($('input[name="description"]'));
          if(error.description) {
            description.addClass('is-invalid');
            description.next('.error').html(error.description);
            description.next('.error').removeClass('hide').addClass('show');
          }  else {
            description.removeClass('is-invalid').addClass('is-valid');
            description.next('.error').html('');
            description.next('.error').removeClass('show').addClass('hide');
          }
          
          let question = element.find($('input[name="question"]'));
          if(error.question) {
            question.addClass('is-invalid');
            question.next('.error').html(error.question);
            question.next('.error').removeClass('hide').addClass('show');
          }  else {
            question.removeClass('is-invalid').addClass('is-valid');
            question.next('.error').html('');
            question.next('.error').removeClass('show').addClass('hide');
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

      $("#addquestionModal, #editquestionModal").on("hidden.bs.modal", function () {
          $('#addquestionForm').trigger("reset");
          $('#editquestionForm').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addquestionForm'))
          resetValidationErrors($('#editquestionForm'))
      });
      $('#confirmBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#confirmdeletionForm')[0]);
      $.ajax({
        url: "{{ route('chapter_questions.delete') }}",
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
            $('#confirmDeleteModal').modal('hide');
            $('.datatable').DataTable().ajax.reload();
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
      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addquestionForm')[0]);
      $.ajax({
        url: "{{ route('chapter_questions.store') }}",
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
            $('#addquestionModal').modal('hide');
            $('#addquestionForm').trigger("reset");
                      // getCount();
            table.draw();
          }else{
            console.log('error',data.error);
            handleValidationErrors($('#addquestionForm'),data.error);
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
          var form = new FormData($('#editquestionForm')[0]);
          form.append('_method', 'POST');
          var url = "{{route('chapter_questions.update')}}";
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
                      $('#editquestionModal').modal('hide');
                      $('#editquestionForm').trigger("reset");
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editquestionForm'), data.error)
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

        getQuestions(chapterName);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#filter-amc-id").val('').trigger('change');
        $("#filter-user-id").val('').trigger('change');
        $("#filter-fund-id").val('').trigger('change');
        $('#filter-form').trigger("reset");
        clearDatatable();
        getQuestions();
    });
    getQuestions();
    $('.remove_btn').click(function(event) {
      alert('clicked');
    });
    });
  </script>
@endpush
