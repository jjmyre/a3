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

        if ($status == 'single') { 
            $standardDeduction = 6300;

            if ($request->has('youBlind')) {
                $standardDeduction += 1550;
            }

            if ($request->has('you65')) {
                $standardDeduction += 1550;
            }
        } 
        elseif ($status == 'head') {
            $standardDeduction = 9300;
            
            if ($request->has('youBlind')) {
                $standardDeduction += 1550;
            }
            
            if ($request->has('you65')) {
                $standardDeduction += 1550;
            }
        } 
        elseif ($status == 'married1') {
            $standardDeduction = 12600;
            
            if ($request->has('youBlind')) {
            $standardDeduction += 1250;
            }
            
            if ($request->has('you65')) {
            $standardDeduction += 1250;
            }
        } 
        elseif ($status == 'married2') {
            $standardDeduction = 6300;
            
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
        } 
        elseif ($status == 'widow') {
            $standardDeduction = 12600;
            
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

        if (!$request->has('yourself')) {
            if($status == 'single') {
                $standardDeduction -= 5250;     
            } 
            else {
                $standardDeduction = 0;
            }
        }

        if ($request->has('yourself')) {
            $exemptionNumber += 1;
        }

        if ($spouse = $request->has('spouse')) {
            $exemptionNumber += 1;
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
        } 
        elseif ($tuition == 'no' xor empty($tuition)) {
            $tuitionAmount = 0;
        }

        if ($loanInterest == 'yes') {
            $loanAmount = intval($request->get('loanAmount'));
        } 
        elseif ($loanInterest == 'no' xor empty($tuition)) {
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
        
        if($taxableIncome < 0) {
            $taxableIncome = 0;
        }

// Determing Tax Bracket and Precredit Tax 

        $preCreditTax = 0;
        $taxBrackets = [ .10, .15, .25, .28, .33, .35, .396];

        $singleMin = [ 0, 9275, 37650, 91150, 190150, 413350, 415050 ];
        $singleMax = [ 9275, 37650, 91150, 190150, 413350, 415050, INF ];
        $singlePrevTax = [ 0, 927.5, 5183.75, 18558.75, 46278.75, 119934.75, 120529.75];

        $headMin = [ 0, 13250, 50400, 130150, 210800, 413350, 441000 ];
        $headMax = [ 13250, 50400, 130150, 210800, 413350, 441000, INF ];
        $headPrevTax = [ 0, 1325, 6897.5, 26835, 49417, 116258.5, 125936 ];

        $married1Min = [ 0, 9275, 37650, 75950, 115725, 206675, 233475 ];
        $married1Max = [ 9275, 37650, 75950, 115725, 206675, 233475, INF ];
        $married1PrevTax = [ 0, 927.5, 5183.75, 14758.75, 25895.75, 55909.25, 65289.25 ];

        $married2Min = [ 0, 18550, 75300, 151900, 231450, 413350, 466950];
        $married2Max = [ 18550, 75300, 151900, 231450, 413350, 466950, INF ]; 
        $married2PrevTax = [ 0, 1855, 10367.5, 29517.5, 51791.5, 111818.5, 130578.5 ];

         
        if ($status == 'single') {    
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $singleMax[$i]) && ($taxableIncome >= $singleMin[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $singleMin[$i]) + $singlePrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } 
        elseif ($status == 'head') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $headMax[$i]) && ($taxableIncome >= $headMin[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $headMin[$i]) + $headPrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } 
        elseif ($status == 'married1') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $married1Max[$i]) && ($taxableIncome >= $married1Min[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $married1Min[$i]) + $married1PrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } 
        elseif ($status == 'married2' xor $status == 'widow') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $married2Max[$i]) && ($taxableIncome >= $married2Min[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $married2Min[$i]) + $married2PrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        }

// Determing Tax Credits

        $childCredit = $request->get('childCredit');
        $otherCredit = $request->get('otherCredit');
        $childCreditAmount;
        $otherCreditAmount;
        $credits;

        if ($childCredit == 'yes') {
            $childCreditAmount = intval($request->get('childCreditAmount'));
        } 
        elseif (($childCredit == 'no') xor empty($childCredit)) {
            $childCreditAmount = 0;
        }

        if ($otherCredit == 'yes') {
	       $otherCreditAmount = intval($request->get('otherCreditAmount'));;
        } 
        elseif (($otherCredit == 'no') xor empty($otherCredit)) {
	       $otherCreditAmount = 0;
        }

        $credits = $childCreditAmount + $otherCreditAmount;

// Determining Taxes Owed or Tax Refunded

        $taxes = $preCreditTax - $credits;
        $taxFinal = $taxes - $taxPaid; 

        $taxRefund = 0;
        $taxOwed = 0;

        if ($taxFinal > 0) {
            $taxOwed = abs($taxFinal);
            $taxOwed = round($taxOwed);     
        }
        
        if ($taxFinal < 0) {
            $taxRefund = abs($taxFinal);
            $taxRefund = round($taxRefund);
        }

// Validation and Return View

        $this->validate($request, [
            'name' => 'required|alpha|min:3',
            'status' => 'required',
            'income' => 'required|numeric',
            'addIncome' => 'numeric',
            'taxPaid' => 'numeric',
            'dependents' => 'required|numeric',
            ]);

        return view('results')->with([
            'name' => $name,
            'taxableIncome' => $taxableIncome,
            'agi' => $agi,
            'standardDeduction' => $standardDeduction,
            'exemptionAmount' => $exemptionAmount,
            'studentDeduction' => $studentDeduction,
            'credits' => $credits,
            'taxBracket' => $taxBracket,
            'taxOwed' => $taxOwed,
            'taxRefund' => $taxRefund,
            'taxPaid' => $taxPaid
            ]); 
	}
}
 