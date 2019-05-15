



window.onload = function () {
//    j(function($){
//        j('#loading')..animate({
//            opacity: 0.25,
//            left: "+=50",
//            height: "toggle"
//        }, 5000, function() {
//            // Animation complete.
//        });
//    })(j);
    document.querySelector('body').classList.remove('init');
    document.querySelector('#loading').style.display = "none";

};

jQuery(document).ready(function(){

    //adding 'image' class to paragraphs with image in posts
    jQuery('p').has("img[class*='wp-image'] ").addClass('image');


    var timer, currentScroll;
    currentScroll = jQuery(window).scrollTop();
    //Check to see if the window is top if not then display button
    jQuery(window).scroll(function(){


        if(jQuery(this).scrollTop() > currentScroll){

            jQuery('.scrollToTop').addClass('rotate');
            clearTimeout(timer);
            timer = setTimeout(function(){
                jQuery('.scrollToTop').removeClass('rotate')
            }, 1000);
        }

        //jQuery('.scrollToTop').css('rotateX', '180deg');


        //jQuery('.scrollToTop').css('width', 'rotate(180deg)').delay( 2000 ).css('transform', 'rotate(360deg)');
        if (jQuery(this).scrollTop() > 2000) {
            jQuery('.scrollToTop').fadeIn();
        } else {
            jQuery('.scrollToTop').fadeOut();
        }

        currentScroll = jQuery(this).scrollTop();
    });
    //Click event to scroll to top
    jQuery('.scrollToTop').click(function(){
        jQuery('html, body').animate({scrollTop : 0},800);
        return false;
    });

    //show window size for testing
//    var $div = jQuery('<div />').appendTo('body');
//    $div.html(jQuery(window).width() + ' x ' + jQuery(window).height());
//    $div.css({
//        backgroundColor: "red",
//        position: 'fixed',
//        top: '10px',
//        left: '10px',
//        padding: '5px',
//        'z-index': '222222'
//
//    });
//    jQuery(window).resize(function () {
//        $div.html(jQuery(window).width() + ' x ' + jQuery(window).height());
//    });

});



//
//var j = jQuery.noConflict();
//
//
//j(function($) {
//    // your page initialization code here
//    // the DOM will be available here
//    $('#toggle').toggle(
//        function() {
//            $('#popout').animate({ left: 0 }, 'slow', function() {
//                $('#toggle').html('<i class="fas fa-bars"></i>');
//            });
//        },
//        function() {
//            $('#popout').animate({ left: -250 }, 'slow', function() {
//                $('#toggle').html('<i class="fas fa-bars"></i>');
//            });
//        }
//    );
//})(j);


(function($) {
    $('#toggle').toggle(
        function() {
            $('#popout').animate({ left: 0 }, 'slow', function() {
                $('#toggle').html('<i class="fas fa-bars"></i>');
            });
        },
        function() {
            $('#popout').animate({ left: -250 }, 'slow', function() {
                $('#toggle').html('<i class="fas fa-bars"></i>');
            });
        }
    );
})(jQuery);


//animated counter on scroll
var a = 0;


jQuery(window).scroll(function() {

    if(jQuery('#social').length){
        var oTop = jQuery('#social').offset().top - window.innerHeight;
        if (a == 0 && jQuery(window).scrollTop() > oTop) {
            jQuery('.counter-value').each(function() {
                var $this = jQuery(this),
                    countTo = $this.attr('data-count');

                $this.css('width', $this.width());

                jQuery({
                    countNum: 0
                }).animate({
                        countNum: countTo
                    },

                    {

                        duration: 3000,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum));
                        },
                        complete: function() {
                            $this.text(this.countNum);
                            //alert('finished');
                        }

                    });
            });
            a = 1;
        }
    }
    
});