(function ($) {
  $.overlay = function (options) {
    // デフォルト設定
    const defaults = {
      id: 'dynamicModal',
      header: '',
      body: '',
      footer: '',
    };
    const settings = $.extend({}, defaults, options);

    const modalHtml = `
      <div class="modal" id="${settings.id}" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <div class="m-auto">${settings.header}</div>
                      <div class="absolute right-0 top-0 mt-2 mr-2">
                          <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                  </div>
                  <div class="modal-body">
                      ${settings.body}
                  </div>
                  <div class="modal-footer">
                      ${settings.footer}
                  </div>
              </div>
          </div>
      </div>`;

    // 既存のモーダルがなければ追加
    if (0 === $(`#${settings.id}`).length) {
      $('body').append(modalHtml);
    }

    // 既存のインスタンスを取得、なければ新規作成
    const getModalInstance = () => {
      const element = document.getElementById(settings.id);
      return bootstrap.Modal.getInstance(element) || new bootstrap.Modal(element);
    };

    // モーダルを表示
    this.show = () => {
      getModalInstance().show();
    };

    // モーダルを非表示
    this.hide = () => {
      const instance = bootstrap.Modal.getInstance(document.getElementById(settings.id));
      if (instance) {
        instance.hide();
      }
    };

    return this;
  };
})(jQuery);
