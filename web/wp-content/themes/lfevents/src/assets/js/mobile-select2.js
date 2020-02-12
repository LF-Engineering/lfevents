$(document).ready(function() {
  if (window.innerWidth < 768) {
    function fixMobileSelect2() {
      $('select[data-combobox=1]').select2({
        minimumResultsForSearch: Infinity
      });

      $('select[data-combobox=1]').each(function(index, element) {
        element.style.display = 'none';
      });

      $('select[data-combobox=1]').on('select2:open', function() {
        $('.select2-search__field').prop('disabled', true);
      });
    }

    fixMobileSelect2();

    $(document).on('sf:ajaxfinish', '.searchandfilter', function() {
      fixMobileSelect2();
    });
  }
});
