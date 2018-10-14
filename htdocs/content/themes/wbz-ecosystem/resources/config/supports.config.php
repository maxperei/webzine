<?php

return [

    /**
     * Edit this file to add features to your theme.
     * Un-comment each feature to add it.
     *
     * http://codex.wordpress.org/Function_Reference/add_theme_support
     */

    /* --------------------------------------------------------------- */
     // Enable features from Soil when plugin is activated
     // @link https://roots.io/plugins/soil/
    /* --------------------------------------------------------------- */
    'soil-clean-up',
    'soil-disable-asset-versioning',
    'soil-disable-trackbacks',
    'soil-google-analytics' => 'UA-XXXXX-Y',
    'soil-jquery-cdn',
    'soil-js-to-footer',
    'soil-nav-walker',
    'soil-nice-search',
    'soil-relative-urls',

    /* --------------------------------------------------------------- */
    // Enable post thumbnails
    /* --------------------------------------------------------------- */
    'post-thumbnails' => ['post'],

    /* --------------------------------------------------------------- */
    // Enable post formats (aside, gallery, link, image, ...)
    /* --------------------------------------------------------------- */
    'post-formats' => [],

    /* --------------------------------------------------------------- */
    // Enable title tag
    /* --------------------------------------------------------------- */
    'title-tag',

    /* --------------------------------------------------------------- */
    // Enable HTML5 features
    /* --------------------------------------------------------------- */
    'html5' => ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption'],

    /* --------------------------------------------------------------- */
    // Enable feed links in head
    /* --------------------------------------------------------------- */
    //'automatic-feed-links',

    /* --------------------------------------------------------------- */
    // Enable custom background (since WP 3.4)
    /* --------------------------------------------------------------- */
    //'custom-background'	=> [
    //	'default-color'          => '',
    //	'default-image'          => '',
    //	'wp-head-callback'       => '_custom_background_cb',
    //	'admin-head-callback'    => '',
    //	'admin-preview-callback' => ''
    //],

    /* --------------------------------------------------------------- */
    // Enable custom header (not compatible for versions < WP 3.4)
    /* --------------------------------------------------------------- */
    //'custom-header'	=>	[
    //	'default-image'          => '',
    //	'random-default'         => false,
    //	'width'                  => 0,
    //	'height'                 => 0,
    //	'flex-height'            => false,
    //	'flex-width'             => false,
    //	'default-text-color'     => '',
    //	'header-text'            => true,
    //	'uploads'                => true,
    //	'wp-head-callback'       => '',
    //	'admin-head-callback'    => '',
    //	'admin-preview-callback' => '',
    //]

];
