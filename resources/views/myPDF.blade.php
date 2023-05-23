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
		@page { 
      size: 9.5in 11in;
      margin: 100px 50px;
    }
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

h2 { 
  font-family: 'Amarante', Tahoma, sans-serif;
  font-weight: bold;
  font-size: 1.5em;
  line-height: 1.7em;
  margin-bottom: 5px;
  margin-left: 127px;
}

.heading-title {
	margin-top: 30px;
	margin-bottom: 30px;
}

span {
  font-weight: normal;
}


/** page structure **/
#wrapper {
  display: block;
  width: 850px;
  background: #fff;
  margin: 0 auto;
  padding: 10px 17px;
  -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
}

#keywords {
  margin: 0 auto;
  font-size: 1.2em;
  margin-bottom: 15px;
}


#keywords .thead  {
  cursor: pointer;
  background: #c9dff0;
  page-break-inside: avoid;
}
#keywords tr > th { 
  font-weight: bold;
  padding: 12px 30px;
  padding-left: 42px;
}
#keywords tr > th span { 
  padding-right: 20px;
  background-repeat: no-repeat;
  background-position: 100% 100%;
}

#keywords  tr > th.headerSortUp, #keywords  tr > th.headerSortDown {
  background: #acc8dd;
}

#keywords  tr > th.headerSortUp span {
  background-image: url('https://i.imgur.com/SP99ZPJ.png');
}
#keywords  tr > th.headerSortDown span {
  background-image: url('https://i.imgur.com/RkA9MBo.png');
}


#keywords tbody tr { 
  color: #555;
}
#keywords tbody tr td {
  text-align: center;
  padding: 15px 10px;
}
#keywords tbody tr td.lalign {
  text-align: left;
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
			Unit Statement Report
		</h1>
	</div>
	<div class="heading-title">
		<h2>
			Name: <span>{{ $user_details->full_name }}</span>
		</h2>
		<h2>
			CNIC: <span>{{ $cnic_number }}</span>
		</h2>
		<h2>
			Phone: <span>{{ $user_details->phone_no }}</span>
		</h2>
		<h2>
			Email: <span>{{ $user_details->email }}</span>
		</h2>
		<h2>
			Address: <span>{{ $user_details->cust_basic_detail->current_address }}</span>
		</h2>
		<h2>
			Portfolio Amount: <span>Rs {{ $current_balance }}</span>
		</h2>
		<h2>
			Total Available Units: <span>{{ $total_available_units }}</span>
		</h2>
     @if ($from)
      <h2>
        From: <span>{{ $from }}</span>
      </h2>
    @endif
    @if ($to)
      <h2>
        To: <span>{{ $to }}</span>
      </h2>
    @endif
	</div>
  <br />

  <div class="heading-title">
	<h2>Investments</h2>
  </div>
  
  <table id="keywords" cellspacing="0" cellpadding="0">
    
      <tr  class='thead'>
        <th><span>Date</span></th>
        <th><span>Fund Name</span></th>
        <th><span>Units</span></th>
        <th><span>Investment Amount</span></th>
      </tr>
    <tbody>
	@foreach ($investment_data as $investment)
      <tr>
        <td class="lalign">{{ $investment->created_at->format('Y-m-d') }}</td>
        <td>{{ $investment->fund->fund_name }}</td>
        <td>{{ $investment->unit }}</td>
        <td>Rs {{ number_format(floatval($investment->amount)) }}</td>
      </tr>
	@endforeach
	  <tr>
        <td class="lalign"><b>Total</b></td>
        <td></td>
        <td><b>{{ $total_unit_sum }}</b></td>
        <td><b>Rs {{ number_format(floatval($total_invest_amount)) }}</b></td>
      </tr>
    </tbody>
  </table>

  <div class="heading-title">
	<h2>Conversion</h2>
  </div>
  <table id="keywords" cellspacing="0" cellpadding="0">
   
   <tr class='thead'>
     <th><span>Date</span></th>
     <th><span>From Fund Name</span></th>
     <th><span>To Fund Name</span></th>
     <th><span>Units</span></th>
     <th><span>Conversion Amount</span></th>
   </tr>
 <tbody>
  @foreach ($conversion_data as $conversion)
    <tr>
      <td class="lalign">{{ $conversion->created_at->format('Y-m-d') }}</td>
      @if($conversion->type == "investment")
      <td>{{ $conversion->investment->fund->fund_name }}</td>
      @else
      <td>{{ $conversion->parent->fund->fund_name }}</td>
      @endif
      <td>{{ $conversion->fund->fund_name }}</td>
      <td>{{ $conversion->unit }}</td>
      <td>Rs {{ number_format(floatval($conversion->amount)) }}</td>
    </tr>
  @endforeach
  <tr>
      <td class="lalign"><b>Total</b></td>
      <td></td>
      <td></td>
      <td><b>{{ $total_conversion_unit_sum }}</b></td>
      <td><b>Rs {{ number_format(floatval($total_conversion_amount)) }}</b></td>
    </tr>
  </tbody>
  </table>

  <div class="heading-title">
	<h2>Redemptions</h2>
  </div>

  <table id="keywords" cellspacing="0" cellpadding="0">
   
      <tr class='thead'>
        <th><span>Date</span></th>
        <th><span>Fund Name</span></th>
        <th><span>Units</span></th>
        <th><span>Redemption Amount</span></th>
      </tr>
    <tbody>
	@foreach ($redemption_data as $redemption)
      <tr>
        <td class="lalign">{{ $redemption->created_at->format('Y-m-d') }}</td>
        @if($redemption->type == "investment")
        <td>{{ $redemption->investment->fund->fund_name }}</td>
        <td>{{ $redemption->investment->unit }}</td>
        @else
        <td>{{ $redemption->conversion->fund->fund_name }}</td>
        <td>{{ $redemption->conversion->unit }}</td>
        @endif
        <td>Rs {{ number_format(floatval($redemption->redeem_amount)) }}</td>
      </tr>
	@endforeach
	  <tr>
        <td class="lalign"><b>Total</b></td>
        <td></td>
        <td><b>{{ $total_redeem_unit_sum }}</b></td>
        <td><b>Rs {{ number_format(floatval($total_redeem_amount)) }}</b></td>
      </tr>
    </tbody>
  </table>

 </div> 
</body>