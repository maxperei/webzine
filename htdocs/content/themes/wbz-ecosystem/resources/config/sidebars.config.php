<?php

return [

    /**
     * Edit this file to add widget sidebars to your theme.
     * Place WordPress default settings for sidebars.
     * Add as many as you want, watch-out your commas!
     */
    [
        'name' => __('Default sidebar', THEME_TEXTDOMAIN),
        'id' => 'default-sidebar',
        'description' => __('Area of default sidebar', THEME_TEXTDOMAIN),
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ],
    [
        'name' => __('Footer sidebar', THEME_TEXTDOMAIN),
        'id' => 'footer-sidebar',
        'description' => __('Area of footer sidebar', THEME_TEXTDOMAIN),
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ]

];
