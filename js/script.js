(function($){

  $(document).ready(function() {

    // Scroll to comments

    $('.js_ask-expert-block_go-to-comments').click(function (e) {

      $('html, body').animate({
          scrollTop: $("#commentform").offset().top
      }, 1000);

      e.preventDefault();
    });

  });

})(jQuery);