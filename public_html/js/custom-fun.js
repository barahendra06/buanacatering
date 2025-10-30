$(document).ready(function () {
    "use strict";




    /* -------------------------------------------------------------------------*
     * Sticky Main Nav
     * -------------------------------------------------------------------------*/
    var menu = $('.navbar');
    var origOffsetY = menu.offset().top;

    function scroll() {
        if ($(window).scrollTop() >= origOffsetY) {
            //$('.navbar').addClass('navbar-fixed-top');
            $('.content').addClass('menu-padding');
        } else {
            //$('.navbar').removeClass('navbar-fixed-top');
            $('.content').removeClass('menu-padding');
        }


    }

    document.onscroll = scroll;




    /* -------------------------------------------------------------------------*
     * ADDING SLIDE UP AND ANIMATION TO DROPDOWN
     * -------------------------------------------------------------------------*/
    enquire.register("screen and (min-width:767px)",
        {

            match: function () {
                $(".dropdown").hover(function () {
                    $('.dropdown-menu', this).stop().fadeIn("slow");
                }, function () {
                    $('.dropdown-menu', this).stop().fadeOut("slow");
                });
            },
        });

    /* -------------------------------------------------------------------------*
     * DROPDOWN LINK NUDGING
     * -------------------------------------------------------------------------*/
    $('.dropdown-menu a').hover(function () { //mouse in
        $(this).animate({
            paddingLeft: '30px'
        }, 400);
    }, function () { //mouse out
        $(this).animate({
            paddingLeft: 20
        }, 400);
    });

});
/**
 * Created by danielistyo on 1/21/2016.
 */
