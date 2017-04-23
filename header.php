<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package localshop
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<!--[if IE]>
  <link rel="stylesheet" id="ie-style" href="<?php echo get_template_directory_uri() . "/assets/css/ie.css"?>" type='text/css' media='all' />
<![endif]-->

<!--[if IE 7]>
  <link rel="stylesheet" id="ie7-style3" href="<?php echo get_template_directory_uri() . "/assets/fonts/FontAwesome/css/font-awesome-ie7.css"?>" type='text/css' media='all' />
  <link rel="stylesheet" id="ie7-style1" href="<?php echo get_template_directory_uri() . "/assets/css/ie7.css"?>" type='text/css' media='all' />
  <script src="<?php echo get_template_directory_uri() . "/assets/js/ie7.js"?>"></script>
<![endif]-->

  <!-- <script src="<?php echo get_template_directory_uri() . "/assets/js/IE7.js"?>"></script> -->

<!--[if lte IE 8]>
  <script src="<?php echo get_template_directory_uri() . "/assets/js/html5shiv.js"?>"></script>
  <script src="<?php echo get_template_directory_uri() . "/assets/js/respond.src.js"?>"></script>
<![endif]-->

</head>

<body <?php body_class(); ?>>

<?php 
  // global $template;
  // print_r($template);
?>
 
<div id="page" class="hfeed site">
	<?php
	do_action( 'localshop_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php localshop_header_styles(); ?>">
    <div class="header-top-content">
      <div class="top-content-wrapper">
        <div class="site-info-block">
          <div class="info-email"><a href="mailto:abc@abc.com"><i class="fa fa-envelope-o"></i> abc@abc.com</a></div>
          <div class="info-phone"><a href="tel:123123123"><i class="fa fa-phone"></i> 123 123 123</a></div>
        </div>
        
        <div class="search-top-block">
          <?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
        </div>
      </div>
    </div>
  
		<div class="col-full">

			<?php
			/**
			 * Functions hooked into localshop_header action
			 *
			 * @hooked localshop_skip_links                       - 0
			 * @hooked localshop_social_icons                     - 10
			 * @hooked localshop_site_branding                    - 20
			 * @hooked localshop_secondary_navigation             - 30
			 * @hooked localshop_product_search                   - 40
			 * @hooked localshop_primary_navigation_wrapper       - 42
			 * @hooked localshop_primary_navigation               - 50
			 * @hooked localshop_header_cart                      - 60
			 * @hooked localshop_primary_navigation_wrapper_close - 68
			 */
			do_action( 'localshop_header' ); ?>

		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to localshop_before_content
	 *
	 * @hooked localshop_header_widget_region - 10
	 */
	do_action( 'localshop_before_content' ); ?>
  
	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		/**
		 * Functions hooked in to localshop_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'localshop_content_top' );
