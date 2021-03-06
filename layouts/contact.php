<?php
/* Template Name: contact */
?>

<?php require __DIR__ . "/../handlers/contactformhandler.php"; ?>

<?php
get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <h1 class="entry-title"><?=__('Contact', 'localshop')?></h1>
      
      <div class="contacts-wrap">
        <?php
          $wpContactFormObj = new ContactFormHandler(); 
          $handleValid = $wpContactFormObj->handleContactForm();
          
          $contactName = $contactEmail = $contactContent = '';
          if(!$handleValid){
            $contactName = esc_attr($_POST['contactname']);
            $contactEmail = esc_attr($_POST['contactemail']);
            $contactContent = esc_textarea($_POST['contactcontent']);
          }
        ?>
        
        <div id="contactFormSection">
          <form action="" id="contactForm" method="POST" enctype="multipart/form-data">
            <fieldset>
              <label for="contactname"><?=__('Name', 'localshop')?></label>
              <input type="text" name="contactname" id="contactname" value="<?=$contactName?>" />
            </fieldset>

            <fieldset>
              <label for="contactemail"><?=__('Email', 'localshop')?> <span class="required">*</span></label>
              <input type="text" name="contactemail" id="contactemail" value="<?=$contactEmail?>" />
            </fieldset>
          
            <fieldset>
              <label for="contactcontent"><?=__('Message', 'localshop')?> <span class="required">*</span></label>
              <textarea name="contactcontent" id="contactcontent" rows="10" cols="35"><?=$contactContent?></textarea>
            </fieldset>
          
            <fieldset>
              <button id="contact-submit" type="submit" name="submitContactForm"><?=__('Send', 'localshop')?></button>
            </fieldset>

            <?php wp_nonce_field( 'submit_contact_form' , 'nonce_field_for_submit_contact_form'); ?>
          </form>
        </div>
        
        <div class="contacts-list">
          <div class="contacts-list-item">
            <div class="contacts-list-icon">
              <a href="mailto:abc@abc.com"><i class="fa fa-envelope-o"></i></a>
            </div>
            <div class="contacts-list-caption">
              <a href="mailto:abc@abc.com">abc@abc.com</a>
            </div>
          </div>
          
          <div class="contacts-list-item">
            <div class="contacts-list-icon">
              <a href="tel:123123123"><i class="fa fa-phone"></i></a>
            </div>
            <div class="contacts-list-caption">
              <a href="tel:123123123">(+34) 123 123 123</a>
            </div>
          </div>
          
          <div class="contacts-list-item">
            <div class="contacts-list-icon">
              <a href="https://twitter.com/my_twitter"><i class="fa fa-twitter"></i></a>
            </div>
            <div class="contacts-list-caption">
              <a href="https://twitter.com/my_twitter">my_twitter</a>
            </div>
          </div>
          
          <div class="contacts-list-item">
            <div class="contacts-list-icon">
              <a href="https://facebook.com/my_facebook"><i class="fa fa-facebook"></i></a>
            </div>
            <div class="contacts-list-caption">
              <a href="https://facebook.com/my_facebook">my_facebook</a>
            </div>
          </div>
          
          <div class="contacts-list-item">
            <div class="contacts-list-icon">
              <a href="skype:my_skype"><i class="fa fa-skype"></i></a>
            </div>
            <div class="contacts-list-caption">
              <a href="skype:my_skype">my_skype</a>
            </div>
          </div>
        </div>
      </div>
    
    </main><!-- #main -->
  </div><!-- #primary -->

<?php
// do_action( 'localshop_sidebar' );
get_footer();
