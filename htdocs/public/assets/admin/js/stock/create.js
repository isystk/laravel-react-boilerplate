$(function () {

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
