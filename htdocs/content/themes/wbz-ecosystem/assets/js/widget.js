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
let title = $('.rssSummary p:first-of-type');
let content = $('.rssSummary p:last-of-type');

playlists.forEach(function (playlist) {
    reformat[i] = '';
    reformat[i] += '<ol>';
    playlist.forEach(function (track) {
        reformat[i] += '<li>' + track.replace(/^\d+\/ /, '') + '</li>';
    });
    reformat[i] += '</ol>';
    i++;
});

content.each(function (k, c) {
    $(c).hide();
    $(c).html(reformat[k]);
});

title.each(function (k, t) {
    $(t).children('b').text($(t).text().replace(' ', '').replace(/\=/g, '+'));
    $(t).click(function (e) {
        e.preventDefault();
        let p = $(t).siblings('p');
        let b = $(t).children('b');
        p.slideToggle(150, function () {
            toggleText(t, p, b);
        });
    })
});
