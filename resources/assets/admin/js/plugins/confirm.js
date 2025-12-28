import './overlay'

(function ($) {
  $.fn.confirm = function (options) {
    const settings = $.extend({
      title: 'タイトル',
      body: 'メッセージを入力してください。',
      confirmText: '保存する',
      cancelText: '閉じる',
      confirmClass: 'btn-primary',
      cancelClass: 'btn-secondary',
      onConfirm: () => {},
    }, options);

    return this.each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();
        const self = $(this);

        const [header, body, footer] = [
          `<h5 class="modal-title">${settings.title}</h5>`,
          `${settings.body}`,
          `
          <button type="button" class="btn ${settings.confirmClass}" id="modalConfirmBtn">${settings.confirmText}</button>
          <button type="button" class="btn ${settings.cancelClass}" data-dismiss="modal">${settings.cancelText}</button>
          `,
        ]
        const modal = $.overlay({header, body, footer})
        modal.show();

        // 実行ボタンのイベント
        $('#modalConfirmBtn', modal).on('click', function () {
          settings.onConfirm(self);
          modal.hide();
        });
      });
    });
  };
})(jQuery);
