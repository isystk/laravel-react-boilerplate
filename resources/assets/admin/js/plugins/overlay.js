(function ($) {
  $.overlay = function (options) {
    // デフォルト設定
    const defaults = {
      header: '',
      body: '',
      footer: '',
    };
    const settings = $.extend({}, defaults, options);

    const modalHtml = `
      <div class="modal fade" id="dynamicModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      ${settings.header}
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
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

    // 既存のモーダルがあれば削除して新しく追加
    $('#dynamicModal').remove();
    $('body').append(modalHtml);

    // モーダルを表示
    this.show = () => {
      $('#dynamicModal').modal('show');
    };

    // モーダルを閉じる
    this.hide = () => {
      $('#dynamicModal').modal('hide');
    };

    return this;
  };
})(jQuery);
