<?php
/**
 * WooCommerce Template Functions.
 *
 * @package localshop
 */

if ( ! function_exists( 'localshop_before_content' ) ) {
	/**
	 * Before Content
	 * Wraps all WooCommerce content in wrappers which match the theme markup
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function localshop_before_content() {
		?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
		<?php
	}
}

if ( ! function_exists( 'localshop_after_content' ) ) {
	/**
	 * After Content
	 * Closes the wrapping divs
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function localshop_after_content() {
		?>
			</main><!-- #main -->
		</div><!-- #primary -->

		<?php // do_action( 'localshop_sidebar' );
	}
}

if ( ! function_exists( 'localshop_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function localshop_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		localshop_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		ob_start();
		localshop_handheld_footer_bar_cart_link();
		$fragments['a.footer-cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

if ( ! function_exists( 'localshop_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function localshop_cart_link() {
		?>
			<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'localshop' ); ?>">
				<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'localshop' ), WC()->cart->get_cart_contents_count() ) );?></span>
			</a>
		<?php
	}
}

if ( ! function_exists( 'localshop_product_search' ) ) {
  /**
   * Display Product Search
   *
   * @since  1.0.0
   * @uses  localshop_is_woocommerce_activated() check if WooCommerce is activated
   * @return void
   */
  function localshop_product_search() {
    if ( localshop_is_woocommerce_activated() ) { ?>
      <div class="site-search">
        <?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
      </div>
    <?php
    }
  }
}

if ( ! function_exists( 'localshop_header_cart' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses  localshop_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function localshop_header_cart() {
		if ( localshop_is_woocommerce_activated() ) {
			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
		?>
		<ul id="site-header-cart" class="site-header-cart menu">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php localshop_cart_link(); ?>
			</li>
			<li>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</li>
		</ul>
		<?php
		}
	}
}

if ( ! function_exists( 'localshop_upsell_display' ) ) {
	/**
	 * Upsells
	 * Replace the default upsell function with our own which displays the correct number product columns
	 *
	 * @since   1.0.0
	 * @return  void
	 * @uses    woocommerce_upsell_display()
	 */
	function localshop_upsell_display() {
		woocommerce_upsell_display( -1, 3 );
	}
}

if ( ! function_exists( 'localshop_sorting_wrapper' ) ) {
	/**
	 * Sorting wrapper
	 *
	 * @since   1.4.3
	 * @return  void
	 */
	function localshop_sorting_wrapper() {
		echo '<div class="localshop-sorting">';
	}
}

if ( ! function_exists( 'localshop_sorting_wrapper_close' ) ) {
	/**
	 * Sorting wrapper close
	 *
	 * @since   1.4.3
	 * @return  void
	 */
	function localshop_sorting_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'localshop_shop_messages' ) ) {
	/**
	 * Localshop shop messages
	 *
	 * @since   1.4.4
	 * @uses    localshop_do_shortcode
	 */
	function localshop_shop_messages() {
		if ( ! is_checkout() ) {
			echo wp_kses_post( localshop_do_shortcode( 'woocommerce_messages' ) );
		}
	}
}

if ( ! function_exists( 'localshop_woocommerce_pagination' ) ) {
	/**
	 * Localshop WooCommerce Pagination
	 * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
	 * but since Localshop adds pagination before that function is excuted we need a separate function to
	 * determine whether or not to display the pagination.
	 *
	 * @since 1.4.4
	 */
	function localshop_woocommerce_pagination() {
		if ( woocommerce_products_will_display() ) {
			woocommerce_pagination();
		}
	}
}

if ( ! function_exists( 'localshop_promoted_products' ) ) {
	/**
	 * Featured and On-Sale Products
	 * Check for featured products then on-sale products and use the appropiate shortcode.
	 * If neither exist, it can fallback to show recently added products.
	 *
	 * @since  1.5.1
	 * @param integer $per_page total products to display.
	 * @param integer $columns columns to arrange products in to.
	 * @param boolean $recent_fallback Should the function display recent products as a fallback when there are no featured or on-sale products?.
	 * @uses  localshop_is_woocommerce_activated()
	 * @uses  wc_get_featured_product_ids()
	 * @uses  wc_get_product_ids_on_sale()
	 * @uses  localshop_do_shortcode()
	 * @return void
	 */
	function localshop_promoted_products( $per_page = '2', $columns = '2', $recent_fallback = true ) {
		if ( localshop_is_woocommerce_activated() ) {

			if ( wc_get_featured_product_ids() ) {

				echo '<h2>' . esc_html__( 'Featured Products', 'localshop' ) . '</h2>';

				echo localshop_do_shortcode( 'featured_products', array(
											'per_page' => $per_page,
											'columns'  => $columns,
				) );
			} elseif ( wc_get_product_ids_on_sale() ) {

				echo '<h2>' . esc_html__( 'On Sale Now', 'localshop' ) . '</h2>';

				echo localshop_do_shortcode( 'sale_products', array(
											'per_page' => $per_page,
											'columns'  => $columns,
				) );
			} elseif ( $recent_fallback ) {

				echo '<h2>' . esc_html__( 'New In Store', 'localshop' ) . '</h2>';

				echo localshop_do_shortcode( 'recent_products', array(
											'per_page' => $per_page,
											'columns'  => $columns,
				) );
			}
		}
	}
}

if ( ! function_exists( 'localshop_handheld_footer_bar' ) ) {
	/**
	 * Display a menu intended for use on handheld devices
	 *
	 * @since 2.0.0
	 */
	function localshop_handheld_footer_bar() {
		$links = array(
			'my-account' => array(
				'priority' => 10,
				'callback' => 'localshop_handheld_footer_bar_account_link',
			),
			'search'     => array(
				'priority' => 20,
				'callback' => 'localshop_handheld_footer_bar_search',
			),
			'cart'       => array(
				'priority' => 30,
				'callback' => 'localshop_handheld_footer_bar_cart_link',
			),
		);

		if ( wc_get_page_id( 'myaccount' ) === -1 ) {
			unset( $links['my-account'] );
		}

		if ( wc_get_page_id( 'cart' ) === -1 ) {
			unset( $links['cart'] );
		}

		$links = apply_filters( 'localshop_handheld_footer_bar_links', $links );
		?>
		<div class="localshop-handheld-footer-bar">
			<ul class="columns-<?php echo count( $links ); ?>">
				<?php foreach ( $links as $key => $link ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>">
						<?php
						if ( $link['callback'] ) {
							call_user_func( $link['callback'], $key, $link );
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
}

if ( ! function_exists( 'localshop_handheld_footer_bar_search' ) ) {
	/**
	 * The search callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function localshop_handheld_footer_bar_search() {
		echo '<a href="">' . esc_attr__( 'Search', 'localshop' ) . '</a>';
		localshop_product_search();
	}
}

if ( ! function_exists( 'localshop_handheld_footer_bar_cart_link' ) ) {
	/**
	 * The cart callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function localshop_handheld_footer_bar_cart_link() {
		?>
			<a class="footer-cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'localshop' ); ?>">
				<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() );?></span>
			</a>
		<?php
	}
}

if ( ! function_exists( 'localshop_handheld_footer_bar_account_link' ) ) {
	/**
	 * The account callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function localshop_handheld_footer_bar_account_link() {
		echo '<a href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '">' . esc_attr__( 'My Account', 'localshop' ) . '</a>';
	}
}

if ( ! function_exists( 'localshop_woocommerce_init_structured_data' ) ) {
	/**
	 * WARNING: This function will be deprecated in Localshop v2.2.
	 *
	 * Generates product category structured data.
	 *
	 * Hooked into `woocommerce_before_shop_loop_item` action hook.
	 */
	function localshop_woocommerce_init_structured_data() {
		if ( ! is_product_category() ) {
			return;
		}

		global $product;

		$json['@type']             = 'Product';
		$json['@id']               = 'product-' . get_the_ID();
		$json['name']              = get_the_title();
		$json['image']             = wp_get_attachment_url( $product->get_image_id() );
		$json['description']       = get_the_excerpt();
		$json['url']               = get_the_permalink();
		$json['sku']               = $product->get_sku();

		if ( $product->get_rating_count() ) {
			$json['aggregateRating'] = array(
				'@type'                => 'AggregateRating',
				'ratingValue'          => $product->get_average_rating(),
				'ratingCount'          => $product->get_rating_count(),
				'reviewCount'          => $product->get_review_count(),
			);
		}

		$json['offers'] = array(
			'@type'                  => 'Offer',
			'priceCurrency'          => get_woocommerce_currency(),
			'price'                  => $product->get_price(),
			'itemCondition'          => 'http://schema.org/NewCondition',
			'availability'           => 'http://schema.org/' . $stock = ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
			'seller'                 => array(
				'@type'                => 'Organization',
				'name'                 => get_bloginfo( 'name' ),
			),
		);

		if ( ! isset( $json ) ) {
			return;
		}

		Localshop::set_structured_data( apply_filters( 'localshop_woocommerce_structured_data', $json ) );
	}
}


function localshop_template_loop_category_link_open( $category ) {
  // echo '<a href="' . get_term_link( $category, 'product_cat' ) . '">';
}

if ( ! function_exists( 'localshop_template_loop_category_title' ) ) {

  /**
   * Show subcategory thumbnails.
   *
   * @param mixed $category
   * @subpackage  Loop
   */
  function localshop_template_loop_category_title( $category ) {
    $small_thumbnail_size   = apply_filters( 'subcategory_archive_thumbnail_size', 'shop_catalog' );
    $dimensions         = wc_get_image_size( $small_thumbnail_size );
    $thumbnail_id       = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

    if ( $thumbnail_id ) {
      $image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
      $image = $image[0];
    } else {
      $image = wc_placeholder_img_src();
    }

    if ( $image ) {
      echo '<div class="category-image">';
      echo '<a href="' . get_term_link( $category, 'product_cat' ) . '">';
      // Prevent esc_url from breaking spaces in urls for image embeds
      // Ref: https://core.trac.wordpress.org/ticket/23605
      $image = str_replace( ' ', '%20', $image );

      echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
      echo '</a>';
      echo '</div>';
    }
  }
}

if (  ! function_exists( 'localshop_subcategory_thumbnail' ) ) {

  /**
   * Show the subcategory title in the product loop.
   */
  function localshop_subcategory_thumbnail( $category ) {
    echo '<div class="category-title">';
    echo '<a href="' . get_term_link( $category, 'product_cat' ) . '">';
    ?>
      <?php
        echo $category->name;

        // if ( $category->count > 0 )
        //   echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
      ?>
    <?php
    echo '</a>';
    echo '</div>';
  }
  }

function localshop_template_loop_category_link_close($category) {
  $cat_id = $category->term_id;
  $prod_term = get_term($cat_id, 'product_cat');
  $description = $prod_term->description;
  
  // echo '</a>';
  echo '<div class="category-description">' . $description . '</div>';
}


// -----------------------------

/**
 * Insert the opening anchor tag for products in the loop.
 */
function localshop_template_loop_product_link_open() {
  // echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">';
}

/**
 * Insert the opening anchor tag for products in the loop.
 */
function localshop_template_loop_product_link_close() {
  // echo '</a>';
}

if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {

  /**
   * Get the product thumbnail for the loop.
   *
   * @subpackage  Loop
   */
  function localshop_template_loop_product_thumbnail() {
    echo '<div class="product-image">';
    echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">';
    echo woocommerce_get_product_thumbnail();
    echo '</a>';
    echo '</div>';
  }
}
if (  ! function_exists( 'woocommerce_template_loop_product_title' ) ) {

  /**
   * Show the product title in the product loop. By default this is an H3.
   */
  function localshop_template_loop_product_title() {
    echo '<div class="product-summary">';
    
    echo '<div class="product-link">';
    echo '<h2 class="title">';
    echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">';
    // echo '<h3>' . get_the_title() . '</h3>';
    echo get_the_title();
    echo '</a>';
    echo '</h2>';
    echo '</div>';
    
    global $product;
    if ( is_search() ) {
      echo '<div class="product-category">';
      echo $product->get_categories( ', ', '<span class="posted_in">', '</span>' );
      echo '</div>';
    }
    
    echo '<div class="product-description">';
    the_excerpt();
    echo '</div>';
    
    echo '<div class="product-price">';
    wc_get_template( 'loop/price.php' );
    echo '</div>';
    
    echo '</div>';
  }
}
if ( ! function_exists( 'woocommerce_template_loop_price' ) ) {

  /**
   * Get the product price for the loop.
   *
   * @subpackage  Loop
   */
  function localshop_template_loop_price() {
    // echo '<div class="product-description">';
    // // wc_get_template( 'single-product/tabs/description.php' );
    // // the_content();
    // the_excerpt();
    // echo '</div>';
    
    // echo '<div class="product-price">';
    // wc_get_template( 'loop/price.php' );
    // echo '</div>';
  }
}


// -----------------------------

if ( ! function_exists( 'localshop_show_product_sale_flash' ) ) {

  /**
   * Output the product sale flash.
   *
   * @subpackage  Product
   */
  function localshop_show_product_sale_flash() {
    wc_get_template( 'single-product/sale-flash.php' );
  }
}

if ( ! function_exists( 'localshop_show_product_images' ) ) {

  /**
   * Output the product image before the single product summary.
   *
   * @subpackage  Product
   */
  function localshop_show_product_images() {
    wc_get_template( 'single-product/product-image.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_title' ) ) {

  /**
   * Output the product title.
   *
   * @subpackage  Product
   */
  function localshop_template_single_title() {
    wc_get_template( 'single-product/title.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_rating' ) ) {

  /**
   * Output the product rating.
   *
   * @subpackage  Product
   */
  function localshop_template_single_rating() {
    wc_get_template( 'single-product/rating.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_price' ) ) {

  /**
   * Output the product price.
   *
   * @subpackage  Product
   */
  function localshop_template_single_price() {
    wc_get_template( 'single-product/price.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_cat' ) ) {

  function localshop_template_single_cat() {
    wc_get_template( 'single-product/cat.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_desc' ) ) {

  function localshop_template_single_desc() {
    wc_get_template( 'single-product/desc.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_excerpt' ) ) {

  /**
   * Output the product short description (excerpt).
   *
   * @subpackage  Product
   */
  function localshop_template_single_excerpt() {
    wc_get_template( 'single-product/short-description.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_meta' ) ) {

  /**
   * Output the product meta.
   *
   * @subpackage  Product
   */
  function localshop_template_single_meta() {
    wc_get_template( 'single-product/meta.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_sharing' ) ) {

  /**
   * Output the product sharing.
   *
   * @subpackage  Product
   */
  function localshop_template_single_sharing() {
    wc_get_template( 'single-product/share.php' );
  }
}

if ( ! function_exists( 'localshop_template_single_add_to_cart' ) ) {

  /**
   * Trigger the single product add to cart action.
   *
   * @subpackage  Product
   */
  function localshop_template_single_add_to_cart() {
    global $product;
    do_action( 'woocommerce_' . $product->product_type . '_add_to_cart' );
  }
}

if ( ! function_exists( 'localshop_output_product_data_tabs' ) ) {

  /**
   * Output the product tabs.
   *
   * @subpackage  Product/Tabs
   */
  function localshop_output_product_data_tabs() {
    wc_get_template( 'single-product/tabs/tabs.php' );
  }
}

if ( ! function_exists( 'localshop_upsell_display' ) ) {

  /**
   * Output product up sells.
   *
   * @param int $posts_per_page (default: -1)
   * @param int $columns (default: 4)
   * @param string $orderby (default: 'rand')
   */
  function localshop_upsell_display( $posts_per_page = '-1', $columns = 4, $orderby = 'rand' ) {
    $args = apply_filters( 'localshop_upsell_display_args', array(
      'posts_per_page'  => $posts_per_page,
      'orderby'     => apply_filters( 'localshop_upsells_orderby', $orderby ),
      'columns'     => $columns
    ) );

    wc_get_template( 'single-product/up-sells.php', $args );
  }
}

if ( ! function_exists( 'localshop_output_related_products' ) ) {

  /**
   * Output the related products.
   *
   * @subpackage  Product
   */
  function localshop_output_related_products() {

    $args = array(
      'posts_per_page'  => 4,
      'columns'       => 4,
      'orderby'       => 'rand'
    );

    localshop_related_products( apply_filters( 'localshop_output_related_products_args', $args ) );
  }
}

if ( ! function_exists( 'localshop_related_products' ) ) {

  /**
   * Output the related products.
   *
   * @param array Provided arguments
   * @param bool Columns argument for backwards compat
   * @param bool Order by argument for backwards compat
   */
  function localshop_related_products( $args = array(), $columns = false, $orderby = false ) {
    if ( ! is_array( $args ) ) {
      _deprecated_argument( __FUNCTION__, '2.1', __( 'Use $args argument as an array instead. Deprecated argument will be removed in WC 2.2.', 'woocommerce' ) );

      $argsvalue = $args;

      $args = array(
        'posts_per_page' => $argsvalue,
        'columns'        => $columns,
        'orderby'        => $orderby,
      );
    }

    $defaults = array(
      'posts_per_page' => 2,
      'columns'        => 2,
      'orderby'        => 'rand'
    );

    $args = wp_parse_args( $args, $defaults );

    wc_get_template( 'single-product/related.php', $args );
  }
}


// ---------------------------------------

if ( ! function_exists( 'localshop_default_product_tabs' ) ) {

  /**
   * Add default product tabs to product pages.
   *
   * @param array $tabs
   * @return array
   */
  function localshop_default_product_tabs( $tabs = array() ) {
    global $product, $post;

    // Description tab - shows product content
    if ( $post->post_content ) {
      $tabs['description'] = array(
        'title'    => __( 'Details', 'woocommerce' ),
        'priority' => 10,
        'callback' => 'woocommerce_product_description_tab'
      );
    }

    // Additional information tab - shows attributes
    if ( $product && ( $product->has_attributes() || ( $product->enable_dimensions_display() && ( $product->has_dimensions() || $product->has_weight() ) ) ) ) {
      $tabs['additional_information'] = array(
        'title'    => __( 'Additional Information', 'woocommerce' ),
        'priority' => 20,
        'callback' => 'woocommerce_product_additional_information_tab'
      );
    }

    // Reviews tab - shows comments
    if ( comments_open() ) {
      $tabs['reviews'] = array(
        'title'    => sprintf( __( 'Reviews (%d)', 'woocommerce' ), $product->get_review_count() ),
        'priority' => 30,
        'callback' => 'comments_template'
      );
    }

    return $tabs;
  }
}

if ( ! function_exists( 'localshop_sort_product_tabs' ) ) {

  /**
   * Sort tabs by priority.
   *
   * @param array $tabs
   * @return array
   */
  function localshop_sort_product_tabs( $tabs = array() ) {

    // Make sure the $tabs parameter is an array
    if ( ! is_array( $tabs ) ) {
      trigger_error( "Function woocommerce_sort_product_tabs() expects an array as the first parameter. Defaulting to empty array." );
      $tabs = array( );
    }

    // Re-order tabs by priority
    if ( ! function_exists( '_sort_priority_callback' ) ) {
      function _sort_priority_callback( $a, $b ) {
        if ( $a['priority'] === $b['priority'] )
          return 0;
        return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
      }
    }

    uasort( $tabs, '_sort_priority_callback' );

    return $tabs;
  }
}

