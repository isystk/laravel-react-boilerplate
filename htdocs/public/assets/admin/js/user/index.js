$(function () {

    $('.js-download').click(function (e) {
        e.preventDefault();
        var self = $(this),
            href = self.attr('href'),
            form = $('#pagingForm');
        location.href = [href, form.serialize()].join('?');
    });

});
