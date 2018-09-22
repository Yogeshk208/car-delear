<?php /**
 * @author William Sergio Minozzi
 * @copyright 2017
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $aurl = BOATDEALERPLUGINURL . 'includes/contact-form/processForm.php';
$aurl = "#";
$BoatDealer_recipientEmail = trim(get_option('BoatDealer_recipientEmail', ''));
if ( ! is_email($BoatDealer_recipientEmail)) {
        $BoatDealer_recipientEmail = '';
        update_option('BoatDealer_recipientEmail', '');
    }
if (empty($BoatDealer_recipientEmail))
    $BoatDealer_recipientEmail = get_option('admin_email'); ?>
<?php Global $boatdealer_the_title; ?>  
<form id="BoatDealer_contactForm" style="display: none;">
<!-- action="<?php echo $aurl; ?>" method="post"> -->
  <input type="hidden" name="BoatDealer_recipientEmail" id="BoatDealer_recipientEmail" value="<?php echo
$BoatDealer_recipientEmail; ?>" />
  <input type="hidden" name="boatdealer_the_title" id="boatdealer_the_title" value="<?php echo $boatdealer_the_title; ?>" />
  <h2><?php 
  echo __('Request Information', 'boatdealer'); 
  ?>...</h2>
  <ul>
    <li>
      <label for="BoatDealer_senderName" class="BoatDealer_contact" ><?php echo __('Your Name',
'boatdealer'); ?>:&nbsp;</label>
      <input type="text" name="BoatDealer_senderName" id="BoatDealer_senderName" placeholder="<?php echo
__('Please type your name', 'boatdealer'); ?>" required="required" maxlength="40" />
    </li>
    <li>
      <label for="BoatDealer_senderEmail" class="BoatDealer_contact"><?php echo __('Your Email',
'boatdealer'); ?>:&nbsp;</label>
      <input type="email" name="BoatDealer_senderEmail" id="BoatDealer_senderEmail" placeholder="<?php echo
__('Please type your email', 'boatdealer'); ?>" required="required" maxlength="50" />
    </li>
    <li>
      <label for="BoatDealer_sendermessage" class="BoatDealer_contact" style="padding-top: .5em;"><?php echo
__('Your Message', 'boatdealer'); ?>:&nbsp;</label>
      <textarea name="BoatDealer_sendermessage" id="BoatDealer_sendermessage" placeholder="<?php echo
__('Please type your message', 'boatdealer'); ?>" required="required"  maxlength="10000"></textarea>
    </li>
  </ul>
<br />
  <div id="formButtons">
    <input type="submit" id="BoatDealer_sendMessage" name="sendMessage" value="<?php echo
__('Send', 'boatdealer'); ?>" />
    <input type="button" id="BoatDealer_cancel" name="cancel" value="<?php echo __('Cancel',
'boatdealer'); ?>" />
  </div>
<?php  wp_nonce_field('boatdealer_cform'); ?> 
</form>
<div id="BoatDealer_sendingMessage" class="BoatDealer_statusMessage" style="display: none; z-index:999;" ><p>Sending your message. Please wait...</p></div>
<div id="BoatDealer_successMessage" class="BoatDealer_statusMessage" style="display: none;  z-index:999;"><p>Thanks for your message! We'll get back to you shortly.</p></div>
<div id="BoatDealer_failureMessage" class="BoatDealer_statusMessage" style="display: none;  z-index:999;"><p>There was a problem sending your message. Please try again.</p></div>
<div id="BoatDealer_email_error" class="BoatDealer_statusMessage" style="display: none; z-index:999;"><p>Please enter one valid email address.</p></div>
<div id="BoatDealer_incompleteMessage" class="BoatDealer_statusMessage" style="display: none; z-index:999;"><p>Please complete all the fields in the form before sending.</p></div>
<div id="BoatDealer_name_error" class="BoatDealer_statusMessage" style="display: none; z-index:999;"><p>Name Error. Use only alpha.</p></div>
<div id="BoatDealer_message_error" class="BoatDealer_statusMessage" style="display: none; z-index:999;"><p>Message Error. Only Use only alpha and numbers.</p></div>