<?php
/**
 * Template used to display post content on single pages.
 *
 * @package localshop
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <?php
  do_action( 'localshop_single_post_top' );

  /**
   * Functions hooked into localshop_single_post add_action
   *
   * @hooked localshop_post_header          - 10
   * @hooked localshop_post_meta            - 20
   * @hooked localshop_post_content         - 30
   * @hooked localshop_init_structured_data - 40
   */
  do_action( 'localshop_single_post' );

  /**
   * Functions hooked in to localshop_single_post_bottom action
   *
   * @hooked localshop_post_nav         - 10
   * @hooked localshop_display_comments - 20
   */
  do_action( 'localshop_single_post_bottom' );
  ?>

</div><!-- #post-## -->
