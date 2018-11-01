<?php

Asset::add('admin', 'js/admin.min.js', ['jquery'], '1.0', true)->to('admin');

View::composer(['sandbox'], function ($view) {
    Asset::add('screen', 'css/screen.min.css', false, '1.0', 'all');
});

Asset::add('angular', '../assets/components/angular/angular.min.js', ['jquery'], '1.7.4', false);
Asset::add('theme', 'js/theme.min.js', ['jquery', 'angular'], '1.0', true);
Asset::add('widget', 'js/widget.min.js', ['jquery'], '1.0', 'all');
//Asset::add('font-awesome', '//use.fontawesome.com/releases/v5.4.1/css/all.css', false, '5.4.1', 'all');
Asset::add('main', 'css/main.min.css', false, '1.0', 'all');
