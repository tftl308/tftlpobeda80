<?php
$footer = get_theme_mod('project_info', '© Проект учителя Томского физико-технического лицея<br>Если вы нашли ошибку или хотите связаться с модераторами, пишите на адрес - tftlpobeda80@yandex.ru');
$footer_min = get_theme_mod('additional_setting', '© Проект учителя ТФТЛ <br>Связь с модерацией - tftlpobeda80@yandex.ru');

?>

            <footer>
                <div class="desktop-footer">
                    <?php echo wp_kses_post($footer); ?>
                </div>
                <div class="mobile-footer">
                    <?php echo wp_kses_post($footer_min); ?>
                </div>
            </footer>
        </div>
    </section>

            <div class="share-dialog-darker"></div>
            <div class="share-dialog-box">
                <div class="sdb-header">
                    <div class="sdb-header-title">Поделиться</div>
                    <div class="sdb-header-close-btn" onclick="share_close();"><i class="fal fa-times"></i></div>
                </div>
                <div class="sdb-content">
                    <div class="sdb-content-box">
                        <div class="sdb-content-box-title">Прямая ссылка</div>
                        <div class="sdb-link-sharer-box">
                            <input type="text" class="sdb-link-sharer-input" onfocus="this.select()" readonly value="http://tftl75.2i.tusur.ru/?page_id=24&profile_id=5eac041f367f4">
                            <div class="sdb-link-sharer-btn" onclick="share_copy();"><div class="fa-wrap"><i class="far fa-copy"></i></div><span>Скопировать</span></div>
                        </div>
                    </div>
                    <div class="sdb-content-box">
                        <div class="sdb-content-box-title">Социальные сети</div>
                        <div class="sdb-soc-list">
                            <div class="sdb-soc-box" data-sharer="vk">
                                <div class="sdb-soc-box-logo vk"><i class="fab fa-vk"></i></div>
                                <div class="sdb-soc-box-title">ВКонтакте</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="okru">
                                <div class="sdb-soc-box-logo ok"><i class="fab fa-odnoklassniki"></i></div>
                                <div class="sdb-soc-box-title">ОК.ру</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="twitter">
                                <div class="sdb-soc-box-logo twitter"><i class="fab fa-twitter"></i></div>
                                <div class="sdb-soc-box-title">Twitter</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="facebook">
                                <div class="sdb-soc-box-logo fb"><i class="fab fa-facebook-f"></i></div>
                                <div class="sdb-soc-box-title">Facebook</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="whatsapp">
                                <div class="sdb-soc-box-logo wa"><i class="fab fa-whatsapp"></i></div>
                                <div class="sdb-soc-box-title">WhatsApp</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="telegram">
                                <div class="sdb-soc-box-logo tg"><i class="fab fa-telegram-plane"></i></div>
                                <div class="sdb-soc-box-title">Telegram</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="viber">
                                <div class="sdb-soc-box-logo vr"><i class="fab fa-viber"></i></div>
                                <div class="sdb-soc-box-title">Viber</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="pinterest">
                                <div class="sdb-soc-box-logo pt"><i class="fab fa-pinterest-p"></i></div>
                                <div class="sdb-soc-box-title">Pinterest</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="tumblr">
                                <div class="sdb-soc-box-logo tr"><i class="fab fa-tumblr"></i></div>
                                <div class="sdb-soc-box-title">Tumblr</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="skype">
                                <div class="sdb-soc-box-logo se"><i class="fab fa-skype"></i></div>
                                <div class="sdb-soc-box-title">Skype</div>
                            </div>
                            <div class="sdb-soc-box" data-sharer="email">
                                <div class="sdb-soc-box-logo email"><i class="fas fa-envelope"></i></div>
                                <div class="sdb-soc-box-title">E-mail</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function share_copy() {
                    document.querySelector('.sdb-link-sharer-input').focus();
                    document.querySelector('.sdb-link-sharer-input').select();
                    document.execCommand('copy');

                    $('.sdb-link-sharer-btn > span').html('Скопировано!');
                    $('.sdb-link-sharer-btn i').removeClass('fa-copy').addClass('fa-check');
                    clearTimeout(this.share_copy_timeout);
                    this.share_copy_timeout = setTimeout(() => {
                        $('.sdb-link-sharer-btn > span').html('Скопировать');
                        $('.sdb-link-sharer-btn i').addClass('fa-copy').removeClass('fa-check');
                    }, 1000);
                }

                function share_close() {
                    $('.share-dialog-box').css({opacity: 0, transform: 'translate(-50%, -50%) translateY(100px)', transitionTimingFunction: 'ease-in'});
                    $('.share-dialog-darker').css({opacity: 0});
                    setTimeout(() => {
                        $('.share-dialog-box, .share-dialog-darker').css({display: 'none'});
                    }, 200);
                }

                function share_dialog(url, fio) {
                    $('.share-dialog-box, .share-dialog-darker').css({display: 'block'});
                    setTimeout(() => {
                        $('.share-dialog-box').css({opacity: 1, transform: 'translate(-50%, -50%) translateY(0)', transitionTimingFunction: 'ease-out'});
                        $('.share-dialog-darker').css({opacity: .7});
                    }, 50);


                    const text = 'Я помню, я горжусь!';
                    const title = fio + ' - 80 лет победы ТФТЛ';
                    const hashtags = ['80летпобеды', 'деньпобеды', '9мая'];
                    const hashtags_w = ['#80летпобеды', '#деньпобеды', '#9мая'];

                    $('.sdb-link-sharer-input').val(url);
                    $('.sdb-soc-box').attr('data-url', url);

                    $('.sdb-soc-box[data-sharer="vk"]').attr({
                        'data-title': title,
                        'data-caption': text + ' ' + hashtags_w.join(' ')
                    });
                    $('.sdb-soc-box[data-sharer="okru"]').attr({
                        'data-title': text + ' ' + hashtags_w.join(' ')
                    });
                    $('.sdb-soc-box[data-sharer="twitter"]').attr({
                        'data-title': text,
                        'data-hashtags': hashtags.join(',')
                    });
                    $('.sdb-soc-box[data-sharer="facebook"]').attr({
                        'data-hashtag': hashtags[0]
                    });
                    $('.sdb-soc-box[data-sharer="whatsapp"]').attr({
                        'data-title': text
                    });
                    $('.sdb-soc-box[data-sharer="telegram"]').attr({
                        'data-title': text
                    });
                    $('.sdb-soc-box[data-sharer="viber"]').attr({
                        'data-title': text
                    });
                    $('.sdb-soc-box[data-sharer="pinterest"]').attr({
                        'data-description': text + ' ' + hashtags_w.join(' ')
                    });
                    $('.sdb-soc-box[data-sharer="tumblr"]').attr({
                        'data-title': title,
                        'data-caption': text,
                        'data-tags': hashtags.join(',')
                    });
                    $('.sdb-soc-box[data-sharer="skype"]').attr({
                        'data-title': text
                    });
                    $('.sdb-soc-box[data-sharer="email"]').attr({
                        'data-title': text,
                        'data-subject': title
                    });
                }
            </script>

    <?php wp_footer(); ?>

</body>
</html>
