
$(() => {
    profiles_list_init();
    map_init();
    mobile_menu_init();
});

function profiles_list_init() {
    profiles_list_click_handler();
    profiles_list_search_init();

    if($(window).width() > 782 && $(window).height() > 610) {
        profiles_list_enable_autoscrolling();
    }
}

function profiles_list_click_handler() {
    $('.s-profile-box').on('click', (e) => {
        profiles_list_show_profile(e.currentTarget);
        $('.profiles-s-section').addClass('popup-opened');
    });

    $('.profile-popup-top, .profile-popup-bottom-content').on('click', (e) => {
        if(!$('.profile-popup-top').is(':animated')) {
            $('.profile-popup-top').removeClass('opened').animate({bottom: '100%'}, 400, 'swing', () => {$('.profile-popup-top').css({opacity: 0});});
            $('.profile-popup-bottom').animate({top: '100%'}, 400);
            $('.profiles-s-section').removeClass('popup-opened');
        }
    })
}

function profiles_list_show_profile(profile_box, parent_box = $('.profiles-s-section')) {
    if(!parent_box.find('.profile-popup-top').is(':animated')) {
        parent_box.find('.profile-popup-top > div').css({backgroundImage: $(profile_box).find('.s-profile-photo').css('background-image')});
        parent_box.find('.profile-popup-bottom .s-profile-info-title').html($(profile_box).find('.s-profile-info-title').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-subtitle').html($(profile_box).find('.s-profile-info-subtitle').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-block-content.years').html($(profile_box).find('.s-profile-info-years').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-block-content.link').html($(profile_box).find('.s-profile-info-link').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-block-content.post').html($(profile_box).find('.s-profile-info-post').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-block-content.station').html($(profile_box).find('.s-profile-info-station').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-block-content.description').html($(profile_box).find('.s-profile-info-description').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-block-content.rewards').html($(profile_box).find('.s-profile-info-rewards').html());
        parent_box.find('.profile-popup-bottom .s-profile-info-block-content.attitude').html($(profile_box).find('.s-profile-info-att').html());

        const href = parent_box.find('.profile-popup-bottom .btn.more').attr('data-href');
        const new_href = href + (href.includes('?') ? '&' : '?') + 'profile_id=' + $(profile_box).attr('data-profile-id');
        parent_box.find('.profile-popup-bottom .btn.more').attr('href', new_href);
        parent_box.find('.profile-popup-bottom .btn.share').attr('data-url', new_href).attr('data-fio', $(profile_box).find('.s-profile-info-title').html());


        parent_box.find('.profile-popup-top').addClass('opened').css({opacity: 1}).animate({bottom: '70%'}, 400);
        parent_box.find('.profile-popup-bottom').animate({top: '30%'}, 400);
    }
}

function profiles_list_search_init() {
    const options = {
        valueNames: [ 's-profile-info-title', 's-profile-info-subtitle', 's-profile-info-att', 's-profile-info-years', 's-profile-info-post', 's-profile-info-station']
    };

    const userList = new List('profiles', options);
}

function profiles_list_enable_autoscrolling() {
    setInterval(() => {
        if($('.profiles-s-section:not(:hover):not(.popup-opened) .s-profiles-list').hasClass('s-profiles-list')) {
            const temp_scrollTop = document.querySelector('.profiles-s-section:not(:hover) .s-profiles-list').scrollTop;

            if($('.profiles-s-section .s-profiles-list').hasClass('down')) {
                document.querySelector('.profiles-s-section:not(:hover) .s-profiles-list').scrollTop += 1;
            }
            else {
                document.querySelector('.profiles-s-section:not(:hover) .s-profiles-list').scrollTop -= 1;
            }

            if(document.querySelector('.profiles-s-section:not(:hover) .s-profiles-list').scrollTop == temp_scrollTop) {
                $('.profiles-s-section .s-profiles-list').toggleClass('down');
            }
        }
    }, 1000/30); // 1000/(пикс. в сек.)
}

    function map_init() {
        ymaps.ready(init);
        function init(){
            window.yMap = new ymaps.Map("map", {
                center: [56.36677188, 53.24165034],
                zoom: 2
            });

            window.placemarks_functions.forEach((func) => {
                func();
            });
        }
    }

    function placemark_click_handler(e) {
        $('.temp-profile-box').html(e.originalEvent.target.properties._data.hintContent);

        profiles_list_show_profile($('.temp-profile-box .s-profile-box'), $(window).width() <= 782 || $(window).height() <= 610 ? $('.s-section.center') : $('.profiles-s-section'));
    }



function mobile_menu_init() {
    $('.header-menu-btn').on('click', (e) => {
        if ($('.mobile-menu').hasClass('opened')) {
            $('.header-menu-btn').removeClass('opened');
            $('.mobile-menu').removeClass('opened');
        }
        else {
            $('.header-menu-btn').addClass('opened');
            $('.mobile-menu').addClass('opened');
        }
    });

    $('.mobile-menu-link').on('click', (e) => {
        if($(e.currentTarget).attr('data-name') == 'instruction') {
            $('.menu-window').css({display: 'none'})
                .parents('.s-section').css({display: 'none'});
            $('.profiles-menu-window').css({display: 'flex'})
                .parents('.s-section').css({display: 'flex'});

            intro_instruction_mobile();
        }
        else {
            $('.menu-window').css({display: 'none'})
                .parents('.s-section').css({display: 'none'});
            $('.'+$(e.currentTarget).attr('data-name')+'-menu-window').css({display: $(e.currentTarget).attr('data-display')})
                .parents('.s-section').css({display: 'flex'});
        }

        $('.header-menu-btn').toggleClass('opened');
        $('.mobile-menu').css({opacity: 0});
        setTimeout(() => {
            $('.mobile-menu').removeClass('opened');
            setTimeout(() => {
                $('.mobile-menu').css({opacity: 1});
            }, 400);
        }, 400);
    });
}