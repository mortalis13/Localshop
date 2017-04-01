<?php
class ThemeOptionsPage
{
    private $options;

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    }

    public function add_plugin_page()
    {
        add_theme_page(
            __( 'Theme Options', 'localshop' ),
            __( 'Theme Options', 'localshop' ),
            'edit_theme_options', 
            'theme_options', 
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'custom_theme_options' );
        
        ?>
        <div class="wrap">
          <h2><?php echo wp_get_theme()  .': '. __( 'Theme Options', 'localshop' )?></h2>

          <form method="post" action="options.php">
          <?php
              // This prints out all hidden setting fields
              settings_fields( 'theme_option_group' );
              do_settings_sections( 'theme_options' );
              submit_button();
          ?>
          </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'theme_option_group', // Option group
            'custom_theme_options', // Option name
            array( $this, 'validate_options' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('General settings', 'localshop'), // Title
            array( $this, 'print_section_info' ), // Callback
            'theme_options' // Page
        );  

        add_settings_field(
            'about-us',
            __('About Us', 'localshop'),
            array( $this, 'about_us_callback' ), 
            'theme_options', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function validate_options( $input )
    {
        $new_input = array();
        
        // if( isset( $input['id_number'] ) )
        //     $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['about_us'] ) )
            $new_input['about_us'] = sanitize_text_field( $input['about_us'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function about_us_callback()
    {
      $about_us_text = isset( $this->options['about_us'] ) ? esc_attr( $this->options['about_us']) : '';
      ?>
      
      <p class="admin-option">
        <textarea name="custom_theme_options[about_us]" id="about_us" cols="50" rows="10"><?php echo esc_textarea($about_us_text) ?></textarea>
      </p>
      
      <?php
    }
}

if( is_admin() ){
    $theme_options_page = new ThemeOptionsPage();
}
