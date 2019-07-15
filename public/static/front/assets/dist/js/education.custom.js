/* 
Assan Education template
Author - design_mylife
Project Version - v1.0
 */
(function ($) {
    "use strict";
    /**Preloader*/
    $(window).preloader({
        delay: 500
    });
    //auto close navbar-collapse on click a
    $('.nav-item>[data-scroll]').on('click', function () {
        $('.navbar-toggler:visible').click();
    });
    /*sticky navbar*/
    $("#navbar-sticky").sticky({ topSpacing: 0 });
    /**transparent header fixed-top */
    /*header shrink*/

    var scrollPosition = $(window).scrollTop();
    if (scrollPosition >= 100){
        $("#navbar-fixed-top").addClass("sticky-active");
    }
    if (scrollPosition <= 46) {
        $("#navbar-fixed-top").addClass("scroll-nav-address");
        $('.nav-social-icons').addClass('nav-social-icons-hidden');
    }


    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll <= 46) {
            $("#navbar-fixed-top").addClass("scroll-nav-address");
            $('.nav-contacts').removeClass('not-show');
            $('.nav-social-icons').addClass('nav-social-icons-hidden');
        } else {
            $("#navbar-fixed-top").removeClass("scroll-nav-address");
            $('.nav-contacts').addClass('not-show');
            $('.nav-social-icons').removeClass('nav-social-icons-hidden');
        }

        if (scroll >= 100) {
            $("#navbar-fixed-top").addClass("sticky-active");

        } else {
            $("#navbar-fixed-top").removeClass("sticky-active");

        }
    });
    /*Tetimonials carousel*/
    $('.carousel-testimonials').owlCarousel({
        loop: true,
        autoPlay: true,
        margin: 15,
        nav: false,
        dots: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
    /*video modal*/
    $('.play-video').magnificPopup({
        type: 'iframe',
        midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    });
    /*smooth scroll*/
    smoothScroll.init({
        selector: '[data-scroll]', // Selector for links (must be a class, ID, data attribute, or element tag)
        speed: 800, // Integer. How fast to complete the scroll in milliseconds
        easing: 'easeInOutCubic', // Easing pattern to use
        offset: 70, // Integer. How far to offset the scrolling anchor location in pixels
        callback: function (anchor, toggle) { } // Function to run after scrolling
    });
    /**sticky sidebar */
    jQuery('.sticky-content, .sticky-sidebar').theiaStickySidebar({
        // Settings
        additionalMarginTop: 80
    });
    /*jquery ui search */
    $( ".select-ui" ).selectmenu();
})(jQuery);
$(function () {
    "use strict";
    if ($('.scrollReveal').length && !$('html.ie9').length) {
        $('.scrollReveal').parent().css('overflow', 'hidden');
        window.sr = ScrollReveal({
            reset: true,
            distance: '25px',
            mobile: true,
            duration: 850,
            scale: 1,
            viewFactor: 0.3,
            easing: 'ease-in-out'
        });
        sr.reveal('.sr-top', { origin: 'top' });
        sr.reveal('.sr-bottom', { origin: 'bottom' });
        sr.reveal('.sr-left', { origin: 'left' });
        sr.reveal('.sr-long-left', { origin: 'left', distance: '70px', duration: 1000 });
        sr.reveal('.sr-right', { origin: 'right' });
        sr.reveal('.sr-scaleUp', { scale: '0.8' });
        sr.reveal('.sr-scaleDown', { scale: '1.15' });

        sr.reveal('.sr-delay-1', { delay: 200 });
        sr.reveal('.sr-delay-2', { delay: 400 });
        sr.reveal('.sr-delay-3', { delay: 600 });
        sr.reveal('.sr-delay-4', { delay: 800 });
        sr.reveal('.sr-delay-5', { delay: 1000 });
        sr.reveal('.sr-delay-6', { delay: 1200 });
        sr.reveal('.sr-delay-7', { delay: 1400 });
        sr.reveal('.sr-delay-8', { delay: 1600 });

        sr.reveal('.sr-ease-in-out-quad', { easing: 'cubic-bezier(0.455,  0.030, 0.515, 0.955)' });
        sr.reveal('.sr-ease-in-out-cubic', { easing: 'cubic-bezier(0.645,  0.045, 0.355, 1.000)' });
        sr.reveal('.sr-ease-in-out-quart', { easing: 'cubic-bezier(0.770,  0.000, 0.175, 1.000)' });
        sr.reveal('.sr-ease-in-out-quint', { easing: 'cubic-bezier(0.860,  0.000, 0.070, 1.000)' });
        sr.reveal('.sr-ease-in-out-sine', { easing: 'cubic-bezier(0.445,  0.050, 0.550, 0.950)' });
        sr.reveal('.sr-ease-in-out-expo', { easing: 'cubic-bezier(1.000,  0.000, 0.000, 1.000)' });
        sr.reveal('.sr-ease-in-out-circ', { easing: 'cubic-bezier(0.785,  0.135, 0.150, 0.860)' });
        sr.reveal('.sr-ease-in-out-back', { easing: 'cubic-bezier(0.680, -0.550, 0.265, 1.550)' });
    }



});


var ajaxRequest = function(url, data, successCallback = function(d){}, failCallback = function(d){}, type = 'post') {
    $.ajax({
        url: url,
        type: type,
        data: data,
        contentType: false,
        processData: false
    }).done(
        function(data){
            if (data.status == 'success') {
                successCallback(data);
                if (data.message != undefined) {
                    swal({
                        title: "Успех",
                        text: data.message,
                        icon: "success",
                        button: "Ok",
                    });
                } else {
                    swal({
                        title: "Успех",
                        text: "Операция выполнена успешно",
                        icon: "success",
                        button: "Ok",
                    });
                }
                if (data.redirect != undefined){
                    setTimeout(function(){
                        window.location.href = data.redirect;
                    }, 1500);
                }
            }  else {
                swal({
                    title: "Уупс",
                    text: "Ошибка." + data.message,
                    icon: "error",
                    button: "Ok",
                });
            }
        }
    ).fail(
        function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.responseJSON){
                failCallback(jqXHR.responseJSON);
                swal({
                    title: "Уупс",
                    text: "Ошибка." + jqXHR.responseJSON.message,
                    icon: "error",
                    button: "Ok",
                });
            } else {
                swal({
                    title: "Уупс",
                    text: "Ошибка. Что-то пошло не так.",
                    icon: "error",
                    button: "Ok",
                });
            }
        }
    );
};

var postAjaxRequest = function(url, data, successCallback = function(d){}, failCallback = function(d){}, showMessage = true, waringCallback = function(d){}) {
    $.post(url, data).done(
        function(data){
            if (data.status == 'success') {
                successCallback(data);
                if (showMessage){
                    if (data.message != undefined) {
                        swal({
                            title: "Успех",
                            text: data.message,
                            icon: "success",
                            button: "Ok",
                        });
                    } else {
                        swal({
                            title: "Успех",
                            text: "Операция выполнена успешно",
                            icon: "success",
                            button: "Ok",
                        });
                    }
                }
                if (data.redirect != undefined){
                    setTimeout(function(){
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                if (data.status == 'fail'){
                    swal({
                        title: "Внимание",
                        text: "Внимание." + data.message,
                        icon: "warning",
                        button: "Ok",
                    });
                    waringCallback(data);
                } else {
                    swal({
                        title: "Уупс",
                        text: "Ошибка." + data.message,
                        icon: "error",
                        button: "Ok",
                    });
                    failCallback(data);
                }
            }
        }
    ).fail(
        function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.responseJSON){
                console.log(jqXHR.responseJSON);
                if (jqXHR.responseJSON.status == 'fail'){
                    swal({
                        title: "Уупс",
                        text: "Внимание." + jqXHR.responseJSON.message,
                        icon: "error",
                        button: "Ok",
                    });
                    waringCallback(jqXHR.responseJSON);
                } else {
                    swal({
                        title: "Уупс",
                        text: "Ошибка." + jqXHR.responseJSON.message,
                        icon: "error",
                        button: "Ok",
                    });
                    failCallback(jqXHR.responseJSON);
                }
            } else {
                swal({
                    title: "Уупс",
                    text: "Ошибка. Что-то пошло не так.",
                    icon: "error",
                    button: "Ok",
                });
            }
        }
    );
}

