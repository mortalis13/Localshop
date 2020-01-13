<?php
/**
 * Localshop WooCommerce hooks
 *
 * @package localshop
 */

/**
 * Styles
 *
 * @see  localshop_woocommerce_scripts()
 */

/**
 * Layout
 *
 * @see  localshop_before_content()
 * @see  localshop_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  localshop_shop_messages()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb',                 20, 0 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper',     10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar',                10 );
remove_action( 'woocommerce_after_shop_loop',     'woocommerce_pagination',                 10 );
remove_action( 'woocommerce_before_shop_loop',    'woocommerce_result_count',               20 );
remove_action( 'woocommerce_before_shop_loop',    'woocommerce_catalog_ordering',           30 );
add_action( 'woocommerce_before_main_content',    'localshop_before_content',              10 );
add_action( 'woocommerce_after_main_content',     'localshop_after_content',               10 );
add_action( 'localshop_content_top',             'localshop_shop_messages',               15 );
add_action( 'localshop_content_top',             'woocommerce_breadcrumb',                 10 );

add_action( 'woocommerce_after_shop_loop',        'localshop_sorting_wrapper',             9 );
add_action( 'woocommerce_after_shop_loop',        'woocommerce_catalog_ordering',           10 );
add_action( 'woocommerce_after_shop_loop',        'woocommerce_result_count',               20 );
add_action( 'woocommerce_after_shop_loop',        'woocommerce_pagination',                 30 );
add_action( 'woocommerce_after_shop_loop',        'localshop_sorting_wrapper_close',       31 );

add_action( 'woocommerce_before_shop_loop',       'localshop_sorting_wrapper',             9 );
add_action( 'woocommerce_before_shop_loop',       'woocommerce_catalog_ordering',           10 );
add_action( 'woocommerce_before_shop_loop',       'woocommerce_result_count',               20 );
add_action( 'woocommerce_before_shop_loop',       'localshop_woocommerce_pagination',      30 );
add_action( 'woocommerce_before_shop_loop',       'localshop_sorting_wrapper_close',       31 );

add_action( 'localshop_footer',                  'localshop_handheld_footer_bar',         999 );

/**
 * Categories
 *
 */
add_action( 'localshop_before_subcategory_title', 'localshop_template_loop_category_title', 10 );
add_action( 'localshop_shop_loop_subcategory_title', 'localshop_subcategory_thumbnail', 10 );
add_action( 'localshop_after_subcategory', 'localshop_template_loop_category_link_close', 10 );

add_action( 'localshop_before_shop_loop_item_title', 'localshop_template_loop_product_thumbnail', 10 );
add_action( 'localshop_shop_loop_item_title', 'localshop_template_loop_product_title', 10 );

add_action( 'localshop_before_single_product_summary', 'localshop_show_product_sale_flash', 10 );
add_action( 'localshop_before_single_product_summary', 'localshop_show_product_images', 20 );

add_action( 'localshop_single_product_summary', 'localshop_template_single_title', 5 );
add_action( 'localshop_single_product_summary', 'localshop_template_single_cat', 5 );
add_action( 'localshop_single_product_summary', 'localshop_template_single_desc', 5 );
add_action( 'localshop_single_product_summary', 'localshop_template_single_rating', 10 );
add_action( 'localshop_single_product_summary', 'localshop_template_single_price', 10 );
add_action( 'localshop_single_product_summary', 'localshop_template_single_sharing', 50 );
add_action( 'localshop_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

// add_action( 'localshop_single_product_summary', array( WC_Wishlists_Plugin, 'add_to_wishlist_button' ), 40 );
// add_action( 'localshop_single_product_summary', array( 'WC_Wishlists_Plugin', 'add_to_wishlist_button' ), 40 );

add_action( 'localshop_after_single_product_summary', 'localshop_output_product_data_tabs', 10 );
add_action( 'localshop_after_single_product_summary', 'localshop_upsell_display', 15 );
add_action( 'localshop_after_single_product_summary', 'localshop_output_related_products', 20 );

add_filter( 'localshop_product_tabs', 'localshop_default_product_tabs' );
add_filter( 'localshop_product_tabs', 'localshop_sort_product_tabs', 99 );


/**
 * Products
 *
 * @see  localshop_upsell_display()
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display',               15 );
add_action( 'woocommerce_after_single_product_summary',    'localshop_upsell_display',                15 );
remove_action( 'woocommerce_before_shop_loop_item_title',  'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_after_shop_loop_item_title',      'woocommerce_show_product_loop_sale_flash', 6 );

/**
 * Structured Data
 *
 * @see localshop_woocommerce_init_structured_data()
 */
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.7', '<' ) ) {
  add_action( 'woocommerce_before_shop_loop_item', 'localshop_woocommerce_init_structured_data' );
}

if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
  add_filter( 'woocommerce_add_to_cart_fragments', 'localshop_cart_link_fragment' );
} else {
  add_filter( 'add_to_cart_fragments', 'localshop_cart_link_fragment' );
}
