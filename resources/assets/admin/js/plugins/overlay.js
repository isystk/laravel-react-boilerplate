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
      <div class="modal fade" id="${settings.id}" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header border-0">
                      <div class="m-auto">${settings.header}</div>
                      <div class="absolute right-0 top-0 mt-2 mr-2">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                  </div>
                  <div class="modal-body">
                      ${settings.body}
                  </div>
                  <div class="modal-footer border-0">
                      ${settings.footer}
                  </div>
              </div>
          </div>
      </div>`;

    // 既存のモーダルがなければ追加
    if (0 === $(`#${settings.id}`).length) {
      $('body').append(modalHtml);
    }

    // モーダルを表示
    this.show = () => {
      $(`#${settings.id}`).modal('show');
    };

    // モーダルを閉じる
    this.hide = () => {
      $(`#${settings.id}`).modal('hide');
    };

    return this;
  };
})(jQuery);
