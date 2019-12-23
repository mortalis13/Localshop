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


<header class="woocommerce-products-header">
  <h1 class="woocommerce-products-header__title page-title">Nuestros Productos</h1>
</header>

<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
<?php
  ob_start();
  woocommerce_output_product_categories( array('parent_id' => is_product_category() ? get_queried_object_id() : 0,) );
  $loop_html .= ob_get_clean();

  wc_set_loop_prop( 'total', 0 );

  global $wp_query;
  if ( $wp_query->is_main_query() ) {
    $wp_query->post_count    = 0;
    $wp_query->max_num_pages = 0;
  }

  echo $loop_html;
?>
</ul>

<?php
  $args = array(
    'post_type' => 'product',
    'posts_per_page' => 12
    );
  $loop = new WP_Query( $args );
  
  if ( $loop->have_posts() ) {
    echo '<ul class="main-page-products">';
    while ( $loop->have_posts() ) : $loop->the_post();
      // wc_get_template_part( 'content', 'product' );
      
      global $product;
      if ( empty( $product ) || ! $product->is_visible() ) {
        continue;
      }
      ?>
      <li <?php wc_product_class( '', $product ); ?>>
        <div class="product-image">
          <a href="<?php echo get_the_permalink(); ?>" class="woocommerce-LoopProduct-link">
            <?php echo woocommerce_get_product_thumbnail(); ?>
          </a>
        </div>
        
        <div class="product-summary">
          <div class="product-link">
            <h2 class="title">
              <a href="<?php echo get_the_permalink(); ?>" class="woocommerce-LoopProduct-link">
                <?php echo get_the_title(); ?>
              </a>
            </h2>
          </div>
          
          <?php
            global $product;
            if ( is_search() ) {
              echo '<div class="product-category">';
              echo $product->get_categories( ', ', '<span class="posted_in">', '</span>' );
              echo '</div>';
            }
          ?>
          
          <div class="product-price">
            <?php wc_get_template( 'loop/price.php' ); ?>
          </div>
        
          <div class="product-add-to-cart">
            <?php
              // wc_get_template( 'loop/add-to-cart.php' );
              // echo do_shortcode('[wc_quick_buy type="link"]');
              woocommerce_template_loop_add_to_cart();
            ?>
          </div>
        
        </div>
      </li>
    <?php endwhile;
    echo '</ul><!--/.products-->';
  }
  wp_reset_postdata();
?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();
