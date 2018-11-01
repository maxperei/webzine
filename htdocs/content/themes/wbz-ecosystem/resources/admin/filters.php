<?php

/**
 * Add <body> classes
 */
Filter::add('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (Utils\display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
}, 10, 1);

/**
 * Render comments.blade.php
 */
Filter::add('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );

    $theme_template = locate_template(["views/{$comments_template}", $comments_template]);

    if ($theme_template) {
        echo view('partials.comments');
        return get_stylesheet_directory().'/index.php';
    }

    return $comments_template;
}, 100, 1);

/**
 * Pass variables to frontend
 */
Filter::add('themosisGlobalObject', function($scriptVars = []) {
    $scriptVars = array_merge($scriptVars, [
        'users' => get_users(['fields' => ['display_name', 'user_nicename']])
    ]);
    return $scriptVars;
}, 10, 1);

/**
 * Add custom elements for all kind of menus
 */
Filter::add('wp_nav_menu_items', function ($items, $args) {
    if($args->theme_location == 'nav-primary' && !wp_is_mobile()){
        $logo_item = '
            <li class="menu-item">
              <a class="brand" href="'.esc_url(home_url('/')).'">'.
                get_bloginfo('name', 'display')
            .'</a>
            </li>
		';
        $items = $logo_item . $items;
    }

    return $items;
}, 10, 2);

/**
 * Add custom "more" link to the_excerpt()
 */
Filter::add('excerpt_more', function($more) {
   return " <span class='moretag'>→</a>";
});