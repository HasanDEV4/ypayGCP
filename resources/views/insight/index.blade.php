@extends('layouts.app')


@section('content')
<div class="container-fluid content-header">
  <div class="row">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
      <div class="ml-2 pl-1">
          <h4>Blogs</h4>
          <p>
              <!-- <a>Dashboard</a> / -->
              <span>Marketing</span> /
              <span>Blogs</span>
          </p>
      </div>
    <div class="d-flex flex-row">
        @can('add-blog')
        <div class="mr-2">
            <a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#addBlogModal" data-backdrop="true">
                <i class="fas fa-plus"></i>
                Add Blog
            </a>
        </div>
        @endcan
        @can('view-insightTag')    
        <div class="mr-2">
            <a href="#"  class="btn btn-primary addTag" data-toggle="modal" data-target="#addTagModal" data-backdrop="true">
                <i class="fas fa-plus"></i>
                Add Tag
            </a>
        </div>
        @endcan
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
                              <th>Logo</th>
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
<!-- Blog Add Modal -->
<div class="modal fade" id="addBlogModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add Blog</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="addBlogForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
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
                            <label for="Title" class="col-sm-2 col-form-label">Author Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control author_name" id="author_name" placeholder="Enter Author Name" name="author_name" autocomplete="off">
                                <div class="invalid-feedback error hide">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label for="Title" class="col-sm-2 col-form-label">Order</label>
                          <div class="col-sm-10">
                              <input type="number" class="form-control title" id="order_no" placeholder="Order" name="order_no" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                        <div class="form-group row">
                            <label for="Title" class="col-sm-2 col-form-label">Reading Time (Minutes)</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control reading_time" id="reading_time" placeholder="Enter Reading Time In Minutes" name="reading_time" autocomplete="off">
                                <div class="invalid-feedback error hide">
                                </div>
                            </div>
                        </div>
                      <div class="form-group row">
                          <label for="Logo" class="col-sm-2 col-form-label">Logo</label>
                          <div class="col-sm-10">
                            <input type='file' class="form-control logo h-auto" id="logo" name="logo" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                    
                      <div class="form-group row">
                          <label for="Text" class="col-sm-2 col-form-label">Text</label>
                          <div class="col-sm-10">
                            <textarea  class="form-control text" id="text" name="text" placeholder="Text" rows="10" cols="70"></textarea>
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                     
                      {{-- <div class="form-group row">
                          <label for="URL" class="col-sm-2 col-form-label">URL/Link</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control url" id="url" name="url" placeholder="https://ypayfinancial.com/" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div> --}}
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
                            <div class="col-lg-12 mt-2 mb-4 ">
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
                  <button type="button" class="btn btn-primary" id="saveBtnBlog">Save changes</button>
              </div>
          </div>
      </div>
</div>
<!-- Blog Add Modal -->

<!-- Tag Add Modal -->
<div class="modal fade" id="addTagModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Tags</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTagForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                    @can('add-insightTag')
                    <div class="form-group row">
                        <label for="Name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control name" id="name" placeholder="name" name="name" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="saveBtnTag">Add</button>
                    </div>
                    @endcan
                </form>
                <div class="row">
                <div class="col-lg-12">
                            <table class="table datatable2" id="dataTableExample" style="width: 100%">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Existing Tags</td>
                                        <td>Actions</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($insight_tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>{{ $tag->name }}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                </div>
            </div>
            </div>
           
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="saveBtnVideo">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>
<!-- Tag Add Modal -->
<!-- Blog Edit Modal -->
<div class="modal fade" id="editBlogModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Blog</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editBlogForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
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
                        <label for="Title" class="col-sm-2 col-form-label">Author Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control author_name" id="author_name" placeholder="Enter Author Name" name="author_name" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                    <label for="Title" class="col-sm-2 col-form-label">Order</label>
                          <div class="col-sm-10">
                              <input type="number" class="form-control title" id="order_no" placeholder="Order" name="order_no" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                    <div class="form-group row">
                        <label for="Title" class="col-sm-2 col-form-label">Reading Time (Minutes)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control reading_time" id="reading_time" placeholder="Enter Reading Time In Minutes" name="reading_time" autocomplete="off">
                            <div class="invalid-feedback error hide">
                            </div>
                        </div>
                    </div>
                      <div class="form-group row">
                          <label for="Logo" class="col-sm-2 col-form-label">Logo</label>
                          <div class="col-sm-10">
                            <input type='file' class="form-control logo h-auto" id="logo" name="logo" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="Text" class="col-sm-2 col-form-label">Text</label>
                        <div class="col-sm-10">
                          <textarea  class="form-control text" id="text" name="text" placeholder="Text" rows="10" cols="70"></textarea>
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
                      {{-- <div class="form-group row">
                          <label for="URL" class="col-sm-2 col-form-label">URL</label>
                          <div class="col-sm-10">
                            <input type='text' class="form-control url" id="url" name="url" placeholder="https://ypayfinancial.com/"" />
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div> --}}
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
                      <div class="form-group row allow-blog" id="abc">
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
                <button type="button" class="btn btn-primary" id="editBtnBlog">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Blog Edit Modal -->
<!-- Tag Edit Modal -->
<div class="modal fade" id="editTagModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTagForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
                      <div class="form-group row">
                          <label for="Title" class="col-sm-2 col-form-label">Tag Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control name" id="name" placeholder="tag name" name="name" autocomplete="off">
                              <div class="invalid-feedback error hide">
                              </div>
                          </div>
                      </div>
                </form>
            </div>
              
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtnTag">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- Tag Edit Modal -->

 <!-- Tag Delete Modal --> 
<div class="modal fade" id="deleteTagModal" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Tag?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- Tag Delete Modal -->
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

      function getInsight(title = '', status = '', from = '', to = '',category = '') {

        var queryParams = '?status='+status+'&title='+title+'&from='+from+'&to='+to+'&category='+category;
         
          var url = "{{route('insight.getData')}}";

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
                      }
                      ,{
                          name: 'title',
                          data: 'title',
                          
                      },
                    //   {
                    //       name: 'category_name',
                    //     //   data: 'category_name',
                    //       render: function (data, type, row) {
                    //           if(row.category_name) {
                    //             var category_name = row.category_name;
                    //               return `<div p-2">${row.category_name}</div>`;
                    //           } else {
                    //               return `<div class="p-2">------</div>`;
                    //           }
                    //       },
                   
                    //   },
                      {
                          name: 'Logo',
                          render: function (data, type, row) {
                              if(row.logo) {
                                var logo = row.logo;
                                  return `<div p-2"><a href="${row.logo.startsWith('http')?row.logo:"{{env('S3_BUCKET_URL')}}"+row.logo}" download>Download</a></p-2>`;
                              } else {
                                  return `<div class="p-2">------</div>`;
                              }
                          },
                          orderable: false
                          
                          // data: 'logo',
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
                            if(row.type == 2){
                                return `<div class="btn-group dropdown" role="group">
                                    @can('edit-blog')
                                    <a class="btn btn-sm btn-light text-center editBlog" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
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
                    [3, "desc"]
                  ],
                  searching: false,
                  "iDisplayLength": 10,
          });

          
          table.on('click', '.editBlog', function () {
            var data = table.row($(this).parents('tr')).data();
            // var blgId = $('#insightId').val(data.id);
            $('#insightId').val(data.id);
            // $('#editBlogForm').find($('select[name="category"]')).val(data.category_id);
            $('#editBlogForm').find($('input[name="title"]')).val(data.title);
            $('#editBlogForm').find($('textarea[name="text"]')).val(data.text);
            $('#editBlogForm').find($('select[name="tag"]')).val(data.tag_id);
            $('#editBlogForm').find($('input[name="author_name"]')).val(data.author_name);
            $('#editBlogForm').find($('input[name="reading_time"]')).val(data.reading_time);
            $('#editBlogForm').find($('input[name="url"]')).val(data.url);
            $('#editBlogForm').find($('select[name="status"]')).val(data.status);
            $('#editBlogForm').find($('input[name="order_no"]')).val(data.order_no);
            if(data.is_allowed == 1){
            $('#editBlogForm').find($('input[name="is_allowed"]')).prop('checked',true);
            }else if(data.is_allowed == 0){
            $('#editBlogForm').find($('input[name="is_allowed"]')).prop('checked',false);
            }
            if(isAllowedCount >= 6){
                if(data.is_allowed == 1){
                    
                    $('.allow-blog').show();
                }else{
                    $('.allow-blog').hide();
                    
                }
                 
            }else if(isAllowedCount <= 6){
                if(data.is_allowed == 0){
                    
                    $('.allow-blog').show();
                }else{
                    $('.allow-blog').show();
                    
                }
            }
            
            
            $('#editBlogModal').modal('show');
           
            
          });

       
      }

      function getTags()
      {
         // tag modal datatable
         var url = "{{route('insightTag.index')}}";

            table2 = $('.datatable2').DataTable({
            "language": {
                "emptyTable": "No records available"
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: "GET",
            },
            columns: [
                {
                    name: 'id',
                    data: 'id',
                    
                },
                {
                    name:'name',
                    data:'name'
                    
                },
                {
                    data: 'Actions',
                    render: function (data, type, row) {
                    if(row.id){
                        return `<div class="btn-group dropdown" role="group">
                            @can('edit-insightTag')
                            <a class="btn btn-sm btn-light text-center editTag" type="button" href="#"><i class="fas fa-edit text-info fa-lg"></i></a>
                            @endcan
                            @can('delete-insightTag')
                            <a class="btn btn-sm btn-danger text-center deleteTag" type="button" href="#"><i class="fas fa-trash  fa-lg"></i></a>
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
            [0, "desc"]
            ],
            searching: false,
            "iDisplayLength": 10,
            });

            table2.on('click', '.editTag', function () {
                var data = table2.row($(this).parents('tr')).data();
                $('#insightId').val(data.id);
                $('#editTagForm').find($('input[name="name"]')).val(data.name);
                

                $('#editTagModal').modal('show');
            
            });

            table2.on('click', '.deleteTag', function () {
                var data = table2.row($(this).parents('tr')).data();
                $('#insightId').val(data.id);
                $('#deleteTagModal').modal('show');
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
          let order_no = element.find($('input[name="order_no"]'));
          if(error.order_no) {
            order_no.addClass('is-invalid');
            order_no.next('.error').html(error.order_no);
            order_no.next('.error').removeClass('hide').addClass('show');
          } else {
            order_no.removeClass('is-invalid').addClass('is-valid');
            order_no.next('.error').html('');
            order_no.next('.error').removeClass('show').addClass('hide');
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
          let name = element.find($('input[name="name"]'));
          if(error.name) {
            name.addClass('is-invalid');
            name.next('.error').html(error.name);
            name.next('.error').removeClass('hide').addClass('show');
          } else {
            name.removeClass('is-invalid').addClass('is-valid');
            name.next('.error').html('');
            name.next('.error').removeClass('show').addClass('hide');
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

      $("#addBlogModal, #addTagModal,#editTagModal ,#editBlogModal, #deleteTagModal").on("hidden.bs.modal", function () {
          $('#addBlogForm').trigger("reset");
          $('#addTagModal').trigger("reset");
          $('#editBlogForm').trigger("reset");
          $('#editTagModal').trigger("reset");
          $('.addFormErrors').removeClass('show').addClass('hide');
          $('.editFormErrors').removeClass('show').addClass('hide');
          $('.addFormErrors').html('');
          $('.editFormErrors').html('');
          $('.modal-backdrop').remove();
          resetValidationErrors($('#addBlogForm'))
          resetValidationErrors($('#addTagModal'))
          resetValidationErrors($('#editBlogForm'))
          resetValidationErrors($('#editTagModal'))
      });

      $('#saveBtnBlog').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addBlogForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('saveBlog') }}",
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
                      $('#addBlogModal').modal('hide');
                      $('#addBlogForm').trigger("reset");
                      location.reload();
                      table.draw();
                  } else {
                      console.log('data error', data);
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addBlogForm'), data.error)
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

      $('#saveBtnTag').click(function (e) {
          e.preventDefault();
           var form = new FormData($('#addTagForm')[0]);
          $.ajax({
              data: form,
              url: "{{ route('insightTag.store') }}",
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
                    //   $('#addVideoModal').modal('hide');
                      $('#addTagForm').trigger("reset");
                      table2.draw();
                      location.reload();
                  } else {
                   console.log('error',data.error);
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                      });
                      //validation errors
                      $('.addFormErrors').html('');
                      handleValidationErrors($('#addTagForm'), data.error)
                      
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

      $('#editBtnBlog').click(function (e) {

          var id = $('#insightId').val();
          var form = new FormData($('#editBlogForm')[0]);
          form.append('_method', 'PUT');
          var url = "{{route('updateBlog', '')}}" + "/" + id;
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
                      $('#editBlogModal').modal('hide');
                      $('#editBlogForm').trigger("reset");
                        location.reload();
                      table.draw();
                  } else {
                      //validation errors
                      handleValidationErrors($('#editBlogForm'), data.error)
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

      $('#editBtnTag').click(function (e) {
        
                var id = $('#insightId').val();
                var form = new FormData($('#editTagForm')[0]);
                form.append('_method', 'PUT');
                var url = "{{route('insightTag.update', '')}}" + "/" + id;
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
                            $('#editTagModal').modal('hide');
                            $('#editTagModal').trigger("reset");
                            table2.draw();
                            location.reload();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                            //validation errors
                            handleValidationErrors($('#editTagForm'), data.error)
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
          var url = "{{route('insightTag.destroy', '')}}" + "/" + id;

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
                      $('#deleteTagModal').modal('hide');
                      table2.draw();
                      location.reload();
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
        var category = $('#filter-form').find($('input[name="filter-category"]')).val();
        var from = $('#filter-form').find($('input[name="filter-from-date"]')).val();
        var to = $('#filter-form').find($('input[name="filter-to-date"]')).val();
        var status = $('#filter-form').find($('select[name="filter-status"]')).val();
        // console.log('status', status);
        status = status != null ? status : '';
        getInsight(title, status, from, to,category);
    });

    $('.btnResetFilter').click(function (e) {
        e.preventDefault();
        $('#filter-form').trigger("reset");
        clearDatatable();
        getInsight();
    });
      getInsight();
      getTags();
    });
  </script>
@endpush
