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
  'key'   => 'safe',
));

define( 'SAPI_LOGOUT_URL', 'https://www.proft.sale/' );


// Carrega as funções do plugin
require SAPI_PATH . 'functions.php';

/**
 * Carrega os arquivos com as funções específicas
 */

// Yoast SEO
// add_filter( 'disable_wpseo_json_ld_search', '__return_true' );
// add_filter(
// 	'wpseo_frontend_presenter_classes'
// 	, function($filter) {
// 			return array_diff($filter, [
// 					'Yoast\WP\SEO\Presenters\Open_Graph\Article_Published_Time_Presenter',
// 					'Yoast\WP\SEO\Presenters\Open_Graph\Article_Modified_Time_Presenter',
// 			]);
// 	}
// );

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