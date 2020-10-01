<?php
/* Template Name: home */
?>

<?php
get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

<header class="woocommerce-products-header">
  <h1 class="woocommerce-products-header__title page-title">Our Products</h1>
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

<div class="products-separator"></div>

<?php
  global $page;
  $page = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
  $ordering                = WC()->query->get_catalog_ordering_args();
  $ordering['orderby']     = array_shift(explode(' ', $ordering['orderby']));
  $ordering['orderby']     = stristr($ordering['orderby'], 'price') ? 'meta_value_num' : $ordering['orderby'];
  $ordering['orderby']     = stristr($ordering['orderby'], 'menu_order') ? '' : $ordering['orderby'];
  $products_per_page       = apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());
  
  // -----------
  echo "<pre>";
  
  $args = array(
    'paginate' => true,
    'limit' => 8, 
    'page' => $page,
    'orderby' => $ordering['orderby'],
    'order'   => $ordering['order'],
  );
  $results = wc_get_products( $args );
  $products = $results->products;
  
  echo "</pre>";
  // -----------
  
  
  echo '<div class="main-page-products">';
  if (isset($results) && $results->total > 0) {
    wc_set_loop_prop( 'total', $results->total );
    wc_set_loop_prop( 'total_pages', $results->max_num_pages );
    wc_set_loop_prop( 'current_page', $page );
    
    woocommerce_catalog_ordering();
    woocommerce_pagination();
    
    echo '<div class="clearfix"></div>';
    
    // ------------
    echo '<ul class="products-list">';
    foreach ( $products as $product ) {
      if ( empty( $product ) || ! $product->is_visible() ) {
        continue;
      }
      
      ?><li <?php wc_product_class( '', $product ); ?>>
        <div class="product-image">
          <a href="<?=$product->get_permalink()?>" class="woocommerce-LoopProduct-link">
            <div class="img-wrapper"><?=woocommerce_get_product_thumbnail()?></div>
          </a>
        </div>
        
        <div class="product-summary">
          <div class="product-link">
            <h2 class="title">
              <a href="<?=$product->get_permalink()?>" class="woocommerce-LoopProduct-link"><?=$product->get_name()?></a>
            </h2>
          </div>
          
          <div class="product-price"><?php wc_get_template( 'loop/price.php' ); ?></div>
        </div>
      </li><?php
    }
    echo '</ul><!--/.products-->';
  }
  echo '</div>';
?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();
