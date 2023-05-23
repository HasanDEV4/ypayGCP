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
  margin-left: 0 !important;
}
.heading-title {
	margin-left:70%;
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
  width: 60%;
  background: #fff;
  margin: 4%;
  -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
}

.table{
  left:0;
  border:1px solid black;
}
.table .thead  {
  color:white;
  border:1px solid black;
  background: #007bff;
}
.table td{
  border:1px solid black;
  font-size: 20px;
}
.table th{
  border:0.5px solid black;
  font-size: 22px;
}


#company-logo {
  position:absolute;
  left:0;
  top:0;
  margin: 4%;
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
	<div>
    <img id="company-logo" src="http://35.90.107.89/images/ypay.png" alt="" width="80"> 
    <p id="current-date">
      <b>{{ date('j F, Y') }}</b>
    </p>
		<h1 class="heading-title">
    Conversion Forms
		</h1>
	</div>
  <br />
	<h2 class="h2 mb-4">Conversions Requests</h2>
  <br />
  <div id="content_div">
    <table class='table justify-content-center align-items-center mb-4'>
      <tr class='thead align-items-center'>
        <th colspan="11">Request for Conversion</th>
      </tr>
      <tr class='thead'>
        <th><span>No.</span></th>
        <th><span>Reference No.</span></th>
        <th><span>Principal Account Holder</span></th>
        <th><span>CNIC</span></th>
        <th><span>Registeration No.</span></th>
        <th><span>Conversion from Fund</span></th>
        <th><span>No of Units</span></th>
        <th><span>Value (Rs)</span></th>
        <th><span>Conversion into Fund</span></th>
        <th><span>Sales Load</span></th>
        <th><span>Sales / Distributor / Facilitator</span></th>
      </tr>
      <tbody>
        @foreach ($filtered_conversions as $index=>$conversion)
          <tr>
            <td>{{$index+1}}</td>
            <td>{{$conversion->amc_reference_number??''}}</td>
            <td>{{strtoupper($conversion->user->full_name)??""}}</td>
            <td>{{$conversion->user->cust_cnic_detail->cnic_number??""}}</td>
            <td>{{$amc_profiles[$index]??""}}</td>
            @if($conversion->type=="investment")
            <td>{{$conversion->investment->fund->fund_name??''}}</td>
            @else
            <td>{{$conversion->parent->fund->fund_name??''}}</td>
            @endif
            <!-- <td>{{$conversion->unit??''}}</td> -->
            <td>ALL</td>
            <td>{{$conversion->amount??''}}</td>
            <td>{{$conversion->fund->fund_name??''}}</td>
            <td>{{$conversion->fund->additional_details->fund_ratings??''}}</td>
            <td>YPAY Financial Services</td>
          </tr>
        @endforeach
      </tbody>
    </table>
</div>
 </div> 
</body>