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


        <h1>Here's a breakdown of your 2016 Tax Information</h1>
        
        @if($taxableIncome != null)
            <p><strong>Taxable Income:</strong> {{ $taxableIncome }}</p>
        @endif
        
        @if($agi != null)
            <p><strong>Adjusted Gross Income (AGI):</strong> {{ $agi }}</p>
        @endif
        
        @if($standardDeduction != null)
            <p><strong>Standard Deduction:</strong>{{ $standardDeduction }}</p>
        @endif
        
        @if($exemptionAmount != null)
            <p><strong>Exemption Amount:</strong> {{ $exemptionAmount }}</p>
        @endif
        
        @if($studentDeduction != null)
            <p><strong>Student Deduction Amount:</strong>{{ $studentDeduction }}</p> 
        @endif
        
        @if($credits != null)
            <p><strong>Tax Credit Amount:</strong> {{ $credits }}</p>     
        @endif
        
        @if($taxBracket != null)
            <p><strong>Tax Bracket:</strong> {{ $taxBracket }}</p>
        @endif
        
        @if($taxPaid != null)
            <p><strong>Taxes Previously Paid:</strong> {{ $taxPaid }}</p>
        @endif
        

           

    </div>
</body>

</html>