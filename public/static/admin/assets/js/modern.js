$( document ).ready(function() {

    // Toggle Search
    $('.show-search').click(function(){
        $('.search-form').css('margin-top', '0');
        $('.search-input').focus();
    });

    $('.close-search').click(function(){
        $('.search-form').css('margin-top', '-60px');
    });


    // Fullscreen
    var fullScreenButton = document.getElementsByClassName("toggle-fullscreen");

    //Activate click listener
for (var i = 0; i < fullScreenButton.length; i++) {
    fullScreenButton[i].addEventListener("click", function () {

        //Toggle fullscreen off, activate it
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen(); // Firefox
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(); // Chrome and Safari
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen(); // IE
            }

        //Toggle fullscreen on, exit fullscreen
        } else {

            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }

    });
};


    // Waves
    Waves.displayEffect();

    // tooltips
    $( '[data-toggle~="tooltip"]' ).tooltip({
        container: 'body'
    });

    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function(html) {
        var switchery = new Switchery(html, { color: '#23B7E5' });
    });

    // Element Blocking
    function blockUI(item) {
        $(item).block({
            message: '<img src="/static/admin/assets/images/reload.gif" width="20px" alt="">',
            css: {
                border: 'none',
                padding: '0px',
                width: '20px',
                height: '20px',
                backgroundColor: 'transparent'
            },
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.9,
                cursor: 'wait'
            }
        });
    }

    function unblockUI(item) {
        $(item).unblock();
    }

    // Panel Control
    $('.panel-collapse').click(function(){
        $(this).closest(".panel").children('.panel-body').slideToggle('fast');
    });

    $('.panel-reload').click(function() {
        var el = $(this).closest(".panel").children('.panel-body');
        blockUI(el);
        window.setTimeout(function () {
            unblockUI(el);
        }, 1000);

    });

    $('.panel-remove').click(function(){
        $(this).closest(".panel").hide();
    });

    // Push Menu
    $('.push-sidebar').click(function(){
        var hidden = $('.sidebar');

        if (hidden.hasClass('visible')){
            hidden.removeClass('visible');
            $('.page-inner').removeClass('sidebar-visible');
        } else {
            hidden.addClass('visible');
            $('.page-inner').addClass('sidebar-visible');
        }
    });

    // sortable
    $(".sortable").sortable({
        connectWith: '.sortable',
        items: '.panel',
        helper: 'original',
        revert: true,
        placeholder: 'panel-placeholder',
        forcePlaceholderSize: true,
        opacity: 0.95,
        cursor: 'move'
    });

    // Uniform
    var checkBox = $("input[type=checkbox]:not(.switchery), input[type=radio]:not(.no-uniform)");
    if (checkBox.size() > 0) {
        checkBox.each(function() {
            $(this).uniform();
        });
    };

    // .toggleAttr() Function
    $.fn.toggleAttr = function(a, b) {
        var c = (b === undefined);
        return this.each(function() {
            if((c && !$(this).is("["+a+"]")) || (!c && b)) $(this).attr(a,a);
            else $(this).removeAttr(a);
        });
    };

    // Sidebar Menu
    var parent, ink, d, x, y;
    $('.sidebar .accordion-menu li .sub-menu').slideUp(0);
    $('.sidebar .accordion-menu li.open > .sub-menu').slideDown(0);
    $('.small-sidebar .sidebar .accordion-menu li.open .sub-menu').hide(0);
    $('.sidebar .accordion-menu > li.droplink > a').click(function(){

        if(!($('body').hasClass('small-sidebar'))&&(!$('body').hasClass('page-horizontal-bar'))&&(!$('body').hasClass('hover-menu'))) {

        var menu = $('.sidebar .menu'),
            sidebar = $('.page-sidebar-inner'),
            page = $('.page-content'),
            sub = $(this).next(),
            el = $(this);

        menu.find('li').removeClass('open');
        $('.sub-menu').slideUp(200, function() {
            sidebarAndContentHeight();
        });
        sidebarAndContentHeight();

        if (!sub.is(':visible')) {
            $(this).parent('li').addClass('open');
            $(this).next('.sub-menu').slideDown(200, function() {
                sidebarAndContentHeight();
            });
        } else {
            sub.slideUp(200, function() {
                sidebarAndContentHeight();
            });
        }
        return false;
        };

        if(($('body').hasClass('small-sidebar'))&&($('body').hasClass('page-sidebar-fixed'))) {

        var menu = $('.sidebar .menu'),
            sidebar = $('.page-sidebar-inner'),
            page = $('.page-content'),
            sub = $(this).next(),
            el = $(this);

        menu.find('li').removeClass('open');
        $('.sub-menu').slideUp(200, function() {
            sidebarAndContentHeight();
        });
        sidebarAndContentHeight();

        if (!sub.is(':visible')) {
            $(this).parent('li').addClass('open');
            $(this).next('.sub-menu').slideDown(200, function() {
                sidebarAndContentHeight();
            });
        } else {
            sub.slideUp(200, function() {
                sidebarAndContentHeight();
            });
        }
        return false;
        }
    });

    $('.sidebar .accordion-menu .sub-menu li.droplink > a').click(function(){

        var menu = $(this).parent().parent(),
            sidebar = $('.page-sidebar-inner'),
            page = $('.page-content'),
            sub = $(this).next(),
            el = $(this);

        menu.find('li').removeClass('open');
        sidebarAndContentHeight();

        if (!sub.is(':visible')) {
            $(this).parent('li').addClass('open');
            $(this).next('.sub-menu').slideDown(200, function() {
                sidebarAndContentHeight();
            });
        } else {
            sub.slideUp(200, function() {
                sidebarAndContentHeight();
            });
        }
        return false;
    });

    // Makes .page-inner height same as .page-sidebar height
    var sidebarAndContentHeight = function () {
        var content = $('.page-inner'),
            sidebar = $('.page-sidebar'),
            body = $('body'),
            height,
            footerHeight = $('.page-footer').outerHeight(),
            pageContentHeight = $('.page-content').height();

        content.attr('style', 'min-height:' + sidebar.height() + 'px !important');

        if (body.hasClass('page-sidebar-fixed')) {
            height = sidebar.height() + footerHeight;
        } else {
            height = sidebar.height() + footerHeight;
            if (height  < $(window).height()) {
                height = $(window).height();
            }
        }

        if (height >= content.height()) {
            content.attr('style', 'min-height:' + height + 'px !important');
        }
    };

    sidebarAndContentHeight();

    window.onresize = sidebarAndContentHeight;


    // Slimscroll
    $('.slimscroll').slimscroll({
        allowPageScroll: true
    });

    // Layout Settings
    var fixedHeaderCheck = document.querySelector('.fixed-header-check'),
        fixedSidebarCheck = document.querySelector('.fixed-sidebar-check'),
        horizontalBarCheck = document.querySelector('.horizontal-bar-check'),
        toggleSidebarCheck = document.querySelector('.toggle-sidebar-check'),
        boxedLayoutCheck = document.querySelector('.boxed-layout-check'),
        compactMenuCheck = document.querySelector('.compact-menu-check'),
        hoverMenuCheck = document.querySelector('.hover-menu-check'),
        defaultOptions = function() {

            if(($('body').hasClass('small-sidebar'))&&(toggleSidebarCheck.checked == 1)){
                toggleSidebarCheck.click();
            }

            if(!($('body').hasClass('page-header-fixed'))&&(fixedHeaderCheck.checked == 0)){
                fixedHeaderCheck.click();
            }

            if(($('body').hasClass('page-sidebar-fixed'))&&(fixedSidebarCheck.checked == 1)){
                fixedSidebarCheck.click();
            }

            if(($('body').hasClass('page-horizontal-bar'))&&(horizontalBarCheck.checked == 1)){
                horizontalBarCheck.click();
            }

            if(($('body').hasClass('compact-menu'))&&(compactMenuCheck.checked == 1)){
                compactMenuCheck.click();
            }

            if(($('body').hasClass('hover-menu'))&&(hoverMenuCheck.checked == 1)){
                hoverMenuCheck.click();
            }

            if(($('.page-content').hasClass('container'))&&(boxedLayoutCheck.checked == 1)){
                boxedLayoutCheck.click();
            }

            $(".theme-color").attr("href", '/static/admin/assets/css/themes/green.css');

            sidebarAndContentHeight();
        },
        str = $('.navbar .logo-box a span').text(),
        smTxt = (str.slice(0,1)),
        collapseSidebar = function() {
            $('body').toggleClass("small-sidebar");
            $('.navbar .logo-box a span').html($('.navbar .logo-box a span').text() == smTxt ? str : smTxt);
            sidebarAndContentHeight();

            if ($('body').hasClass('small-sidebar')) {
                changeStyleRequest('t_sidebar', true);
            } else {
                changeStyleRequest('t_sidebar', false);
            }
        },
        fixedHeader = function() {
            if(($('body').hasClass('page-horizontal-bar'))&&($('body').hasClass('page-sidebar-fixed'))&&($('body').hasClass('page-header-fixed'))){
                fixedSidebarCheck.click();
                alert("Static header isn't compatible with fixed horizontal nav mode. Modern will set static mode on horizontal nav.");
            };
            $('body').toggleClass('page-header-fixed');
            sidebarAndContentHeight();

            if ($('body').hasClass('page-header-fixed')) {
                changeStyleRequest('f_header', true);
            } else {
                changeStyleRequest('f_header', false);
            }
        },
        fixedSidebar = function() {
            if(($('body').hasClass('page-horizontal-bar'))&&(!$('body').hasClass('page-sidebar-fixed'))&&(!$('body').hasClass('page-header-fixed'))){
                fixedHeaderCheck.click();
                alert("Fixed horizontal nav isn't compatible with static header mode. Modern will set fixed mode on header.");
            };
            if(($('body').hasClass('hover-menu'))&&(!$('body').hasClass('page-sidebar-fixed'))){
                hoverMenuCheck.click();
                alert("Fixed sidebar isn't compatible with hover menu mode. Modern will set accordion mode on menu.");
            };
            $('body').toggleClass('page-sidebar-fixed');
            if ($('body').hasClass('page-sidebar-fixed')) {
                $('.page-sidebar-inner').slimScroll({
                    destroy:true
                });
                changeStyleRequest('f_sidebar', true);
            } else {
                changeStyleRequest('f_sidebar', false);
            }
            $('.page-sidebar-inner').slimScroll();
            sidebarAndContentHeight();
        },
        horizontalBar = function() {
            $('.sidebar').toggleClass('horizontal-bar');

            if ($('.sidebar').hasClass('horizontal-bar')) {
                changeStyleRequest('h_bar', true);
            } else {
                changeStyleRequest('h_bar', false);
            }

            $('.sidebar').toggleClass('page-sidebar');
            $('body').toggleClass('page-horizontal-bar');
            if(($('body').hasClass('page-sidebar-fixed'))&&(!$('body').hasClass('page-header-fixed'))){
                fixedHeaderCheck.click();
                alert("Static header isn't compatible with fixed horizontal nav mode. Modern will set static mode on horizontal nav.");
            };
            sidebarAndContentHeight();
        },
        boxedLayout = function() {
            $('.page-content').toggleClass('container');
            sidebarAndContentHeight();
            if ($('.page-content').hasClass('container')) {
                changeStyleRequest('b_layout', true);
            } else {
                changeStyleRequest('b_layout', false);
            }
        },
        compactMenu = function() {
            $('body').toggleClass('compact-menu');
            sidebarAndContentHeight();
            if ($('body').hasClass('compact-menu')) {
                changeStyleRequest('c_menu', true);
            } else {
                changeStyleRequest('c_menu', false);
            }
        },
        hoverMenu = function() {
            if((!$('body').hasClass('hover-menu'))&&($('body').hasClass('page-sidebar-fixed'))){
                fixedSidebarCheck.click();
                alert("Fixed sidebar isn't compatible with hover menu mode. Modern will set static mode on sidebar.");
            };
            $('body').toggleClass('hover-menu');
            sidebarAndContentHeight();
            if ($('body').hasClass('hover-menu')) {
                changeStyleRequest('h_menu', true);
            } else {
                changeStyleRequest('h_menu', false);
            }
        };


    // Logo text on Collapsed Sidebar
    $('.small-sidebar .navbar .logo-box a span').html($('.navbar .logo-box a span').text() == smTxt ? str : smTxt);


    if( !$('.theme-settings').length ) {
        $('.sidebar-toggle').click(function() {
            collapseSidebar();
        });
    };

    if( $('.theme-settings').length ) {
    fixedHeaderCheck.onchange = function() {
        fixedHeader();
    };

    fixedSidebarCheck.onchange = function() {
        fixedSidebar();
    };

    horizontalBarCheck.onchange = function() {
        horizontalBar();
    };

    toggleSidebarCheck.onchange = function() {
        collapseSidebar();
    };

    compactMenuCheck.onchange = function() {
        compactMenu();
    };

    hoverMenuCheck.onchange = function() {
        hoverMenu();
    };

    boxedLayoutCheck.onchange = function() {
        boxedLayout();
    };


    // Sidebar Toggle
    $('.sidebar-toggle').click(function() {
        toggleSidebarCheck.click();
    });

    // Reset options
    $('.reset-options').click(function() {
        defaultOptions();
        changeStyleRequest('reset', true);
    });

    // Color changer
    $(".colorbox").click(function(){
        var color =  $(this).attr('data-css');
        $(".theme-color").attr('href', '/static/admin/assets/css/themes/' + color + '.css');
        changeStyleRequest('custom_style', color);
        return false;
    });

    // Fixed Sidebar Bug
    if(!($('body').hasClass('page-sidebar-fixed'))&&(fixedSidebarCheck.checked == 1)){
        $('body').addClass('page-sidebar-fixed');
    }

    if(($('body').hasClass('page-sidebar-fixed'))&&(fixedSidebarCheck.checked == 0)){
        $('.fixed-sidebar-check').prop('checked', true);
    }

    // Fixed Header Bug
    if(!($('body').hasClass('page-header-fixed'))&&(fixedHeaderCheck.checked == 1)){
        $('body').addClass('page-header-fixed');
    }

    if(($('body').hasClass('page-header-fixed'))&&(fixedHeaderCheck.checked == 0)){
        $('.fixed-header-check').prop('checked', true);
    }

    // horizontal bar Bug
    if(!($('body').hasClass('page-horizontal-bar'))&&(horizontalBarCheck.checked == 1)){
        $('body').addClass('page-horizontal-bar');
        $('.sidebar').addClass('horizontal-bar');
        $('.sidebar').removeClass('page-sidebar');
    }

    if(($('body').hasClass('page-horizontal-bar'))&&(horizontalBarCheck.checked == 0)){
        $('.horizontal-bar-check').prop('checked', true);
    }

    // Toggle Sidebar Bug
    if(!($('body').hasClass('small-sidebar'))&&(toggleSidebarCheck.checked == 1)){
        $('body').addClass('small-sidebar');
    }

    if(($('body').hasClass('small-sidebar'))&&(toggleSidebarCheck.checked == 0)){
        $('.horizontal-bar-check').prop('checked', true);
    }

    // Boxed Layout Bug
    if(!($('.page-content').hasClass('container'))&&(boxedLayoutCheck.checked == 1)){
        $('.toggle-sidebar-check').addClass('container');
    }

    if(($('.page-content').hasClass('container'))&&(boxedLayoutCheck.checked == 0)){
        $('.boxed-layout-check').prop('checked', true);
    }

    // Boxed Layout Bug
    if(!($('.page-content').hasClass('container'))&&(boxedLayoutCheck.checked == 1)){
        $('.toggle-sidebar-check').addClass('container');
    }

    if(($('.page-content').hasClass('container'))&&(boxedLayoutCheck.checked == 0)){
        $('.boxed-layout-check').prop('checked', true);
    }

    // Boxed Layout Bug
    if(!($('.page-content').hasClass('container'))&&(boxedLayoutCheck.checked == 1)){
        $('.toggle-sidebar-check').addClass('container');
    }

    if(($('.page-content').hasClass('container'))&&(boxedLayoutCheck.checked == 0)){
        $('.boxed-layout-check').prop('checked', true);
    }
    }


    // Chat Sidebar
    // Chat Sidebar
    if($('.chat').length) {
        var menuRight = document.getElementById( 'cbp-spmenu-s1' ),
        showRight = document.getElementById( 'showRight' ),
        closeRight = document.getElementById( 'closeRight' ),
        menuRight2 = document.getElementById( 'cbp-spmenu-s2' ),
        closeRight2 = document.getElementById( 'closeRight2' ),
        body = document.body;

    showRight.onclick = function() {
        classie.toggle( menuRight, 'cbp-spmenu-open' );
    };

    closeRight.onclick = function() {
        classie.toggle( menuRight, 'cbp-spmenu-open' );
    };

    closeRight2.onclick = function() {
        classie.toggle( menuRight2, 'cbp-spmenu-open' );
    };

    $('.showRight2').click(function() {
        classie.toggle( menuRight2, 'cbp-spmenu-open' );
    });

    $(".chat-write form input").keypress(function (e) {
        if ((e.which == 13)&&(!$(this).val().length == 0)) {
            $('<div class="chat-item chat-item-right"><div class="chat-message">' + $(this).val() + '</div></div>').insertAfter(".chat .chat-item:last-child");
            $(this).val('');
        } else if(e.which == 13) {
            return;
        }
        var scrollTo_int = $('.chat').prop('scrollHeight') + 'px';
        $('.chat').slimscroll({
            allowPageScroll: true,
            scrollTo : scrollTo_int
        });
    });
    }




        ajaxRequest = function(url, data, successCallback = function(d){}, failCallback = function(d){}, type = 'post') {
            Pace.restart();
            Pace.track(function () {
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
                            if (data.message != undefined) toastr["success"](data.message, "Успех");
                            else toastr["success"]("Операция выполнена успешно", "Успех");
                            if (data.redirect != undefined){
                                setTimeout(function(){
                                    window.location.href = data.redirect;
                                }, 1500);
                            }
                        }  else {
                            toastr["error"]("Ошибка. " + data.message, "Уупс");
                        }
                     }
                   ).fail(
                    function(jqXHR, textStatus, errorThrown) {
                          if (jqXHR.responseJSON){
                              failCallback(jqXHR.responseJSON);
                              toastr["error"]("Ошибка. " + jqXHR.responseJSON.message, "Уупс");
                          } else {
                              toastr["error"]("Ошибка. Что-то пошло не так.", "Уупс");
                          }
                     }
                 );
            });
        }

        postAjaxRequest = function(url, data, successCallback = function(d){}, failCallback = function(d){}, showMessage = true, waringCallback = function(d){}) {
            Pace.restart();
            Pace.track(function () {
                $.post(url, data).done(
                    function(data){
                        if (data.status == 'success') {
                            successCallback(data);
                            if (showMessage){
                                if (data.message != undefined) toastr["success"](data.message, "Успех");
                                else toastr["success"]("Операция выполнена успешно", "Успех");
                            }
                            if (data.redirect != undefined){
                                setTimeout(function(){
                                    window.location.href = data.redirect;
                                }, 1500);
                            }
                        } else {
                            if (data.status == 'fail'){
                                toastr["warning"]("Внимание." + data.message, "Уупс");
                                waringCallback(data);
                            } else {
                                toastr["error"]("Ошибка." + data.message, "Уупс");
                                failCallback(data);
                            }
                        }
                     }
                   ).fail(
                    function(jqXHR, textStatus, errorThrown) {
                          if (jqXHR.responseJSON){
                              console.log(jqXHR.responseJSON);
                              if (jqXHR.responseJSON.status == 'fail'){
                                  toastr["warning"]("Внимание." + jqXHR.responseJSON.message, "Уупс");
                                  waringCallback(jqXHR.responseJSON);
                              } else {
                                  toastr["error"]("Ошибка." + jqXHR.responseJSON.message, "Уупс");
                                  failCallback(jqXHR.responseJSON);
                              }
                          } else {
                              toastr["error"]("Ошибка. Что-то пошло не так.", "Уупс");
                          }
                     }
                 );
            });
        }





        paginationRequest = function(url, element, template, max = 10, afterSuccessLoadingCallBack = function(d){}) {
            var page = 1;
            var incomplete_results = true;
            var isLoading = false;
            $(element).after('<div class="alert alert-success text-center" data-more="true" role="alert" id="' + ((element.substr(0, 1) == '#') ? element.substr(1) : element) + 'Alert">Загружается...</div>');
            loadList(page, max, true);
            function loadList(page, max, firstCall = false) {
                isLoading = true;
                $(element + 'Alert').removeClass('alert-danger').addClass('alert-success').html("Загружается...").show();
                Pace.restart();
                Pace.track(function () {
                    $.post(url, {
                        page: page,
                        max: max
                    }).done(
                        function(data){
                            var nothingFound = false;
                            if (data.status == "success"){
                                result = template(data);
                                incomplete_results = data.incomplete_results;
                                if (data.data.length > 0) {
                                    $(element).find('tbody').append(result);
                                } else if (firstCall && data.data.length == 0) {
                                    nothingFound = true;
                                    $(element + 'Alert').removeClass('alert-success').addClass('alert-danger').html("Здесь пока ничего нет").show();
                                } else {
                                    $(element + 'Alert').data('more', false);
                                }
                            } else {
                                $(element + 'Alert').hide();
                                toastr["error"]("Ошибка. " + data.message, "Уупс");
                            }
                            isLoading = false;
                            if (!incomplete_results && !nothingFound) $(element + 'Alert').hide();
                            afterSuccessLoadingCallBack(data);
                         }
                       ).fail(
                        function(jqXHR, textStatus, errorThrown) {
                              if (jqXHR.responseJSON){
                                  toastr["error"]("Ошибка. " + jqXHR.responseJSON.message, "Уупс");
                              } else {
                                  toastr["error"]("Ошибка. Что-то пошло не так.", "Уупс");
                              }
                              $(element + 'Alert').hide();
                              isLoading = false;
                         }
                     );
                });

            }

            $(window).scroll(function(){

              var position = $(window).scrollTop();
              var bottom = $(document).height() - $(window).height();

              if (bottom - 100 < position && incomplete_results && !isLoading){
                  page += 1;
                  loadList(page, max);
              }

             });
        }












        filteredPaginationRequest = function(url, filter, element, template, max = 10) {
            var page = 1;
            var incomplete_results = true;
            var isLoading = false;
            if ($(element).closest('.table-responsive').find('div[role=alert]').length == 0) $(element).after('<div class="alert alert-success text-center" role="alert" data-loaded="true" id="' + ((element.substr(0, 1) == '#') ? element.substr(1) : element) + 'Alert">Загружается...</div>');
            loadFilteredList(page, max, true);
            function loadFilteredList(page, max, firstCall = false) {
                isLoading = true;
                $(element + 'Alert').removeClass('alert-danger').addClass('alert-success').html("Загружается...").show();
                Pace.restart();
                Pace.track(function () {
                    $.post(url, jQuery.extend({
                                    page: page,
                                    max: max
                                },
                                    filter)
                    ).done(
                        function(data){
                            var nothingFound = false;
                            if (data.status == "success"){
                                result = template(data);
                                incomplete_results = data.incomplete_results;
                                if (data.data.length > 0) {
                                    if (firstCall){
                                        $(element).find('tbody').html(result);
                                    } else {
                                        $(element).find('tbody').append(result);
                                    }

                                } else if (firstCall && data.data.length == 0) {
                                    nothingFound = true;
                                    $(element + 'Alert').removeClass('alert-success').addClass('alert-danger').html("Здесь пока ничего нет").show();
                                }
                            } else {
                                $(element + 'Alert').hide();
                                toastr["error"]("Ошибка. " + data.message, "Уупс");
                            }
                            isLoading = false;
                            if (!incomplete_results && !nothingFound) $(element + 'Alert').hide();
                         }
                       ).fail(
                        function(jqXHR, textStatus, errorThrown) {
                              if (jqXHR.responseJSON){
                                  toastr["error"]("Ошибка. " + jqXHR.responseJSON.message, "Уупс");
                              } else {
                                  toastr["error"]("Ошибка. Что-то пошло не так.", "Уупс");
                              }
                              $(element + 'Alert').hide();
                              isLoading = false;
                         }
                     );
                });

            }

            $(window).scroll(function(){

              var position = $(window).scrollTop();
              var bottom = $(document).height() - $(window).height();

              if (bottom - 100 < position && incomplete_results && !isLoading){
                  page += 1;
                  loadFilteredList(page, max);
              }

             });
        }









        function changeStyleRequest(argument, value) {
            postAjaxRequest('/admin/ajax/changeStyle',
                            {
                                type: argument,
                                value: value
                            },
                            function(d){},
                            function(d){},
                            false
            );
        }


        $('button[type=link]').click(function(){
            link = $(this).data('href');
            if (link != undefined) window.location.href = link;
        });


        $('#lockScreen').on('click', function(){

            Pace.restart();
            Pace.track(function () {
                $.post('/admin/ajax/lock', {
                    action: 'lock'
                }).done(
                    function(data){
                        if (data.status == 'success') {
                            location.reload();
                        }  else {
                            $('#cancelDeleteAccount').click();
                            toastr["error"]("Ошибка. " + data.message, "Уупс");
                        }
                     }
                   ).fail(
                    function(jqXHR, textStatus, errorThrown) {
                          if (jqXHR.responseJSON){
                              toastr["error"]("Ошибка. " + jqXHR.responseJSON.message, "Уупс");
                          } else {
                              toastr["error"]("Ошибка. Что-то пошло не так.", "Уупс");
                          }
                     }
                 );
            });


        });
        function getCookie(name) {
            var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }
        function setCookie(name, value, options) {
            options = options || {};

            var expires = options.expires;

            if (typeof expires == "number" && expires) {
                var d = new Date();
                d.setTime(d.getTime() + expires * 1000);
                expires = options.expires = d;
            }
            if (expires && expires.toUTCString) {
                options.expires = expires.toUTCString();
            }

            value = encodeURIComponent(value);

            var updatedCookie = name + "=" + value;

            for (var propName in options) {
                updatedCookie += "; " + propName;
                var propValue = options[propName];
                if (propValue !== true) {
                    updatedCookie += "=" + propValue;
                }
            }
            document.cookie = updatedCookie;
        }




});


var removeElement = function(element) {
    element.css("background-color","red").css("color","white").fadeOut(400, function(){
        $(this).remove();
    });
}
