<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>        
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
    <link rel="stylesheet" href="public/css/main.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<style type="text/css">
		@page { 
      size: 9.5in 28in;
      margin: 50px 50px;
    }

</style>
</head>
<body>
<div class="container-fluid content-header">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <p class="font-weight-bold">
                            Name :
                        </p>
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
            <div class="card-header bg-info">
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
            </div>
        </div>
    </div>
</div>
</body>