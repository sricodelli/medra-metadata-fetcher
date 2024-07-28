<?php
/*
Plugin Name: Medra Metadata Fetcher
Description: Fetches and displays metadata from Medra using DOI.
Version: 1.0
Author: Srikanth Gopi
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include required files
include_once(plugin_dir_path(__FILE__) . 'includes/meta-box.php');
include_once(plugin_dir_path(__FILE__) . 'includes/save-meta.php');
include_once(plugin_dir_path(__FILE__) . 'includes/display-meta.php');
?>
