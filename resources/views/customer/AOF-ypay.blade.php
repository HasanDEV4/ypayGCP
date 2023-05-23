<!DOCTYPE html>
<html>
<head>
    <title>Account Opening From YPay</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<style type="text/css">
    h2{
        text-align: center;
        font-size:22px;
        margin-bottom:50px;
    }
    body{
        background:#f2f2f2;
    }
    .section{
        margin-top:30px;
        padding:50px;
        background:#fff;
    }
    .pdf-btn{
        margin-top:30px;
    }
</style>  
<body>
	<div class="container">
        <div class="col-md-8 section offset-md-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2>Account Opening Form</h2>
                </div>
                <div class="panel-body">
                    <div class="main-div">
                        <table class="mb-0 table table-bordered">
                            <thead>
                                <tr>
                                    <th>Dated</th>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>YPay ID</th>
                                    <td>{{ $data->id }}</td>
                                    <th>Email</th>
                                    <td>{{ $data->email }}</td>
                                </tr>
                                <tr><th>Name</th>
                                    <td>{{ $data->full_name }}</td>
                                </tr>
                                <tr><th>Father's Name</th>
                                    <td>{{ $data->cust_basic_detail->father_name }}</td>
                                </tr>
                                <tr><th>Phone</th>
                                    <td>{{ $data->phone_no }}</td>
                                </tr>
                                <tr><th>Date of Birth</th>
                                    <td>{{ $data->cust_basic_detail->dob }}</td>
                                </tr>
                                <tr><th>Address</th>
                                    <td>{{ $data->cust_basic_detail->current_address }}</td>
                                </tr>
                                <tr><th>City</th>
                                    <td>{{ $data->cust_basic_detail->cities->city }}</td>
                                </tr>
                                <tr><th>State</th>
                                    <td>{{ $data->cust_basic_detail->cities->state }}</td>
                                </tr>
                                <tr><th>Country</th>
                                    <td>{{ $data->cust_basic_detail->cities->country }}</td>
                                </tr>
                                <tr><th>CNIC #</th>
                                    <td>{{ $data->cust_cnic_detail->cnic_number }}</td>
                                </tr>
                                <tr><th>CNIC Copy</th>
                                    <td>{{ $data->cust_cnic_detail->cnic_front }}.''.{{ $data->cust_cnic_detail->cnic_back }}</td>
                                </tr>
                                <tr><th>CNIC Issue Date</th>
                                    <td>{{ $data->cust_cnic_detail->issue_date }}</td>
                                </tr>
                                <tr><th>CNIC Expiry Date</th>
                                    <td>{{ $data->cust_cnic_detail->expiry_date }}</td>
                                </tr>
                                <tr><th>Bank Name</th>
                                    <td>{{ $data->cust_bank_detail->bank }}</td>
                                </tr>
                                <tr><th>IBAN #</th>
                                    <td>{{ $data->cust_bank_detail->iban }}</td>
                                </tr>
                                <tr><th>Bank Branch Name</th>
                                    <td>{{ $data->cust_bank_detail->branch }}</td>
                                </tr>
                                <tr><th>Zakat Deduction</th>
                                    <td></td>
                                </tr>
                                <tr><th>Return Statement</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Reinvesting Dividends</th>
                                    <td></td>
                                </tr>
                                <tr><th>Investor Type</th>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p><strong>*Note</strong> All information and attachments have been provided by the Customer to the YPay Platform and YPay 
                    has done primary screening to remove/filter garbage and/or irrelevant information. </p>
            </div>
        </div>
    </div>
</body>
</html>

