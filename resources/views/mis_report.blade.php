      <table>
        <thead>
        <tr>
         <th colspan="13" style="background:blue;color:white;text-align:center;">Account Opening</th>
        </tr>
        <tr>
          <th>Serial Number</th>
          <th>Unique Reference No</th>
          <th>Date and Time</th>
          <th>Investor Name</th>
          <th>FatherName</th>
          <th>CNIC No</th>
          <th>Bank IBAN</th>
          <th>Bank Name</th>
        </tr>
        </thead>
        <tbody>
          @if(count($data['acc_opening_data'])!=0)
            @foreach ($data['acc_opening_data'] as $acc_opening_data)  
              <tr>
                <td>{{$acc_opening_data['srno']}}</td>
                <td>{{$acc_opening_data['ref_no']}}</td>
                <td>{{$acc_opening_data['date']}}</td>
                <td>{{$acc_opening_data['cust_name']}}</td>
                <td>{{$acc_opening_data['father_name']}}</td>
                <td>{{$acc_opening_data['cnic_no']}}</td>
                <td>{{$acc_opening_data['iban']}}</td>
                <td>{{$acc_opening_data['bank_name']}}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
    </table>
    <br>
    <table>
        <thead>
        <tr>
         <th colspan="15" style="background:green;color:white;text-align:center;">Purchase Applications</th>
        </tr>
        <tr>
          <th>Serial Number</th>
          <th>CNIC</th>
          <th>Investor Name</th>
          <th>Folio Number</th>
          <th>Fund Short Code</th>
          <th>Mutual Fund Name</th>
          <th>From Account</th>
          <th>Amount</th>
          <th>To Account</th>
          <th>Transaction Reference</th>
          <th>Transaction ID</th>
          <th>Stamp Date</th>
          <th>Status</th>
          <th>Fund Unit Class</th>
          <th>Fund Unit Type</th>
        </tr>
        </thead>
        <tbody>
          @if(count($data['purchase_app_data'])!=0)
            @foreach ($data['purchase_app_data'] as $purchase_app_data)  
              <tr>
                <td>{{$purchase_app_data['srno']}}</td>
                <td>{{$purchase_app_data['cnic']}}</td>
                <td>{{$purchase_app_data['cust_name']}}</td>
                <td>{{$purchase_app_data['folio_no']}}</td>
                <td>{{$purchase_app_data['fund_short_code']}}</td>
                <td>{{$purchase_app_data['fund_name']}}</td>
                <td>{{$purchase_app_data['from_account']}}</td>
                <td>{{$purchase_app_data['amount']}}</td>
                <td>{{$purchase_app_data['to_account']}}</td>
                <td>{{$purchase_app_data['trx_reference']}}</td>
                <td>{{$purchase_app_data['trx_id']}}</td>
                <td>{{$purchase_app_data['stamp_date']}}</td>
                <td>{{$purchase_app_data['status']}}</td>
                <td>{{$purchase_app_data['fund_unit_class']}}</td>
                <td>{{$purchase_app_data['fund_unit_type']}}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
    </table>
    <br>
    <table>
        <thead>
        <tr>
         <th colspan="12" style="background:orange;color:white;text-align:center;">Redemption Applications</th>
        </tr>
        <tr>
          <th>Serial Number</th>
          <th>CNIC</th>
          <th>Folio Number</th>
          <th>Investor Name</th>
          <th>Fund Name</th>
          <th>FundUnitClass</th>
          <th>Redeem Amount</th>
          <th>Redeem By</th>
          <th>Redeem Units</th>
          <th>Transaction Reference</th>
          <th>Transaction ID</th>
          <th>Stamp Date</th>
        </tr>
        </thead>
        <tbody>
          @if(count($data['redemptions_data'])!=0)
            @foreach ($data['redemptions_data'] as $redemptions_data)  
              <tr>
                <td>{{$redemptions_data['srno']}}</td>
                <td>{{$redemptions_data['cnic']}}</td>
                <td>{{$redemptions_data['folio_no']}}</td>
                <td>{{$redemptions_data['cust_name']}}</td>
                <td>{{$redemptions_data['fund_name']}}</td>
                <td>{{$redemptions_data['fund_unit_class']}}</td>
                <td>{{$redemptions_data['redeem_amount']}}</td>
                <td>{{$redemptions_data['redeem_by']}}</td>
                <td>{{$redemptions_data['redeem_units']}}</td>
                <td>{{$redemptions_data['trx_reference']}}</td>
                <td>{{$redemptions_data['trx_id']}}</td>
                <td>{{$redemptions_data['stamp_date']}}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
    </table>