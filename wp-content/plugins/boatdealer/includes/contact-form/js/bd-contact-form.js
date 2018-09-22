var messageDelay = 3000;  
jQuery( init_bd_form );
   
function init_bd_form() {

   jQuery('#bd_contactForm').hide().submit( submitForm ).addClass( 'bd_positioned' );
   
   jQuery('#sendingMessage').hide(); 
   jQuery('#successMessage').hide(); 
   jQuery('#failureMessage').hide();     
   jQuery('#incompleteMessage').hide();   
  
   jQuery("#bd_cform").click( function() {
   jQuery('#content2').fadeTo( 'slow', .2 );
  
     
    jQuery('#bd_contactForm').fadeIn( 'slow', function() {
      jQuery('#bd_senderName').focus();
    } )
   

    return false;
  } );
  
  // When the "Cancel" button is clicked, close the form
  jQuery('#bd_cancel').click( function() { 
    jQuery('#bd_contactForm').fadeOut();
    jQuery('#content2').fadeTo( 'slow', 1 );
  } );  

  // When the "Escape" key is pressed, close the form
  jQuery('#bd_contactForm').keydown( function( event ) {
    if ( event.which == 27 ) {
      jQuery('#bd_contactForm').fadeOut();
      jQuery('#content2').fadeTo( 'slow', 1 );
    }
  } );

}


// Submit the form via Ajax

function submitForm() {
  var bd_contactForm = jQuery(this);

  // Are all the fields filled in?

  if ( !jQuery('#bd_senderName').val() || !jQuery('#bd_senderEmail').val() || !jQuery('#bd_sendermessage').val() ) {


    // No; display a warning message and return to the form
    jQuery('#incompleteMessage').fadeIn().delay(messageDelay).fadeOut();
    bd_contactForm.fadeOut().delay(messageDelay).fadeIn();

  } else {

    jQuery('#sendingMessage').fadeIn();
    bd_contactForm.fadeOut();
    
    

    jQuery.ajax( {
      url: bd_contactForm.attr( 'action' ) + "?ajax=true",
      type: bd_contactForm.attr( 'method' ),
      data: bd_contactForm.serialize(),
      success: submitFinished
    } );
  }

  // Prevent the default form submission occurring
  return false;
}


// Handle the Ajax response

function submitFinished( response ) {
  response = jQuery.trim( response );

  
  jQuery('#sendingMessage').fadeOut();

  if ( response == "success" ) {

    // Form submitted successfully:
    // 1. Display the success message
    // 2. Clear the form fields
    // 3. Fade the content back in

    jQuery('#successMessage').fadeIn().delay(messageDelay).fadeOut();
    jQuery('#bd_senderName').val( "" );
    jQuery('#bd_senderEmail').val( "" );
    jQuery('#bd_sendermessage').val( "" );

    jQuery('#content2').delay(messageDelay+1000).fadeTo( 'slow', 1 );

  } else {

    // Form submission failed: Display the failure message,
    // then redisplay the form
    jQuery('#bd_failureMessage').fadeIn().delay(messageDelay).fadeOut();
    jQuery('#bd_contactForm').delay(messageDelay+1000).fadeIn();
  }
}
