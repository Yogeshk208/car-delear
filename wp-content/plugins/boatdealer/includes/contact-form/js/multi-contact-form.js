jQuery(document).ready(function() {
	var messageDelay = 3000;
	jQuery("#BoatDealer_sendMessage").click(function(evt) {
		evt.preventDefault();
		var BoatDealer_contactForm = jQuery(this);
        var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
    	var uemail = jQuery('#BoatDealer_senderEmail').val();
		if (!jQuery('#BoatDealer_senderName').val() || !jQuery('#BoatDealer_senderEmail').val() || !jQuery('#BoatDealer_sendermessage').val()) {
            jQuery('#BoatDealer_incompleteMessage').fadeIn().delay(messageDelay).fadeOut();
            BoatDealer_contactForm.fadeOut().delay(messageDelay).fadeIn();
			// jQuery('#BoatDealer_senderName').css('border', '1px solid red');
            return false;
    	} 
        else if(!re.test(uemail))
        {
              jQuery('#BoatDealer_email_error').fadeIn().delay(messageDelay).fadeOut();
              return false;
        }
  		var uname = jQuery('#BoatDealer_senderName').val();
        var umessage = jQuery('#BoatDealer_sendermessage').val();
        if(!onlyalpha (uname))
        {
           jQuery('#BoatDealer_name_error').fadeIn().delay(messageDelay).fadeOut();
           return false;     
        }
        /*
        if( ! alphanumeric(umessage) )
        {
           jQuery('#BoatDealer_message_error').fadeIn().delay(messageDelay).fadeOut();
           return false; 
        }
        */
        umessage = sanitarize (umessage);
        
        
        
        
        
        
        
        //else {
			jQuery('#BoatDealer_sendingMessage').fadeIn();
			BoatDealer_contactForm.fadeOut();
            var nonce = jQuery('#_wpnonce').val();
            form_content = jQuery('#BoatDealer_contactForm').serialize();
              jQuery.ajax({
                type: "POST",
				url: ajax_object.ajax_url,
				data: form_content + '&action=boatdealer_process_form' + '&security=' + _wpnonce,
				    timeout: 20000,
                    error: function(jqXHR, textStatus, errorThrown) {
                      // alert('errorThrown');
                  		jQuery('#BoatDealer_sendingMessage').hide();
                        BoatDealer_contactForm.fadeIn();
                        alert('Fail to Connect with Data Base (9).\nPlease, try again later.');
                    }, 
                success: submitFinished
			});          
		// }
		return false;
	});
	jQuery(init_BoatDealer_form);
	function init_BoatDealer_form() {
		jQuery('#BoatDealer_contactForm').hide(); //.submit( submitForm ).addClass( 'BoatDealer_positioned' );
		jQuery('#BoatDealer_sendingMessage').hide();
		jQuery('#BoatDealer_successMessage').hide();
		jQuery('#BoatDealer_failureMessage').hide();
		jQuery('#BoatDealer_incompleteMessage').hide();
		jQuery("#BoatDealer_cform").click(function() {
			jQuery('#BoatDealer_cform').hide();
			jQuery('#BoatDealer_contactForm').addClass('BoatDealer_positioned');
			jQuery('#BoatDealer_contactForm').css('opacity', '1');
			jQuery('#BoatDealer_contactForm').fadeIn('slow', function() {
				jQuery('#BoatDealer_senderName').focus();
			})
			return false;
		});
		// When the "Cancel" button is clicked, close the form
		jQuery('#BoatDealer_cancel').click(function() {
			jQuery('#BoatDealer_contactForm').fadeOut();
			jQuery('#content2').fadeTo('slow', 1);
            jQuery("#BoatDealer_cform").fadeIn()
		});
		// When the "Escape" key is pressed, close the form
		jQuery('#BoatDealer_contactForm').keydown(function(event) {
			if (event.which == 27) {
				jQuery('#BoatDealer_contactForm').fadeOut();
				jQuery('#content2').fadeTo('slow', 1);
                jQuery("#BoatDealer_cform").fadeIn()
			}
		});
	}
	function submitFinished(response) {
		response = jQuery.trim(response);
		jQuery('#BoatDealer_sendingMessage').fadeOut();
		if (response == "success") {
			jQuery('#BoatDealer_successMessage').fadeIn().delay(messageDelay).fadeOut();
			jQuery('#BoatDealer_senderName').val("");
			jQuery('#BoatDealer_senderEmail').val("");
			jQuery('#BoatDealer_sendermessage').val("");
			jQuery('#content2').delay(messageDelay + 1000).fadeTo('slow', 1);
			jQuery('#BoatDealer_contactForm').fadeOut();
            jQuery("#BoatDealer_cform").fadeIn()
		} else {
			jQuery('#BoatDealer_failureMessage').fadeIn().delay(messageDelay).fadeOut();
			jQuery('#BoatDealer_contactForm').delay(messageDelay + 1000).fadeIn();
		}
	}
    function alphanumeric(inputtext)
    {
         if( /[^a-zA-Z0-9 ]/.test( inputtext ) ) {
           return false;
         }
        return true;
    }
    
 function sanitarize(str) {
    var map = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        "\"": "&quot;",
        "'": "&#39;" // ' -> &apos; for XML only
    };
    return str.replace(/[&<>"']/g, function(m) { return map[m]; });
}   
    
    
    
    function onlyalpha(inputtext)
    {
     if( /[^a-zA-Z ]/.test( inputtext ) ) {
       return false;
     }
      return true;
    }
    
    
    
}); // end jQuery ready