
(function ($) {
    /*
     * autoFocus
     *
     * Description:
     * 入力完了時に次のフォームへフォーカスを当てる機能を提供します
     *
     * Sample:
     * 	$.autoFocus();
     *
     */
    // デフォルト値
    var options = {
        wrapselector: document,
        textselector: 'input,select,textarea'
    };

    $.autoFocus = function (opts) {

        // 引数に値が存在する場合、デフォルト値を上書きする
        $.extend(options, opts);

        var inputObj = $(options.wrapselector).find(options.textselector);

        $(options.wrapselector).delegate(options.textselector, 'change', function (event) {

            var self = $(this);

            // ラジオボタンとチェックボックスは対象外
            if (self.attr('type') === 'radio' || self.attr('type') === 'checkbox') {
                return;
            }

            inputObj = $(options.wrapselector).find(options.textselector);

            // 配列をループ処理
            $.each(inputObj, function (i, val) {
                if (i === inputObj.length - 1) {
                    return;
                }
                var target = $(inputObj[i]);
                var next = findNextObj(i);
                if (self.attr('name') === target.attr('name')) {
                    next.focus();
                }
            });

            // 次の入力フォームを見つける。
            function findNextObj(idx) {
                if (inputObj.length <= idx) {
                    return;
                }

                var target = $(inputObj[idx]);
                var targetName = target.attr('name');

                var next = $(inputObj[idx + 1]);
                var nextName = next.attr('name');

                // 同じnameのフォームは飛ばす。
                if (targetName === nextName) {
                    return findNextObj(++idx);
                } else {
                    return next;
                }
            }

        });

        // 初期表示時にエラーが有る場合は該当項目にフォーカスを当てる
        inputObj.each(function () {
            var target = $(this);
            if (target.hasClass('error')) {
                target.focus();
                return false;
            }
        });

    }

})(jQuery);

(function ($) {
    /*
     * textCounter
     *
     * Description:
     * 文字数をカウントします
     * 半角文字・スペース・改行、全角文字は1としてカウントします
     */
    $.textCounter = function (selectors) {
        var s = selectors;
        for (var i = 0, len = s.length; i < len; i++) {
            var obj = s[i],
                elem = $(obj.textSelector),
                label = $(obj.labelSelector);

            // カウント数
            label.html(obj.count - getCount(elem.val()));

            // デフォルト色
            label.data('defaultcolor', label.css('color'));

            elem.bind('keyup', function (val) {
                var count = obj.count - getCount(elem.val());
                label.html(count);

                if (count <= 0) {
                    label.css('color', '#FF0000');
                } else {
                    label.css('color', label.data('defaultcolor'));
                }
            });
        }
    };

    function getCount(val) {
        if (val) {
            var str = val.replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g, " ");
            return str.replace(/\n/g, "\r\n").length;
        }
        return 0;
    }

})(jQuery);

(function ($) {
    /*
     * imageUploader
     *
     * Copyright (c) 2021 iseyoshitaka
     *
     * Description:
     * 画像ファイルをアップロード用にリサイズする（HEIC形式の場合はJPEGに変換）
     */
    $.fn.imageUploader = function (options) {

        var params = $.extend({}, $.fn.imageUploader.defaults, options);

        var nowLoading = false; // 処理中フラグ
        var dropAreaSelector = params.dropAreaSelector;
        var maxFileSize = params.maxFileSize;
        var thumbnail_width = params.thumbnail_width;
        var thumbnail_height = params.thumbnail_height;
        var successCallback = params.successCallback;
        var errorCallback = params.errorCallback;

        var init = function (target) {

            // ファイルドロップ時のイベントリスナー
            var dropArea = $(dropAreaSelector);
            dropArea.on('dragenter', function (event) {
                event.preventDefault();
                event.stopPropagation();
            });
            dropArea.on('dragover', function (event) {
                event.preventDefault();
                event.stopPropagation();
            });
            dropArea.on('drop', function (event) {
                event.preventDefault();
                event.stopPropagation();
                var files = event.originalEvent.dataTransfer.files;
                if (files.length === 0) {
                    return;
                }
                exec(event.originalEvent.dataTransfer);
            });

            // ファイル選択時のイベントリスナー
            $(target).change(function () {
                if (this.files.length === 0) {
                    return;
                }
                exec(this);
            });
        }

        var exec = function (obj) {

            // ファイルAPIに対応していない場合は、エラーメッセージを表示する
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                errorCallback(['お使いのブラウザはファイルAPIに対応していません。']);
                return;
            }

            if (nowLoading) {
                errorCallback(['処理中です。']);
                return;
            }

            $.each(obj.files, function (i, file) {

                nowLoading = true;

                function getExt(filename) {
                    var pos = filename.lastIndexOf('.');
                    if (pos === -1) return '';
                    return filename.slice(pos + 1);
                }
                var ext = getExt(file.name).toLowerCase();

                if (ext === 'heic') {
                    // HEIC対応 iphone11 以降で撮影された画像にも対応する
                    // console.log('HEIC形式の画像なのでJPEGに変換します。')

                    heic2any({
                        blob: file,
                        toType: "image/jpeg",
                        quality: 1
                    }).then(function (resultBlob) {
                        var errors = validate(resultBlob);
                        if (0 < errors.length) {
                            errorCallback(errors);
                            nowLoading = false;
                            return;
                        }
                        resize(resultBlob, function (res) {
                            res.fileName = file.name;
                            successCallback(res);
                            nowLoading = false;
                        }, function (errors) {
                            errorCallback(errors);
                            nowLoading = false;
                            return;
                        });
                    });
                } else {

                    var errors = validate(file);
                    if (0 < errors.length) {
                        errorCallback(errors);
                        nowLoading = false;
                        return;
                    }
                    resize(file, function (res) {
                        successCallback(res);
                        nowLoading = false;
                    }, function (errors) {
                        errorCallback(errors);
                        nowLoading = false;
                        return;
                    });
                }

            });

        }

        // 入力チェック
        var validate = function (blob) {
            var errors = [];
            // ファイルサイズチェック
            if (maxFileSize < blob.size) {
                errors.push('画像ファイルのファイルサイズが最大値(' + Math.floor(maxFileSize / 1000000) + 'MB)を超えています。');
            }
            return errors;
        }

        // そのままの
        var resize = function (blob, callback, errorCallback) {
            var image = new Image();
            var fr = new FileReader();
            fr.onload = function (evt) {
                // リサイズする
                image.onload = function () {
                    var width, height;
                    if (image.width > image.height) {
                        // 横長の画像は横のサイズを指定値にあわせる
                        var ratio = image.height / image.width;
                        width = thumbnail_width;
                        height = thumbnail_width * ratio;
                    } else {
                        // 縦長の画像は縦のサイズを指定値にあわせる
                        var ratio = image.width / image.height;
                        width = thumbnail_height * ratio;
                        height = thumbnail_height;
                    }
                    // サムネ描画用canvasのサイズを上で算出した値に変更
                    var canvas = $('<canvas id="canvas" width="0" height="0" ></canvas>')
                        .attr('width', width)
                        .attr('height', height);
                    var ctx = canvas[0].getContext('2d');
                    // canvasに既に描画されている画像をクリア
                    ctx.clearRect(0, 0, width, height);
                    // canvasにサムネイルを描画
                    ctx.drawImage(image, 0, 0, image.width, image.height, 0, 0, width, height);

                    // canvasからbase64画像データを取得
                    var base64 = canvas.get(0).toDataURL('image/jpeg');
                    // base64からBlobデータを作成
                    var barr, bin, i, len;
                    bin = atob(base64.split('base64,')[1]);
                    len = bin.length;
                    barr = new Uint8Array(len);
                    i = 0;
                    while (i < len) {
                        barr[i] = bin.charCodeAt(i);
                        i++;
                    }
                    var resizeBlob = new Blob([barr], { type: 'image/jpeg' });
                    callback({
                        fileName: blob.name,
                        ofileData: evt.target.result,
                        fileData: base64,
                        ofileSize: blob.size,
                        fileSize: resizeBlob.size,
                        fileType: resizeBlob.type
                    })
                }
                image.onerror = function () {
                    errorCallback(['選択されたファイルをロードできません。']);
                }
                image.src = evt.target.result;
            }
            fr.readAsDataURL(blob);
        }

        $(this).each(function () {
            init(this);
        });

        return this;
    }

    $.fn.imageUploader.defaults = {
        dropAreaSelector: '',
        maxFileSize: 10485760, // 10BM
        thumbnail_width: 500, // 画像リサイズ後の横の長さの最大値
        thumbnail_height: 500, // 画像リサイズ後の縦の長さの最大値
        successCallback: function (res) { console.log(res); },
        errorCallback: function (res) { console.log(res); }
    }

})(jQuery);
