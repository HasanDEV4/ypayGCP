@extends('layouts.app')
@section('content')
<div class="container-fluid content-header">
   @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
  <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
    <div class="ml-2 pl-1">
      <h4>Edit Risk Profile Detail</h4>
      <p>
        <a>Dashboard</a> /
        <a>Edit Risk Profile</a> /
        <span>Edit Risk Profile Detail</span>
      </p>
    </div>
  </div>
    <div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{url('/risk_profile/update_response')}}">
                <input type="hidden" id="user_id" value="{{$response->user_id}}" name="user_id">
                <div class="row mb-3">
                    {{ csrf_field() }}
                    <div class="col-12 col-md-3">
                        <label for="name" class="form-label">Name</label>
                        <input placeholder="Name" type="text" name="name" value="{{$response->user->full_name}}" class="form-control " id="name" disabled>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="score" class="form-label">Score</label>
                        <input placeholder="Score" type="number" step='0.001' name="score" value="{{$response->total_score}}" class="form-control " id="score">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="inputStatus4" class="form-label">Risk Profile Status</label>
                        <select class="mb-2 form-control" name="risk_profile_status" autocomplete="off">
                            <option selected disabled>Select Risk Profile Status</option>
                            <option value="2" {{ $response->risk_profile_status=="2"?"selected":"" }}>Approved</option>
                            <option value="3"{{ $response->risk_profile_status=="3" ?"selected":"" }} >Rejected</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                    <label for="inputStatus4" class="form-label">Rank</label>
                        <select class="mb-2 form-control" name="rank" autocomplete="off">
                            @foreach($ranks as $rank)
                            <option value="{{$rank->rank}}" {{ $rank->rank==$response->rank?"selected":"" }}>{{$rank->rank}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-md-12">
                        <label for="message" class="form-label">Message</label>
                        <textarea placeholder="Message" name="message" class="form-control " id="message">{{$response->message}}</textarea>
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
                @foreach($questions as $index => $question)
                    <div class="mb-5">
                            <label class="form-label">{{$question->question}}</label>
                            <select class="mb-2 form-control" name="selection[]" autocomplete="off">
                            @foreach($question->option as $_option)
                            <option value="{{$_option->id}}" {{$_option->_option==$options[$index]->_option?'selected':''}}>{{$_option->_option}}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
                <a>
                 <button class="btn btn-primary" type="submit">
                    <i class='fas fa-save'></i>
                    Update Response
                 </button>
                </a>
        </div>
    </div>
    </form>
    </div>
@endsection