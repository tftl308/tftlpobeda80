<?php
/*
Template Name: Страница участника ВОВ
*/

$profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

$post = get_post($profile_id);

if (!$post || $post->post_type !== 'zayavka') {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part(404);
    exit();
}

$p_fio = get_the_title($post->ID);
$p_job = get_post_meta($post->ID, 'rank', true);
$p_photo = get_post_meta($post->ID, 'photo', true) ?: get_bloginfo('template_directory') . "/img/profile-placeholder.jpg";
$p_photos = get_post_meta($post->ID, 'photos', true);
$p_years = get_post_meta($post->ID, 'years', true);
$p_years_born = isset($p_years['years_born']) ? $p_years['years_born'] : '';
$p_years_death = isset($p_years['years_death']) ? $p_years['years_death'] : 'наше время';
$p_years_display = $p_years_born . " - " . $p_years_death;

$p_post = get_post_meta($post->ID, 'history', true) ?: "нет";
$p_station = get_post_meta($post->ID, 'position', true) ?: "нет";
$p_rewards = get_post_meta($post->ID, 'awards', true) ?: "нет";
$p_attitude = get_post_meta($post->ID, 'attitude', true);
$p_link = get_post_meta($post->ID, 'link', true) ?: "нет";
$p_info = get_post_meta($post->ID, 'history', true) ?: "Дополнительная информация отсутствует.";


//while (have_rows('profiles', get_option('page_on_front'))) { the_row();
//    if(get_sub_field('id') == $_GET['profile_id']) {
//        $found = true;
//
//        $p_fio = get_sub_field('fio');
//        $p_job = get_sub_field('job');
//
//        if(get_sub_field('photo')) $p_photo = get_sub_field('photo'); else $p_photo = get_bloginfo('template_directory') . "/img/profile-placeholder.jpg";
//
//        $p_years = get_sub_field('years')['years_born'] . " - ";
//        if(get_sub_field('years')['years_death']) $p_years .= get_sub_field('years')['years_death']; else $p_years .= "наше время";
//
//        if(get_sub_field('post')) $p_post = get_sub_field('post'); else $p_post = "нет";
//        if(get_sub_field('station')) $p_station = get_sub_field('station'); else $p_station = "нет";
//        if(get_sub_field('rewards')) $p_rewards = get_sub_field('rewards'); else $p_rewards = "нет";
//        $p_attitude = get_sub_field('attitude');
//        if(get_sub_field('info')) $p_info = get_sub_field('info'); else $p_info = "Дополнительная информация отстутствует.";
//
//        break;
//    }
//}
//
//if(!$found) {
//    global $wp_query;
//    $wp_query->set_404();
//    status_header( 404);
//    get_template_part( 404); exit();
//}

?>

<?php get_header('single'); ?>

    <script>
        document.title = '<?php echo $p_fio; ?> - <?php bloginfo('name'); ?>';
    </script>

    <a class="header-home-btn" href="<?php echo home_url(); ?>"
       onclick="$(this).children('span').css({transform: 'translateX(-200px)'});">
        <i class="far fa-arrow-left"></i>
        <span>На главную</span>
    </a>

    <div class="profile-wrapper">
        <div class="profile-wrapper-left">
            <div class="p-dark-shape"></div>

            <div class="profile-info">
                <div class="profile-info-text">
                    <div class="s-profile-info-title"><?php echo $p_fio; ?></div>
                    <div class="s-profile-info-subtitle"><?php echo $p_job; ?></div>
                </div>
                <img src="<?php echo $p_photo; ?>" alt="" class="profile-info-img">
                <div style="background-image: url('<?php echo $p_photo; ?>')" alt=""
                     class="profile-info-img-mobile"></div>
                <a class="mobile-profile-right-btn">
                    <i class="far fa-chevron-left"></i>
                </a>

                <div class="s-profile-info-block-name"><span>Дополнительная информация</span></div>

                <script>
                    $('.mobile-profile-right-btn').on('click', (e) => {
                        $('.profile-wrapper-left .p-dark-shape').show().animate({opacity: '.7'}, 200);
                        $('.profile-wrapper-right').css({left: '30%'});
                    });

                    $('.profile-wrapper-left .p-dark-shape').on('click', (e) => {
                        $('.profile-wrapper-left .p-dark-shape').animate({opacity: '0'}, 200);
                        setTimeout(() => {
                            $('.profile-wrapper-left .p-dark-shape').hide();
                        }, 200);
                        $('.profile-wrapper-right').css({left: '100%'});
                    });
                </script>
            </div>
            <div class="profile-content content">
                <?php echo $p_info; ?> <br>
                <div class="profile-content-images">
                    <?php if (is_array($p_photos)) {
                        foreach ($p_photos as $photo) {
                            echo '<img src="' . esc_url($photo) . '" alt="Photo" />';
                        }
                    } ?>
                </div>
            </div>
        </div>
        <div class="profile-wrapper-right">
            <div class="s-profile-info-block">
                <div class="s-profile-info-block-name"><span>Отношение к ТФТЛ</span></div>
                <div class="s-profile-info-block-content attitude"><?php echo $p_attitude; ?></div>
            </div>
            <div class="s-profile-info-block">
                <div class="s-profile-info-block-name"><span>Годы жизни</span></div>
                <div class="s-profile-info-block-content primary years"><?php echo $p_years; ?></div>
            </div>
            <div class="s-profile-info-block">
                <div class="s-profile-info-block-name"><span>Воинское звание / гражданский</span></div>
                <div class="s-profile-info-block-content primary post"><?php echo $p_job; ?></div>
            </div>
            <div class="s-profile-info-block">
                <div class="s-profile-info-block-name"><span>Воинская должность / труженик тыла</span></div>
                <div class="s-profile-info-block-content primary station"><?php echo $p_station; ?></div>
            </div>
            <div class="s-profile-info-block">
                <div class="s-profile-info-block-name"><span>Награды</span></div>
                <div class="s-profile-info-block-content primary rewards"><?php echo $p_rewards; ?></div>
            </div>
            <div class="s-profile-info-block">
                <div class="s-profile-info-block-name"><span>Ссылка на карточку в Памяти народа</span></div>
                <div class="s-profile-info-block-content primary link"><?php echo $p_link; ?></div>
            </div>

            <div class="profile-popup-buttons">
                <a class="btn share" data-fio="<?php echo $p_fio; ?>"
                   onclick="share_dialog($(this).attr('data-url'), $(this).attr('data-fio'))">
                    <span class="btn-icon-box"><i class="fas fa-share"></i></span>
                    <span>Поделиться</span>
                </a>

                <script>
                    $('.btn.share').attr('data-url', window.location.href);
                </script>
            </div>
        </div>
    </div>

    <script>
        // fix images
        // $('.content img').each((index, elem) => {
        //     $(elem).attr('src', $(elem).attr('srcset').split(', ')[$(elem).attr('srcset').split(', ').length - 1].split(' ')[0]);
        // });

        $('.content img').not('.blocks-gallery-grid img').each((index, elem) => {
            new Viewer(elem, {navbar: false});
            $(elem).css({cursor: 'pointer'});
        });
        $('.blocks-gallery-grid').each((index, elem) => {
            new Vie
            wer(elem);
            $(elem).find('img').css({cursor: 'pointer'});
        });
    </script>

<?php get_footer(); ?>