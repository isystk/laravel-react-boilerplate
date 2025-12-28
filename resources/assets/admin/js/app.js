// すべての画像ファイルをビルド
import.meta.glob('../images/**/*');

// jQueryをグローバルに設定
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import 'bootstrap-datepicker';

// AdminLTE
import 'admin-lte';

import heic2any from 'heic2any';
window.heic2any = heic2any;
