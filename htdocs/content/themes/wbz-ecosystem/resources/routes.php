<?php

/**
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */
Route::get('home', ['', function () {
    return View::make('index');
}]);
Route::get('search', ['search', function () {
    return View::make('search');
}]);
Route::get('page', ['sandbox', 'uses' => 'Example@index']);
Route::get('page', ['privacy-policy', function () {
    return View::make('page');
}]);
Route::get('archive', ['author', function () {
    return View::make('page');
}]);
Route::get('single', ['hello-world', function ($post) {
    return View::make('single', compact('post'));
}]);
Route::get('single', ['welcome', function ($post) {
    return View::make('single', compact('post'));
}]);
Route::get('page', ['sample-page', function ($post) {
    return View::make('page');
}]);
Route::get('404', 'Page@notFound');