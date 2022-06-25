<?php

function proft_turn_off_feed() {
  wp_die( 'Forbidden' );
}
add_action('do_feed', 'proft_turn_off_feed', 1);
add_action('do_feed_rdf', 'proft_turn_off_feed', 1);
add_action('do_feed_rss', 'proft_turn_off_feed', 1);
add_action('do_feed_rss2', 'proft_turn_off_feed', 1);
add_action('do_feed_atom', 'proft_turn_off_feed', 1);
add_action('do_feed_rss2_comments', 'proft_turn_off_feed', 1);
add_action('do_feed_atom_comments', 'proft_turn_off_feed', 1);