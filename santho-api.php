<?php
/*
Plugin Name:  API Wordpress Reset
Plugin URI:   https://www.santho.com.br
Description:  Protect and optimize your Wordpress for API headless sites.
Version:      1.0
Author:       Santho
Author URI:   https://www.santho.com.br
Text Domain: 	sapi
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Define o caminho base para os arquivos do plugin
define( 'SAPI_PATH', plugin_dir_path( __FILE__ ) );

// Define o novo caminho para login
define( 'SAPI_CUSTOM_LOGIN', array(
  'slug'  => 'masterpanel',
  'key'   => 'sapisafetoken',
));

define( 'SAPI_SET_GROUP', 'sapi_settings' );


// Carrega as funções do plugin
require SAPI_PATH . 'functions.php';

// Define páginas e configurações do plugin no painel
require SAPI_PATH . 'admin-page.php';


// Remove tags do Wordpress do header
require SAPI_PATH . 'inc/wordpress-head.php';

// Desabilita feeds RSS
require SAPI_PATH . 'inc/disable-rss.php';

// Remove jQuery Migrate
require SAPI_PATH . 'inc/jquery-migrate.php';

// Remove WP Embed
require SAPI_PATH . 'inc/wordpress-embed.php';

// Remove WP Styles e SVG
require SAPI_PATH . 'inc/wordpress-styles.php';