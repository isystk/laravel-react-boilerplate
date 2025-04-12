(function($) {
    // テキストボックスの入力を数値のみに限定する
    $.fn.inputNumber = function(){
        const init = function(obj) {
            const maxLength = parseInt(obj.attr('maxlength'));
            obj.on('input', function(e) {
                let value = $(this).val();
                value = value
                    .replace(/[０-９]/g, function(s) {
                        return String.fromCharCode(s.charCodeAt(0) - 65248);
                    })
                    .replace(/[^0-9]/g, '');

                if (value.length > maxLength) {
                    value = value.slice(0, maxLength);
                }

                $(this).val(value);
            });
        }
        $(this).each(function() {
            init($(this));
        })
        return this;
    };
})(jQuery);
 
