import $ from 'jquery';

let playlists = [];

/**
 * Handle various texts
 **/
(function () {
    $('.rssSummary p').each(function () {
        if ($(this).text() === '') {
            $(this).remove();
        } else if (!$(this).text().includes('Playlist')) {
            playlists.push($(this).html().split('<br>'));
        }
    });
})();

function toggleText(t, p, b) {
    let plus = $(t).text().replace(/\-/g, '+');
    let minus = $(t).text().replace(/\+/g, '-');
    return p.is(':visible') ? b.text(minus) : b.text(plus);
}

let reformat = [];
let i = 0;
let list = $('.rssSummary p:first-of-type');
let content = $('.rssSummary p:last-of-type');

playlists.forEach(function (playlist) {
    var j = 1;
    reformat[i] = '';
    reformat[i] += '<ol class="tracklist">';
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
        reformat[i] += '<li class="track track'+j+'">' + track;
            reformat[i] += '<ul class="track__metadata">';
            reformat[i] +=
                '<li class="track__artist">'+artist+'</li>'+
                '<li class="track__album">'+album+'</li>'+
                '<li class="track__year">'+year+'</li>'+
                '<li class="track__label">'+label+'</li>';
            reformat[i] += '</ul>';
        reformat[i] += '</li>';

        j++;
    });
    reformat[i] += '</ol>';
    i++;
});

content.each(function (k, c) {
    $(c).hide();
    $(c).html(reformat[k]);
});

list.each(function (k, l) {
    $(l).children('b').text($(l).text().replace(' ', '').replace(/\=/g, '+'));
    $(l).click(function (e) {
        e.preventDefault();
        let p = $(l).siblings('p');
        let b = $(l).children('b');
        p.slideToggle(150, function () {
            toggleText(l, p, b);
        });
    })
});

let track = $('.track');

track.each(function (k, t) {
    $(t).hover(function () {
        $(t).children('ul').stop().slideDown(150);
    }, function () {
        $(t).children('ul').stop().slideUp(150);
    });
});
