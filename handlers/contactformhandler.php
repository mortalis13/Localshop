<?php

class ContactFormHandler {
 
    function handleContactForm() {
        if($this->isFormSubmitted() && $this->isNonceSet()) {
            if($this->isFormValid()) {
                $this->sendContactForm();
                $this->displayContactForm();
            } 
            else {
              $this->displayContactForm();
            }
        } 
        else {
            $this->displayContactForm();
        }
    }
    
    //This function displays the Contact form.
    public function displayContactForm() {
    ?>
      <div id ="contactFormSection">
        <form action="" id="contactForm" method="POST" enctype="multipart/form-data">
          <fieldset>
            <label for="contactname"><?=__('Name', 'localshop')?></label>
            <input type="text" name="contactname" id="contactname" />
          </fieldset>

          <fieldset>
            <label for="contactemail"><?=__('Email', 'localshop')?> <span class="required">*</span></label>
            <input type="text" name="contactemail" id="contactemail" />
          </fieldset>
        
          <fieldset>
            <label for="contactcontent"><?=__('Message', 'localshop')?> <span class="required">*</span></label>
            <textarea name="contactcontent" id="contactcontent" rows="10" cols="35" ></textarea>
          </fieldset>
        
          <fieldset>
            <button id="contact-submit" type="submit" name="submitContactForm"><?=__('Send', 'localshop')?></button>
          </fieldset>

          <?php wp_nonce_field( 'submit_contact_form' , 'nonce_field_for_submit_contact_form'); ?>
        </form>
      </div>
    <?php
    }
    
    public function sendContactForm() {
        $contactName = $_POST['contactname'] ;
        $contactEmail = $_POST['contactemail'];
        $contactContent = $_POST['contactcontent'];
 
        $emailTo = get_option('admin_email');
 
        $subject = 'New contact from '.$contactName;
        $body = "Contact Name: $contactName \n\nContact Email: $contactEmail \n\nContact contents: $contactContent";
        $headers = 'From: '.$contactName.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $contactEmail;
 
        wp_mail($emailTo, $subject, $body, $headers);

        echo '<div class="contact-send-status">';
        echo __("Your message was sent. We will reply to it soon.", 'localshop');
        echo '</div>';
    }
     
    function isNonceSet() {
        if( isset( $_POST['nonce_field_for_submit_contact_form'] )  &&
          wp_verify_nonce( $_POST['nonce_field_for_submit_contact_form'], 'submit_contact_form' ) ) return true;
        else return false;
    }
     
    function isFormValid() {
        // if ( trim( $_POST['contactname'] ) === '' ) {
        //     $error = __('Please enter your name.', 'localshop');
        //     $hasError = true;
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
        if($hasError == true) {
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
