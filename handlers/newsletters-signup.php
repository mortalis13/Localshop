<?php

require_once("../../../../wp-load.php");

if(isset($_POST['nssignup_submit'])){
  $email = $_POST['signup-email'];
  $res = localshop_insert_newsletters_email($email);
  echo $res?'true': 'false';
}

?>