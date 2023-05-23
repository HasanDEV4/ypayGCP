<!DOCTYPE html>
<html>
<head>
    <title>Investment Request Form YPay</title>
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
                    <h2>Investment Request Form</h2>
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
                                    <th>Investor ID</th>
                                    <td>{{ $data->fund->id }}</td>
                                    <th>YPay ID</th>
                                    <td>{{ $data->user->id }}</td>
                                    
                                </tr>
                                <tr><th>Name</th>
                                    <td>{{ $data->user->full_name }}</td>
                                </tr>
                                <tr><th>CNIC #</th>
                                    <td>{{ $data->user->cust_cnic_detail->cnic_number }}</td>
                                </tr>
                                <tr><th>Name of Fund</th>
                                    <td>{{ $data->fund->fund_name }}</td>
                                </tr>
                                <tr><th>Amount (PKR)</th>
                                    <td>{{ $data->amount }}</td>
                                </tr>
                                <tr><th>Type</th>
                                    <td>investment</td>
                                </tr>
                                <tr><th>Mode of Payment</th>
                                    <td></td>
                                </tr>
                                <tr><th>IBAN #</th>
                                    <td></td>
                                </tr>
                                <tr><th>Bank Name</th>
                                    <td></td>
                                </tr>
                                <tr><th>Bank Branch Name</th>
                                    <td></td>
                                </tr>
                                <tr><th>Frequency of Payment</th>
                                    <td></td>
                                </tr>
                                <tr><th>Payment Option</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td></td>
                                </tr>
                                <tr><th>Sales Load</th>
                                    <td></td>
                                </tr>
                                <tr><th>Prove of Payment/Reciept</th>
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

