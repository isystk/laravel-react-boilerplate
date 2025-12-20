$(function () {
  // 削除確認用のダイアログを表示
  $('.js-deleteBtn').click(function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    if (confirm('本当に削除していいですか？')) {
      $('#delete_' + id).submit();
    }
  });
});
