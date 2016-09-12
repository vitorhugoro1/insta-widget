<?php
/**
 * Plugin Name: Insta Widget
 * Author: Vitor Hugo R Merencio
 * Version: 0.1
 * Lincese: GPLv2
 */

define('INSTA_CLIENT_KEY', '6481fc20c37e463785ecdb1772da49d1'); // Client ID
define('INSTA_CLIENT_PASS', 'c2c725c801b54dbf937170341cf0f38a'); // Cliente Secret
define('API_URL', 'https://api.instagram.com/v1/');

function generate_sig($endpoint, $params, $secret) {
  $sig = $endpoint;
  ksort($params);
  foreach ($params as $key => $val) {
    $sig .= "|$key=$val";
  }
  return hash_hmac('sha256', $sig, $secret, false);
}

function nthstrpos($haystack, $needle, $nth) {
    $count = 0;
    $pos = -1;
    do {
        $pos = strpos($haystack, $needle, $pos + 1);
        $count++;
    } while ($pos !== false && $count < $nth);
    return $pos;
}

function call_user_implicit( $key, $type = 'self' ) {
    switch($type){
      case 'media':
        $url = API_URL . "users/self/media/recent/";
        break;
      case 'liked':
        $url = API_URL . "users/self/media/liked/";
        break;
      default:
        $url = API_URL . "users/self/";
    }

    $ch = wp_remote_get( "{$url}?access_token={$key}");

  $ch = json_decode($ch['body'], true);

  return $ch['data'];
}

include plugin_dir_path( __FILE__ ) . 'insta-widget-class.php';

function insta_files_admin_load(){
  wp_enqueue_script('insta-widget', plugin_dir_url( __FILE__ ) . 'insta-widget.js', array('jquery'), '', true);
  wp_enqueue_style( 'insta-widget-style', plugin_dir_url( __FILE__ ) . 'insta-widget.css', array(), '', 'all' );
}

add_action('admin_enqueue_scripts', 'insta_files_admin_load');

function insta_files_front_load(){
  wp_enqueue_style( 'insta-widget-style', plugin_dir_url( __FILE__ ) . 'insta-widget.css', array(), '', 'all' );
}

add_action('wp_enqueue_scripts', 'insta_files_front_load');

function insta_activation(){
  register_widget( 'InstaWidget' );
}

register_activation_hook( __FILE__, add_action('widgets_init', 'insta_activation') );

function insta_deactivation(){
  unregister_widget( 'InstaWidget' );
}

register_deactivation_hook( __FILE__ , 'insta_deactivation' );
