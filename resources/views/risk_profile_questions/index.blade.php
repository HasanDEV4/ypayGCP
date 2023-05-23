@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Risk Profile Questions</h4>
          <p>
          <a>AMC Management</a> /
              <span>Risk Profile Questions</span>
          </p>
      </div>
      <a href="#" id="add-btn" type="button" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>
        Add Risk Profile Question
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
                          <label for="created_at" class="form-label">Creation Date</label>
                          <input type="date" class="form-control" id="created_at" placeholder="Creation Date" name="filter-created-at" autocomplete="off">
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
                              <th>Question</th>
                              <th>Category</th>
                              <th>Weightage</th>
                              <th>Option</th>
                              <th>Points</th>
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
<input type="hidden" id="optionId" name="id">
@endsection

@section('modal')
<!-- Goal Add Modal -->
<div class="modal fade" id="addriskprofilequestionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add Risk Profile Question</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form id="addriskprofilequestionForm" onsubmit="event.preventDefault()">
                <div class="form-group row">
                    <label for="question" class="col-sm-2 col-form-label">Question</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="question" placeholder="Enter Question" name="question" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                      <label for="category" class="col-sm-2 col-form-label">Category</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="category_id" name="category_id">
                        <option value="" selected disabled>Select Question Category</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                        </select>  
                        <div class="invalid-feedback error hide">
                          </div>
                      </div>
                </div>
                <div class="form-group row">
                    <label for="weightage" class="col-sm-2 col-form-label">Weightage</label>
                    <div class="col-sm-10">
                    <input type="number" class="form-control" step="0.01" id="question" placeholder="Enter Weightage" name="weightage" autocomplete="off">
                    <div class="invalid-feedback error hide">
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
<!-- Department Add Modal -->
<!-- Department Edit Modal -->
<div class="modal fade" id="editriskprofilequestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Risk Profile Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editriskprofilequestionForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="question" class="col-sm-2 col-form-label">Question</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="question" placeholder="Enter Question" name="question" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="weightage" class="col-sm-2 col-form-label">Weightage</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="question" placeholder="Enter Weightage" name="weightage" autocomplete="off">
                    <div class="invalid-feedback error hide">
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                      <label for="category" class="col-sm-2 col-form-label">Category</label>
                      <div class="col-sm-10">
                      <select class="form-control" id="category_id" name="category_id">
                        <option value="" selected disabled>Select Question Category</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                        </select>  
                          <div class="invalid-feedback error hide">
                          </div>
                      </div>
                </div>
                <div class="form-group row">
                    <label for="option" class="col-sm-2 col-form-label">Option</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="option" name="option" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                    <label for="points" class="col-sm-2 col-form-label">Points</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="points" name="points" autocomplete="off">
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
      let count=$('#option_div').children().length+1;
      var table;
      var ids = [];
      $('.add_option').click(function(event) {
      event.preventDefault();
      let option=`<div class="form-group row option_`+count+`_div">
                    <label for="option" class="col-sm-2 col-form-label">Option `+count+`</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="option" placeholder="Enter Option" name="options[]" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                    <label for="points" class="col-sm-2 col-form-label">Points</label>
                    <div class="col-sm-3">
                      <input type="number" class="form-control" id="points" placeholder="Enter Points" name="points[]" autocomplete="off">
                      <div class="invalid-feedback error hide">
                      </div>
                    </div>
                    <div class="col-sm-2">
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
      function getRiskProfileQuestion(created_at='') {

        var queryParams = '?&created_at='+created_at;
          var url = "{{route('risk_profile_questions.getData')}}";

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
                          name: 'question.question',
                          data: 'question.question',
                      },
                      {
                          name: 'question.category.name',
                          data: 'question.category.name',
                      },
                      {
                          name: 'question.weightage',
                          data: 'question.weightage',
                      },
                      {
                          name: '_option',
                          data: '_option',
                      },
                      {
                          name: 'points',
                          data: 'points',
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
                    [5, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });
          $('#add-btn').click(function(event) {
            event.preventDefault();
            $('#addriskprofilequestionModal').modal('show');
          });
          table.on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            console.log("data", data);
            $('#optionId').val(data.id);
            data.question.question=data.question.question.replace("&#039;","'");
            $('#editriskprofilequestionForm').find($('input[name="question"]')).val(data.question.question);
            $('#editriskprofilequestionForm').find($('input[name="option"]')).val(data._option);
            $('#editriskprofilequestionForm').find($('select[name="category_id"]')).val(data.question.category.id);
            $('#editriskprofilequestionForm').find($('input[name="points"]')).val(data.points);
            $('#editriskprofilequestionForm').find($('input[name="weightage"]')).val(data.question.weightage);
            $('#editriskprofilequestionModal').modal('show');
          });
        }
      function clearDatatable() {
          table.clear();
          table.destroy();
      }

      $("#addriskprofilequestionModal, #editriskprofilequestionModal").on("hidden.bs.modal", function () {
          $('#addriskprofilequestionForm').trigger("reset");
          $('#editriskprofilequestionForm').trigger("reset");
          $(".categorySelect").empty().trigger('change');
      });
      $('#saveBtn').click(function(event) {
      event.preventDefault();
      var form = new FormData($('#addriskprofilequestionForm')[0]);
      $.ajax({
        url: "{{ route('risk_profile_questions.store') }}",
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
            $('#addriskprofilequestionModal').modal('hide');
            $('#addriskprofilequestionForm').trigger("reset");
            table.draw();
          }else{
            console.log('error',data.error);
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

          var id = $('#optionId').val();
          var form = new FormData($('#editriskprofilequestionForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('risk_profile_questions.update', '')}}" + "/" + id;
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
                      $('#editriskprofilequestionForm').trigger("reset");
                      $('#editriskprofilequestionModal').modal('hide');
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
        var creation_date = $('#filter-form').find($('input[name="filter-created-at"]')).val();
        getRiskProfileQuestion(creation_date);
    });
    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $("#created_at").val('').trigger('change');
        clearDatatable();
        getRiskProfileQuestion();
    });
    getRiskProfileQuestion();
    });
  </script>
@endpush
