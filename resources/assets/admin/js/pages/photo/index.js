$(function () {
  // 削除確認用のダイアログを表示
  $('.js-deleteBtn').confirm({
    title: '確認',
    body: '削除します。よろしいですか？',
    confirmText: '削除する',
    confirmClass: 'btn-danger',
    onConfirm: function (target) {
      const id = $(target).data('id');
      $('#delete_' + id.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')).submit();
    },
  });
});
