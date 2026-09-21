<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f2f7ff;
        }
        .card {
            max-width: 500px;
            background: #fff;
            padding: 25px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: green;
        }
        .row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="card">

    <h2>Payment Successful 🎉</h2>

    <div class="row"><span class="label">Name:</span> <?= $payment->name ?></div>
    <div class="row"><span class="label">Transaction ID:</span> <?= $payment->txnid ?></div>
    <div class="row"><span class="label">Status:</span> <?= $payment->status ?></div>
    <div class="row"><span class="label">Amount:</span> ₹<?= $payment->amount ?></div>
    <div class="row"><span class="label">Phone:</span> <?= $payment->phone ?></div>
    <div class="row"><span class="label">Added On:</span> <?= $payment->addedon ?></div>

</div>

</body>
</html>
