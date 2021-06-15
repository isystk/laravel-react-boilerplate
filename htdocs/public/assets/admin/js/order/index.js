$(function () {

    // ダウンロード
    $('.js-download').click(function(e) {
      e.preventDefault();
      var form = $('#pagingForm');
      form.attr('action', $(this).attr('href'));
      form.submit();
    });

});
