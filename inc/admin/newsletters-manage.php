<?php
class NewslettersManagePage
{
  private $options;

  public function __construct() {
    add_action( 'admin_init', array( $this, 'page_init' ) );
    add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
    
    add_action( 'wp_ajax_send_newsletter', array( $this, 'action_send_newsletter' ) );
    add_action( 'wp_ajax_delete_newsletter', array( $this, 'action_delete_newsletter' ) );
    add_action( 'wp_ajax_send_newsletter_all', array( $this, 'action_send_newsletter_all' ) );
  }
  
  public function page_init() {
    if( isset( $_POST['save_newsletters_template_nonce'] )
      && wp_verify_nonce( $_POST['save_newsletters_template_nonce'], 'save_newsletters_template_nonce' )
      && isset($_POST['save_newsletters_template']) )
    {
      $email_subject = sanitize_text_field($_POST['email-subject']);
      $email_body = esc_textarea($_POST['email-body']);
      
      localshop_set_theme_option('newsletter_email_subject', $email_subject);
      localshop_set_theme_option('newsletter_email_body', $email_body);
    }
  }
  
  public function add_menu_page() {
    // add_theme_page(
    // add_options_page(
    //   __( 'Newsletters', 'localshop' ),
    //   __( 'Newsletters', 'localshop' ),
    //   'manage_options',
    //   'newsletters_manage',
    //   array( $this, 'create_admin_page' )
    // );
    
    // add_menu_page(
    //   __( 'Newsletters', 'localshop' ),
    //   __( 'Newsletters', 'localshop' ),
    //   'manage_options',
    //   'newsletters_manage',
    //   array( $this, 'create_admin_page' ),
    //   '',
    //   60
    // );
    
    add_submenu_page(
      'themes.php',
      __( 'Newsletters', 'localshop' ),
      __( 'Newsletters', 'localshop' ),
      'manage_options',
      'newsletters_manage',
      array( $this, 'create_admin_page' )
    );
  }

  public function create_admin_page() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'newsletters_list';
    $newsletters_list = $wpdb->get_results('select * from ' . $table_name . ' order by date desc');
    
    $emailSubject = sanitize_text_field(localshop_get_theme_option('newsletter_email_subject'));
    $emailBody = esc_textarea(localshop_get_theme_option('newsletter_email_body'));
    // $emailBody = html_entity_decode($emailBody);
    $emailBody = stripslashes(html_entity_decode($emailBody));
    
    ?>
      <div class="wrap">
        <h2><?php echo __( 'Newsletters Manager', 'localshop' )?></h2>
        
        <div class="newsletters-main">
          <table class="newsletters-list">
            <tr>
              <th>Email</th>
              <th>Date Added</th>
              <th>Operations</th>
            </tr>
          
            <?php
              foreach($newsletters_list as $row){
                ?>
                  <tr data-email-id="<?= $row->id ?>">
                    <td><?= $row->email ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($row->date)) ?></td>
                    <td>
                      <button class="btnSendNewsletter newsletter-button first">Send</button><button class="btnDeleteNewsletter newsletter-button">Delete</button>
                    </td>
                  </tr>
                <?php
              }
            ?>
          </table>
          
          <button id="btnSendAllNewsletters" class="newsletter-button">Send All</button>
        </div>
        
        <div class="newsletters-template">
          <div class="form-wrapper">
            <form method="post" action="">
              <div class="form-group">
                <label for="email-subject" class="control-label">Email Subject</label>
                <div class="controls">
                  <input type="text" id="email-subject" name="email-subject" class="control-input" value="<?=$emailSubject?>">
                </div>
              </div>
              
              <div class="form-group">
                <label for="email-body" class="control-label">Email Body</label>
                <div class="controls">
                  <textarea id="email-body" name="email-body" class="control-input"><?=$emailBody?></textarea>
                </div>
              </div>
              
              <input type="hidden" name="save_newsletters_template" value="true" />
              
              <div class="template-preview">
                <button id="preview">Preview</button>
              </div>
              
              <?php
                wp_nonce_field( 'save_newsletters_template_nonce', 'save_newsletters_template_nonce' );
                submit_button();
              ?>
            </form>
          </div>
        </div>
      </div>
    
    <?php
  }

  public function action_send_newsletter() {
    if($this->validate_id_email()){
      $id_email = $_POST['id_email'];
      
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'newsletters_list';
      $sql = $wpdb->prepare('select email from ' . $table_name . ' where id=%d', $id_email);
      $res = $wpdb->get_row($sql);
      
      $res = $this->send_newsletters_email($res->email);
      
      echo $res;
    }
    else{
      echo false;
    }
    
    wp_die();
  }
  
  public function action_delete_newsletter() {
    if($this->validate_id_email()){
      $id_email = $_POST['id_email'];
      
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'newsletters_list';
      $res = $wpdb->delete($table_name, array('id' => $id_email), '%d');
      
      echo $res;
    }
    else{
      echo false;
    }
    
    wp_die();
  }
  
  public function action_send_newsletter_all() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'newsletters_list';
    $newsletters_list = $wpdb->get_results('select * from ' . $table_name);
    
    $errors = '';
    $error_send_list = array();
    $response = array();
    
    foreach ($newsletters_list as $row) {
      try {
        $res = $this->send_newsletters_email($row->email);
        
        if(!$res){
          $error_send_list[] = $emailTo;
        }
      } 
      catch (Exception $e) {
        $errors .= $e->getMessage() . '\n';
      }
    }
    
    if(strlen($errors)){
      $response["status"] = 'error_exception';
      $response["ERRORS"] = $errors;
    }
    else if(count($error_send_list)){
      $response["status"] = 'error_send';
      $response["error_send_list"] = $error_send_list;
    }
    else{
      $response["status"] = 'success';
    }
    
    echo json_encode($response);
    
    wp_die();
  }
  
  
  // ---------------------- service ----------------------
  
  public function validate_id_email() {
    if(isset($_POST['id_email'])){
      $id_email = intval($_POST['id_email']);
      if($id_email){
        return true;
      }
    }
    
    return false;
  }
  
  public function send_newsletters_email($emailTo){
    $emailFrom = get_option('admin_email');
    $contactName = get_option('blogname');;
    
    $emailSubject = localshop_get_theme_option('newsletter_email_subject');
    // $emailBody = html_entity_decode(localshop_get_theme_option('newsletter_email_body'));
    $emailBody = stripslashes(html_entity_decode(localshop_get_theme_option('newsletter_email_body')));
    // $headers = 'From: '.$contactName.' <'.$emailFrom.'>';
    
    $headers = "MIME-Version: 1.0\n";
    $headers .= "Content-type:text/html;charset=UTF-8\n";
    $headers .= 'From: '.$contactName.' <'.$emailFrom.'>';
    
    $res = wp_mail($emailTo, $emailSubject, $emailBody, $headers);
    
    return $res;
  }
  
}

if( is_admin() ){
  $newsletters_manage_page = new NewslettersManagePage();
}
