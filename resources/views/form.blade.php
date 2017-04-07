@extends('layouts.master')	

@push('head')
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.js' type='text/javascript' ></script>
    <script src="{{ asset('js/javascript.js') }}" type='text/javascript'></script>
@endpush

@section('title')
    {{ $year }} Federal Income Tax Estimator
@endsection

@section('content')
    <header class="row-fluid">
        <h1 id="banner"><i class="fa fa-usd red" aria-hidden="true"></i> 
        {{ $year }} Federal Income Tax Estimator <i class="fa fa-usd green"
        aria-hidden="true"></i></h1>
    </header>
 
    <div class="container-fluid">
    	<div id="content" class="row-fluid">
            <form action="taxreturn/{{$year}}/estimate/" method='get' 
            name="taxForm">
                <div class='name-row'>
                    <span class="require-symbol" title="Required">* </span>
                    <input type="text" name="name" class="" id="nameinput"
                    placeholder='Name' value="{{ old('name') }}" required />
                </div>  	
                
                <fieldset>            
                    <legend>Filing Status</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" 
                    aria-hidden="true" id='statusInfoButton' title="More Info">
                    </i>
                    
                    <div class="infoBox" id="statusInfoBox">
                        <h3>Single</h3> 
                        <p>if you are unmarried with no dependents.</p>
                        
                        <h3>Head of Household</h3> 
                        <p>if you are unmarried, have qualified dependents, 
                        and pay for at least 50% of the household bills.</p>
                        
                        <h3>Married Filing Separately</h3>
                        <p>if you are married and you and your spouse are 
                        filing separate, individual tax returns. Spouses 
                        cannot be claimed with this status.</p>
                        
                        <h3>Married Filing Jointly</h3> 
                        <p>if you are married and you are filing a joint 
                        return with your spouse. Spouses are generally claimed 
                        with this status.</p>
                        
                        <h3>Qualified Widow(er)</h3> 
                        <p>if your spouse passed away during the tax year. 
                        This status allows for a higher standard deduction.</p>
                    </div>          
                    
                    <span class="require-symbol" title="Required">* </span>
                    <select name="status" required>
                        <option disabled selected value>Select Your Status</option>
                        <option value="single" {{ old('status') == "single" ? 'SELECTED' : '' }} >Single</option>
                        <option value="head" {{ old('status') == "head" ? 'SELECTED' : '' }} >Head of Household</option>
                        <option value="married1" {{ old('status') == "married1" ? 'SELECTED' : '' }} >Married Filing Separately</option>
                        <option value="married2" {{ old('status') == "married2" ? 'SELECTED' : '' }} >Married Filing Jointly</option>
                        <option value="widow" {{ old('status') == "widow" ? 'SELECTED' : '' }} >Qualified Widow(er)</option>
                    </select>
                </fieldset>

                <fieldset id="exemptions">
                    <legend>Exemptions</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="exemptionsInfoButton" aria-hidden="true" title="More Info"></i>
      
                    <div class="infoBox" id="exemptionsInfoBox">
                        <p> Each exemption and dependent is $4050 off of your pretaxed gross income.</p> 

                        <p>You can claim yourself only if you are not claimed 
                        on someone else's tax return. If you are single and do 
                        not select yourself, your standard deduction will be 
                        adjusted to $1050 to reflect the fact that you are a 
                        dependent on another's return.</p>

                        <p>You can only claim a spouse if your filing status 
                        is "Married Filing Jointly".</p> 

                        <p>Widows cannot claim spouses.</p>
                    </div>
                    <div class='center'>      
                        <div class='ex-col'>
				    		<label><input type="checkbox" id="yourself" class="checkbox" name="yourself" {{ old('yourself') ? 'CHECKED' : '' }} /> Yourself</label>

				    		<label><input type="checkbox" id="spouse" class="checkbox" name="spouse" {{ old('spouse') ? 'CHECKED' : '' }} /> Spouse</label>
				        </div>
					    
                        <div class='ex-col'>
                            <label for="dependents">Dependents</label>
                            <input type="number" name="dependents" id="dependents" class="text" value="{{ old('dependents') }}" placeholder="#" />
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Deduction Adjustments</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="adjustInfoButton" aria-hidden="true" title="More Info"></i>

                    <div class="infoBox" id="adjustInfoBox">
                        <p>Depending upon your filing status, each of the 
                        follow checkboxes results in a higher adjustment of 
                        your standard deduction. The adjustment differs 
                        according to your status.</p>
                    </div>

                    <p><em>Check all that apply</em></p>

                    <div class='center'>
                        <div class='ex-col'>
                            <label><input type="checkbox" id="you65" class="checkbox" name="you65" {{ old('you65') ? 'CHECKED' : '' }} /> You are 65 or older</label>

                            <label><input type="checkbox" id="spouse65" class="checkbox" name="spouse65" {{ old('spouse65') ? 'CHECKED' : '' }}/> Spouse is 65 or older</label>  
                        </div>  
                        
                        <div class='ex-col'>
                            <label><input type="checkbox" id="youBlind" class="checkbox" name="youBlind" {{ old('youBlind') ? 'CHECKED' : '' }}/> You are blind</label>

                            <label><input type="checkbox" id="spouseBlind" class="checkbox" name="spouseBlind" {{ old('spouseBlind') ? 'CHECKED' : '' }}/> Spouse is blind</label>
                        </div>      
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Income</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="incomeInfoButton" aria-hidden="true" title="More Info"></i>

                    <div class="infoBox" id="incomeInfoBox">
                        <p>Your total employment income is typically found on 
                        your W2 Form under Box #1.</p>

                        <p>Include all additional income, including gambling 
                        winnings, interest dividends, 1099 Forms, etc.</p>

                        <p>Your federal income tax withheld is typically found 
                        on your W2 Form under Box #2.</p>
                    </div>
				
                    <label for="income"><span class="require-symbol" title="
                    Required">*</span> Total Wages/Salary:</label>
                    <input type="number" name="income" id="income" placeholder="$" value="{{ old('income') }}" required />
                    <br>

                    <label for="addIncome">Additional Income:</label>
                    <input type="number" name="addIncome" id="addIncome" placeholder="$" value="{{ old('addIncome') }}" />
                    <br>

                    <label for="taxPaid">Federal Taxes Withheld/Paid:</label> 
                    <input type="number" name="taxPaid" id="taxPaid" placeholder="$" value="{{ old('taxPaid') }}" />
                    <br>
                
                </fieldset>
                <fieldset>
                    <legend>Tax Credits</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="creditsInfoButton" aria-hidden="true" title="More Info"></i>                

                    <div class="row-fluid infoBox" id="creditsInfoBox">
                        <p>This estimator does not determine if you are 
                        eligible for tax credits. However, if you are aware of 
                        your eligibility, then insert the amounts below.</p>
                    </div>
					
					<div class="radios">
 	                	<p>Do you qualify for child tax credits?</p>
                    	<label><input type='radio' name='childCredit' value='no' class="radio"  {{ old('childCredit') == "no" ? 'CHECKED' : '' }}  />No</label>
                    	<label><input type='radio' name='childCredit' value='yes' class="radio childYes" {{ old('childCredit') == "yes" ? 'CHECKED' : '' }} />Yes</label>
                	</div>
                    
                    <div class="displayBox" id="childCreditBox">
                        <label for="childCreditAmount"><span class="require-symbol" title="Required">*</span>Child Tax Credit Amount:</label>
                        <input type="number" name="childCreditAmount" placeholder="$" id="childCreditAmount" min='1' value="{{ old('childCreditAmount') }}" />
                    </div>
					
					<div class="radios">
                    	<p>Do you qualify for other tax credits?</p>
                    	<label><input type='radio' name='otherCredit' value='no' class="radio" {{ old('otherCredit') == "no" ? 'CHECKED' : '' }} />No</label>
                    	<label><input type='radio' name='otherCredit' value='yes' class="radio otherYes" {{ old('otherCredit') == "yes" ? 'CHECKED' : '' }} />Yes</label>
                    </div> 

                    <div class="displayBox" id="otherCreditBox"> 
                        <label for="otherCreditAmount"><span class="require-symbol" title="Required">*</span>Additional Tax Credit Amount:</label> 
                        <input type="number" name="otherCreditAmount" placeholder="$" id="otherCreditAmount" min='1' value="{{ old('otherCreditAmount') }}" />
                    </div> 
                </fieldset>

                <fieldset>
                    <legend>Student Deductions</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="studentInfoButton" aria-hidden="true" title="More Info"></i>

                    <div class="row-fluid infoBox" id="studentInfoBox">
                        <p>Any school tuition paid during the tax year can be 
                        deducted without itemizing up to $4000.</p>

                        <p>Any student loan interest paid during the tax year 
                        can be deducted without itemizing up to $2500.</p>

                        <p>There are income limitations. If your status is 
                        "Married Filing Jointly" and your total income exceeds 
                        $160,000, these deductions cannot be made. Likewise, 
                        if you are filing under any other status, the income 
                        limitation is $80,000.</p>
                    </div>
                    
                    <div class="radios">
                    	<p>Did you pay school tuition in 2016?</p>
                   		<label><input type='radio' name='tuition' value='no' class="radio" {{ old('tuition') == "no" ? 'CHECKED' : '' }}/>No</label>
                    	<label><input type='radio' name='tuition' value='yes' class="radio tuitionYes" {{ old('childCredit') == "yes" ? 'CHECKED' : '' }} />Yes</label>
                    </div>
                    
                    <div class="displayBox" id="tuitionBox"> 
                        <label for="tuitionAmount"><span class="require-symbol" title="Required">*</span>Tuition Amount:</label> 
                        <input type="number" placeholder="$" name="tuitionAmount" id="tuitionAmount" value="{{ old('tuitionAmount') }}" />
                    </div>

                    <div class="radios">
						<p>Did you pay any student loan interest in 2016?</p>
                    	<label><input type='radio' name='loanInterest' value='no' class="radio no"  {{ old('loanInterest') == "no" ? 'CHECKED' : '' }}/>No</label>
                    	<label><input type='radio' name='loanInterest' value='yes' class="radio loanYes"  {{ old('loanInterest') == "yes" ? 'CHECKED' : '' }} />Yes</label>
                    </div>

                    <div class="displayBox" id="loanBox"> 
                        <label for="loanAmount"><span class="require-symbol" title="Required">*</span>Loan Interest Amount:</label> 
                        <input type="number" placeholder="$" name="loanAmount" id="loanAmount" value="{{ old('loanAmount') }}" />
                    </div> 
                </fieldset>

                <div class='details'>
                    <p>If your tax situation is beyond what would be 
                    considered simple, it is highly recommended that you 
                    acquire the services of a certified tax accountant to 
                    assist with your tax preparation. This tax estimator 
                    provides a simple estimate with an easy-to-use interface. 
                    It does not take into consideration more complex tax 
                    situations, such as Alternative Minimum Tax (AMT), 
                    Self-Employment Tax, Health Insurace Penalty, or even an 
                    option for itemized deductions. It is merely a way for 
                    those who regularly take the standard deduction on their 
                    taxes to assess their tax situation.</p>
                
                    <label class='text-center'>
                        <input type="checkbox" id="terms" class="checkbox" name="terms" required/> 
                        <span class="require-symbol" title="Required">*</span>
                        <strong>I agree to the terms of use.</strong>
                    </label>

                </div>    
        
                <input type="submit" name="action" value="Submit" class="btn btn-lg"/>
        
            </form>

            @if(count($errors) >0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection

