<?php

class ContactFormHandler {

  function handleContactForm() {
    if($this->isFormSubmitted() && $this->isNonceSet()) {
      if($this->isFormValid()) {
        $this->sendContactForm();
        return true;
      }
      else{
        return false;
      }
    }
    
    return true;
  }
  
  public function sendContactForm() {
    $contactName = sanitize_text_field($_POST['contactname']);
    $contactEmail = sanitize_email($_POST['contactemail']);
    $contactContent = esc_textarea($_POST['contactcontent']);
    
    $emailTo = get_option('admin_email');
    
    $subject = 'New contact from '.$contactName;
    $body = "Contact Name: $contactName \nContact Email: $contactEmail \nContact contents: $contactContent";
    $headers = 'From: '.$contactName.' <'.$contactEmail.'>' . "\n" . 'Reply-To: ' . $contactEmail;
    
    wp_mail($emailTo, $subject, $body, $headers);
    
    echo '<div class="contact-send-status">';
    echo __("Your message was sent. We will reply to it soon.", 'localshop');
    echo '</div>';
  }
  
  function isNonceSet() {
    if( isset( $_POST['nonce_field_for_submit_contact_form'] ) && wp_verify_nonce( $_POST['nonce_field_for_submit_contact_form'], 'submit_contact_form' ) ){
      return true;
    }
    else{
      return false;
    }
  }
  
  function isFormValid() {
    $hasError = false;
    
    // if ( trim( $_POST['contactname'] ) === '' ) {
    //   $error = __('Please enter your name.', 'localshop');
    //   $hasError = true;
    // } 
    // else
    
    if (!filter_var($_POST['contactemail'], FILTER_VALIDATE_EMAIL)  ) {
      $error = __('Please enter a valid email.', 'localshop');
      $hasError = true;
    } else if ( trim( $_POST['contactcontent'] ) === '' ) {
      $error = __('Please enter the message.', 'localshop');
      $hasError = true;
    }
    
    //Check if any error was detected in validation.
    if($hasError) {
      echo '<div class="contact-error">';
      echo $error;
      echo '</div>';
      
      return false;
    }
    
    return true;
  }
  
  function isFormSubmitted() {
    if( isset( $_POST['submitContactForm'] ) ) return true;
    return false;
  }
  
}

?>
