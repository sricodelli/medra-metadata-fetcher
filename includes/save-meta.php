<?php
/*
Plugin Name: Medra Metadata Fetcher
Description: Fetches and displays metadata from Medra using DOI.
Version: 1.0
Author: Srikanth Gopi
*/
add_action('save_post', 'medra_save_meta_box_data');

function medra_save_meta_box_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $doi = get_field('doi', $post_id); // Ensure this is the correct field name
    if ($doi) {
        $metadata = medra_fetch_metadata($doi);
        if ($metadata) {
            update_post_meta($post_id, '_medra_metadata', $metadata);
        } else {
            error_log("Failed to fetch metadata for DOI: " . $doi, 3, WP_CONTENT_DIR . '/debug.log');
        }
    }
}

function medra_fetch_metadata($doi) {
    $url = 'https://api.medra.org/metadata/' . urlencode($doi);
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        error_log("WP Error: " . $response->get_error_message(), 3, WP_CONTENT_DIR . '/debug.log');
        return false;
    }
    $body = wp_remote_retrieve_body($response);

    $xml = simplexml_load_string($body);
    if ($xml === false) {
        error_log("Failed to parse XML for DOI: " . $doi, 3, WP_CONTENT_DIR . '/debug.log');
        return false;
    }

    $namespaces = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('x', $namespaces['']);

    $metadata = [];
    $metadata['title'] = (string)$xml->DOISerialArticleWork->ContentItem->Title->TitleText;
    $metadata['authors'] = [];
    foreach ($xml->DOISerialArticleWork->ContentItem->Contributor as $contributor) {
        $metadata['authors'][] = (string)$contributor->PersonName;
    }
    $metadata['journal'] = (string)$xml->DOISerialArticleWork->SerialPublication->SerialWork->Title->TitleText;
    $metadata['publisher'] = (string)$xml->DOISerialArticleWork->SerialPublication->SerialWork->Publisher->PublisherName;
    $metadata['publication_date'] = (string)$xml->DOISerialArticleWork->JournalIssue->JournalIssueDate->Date;
    $metadata['volume'] = (string)$xml->DOISerialArticleWork->JournalIssue->JournalVolumeNumber;
    $metadata['issue'] = (string)$xml->DOISerialArticleWork->JournalIssue->Date;
    $metadata['pages'] = (string)$xml->DOISerialArticleWork->ContentItem->TextItem->PageRun->FirstPageNumber . '-' .
                         (string)$xml->DOISerialArticleWork->ContentItem->TextItem->PageRun->LastPageNumber;
    $metadata['abstract'] = (string)$xml->DOISerialArticleWork->ContentItem->OtherText->Text;
    $metadata['doi_link'] = (string)$xml->DOISerialArticleWork->DOIWebsiteLink;
    $metadata['copyright'] = (string)$xml->DOISerialArticleWork->ContentItem->CopyrightStatement->CopyrightOwner->CorporateName;

    return $metadata;
}