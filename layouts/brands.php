<?php /* Template Name: brands */ ?>

<?php
get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <h1 class="entry-title"><?=__('Our Brands', 'localshop')?></h1>
      
      <?php 
        $site_url = site_url();
        // $url = get_home_url();
      ?>
    
    <div class="brands">
      <div class="product-brand">
        <a href="#" class="brand-link">
          <img src="<?=$site_url . '/wp-content/images/brands/320px-LOreal_logo.svg.png'?>" alt="">
        </a>
      </div>
      <div class="product-brand">
        <a href="#" class="brand-link">
          <img src="<?=$site_url . '/wp-content/images/brands/HS-Logo.png'?>" alt="">
        </a>
      </div>
      <div class="product-brand">
        <a href="#" class="brand-link">
          <img src="<?=$site_url . '/wp-content/images/brands/Johnsons-Baby-Logo.jpg'?>" alt="">
        </a>
      </div>
      <div class="product-brand">
        <a href="#" class="brand-link">
          <img src="<?=$site_url . '/wp-content/images/brands/logo3YCTc1441954713..png'?>" alt="">
        </a>
      </div>
      <div class="product-brand">
        <a href="#" class="brand-link">
          <img src="<?=$site_url . '/wp-content/images/brands/logo-black.png'?>" alt="">
        </a>
      </div>
      <div class="product-brand">
        <a href="#" class="brand-link">
          <img src="<?=$site_url . '/wp-content/images/brands/palmolive-1.png'?>" alt="">
        </a>
      </div>
      <div class="product-brand">
        <a href="#" class="brand-link">
          <img src="<?=$site_url . '/wp-content/images/brands/Pantene_Pro-V_2011_Logos.gif'?>" alt="">
        </a>
      </div>
    </div>
    
    </main><!-- #main -->
  </div><!-- #primary -->

<?php
// do_action( 'localshop_sidebar' );
get_footer();
