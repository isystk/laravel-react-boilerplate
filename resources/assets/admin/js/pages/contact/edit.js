$(function () {
  // 画像ファイルアップロード
  $('.js-uploadImage').each(function (i) {
    const self = $(this),
      parent = self.closest('div'),
      result = parent.find('.result');
    self.imageUploader({
      dropAreaSelector: '#drop-zone',
      successCallback: function (res) {
        result
          .empty()
          .append('<img src="' + res.fileData + '" width="200px" />')
          .append(`<input type="hidden" name="image_base_64" value="` + res.fileData + '" />')
          .append('<input type="hidden" name="image_file_name" value="' + res.fileName + '" />');

        $('.error-message').empty();
      },
      errorCallback: function (res) {
        $('.error-message').text(res[0]);
      },
    });
  });
  // 画像削除ボタン
  $('.js-remove-image').on('click', function () {
    $(this).closest('.result').empty();
  });
});
