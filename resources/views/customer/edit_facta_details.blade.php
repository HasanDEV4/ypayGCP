@extends('layouts.app')
@section('content')
    <div class="container-fluid content-header">
        <div class="col-lg-12">
            <div class="d-flex flex-row justify-content-between mb-2">
                <div class="col-lg-12">
                    <a  class="btn btn-primary"  href="{{route('cust.details','').'/'.$user->id}}">
                    <i class='fa fa-arrow-left' style='font-size:24px;'></i>
                    Go Back
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between w-100  align-items-start align-items-md-center">
            <div class="ml-2 pl-1">
            <h4>Edit Facta Details</h4>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="d-flex flex-row justify-content-between mb-2">
            @if(count($facta_response)!=0)
                <div class="col-lg-12">
                    <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row mb-3">
                        <div class="col-12">
                        <div id="comments_div">
                        <form action="{{ route('facta.update.details', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                            @csrf
                        @method('PUT')
                            <table class="mb-0 table datatable w-100" style="border: 1px solid black;">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">Facta/CRS Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                <td>Country of birth</td>
                                <td>{{strtoupper($user->cust_cnic_detail['citizenshipstatus']['status'])}}</td>
                                </tr>
                                <tr>
                                <td>Country of Residance</td>
                                <td>{{strtoupper($user->cust_cnic_detail['country_of_residence'])}}</td>
                                </tr>
                                @if(strtolower($user->cust_basic_detail['country_of_residence'])=="usa")
                                <tr>
                                <td>Passport Number</td>
                                <td>{{$user->cust_cnic_detail['passport_number']}}</td>
                                </tr>
                                @endif
                                @foreach($facta_response as $index=>$response)
                                <tr>
                                <td>{{$facta_crs_questions[$index]}}</td>
                                <td>
                                    <select class="mb-2 form-control" name="answers[]" autocomplete="off">
                                            <option selected disabled>Select Status</option>
                                            <option value="1" {{@$response->answer == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{@$response->answer == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>
                                </tr>
                                @endforeach
                                <tr>
                                <td>Taxpayer Identification Number</td>
                                @if($user->cust_cnic_detail['country_of_residence']=="Other")
                                    <td>{{$user->cust_cnic_detail['taxpayer_identification_number']}}</td>
                                @else
                                    <td>N/A</td>
                                @endif
                                </tr>
                                </tbody>
                            </table>
                            <div class="col-12 mt-2 mb-4 text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </div>
                        </form>
                        </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush