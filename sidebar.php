<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package localshop
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
  return;
}
?>

<?php // debug_print_backtrace(); ?>

<div id="secondary" class="widget-area" role="complementary">
  <?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
