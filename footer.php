<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package localshop
 */

?>

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
      
      <div class="footer-block about">
        <h3 class="about-title">About Us</h3>
        <p class="about-text">
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates vel id saepe similique ratione qui dolore sunt quae soluta consectetur perferendis nam culpa quaerat nulla amet aperiam aliquid rerum, in optio asperiores quasi, veniam eos ex ducimus.
        </p>
      </div>
      
      <div class="footer-block footer-categories">
        <h3 class="footer-categories-title">Categories</h3>
        
        <?php 
          $taxonomy     = 'product_cat';
          $orderby      = 'name';  
          $show_count   = 0;      // 1 for yes, 0 for no
          $pad_counts   = 0;      // 1 for yes, 0 for no
          $hierarchical = 1;      // 1 for yes, 0 for no  
          $title        = '';  
          $empty        = 0;
          
          $args = array(
                  'taxonomy'     => $taxonomy,
                  'orderby'      => $orderby,
                  'show_count'   => $show_count,
                  'pad_counts'   => $pad_counts,
                  'hierarchical' => $hierarchical,
                  'title_li'     => $title,
                  'hide_empty'   => $empty
          );
          
          $all_categories = get_categories( $args );
          
          echo '<ul class="footer-categories-list">';
          
          foreach ($all_categories as $cat) {
            if($cat->category_parent == 0 && $cat->count != 0) {
              $category_id = $cat->term_id;
              echo '<li><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a></li>';
            }       
          }
          
          echo '</ul>';
        ?>
      </div>
      
      <div class="footer-block footer-newsletters">
        <h3 class="footer-signup-title">Sign Up for Newsletters</h3>
        <!-- <form action="../newsletters-signup.php"> -->
        <!-- <form action="" method="POST"> -->
        <form id="signup-form" action="<?php echo get_bloginfo('template_url') . '/handlers/newsletters-signup.php' ?>" method="POST">
          <input type="text" name="signup-email" id="signup-email" class="text-field" placeholder="Email Address">
          <button id="signup-submit">
            <span class="signup-caption">Sign Up</span>
            <span class="dot1"></span>
            <span class="dot2"></span>
            <span class="dot3"></span>
          </button>
          
          <div class="signup-message"></div>
          
          <!-- <input type="hidden" name="nssignup_submit" value="asdasd"> -->
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
