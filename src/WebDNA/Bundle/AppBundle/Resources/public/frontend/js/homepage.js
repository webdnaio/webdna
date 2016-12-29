$(document).ready(function() {

    if(headerCookie.isset()===false) {
        headerCookie.show();
    }
  
    $('.scrollToTop').click(function () {
        $('body,html').animate({
                scrollTop: 0
        }, 800);
    });
  
    $(".navbar-nav a").click(function() {

        var rel = $(this).attr("rel"); 

        $("html, body").animate({
          scrollTop: $("#"+rel).offset().top-100 
        }, 1500);
    });

});