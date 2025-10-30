(function ($) {
  // accordion
  $('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
    $(this).siblings('.panel-heading-tnc').addClass('active');
  });

  $('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
    $(this).siblings('.panel-heading-tnc').removeClass('active');
  });
})(window.jQuery);
