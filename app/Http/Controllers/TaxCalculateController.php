<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxCalculateController extends Controller
{
	function __invoke(Request $request){
		
// Defining User Name & Tax Filing Status
        $name = $request->get('name');
        $status = $request->get('status');

// Determining Standard Deduction Amount
        
        $standardDeduction = 1050;

        if ($status == 'single') { 
            $standardDeduction = 6300;

            if ($request->has('youBlind')) {
                $standardDeduction += 1550;
            }

            if ($request->has('you65')) {
                $standardDeduction += 1550;
            }
        } elseif ($status == 'head') {
            $standardDeduction = 9300;
            
            if ($request->has('youBlind')) {
                $standardDeduction += 1550;
            }
            
            if ($request->has('you65')) {
                $standardDeduction += 1550;
            }
        } elseif ($status == 'married1') {
            $standardDeduction = 12600;
            
            if ($request->has('youBlind')) {
            $standardDeduction += 1250;
            }
            
            if ($request->has('you65')) {
            $standardDeduction += 1250;
            }
        } elseif ($status == 'married2') {
            $standardDeduction == 6300;
            
            if ($request->has('youBlind')) {
                $standardDeduction += 1250;
            }    
            
            if ($request->has('you65')) {
            $standardDeduction += 1250;
            }
        
            if ($request->has('spouseBlind')) {
                $standardDeduction += 1250;
            }
        
            if($request->has('spouse65')) {
                $standardDeduction += 1250;
            }
        } elseif ($status == 'widow') {
            $standardDeduction == 12600;
            
            if ($request->has('youBlind')) {
                $standardDeduction += 1250;
            }

            if ($request->has('you65')) {
                $standardDeduction += 1250;
            }
        }

// Determining Exemption Amount

        $exemptionNumber = 0;
        $exemptionAmount = 4050;

        if ($request->has('yourself')) {
            $exemptionNumber += 1;
        }

        if ($spouse = $request->has('spouse')) {
            $exemptionNumber =+ 1;
        }

        $dependents = intval($request->get('dependents'));

        $exemptionNumber += $dependents;
        $exemptionAmount *= $exemptionNumber;

// Determing Student Deduction Amount
        
        $tuition = $request->get('tuition');
        $loanInterest = $request->get('loanInterest');
        $tuitionAmount = 0;
        $loanAmount = 0;
        $studentDeduction = 0;

        if ($tuition == 'yes') {
            $tuitionAmount = intval($request->get('tuitionAmount'));
        } elseif ($tuition == 'no') {
            $tuitionAmount = 0;
        }

        if ($loanInterest == 'yes') {
            $loanAmount = intval($request->get('loanAmount'));
        } elseif ($loanInterest == 'no') {
            $loanAmount = 0;
        }

        $studentDeduction = $tuitionAmount + $loanAmount; 

// Determing Adjusted Gross Income

        $income = intval($request->get('income'));
        $additionalIncome = intval($request->get('addIncome'));
        $taxPaid = intval($request->get('taxPaid'));
        $agi = $income + $additionalIncome;

// Determining Taxable Income

        $taxableIncome = $agi - $standardDeduction - $exemptionAmount - $studentDeduction; 
        
        if($taxableIncome <= 0) {
            $taxableIncome = 0;
        }

// Determing Tax Bracket and Precredit Tax 

        $preCreditTax = 0;
        $taxBrackets = [ .1, .15, .25, .28, .33, .35, .396];
        $taxBracket= 0;
        $previousTax = [0];

        $singleBracketMin = [ 0, 9275, 37650, 91150, 190150, 413350, 415050 ];
        $singleBracketMax = [ 9275, 37650, 91150, 190150, 413350, 415050, INF ];
        $singlePreviousTax = [ 0, 927.5, 5183.75, 18558.75, 46278.75, 119934.75, 120529.75];

        $headBracketMax = [ 13250, 50400, 130150, 210800, 413350, 441000 ];
        $headPreviousTax = [ 0, 1325, 6897.5, 26835, 49417, 116258.5, 125936 ];

        $married1BracketMax = [ 9275, 37650, 75950, 115725, 206675, 233475 ];
        $married1PreviousTax = [ 0, 927.5, 5183.75, 14758.75, 25896.75, 55909.25, 65289.25 ];

        $married2BracketMax = [ 18550, 75300, 151900, 231450, 413350, 466950 ]; 
        $married2PreviousTax = [ 0, 1855, 10367.5, 29517.5, 51791.5, 111818.5, 130578.5 ];
var_dump($taxBrackets[3]);

        if ($status = 'single') {
            if ($taxableIncome < $singleBracketMax[0]) {
                $preCreditTax = $taxBrackets[0] * $taxableIncome;
                $taxBracket = $taxBracket[0];
            } 
            else {
                for ($i=1; $i<=6; $i++) {
                    $previousTax[$i] = (($singleBracketMax[$i-1] - $singleBracketMin[$i-1]) * $taxBrackets[$i-1]) + $previousTax[$i-1];
                    if (($taxableIncome < $singleBracketMax[$i]) && ($taxableIncome > $singleBracketMax[$i])) {
                        $preCreditTax = $taxBracket[$i] * ($taxableIncome - $singleBracketMin[$i]);
                    }
                }
            }
        }
        var_dump($previousTax);
  
    /*        
        } elseif ($taxableIncome <= $singleBracketMax[5]) {
                    if ($taxableIncome < $singleBracketMax[$i]) {
                        $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $singleBracketMin[$i]);
                        $preCreditTax += $singlePreviousTax[$i];                 
                    }
                
        } elseif ($status = '$married1') {
            if ($taxableIncome > $married1BracketMax[5]) {
                $taxBracket = $taxBrackets[6];
                $preCreditTax = $taxBracket * ($taxableIncome - $married1BracketMax[5]);
                $preCreditTax += $married1PreviousTax[6]; 
            } elseif ($taxableIncome <= $married1BracketMax[5]) {
                for ($i=0; $i<=5; $i++) {
                    if ($taxableIncome < $married1BracketMax[$i]) {
                        $taxBracket = $taxBracket[$i];
                        $preCreditTax = $taxBracket * ($married1BracketMax[$i] - $taxableIncome);
                        $preCreditTax += $married1PreviousTax[$i];
                    }
                }
            }
        } elseif ($status == 'head') {
            if ($taxableIncome > $headBracketMax[5]) {
                $taxBracket = $taxBrackets[6];
                $preCreditTax = $taxBracket * ($taxableIncome - $headBracketMax[5]);
                $preCreditTax += $headPreviousTax[6]; 
            } elseif ($taxableIncome <= $headBracketMax[5]) {
                for ($i=0; $i<=5; $i++) {
                    if ($taxableIncome < $headBracketMax[$i]) {
                        $taxBracket = $taxBracket[$i];
                        $preCreditTax = $taxBracket * ($headBracketMax[$i] - $taxableIncome);
                        $preCreditTax += $headPreviousTax[$i];
                        break; 
                    }
                }
            }
        } elseif ($status == 'married2' xor $status == 'widow') {
            if ($taxableIncome > $married2BracketMax[5]) {
                $taxBracket = $taxBrackets[6];
                $preCreditTax = $taxBracket * ($taxableIncome - $married2BracketMax[5]);
                $preCreditTax += $singlePreviousTax[6]; 
            } elseif ($taxableIncome <= $married2BracketMax[5]) {
                for ($i=0; $i<=5; $i++) {
                    if ($taxableIncome < $married2BracketMax[$i]) {
                        $taxBracket = $taxBracket[$i];
                        $preCreditTax = $taxBracket * ($married2BracketMax[$i] - $taxableIncome);
                        $preCreditTax += $married2PreviousTax[$i];
                        break; 
                    }
                }
            }
        }
*/
// Determing Tax Credits

        $childCredit = $request->get('childCredit');
        $otherCredit = $request->get('otherCredit');
        $childCreditAmount;
        $otherCreditAmount;
        $credits;

        if ($childCredit == 'yes') {
            $childCreditAmount = intval($request->get('childCreditAMount'));
        } elseif (($childCredit == 'no') xor !$childCredit) {
            $childCreditAmount = 0;
        }

        if ($otherCredit == 'yes') {
	       $otherCreditAmount = intval($request->get('otherCreditAmount'));;
        } elseif (($otherCredit == 'no') xor !$otherCredit) {
	       $otherCreditAmount = 0;
        }

        $credits = $childCreditAmount + $otherCreditAmount;

// Determining Taxes Owed or Tax Refunded

        $taxes = $preCreditTax - $credits;

        $taxFinal = $taxes - $taxPaid; 

        if ($taxFinal > 0) {
            $taxOwed = abs($taxes);
            $taxRefund = 0;
        } elseif ($taxFinal < 0) {
            $taxRefund = abs($taxes);
            $taxOwed = 0;
        }

// Validation and Return View

        return view('results')->with([
            'name' => $name,
          //  'taxRefund' => $taxRefund,
         //   'taxOwed' => $taxOwed,
            'taxableIncome' => $taxableIncome,
            'agi' => $agi,
            'standardDeduction' => $standardDeduction,
            'exemptionAmount' => $exemptionAmount,
            'studentDeduction' => $studentDeduction,
            'credits' => $credits,
            'taxBracket' => $taxBracket,
            'taxPaid' => $taxPaid
        ]);
	}
}
 