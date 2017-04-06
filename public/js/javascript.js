

$(document).ready(function(){

    // Hide the info and display boxes on document ready
	
    $('.infoBox').hide();
	
    $('.displayBox').hide();

    // Displays amount inputs, if check, after validation fails

    if($('.loanYes').is(':checked')) { $('#loanBox').show(); }

    if($('.tuitionYes').is(':checked')) { $('#tuitionBox').show(); }

    if($('.otherYes').is(':checked')) { $('#otherCreditBox').show(); }

    if($('.childYes').is(':checked')) { $('#childCreditBox').show(); }

    // Event handlers to show or hide Infoxboxes when icon is clicked
    
	$('#statusInfoButton').click(function() {
		$('#statusInfoBox').toggle(200);
	});

	$('#exemptionsInfoButton').click(function() {
		$('#exemptionsInfoBox').toggle(200);
	});

    $('#adjustInfoButton').click(function() {
        $('#adjustInfoBox').toggle(200);
    });

	$('#incomeInfoButton').click(function() {
		$('#incomeInfoBox').toggle(200);
	});

	$('#creditsInfoButton').click(function() {
		$('#creditsInfoBox').toggle(200);
	});

	$('#studentInfoButton').click(function() {
		$('#studentInfoBox').toggle(200);
	});

	$('#healthInfoButton').click(function() {
		$('#healthInfoBox').toggle(200);
	});

    // Event handlers to show or hide amount inputs

  	$("input[name='childCredit']").click(function() {
    	if ($(this).attr('value') =='no') {
        	$('#childCreditBox').hide();
    	} 
    	else if ($(this).attr('value') == 'yes') {
    		$('#childCreditBox').show();
    	}
    });

    $("input[name='otherCredit']").click(function() {
    	if ($(this).attr('value') =='no') {
        	$('#otherCreditBox').hide();
    	} 
    	else if ($(this).attr('value') == 'yes') {
    		$('#otherCreditBox').show();
    	}
    });

     $("input[name='tuition']").click(function() {
    	if ($(this).attr('value') =='no') {
        	$('#tuitionBox').hide();
    	} 
    	else if ($(this).attr('value') == 'yes') {
    		$('#tuitionBox').show();
    	}
    });

    $("input[name='loanInterest']").click(function() {
    	if ($(this).attr('value') =='no') {
        	$('#loanBox').hide();
    	} 
    	else if ($(this).attr('value') == 'yes') {
    		$('#loanBox').show();
    	}
    });
 
});

