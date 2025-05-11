$(function () {
  // ダウンロード
  $('.js-download').click(function (e) {
    e.preventDefault();
    const form = $('#pagingForm');
    const url = $(this).attr('href');
    const serializedData = form.serialize();
    window.location.href = url + '&' + serializedData;
  });
});
