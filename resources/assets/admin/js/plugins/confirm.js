import './overlay'

(function ($) {
  $.fn.confirm = function (options) {
    const settings = $.extend({
      id: 'confirmModal',
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
          `<h5 class="modal-title fw-bold">${settings.title}</h5>`,
          `${settings.body}`,
          `
          <button type="button" class="btn ${settings.confirmClass}">${settings.confirmText}</button>
          <button type="button" class="btn ${settings.cancelClass}">${settings.cancelText}</button>
          `,
        ]
        const modal = $.overlay({id: settings.id, header, body, footer})
        const $modalElement = $(`#${settings.id}`);

        // キャンセルボタンにフォーカスを当てる
        $modalElement.on('shown.bs.modal', function () {
          setTimeout(() => {
            $(`.${settings.cancelClass}`, this).focus()
          }, 100)
        });

        // 実行ボタンのイベント
        $(`.${settings.confirmClass}`, $modalElement).on('click', function () {
          settings.onConfirm(self);
          modal.hide();
        });

        // キャンセルボタンのイベント
        $(`.${settings.cancelClass}`, $modalElement).on('click', function () {
          modal.hide();
        });

        // モーダルを表示
        modal.show();
      });
    });
  };
})(jQuery);
