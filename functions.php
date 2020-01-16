<?php
/**
 * Localshop engine room
 *
 * @package localshop
 */

/**
 * Assign the Localshop version to a var
 */
$theme = wp_get_theme( 'localshop' );
$localshop_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
  $content_width = 980; /* pixels */
}

$localshop = (object) array(
  'version' => $localshop_version,

  /**
   * Initialize all the things.
   */
  'main'       => require 'inc/class-localshop.php',
  'customizer' => require 'inc/customizer/class-localshop-customizer.php',
);

require 'inc/localshop-functions.php';
require 'inc/localshop-template-hooks.php';
require 'inc/localshop-template-functions.php';

if ( localshop_is_woocommerce_activated() ) {
  $localshop->woocommerce = require 'inc/woocommerce/class-localshop-woocommerce.php';

  require 'inc/woocommerce/localshop-woocommerce-template-hooks.php';
  require 'inc/woocommerce/localshop-woocommerce-template-functions.php';
}

if ( is_admin() ) {
  require 'inc/admin/theme-options.php';
  require 'inc/admin/newsletters-manage.php';
}


function my_pagination_rewrite() {
  add_rewrite_rule('page/([0-9]{1,})/?$', 'index.php?page=$matches[1]', 'top');
}
// add_action('init', 'my_pagination_rewrite');

function ls_paginate_links_filter($link) {
  if (is_home()) $link = preg_replace('/\?.+$/', '', $link);
  return $link;
}
add_filter( 'paginate_links', 'ls_paginate_links_filter', 10, 1 );
