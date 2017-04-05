<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="shortcut icon" href="images/favicon.png"/>-->
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.js" type="text/javascript" ></script>
    <script src="js/javascript.js" type="text/javascript" ></script>
	<title>2016 Federal Tax Estimator</title>
</head>

<body>
    <header class="row-fluid">
        <h1 id="banner"><i class="fa fa-usd red" aria-hidden="true"></i>2016 Standard Deduction Federal Tax Estimator<i class="fa fa-usd green" aria-hidden="true"></i></h1>
    </header>
    <div class="container-fluid">
    	<div id="content" class="row-fluid">
            <form action="/calculate" method='GET' name="taxForm">
                <div class='name-row'>
                    <span class="require-symbol" title="Required">* </span><input type="text" name="name" class="" id="nameinput" placeholder='Name' value="{{ old('name') }}" required />
                </div>  	
                
                <fieldset id="filing_status">
                    <legend>Filing Status</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" aria-hidden="true" id='statusInfoButton' title="More Info"></i>
                    <div class="infoBox" id="statusInfoBox">
                        <p><strong>Single-</strong> if you are unmarried with no dependents.</p>
                        <p><strong>Head of Household-</strong> if you are unmarried, have qualified dependents, and pay for at least 50% of the household bills.</p>
                        <p><strong>Married Filing Separately-</strong> if you are married and you and your spouse are filing separate, individual tax returns. Spouses cannot be claimed with this status.</p>
                        <p><strong>Married Filing Jointly-</strong> if you are married and you are filing a joint return with your spouse. Spouses are generally claimed with this status.
                        <p><strong>Qualified Widow(er)-</strong> if your spouse passed away during the tax year. This status allows for a higher standard deduction.</p>  
                    </div>
                        <span class="require-symbol" title="Required">* </span><select name="status" required>
                            <option disabled selected value>Select Your Status</option>
                            <option value="single">Single</option>
                            <option value="head">Head of Household</option>
                            <option value="married1">Married Filing Separately</option>
                            <option value="married2">Married Filing Jointly</option>
                            <option value="widow">Qualified Widow(er)</option>
                        </select>
                </fieldset>
                
                <fieldset id="exemptions">
                    <legend>Exemptions</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="exemptionsInfoButton" aria-hidden="true" title="More Info"></i>
      
                    <div class="infoBox" id="exemptionsInfoBox">
                        <p>help is here</p>
                    </div>
                    <div class='center'>
                            
					<div class='ex-col'>
				    		<label><input type="checkbox" id="yourself" class="checkbox" name="yourself"/> Yourself</label>

				    		<label><input type="checkbox" id="spouse" class="checkbox" name="spouse" /> Spouse</label>
				    </div>
					
				    </div>

				    	<label for="dependents"><span class="require-symbol" title="Required">*</span> Dependents:</label>
                    		<input type="number" name="dependents" id="howmany" class="text" value="{{ old('dependents') }}" placeholder="#" />
					
                </fieldset>

                <fieldset id="deductAdjust">

                        <legend>Standard Deduction Adjustments</legend>
                        <div class='center'>
                            <p><em>Check all that apply.</em></p>

                        <div class='ex-col'>
                            <label><input type="checkbox" id="you65" class="checkbox" name="you65"/> You are 65 or older</label>

                            <label><input type="checkbox" id="spouse65" class="checkbox" name="spouse65"/> Spouse is 65 or older</label>    
                        </div>  
                        
                        <div class='ex-col'>
                            <label><input type="checkbox" id="youBlind" class="checkbox" name="youBlind"/> You are blind</label>

                            <label><input type="checkbox" id="spouseBlind" class="checkbox" name="spouseBlind"/> Spouse is blind</label>
                        </div>      

                </fieldset>
    		
                <fieldset>
                    <legend>Income</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="incomeInfoButton" aria-hidden="true" title="More Info"></i>

                    <div class="infoBox" id="incomeInfoBox">
                        <p>help is here</p>
                    </div>
				
                    <label for="income"><span class="require-symbol" title="Required">* </span>Total Wages/Salary:</label> 
                    <input type="number" name="income" id="income" placeholder="$" value="{{ old('name') }}" required /><br>

                    <label for="addIncome">Additional Income:</label> 
                    <input type="number" name="addIncome" id="addIncome" placeholder="$" value="{{ old('addIncome') }}" /><br>

                    <label for="taxPaid">Federal Taxes Withheld/Paid:</label> 
                    <input type="number" name="taxPaid" id="taxPaid" placeholder="$" value="{{ old('taxPaid') }}" /><br>
                
                </fieldset>
            
                <fieldset>
                    <legend>Tax Credits</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="creditsInfoButton" aria-hidden="true" title="More Info"></i>                

                    <div class="row-fluid infoBox" id="creditsInfoBox">
                        <p>help is here</p>
                    </div>
					
					<div class="radios">
 	                	<p>Do you qualify for child tax credits?</p>
                    	<label><input type='radio' name='childCredit' value='no' class="radio" />No</label>
                    	<label><input type='radio' name='childCredit' value='yes' class="radio" />Yes</label><br>
                	</div>
                    
                    <div class="displayBox" id="childCreditBox"> 
                        <label for="childCreditAmount">Child Tax Credit Amount:</label> 
                        <input type="number" name="childCreditAmount" placeholder="$" id="childCreditAmount" min='1' value="{{ old('childCreditAmount') }}" />
                    </div>
					
					<div class="radios">
                    	<p>Do you qualify for any other tax credits?</p>
                    	<label><input type='radio' name='otherCredit' value='no' class="radio no" />No</label>
                    	<label><input type='radio' name='otherCredit' value='yes' class="radio yes" />Yes</label><br>
                    </div> 

                    <div class="displayBox" id="otherCreditBox"> 
                        <label for="otherCreditAmount">Additional Tax Credit Amount:</label> 
                        <input type="number" name="otherCreditAmount" placeholder="$" id="otherCreditAmount" min='1' value="{{ old('otherCreditAmount') }}" />
                    </div> 
            
                </fieldset>

                <fieldset>
                    <legend>Student Deductions</legend>
                    <i class="fa fa-info-circle fa-lg infoButton" id="studentInfoButton" aria-hidden="true" title="More Info"></i>

                    <div class="row-fluid infoBox" id="studentInfoBox">
                        <p>help is here</p>
                    </div>
                    
                    <div class="radios">
                    	<p>Did you pay school tuition in 2016?</p>
                   		<label><input type='radio' name='tuition' value='no' class="radio no"/>No</label>
                    	<label><input type='radio' name='tuition' value='yes' class="radio yes" />Yes</label><br>
                    </div>
                    
                    <div class="displayBox" id="tuitionBox"> 
                        <label for="tuitionAmount">Tuition Amount:</label> 
                        <input type="number" placeholder="$" name="tuitionAmount" id="tuitionAmount" min='1' value="{{ old('tuitionAmount') }}" /><br>
                    </div>

                    <div class="radios">
						<p>Did you pay any student loan interest in 2016?</p>
                    	<label><input type='radio' name='loanInterest' value='no' class="radio no" />No</label>
                    	<label><input type='radio' name='loanInterest' value='yes' class="radio yes" />Yes</label><br>
                    </div>

                    <div class="displayBox" id="loanBox"> 
                        <label for="loanAmount">Loan Interest Amount:</label> 
                        <input type="number" placeholder="$" name="loanAmount" id="loaninterestpaid" min='1' value="{{ old('loanAmount') }}" />
                    </div> 
            
                </fieldset>
            
                <p class="alert alert-warning">If your tax situation is beyond what would be considered simple, it is recommended that you acquire the services of a certified tax accountant or other qualified individual to assist with your tax preparation. This estimator in no way reflects a precise total of your taxes but rather a simple estimate. It does not take into consideration more complex tax situations such as Alternative Minimum Tax (AMT), Self-Employment Tax, Health Insurace Penalty, or an option for itemized deductions. Furthermore, it is assumed that you and your dependents have maintained qualified health insurance, fully paid your Social Security, Medicare and Medicaid taxes, and have no outstanding tax balances or surpluses from previous tax years.</p>

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
</body>

</html>