<?php
/**
 * Funções para o plugin de API
 */

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


// Bloqueia todo o acesso ao site
// Libera tela de login
add_action( 'after_setup_theme', function () {
  $clogin = SAPI_CUSTOM_LOGIN;

  if (
  is_admin() ||
  is_user_logged_in() ||
  ( $GLOBALS['pagenow'] === 'wp-login.php' && isset( $_REQUEST[$clogin['key']] ) && $_REQUEST[$clogin['key']] === $clogin['slug'] ) ||
  ( $GLOBALS['pagenow'] === 'wp-login.php' && !empty( $_POST ) )
  )
    return;

  sapi_redir404();
}, 10);


// Cria novo caminho para login usando query secreta
add_action( 'after_setup_theme', function () {

  $clogin = SAPI_CUSTOM_LOGIN;

  // Verifica se tem slug com home + masterpanel
  $safe_slug = home_url(
    $path = $clogin['slug'],
    $scheme = 'relative'
  );

  if ( strpos($_SERVER['REQUEST_URI'], $safe_slug) !== false ) {

    $destiny = esc_url( add_query_arg(
      $clogin['key'], $clogin['slug'],
      home_url('/') . 'wp-login.php'
    ));

    wp_redirect( $destiny );
    exit();
  }
}, 9);


// Define nova URL para 'Esqueci minha senha'
add_filter( 'lostpassword_url',  function () {
  $clogin = SAPI_CUSTOM_LOGIN;

  return esc_url( add_query_arg(
    array(
      $clogin['key'] => $clogin['slug'],
      'action' => 'lostpassword',
    ),
    home_url('/') . 'wp-login.php'
  ));
}, 10, 0 );


// Define nova URL para 'Login'
add_filter( 'login_url', function ( $login_url, $redirect, $force_reauth ) {
  $clogin = SAPI_CUSTOM_LOGIN;

  $login_url = esc_url( add_query_arg(
    $clogin['key'], $clogin['slug'],
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
  wp_redirect( esc_url(SAPI_LOGOUT_URL) );
  exit();
});


// Evita o redirecionamento para a 'login url' no caminho /wp-admin
add_action( 'init', function () {
  remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
});

add_filter( 'auth_redirect_scheme', function () {
  if ( $user_id = wp_validate_auth_cookie( '',  $scheme) ) {
    return $scheme;
  }

  sapi_redir404();
}, 9999 );


// add_filter( 'wp_redirect_admin_locations', '__return_false' );

// add_action( 'after_setup_theme', function () {
//   $safe_slug = home_url( $path = 'masterpanel', $scheme = 'relative' );

//   var_dump( strpos($_SERVER['REQUEST_URI'], $safe_slug) );
// });