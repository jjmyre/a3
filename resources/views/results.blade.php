@extends('layouts.master')

@section('title')
    Federal Income Tax Return Estimate
@endsection

@section('content')
    <header class="row-fluid">
        <h1 id="banner"><i class="fa fa-usd red" aria-hidden="true"></i> Federal Income Tax Return Estimate <i class="fa fa-usd green" aria-hidden="true"></i></h1>
    </header>
    <div class="container-fluid" id="content">
        @if($taxOwed > 0)
            <h2><i class="fa fa-frown-o" aria-hidden="true"></i> Bad news, {{ $name }} </h2>
          
            <h2>You owe the U.S. Government <span class="red">${{ $taxOwed }}</span></h2>
            <br>
            
        @elseif($taxRefund > 0)
            <h2><i class="fa fa-smile-o" aria-hidden="true"></i> Good news, {{ $name }} </h2>

            <h2>The U.S. Government owes you <span class="green">${{ $taxRefund }}</span></h2>
            <br>
            
        @elseif($taxRefund == 0 && $taxOwed == 0)  
            <h2><i class="fa fa-meh-o" aria-hidden="true"></i> 
            Good news, {{ $name }} </h2>

            <h2>You and the U.S. Government broke even for the year.<br>
            There is neither a tax refund nor a tax balance owed.</h2>
            <br>       
        @endif

        <div class="row-fluid results">
            <h3>Here's a breakdown of your Tax Information</h3>
        
            <p><strong>Taxable Income:</strong> 
            ${{ $taxableIncome }}</p>

            <p><strong>Tax Bracket:</strong> 
            {{ $taxBracket }}%</p>
        
            <p><strong>Adjusted Gross Income (AGI):</strong> 
            ${{ $agi }}</p>
     
            <p><strong>Standard Deduction:</strong> 
            ${{ $standardDeduction }}</p>
        
            <p><strong>Exemption Amount:</strong> 
            ${{ $exemptionAmount }}</p>
        
            <p><strong>Student Deduction Amount:</strong> 
            ${{$studentDeduction }}</p> 
        
            <p><strong>Tax Credit Amount:</strong> 
            ${{ $credits }}</p>     
        
            <p><strong>Taxes Previously Paid:</strong> 
            ${{ $taxPaid }}</p>
        
            @if(!empty($taxOwed) && $taxOwed > 0)
                <p><strong>Tax Balance Owed:</strong>
                <span class="red"> ${{ $taxOwed }}</span></p>   
            @elseif(!empty($taxRefund) && $taxRefund > 0)
                <p><strong>Tax Refund:</strong>
                <span class="green"> ${{ $taxRefund }}</span></p> 
            @endif
            
        </div>
    </div>
@endsection
