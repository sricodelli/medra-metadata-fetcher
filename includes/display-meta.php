<?php
/*
Plugin Name: Medra Metadata Fetcher
Description: Fetches and displays metadata from Medra using DOI.
Version: 1.0
Author: Srikanth Gopi
*/
add_filter('the_content', 'medra_display_metadata');

function medra_display_metadata($content) {
    if (is_singular('post')) {
        global $post;
        $metadata = get_post_meta($post->ID, '_medra_metadata', true);
        if ($metadata) {
            $meta_content = '<div class="medra-metadata">';
            $meta_content .= '<h3>Metadata</h3>';
            $meta_content .= '<p><strong>Title:</strong> ' . esc_html($metadata['title']) . '</p>';
            $meta_content .= '<p><strong>Authors:</strong> ' . esc_html(implode(', ', $metadata['authors'])) . '</p>';
            $meta_content .= '<p><strong>Journal:</strong> ' . esc_html($metadata['journal']) . '</p>';
            $meta_content .= '<p><strong>Publisher:</strong> ' . esc_html($metadata['publisher']) . '</p>';
            $meta_content .= '<p><strong>Publication Date:</strong> ' . esc_html($metadata['publication_date']) . '</p>';
            $meta_content .= '<p><strong>Volume:</strong> ' . esc_html($metadata['volume']) . '</p>';
            $meta_content .= '<p><strong>Issue:</strong> ' . esc_html($metadata['issue']) . '</p>';
            $meta_content .= '<p><strong>Pages:</strong> ' . esc_html($metadata['pages']) . '</p>';
            $meta_content .= '<p><strong>Abstract:</strong> ' . esc_html($metadata['abstract']) . '</p>';
            $meta_content .= '<p><strong>DOI Link:</strong> <a href="' . esc_url($metadata['doi_link']) . '" target="_blank">View Article</a></p>';
            $meta_content .= '<p><strong>Copyright:</strong> ' . esc_html($metadata['copyright']) . '</p>';
            $meta_content .= '</div>';
            $content .= $meta_content;
        } else {
            $content .= '<p>No metadata found.</p>';
        }
    }
    return $content;
}

add_action('wp_head', 'medra_display_metadata_in_head');

function medra_display_metadata_in_head() {
    if (is_singular('post')) {
        global $post;
        $metadata = get_post_meta($post->ID, '_medra_metadata', true);
        if ($metadata) {
            echo '<meta name="medra-title" content="' . esc_attr($metadata['title']) . '">' . "\n";
            echo '<meta name="medra-authors" content="' . esc_attr(implode(', ', $metadata['authors'])) . '">' . "\n";
            echo '<meta name="medra-journal" content="' . esc_attr($metadata['journal']) . '">' . "\n";
            echo '<meta name="medra-publisher" content="' . esc_attr($metadata['publisher']) . '">' . "\n";
            echo '<meta name="medra-publication-date" content="' . esc_attr($metadata['publication_date']) . '">' . "\n";
            echo '<meta name="medra-volume" content="' . esc_attr($metadata['volume']) . '">' . "\n";
            echo '<meta name="medra-issue" content="' . esc_attr($metadata['issue']) . '">' . "\n";
            echo '<meta name="medra-pages" content="' . esc_attr($metadata['pages']) . '">' . "\n";
            echo '<meta name="medra-abstract" content="' . esc_attr($metadata['abstract']) . '">' . "\n";
            echo '<meta name="medra-doi-link" content="' . esc_url($metadata['doi_link']) . '">' . "\n";
            echo '<meta name="medra-copyright" content="' . esc_attr($metadata['copyright']) . '">' . "\n";
        }
    }
}