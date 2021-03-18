$(function () {

    // 入力完了時に次のフォームへフォーカスを当てる
    $.autoFocus();

    // テキストエリア文字数カウント
    (function () {
        $('.js-textCounter').each(function () {
            var self = $(this),
                maxCount = self.text(),
                textarea = self.closest('.textarea-wrap').find('textarea');
            $.textCounter([
                { textSelector: textarea, labelSelector: self, count: maxCount }
            ]);
        });
    })();

    // フォーカス時の処理
    (function () {
        var wrap = $('.form-section');
        var form = wrap.find('input,select,textarea');
        form.each(function () {
            var self = $(this);
            // フォーカスイン
            self.focus(function () {
                self.closest('div').addClass('active');
            });
            // フォーカスアウト
            self.blur(function () {
                wrap.find('div').removeClass('active');
            });
        });
    })();


    // 画像ファイルアップロード
    $('#js-uploadImage').imageUploader({
        dropAreaSelector: '#drop-zone',
        successCallback: function (res) {

            $('#result').empty();
            $('#result').append('<img src="' + res.fileData + '" width="200px" />');
            $('#result').append('<input type="hidden" name="imageBase64" value="' + res.fileData + '" />');
            $('#result').append('<input type="hidden" name="fileName" value="' + res.fileName + '" />');

            $('.error-message').empty();
        },
        errorCallback: function (res) {
            $('.error-message').text(res[0]);
        }
    });

});
