import '../plugins';

$(function () {
  // ローディング
  $.loading();

  // 数値入力補助
  $('.js-input-number').inputNumber();

  // メニューの開閉状態を維持
  (() => {
    // ロード時に localStorage の値を確認し、状態を反映
    const sidebarState = localStorage.getItem('sidebar');
    if ('collapsed' === sidebarState) {
      $('body').addClass('sidebar-collapse');
    }

    $('body').removeClass('invisible');

    // メニュー開閉ボタンがクリックされたときに状態を保存
    $('[data-widget="pushmenu"]').on('click', function () {
      const isCollapsed = $('body').hasClass('sidebar-collapse');
      localStorage.setItem('sidebar', isCollapsed ? 'expanded' : 'collapsed');
    });
  })();

  // 日付入力補助
  (() => {
    // Date Picker に関する共通設定
    $.extend($.fn.datepicker.dates, {
      ja: {
        days: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'],
        daysShort: ['日', '月', '火', '水', '木', '金', '土', '日'],
        daysMin: ['日', '月', '火', '水', '木', '金', '土', '日'],
        months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月',],
        monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月',],
      },
    });
    $.extend($.fn.datepicker.defaults, {
      autoclose: true,
      format: 'yyyy/mm/dd',
      language: 'ja',
    });

    // 年月の入力フォーム
    $('.date-picker').datepicker();

    // 年月の入力フォーム
    $('.month-picker').datepicker({
      format: 'yyyy/mm',
      minViewMode: 'months',
    });
  })();
});
