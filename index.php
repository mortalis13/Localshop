<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package localshop
 */

get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

<?php 
      ob_start();
      woocommerce_output_product_categories(
        array(
          'parent_id' => is_product_category() ? get_queried_object_id() : 0,
        )
      );
      $loop_html .= ob_get_clean();
      echo $loop_html;
 ?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();
