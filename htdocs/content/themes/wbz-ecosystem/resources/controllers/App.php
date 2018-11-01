<?php

namespace Theme\Controllers;

use Themosis\Route\BaseController;

class App extends BaseController
{
    public function siteName()
    {
        return get_bloginfo('name');
    }
    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', THEME_TEXTDOMAIN);
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', THEME_TEXTDOMAIN), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', THEME_TEXTDOMAIN);
        }
        return get_the_title();
    }
}