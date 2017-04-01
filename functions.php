<?php
/**
 * Localshop engine room
 *
 * @package localshop
 */

/**
 * Assign the Localshop version to a var
 */
$theme              = wp_get_theme( 'localshop' );
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

if ( class_exists( 'Jetpack' ) ) {
	$localshop->jetpack = require 'inc/jetpack/class-localshop-jetpack.php';
}

if ( localshop_is_woocommerce_activated() ) {
	$localshop->woocommerce = require 'inc/woocommerce/class-localshop-woocommerce.php';

	require 'inc/woocommerce/localshop-woocommerce-template-hooks.php';
	require 'inc/woocommerce/localshop-woocommerce-template-functions.php';
}

if ( is_admin() ) {
	// $localshop->admin = require 'inc/admin/class-localshop-admin.php';
  require 'inc/admin/theme-options.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */
