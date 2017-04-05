<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="images/favicon.png"/>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript" ></script>
	<title>2016 Tax Estimator</title>
</head>

<body>
	<h1>2016 FEDERAL TAX RESULTS</h1>
    
    <div class="container-fluid">

        @if($taxOwed > 0)

            <h2><i class="fa fa-frown-o" aria-hidden="true"></i> Bad news, <span class="blue">{{ $name }}</span>. It looks like you owe the U.S. Government <span class="red">${{ $taxOwed }}</span></h2>

        @elseif($taxRefund > 0)

            <h2><i class="fa fa-smile-o" aria-hidden="true"></i> Good news, <span class="blue">{{ $name }}</span>. It looks like the U.S. Government owes you <span class="green">${{ $taxRefund }}</span></h2>

        @elseif($taxRefund == 0 && $taxOwed == 0)  

            <h2><i class="fa fa-meh-o" aria-hidden="true"></i> Good news, <span class="blue">{{ $name }}</span>. It looks like you and the U.S. Government broke even for the year. There is neither a tax refund nor a tax balance owed for the year.</h2>

        @endif


        <h3>Here's a breakdown of your Tax Information</h3>
        
        <p><strong>Taxable Income:</strong> ${{ $taxableIncome }}</p>

        <p><strong>Tax Bracket:</strong> {{ $taxBracket }}%</p>
        
        <p><strong>Adjusted Gross Income (AGI):</strong> ${{ $agi }}</p>
     
        <p><strong>Standard Deduction:</strong> ${{ $standardDeduction }}</p>
        
        <p><strong>Exemption Amount:</strong> ${{ $exemptionAmount }}</p>
        
        <p><strong>Student Deduction Amount:</strong> ${{ $studentDeduction }}</p> 
        
        <p><strong>Tax Credit Amount:</strong> ${{ $credits }}</p>     
        
        <p><strong>Taxes Previously Paid:</strong> ${{ $taxPaid }}</p>
        
        @if($taxOwed > 0)

            <p><strong>Tax Balance Owed:</strong><span class="red"> ${{ $taxOwed }}</span></p>   

        @elseif($taxRefund > 0)

            <p><strong>Tax Refund:</strong><span class="green"> ${{ $taxRefund }}</span></p> 

        @endif

    </div>
</body>

</html>