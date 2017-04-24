<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package localshop
 */

get_header(); ?>

  <div id="primary" class="content-area">

    <main id="main" class="site-main" role="main">

      <div class="error-404 not-found">

        <div class="page-content">

          <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'That page can&rsquo;t be found.', 'localshop' ); ?></h1>
          </header><!-- .page-header -->
          
          <div class="content">
            <p><?php esc_html_e( 'Nothing was found at this location. Try to use the search.', 'localshop' ); ?></p>
          </div>

        </div><!-- .page-content -->
      </div><!-- .error-404 -->

    </main><!-- #main -->
  </div><!-- #primary -->

<?php get_footer();
