<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package localshop
 */

?>

<script>
  var signUpText = "<?=__('Sign Up', 'localshop')?>";
  var signUpEmailNotValidText = "<?=__('Email is not valid', 'localshop')?>";
  var signUpEmailAddedText = "<?=__('Your email added to the news list', 'localshop')?>";
</script>

    </div><!-- .col-full -->
  </div><!-- #content -->

  <?php do_action( 'localshop_before_footer' ); ?>

  <footer id="colophon" class="site-footer" role="contentinfo">
    <div class="col-full">

      <?php
      /**
       * Functions hooked in to localshop_footer action
       *
       * @hooked localshop_footer_widgets - 10
       * @hooked localshop_credit         - 20
       */
      // do_action( 'localshop_footer' ); ?>
      
      <div class="footer-block first about">
        <h3 class="block-title about-title"><?=__('About Us', 'localshop')?></h3>
        <p class="about-text"><?php echo localshop_get_option('about_us') ?></p>
      </div>
      
      <div class="footer-block footer-categories">
        <h3 class="block-title footer-categories-title"><?=__('Categories', 'localshop')?></h3>
        
        <?php 
          $args = array(
                  'taxonomy'     => 'product_cat',
                  'orderby'      => 'name',
                  'show_count'   => 0,      // 1 for yes, 0 for no
                  'pad_counts'   => 0,      // 1 for yes, 0 for no
                  'hierarchical' => 1,      // 1 for yes, 0 for no  
                  'title_li'     => '',
                  'hide_empty'   => 0
          );
          
          $all_categories = get_categories( $args );
          
          echo '<ul class="footer-categories-list">';
          foreach ($all_categories as $cat) {
            if($cat->category_parent == 0 && $cat->count != 0) {
              $category_id = $cat->term_id;
              echo '<li><a href="' . get_term_link($cat->slug, 'product_cat') . '">'. $cat->name .'</a></li>';
            }       
          }
          echo '</ul>';
        ?>
      </div>
      
      <div class="footer-block last footer-newsletters">
        <h3 class="block-title footer-signup-title"><?=__('Sign Up for Newsletters', 'localshop')?></h3>
        
        <form id="signup-form" action="<?php echo get_bloginfo('template_url') . '/handlers/newsletters-signup.php' ?>" method="POST">
          <input type="text" name="signup-email" id="signup-email" class="text-field" placeholder="<?=__('Email', 'localshop')?>">
          <button id="signup-submit">
            <span class="signup-caption"><?=__('Sign Up', 'localshop')?></span>
            <span class="dot1"></span>
            <span class="dot2"></span>
            <span class="dot3"></span>
          </button>
          <div class="signup-message"></div>
          <?php wp_nonce_field( 'submit_newsletters_form' , 'nssignup_submit'); ?>
        </form>
      </div>
      
    </div><!-- .col-full -->
  </footer><!-- #colophon -->

  <?php do_action( 'localshop_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
