<?php


// Remover o jQuery Migrate
add_action('wp_default_scripts', function ( $scripts ) {
  
  if ( !is_admin() && isset( $scripts->registered['jquery'] ) ) {
    $script = $scripts->registered['jquery'];

    if ( $script->deps ) {
      $script->deps = array_diff(
        $script->deps,
        array(
          'jquery-migrate',
        )
      );
    }
  }
});