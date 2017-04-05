@extends('layouts.master')

@section('title')
    Federal Tax Estimator
@endsection

@section('content')
    <header class="row-fluid">
        <h1 id="banner"><i class="fa fa-usd red" aria-hidden="true"></i> Federal Tax Estimator Results <i class="fa fa-usd green" aria-hidden="true"></i></h1>
    </header>
    <div class="container-fluid">
        <div class="row-fluid" id="content">

            @if($taxOwed > 0)
                <h2><i class="fa fa-frown-o" aria-hidden="true"></i> Bad news, {{ $name }} <i class="fa fa-frown-o" aria-hidden="true"></i><br> 
                You owe the U.S. Government <span class="red">${{ $taxOwed }}</span></h2>
                <br>'
            
            @elseif($taxRefund > 0)
                <h2><i class="fa fa-smile-o" aria-hidden="true"></i> Good news, {{ $name }} <i class="fa fa-smile-o" aria-hidden="true"></i> <br> 
                The U.S. Government owes you <span class="green">${{ $taxRefund }}</span></h2>
                <br>
            
            @elseif($taxRefund == 0 && $taxOwed == 0)  
                <h2><i class="fa fa-meh-o" aria-hidden="true"></i> Good news, {{ $name }} <i class="fa fa-meh-o" aria-hidden="true"> <br>
                You and the U.S. Government broke even for the year. There is neither a tax refund nor a tax balance owed.</h2>
                <br>
            
            @endif

            <h3>Here's a breakdown of your Tax Information</h3>
        
            <p><strong>Taxable Income:</strong> ${{ $taxableIncome }}</p>

            <p><strong>Tax Bracket:</strong> {{ $taxBracket or ''}}%</p>
        
            <p><strong>Adjusted Gross Income (AGI):</strong> ${{ $agi or '' }}</p>
     
            <p><strong>Standard Deduction:</strong> ${{ $standardDeduction or '' }}</p>
        
            <p><strong>Exemption Amount:</strong> ${{ $exemptionAmount or '' }}</p>
        
            <p><strong>Student Deduction Amount:</strong> ${{ $studentDeduction or '' }}</p> 
        
            <p><strong>Tax Credit Amount:</strong> ${{ $credits or '' }}</p>     
        
            <p><strong>Taxes Previously Paid:</strong> ${{ $taxPaid or '' }}</p>
        
            @if(!empty($taxOwed) && $taxOwed > 0)
                <p><strong>Tax Balance Owed:</strong><span class="red"> ${{ $taxOwed or '' }}</span></p>   
            @elseif(!empty($taxRefund) && $taxRefund > 0)
                <p><strong>Tax Refund:</strong><span class="green"> ${{ $taxRefund or '' }}</span></p> 
            @endif
        </div>
    </div>
@endsection
