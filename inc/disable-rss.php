<?php
/**
 * Desabilita feeds RSS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


function sapi_turn_off_feed() {
  wp_die( 'Forbidden' );
}

add_action( 'do_feed', 'sapi_turn_off_feed', 1 );
add_action( 'do_feed_rdf', 'sapi_turn_off_feed', 1 );
add_action( 'do_feed_rss', 'sapi_turn_off_feed', 1 );
add_action( 'do_feed_rss2', 'sapi_turn_off_feed', 1 );
add_action( 'do_feed_atom', 'sapi_turn_off_feed', 1 );
add_action( 'do_feed_rss2_comments', 'sapi_turn_off_feed', 1 );
add_action( 'do_feed_atom_comments', 'sapi_turn_off_feed', 1 );