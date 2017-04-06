<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxEstimateController extends Controller
{
	public function estimate2016(Request $request){
		
        // Defining User Name & Tax Filing Status
        
        $name = $request->input('name');
        $status = $request->input('status');
        $yourself = $request->has('yourself');
        $spouse = $request->has('spouse');
        $you65 = $request->has('you65');
        $spouse65 = $request->has('spouse65');
        $youBlind = $request->has('youBlind');
        $spouseBlind = $request->has('spouseBlind');

        // Determining Standard Deduction Amount

        $standardDeduction = 0;

        if ($status == 'single') { 
            $standardDeduction = 6300;

            if ($youBlind) {
                $standardDeduction += 1550;
            }

            if ($you65) {
                $standardDeduction += 1550;
            }
        } 
        elseif ($status == 'head') {
            $standardDeduction = 9300;
            
            if ($youBlind) {
                $standardDeduction += 1550;
            }
            
            if ($you65) {
                $standardDeduction += 1550;
            }
        } 
        elseif ($status == 'married1') {
            $standardDeduction = 6300;
            
            if ($youBlind) {
                $standardDeduction += 1250;
            }
            
            if ($you65) {
                $standardDeduction += 1250;
            }
        } 
        elseif ($status == 'married2') {
            $standardDeduction = 12600;
            
            if ($youBlind) {
                $standardDeduction += 1250;
            }    
            
            if ($you65) {
            $standardDeduction += 1250;
            }
        
            if ($spouseBlind) {
                $standardDeduction += 1250;
            }
        
            if($spouse65) {
                $standardDeduction += 1250;
            }
        } 
        elseif ($status == 'widow') {
            $standardDeduction = 12600;
            
            if ($youBlind) {
                $standardDeduction += 1250;
            }

            if ($you65) {
                $standardDeduction += 1250;
            }
        }

        // Determining Exemption Amount

        $exemptionNumber = 0;
        $exemptionAmount = 4050;

        if (!$yourself) {
            if($status == 'single') {
                $standardDeduction -= 5250;     
            } 
        }

        if ($yourself) {
            $exemptionNumber += 1;
        }

        if (($spouse) && ($status == 'married2')) {
            $exemptionNumber += 1;
        }

        $dependents = intval($request->input('dependents'));

        $exemptionNumber += $dependents;
        $exemptionAmount *= $exemptionNumber;

        // Determing Adjusted Gross Income & Taxes Previously Paid

        $income = intval($request->input('income'));
        $additionalIncome = intval($request->input('addIncome'));
        $taxPaid = intval($request->input('taxPaid'));
        
        $agi = $income + $additionalIncome;

        // Determing Student Deduction Amount including income limitation
        
        $tuition = $request->get('tuition');
        $loanInterest = $request->get('loanInterest');
        $tuitionAmount = 0;
        $loanAmount = 0;
        $studentDeduction = 0;

        if ($tuition == 'yes') {
            $tuitionAmount = intval($request->input('tuitionAmount'));
        } 
        elseif ($tuition == 'no' xor empty($tuition)) {
            $tuitionAmount = 0;
        }

        if ($loanInterest == 'yes') {
            $loanAmount = intval($request->input('loanAmount'));
        } 
        elseif ($loanInterest == 'no' xor empty($tuition)) {
            $loanAmount = 0;
        }

        if ($loanAmount > 2500) {
            $loanAmount = 2500;
        }

        if($tuitionAmount > 4000) {
            $tuitionAmount = 4000; 
        }

        if (($status == 'married2') &&  ($agi >= 160000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'married1') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'head') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'single') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'widow') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }

        $studentDeduction = $tuitionAmount + $loanAmount; 

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
         
        /* The following for loops extract the given minimum and maximum tax */
        /* brackets from the areas above for each tax status */

        if ($status == 'single') {    
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $singleMax[$i]) && ($taxableIncome >= $singleMin[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $singleMin[$i]) + $singlePrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } elseif ($status == 'head') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $headMax[$i]) && ($taxableIncome >= $headMin[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $headMin[$i]) + $headPrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } elseif ($status == 'married1') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $married1Max[$i]) && ($taxableIncome >= $married1Min[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $married1Min[$i]) + $married1PrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } elseif ($status == 'married2' xor $status == 'widow') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $married2Max[$i]) && ($taxableIncome >= $married2Min[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $married2Min[$i]) + $married2PrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        }

        // Determing Tax Credits from form input.

        $childCredit = $request->get('childCredit');
        $otherCredit = $request->get('otherCredit');
        $childCreditAmount;
        $otherCreditAmount;
        $credits;

        if ($childCredit == 'yes') {
            $childCreditAmount = intval($request->input('childCreditAmount', '0'));
        } elseif (($childCredit == 'no') xor empty($childCredit)) {
            $childCreditAmount = 0;
        }

        if ($otherCredit == 'yes') {
	       $otherCreditAmount = intval($request->get('otherCreditAmount', '0'));;
        } elseif (($otherCredit == 'no') xor empty($otherCredit)) {
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

        // Validation

        $this->validate($request, [
            'name' => 'required|min:3',
            'status' => 'required',
            'dependents' => 'nullable|numeric|min:0|max:15',
            'income' => 'required|numeric|min:1',
            'addIncome' => 'nullable|numeric|min:0',
            'taxPaid' => 'nullable|numeric|min:0',
            'terms' => 'required',
            ]);

        // If statements for validation - if the yes box is checked for each

        if($childCredit == 'yes') {
            $this->validate($request, [
                'childCreditAmount' => 'required|numeric|min:1',
                ]);
        }

        if($otherCredit == 'yes') {
            $this->validate($request, [
                'otherCreditAmount' => 'required|numeric|min:1',
                ]);
        }
        
        if($tuition == 'yes') {
            $this->validate($request, [
                'tuitionAmount' => 'required|numeric|min:1',
                ]);
        }

        if($loanInterest == 'yes') {
            $this->validate($request, [
                'loanAmount' => 'required|numeric|min:1',
                ]);
        }    

        // Return View after form submission with formatted data

        return view('results')->with([
            'name' => $name,
            'taxableIncome' => number_format($taxableIncome),
            'agi' => number_format($agi),
            'standardDeduction' => number_format($standardDeduction),
            'exemptionAmount' => number_format($exemptionAmount),
            'studentDeduction' => number_format($studentDeduction),
            'credits' => number_format($credits),
            'taxBracket' => $taxBracket,
            'taxOwed' => number_format($taxOwed),
            'taxRefund' => number_format($taxRefund),
            'taxPaid' => number_format($taxPaid)
            ]); 
	}
 
    public function estimate2017(Request $request){
        // It should be noted that much of the following logic is repeated
        // from the above function but with different amounts (deduction
        // rates, income brackets, etc.) for the 2017 tax year 

        // Defining User Name & Tax Filing Status
        
        $name = $request->input('name');
        $status = $request->input('status');
        $yourself = $request->has('yourself');
        $spouse = $request->has('spouse');
        $you65 = $request->has('you65');
        $spouse65 = $request->has('spouse65');
        $youBlind = $request->has('youBlind');
        $spouseBlind = $request->has('spouseBlind');

        // Determining Standard Deduction Amount

        $standardDeduction = 0;

        if ($status == 'single') { 
            $standardDeduction = 6350;

            if ($youBlind) {
                $standardDeduction += 1550;
            }

            if ($you65) {
                $standardDeduction += 1550;
            }
        } 
        elseif ($status == 'head') {
            $standardDeduction = 9350;
            
            if ($youBlind) {
                $standardDeduction += 1550;
            }
            
            if ($you65) {
                $standardDeduction += 1550;
            }
        } 
        elseif ($status == 'married1') {
            $standardDeduction = 6350;
            
            if ($youBlind) {
                $standardDeduction += 1250;
            }
            
            if ($you65) {
                $standardDeduction += 1250;
            }
        } 
        elseif ($status == 'married2') {
            $standardDeduction = 12700;
            
            if ($youBlind) {
                $standardDeduction += 1250;
            }    
            
            if ($you65) {
            $standardDeduction += 1250;
            }
        
            if ($spouseBlind) {
                $standardDeduction += 1250;
            }
        
            if($spouse65) {
                $standardDeduction += 1250;
            }
        } 
        elseif ($status == 'widow') {
            $standardDeduction = 12700;
            
            if ($youBlind) {
                $standardDeduction += 1250;
            }

            if ($you65) {
                $standardDeduction += 1250;
            }
        }

        // Determining Exemption Amount

        $exemptionNumber = 0;
        $exemptionAmount = 4050;

        if (!$yourself) {
            if($status == 'single') {
                $standardDeduction -= 5250;     
            } 
        }

        if ($yourself) {
            $exemptionNumber += 1;
        }

        if (($spouse) && ($status == 'married2')) {
            $exemptionNumber += 1;
        }

        $dependents = intval($request->input('dependents'));

        $exemptionNumber += $dependents;
        $exemptionAmount *= $exemptionNumber;

        // Determing Adjusted Gross Income & Taxes Previously Paid

        $income = intval($request->input('income'));
        $additionalIncome = intval($request->input('addIncome'));
        $taxPaid = intval($request->input('taxPaid'));
        
        $agi = $income + $additionalIncome;

        // Determing Student Deduction Amount including income limitation
        
        $tuition = $request->get('tuition');
        $loanInterest = $request->get('loanInterest');
        $tuitionAmount = 0;
        $loanAmount = 0;
        $studentDeduction = 0;

        if ($tuition == 'yes') {
            $tuitionAmount = intval($request->input('tuitionAmount'));
        } 
        elseif ($tuition == 'no' xor empty($tuition)) {
            $tuitionAmount = 0;
        }

        if ($loanInterest == 'yes') {
            $loanAmount = intval($request->input('loanAmount'));
        } 
        elseif ($loanInterest == 'no' xor empty($tuition)) {
            $loanAmount = 0;
        }

        if ($loanAmount > 2500) {
            $loanAmount = 2500;
        }

        if($tuitionAmount > 4000) {
            $tuitionAmount = 4000; 
        }

        if (($status == 'married2') &&  ($agi >= 160000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'married1') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'head') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'single') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }
        
        if (($status == 'widow') && ($agi >= 80000)) {
            $loanAmount = 0;
            $tuitionAmount = 0;
        }

        $studentDeduction = $tuitionAmount + $loanAmount; 

        // Determining Taxable Income

        $taxableIncome = $agi - $standardDeduction - $exemptionAmount - $studentDeduction; 

        if($taxableIncome < 0) {
            $taxableIncome = 0;
        }

        // Determing Tax Bracket and Precredit Tax 

        $preCreditTax = 0;
        $taxBrackets = [ .10, .15, .25, .28, .33, .35, .396];

        $singleMin = [ 0, 9325, 37950, 91900, 191650, 416700, 418400 ];
        $singleMax = [ 9325, 37950, 91900, 191650, 416700, 418400, INF ];
        $singlePrevTax = [ 0, 932.5, 5226.25, 18713.75, 46643.75, 120910.25, 121505.25 ];

        $headMin = [ 0, 13350, 50800, 131200, 212500, 416700, 444550 ];
        $headMax = [ 13350, 50800, 131200, 212500, 416700, 444550, INF ];
        $headPrevTax = [ 0, 1335, 6952.5, 27052.5, 49816.5, 117202.5, 126950 ];

        $married1Min = [ 0, 9325, 37950, 76550, 116675, 208350, 235350 ];
        $married1Max = [ 9325, 37950, 76550, 116675, 208350, 235350, INF ];
        $married1PrevTax = [ 0, 932.5, 5226.25, 14876.25, 26111.25, 56364, 65814 ];

        $married2Min = [ 0, 18650, 75900, 153100, 233350, 416700, 470700 ];
        $married2Max = [ 18650, 75900, 153100, 233350, 416700, 470700, INF ]; 
        $married2PrevTax = [ 0, 1865, 10452.5, 29752.5, 52222.5, 112728, 131628 ];
         
        /* The following for loops extract the given minimum and maximum tax */
        /* brackets from the areas above for each tax status */

        if ($status == 'single') {    
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $singleMax[$i]) && ($taxableIncome >= $singleMin[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $singleMin[$i]) + $singlePrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } elseif ($status == 'head') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $headMax[$i]) && ($taxableIncome >= $headMin[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $headMin[$i]) + $headPrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } elseif ($status == 'married1') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $married1Max[$i]) && ($taxableIncome >= $married1Min[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $married1Min[$i]) + $married1PrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        } elseif ($status == 'married2' xor $status == 'widow') {
            for($i=0; $i<=6; $i++) {  
                if (($taxableIncome < $married2Max[$i]) && ($taxableIncome >= $married2Min[$i])) {
                    $preCreditTax = $taxBrackets[$i] * ($taxableIncome - $married2Min[$i]) + $married2PrevTax[$i];
                    $taxBracket = $taxBrackets[$i] * 100;
                    break;
                }          
            }
        }

        // Determing Tax Credits from form input.

        $childCredit = $request->get('childCredit');
        $otherCredit = $request->get('otherCredit');
        $childCreditAmount;
        $otherCreditAmount;
        $credits;

        if ($childCredit == 'yes') {
            $childCreditAmount = intval($request->input('childCreditAmount', '0'));
        } elseif (($childCredit == 'no') xor empty($childCredit)) {
            $childCreditAmount = 0;
        }

        if ($otherCredit == 'yes') {
           $otherCreditAmount = intval($request->get('otherCreditAmount', '0'));;
        } elseif (($otherCredit == 'no') xor empty($otherCredit)) {
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

        // Validation

        $this->validate($request, [
            'name' => 'required|min:3',
            'status' => 'required',
            'dependents' => 'nullable|numeric|min:0|max:15',
            'income' => 'required|numeric|min:1',
            'addIncome' => 'nullable|numeric|min:0',
            'taxPaid' => 'nullable|numeric|min:0',
            'terms' => 'required',
            ]);

        // If statements for validation - if the yes box is checked for each

        if($childCredit == 'yes') {
            $this->validate($request, [
                'childCreditAmount' => 'required|numeric|min:1',
                ]);
        }

        if($otherCredit == 'yes') {
            $this->validate($request, [
                'otherCreditAmount' => 'required|numeric|min:1',
                ]);
        }
        
        if($tuition == 'yes') {
            $this->validate($request, [
                'tuitionAmount' => 'required|numeric|min:1',
                ]);
        }

        if($loanInterest == 'yes') {
            $this->validate($request, [
                'loanAmount' => 'required|numeric|min:1',
                ]);
        }    

        // Return View after form submission with formatted data

        return view('results')->with([
            'name' => $name,
            'taxableIncome' => number_format($taxableIncome),
            'agi' => number_format($agi),
            'standardDeduction' => number_format($standardDeduction),
            'exemptionAmount' => number_format($exemptionAmount),
            'studentDeduction' => number_format($studentDeduction),
            'credits' => number_format($credits),
            'taxBracket' => $taxBracket,
            'taxOwed' => number_format($taxOwed),
            'taxRefund' => number_format($taxRefund),
            'taxPaid' => number_format($taxPaid)
            ]); 
    }
}