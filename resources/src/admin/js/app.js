import jQuery from "admin-lte/plugins/jquery/jquery.min.js";
window.$ = jQuery;
window.jQuery = jQuery;

import "admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js";
import "admin-lte/dist/js/adminlte.min.js";

import heic2any from "heic2any";
window.heic2any = heic2any;

require('./plugins/loading');
require('./plugins/inputNumber');
require('./plugins/imageUploader');

$(function() {

    // ローディング
    $.loading();

    // 一覧のソート処理
    $('.sortable_th').on('click', function () {
        window.location.href = $(this).attr('url')
    });

    // 数値入力補助
    $('.js-input-number').inputNumber();

    // 日付入力補助
    (function () {
        // Date Picker に関する共通設定
        $.extend($.fn.datepicker.dates , {
            ja: {
                days: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'],
                daysShort: ['日', '月', '火', '水', '木', '金', '土', '日'],
                daysMin: ['日', '月', '火', '水', '木', '金', '土', '日'],
                months: ['1月', '2月', '3月', '4月', '5月', '6月','7月', '8月', '9月', '10月', '11月', '12月'],
                monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月','7月', '8月', '9月', '10月', '11月', '12月']
            }
        });
        $.extend($.fn.datepicker.defaults , {
            autoclose: true,
            format : 'yyyy/mm/dd',
            language : 'ja',
        });
        // 年月の入力フォーム
        $('.date-picker').datepicker();
        // 年月の入力フォーム
        $('.month-picker').datepicker({
            format : 'yyyy/mm',
            minViewMode: 'months',
        });
    })();

})
