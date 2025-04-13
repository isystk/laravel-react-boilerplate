// jQueryをグローバルに設定
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'bootstrap';
import 'bootstrap-datepicker'
import "admin-lte/dist/js/adminlte.min.js";

// Daterangepicker（moment.jsが依存）
import moment from 'moment';
window.moment = moment;

import heic2any from "heic2any";
window.heic2any = heic2any;
