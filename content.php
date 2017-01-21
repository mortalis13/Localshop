<?php
/**
 * Template used to display post content.
 *
 * @package localshop
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to localshop_loop_post action.
	 *
	 * @hooked localshop_post_header          - 10
	 * @hooked localshop_post_meta            - 20
	 * @hooked localshop_post_content         - 30
	 * @hooked localshop_init_structured_data - 40
	 */
	do_action( 'localshop_loop_post' );
	?>

</article><!-- #post-## -->
