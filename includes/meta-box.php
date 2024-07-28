<?php
/*
Plugin Name: Medra Metadata Fetcher
Description: Fetches and displays metadata from Medra using DOI.
Version: 1.0
Author: Srikanth Gopi
*/
add_action('add_meta_boxes', 'medra_add_meta_box');

function medra_add_meta_box() {
    add_meta_box(
        'medra_meta_box',
        'Medra Metadata',
        'medra_meta_box_callback',
        'post',
        'side'
    );
}

function medra_meta_box_callback($post) {
    wp_nonce_field('medra_save_meta_box_data', 'medra_meta_box_nonce');
    $value = get_post_meta($post->ID, '_medra_doi', true);
    echo '<label for="medra_doi">DOI Number</label>';
    echo '<input type="text" id="medra_doi" name="medra_doi" value="' . esc_attr($value) . '" size="25" />';
}
?>
