<?php get_header('single'); ?>

<a class="header-home-btn" href="<?php echo home_url(); ?>" onclick="$(this).children('span').css({transform: 'translateX(-200px)'});">
    <i class="far fa-arrow-left"></i>
    <span>На главную</span>
</a>

<div class="not-found-wrapper">
    <div class="nf-text-box">
        <div class="nf-text-title"><span class="nf-number" id="nf-number">193</span><span class="nf-dot" style="display: none;">.</span></div>
        <div class="nf-text-subtitle" style="opacity: 0;">Страница не найдена</div>
    </div>
    <div class="nf-photo"></div>
</div>

<script src="<?php echo get_template_directory_uri(); ?>/js/numberAnimate.js"></script>
<script>

    $('#nf-number').html(Math.floor(Math.random()*899 + 100))
        .numberAnimate({
        animationTimes: [100, 500, 100]
    });

    setTimeout(() => {
        $('#nf-number').numberAnimate('set', Math.floor(Math.random()*899 + 100));

        setTimeout(() => {
            $('#nf-number').numberAnimate('set', Math.floor(Math.random()*899 + 100));

            setTimeout(() => {
                $('#nf-number').numberAnimate('set', 404);
                $('.nf-text-subtitle').animate({opacity: 1}, 700);

                setTimeout(() => {
                    $('.nf-dot').css({display: 'inline'}).addClass('anim');
                }, 700);
            }, 700);
        }, 700);
    }, 50);

</script>

<?php get_footer('single'); ?>
