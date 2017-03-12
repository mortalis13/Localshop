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

      if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.5', '<' ) ) {
        add_action( 'wp_footer',              array( $this, 'star_rating_script' ) );
      }

      // Integrations.
      // add_action( 'wp_enqueue_scripts',             array( $this, 'woocommerce_integrations_scripts' ), 99 );
      add_action( 'wp_enqueue_scripts',                       array( $this, 'add_customizer_css' ), 140 );

      add_action( 'after_switch_theme',                       array( $this, 'set_localshop_style_theme_mods' ) );
      add_action( 'customize_save_after',                     array( $this, 'set_localshop_style_theme_mods' ) );
    }

    /**
     * Add CSS in <head> for styles handled by the theme customizer
     * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
     *
     * @since 2.1.0
     * @return void
     */
    public function add_customizer_css() {
      $localshop_woocommerce_extension_styles = get_theme_mod( 'localshop_woocommerce_extension_styles' );

      if ( is_customize_preview() || ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) || ( false === $localshop_woocommerce_extension_styles ) ) {
        wp_add_inline_style( 'localshop-woocommerce-style', $this->get_woocommerce_extension_css() );
      } else {
        wp_add_inline_style( 'localshop-woocommerce-style', $localshop_woocommerce_extension_styles );
      }
    }

    /**
     * Assign styles to individual theme mod.
     *
     * @since 2.1.0
     * @return void
     */
    public function set_localshop_style_theme_mods() {
      set_theme_mod( 'localshop_woocommerce_extension_styles', $this->get_woocommerce_extension_css() );
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

      wp_enqueue_style( 'localshop-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', $localshop_version );
      wp_style_add_data( 'localshop-woocommerce-style', 'rtl', 'replace' );

      wp_register_script( 'localshop-header-cart', get_template_directory_uri() . '/assets/js/woocommerce/header-cart.min.js', array(), $localshop_version, true );
      wp_enqueue_script( 'localshop-header-cart' );

      wp_register_script( 'localshop-sticky-payment', get_template_directory_uri() . '/assets/js/woocommerce/checkout.min.js', 'jquery', $localshop_version, true );

      if ( is_checkout() && apply_filters( 'localshop_sticky_order_review', true ) ) {
        wp_enqueue_script( 'localshop-sticky-payment' );
      }
      if ( is_product() ) {
        wp_enqueue_script( 'localshop-jquery-zoom', get_template_directory_uri() . '/assets/js/jquery.zoom.js', 'jquery', $localshop_version, true );
        wp_enqueue_script( 'localshop-product', get_template_directory_uri() . '/assets/js/product.js', 'jquery', $localshop_version, true );
      }
      
      wp_enqueue_style( 'localshop-add-style', get_template_directory_uri() . '/assets/css/add_style.css', '', $localshop_version );
    }

    /**
     * Star rating backwards compatibility script (WooCommerce <2.5).
     *
     * @since 1.6.0
     */
    public function star_rating_script() {
      if ( wp_script_is( 'jquery', 'done' ) && is_product() ) {
    ?>
      <script type="text/javascript">
        jQuery( function( $ ) {
          $( 'body' ).on( 'click', '#respond p.stars a', function() {
            var $container = $( this ).closest( '.stars' );
            $container.addClass( 'selected' );
          });
        });
      </script>
    <?php
      }
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
      return intval( apply_filters( 'localshop_products_per_page', 12 ) );
      // return intval( apply_filters( 'localshop_products_per_page', 3 ) );
    }

    /**
     * Query WooCommerce Extension Activation.
     *
     * @param string $extension Extension class name.
     * @return boolean
     */
    public function is_woocommerce_extension_activated( $extension = 'WC_Bookings' ) {
      return class_exists( $extension ) ? true : false;
    }

    /**
     * Integration Styles & Scripts
     *
     * @return void
     */
    public function woocommerce_integrations_scripts() {
      /**
       * Bookings
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Bookings' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-bookings-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/bookings.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-bookings-style', 'rtl', 'replace' );
      }

      /**
       * Brands
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Brands' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-brands-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/brands.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-brands-style', 'rtl', 'replace' );
      }

      /**
       * Wishlists
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Wishlists_Wishlist' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-wishlists-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/wishlists.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-wishlists-style', 'rtl', 'replace' );
      }

      /**
       * AJAX Layered Nav
       */
      if ( $this->is_woocommerce_extension_activated( 'SOD_Widget_Ajax_Layered_Nav' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-ajax-layered-nav-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/ajax-layered-nav.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-ajax-layered-nav-style', 'rtl', 'replace' );
      }

      /**
       * Variation Swatches
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_SwatchesPlugin' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-variation-swatches-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/variation-swatches.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-variation-swatches-style', 'rtl', 'replace' );
      }

      /**
       * Composite Products
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Composite_Products' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-composite-products-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/composite-products.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-composite-products-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Photography
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Photography' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-photography-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/photography.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-photography-style', 'rtl', 'replace' );
      }

      /**
       * Product Reviews Pro
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Product_Reviews_Pro' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-product-reviews-pro-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/product-reviews-pro.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-product-reviews-pro-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Smart Coupons
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Smart_Coupons' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-smart-coupons-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/smart-coupons.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-smart-coupons-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Deposits
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Deposits' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-deposits-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/deposits.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-deposits-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Product Bundles
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Bundles' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-bundles-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/bundles.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-bundles-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Multiple Shipping Addresses
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Ship_Multiple' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-sma-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/ship-multiple-addresses.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-sma-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Advanced Product Labels
       */
      if ( $this->is_woocommerce_extension_activated( 'Woocommerce_Advanced_Product_Labels' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-apl-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/advanced-product-labels.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-apl-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Mix and Match
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Mix_and_Match' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-mix-and-match-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/mix-and-match.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-mix-and-match-style', 'rtl', 'replace' );
      }

      /**
       * WooCommerce Quick View
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Quick_View' ) ) {
        wp_enqueue_style( 'localshop-woocommerce-quick-view-style', get_template_directory_uri() . '/assets/sass/woocommerce/extensions/quick-view.css', 'localshop-woocommerce-style' );
        wp_style_add_data( 'localshop-woocommerce-quick-view-style', 'rtl', 'replace' );
      }

      /**
       * Checkout Add Ons
       */
      if ( $this->is_woocommerce_extension_activated( 'WC_Checkout_Add_Ons' ) ) {
        add_filter( 'localshop_sticky_order_review', '__return_false' );
      }
    }

    /**
     * Get extension css.
     *
     * @see get_localshop_theme_mods()
     * @return array $styles the css
     */
    public function get_woocommerce_extension_css() {
      $localshop_customizer = new Localshop_Customizer();
      $localshop_theme_mods = $localshop_customizer->get_localshop_theme_mods();

      $woocommerce_extension_style        = '';

      if ( $this->is_woocommerce_extension_activated( 'WC_Quick_View' ) ) {
        $woocommerce_extension_style          .= '
        div.quick-view div.quick-view-image a.button {
          background-color: ' . $localshop_theme_mods['button_background_color'] . ' !important;
          border-color: ' . $localshop_theme_mods['button_background_color'] . ' !important;
          color: ' . $localshop_theme_mods['button_text_color'] . ' !important;
        }

        div.quick-view div.quick-view-image a.button:hover {
          background-color: ' . localshop_adjust_color_brightness( $localshop_theme_mods['button_background_color'], $darken_factor ) . ' !important;
          border-color: ' . localshop_adjust_color_brightness( $localshop_theme_mods['button_background_color'], $darken_factor ) . ' !important;
          color: ' . $localshop_theme_mods['button_text_color'] . ' !important;
        }';
      }

      if ( $this->is_woocommerce_extension_activated( 'WC_Bookings' ) ) {
        $woocommerce_extension_style          .= '
        #wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker td.bookable a,
        #wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker td.bookable a:hover,
        #wc-bookings-booking-form .block-picker li a:hover,
        #wc-bookings-booking-form .block-picker li a.selected {
          background-color: ' . $localshop_theme_mods['accent_color'] . ' !important;
        }

        #wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker td.ui-state-disabled .ui-state-default,
        #wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker th {
          color:' . $localshop_theme_mods['text_color'] . ';
        }

        #wc-bookings-booking-form .wc-bookings-date-picker .ui-datepicker-header {
          background-color: ' . $localshop_theme_mods['header_background_color'] . ';
          color: ' . $localshop_theme_mods['header_text_color'] . ';
        }';
      }

      if ( $this->is_woocommerce_extension_activated( 'WC_Product_Reviews_Pro' ) ) {
        $woocommerce_extension_style          .= '
        .woocommerce #reviews .product-rating .product-rating-details table td.rating-graph .bar,
        .woocommerce-page #reviews .product-rating .product-rating-details table td.rating-graph .bar {
          background-color: ' . $localshop_theme_mods['text_color'] . ' !important;
        }

        .woocommerce #reviews .contribution-actions .feedback,
        .woocommerce-page #reviews .contribution-actions .feedback,
        .star-rating-selector:not(:checked) label.checkbox {
          color: ' . $localshop_theme_mods['text_color'] . ';
        }

        .woocommerce #reviews #comments ol.commentlist li .contribution-actions a,
        .woocommerce-page #reviews #comments ol.commentlist li .contribution-actions a,
        .star-rating-selector:not(:checked) input:checked ~ label.checkbox,
        .star-rating-selector:not(:checked) label.checkbox:hover ~ label.checkbox,
        .star-rating-selector:not(:checked) label.checkbox:hover,
        .woocommerce #reviews #comments ol.commentlist li .contribution-actions a,
        .woocommerce-page #reviews #comments ol.commentlist li .contribution-actions a,
        .woocommerce #reviews .form-contribution .attachment-type:not(:checked) label.checkbox:before,
        .woocommerce-page #reviews .form-contribution .attachment-type:not(:checked) label.checkbox:before {
          color: ' . $localshop_theme_mods['accent_color'] . ' !important;
        }';
      }

      if ( $this->is_woocommerce_extension_activated( 'WC_Smart_Coupons' ) ) {
        $woocommerce_extension_style          .= '
        .coupon-container {
          background-color: ' . $localshop_theme_mods['button_background_color'] . ' !important;
        }

        .coupon-content {
          border-color: ' . $localshop_theme_mods['button_text_color'] . ' !important;
          color: ' . $localshop_theme_mods['button_text_color'] . ';
        }

        .sd-buttons-transparent.woocommerce .coupon-content,
        .sd-buttons-transparent.woocommerce-page .coupon-content {
          border-color: ' . $localshop_theme_mods['button_background_color'] . ' !important;
        }';
      }

      return apply_filters( 'localshop_customizer_woocommerce_extension_css', $woocommerce_extension_style );
    }
  }

endif;

return new Localshop_WooCommerce();
