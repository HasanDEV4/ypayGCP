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
		html, body, div, span, applet, object, iframe, h1, h2, h2, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, , tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  font: inherit;
  vertical-align: baseline;
  outline: none;
  -webkit-font-smoothing: antialiased;
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.img-fluid
{
  margin:20px !important;
}
.images_div
{
  page-break-before: always;
  page-break-inside: avoid;
  margin-bottom:200px !important;
}
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
@page { 
  size: 23in 20in;
}
::selection { background: #5f74a0; color: #fff; }
::-moz-selection { background: #5f74a0; color: #fff; }
::-webkit-selection { background: #5f74a0; color: #fff; }

br { display: block; line-height: 1.6em; } 

article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section { display: block; }
ol, ul { list-style: none; }

input, textarea { 
  -webkit-font-smoothing: antialiased;
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  outline: none; 
}

blockquote, q { quotes: none; }
blockquote:before, blockquote:after, q:before, q:after { content: ''; content: none; }
strong, b { font-weight: bold; } 

table h2{ border-collapse: collapse; border-spacing: 0; }
img { border: 0; max-width: 100%; }

h1 { 
  font-family: 'Amarante', Tahoma, sans-serif;
  font-weight: bold;
  font-size: 2.0em;
  line-height: 1.7em;
  margin-bottom: 5px;
  text-decoration: underline;
  text-align: center;
}
.h2 { 
  font-family: 'Amarante', Tahoma, sans-serif;
  font-weight: bold;
  font-size: 2.0em;
  line-height: 1.7em;
  margin-bottom: 5px;
  margin-left: 10px !important;
}
.heading-title {
	margin-left:65%;
  font-size: 2.5em;
}

span {
  font-weight: bold;
  color:#fff;
  font-size: 16px;
}


/** page structure **/
#wrapper {
  display: block;
  width: 850px;
  background: #fff;
  margin: 4%;
  -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
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


#company-logo {
  position:absolute;
  left:0;
  top:0;
  margin: 4%;
}
#content_div
{
  margin:10px;
}
#current-date {
  position:absolute;
  right:0;
  top:0;
  margin: 4%;
  font-size: 1.2em;
}
        
	</style>
</head>
<body>
 <div id="wrapper">
      <img id="company-logo" src="http://35.90.107.89/images/ypay.png" alt="" width="80"> 
      <p id="current-date">
        <b>{{ date('j F, Y') }}</b>
      </p>
      <h1 class="heading-title">
        Account Opening Form
      </h1>
    </div>
    <br />
    <h2 class="h2 mb-4">Account Opening Request</h2>
    <br />
    <div id="content_div">
    <br />
    <h4 class="h2 mb-4">Basic Details</h4>
    <br />
    <table class='table justify-content-center align-items-center mb-4'>
          
          <tr  class='thead'>
            <th><span>Name</span></th>
            <th><span>Email</span></th>
            <th><span>Father Name / Husband Name</span></th>
            <th><span>Mother Name</span></th>
            <th><span>Gender</span></th>
            <th><span>D.O.B</span></th>
            <th><span>Nationality</span></th>
            <th><span>Current Address</span></th>
            <th><span>Phone No</span></th>
          </tr>
        <tbody>
          <tr>
            <td>{{ $user->full_name??'' }}</td>
            <td>{{ $user->email??'' }}</td>
            <td>{{ $user->cust_basic_detail->father_name??'' }}</td>
            <td>{{ $user->cust_basic_detail->mother_name??'' }}</td>
            <td>{{ $user->cust_basic_detail->gender??'' }}</td>
            <td>{{ $user->cust_basic_detail->dob??'' }}</td>
            <td>{{ $user->cust_basic_detail->nationality??'' }}</td>
            <td>{{ $user->cust_basic_detail->current_address??'' }}</td>
            <td>{{ $user->phone_no??''}}</td>
          </tr>
        </tbody>
    </table>
    <table class='table justify-content-center align-items-center mb-4'>
          
          <tr  class='thead'>
            <!-- <th><span>Source of income(old)</span></th> -->
            <th><span>Nominee</span></th>
            <th><span>Nominee CNIC Number</span></th>
            <th><span>Source of Income</span></th>
            <th><span>Occupation</span></th>
            <th><span>City</span></th>
            <th><span>State</span></th>
            <th><span>Refer Code</span></th>
            <th><span>Zakat Deduction</span></th>
            <th><span>Investor Type</span></th>
            <th><span>Dividend Reinvest</span></th>
            <th><span>Account type</span></th>
          </tr>
        <tbody>
          <tr>
            <!-- <td>{{ $user->cust_basic_detail->source_of_income??''}}</td> -->
            <td>{{ $user->cust_basic_detail->nominee_name??'' }}</td>
            <td>{{ $user->cust_basic_detail->nominee_cnic??'' }}</td>
            <td>{{ $user->cust_basic_detail->income_sources['income_name']??'' }}</td>
            <td>{{ $user->cust_basic_detail->occupations['name']??'' }}</td>
            <td>{{ $user->cust_basic_detail->cities['city']??''}}</td>
            <td>{{$user->cust_basic_detail->cities['state']}}</td>
            <td>{{ $user->refer_code??'' }}</td>
            <td>{{ $user->cust_basic_detail['zakat'] == 1 ? "YES" : "NO" }}</td>
            <td>Individual</td>
            <td>Yes</td>
            <td>Sahulat Sarmayakari</td>
          </tr>
        </tbody>
    </table>
    <br/>
    <h4 class="h2 mb-4">CNIC Details</h4>
    <br />
    <table class='table justify-content-center align-items-center mb-4'>
          
          <tr  class='thead'>
            <th><span>CNIC Number</span></th>
            <th><span>CNIC Issue Date</span></th>
            <th><span>CNIC Expiry Date</span></th>
          </tr>
        <tbody>
          <tr>
            <td>{{ $user->cust_cnic_detail->cnic_number??'' }}</td>
            <td>{{ $user->cust_cnic_detail->issue_date??'' }}</td>
            <td>{{ $user->cust_cnic_detail->expiry_date??'' }}</td>
          </tr>
        </tbody>
    </table>
    <br/>
    <h4 class="h2 mb-4">Bank Account Details</h4>
    <br />
    <table class='table justify-content-center align-items-center mb-4'>
          
          <tr  class='thead'>
            <th><span>IBAN</span></th>
            <!-- <th><span>Bank(old)</span></th> -->
            <th><span>Bank Account Number</span></th>
            <th><span>Bank</span></th>
            <th><span>Branch</span></th>
            <th><span>Registered On</span></th>
            <th><span>Profile Status</span></th>
          </tr>
        <tbody>
          <tr>
            <td>{{ $user->cust_bank_detail->iban??'' }}</td>
            <!-- <td>{{ $user->cust_bank_detail->bank??'' }}</td> -->
            <td>{{ $user->cust_bank_detail->bank_account_number??'' }}</td>
            <td>{{ $user->cust_bank_detail->bank??'' }}</td>
            <td>{{ $user->cust_bank_detail->branch??'' }}</td>
            <td>{{ $user->cust_bank_detail->created_at??'' }}</td>
            @if($user->cust_account_detail->status=="0")
            <td>Pending</td>
            @elseif($user->cust_account_detail->status=="1")
            <td>Approved</td>
            @elseif($user->cust_account_detail->status=="2")
            <td>Rejected</td>
            @else
            <td>On Hold</td>
            @endif
          </tr>
        </tbody>
    </table>
    <br />
      @if(count($admin_comments)!=0)
      <table class='table justify-content-center align-items-center mb-4'>
      <tr class='thead'>
        <th><span>#</span></th>
        <th><span>Comment</span></th>
        <th><span>Comment By</span></th>
        <th><span>Comment Date</span></th>
      </tr>
      <tbody>
        @foreach($admin_comments as $index=>$value)
          <tr>
            <td>{{$index+1}}</td>
            <td>{{$value->comment}}</td>
            <td>{{$value->commented_by->full_name}}</td>
            <td>{{date('Y-m-d',strtotime($value->created_at))}}</td>
          </tr>
        @endforeach
      </tbody>
      </table>
      @endif
      <p class="images_div"></p>
      <div>
          <a href="#" class="image" download>
            <img class="img-fluid" src="{{str_starts_with($user->cust_cnic_detail->cnic_front, 'http')?$user->cust_cnic_detail->cnic_front:env('S3_BUCKET_URL').'/'.$user->cust_cnic_detail->cnic_front}}" width="500" height="600">
          </a>
          <br />
          <a href="#" class="image" download>
            <img class="img-fluid" src="{{str_starts_with($user->cust_cnic_detail->cnic_back, 'http')?$user->cust_cnic_detail->cnic_back:env('S3_BUCKET_URL').'/'.$user->cust_cnic_detail->cnic_back}}" width="500" height="600">
          </a>
          <br />
          <a href="#" class="image" download>
            <img class="img-fluid" src="{{str_starts_with($user->cust_cnic_detail->income, 'http')?$user->cust_cnic_detail->income:env('S3_BUCKET_URL').'/'.$user->cust_cnic_detail->income}}" width="500" height="600">
          </a>
      </div>
    </div>
 </div> 
</body>