<?php

add_action( 'admin_init', 'starter_options_init' );
add_action( 'admin_menu', 'starter_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function starter_options_init(){
  // register_setting('starter_options_group', 'starter_general_options','theme_options_validate');
}

/**
 * Load up the menu page
 */
function starter_options_add_page() {
  add_theme_page( __( 'Theme Options', 'localshop' ), __( 'Theme Options', 'localshop' ), 'edit_theme_options', 'theme_options', 'starter_options_do_page' );
}

/**
 * Create the options page and process requests
 */
function starter_options_do_page() {
  if(isset($_GET['subpage'])){
    $subpage=$_GET['subpage'];
    switch($subpage){
      case 'editor_shortcuts':
        require(dirname(__FILE__).'/editor-shortcuts.php');
        break;
      case 'css_snippets':
        require(dirname(__FILE__).'/css-snippets.php');
        break;
      case 'emmet_abbr':
        require(dirname(__FILE__).'/emmet-abbr.php');
        break;
    }
  }
  else{
  ?>
  
    <div class="wrap">
      <h2><?php echo wp_get_theme()  .': '. __( 'Theme Options', 'localshop' )?></h2>

      <?php if ( !empty($_GET['status']) && $_GET['status'] == 'reset' ) : ?>
        <div class="updated fade"><p><strong><?php _e( 'Style Has Been Reset', 'localshop' ); ?></strong></p></div>
      <?php endif; ?>
      
      <form method="post" id="options_form" action="options.php">
        <?php settings_fields('starter_options_group') ?>

        <p>
          <?php submit_button(); ?>
        </p>
      </form>
      
    </div>
  
  <?php
  }
}

/**
 * Validation function for checkboxes and textarea
 */
function theme_options_validate( $input ) {
  $checkboxes=array(
    'disable_image_cropping',
    'use_custom_css',
    'use_category_colors'
  );
  
  foreach($checkboxes as $ch){
    if(!isset($input[$ch])) $input[$ch] = null;
    $input[$ch]=($input[$ch]? 1 : 0 );
  }
  
  $input['custom_css'] = wp_filter_post_kses( $input['custom_css'] );
  
  return $input;
}

/**
 * Restore factory color scheme
 */
function starter_reset_customizer_settings() {
  if( empty( $_POST['starter_reset_customizer'] ) || 'starter_reset_customizer_settings' !== $_POST['starter_reset_customizer'] )
    return;
  if( ! wp_verify_nonce( $_POST['starter_reset_customizer_nonce'], 'starter_reset_customizer_nonce' ) )
    return;
  if( ! current_user_can( 'manage_options' ) )
    return;
  
  delete_option('starter_scheme_options');
  wp_safe_redirect( admin_url( 'themes.php?page=theme_options&status=reset' ) ); exit;
}
add_action( 'admin_init', 'starter_reset_customizer_settings' );
