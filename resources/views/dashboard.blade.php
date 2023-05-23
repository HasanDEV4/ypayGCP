@extends('layouts.app')


@section('content')

<div class="container-fluid content-header" >
  <h3 class="ml-2 pl-1">Dashboard</h3>
  <div class="row">
    @can('dashboard-user')
    <div class="col-6 col-md-3">
      <div class="small-box bg-info">
        <div class="inner">
          <h3 class="allMembers"></h3>
          <p>Approved App Users</p>
          {{ $users }}
        </div>
        {{-- <div class="icon">
          <i class="ion ion-ios-people"></i>
        </div> --}}
        <a href="#"  data-id="1" class="small-box-footer table">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    @endcan
    @can('dashboard-amc')  
    <div class="col-6 col-md-3">
      <div class="small-box bg-info">
        <div class="inner">
          <h3 class="allMembers"></h3>
          <p>AMC's</p>
          {{ $amc }}
        </div>
        {{-- <div class="icon">
          <i class="ion ion-ios-people"></i>
        </div> --}}
        <a href="#"  data-id="2" class="small-box-footer table">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div> 
    @endcan
    @can('dashboard-fund')  
    <div class="col-6 col-md-3">
      <div class="small-box bg-info">
        <div class="inner">
          <h3 class="allMembers"></h3>
          <p>Funds</p>
          {{ $funds }}
        </div>
        {{-- <div class="icon">
          <i class="ion ion-ios-people"></i>
        </div> --}}
        <a href="#"  data-id="3" class="small-box-footer table">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div> 
    @endcan
    @can('dashboard-investment')
    <div class="col-6 col-md-3">
      <div class="small-box bg-info">
        <div class="inner">
          <h3 class="allMembers"></h3>
          <p>Investments</p>
          {{ $sum }}
        </div>
        
        {{-- <div class="icon">
          <i class="ion ion-ios-people"></i>
        </div> --}}
        <a href="#" data-id="4" class="small-box-footer table">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    @endcan

    @can('dashboard-investment')
    <div class="col-lg-12" id="investmentTable">
    <div class="col-12 mb-4">
      <a class="btn btn-primary float-right px-md-5" href="{{route('investments.index')}}">View All</a>
      <h3 class="pl-1">Latest Investments</h3>
    </div>
      <div class="main-card mb-3 card">
          <div class="card-body">
              <table class="mb-0 table datatable">
                  <thead>
                      <tr>
                          <th>TransactionID</th>
                          <th>Customer</th>
                          <th>Fund</th>
                          <th>Created At</th>
                          <th>Payment Proof</th>
                          <th>Amount</th>
                          <th>Payment Method</th>
                          <th>Status</th>
                          
                      </tr>
                  </thead>
                  <tbody>
                 
                    @foreach ($data as $value)
                    {{-- @php
                    dd($data);
                  @endphp --}}
                    <tr>
                      <td>{{$value['transaction_id']}}</td> 
                      <td>{{$value['user']['full_name']}}</td> 
                      <td>{{$value['fund']['fund_name']}}</td>
                      <td>{{$value['fund']['created_at']}}</td>
                      <td><div class="p-2"><a href="{{str_starts_with($value['image'], 'http')?$value['image']:env('S3_BUCKET_URL').$value['image']}}" download>Download</a></div></td>   
                      <td>{{$value['amount']}}</td> 
                      <td>{{$value['pay_method']}}</td> 
                      {{-- <td>{{$investment->status}}</td>  --}}
                      <td>
                         @if($value['status'] == 0)
                         <div class="badge badge-dark p-2">Pending</div>
                         @elseif ($value['status'] == 1)
                         <div class="badge badge-success p-2">Accept</div>
                         @else
                         <div class="badge badge-danger p-2">Reject</div>
                         @endif
                       </td> 
                    </tr>
                    @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  @endcan
  </div>
  @can('dashboard-user')
  <div class="col-lg-12" id="customerTable">
    <div class="col-12 mb-4">
      <a class="btn btn-primary float-right px-md-5" href="{{route('customer.index')}}">View All</a>
      <h3 class="pl-1">Latest Customers</h3>
    </div>
      <div class="main-card mb-3 card">
          <div class="card-body">
              <table class="mb-0 table datatable">
                  <thead>
                      <tr>
                          <th>Customer</th>
                          <th>Email</th>
                          <th>CNIC no</th>
                          <th>Phone no</th>
                          <th>Refer Code</th>
                          <th>Status</th>
                          
                      </tr>
                  </thead>
                  <tbody>
                 
                    @foreach ($customers as $value) 
                    <tr>
                      <td>{{@$value->full_name}}</td> 
                      <td>{{@$value->email}}</td> 
                      <td>{{@$value->cust_cnic_detail->cnic_number}}</td>
                      <td>{{@$value->phone_no}}</td>
                      <td>{{ $value->refer_code }}</td>    
                      <td>
                         @if($value['status'] == 0)
                         <div class="badge badge-dark p-2">Pending</div>
                         @elseif ($value['status'] == 1)
                         <div class="badge badge-success p-2">Accept</div>
                         @else
                         <div class="badge badge-danger p-2">Reject</div>
                         @endif
                       </td> 
                    </tr>
                    @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  @endcan
  </div>
  @can('dashboard-amc')  
  <div class="col-lg-12" id="amcTable">
    <div class="col-12 mb-4">
      <a class="btn btn-primary float-right px-md-5" href="{{route('amc.index')}}">View All</a>
      <h3 class="pl-1">Latest Amcs</h3>
    </div>
      <div class="main-card mb-3 card">
          <div class="card-body">
              <table class="mb-0 table datatable">
                  <thead>
                      <tr>
                          <th>Entity</th>
                          <th>Logo</th>
                          <th>Complaint Email</th>
                          <th>Contact no</th>
                          <th>Status</th>
                          
                      </tr>
                  </thead>
                  <tbody>
                 
                    @foreach ($amcs as $value) 
                    <tr>
                      <td>{{$value->entity_name}}</td> 
                      <td><div class="p-2"><a href="{{str_starts_with($value['image'], 'http')?$value['image']:env('S3_BUCKET_URL').$value['image']}}" download>Download</a></div></td>
                       <td>{{$value->compliant_email}}</td> 
                      <td>{{$value->contact_no}}</td>    
                      <td>
                         @if($value['status'] == 0)
                         <div class="badge badge-dark p-2">In-active</div>
                         @else
                         <div class="badge badge-success p-2">Active</div>
                         @endif
                       </td> 
                    </tr>
                    @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  @endcan
  @can('dashboard-fund')
  <div class="col-lg-12" id="fundTable">
    <div class="col-12 mb-4">
      <a class="btn btn-primary float-right px-md-5" href="{{route('fund.index')}}">View All</a>
      <h3 class="pl-1">Top Funds</h3>
    </div>
      <div class="main-card mb-3 card">
          <div class="card-body">
            <table class="mb-0 table datatable">
              <thead>
                  <tr>
                      <th>Fund</th>
                      <th>Amc</th>
                      <th>Investments</th>
                      <th>Status</th>
                      
                  </tr>
              </thead>
              <tbody>
             
                @foreach ($topFunds as $value) 
                <tr>
                  <td>{{$value->fund_name}}</td> 
                  <td>{{$value->entity_name}}</td> 
                  <td>{{$value->investment_count}}</td>   
                  <td>
                     @if($value['status'] == 0)
                     <div class="badge badge-dark p-2">In-active</div>
                     @else
                     <div class="badge badge-success p-2">Active</div>
                     @endif
                   </td> 
                </tr>
                @endforeach
              </tbody>
          </table>
        </div>
      </div>
  </div>
  {{-- </div> --}}
  @endcan
  
</div>

    
@endsection


@push('scripts')
<script>
   $(function () { 

    $('#investmentTable').show();
    $('#customerTable').hide();
    $('#amcTable').hide();
    $('#fundTable').hide();
    $('.table').on('click', function (e) {
      e.stopPropagation();
      var table =  $(this).data('id');
      if(table == 1){
        $('#customerTable').show();
        $('#investmentTable').hide();
        $('#amcTable').hide();
        $('#fundTable').hide();
      }else if(table == 2){
        $('#amcTable').show();
        $('#investmentTable').hide();
        $('#fundTable').hide();
        $('#customerTable').hide();
      }else if(table == 3){
        $('#fundTable').show();
        $('#investmentTable').hide();
        $('#customerTable').hide();
        $('#amcTable').hide();
      }else if(table == 4){
        $('#investmentTable').show();
        $('#customerTable').hide();
        $('#amcTable').hide();
        $('#fundTable').hide();
      }

          });


   });
</script>
@endpush
