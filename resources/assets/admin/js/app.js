// すべての画像ファイルをビルド
import.meta.glob('../images/**/*');

// jQueryをグローバルに設定
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'bootstrap';
import 'bootstrap-datepicker';
import 'admin-lte/dist/js/adminlte.min.js';

import heic2any from 'heic2any';
window.heic2any = heic2any;
