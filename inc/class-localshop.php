<?php
/**
 * Localshop Class
 *
 * @author   WooThemes
 * @since    2.0.0
 * @package  localshop
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'Localshop' ) ) :

  /**
   * The main Localshop class
   */
  class Localshop {

    private static $structured_data;

    /**
     * Setup class.
     *
     * @since 1.0
     */
    public function __construct() {
      add_action( 'after_setup_theme',          array( $this, 'setup' ) );
      add_action( 'after_switch_theme',         array( $this, 'create_custom_tables') );
      add_action( 'widgets_init',               array( $this, 'widgets_init' ) );
      add_action( 'wp_enqueue_scripts',         array( $this, 'scripts' ),       10 );
      add_action( 'wp_enqueue_scripts',         array( $this, 'child_scripts' ), 30 ); // After WooCommerce.
      add_action( 'admin_enqueue_scripts',      array( $this, 'admin_scripts') );
      add_filter( 'body_class',                 array( $this, 'body_classes' ) );
      add_filter( 'wp_page_menu_args',          array( $this, 'page_menu_args' ) );
      add_filter( 'navigation_markup_template', array( $this, 'navigation_markup_template' ) );
      add_action( 'wp_footer',                  array( $this, 'get_structured_data' ) );
      
      add_action( 'wp_ajax_nopriv_ajax_pagination', array( $this, 'my_ajax_pagination' ) );
      add_action( 'wp_ajax_ajax_pagination', array( $this, 'my_ajax_pagination' ) );
    }
    
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    public function setup() {
      /*
       * Load Localisation files.
       *
       * Note: the first-loaded translation file overrides any following ones if the same translation is present.
       */

      // Loads wp-content/languages/themes/localshop-it_IT.mo.
      load_theme_textdomain( 'localshop', trailingslashit( WP_LANG_DIR ) . 'themes/' );

      // Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
      load_theme_textdomain( 'localshop', get_stylesheet_directory() . '/languages' );

      // Loads wp-content/themes/localshop/languages/it_IT.mo.
      load_theme_textdomain( 'localshop', get_template_directory() . '/languages' );
      
      /**
       * Add default posts and comments RSS feed links to head.
       */
      add_theme_support( 'automatic-feed-links' );

      /*
       * Enable support for Post Thumbnails on posts and pages.
       *
       * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
       */
      add_theme_support( 'post-thumbnails' );

      /**
       * Enable support for site logo
       */
      add_theme_support( 'custom-logo', array(
        'height'      => 110,
        'width'       => 470,
        'flex-width'  => true,
      ) );

      // This theme uses wp_nav_menu() in two locations.
      register_nav_menus( array(
        'primary'   => __( 'Primary Menu', 'localshop' ),
        'secondary' => __( 'Secondary Menu', 'localshop' ),
        'handheld'  => __( 'Handheld Menu', 'localshop' ),
      ) );

      /*
       * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
       * to output valid HTML5.
       */
      add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'widgets',
      ) );

      // Setup the WordPress core custom background feature.
      add_theme_support( 'custom-background', apply_filters( 'localshop_custom_background_args', array(
        'default-color' => apply_filters( 'localshop_default_background_color', 'ffffff' ),
        'default-image' => '',
      ) ) );

      /**
       *  Add support for the Site Logo plugin and the site logo functionality in JetPack
       *  https://github.com/automattic/site-logo
       *  http://jetpack.me/
       */
      add_theme_support( 'site-logo', array( 'size' => 'full' ) );

      // Declare WooCommerce support.
      add_theme_support( 'woocommerce' );

      // Declare support for title theme feature.
      add_theme_support( 'title-tag' );

      // Declare support for selective refreshing of widgets.
      add_theme_support( 'customize-selective-refresh-widgets' );
    }

    /**
     * Register widget area.
     *
     * @link http://codex.wordpress.org/Function_Reference/register_sidebar
     */
    public function widgets_init() {
      $sidebar_args['sidebar'] = array(
        'name'          => __( 'Sidebar', 'localshop' ),
        'id'            => 'sidebar-1',
        'description'   => ''
      );

      $sidebar_args['header'] = array(
        'name'        => __( 'Below Header', 'localshop' ),
        'id'          => 'header-1',
        'description' => __( 'Widgets added to this region will appear beneath the header and above the main content.', 'localshop' ),
      );

      $footer_widget_regions = apply_filters( 'localshop_footer_widget_regions', 4 );

      for ( $i = 1; $i <= intval( $footer_widget_regions ); $i++ ) {
        $footer = sprintf( 'footer_%d', $i );

        $sidebar_args[ $footer ] = array(
          'name'        => sprintf( __( 'Footer %d', 'localshop' ), $i ),
          'id'          => sprintf( 'footer-%d', $i ),
          'description' => sprintf( __( 'Widgetized Footer Region %d.', 'localshop' ), $i )
        );
      }

      foreach ( $sidebar_args as $sidebar => $args ) {
        $widget_tags = array(
          'before_widget' => '<div id="%1$s" class="widget %2$s">',
          'after_widget'  => '</div>',
          'before_title'  => '<span class="gamma widget-title">',
          'after_title'   => '</span>'
        );

        /**
         * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
         *
         * 'localshop_header_widget_tags'
         * 'localshop_sidebar_widget_tags'
         *
         * localshop_footer_1_widget_tags
         * localshop_footer_2_widget_tags
         * localshop_footer_3_widget_tags
         * localshop_footer_4_widget_tags
         */
        $filter_hook = sprintf( 'localshop_%s_widget_tags', $sidebar );
        $widget_tags = apply_filters( $filter_hook, $widget_tags );

        if ( is_array( $widget_tags ) ) {
          register_sidebar( $args + $widget_tags );
        }
      }
    }

    /**
     * Enqueue scripts and styles.
     *
     * @since  1.0.0
     */
    public function scripts() {
      global $localshop_version;

      /**
       * Styles
       */
      wp_enqueue_style( 'localshop-normalize-style', get_template_directory_uri() . '/assets/css/normalize.css', '', $localshop_version );
      wp_enqueue_style( 'localshop-fonts-style', get_template_directory_uri() . '/assets/css/fonts.css', '', $localshop_version );
      wp_enqueue_style( 'localshop-fontawesome-style', get_template_directory_uri() . '/assets/fonts/FontAwesome/css/font-awesome.css', '', $localshop_version );
      
      wp_enqueue_style( 'localshop-style', get_template_directory_uri() . '/style.css', '', $localshop_version );
      
      wp_enqueue_style( 'localshop-contactform', get_template_directory_uri() . '/assets/css/contactform.css', '', $localshop_version );
      wp_enqueue_style( 'localshop-social', get_template_directory_uri() . '/assets/css/social.css', '', $localshop_version );
      wp_enqueue_style( 'localshop-loaders-style', get_template_directory_uri() . '/assets/css/loaders.css', '', $localshop_version );
      
      wp_enqueue_style( 'localshop-wishlist', get_template_directory_uri() . '/assets/css/ti-wishlist.css', '', $localshop_version );
      
      wp_style_add_data( 'localshop-style', 'rtl', 'replace' );


      /**
       * Scripts
       */
      wp_enqueue_script( 'localshop-css_browser_selector', get_template_directory_uri() . '/assets/js/css_browser_selector_dev.js', array(), $localshop_version, false );
      wp_enqueue_script( 'localshop-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array( 'jquery' ), $localshop_version, true );
      wp_enqueue_script( 'localshop-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.min.js', array(), $localshop_version, true );
      wp_enqueue_script( 'localshop-functions', get_template_directory_uri() . '/assets/js/functions.js', array('jquery'), $localshop_version, true );
      
      wp_enqueue_script( 'ajax-pagination', get_template_directory_uri() . '/assets/js/ajax-pagination.js', array('jquery'), $localshop_version, true );
      wp_localize_script( 'ajax-pagination', 'ajax_pagination', array('ajax_url' => admin_url( 'admin-ajax.php' ) ));
      
      if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
      }
    }


    /**
     * Enqueue child theme stylesheet.
     * A separate function is required as the child theme css needs to be enqueued _after_ the parent theme
     * primary css and the separate WooCommerce css.
     *
     * @since  1.5.3
     */
    public function child_scripts() {
      if ( is_child_theme() ) {
        wp_enqueue_style( 'localshop-child-style', get_stylesheet_uri(), '' );
      }
    }

    /**
     * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
     *
     * @param array $args Configuration arguments.
     * @return array
     */
    public function page_menu_args( $args ) {
      $args['show_home'] = true;
      return $args;
    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function body_classes( $classes ) {
      // Adds a class of group-blog to blogs with more than 1 published author.
      if ( is_multi_author() ) {
        $classes[] = 'group-blog';
      }

      if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
        $classes[]  = 'no-wc-breadcrumb';
      }

      /**
       * What is this?!
       * Take the blue pill, close this file and forget you saw the following code.
       * Or take the red pill, filter localshop_make_me_cute and see how deep the rabbit hole goes...
       */
      $cute = apply_filters( 'localshop_make_me_cute', false );

      if ( true === $cute ) {
        $classes[] = 'localshop-cute';
      }

      // If our main sidebar doesn't contain widgets, adjust the layout to be full-width.
      if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'localshop-full-width-content';
      }

      return $classes;
    }

    /**
     * Custom navigation markup template hooked into `navigation_markup_template` filter hook.
     */
    public function navigation_markup_template() {
      $template  = '<nav id="post-navigation" class="navigation %1$s" role="navigation" aria-label="Post Navigation">';
      $template .= '<span class="screen-reader-text">%2$s</span>';
      $template .= '<div class="nav-links">%3$s</div>';
      $template .= '</nav>';

      return apply_filters( 'localshop_navigation_markup_template', $template );
    }

    /**
     * Sets `self::structured_data`.
     *
     * @param array $json
     */
    public static function set_structured_data( $json ) {
      if ( ! is_array( $json ) ) {
        return;
      }

      self::$structured_data[] = $json;
    }

    /**
     * Outputs structured data.
     *
     * Hooked into `wp_footer` action hook.
     */
    public function get_structured_data() {
      if ( ! self::$structured_data ) {
        return;
      }

      $structured_data['@context'] = 'http://schema.org/';

      if ( count( self::$structured_data ) > 1 ) {
        $structured_data['@graph'] = self::$structured_data;
      } else {
        $structured_data = $structured_data + self::$structured_data[0];
      }

      echo '<script type="application/ld+json">' . wp_json_encode( $this->sanitize_structured_data( $structured_data ) ) . '</script>';
    }

    /**
     * Sanitizes structured data.
     *
     * @param  array $data
     * @return array
     */
    public function sanitize_structured_data( $data ) {
      $sanitized = array();

      foreach ( $data as $key => $value ) {
        if ( is_array( $value ) ) {
          $sanitized_value = $this->sanitize_structured_data( $value );
        } else {
          $sanitized_value = sanitize_text_field( $value );
        }

        $sanitized[ sanitize_text_field( $key ) ] = $sanitized_value;
      }

      return $sanitized;
    }
    
    public function create_custom_tables() {
      global $wpdb;
      
      $charset_collate = $wpdb->get_charset_collate();
      
      $table_name = $wpdb->prefix . 'newsletters_list';

      $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) DEFAULT '',
        date DATETIME NULL DEFAULT NULL,
        UNIQUE KEY id (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
      
      
      $table_name = $wpdb->prefix . 'localshop_options';

      $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) DEFAULT '',
        value TEXT NULL,
        UNIQUE KEY id (id)
      ) $charset_collate;";

      dbDelta( $sql );
    }
   
    public function admin_scripts() {
      global $localshop_version;
      
      wp_enqueue_style( 'localshop-admin-style', get_template_directory_uri() . '/assets/css/admin/admin-style.css', '', $localshop_version );

      // wp_enqueue_script( 'localshop-admin-script', get_template_directory_uri() . '/assets/js/admin/admin-script.js', array('jquery'), $localshop_version, false );
    }
    
    
    function my_ajax_pagination() {
      // ---
      // $page = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
      $page = ( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
      
      $args = array(
        'post_type' => 'product',
        'posts_per_page' => 4,
        'paged' => $page
        );
      $loop = new WP_Query( $args );
      
      if ( $loop->have_posts() ) {
        wc_set_loop_prop( 'total', $loop->found_posts );
        wc_set_loop_prop( 'total_pages', $loop->max_num_pages );
        wc_set_loop_prop( 'current_page', $page );
        woocommerce_pagination();
        
        // ------------
        echo '<ul>';
        while ( $loop->have_posts() ) : $loop->the_post();
          global $product;
          if ( empty( $product ) || ! $product->is_visible() ) {
            continue;
          }
          ?><li <?php wc_product_class( '', $product ); ?>>
            <div class="product-image">
              <a href="<?php echo get_the_permalink(); ?>" class="woocommerce-LoopProduct-link">
                <div class="img-wrapper">
                <?php echo woocommerce_get_product_thumbnail(); ?>
                </div>
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
            
            </div>
          </li><?php endwhile;
        echo '</ul><!--/.products-->';
      }
      wp_reset_postdata();
      // ---
      
      die();
    }
    
  }
endif;

return new Localshop();
