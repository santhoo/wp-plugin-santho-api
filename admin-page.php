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
  // Registra grupo de opções
  register_setting( 'sapi-fields', 'sapi_settings' );

  // Adiciona seção de campos de opções
  add_settings_section(
    'sapi-plugin-section', 
    'Santho API',
    'sapi_settings_section_callback',
    'sapi-fields'
  );

  // Campo opção - Login slug
  add_settings_field(
    'sapi_login_slug',
    'Novo caminho URL de login',
    'sapi_login_slug_cb',
    'sapi-fields',
    'sapi-plugin-section',
  );

  // Campo opção - Redirecionamento após logout
  add_settings_field(
    'sapi_logout_redir',
    'Redirecionamento no logout',
    'sapi_logout_redir_cb',
    'sapi-fields',
    'sapi-plugin-section',
  );
});


function sapi_settings_section_callback() {
  echo 'Wordpress API Reset Plugin - Defina as opções básicas para o Santho API';
}

function sapi_login_slug_cb() {
  $defaults = SAPI_CUSTOM_LOGIN;
  $group = 'sapi_settings';
  $options = get_option( $group );
  $name = 'sapi_login_slug';
  $value = (!isset($options[$name]) || empty($options[$name]) ) ? $defaults['slug'] : $options[$name];

  $html = '<input type="text"';
  $html .= ' name="' . $group . '['. $name .']"';
  $html .= ' value="' . $value . '"';
  $html .= ' class="regular-text"';
  $html .= '>';

  echo $html;
}

function sapi_logout_redir_cb() {
  $group = 'sapi_settings';
  $options = get_option( $group );
  $name = 'sapi_login_redir';
  $value = (!isset($options[$name]) || empty($options[$name]) ) ? null : $options[$name];

  $html = '<input type="url"';
  $html .= ' name="' . $group . '['. $name .']"';
  $html .= ' value="' . $value . '"';
  $html .= ' class="regular-text"';
  $html .= '>';

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