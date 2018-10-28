import $ from 'jquery';

$(document).ready(function () {
    let itemsOps = $('#rss-items-2');
    let offset = $('.offset');

    itemsOps.change(function () {
        $(this).val() == 1 ? offset.show() : offset.hide();
    });
});

$(document).ajaxComplete(function () {
    let itemsOps = $('#rss-items-2');
    let offset = $('.offset');

    itemsOps.change(function () {
        $(this).val() == 1 ? offset.show() : offset.hide();
    });
});
