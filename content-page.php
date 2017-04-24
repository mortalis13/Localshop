<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package localshop
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php
  /**
   * Functions hooked in to localshop_page add_action
   *
   * @hooked localshop_page_header          - 10
   * @hooked localshop_page_content         - 20
   * @hooked localshop_init_structured_data - 30
   */
  do_action( 'localshop_page' );
  ?>
</div><!-- #post-## -->
