$(function () {
  // 画像ファイルアップロード
  $('.js-uploadImage').each(function () {
    const self = $(this),
      parent = self.closest('div'),
      result = parent.find('.result');
    self.imageUploader({
      dropAreaSelector: '#drop-zone',
      successCallback: function (res) {
        result.empty().append(`
            <img src="${res.fileData}" width="200px" />
            <input type="hidden" name="image_base_64" value="${res.fileData}" />
            <input type="hidden" name="image_file_name" value="${res.fileName}" />
            <button type="button" class="btn btn-danger btn-sm js-remove-image">削除する</button>
          `);
        $('.error-message').empty();
      },
      errorCallback: function (res) {
        $('.error-message').text(res[0]);
      },
    });
  });
  // 画像削除ボタン
  $(document).on('click', '.js-remove-image', function () {
    $(this).closest('.result').empty();
  });
});
