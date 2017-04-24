<?php
/**
 * Localshop WooCommerce Class
 *
 * @package  localshop
 * @author   WooThemes
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'Localshop_WooCommerce' ) ) :

  /**
   * The Localshop WooCommerce Integration class
   */
  class Localshop_WooCommerce {

    /**
     * Setup class.
     *
     * @since 1.0
     */
    public function __construct() {
      add_filter( 'loop_shop_columns',            array( $this, 'loop_columns' ) );
      add_filter( 'body_class',                 array( $this, 'woocommerce_body_class' ) );
      add_action( 'wp_enqueue_scripts',             array( $this, 'woocommerce_scripts' ),  20 );
      add_filter( 'woocommerce_enqueue_styles',         '__return_empty_array' );
      add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
      add_filter( 'woocommerce_product_thumbnails_columns',   array( $this, 'thumbnail_columns' ) );
      add_filter( 'loop_shop_per_page',             array( $this, 'products_per_page' ) );
    }

    /**
     * Default loop columns on product archives
     *
     * @return integer products per row
     * @since  1.0.0
     */
    public function loop_columns() {
      // return apply_filters( 'localshop_loop_columns', 3 ); // 3 products per row
      
      if ( is_product_category() ) {
        return apply_filters( 'localshop_loop_columns', 3 ); // 3 products per row
      } 
      else { // for other archive pages and shop page
        return apply_filters( 'localshop_loop_columns', 2 ); // 3 products per row
      }
    }

    /**
     * Add 'woocommerce-active' class to the body tag
     *
     * @param  array $classes css classes applied to the body tag.
     * @return array $classes modified to include 'woocommerce-active' class
     */
    public function woocommerce_body_class( $classes ) {
      if ( localshop_is_woocommerce_activated() ) {
        $classes[] = 'woocommerce-active';
      }

      return $classes;
    }

    /**
     * WooCommerce specific scripts & stylesheets
     *
     * @since 1.0.0
     */
    public function woocommerce_scripts() {
      global $localshop_version;
      
      // --- Styles
      
      wp_enqueue_style( 'localshop-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', $localshop_version );
      wp_style_add_data( 'localshop-woocommerce-style', 'rtl', 'replace' );

      wp_enqueue_style( 'localshop-add_style-style', get_template_directory_uri() . '/assets/css/add_style.css', '', $localshop_version );
      wp_enqueue_style( 'localshop-browser_fix-style', get_template_directory_uri() . '/assets/css/browser_fix.css', '', $localshop_version );
      
      wp_enqueue_style( 'localshop-ie-style', get_template_directory_uri() . '/assets/css/ie.css', '', $localshop_version );
      wp_enqueue_style( 'localshop-faie7-style', get_template_directory_uri() . '/assets/fonts/FontAwesome/css/font-awesome-ie7.css', '', $localshop_version );
      wp_enqueue_style( 'localshop-ie7-style', get_template_directory_uri() . '/assets/css/ie7.css', '', $localshop_version );
      
      wp_style_add_data( 'localshop-ie-style', 'conditional', 'IE' );
      wp_style_add_data( 'localshop-faie7-style', 'conditional', 'lte IE 7' );
      wp_style_add_data( 'localshop-ie7-style', 'conditional', 'lte IE 7' );
      
      wp_enqueue_style( 'localshop-media-style', get_template_directory_uri() . '/assets/css/media.css', '', $localshop_version );
      
      wp_enqueue_style( 'localshop-ie7media-style', get_template_directory_uri() . '/assets/css/ie7-media.css', '', $localshop_version );
      wp_style_add_data( 'localshop-ie7media-style', 'conditional', 'lte IE 7' );
      
      
      // --- Scripts
      
      wp_register_script( 'localshop-header-cart', get_template_directory_uri() . '/assets/js/woocommerce/header-cart.js', array(), $localshop_version, true );
      wp_enqueue_script( 'localshop-header-cart' );

      wp_register_script( 'localshop-sticky-payment', get_template_directory_uri() . '/assets/js/woocommerce/checkout.min.js', 'jquery', $localshop_version, true );

      if ( is_checkout() && apply_filters( 'localshop_sticky_order_review', true ) ) {
        wp_enqueue_script( 'localshop-sticky-payment' );
      }
      if ( is_product() ) {
        // wp_enqueue_script( 'localshop-jquery-zoom', get_template_directory_uri() . '/assets/js/jquery.zoom.js', 'jquery', $localshop_version, true );
        wp_enqueue_script( 'localshop-jquery-elevatezoom', get_template_directory_uri() . '/assets/js/jquery.elevatezoom.js', 'jquery', $localshop_version, true );
        wp_enqueue_script( 'localshop-product', get_template_directory_uri() . '/assets/js/product.js', 'jquery', $localshop_version, true );
        wp_enqueue_script( 'localshop-product-zoom', get_template_directory_uri() . '/assets/js/product-zoom.js', 'jquery', $localshop_version, true );
      }
      
      wp_enqueue_script( 'localshop-ie7-script', get_template_directory_uri() . '/assets/js/ie7.js', 'jquery', $localshop_version, false );
      wp_enqueue_script( 'localshop-html5shiv-script', get_template_directory_uri() . '/assets/js/html5shiv.js', '', $localshop_version, false );
      wp_enqueue_script( 'localshop-ie-respond-script', get_template_directory_uri() . '/assets/js/respond.src.js', '', $localshop_version, false );
      
      wp_script_add_data( 'localshop-ie7-script', 'conditional', 'lte IE 7' );
      wp_script_add_data( 'localshop-html5shiv-script', 'conditional', 'lte IE 8' );
      wp_script_add_data( 'localshop-ie-respond-script', 'conditional', 'lte IE 8' );
    }

    /**
     * Related Products Args
     *
     * @param  array $args related products args.
     * @since 1.0.0
     * @return  array $args related products args
     */
    public function related_products_args( $args ) {
      $args = apply_filters( 'localshop_related_products_args', array(
        'posts_per_page' => 3,
        'columns'        => 3,
      ) );

      return $args;
    }

    /**
     * Product gallery thumnail columns
     *
     * @return integer number of columns
     * @since  1.0.0
     */
    public function thumbnail_columns() {
      return intval( apply_filters( 'localshop_product_thumbnail_columns', 5 ) );
    }

    /**
     * Products per page
     *
     * @return integer number of products
     * @since  1.0.0
     */
    public function products_per_page() {
      // return intval( apply_filters( 'localshop_products_per_page', 12 ) );
      return intval( apply_filters( 'localshop_products_per_page', 5 ) );
      // return intval( apply_filters( 'localshop_products_per_page', 3 ) );
    }

  }

endif;

return new Localshop_WooCommerce();
