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
<!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"> -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0, user-scalable=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div class="debug-bar"><pre></pre></div>

<?php 
  // global $template;
  // print_r($template);
?>
 
<div id="page" class="hfeed site">
  <?php
  do_action( 'localshop_before_header' ); ?>

  <header id="masthead" class="site-header" role="banner" style="<?php localshop_header_styles(); ?>">
  
    <?php do_action( 'localshop_header_start' ); ?>
    
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
