<?php

require_once UAE_POSTS_SKINS_DIR .'theme-builder/documents/uae.php';
require_once UAE_POSTS_SKINS_DIR .'theme-builder/dynamic-tags/uae-tags.php';

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Documents\UAE_Post_Skin;
use ElementorPro\Plugin;
use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;

Plugin::elementor()->documents->register_document_type( 'post_skin', UAE_Post_Skin::get_class_full_name() );
Source_Local::add_template_type( 'post_skin' );

function uae_post_get_document( $post_id ) {
    $document = null;

    try {
        $document = Plugin::elementor()->documents->get( $post_id );
    } catch ( \Exception $e ) {}

    if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
        $document = null;
    }

    return $document;
}

function uae_post_add_more_types( $settings ) {
    $post_id = get_the_ID();
    $document = uae_post_get_document( $post_id );

    if ( ! $document ) {
        return $settings;
    }

    $new_types      = [ 'post_skin'     => UAE_Post_Skin::get_properties() ];
    $add_settings   = [ 'theme_builder' => [ 'types' => $new_types ] ];

    if ( ! array_key_exists( 'post_skin', $settings['theme_builder']['types'] ) ) $settings = array_merge_recursive( $settings, $add_settings );

    return $settings;
}

add_filter( 'elementor_pro/editor/localize_settings', 'uae_post_add_more_types' );
