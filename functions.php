<?php
/**
 * Funções para o plugin de API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Carrega template para página 404
add_filter( '404_template', function () {
  return SAPI_PATH . 'templates/404.php';
});


// Redirecionamento 404
function sapi_redir404() {
  global $wp_query;
  $wp_query->set_404();

  status_header( 404 );
  nocache_headers();

  require get_404_template();
  exit;
}


// Retorna valor de settings do grupo
function sapi_setting( $setting ) {
  $group = get_option(SAPI_SET_GROUP);
  
  if ( isset($group[$setting]) && !empty($group[$setting]) ) {
    return $group[$setting];
  }
  else {
    return false;
  }
}


// Retorna slug para página de login escondida
function sapi_get_custom_login() {
  $df_cl = SAPI_CUSTOM_LOGIN;
  $login_slug = sapi_setting('sapi_login_slug');

  if ( $login_slug ) {
    $slug = sanitize_title($login_slug);
  }
  else {
    $slug = $df_cl['slug'];
  }

  return array(
    'slug' => $slug,
    'key'  => $df_cl['key'],
  );
}

// Bloqueia todo o acesso ao site
// Libera tela de login
add_action( 'after_setup_theme', function () {
  
  $login = sapi_get_custom_login();

  if (
  is_admin() ||
  is_user_logged_in() ||
  ( $GLOBALS['pagenow'] === 'wp-login.php' && isset( $_REQUEST[$login['key']] ) && $_REQUEST[$login['key']] === $login['slug'] ) ||
  ( $GLOBALS['pagenow'] === 'wp-login.php' && !empty( $_POST ) )
  )
    return;

  sapi_redir404();
}, 10);


// Cria novo caminho para login usando query secreta
add_action( 'after_setup_theme', function () {

  $login = sapi_get_custom_login();

  // Verifica se tem slug com home + masterpanel
  $safe_slug = home_url(
    $path = $login['slug'],
    $scheme = 'relative'
  );

  if ( strpos($_SERVER['REQUEST_URI'], $safe_slug) !== false ) {

    $destiny = esc_url( add_query_arg(
      $login['key'], $login['slug'],
      home_url('/') . 'wp-login.php'
    ));

    wp_redirect( $destiny );
    exit();
  }
}, 9);


// Define nova URL para 'Esqueci minha senha'
add_filter( 'lostpassword_url',  function () {
  $login = sapi_get_custom_login();

  return esc_url( add_query_arg(
    array(
      $login['key'] => $login['slug'],
      'action' => 'lostpassword',
    ),
    home_url('/') . 'wp-login.php'
  ));
}, 10, 0 );


// Define nova URL para 'Login'
add_filter( 'login_url', function ( $login_url, $redirect, $force_reauth ) {
  $login = sapi_get_custom_login();

  $login_url = esc_url( add_query_arg(
    $login['key'], $login['slug'],
    home_url('/') . 'wp-login.php'
  ));

  if ( ! empty( $redirect ) ) {
      $login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
  }

  if ( $force_reauth ) {
      $login_url = add_query_arg( 'reauth', '1', $login_url );
  }

  return $login_url;
}, 10, 3 );


// Redireciona para o site após o logout
add_action( 'wp_logout', function () {
  $setting_ur_logout = sapi_setting('sapi_logout_redir');

  $url = (!isset($setting_ur_logout) || empty($setting_ur_logout) ? get_home_url() : $setting_ur_logout );

  wp_redirect( esc_url($url) );
  exit();
});


// Evita o redirecionamento para a 'login url' no caminho /wp-admin
add_action( 'init', function () {
  remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
});

add_filter( 'auth_redirect_scheme', function ( $scheme ) {
  if ( $user_id = wp_validate_auth_cookie( '',  $scheme) ) {
    return $scheme;
  }

  sapi_redir404();
}, 9999 );