$(function () {

    $.ajax({
        type: "GET",
        url: "/api/likes/",
        data: {
        },
        success: function (res) {
            if (res.result) {
                // 取得成功
                for (var i = 0, len = res.likes.length; i < len; i++) {
                    $('.js-like[data-id="' + res.likes[i] + '"]').removeClass('btn-secondary').addClass('btn-success');
                }
            }
        }
    });

    $('.js-like').on('click', function (e) {
        e.preventDefault();
        var self = $(this),
            id = self.data('id');

        var hasCookie = false;
        if (self.hasClass('btn-success')) {
            hasCookie = true;
        }

        if (hasCookie) {
            $.ajax({
                type: "POST",
                url: "/api/likes/destroy/" + id,
                data: {
                },
                success: function (res) {
                    if (res.result) {
                        // 取得成功
                        self.removeClass('btn-success').addClass('btn-secondary');
                    }
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: "/api/likes/store",
                data: {
                    id: id
                },
                success: function (res) {
                    if (res.result) {
                        // 取得成功
                        alert('お気に入りに追加しました。');
                        self.removeClass('btn-secondary').addClass('btn-success');
                    }
                }
            });
        }
    });


});
