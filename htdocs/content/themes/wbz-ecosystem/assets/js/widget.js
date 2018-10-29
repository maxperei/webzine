import $ from 'jquery';

let playlists = [];
let texts = [];

/**
 * Handle various texts
 **/
(function () {
    $('.rss-base > p').each(function () {
        if ($(this).text() === '') {
            $(this).remove();
        } else if (!$(this).text().includes('Playlist')) {
            playlists.push($(this).html().split('<br>'));
        }
    });
    $('.rss-content').each(function () {
        texts.push($(this).html().split('['));
    });
})();

function toggleText(t, p, b) {
    let plus = $(t).text().replace(/\-/g, '+');
    let minus = $(t).text().replace(/\+/g, '-');
    return p.is(':visible') ? b.text(minus) : b.text(plus);
}

let section = $('.rss-base > p:first-of-type').wrapInner('<b></b>');
let list = $('.rss-base > p:last-of-type');
$(list).hide();

let contents = $('.rss-content');

let format = [];
let i = 0;

texts.forEach(function (text) {
    format[i] = '';
    text.forEach(function (t) {
        t = t.split(']');
        if (t[0] !== '…') {
            format[i] += '<h4 class="rss-author">'+t[0]+'</h4>';
            format[i] += '<p class="rss-text">'+t[1].replace(/\./g, ".<br/>")+'</p>';
        }
    });
    i++;
});

contents.each(function (k, c) {
    $(c).html(format[k]);
});

format = [];
i = 0;

playlists.forEach(function (playlist) {
    var j = 1;
    format[i] = '';
    format[i] += '<ol class="rss-tracklist">';
    playlist.forEach(function (track) {
        var meta, artist, label, album, year;
        meta = track.match(/\(([^)]+)\)/g).toString().replace(/\(|\)/g, '');
        track = track.split('(')[0].replace(/^\d+\/ /, '');
        artist = track.split(' - ')[0];
        track = track.split(' - ')[1];
        album = meta.split('/')[0];
        label = meta.split('/')[1];
        label = meta.match(/<\s*a[^>]*>(.*?)<\/a>/g) || label;
        year = meta.split('/').pop();

        // TODO Refacto
        format[i] += '<li class="rss-track track'+j+'">' + track;
            format[i] += '<ul class="rss-track__metadata">';
            format[i] +=
                '<li class="rss-track__artist">'+artist+'</li>'+
                '<li class="rss-track__album">'+album+'</li>'+
                '<li class="rss-track__year">'+year+'</li>'+
                '<li class="rss-track__label">'+label+'</li>';
            format[i] += '</ul>';
        format[i] += '</li>';

        j++;
    });
    format[i] += '</ol>';
    i++;
});

list.each(function (k, c) {
    $(c).html(format[k]);
});

section.each(function (k, l) {
    $(l).children('b').text($(l).text().replace(' ', '').replace(/\=/g, '+'));
    $(l).click(function (e) {
        e.preventDefault();
        let p = $(l).siblings('p');
        let b = $(l).children('b');
        p.slideToggle(150, function () {
            toggleText(l, p, b);
        });
        return false;
    })
});

$('.rss-author').click(function () {
    $(this).toggleClass('rss-author--open').next().slideToggle(150);
    $('.rss-author').not($(this)).removeClass('rss-author--open');
    $('.rss-text').not($(this).next()).slideUp(150);
});

$('.rss-track').each(function (k, t) {
    $(t).hover(function () {
        $(t).children('ul').stop().slideDown(150);
    }, function () {
        $(t).children('ul').stop().slideUp(150);
    });
});
