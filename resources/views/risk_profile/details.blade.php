@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
  <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div class="ml-2 pl-1">
      <h4>Risk Profile Response</h4>
      <p>
        <a>Dashboard</a> /
        <a>Risk Profile</a> /
        <span>Risk Profile Response</span>
      </p>
    </div>
    @if(!isset($no_data))
      <a id="export_risk_profile_resp_btn"  class="btn btn-primary">
            <i class='fas fa-file-export'></i>
              Export Response
      </a>
    @endif
  </div>
  @if(isset($no_data))
  <div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 col-md-3">
                    <p class="font-weight-bold">
                        No Response Data Found
                    </p>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
  @else
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <p class="font-weight-bold">
                            Name :
                        </p>
                        <input id="user_id" value="{{$response->user_id}}" hidden>
                        <p class="text-muted">{{ $response->user->full_name }}</p>
                    </div>
                    <div class="col-12 col-md-3">
                        <p class="font-weight-bold">
                            Score :
                        </p>
                        <p class="text-muted">{{ $response->total_score }}</p>
                    </div>
                    <div class="col-12 col-md-3">
                        <p class="font-weight-bold">
                            Risk Profile Status :
                        </p>
                        @if($response->risk_profile_status==2)
                        <div class="badge badge-success p-2">Approved</div>
                        @else
                        <div class="badge badge-danger p-2">Rejected</div>
                        @endif
                    </div>
                    <div class="col-12 col-md-3">
                        <p class="font-weight-bold">
                            Rank :
                        </p>
                        @if($response->total_score>=6)
                        <div class="badge badge-success p-2">{{ $response->rank }}</div>
                        @else
                        <div class="badge badge-danger p-2">{{$response->rank }}</div>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-md-12">
                            <p class="font-weight-bold">
                                Message :
                            </p>
                            <p class="text-muted">{{ $response->message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header bg-secondary">
                <h4 class="mb-0">Customer Selections</h4>
            </div>
            <div class="card-body">
                @foreach($options as $option)
                    <div class="mb-5">
                            <p class="font-weight-bold">
                                {{$option->question->question}}
                            </p>
                            <p class="text-muted">{{ $option->_option }}</p>
                    </div>
                @endforeach
                <a href="{{url('/risk_profile/editdetails/'.$response->user_id)}}" class="btn btn-primary">
                    <i class='fas fa-edit'></i>
                    Edit Response
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@push('scripts')
<script>
             $('#export_risk_profile_resp_btn').on('click', function(e) {
            e.preventDefault();
            var user_id=$('#user_id').val();
            if(user_id!=null)
            window.open("/risk_profile/exportdetails/"+user_id);
        });
</script>
@endpush