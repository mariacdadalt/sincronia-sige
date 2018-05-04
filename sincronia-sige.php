<?php
/*
Plugin Name: Sincronia SIGE
Plugin URI: https://sysal.com.br
Description: Sysal is a system developed by Algo Assim.
Version: 0.0.0.1
Author: Algo Assim - Serviços para Internet
Author URI: https://algoassim.com.br
*/

//add_action('init', array('sincronia_sige', 'init'));
//add_action('admin_init', array('sincronia_sige', 'admin_init'));
add_action('admin_menu', array('sincronia_sige','init'));

class Sincronia_SIGE
{
  public static function admin_init()
  {
    //Code that runs only on admin.
    return;
  }

  public static function init()
  {

    if (is_admin())
    {
        add_options_page('Sincronia com SIGE', 'Sincronia com SIGE', 'manage_options', 'sincronia_sige', array('sincronia_sige','construct_menu'));
    }

  }

  public static function construct_menu()
  {

      if ($_POST["min"] > 0) {
          include_once 'sige-stock.php';
          $api = new SIGE_Stock();
          for ($i= $_POST["min"] ; $i <= $_POST["max"]; $i++) {
            $api->run_sige($i);
          };
      }

      include_once 'view-menu.php';
  }
}
