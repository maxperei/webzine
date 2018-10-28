import $ from 'jquery';

let playlists = [];
let texts = [];

/**
 * Handle various texts
 **/
(function () {
    $('.rssBase > p').each(function () {
        if ($(this).text() === '') {
            $(this).remove();
        } else if (!$(this).text().includes('Playlist')) {
            playlists.push($(this).html().split('<br>'));
        }
    });
    $('.rssContent').each(function () {
        texts.push($(this).html().split('['));
    });
})();

function toggleText(t, p, b) {
    let plus = $(t).text().replace(/\-/g, '+');
    let minus = $(t).text().replace(/\+/g, '-');
    return p.is(':visible') ? b.text(minus) : b.text(plus);
}

let section = $('.rssBase > p:first-of-type').wrapInner('<b></b>');
let list = $('.rssBase > p:last-of-type');
let contents = $('.rssContent');

let format = [];
let i = 0;

texts.forEach(function (text) {
    format[i] = '';
    text.forEach(function (t) {
        t = t.split(']');
        if (t[0] !== '…') {
            format[i] += '<p class="author"><b>'+t[0]+'</b>';
            format[i] += '<span>'+t[1]+'</span>';
            format[i] += '</p>';
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
    format[i] += '<ol class="tracklist">';
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
        format[i] += '<li class="track track'+j+'">' + track;
            format[i] += '<ul class="track__metadata">';
            format[i] +=
                '<li class="track__artist">'+artist+'</li>'+
                '<li class="track__album">'+album+'</li>'+
                '<li class="track__year">'+year+'</li>'+
                '<li class="track__label">'+label+'</li>';
            format[i] += '</ul>';
        format[i] += '</li>';

        j++;
    });
    format[i] += '</ol>';
    i++;
});

$(list).hide();
$('.author > span').hide();

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

$('.author').each(function (k, a) {
   $(a).click(function (e) {
       e.preventDefault();
       let c = $(a).children('span');
       c.slideToggle(150);
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
