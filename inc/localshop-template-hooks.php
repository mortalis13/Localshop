<?php
/**
 * Localshop hooks
 *
 * @package localshop
 */

/**
 * General
 *
 * @see  localshop_header_widget_region()
 * @see  localshop_get_sidebar()
 */
add_action( 'localshop_before_content', 'localshop_header_widget_region', 10 );
add_action( 'localshop_sidebar',        'localshop_get_sidebar',          10 );

/**
 * Header
 *
 * @see  localshop_skip_links()
 * @see  localshop_secondary_navigation()
 * @see  localshop_site_branding()
 * @see  localshop_primary_navigation()
 */
add_action( 'localshop_header', 'localshop_skip_links',                       0 );
add_action( 'localshop_header', 'localshop_main_header',                    20 );
add_action( 'localshop_header_start', 'localshop_header_start',               0 );

/**
 * Footer
 *
 * @see  localshop_footer_widgets()
 * @see  localshop_credit()
 */
add_action( 'localshop_footer', 'localshop_footer_widgets', 10 );
add_action( 'localshop_footer', 'localshop_credit',         20 );

/**
 * Homepage
 *
 * @see  localshop_homepage_content()
 * @see  localshop_product_categories()
 * @see  localshop_recent_products()
 * @see  localshop_featured_products()
 * @see  localshop_popular_products()
 * @see  localshop_on_sale_products()
 * @see  localshop_best_selling_products()
 */
add_action( 'homepage', 'localshop_homepage_content',      10 );
add_action( 'homepage', 'localshop_product_categories',    20 );
add_action( 'homepage', 'localshop_recent_products',       30 );
add_action( 'homepage', 'localshop_featured_products',     40 );
add_action( 'homepage', 'localshop_popular_products',      50 );
add_action( 'homepage', 'localshop_on_sale_products',      60 );
add_action( 'homepage', 'localshop_best_selling_products', 70 );

/**
 * Posts
 *
 * @see  localshop_post_header()
 * @see  localshop_post_meta()
 * @see  localshop_post_content()
 * @see  localshop_init_structured_data()
 * @see  localshop_paging_nav()
 * @see  localshop_single_post_header()
 * @see  localshop_post_nav()
 * @see  localshop_display_comments()
 */
add_action( 'localshop_loop_post',           'localshop_post_header',          10 );
add_action( 'localshop_loop_post',           'localshop_post_meta',            20 );
add_action( 'localshop_loop_post',           'localshop_post_content',         30 );
add_action( 'localshop_loop_post',           'localshop_init_structured_data', 40 );
add_action( 'localshop_loop_after',          'localshop_paging_nav',           10 );
add_action( 'localshop_single_post',         'localshop_post_header',          10 );
add_action( 'localshop_single_post',         'localshop_post_meta',            20 );
add_action( 'localshop_single_post',         'localshop_post_content',         30 );
add_action( 'localshop_single_post',         'localshop_init_structured_data', 40 );
add_action( 'localshop_single_post_bottom',  'localshop_post_nav',             10 );
add_action( 'localshop_single_post_bottom',  'localshop_display_comments',     20 );
add_action( 'localshop_post_content_before', 'localshop_post_thumbnail',       10 );

/**
 * Pages
 *
 * @see  localshop_page_header()
 * @see  localshop_page_content()
 * @see  localshop_init_structured_data()
 * @see  localshop_display_comments()
 */
add_action( 'localshop_page',       'localshop_page_header',          10 );
add_action( 'localshop_page',       'localshop_page_content',         20 );
add_action( 'localshop_page',       'localshop_init_structured_data', 30 );
add_action( 'localshop_page_after', 'localshop_display_comments',     10 );

add_action( 'after_switch_theme', 'localshop_create_custom_tables');
