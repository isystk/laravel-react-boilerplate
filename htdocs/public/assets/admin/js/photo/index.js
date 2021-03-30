$(function () {

  $('.js-delete').on('click', function (e) {
      e.preventDefault();
      if (!confirm('本当に削除していいですか？')) {
        return false;
      }

      var id = $(this).data('id');
      document.getElementById('delete_' + id).submit();
  });

});
