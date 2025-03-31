<?php
get_header();
$important_remember = get_theme_mod('important_remember', 'Как важно помнить<br>тех, кому мы обязаны жизнью...<br>Как важно знать<br>тех, кто подарил нам Победу!');
$logo = get_theme_mod('custom_logo', get_template_directory_uri() . '/img/logo.jpg');
?>


    <div class="help-button">
        <div class="hb-content">
            <div class="hb-left">Инструктаж</div>
            <div class="hb-right"><i class="far fa-info"></i></div>
			
        </div>    
   	</div>
    <div class="sections-wrapper">
        <div class="s-section profiles-s-section menu-window profiles-menu-window" id="profiles">
            <div class="s-title">Связь поколений</div>
            <div class="s-search">
                <i class="far fa-search"></i>
                <input class="s-search-input search" type="text" placeholder="Поиск">
            </div>
            <div class="s-profiles-list-wrapper">
                <div class="s-profiles-list list down">

                    <script>
                        window.placemarks_functions = [];
                    </script>

                    <?php

                    $myposts = get_posts([
                        'posts_per_page' => -1,
                        'post_type' => 'zayavka',
                    ]);

                    
                    $cards_arr = array();

                    foreach ($myposts as $post) {
                        setup_postdata($post);

                        $fio = get_post_meta($post->ID, 'fio', true);
                        $attitude = get_post_meta($post->ID, 'attitude', true);
                        $years = get_post_meta($post->ID, 'years', true);
                        $rank = get_post_meta($post->ID, 'rank', true);
                        $position = get_post_meta($post->ID, 'position', true);
                        $label = get_post_meta($post->ID, 'label', true);
                        $link = get_post_meta($post->ID, 'link', true);
                        $awards = get_post_meta($post->ID, 'awards', true);
                        $history = get_post_meta($post->ID, 'history', true);
                        $coords = get_post_meta($post->ID, 'coords', true);
                        $descriptionCoords = get_post_meta($post->ID, 'label', true);
                        $photo = get_post_meta($post->ID, 'photo', true);
                        $photos = get_post_meta($post->ID, 'photos', true);

                        $years_born = '';
                        $years_death = '';

                        $cards_arr[] = [
                            'index' => $post->ID,
                            'photo' => $photo,
                            'fio' => $fio,
                            'job' => $rank,
                            'attitude' => $attitude,
                            'years' => $years,
                            'post' => $history,
                            'link' => $link,
                            'station' => $position,
                            'description' => $descriptionCoords,
                            'rewards' => $awards,
                            'map_points' => $coords,
                        ];
                    }

                    wp_reset_postdata();

                    shuffle($cards_arr);


                    if (count($cards_arr) != 0):

                        foreach ($cards_arr as $card) : var_dump($card); ?>

                            <div class="s-profile-box" data-profile-id="<?php echo $card['index']; ?>">
                                <div class="s-profile-photo"
                                     <?php if ($card['photo']): ?>style="background-image: url('<?php echo $card['photo']; ?>')"<?php endif; ?>></div>
                                <div class="s-profile-info">
                                    <div class="s-profile-info-title"><?php echo get_the_title($card['index']); ?></div>
                                    <div class="s-profile-info-subtitle"><?php echo $card['job']; ?></div>
                                    <div class="s-profile-info-att"><?php echo $card['attitude']; ?></div>
                                    <div class="s-profile-info-years"><?php echo $card['years']; ?> </div>
                                    <div class="s-profile-info-post"><?php echo $card['job']; ?></div>
                                    
                                    <div class="s-profile-info-station"><?php echo $card['station']; ?></div>
                                    <div class="s-profile-info-description"><?php echo $card['description']; ?></div>
                                    <div class="s-profile-info-rewards"><?php echo $card['rewards']; ?></div>
                                </div>
                            </div>

                        <?php

                        if (!empty($card['map_points'])) :
                        if (strpos($card['map_points'], ',') !== false) {
                        list($lat, $lng) = explode(',', $card['map_points']);
                        ?>
                            <script>
                                window.placemarks_functions.push(() => {
                                    var placemark_html = "<div class=\"s-profile-box in-map\" data-profile-id=\"<?php echo $card['index']; ?>\">" +
                                        "<div class=\"s-profile-photo\" <?php if ($card['photo']): ?>style=\"background-image: url('<?php echo $card['photo']; ?>')\"<?php endif; ?>></div>" +
                                        "<div class=\"s-profile-info\">" +
                                        "<div class=\"s-profile-info-title\"><?php echo get_the_title($card['index']); ?></div>" +
                                        "<div class=\"s-profile-info-subtitle\"><?php echo addslashes($card['job']); ?></div>" +
                                        "<div class=\"s-profile-info-link\"><?php echo addslashes($card['link']); ?></div>" +
                                        "<div class=\"s-profile-info-att\"><?php echo str_replace(array("\r\n","\r", "\n", ";"), '<br>', addslashes($card['attitude'])); ?></div>" +
                                        "<div class=\"s-profile-info-years\"><?php echo addslashes($card['years']) ?></div>" +
                                        "<div class=\"s-profile-info-post\"><?php echo addslashes($card['job']); ?></div>" +
                                        "<div class=\"s-profile-info-station\"><?php echo addslashes($card['station']); ?></div>" +
                                        "<div class=\"s-profile-info-description\">Отмечено на карте: <?php echo addslashes($card['description']); ?></div>" +
                                        "<div class=\"s-profile-info-rewards\"><?php echo str_replace(array("\r\n","\r", "\n", ";"), '<br>', addslashes($card['rewards'])); ?></div>" +
                                        "</div>" +
                                        "</div>";

                                    var placemark = new ymaps.Placemark([<?php echo $lat; ?>, <?php echo $lng; ?>], {
                                        hintContent: placemark_html
                                    }, {
                                        iconColor: '#C2351F'
                                    });

                                    placemark.events.add(['click'], placemark_click_handler);

                                    window.yMap.geoObjects.add(placemark);
                                });
                            </script>
                            <?php
                        } else {
                            echo "<script>console.error('Неверный формат координат: {$card['map_points']}');</script>";
                        }
                        endif;
                            ?>

                        <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="profile-popup-top">
                <div class="profile-popup-top-bg"></div>
                <div class="profile-popup-top-photo"></div>
            </div>
            <div class="profile-popup-bottom">
                <div class="profile-popup-bottom-content">
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-title">Фамилия Имя Отчество</div>
                        <div class="s-profile-info-subtitle">Звание</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Отношение к учреждению</span></div>
                        <div class="s-profile-info-block-content attitude">Отношение к учреждению</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Годы жизни</span></div>
                        <div class="s-profile-info-block-content primary years">Годы жизни</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Воинское звание / труженик тыла / блокадник</span></div>
                        <div class="s-profile-info-block-content primary post">Воинское звание / труженик тыла / блокадник</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Воинская должность / профессия / ученая степень </span></div>
                        <div class="s-profile-info-block-content primary station">Воинская должность / профессия / ученая степень
                        </div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Награды</span></div>
                        <div class="s-profile-info-block-content primary rewards">Награды</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Ссылка на Память народа / Рувики / Дорогу памяти</span></div>
                        <div class="s-profile-info-block-content primary link">Ссылка на Память народа / Рувики / Дорогу памяти</div>
                    </div>

                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Почему эта метка</span></div>
                        <div class="s-profile-info-block-content description">Почему эта метка</div>
                    </div>
                </div>

                <div class="profile-popup-buttons">

<!--                    --><?php //$pages = get_pages(array(
//                        'meta_key' => '_wp_page_template',
//                        'meta_value' => 'profile.php'
//                    ));
//                    $page_link = $pages[0]->guid;
//
//                    var_dump($pages);

                    $post_link = get_permalink($card['index']);
                    ?>

                    <a class="btn more" data-href="<?php echo $post_link ?>">
                        <span class="btn-icon-box"><i class="fas fa-info-circle"></i></span>
                        <span>Подробнее</span>
                    </a>
                    <a class="btn share"
                       onclick="share_dialog('<?php echo esc_url($post_link); ?>', '<?php echo esc_attr($card['fio']); ?>')">
                        <span class="btn-icon-box"><i class="fas fa-share"></i></span>
                        <span>Поделиться</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="s-section center">
            <div class="s-site-info menu-window info-menu-window">

                <div class="s-site-info-img" style="background: url('<?php echo esc_url($logo); ?>') no-repeat center center / contain;"></div>
                <div> <?php echo wp_kses_post($important_remember); ?> </div>

            </div>
            <div class="s-title menu-window map-menu-window">Карта нашей победы</div>
            <div id="map" class="s-map menu-window map-menu-window">

            </div>
            <div class="temp-profile-box s-profile-box in-map" style="display: none;">

            </div>

            <div class="profile-popup-top">
                <div class="profile-popup-top-bg"></div>
                <div class="profile-popup-top-photo"></div>
            </div>
            <div class="profile-popup-bottom">
                <div class="profile-popup-bottom-content">
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-title">Фамилия Имя Отчество</div>
                        <div class="s-profile-info-subtitle">Звание</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Отношение к учреждению</span></div>
                        <div class="s-profile-info-block-content attitude">Отношение к учреждению</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Годы жизни</span></div>
                        <div class="s-profile-info-block-content primary years">Годы жизни</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Должность</span></div>
                        <div class="s-profile-info-block-content primary post">Должность</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Место службы</span></div>
                        <div class="s-profile-info-block-content primary station">Место службы</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Награды</span></div>
                        <div class="s-profile-info-block-content primary rewards">Награды</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Ссылка на Память народа / Рувики / Дорогу памяти</span></div>
                        <div class="s-profile-info-block-content primary link">Ссылка на Память народа / Рувики / Дорогу памяти</div>
                    </div>
                    <div class="s-profile-info-block">
                        <div class="s-profile-info-block-name"><span>Почему эта метка</span></div>
                        <div class="s-profile-info-block-content description">Почему эта метка</div>
                    </div>
                </div>

                <div class="profile-popup-buttons">

                    <a class="btn more"
<!--                       data-href="--><?php //echo $page_link ?><!--"-->
                    >
                        <span class="btn-icon-box"><i class="fas fa-info-circle"></i></span>
                        <span>Подробнее</span>
                    </a>
                    <a class="btn share" onclick="share_dialog($(this).attr('data-url'), $(this).attr('data-fio'))">
                        <span class="btn-icon-box"><i class="fas fa-share"></i></span>
                        <span>Поделиться</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="s-section menu-window projects-menu-window">
            <div class="s-title">Истории наших побед</div>
            <div class="s-projects-list-wrapper">
                <div class="s-projects-list">

                    <?php
                    global $post;

                    $wpb_all_query = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1));
                    if ($wpb_all_query->have_posts()) {
                        while ($wpb_all_query->have_posts()) {
                            $wpb_all_query->the_post();
                            ?>

                            <a class="s-project-box" href="<?php the_permalink(); ?>">
                                <div class="s-project-photo" <?php if (has_post_thumbnail()) echo 'style="background-image: url(\'' . get_the_post_thumbnail_url() . '\')"'; ?>></div>
                                <div class="s-project-name"><?php the_title(); ?></div>
                                <span class="s-project-info"><?php echo get_the_excerpt(); ?></span>
                            </a>

                            <?php wp_reset_postdata(); ?>

                            <?php
                        }
                    }

                    wp_reset_postdata();
                    ?>

                </div>
            </div>
        </div>
    </div>

    <script>

        function intro_instruction() {
            $('.profile-popup-top').removeClass('opened').animate({bottom: '100%'}, 400, 'swing', () => {
                $('.profile-popup-top').css({opacity: 0});
            });
            $('.profile-popup-bottom').animate({top: '100%'}, 400);
            $('.profiles-s-section').removeClass('popup-opened');

            const intro = introJs().setOptions({
                nextLabel: 'Вперед',
                prevLabel: 'Назад',
                skipLabel: 'Выйти',
                doneLabel: 'Понятно',
                showStepNumbers: true,
                showBullets: false,
                exitOnOverlayClick: false,
                keyboardNavigation: false,
                steps: [
                    {
                        element: '.profiles-s-section',
                        intro: "<h4>Связь поколений</h4>Здесь отображаются участники Великой Отечественной Войны, которые имеют отношение к вашему учреждению.<br><br><i>Нажмите на любую карточку участника для продолжения...</i>",
                        position: 'right',
                        disableInteraction: false
                    },
                    {
                        element: '.profiles-s-section',
                        intro: "<h4>Карточка участника</h4>Здесь вы можете узнать более подробную информацию об интересующем вас участнике Великой Отечественной Войны.",
                        position: 'right',
                        disableInteraction: true
                    },
                    {
                        element: '.profiles-s-section .btn.more',
                        intro: "<h4>Подробнее</h4>По нажатии на эту кнопку откроется страница этого участника, где можно прочитать интересную статью о его жизни и заслугах.",
                        position: 'top',
                        disableInteraction: true
                    },
                    {
                        element: '.profiles-s-section .btn.share',
                        intro: "<h4>Поделиться</h4>По нажатии на эту кнопку откроется форма, с помощью которой вы сможете поделиться ссылкой на страницу участника в любых соц. сетях.",
                        position: 'top',
                        disableInteraction: true
                    },
                    {
                        element: '.profiles-s-section',
                        intro: "<h4>Закрыть карточку</h4>Чтобы закрыть карточку участика достаточно нажать в любую точку этой карточки, кроме кнопок, конечно.<br><br><i>Закройте карточку для продолжения...</i>",
                        position: 'right',
                        disableInteraction: false
                    },
                    {
                        element: '.s-map',
                        intro: "<h4>Карта нашей победы</h4>Здесь отображаются все места на карте, где прославились участники из блока Связь поколений. По нажатию на метку откроется карточка участника, который прославился в этом месте.",
                        position: 'top',
                        disableInteraction: true
                    },
                    {
                        element: '.projects-menu-window',
                        intro: "<h4>Истории наших побед</h4>Здесь отображаются статьи о победах участников Великой Отечественной Войны, статьи на другие интересные темы о Великой Отечественной Войне. Заходите сюда почаще, чтобы не пропускать свежие публикации.",
                        position: 'left',
                        disableInteraction: true
                    },
                ]
            });
            intro.start();

            $('.introjs-prevbutton, .introjs-helperNumberLayer, .introjs-nextbutton').hide();

            $('.introjs-nextbutton').on('click', (e) => {
                if ($('.introjs-helperNumberLayer').html() == '4' || $('.introjs-helperNumberLayer').html() == '6') {
                    $('.introjs-nextbutton').hide();
                }
            });
            $('.s-profile-box').on('mouseup', () => {
                intro.nextStep();
                $('.introjs-nextbutton').show();
            });
            $('.profile-popup-bottom, .profile-popup-top').on('mouseup', () => {
                intro.nextStep();
                $('.introjs-nextbutton').show();
            });

            $('.introjs-skipbutton').on('click', (e) => {
                $('.s-profile-box').off('mouseup');
                $('.profile-popup-bottom, .profile-popup-top').off('mouseup');
            });
        }

        function intro_instruction_mobile() {
            $('.profile-popup-top').removeClass('opened').animate({bottom: '100%'}, 400, 'swing', () => {
                $('.profile-popup-top').css({opacity: 0});
            });
            $('.profile-popup-bottom').animate({top: '100%'}, 400);
            $('.profiles-s-section').removeClass('popup-opened');

            document.querySelector('.s-profiles-list').scrollTop = 0;

            const intro = introJs().setOptions({
                nextLabel: 'Вперед',
                prevLabel: 'Назад',
                skipLabel: 'Выйти',
                doneLabel: 'Понятно',
                showStepNumbers: true,
                showBullets: false,
                exitOnOverlayClick: false,
                keyboardNavigation: false,
                steps: [
                    {
                        intro: "<h4>Связь поколений</h4>Здесь отображаются участники Великой Отечественной Войны, которые имеют отношение к ТФТЛ.",
                    },
                    {
                        element: document.querySelectorAll('.s-profile-box')[0],
                        intro: "<h4>Выберите карточку</h4><i>Нажмите на эту карточку участника для продолжения...</i>",
                        position: 'bottom',
                        disableInteraction: false
                    },
                    {
                        intro: "<h4>Карточка участника</h4>Здесь вы можете узнать более подробную информацию об интересующем вас участнике Великой Отечественной Войны.",
                    },
                    {
                        element: '.profiles-s-section .btn.more',
                        intro: "<h4>Подробнее</h4>По нажатии на эту кнопку откроется страница этого участника, где можно прочитать интересную статью о его жизни и заслугах.",
                        position: 'top',
                        disableInteraction: true
                    },
                    {
                        element: '.profiles-s-section .btn.share',
                        intro: "<h4>Поделиться</h4>По нажатии на эту кнопку откроется форма, с помощью которой вы сможете поделиться ссылкой на страницу участника в любых соц. сетях.",
                        position: 'top',
                        disableInteraction: true
                    },
                    {
                        intro: "<h4>Закрыть карточку</h4>Чтобы закрыть карточку участика достаточно нажать в любую точку этой карточки, кроме кнопок, конечно.<br><br><i>Закройте карточку для продолжения...</i>",
                    },
                    {
                        intro: "<h4>Ожидание</h4>",
                    },
                    {
                        element: 'header',
                        intro: "<h4>Откройте меню</h4><i>Откройте меню сайта для продолжения...</i>",
                        position: 'bottom',
                        disableInteraction: false
                    },
                    {
                        intro: "<h4>Карта нашей победы</h4><i>Зайдите в раздел Карта нашей победы для продолжения...</i>",
                    },
                    {
                        intro: "<h4>Ожидание</h4>",
                    },
                    {
                        intro: "<h4>Карта нашей победы</h4>Здесь отображаются все места на карте, где прославились участники из блока Связь поколений. По нажатию на метку откроется карточка участника, который прославился в этом месте.",
                    },
                    {
                        element: 'header',
                        intro: "<h4>Откройте меню</h4><i>Откройте меню сайта для продолжения...</i>",
                        position: 'bottom',
                        disableInteraction: false
                    },
                    {
                        intro: "<h4>Истории наших побед</h4><i>Зайдите в раздел Истории наших побед для продолжения...</i>",
                    },
                    {
                        intro: "<h4>Ожидание</h4>",
                    },
                    {
                        intro: "<h4>Истории наших побед</h4>Здесь отображаются статьи о победах участников Великой Отечественной Войны, статьи на другие интересные темы о Великой Отечественной Войне. Заходите сюда почаще, чтобы не пропускать свежие публикации.",
                    },
                ]
            });
            intro.start();

            $('.introjs-prevbutton, .introjs-helperNumberLayer').hide();
            $('.mml-instruction').hide();

            $('.introjs-nextbutton').on('click', (e) => {
                if ($('.introjs-helperNumberLayer').html() == '1') {
                    $('.introjs-nextbutton').hide();

                    $('.s-profile-box').on('mouseup', () => {
                        intro.nextStep();
                        $('.introjs-nextbutton').show();

                        $('.s-profile-box').off('mouseup');
                    });
                }
                if ($('.introjs-helperNumberLayer').html() == '6') {
                    $('.introjsFloatingElement, .introjs-overlay, .introjs-helperLayer, .introjs-tooltipReferenceLayer, .introjs-tooltip').hide();

                    $('.profile-popup-bottom, .profile-popup-top').on('mouseup', () => {
                        intro.nextStep();
                        $('.introjsFloatingElement, .introjs-overlay, .introjs-helperLayer, .introjs-tooltipReferenceLayer, .introjs-tooltip').show();
                        $('.introjs-nextbutton').hide();

                        $('.header-menu-btn').on('mouseup', () => {
                            intro.nextStep();
                            $('.introjs-nextbutton').show();

                            $('.header-menu-btn').off('mouseup');
                        });

                        $('.profile-popup-bottom, .profile-popup-top').off('mouseup');
                    });
                }
                if ($('.introjs-helperNumberLayer').html() == '9') {
                    $('.introjsFloatingElement, .introjs-overlay, .introjs-helperLayer, .introjs-tooltipReferenceLayer, .introjs-tooltip').css({visibility: 'hidden'});

                    $('.mobile-menu-link[data-name="map"]').on('mouseup', () => {
                        intro.nextStep();
                        $('.introjsFloatingElement, .introjs-overlay, .introjs-helperLayer, .introjs-tooltipReferenceLayer, .introjs-tooltip').css({visibility: 'visible'});

                        $('.mobile-menu-link[data-name="map"]').off('mouseup');
                    });
                }
                if ($('.introjs-helperNumberLayer').html() == '11') {
                    $('.introjs-nextbutton').hide();

                    $('.header-menu-btn').on('mouseup', () => {
                        intro.nextStep();
                        $('.introjs-nextbutton').show();

                        $('.header-menu-btn').off('mouseup');
                    });
                }
                if ($('.introjs-helperNumberLayer').html() == '13') {
                    $('.introjsFloatingElement, .introjs-overlay, .introjs-helperLayer, .introjs-tooltipReferenceLayer, .introjs-tooltip').css({visibility: 'hidden'});

                    $('.mobile-menu-link[data-name="projects"]').on('mouseup', () => {
                        intro.nextStep();
                        $('.introjsFloatingElement, .introjs-overlay, .introjs-helperLayer, .introjs-tooltipReferenceLayer, .introjs-tooltip').css({visibility: 'visible'});
                        $('.introjs-nextbutton').hide();

                        $('.mobile-menu-link[data-name="projects"]').off('mouseup');
                    });
                }
            });


            $('.introjs-skipbutton').on('click', (e) => {
                $('.s-profile-box').off('mouseup');
                $('.profile-popup-bottom, .profile-popup-top').off('mouseup');
                $('.header-menu-btn').off('mouseup');
                $('.mobile-menu-link[data-name="map"]').off('mouseup');
                $('.mobile-menu-link[data-name="projects"]').off('mouseup');

                $('.mml-instruction').show();
            });
        }

        $('.help-button').on('click', (e) => {
            intro_instruction();
        });

    </script>

<?php get_footer(); ?>