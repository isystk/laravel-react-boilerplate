$(function () {
  // アカウント停止確認用のダイアログを表示
  $('.js-suspendBtn').confirm({
    title: '確認',
    body: 'アカウントを停止します。よろしいですか？',
    confirmText: '停止する',
    confirmClass: 'btn-danger',
    onConfirm: function (target) {
      const id = $(target).data('id');
      $('#suspend_' + id).submit();
    },
  });

  // アカウント有効化確認用のダイアログを表示
  $('.js-activateBtn').confirm({
    title: '確認',
    body: 'アカウントを有効にします。よろしいですか？',
    confirmText: '有効にする',
    confirmClass: 'btn-success',
    onConfirm: function (target) {
      const id = $(target).data('id');
      $('#activate_' + id).submit();
    },
  });
});
