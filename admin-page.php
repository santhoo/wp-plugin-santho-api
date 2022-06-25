<?php
/**
 * Página e configurações do plugin o Painel Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Adiciona página no sub-menu "Configurações"
add_action( 'admin_menu', function() {
  add_submenu_page(
    'options-general.php', // slug name for the parent menu
    'Santho API Plugin', // text to be displayed in the title tags of the page
    'Santho API', // text to be used for the menu
    'manage_options', // user capability required for this menu to be displayed
    'sapi_plugin', // slug name to refer to this menu by
    'sapi_plugin_page' // function to be called to output the content for this page
  );
});


add_action( 'admin_init', function () {

  $df_group = SAPI_SET_GROUP;

  // Registra grupo de opções
  register_setting( 'sapi-fields', $df_group );

  // Adiciona seção de campos de opções
  add_settings_section(
    'sapi-plugin-section', 
    __( 'Santho API' ),
    'sapi_settings_section_callback',
    'sapi-fields'
  );

  // Campo opção - Login slug

  $df_cl = SAPI_CUSTOM_LOGIN;
  $option_slug = sapi_setting('sapi_login_slug');
  $login_url = get_home_url('', (
    ( !isset($option_slug) || empty($option_slug) )
    ? $df_cl['slug']
    : sanitize_title($option_slug) )
  );
  add_settings_field(
    'sapi_login_slug',
    __( 'Novo caminho URL de login' ),
    'sapi_render_text_field',
    'sapi-fields',
    'sapi-plugin-section',
    array(
      'st_group'    => $df_group,
      'label_for'   => 'sapi_login_slug',
      'default'     => $df_cl['slug'],
      'description' => __( 'Login: <a target="_blank" href="' . $login_url . '">' . $login_url . '</a>' )
    )
  );

  // Campo opção - Redirecionamento após logout
  add_settings_field(
    'sapi_logout_redir',
    __( 'Redirecionamento no logout' ),
    'sapi_render_text_field',
    'sapi-fields',
    'sapi-plugin-section',
    array(
      'st_group'    => $df_group,
      'label_for'   => 'sapi_logout_redir',
      'type'        => 'url',
      'description' => __( 'URL destino para redirecionamento quando o usuário fizer logout.' )
    )
  );
});


function sapi_settings_section_callback() {
  echo __( '<h4 style="margin-bottom:0">Wordpress API Reset Plugin</h4>' );
  echo __ ( 'Defina as opções básicas para Santho API:' );
}

function sapi_render_text_field( $args ) {
  $group = $args['st_group'];
  $options = get_option( $group );

  $name = $args['label_for'];
  $type = (isset($args['type']) ? $args['type'] : 'text');
  $description = $args['description'];

  if ( !isset($options[$name]) || empty($options[$name]) ) {
    if ( isset($args['default']) ) {
      $value = $args['default'];
    }
    else {
      $value = null;
    }
  }
  else {
    $value = $options[$name];
  }


  $html = '<input type="' . $type . '"';
  $html .= ' name="' . $group . '['. $name .']"';
  $html .= ' id="' . $name .'"';
  $html .= ' value="' . $value . '"';
  $html .= ' class="regular-text"';
  $html .= '>';

  if ( isset($description) ) {
    $html .= '<p class="description">' . $description .'</p>';
  }

  echo $html;
}


function sapi_plugin_page() {
  ?>
    <form action='options.php' method='post'> <?php
      settings_fields( 'sapi-fields' );
      do_settings_sections( 'sapi-fields' );
      submit_button(); ?>
    </form>
  <?php
}