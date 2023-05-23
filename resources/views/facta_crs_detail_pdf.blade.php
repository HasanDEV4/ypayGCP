<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<meta name="generator" content="LibreOffice 6.4.7.2 (Linux)"/>
	<meta name="created" content="2022-09-22T12:42:31"/>
	<meta name="changed" content="2022-09-26T10:58:21.378957124"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<style type="text/css">
#table
{
  page-break-after: always;
}
html { overflow-y: scroll; }
body { 
  /* background: #eee url('https://i.imgur.com/eeQeRmk.png'); https://subtlepatterns.com/weave/ */
  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
  font-size: 62.5%;
  line-height: 1;
  color: #585858;
  /* padding: 22px 10px; */
  padding-bottom: 55px;
}
span {
  font-weight: bold;
  color:#fff;
  font-size: 16px;
}
.table{
  left:0;
  border:1px solid black;
}
.table .thead  {
  border:1px solid black;
  background: #007bff;
}
.table td{
  border:1px solid black;
  font-size: 14px;
}
.table th{
  border:0.5px solid black;
}
#border{
  font-size:18px;
  border:0.5px solid black;
}
        
	</style>
</head>
<body>
    <br />
    <div id="content_div">
    @if(count($facta_response)!=0)
  <div class="main-card mb-3">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12">
        <div id="comments_div">
        <table class='table'>
          <tr class='thead'>
            <th colspan="2" class="text-center"><span>Facta/CRS Details</span></th>
         </tr>
          <tbody>
            <tr>
              <td>Citizenship Status</td>
              <td>{{strtoupper($user->cust_cnic_detail['citizenshipstatus']['status'])}}</td>
            </tr>
            <tr>
              <td>Country of Residance</td>
              <td>{{strtoupper($user->cust_cnic_detail['country_of_residence'])}}</td>
            </tr>
            <tr>
              <td>Passport Number</td>
              <td>{{$user->cust_cnic_detail['passport_number']??'N/A'}}</td>
            </tr>
            @foreach($facta_response as $index=>$response)
            <tr>
              <td>{{$facta_crs_questions[$response->question_id]}}</td>
              @if($response->answer=="0")
              <td>No</td>
              @else
              <td>Yes</td>
              @endif
            </tr>
            @endforeach
            <tr>
              <td>Taxpayer Identification Number</td>
              @if(strtolower($user->cust_cnic_detail['country_of_residence'])=="usa" || $user->cust_cnic_detail['citizenship_status']=="2")
                <td>{{$user->cust_cnic_detail['taxpayer_identification_number']}}</td>
              @else
                <td>N/A</td>
              @endif
            </tr>
            <tr class='thead'>
                <th colspan="2" class="text-center"><span>PEP RELATED INFORMATION</span></th>
            </tr>
            @foreach($pep_questions as $index=>$response)
            <tr>
              <td>{{$response}}</td>
              <td>No</td>
            </tr>
            @endforeach
          </tbody></table>
          <br/>
          <div id="border">
            <label class="form-label p-2"><b>Name:</b> {{strtoupper($user->full_name)}}</label>
          </div>
          <br/>
          <div id="border">
            <label class="form-label p-2"><b>CNIC:</b> {{$user->cust_cnic_detail['cnic_number']}}</label>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
    </div>
    <br />
 </div> 
</body>