<?php

/**
 * Rodas e suas funções para API
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

// Prefixo/namespace do Endpoint
define('SANTHO_API_NAMESPACE', 'sapi/v1');


// Custom Endpoints
add_action('rest_api_init', function () {
  // Front-page ID
  // register_rest_route( SANTHO_API_NAMESPACE, 'frontpage', array(
  //   'methods'  => 'GET',
  //   'callback' => 'sapi_frontpage'
  // ));

  // Menu
  register_rest_route(SANTHO_API_NAMESPACE, 'menu/(?P<menu_location>[a-zA-Z0-9-]+)', array(
    'methods'  => 'GET',
    'callback' => 'sapi_menu'
  ));

  // Site
  // register_rest_route(SANTHO_API_NAMESPACE, 'site', array(
  //   'methods'  => 'GET',
  //   'callback' => 'sapi_site'
  // ));

  // Retorna CAMPOS de todos os posts de TIPO
  register_rest_route(SANTHO_API_NAMESPACE, 'all/(?P<post_type>[a-zA-Z0-9-]+)/(?P<post_prop>[a-zA-Z0-9-_,]+)', array(
    'methods'  => 'GET',
    'callback' => 'sapi_getall'
  ));
});


// CB - Front-page ID
// function sapi_frontpage() {

//   $front_page = get_option('page_on_front');

//   if ( !$front_page ) {
//     return new WP_Error( 'empty_frontpage', 'No front-page defined', array('status' => 404) );
//   }

//   $response = new WP_REST_Response( array(
//     'id' => $front_page
//   ));

//   $response->set_status(200);

//   return $response;
// }


// CB - Menu Location
function sapi_menu($request)
{

  $locations = get_nav_menu_locations();

  if (array_key_exists($request['menu_location'], $locations)) {
    $menu = wp_get_nav_menu_object($locations[$request['menu_location']]);
    $menuitems = wp_get_nav_menu_items($menu->term_id);
  }

  if (!$locations || !isset($menu) || !isset($menuitems)) {
    return new WP_Error('menu_not_found', 'No menu found', array('status' => 404));
  }

  $response = new WP_REST_Response($menuitems);

  $response->set_status(200);

  return $response;
}


// CB - Opções personalizadas do site
// function sapi_site()
// {

//   if (function_exists('get_field')) {

//     $site_page = 'options';

//     $settings = array(
//       'main' => array(
//         'about' => get_field('site_about', $site_page),
//         'manager_link' => esc_url(get_field('site_panel_manager', $site_page)),
//         'link_whatsapp' => esc_url(get_field('link_whatsapp', $site_page)),
//       ),
//       'apps' => get_field('site_app_stores', $site_page),
//       'partners' => array(
//         'title' => get_field('acredita_titulo', $site_page),
//         'list' => get_field('acredita_list', $site_page),
//       ),
//       'social' => get_field('site_social_redes', $site_page),
//     );

//     $response = new WP_REST_Response($settings);

//     $response->set_status(200);

//     return $response;
//   } else {
//     return new WP_Error('settings_not_found', 'Site settings not found', array('status' => 404));
//   }
// }


// CB - Retorna Campo de todo os posts de certo Tipo
function sapi_getall($request)
{

  $post_type = $request['post_type'];
  $post_prop = explode(',', $request['post_prop']);

  // Se o tipo de post existir e tiver campos selecionados
  if (post_type_exists($post_type) && is_array($post_prop) && !empty($post_prop)) {
    $output = get_posts_fields([
      'post_type' => $post_type,
      'fields' => $post_prop,
      'posts_per_page' => -1,
    ]);

    $response = new WP_REST_Response($output);

    $response->set_status(200);

    return $response;
  } else {
    return new WP_Error('posts_not_found', 'Posts fields not found', array('status' => 404));
  }
}
