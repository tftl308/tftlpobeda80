<?php
require_once get_template_directory() . '/tgm/class-tgm-plugin-activation.php';

function hide_custom_fields_metabox() {
    global $pagenow;
    if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
        echo '<style>
            #postcustom { display: none; }
        </style>';
    }
}
add_action('admin_head', 'hide_custom_fields_metabox');

function my_theme_register_required_plugins()
{
    $plugins = array(
        array(
            'name' => 'All-in-One WP Migration',
            'slug' => 'all-in-one-wp-migration',
            'source' => get_template_directory() . '/plugins/all-in-one-wp-migration.zip',
            'required' => true,
        ),
        array(
            'name' => 'All-in-One WP Migration Unlimited Extension',
            'slug' => 'all-in-one-wp-migration-unlimited-extension',
            'source' => get_template_directory() . '/plugins/all-in-one-wp-migration-unlimited-extension.zip',
            'required' => true,
        ),
        array(
            'name' => 'Embed Any Document',
            'slug' => 'embed-any-document',
            'source' => get_template_directory() . '/plugins/embed-any-document.zip',
            'required' => true,
        ),
        array(
            'name' => 'Simple Spoiler',
            'slug' => 'simple-spoiler',
            'source' => get_template_directory() . '/plugins/simple-spoiler.zip',
            'required' => true,
        ),
        array(
            'name' => 'Сaptcha BWS',
            'slug' => 'captcha-bws',
            'source' => get_template_directory() . '/plugins/captcha-bws.zip',
            'required' => true,
        ),
    );

    tgmpa($plugins);
}

add_action('tgmpa_register', 'my_theme_register_required_plugins');

function pobeda_enqueue_scripts()
{
    wp_enqueue_script('main-jquery', get_template_directory_uri() . '/js/jquery-3.4.1.min.js');
    wp_enqueue_script('sharerjs', get_template_directory_uri() . '/js/sharer.min.js');
    wp_enqueue_script('viewerjs', get_template_directory_uri() . '/js/viewer.min.js');
    wp_enqueue_script('ymaps', 'https://api-maps.yandex.ru/2.1/?apikey=b2488fce-2b8e-4fb9-a831-7b31090884ef&lang=ru_RU');

    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/css/all.min.css');
    wp_enqueue_style('main-style', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_style('viewerjs-css', get_template_directory_uri() . '/css/viewer.min.css');
}

add_action('wp_enqueue_scripts', 'pobeda_enqueue_scripts');

function pobeda_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'pobeda_setup');

function pobeda_acf_init()
{
    acf_update_setting('google_api_key', 'AIzaSyC_GdRuEaGw_9teeHB3dfdUvtk0fKCcsR8');
}

add_action('acf/init', 'pobeda_acf_init');

add_filter('jpeg_quality', function () {
    return 100;
});

function create_custom_post_type()
{
    register_post_type('zayavka',
        array(
            'labels' => array(
                'name' => __('Заявки'),
                'singular_name' => __('Заявка')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields', 'publicize'),
            'menu_icon' => 'dashicons-forms',
            'rewrite' => array('slug' => 'person'),
        )
    );
}

add_action('init', 'create_custom_post_type');



add_action('wp_ajax_submit_application', 'submit_application');
add_action('wp_ajax_nopriv_submit_application', 'submit_application');

function submit_application()
{
    if (function_exists('bws_captcha_check')) {
        $captcha_result = isset($_POST['cptch_result']) ? sanitize_text_field($_POST['cptch_result']) : '';

        if (empty($captcha_result) || !bws_captcha_check()) {
            wp_send_json_error(['message' => 'Ошибка: неверная капча!']);
            wp_die();
        }
    }

    $fio = sanitize_text_field($_POST['fio']);
    $attitude = sanitize_text_field($_POST['attitude']);
    $years = sanitize_text_field($_POST['years']);
    $rank = sanitize_text_field($_POST['rank']);
    $position = sanitize_text_field($_POST['position']);
    $label = sanitize_textarea_field($_POST['label']);
    $link = sanitize_text_field($_POST['link']);
    $awards = sanitize_textarea_field($_POST['awards']);
    $history = sanitize_textarea_field($_POST['history']);
    $coords = sanitize_text_field($_POST['coords']);
    $descriptionCoords = sanitize_text_field($_POST['descriptionCoords']);
    $publish_status = sanitize_text_field($_POST['publish_status']);
    $selected_photo = isset($_POST['selected_photo']) ? esc_url_raw($_POST['selected_photo']) : '';

    $post_status = ($publish_status === 'publish') ? 'publish' : 'draft';

    $post_data = array(
        'post_title' => $fio,
        'post_content' => $history,
        'post_status' => $post_status,
        'post_type' => 'zayavka',
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['message' => 'Ошибка при создании заявки.']);
    }

    update_post_meta($post_id, 'fio', $fio);
    update_post_meta($post_id, 'attitude', $attitude);
    update_post_meta($post_id, 'years', $years);
    update_post_meta($post_id, 'rank', $rank);
    update_post_meta($post_id, 'position', $position);
    update_post_meta($post_id, 'label', $label);
    update_post_meta($post_id, 'link', $link);
    update_post_meta($post_id, 'history', $history);
    update_post_meta($post_id, 'awards', $awards);
    update_post_meta($post_id, 'coords', $coords);
    update_post_meta($post_id, 'descriptionCoords', $descriptionCoords);
    update_post_meta($post_id, '_publish_status', $post_status);

    if (!empty($_FILES['photo'])) {
        $photo = $_FILES['photo'];
        if ($photo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = wp_upload_dir();
            $target_file = $upload_dir['path'] . '/' . basename($photo['name']);
            move_uploaded_file($photo['tmp_name'], $target_file);

            $file_url = $upload_dir['url'] . '/' . basename($photo['name']);

            update_post_meta($post_id, 'photo', $file_url);
        }
    }

    if (!empty($selected_photo)) {
        update_post_meta($post_id, 'photo', $selected_photo);
    }

    if (!empty($_FILES['photos']['name'])) {
        $gallery_images = [];
        $files = $_FILES['photos'];

        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $upload_dir = wp_upload_dir();
                $target_file = $upload_dir['path'] . '/' . basename($name);
                move_uploaded_file($files['tmp_name'][$key], $target_file);

                $file_url = $upload_dir['url'] . '/' . basename($name);
                $gallery_images[] = $file_url;
            }
        }

        if (!empty($gallery_images)) {
            update_post_meta($post_id, 'photos', $gallery_images);
        }
    }

    wp_send_json_success(['message' => 'Заявка успешно отправлена!']);
}

add_filter('manage_edit-zayavka_columns', 'set_custom_edit_zayavka_columns');

function render_custom_meta_box($post)
{
    $value = get_post_status($post->ID);
    ?>
    <label for="publish_status"><?php _e('Опубликовать/Скрыть', 'your_text_domain'); ?></label>
    <select name="publish_status" id="publish_status">
        <option value="publish" <?php selected($value, 'publish'); ?>><?php _e('Опубликовать', 'your_text_domain'); ?></option>
        <option value="draft" <?php selected($value, 'draft'); ?>><?php _e('Черновик', 'your_text_domain'); ?></option>
    </select>
    <?php
}

function add_custom_meta_box()
{
    add_meta_box(
        'custom_publish_meta_box',
        __('Статус публикации', 'your_text_domain'),
        'render_custom_meta_box',
        'zayavka',
        'side'
    );
}

add_action('add_meta_boxes', 'add_custom_meta_box');

function save_custom_meta_box_data($post_id)
{
    if (array_key_exists('publish_status', $_POST)) {
        $status = sanitize_text_field($_POST['publish_status']);

        update_post_meta($post_id, '_publish_status', $status);

        $post_data = array(
            'ID' => $post_id,
            'post_status' => ($status === 'publish') ? 'publish' : 'draft',
        );
        remove_action('save_post', 'save_custom_meta_box_data');
        wp_update_post($post_data);
        add_action('save_post', 'save_custom_meta_box_data');
    }
}

add_action('save_post', 'save_custom_meta_box_data');

function set_custom_edit_zayavka_columns($columns)
{
    $columns['attitude'] = __('Отношение к учереждению', 'your_text_domain');
    $columns['years'] = __('Годы', 'your_text_domain');
    $columns['rank'] = __('Ранг', 'your_text_domain');
    $columns['position'] = __('Должность', 'your_text_domain');
    $columns['link'] = __('Ссылка на карточку в Памяти народа', 'your_text_domain');
    $columns['awards'] = __('Награды', 'your_text_domain');
    $columns['publish_status'] = __('Статус', 'your_text_domain');
    return $columns;
}

add_action('manage_zayavka_posts_custom_column', 'custom_zayavka_column', 10, 2);

function custom_zayavka_column($column, $post_id)
{
    switch ($column) {
        case 'attitude':
            echo esc_html(get_post_meta($post_id, 'attitude', true));
            break;

        case 'years':
            echo esc_html(get_post_meta($post_id, 'years', true));
            break;

        case 'rank':
            echo esc_html(get_post_meta($post_id, 'rank', true));
            break;

        case 'position':
            echo esc_html(get_post_meta($post_id, 'position', true));
            break;
        case 'link':
            echo esc_html(get_post_meta($post_id, 'link', true));
            break;
        case 'awards':
            echo esc_html(get_post_meta($post_id, 'awards', true));
            break;
        case 'publish_status':
            $status = get_post_status($post_id);
            $status_label = ($status === 'publish') ? 'Опубликовано' : 'Черновик';
            echo '<span class="toggle-status" data-post-id="' . $post_id . '" style="cursor: pointer; color: #0073aa; text-decoration: underline;">' . esc_html($status_label) . '</span>';
            break;
    }
}

add_action('wp_ajax_toggle_post_status', 'toggle_post_status');

function enqueue_admin_scripts($hook)
{
    if ($hook !== 'edit.php') return;

    wp_enqueue_script(
        'custom-admin-script',
        get_template_directory_uri() . '/js/admin-toggle-status.js',
        array('jquery'),
        null,
        true
    );

    wp_localize_script('custom-admin-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

function toggle_post_status()
{
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => 'Недостаточно прав']);
    }

    if (!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
        wp_send_json_error(['message' => 'Некорректный ID поста']);
    }

    $post_id = intval($_POST['post_id']);
    $current_status = get_post_status($post_id);

    $new_status = ($current_status === 'publish') ? 'draft' : 'publish';

    $updated_post = array(
        'ID' => $post_id,
        'post_status' => $new_status,
    );

    wp_update_post($updated_post);

    update_post_meta($post_id, '_publish_status', $new_status);

    wp_send_json_success([
        'new_status' => $new_status,
        'status_label' => ($new_status === 'publish') ? 'Опубликовано' : 'Черновик'
    ]);
}

function add_custom_meta_boxes()
{
    add_meta_box(
        'custom_post_meta_box',
        __('Данные заявки', 'your_text_domain'),
        'render_custom_meta_boxs',
        'zayavka',
        'normal',
        'default'
    );
}

add_action('add_meta_boxes', 'add_custom_meta_boxes');

function render_custom_meta_boxs($post)
{
    $attitude = get_post_meta($post->ID, 'attitude', true);
    $photo_url = get_post_meta($post->ID, 'photo', true);
    $gallery_images = get_post_meta($post->ID, 'photos', true);
    $years = get_post_meta($post->ID, 'years', true);
    $rank = get_post_meta($post->ID, 'rank', true);
    $position = get_post_meta($post->ID, 'position', true);
    $label = get_post_meta($post->ID, 'label', true);
    $link = get_post_meta($post->ID, 'link', true);
    $awards = get_post_meta($post->ID, 'awards', true);
    $coords = get_post_meta($post->ID, 'coords', true);
    $descriptionCoords = get_post_meta($post->ID, 'descriptionCoords', true);

    $publish_status = get_post_meta($post->ID, '_publish_status', true);


    ?>
    <p>
        <label for="attitude"><?php _e('Отношение к учреждению', 'your_text_domain'); ?></label>
        <input type="text" name="attitude" id="attitude" value="<?php echo esc_attr($attitude); ?>" class="widefat">
    </p>
    <p>
        <label for="photo"><?php _e('Фото', 'your_text_domain'); ?></label><br>
        <input type="text" name="photo" id="photo" value="<?php echo esc_url($photo_url); ?>" style="width: 100%;"
               class="regular-text">
        <button style="margin-top: 20px" type="button"
                class="upload_image_button button"><?php _e('Выбрать изображение', 'your_text_domain'); ?></button>
        <?php if ($photo_url): ?>
            <br><img src="<?php echo esc_url($photo_url); ?>" alt="Фото" style="max-width: 200px; margin-top: 10px;">
        <?php endif; ?>
    </p>
    <p>
        <label for="years"><?php _e('Годы', 'your_text_domain'); ?></label>
        <input type="text" name="years" id="years" value="<?php echo esc_attr($years); ?>" class="widefat">
    </p>
    <p>
        <label for="rank"><?php _e('Ранг', 'your_text_domain'); ?></label>
        <input type="text" name="rank" id="rank" value="<?php echo esc_attr($rank); ?>" class="widefat">
    </p>
    <p>
        <label for="position"><?php _e('Должность', 'your_text_domain'); ?></label>
        <input type="text" name="position" id="position" value="<?php echo esc_attr($position); ?>" class="widefat">
    </p>
    <p>
        <label for="label"><?php _e('Метка', 'your_text_domain'); ?></label>
        <input type="text" name="label" id="label" value="<?php echo esc_attr($label); ?>" class="widefat">
    </p>
    <p>
        <label for="link"><?php _e('Ссылка на карточку в Памяти народа', 'your_text_domain'); ?></label>
        <input type="text" name="link" id="link" value="<?php echo esc_attr($link); ?>" class="widefat">
    </p>
    <p>
        <label for="awards"><?php _e('Награды', 'your_text_domain'); ?></label>
        <textarea name="awards" id="awards" class="widefat"><?php echo esc_textarea($awards); ?></textarea>
    </p>
    <p>
        <label for="coords"><?php _e('Координаты', 'your_text_domain'); ?></label>
        <input type="text" name="coords" id="coords" value="<?php echo esc_attr($coords); ?>" class="widefat">
    </p>
    <p>
        <label for="coords"><?php _e('Описание координаты', 'your_text_domain'); ?></label>
        <textarea name="descriptionCoords" id="descriptionCoords" value="<?php echo esc_attr($descriptionCoords); ?>" class="widefat"><?php echo esc_attr($descriptionCoords); ?></textarea>
    </p>
    <p>
    <label for="photos"><?php _e('Галерея изображений', 'your_text_domain'); ?></label><br>
    <input type="text" name="photos" id="photos" value="<?php echo esc_attr(implode(',', (array)$gallery_images)); ?>"
           style="width: 100%;" class="regular-text">
    <button style="margin-top: 20px" type="button"
            class="upload_gallery_button button"><?php _e('Выбрать изображения', 'your_text_domain'); ?></button>

    <?php if (!empty($gallery_images)): ?>
    <div class="gallery-preview" style="margin-top: 10px;">
        <?php foreach ($gallery_images as $image_url): ?>
            <img src="<?php echo esc_url($image_url); ?>" alt="Галерея"
                 style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;">
        <?php endforeach; ?>
    </div>
<?php endif; ?>
    </p>

    <p>
        <label for="publish_status"><?php _e('Статус публикации', 'your_text_domain'); ?></label>
        <select name="publish_status" id="publish_status">
            <option value="publish" <?php selected($publish_status, 'publish'); ?>><?php _e('Опубликовано', 'your_text_domain'); ?></option>
            <option value="draft" <?php selected($publish_status, 'draft'); ?>><?php _e('Черновик', 'your_text_domain'); ?></option>
        </select>
    </p>
    <?php
}

function enqueue_media_uploader()
{
    wp_enqueue_script('jquery');

    wp_enqueue_media();

    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            $(".upload_image_button").click(function(e) {
                e.preventDefault();

                var imageUploader = wp.media({
                    title: "Выбрать изображение",
                    button: {
                        text: "Выбрать"
                    },
                    multiple: false,
                    library: { type: "image" },
                    create: false
                })
                .on("select", function() {
                    var attachment = imageUploader.state().get("selection").first().toJSON();
                    $("#photo").val(attachment.url);
                    $("img").attr("src", attachment.url).show();
                })
                .open();
            });
        });
    ');
}

add_action('admin_enqueue_scripts', 'enqueue_media_uploader');

function add_custom_gallery_meta_box()
{
    add_meta_box(
        'custom_gallery_meta_box',
        __('Галерея изображений', 'your_text_domain'),
        'render_gallery_meta_box',
        'post',
        'normal',
        'low'
    );
}

add_action('add_meta_boxes', 'add_custom_gallery_meta_box');

function render_gallery_meta_box($post)
{
    $gallery_images = get_post_meta($post->ID, 'photos', true);
    $gallery_images = is_array($gallery_images) ? $gallery_images : [];
    ?>
    <label for="photos"><?php _e('Выберите изображения для галереи', 'your_text_domain'); ?></label><br>
    <input type="text" name="photos" id="photos" value="<?php echo esc_attr(implode(",", $gallery_images)); ?>"
           style="width: 100%;" class="regular-text">
    <button class="upload_gallery_button button"><?php _e('Выбрать изображения', 'your_text_domain'); ?></button>

    <div class="gallery-preview" style="margin-top: 10px;">
        <?php
        if (!empty($gallery_images)) {
            foreach ($gallery_images as $image_url) {
                echo "<img src='" . esc_url($image_url) . "' class='gallery-image-preview' style='max-width: 200px; margin-right: 10px; margin-bottom: 10px;' />";
            }
        }
        ?>
    </div>
    <?php
}

function save_gallery_meta_box_data($post_id)
{
    if (isset($_POST['photos'])) {
        $image_urls = array_map('sanitize_text_field', explode(',', sanitize_text_field($_POST['photos'])));

        if (!empty($image_urls)) {
            update_post_meta($post_id, 'photos', $image_urls);
        } else {
            delete_post_meta($post_id, 'photos');
        }
    }
}

add_action('save_post', 'save_gallery_meta_box_data');


function enqueue_gallery_uploader()
{
    wp_enqueue_script('jquery');
    wp_enqueue_media();

    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            $(".upload_gallery_button").click(function(e) {
                e.preventDefault();

                var imageUploader = wp.media({
                    title: "Выбрать изображения",
                    button: {
                        text: "Выбрать"
                    },
                    multiple: true,
                    library: { type: "image" }
                })
                .on("select", function() {
                    var attachments = imageUploader.state().get("selection").toJSON();
                    var imageUrls = [];

                    attachments.forEach(function(attachment) {
                        imageUrls.push(attachment.url);
                    });

                    $("#photos").val(imageUrls.join(","));
                    
                    $(".gallery-preview").empty();
                    imageUrls.forEach(function(url) {
                        $(".gallery-preview").append("<img src=\'" + url + "\' class=\'gallery-image-preview\' />");
                    });
                })
                .open();
            });
        });
    ');
}

add_action('admin_enqueue_scripts', 'enqueue_gallery_uploader');


function save_custom_meta_boxs_data($post_id)
{
    if (!isset($_POST['attitude']) || !isset($_POST['years']) || !isset($_POST['rank']) || !isset($_POST['position']) || !isset($_POST['label']) || !isset($_POST['link']) || !isset($_POST['awards']) || !isset($_POST['coords']) || !isset($_POST['publish_status'])) {
        return;
    }

    if (isset($_POST['photo'])) {
        $photo_url = sanitize_text_field($_POST['photo']);
        update_post_meta($post_id, 'photo', $photo_url);
    }

    update_post_meta($post_id, 'attitude', sanitize_text_field($_POST['attitude']));
    update_post_meta($post_id, 'years', sanitize_text_field($_POST['years']));
    update_post_meta($post_id, 'rank', sanitize_text_field($_POST['rank']));
    update_post_meta($post_id, 'position', sanitize_text_field($_POST['position']));
    update_post_meta($post_id, 'label', sanitize_text_field($_POST['label']));
    update_post_meta($post_id, 'link', sanitize_text_field($_POST['link']));
    update_post_meta($post_id, 'awards', sanitize_textarea_field($_POST['awards']));
    update_post_meta($post_id, 'coords', sanitize_text_field($_POST['coords']));
    update_post_meta($post_id, '_publish_status', sanitize_text_field($_POST['publish_status']));
}

add_action('save_post', 'save_custom_meta_boxs_data');

function mytheme_create_form_page()
{
    $page_title = 'Страница формы';
    $page_slug = 'form';
    $page_check = get_page_by_path($page_slug);

    if (!isset($page_check->ID)) {
        $page = array(
            'post_title' => $page_title,
            'post_content' => 'Это страница с формой.',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1,
            'post_name' => $page_slug,
            'page_template' => 'form.php'
        );

        $new_page_id = wp_insert_post($page);

        if (!is_wp_error($new_page_id)) {
            update_post_meta($new_page_id, '_wp_page_template', 'form.php');
        }
    }
}

add_action('after_switch_theme', 'mytheme_create_form_page');

function set_custom_permalink_structure()
{
    if (get_option('permalink_structure') != '/%postname%/') {
        update_option('permalink_structure', '/%postname%/');
    }
}

add_action('after_switch_theme', 'set_custom_permalink_structure');

function my_theme_customize_register($wp_customize)
{
    $wp_customize->add_section('my_custom_section', array(
        'title' => __('Настройки сайта', 'textdomain'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('custom_logo', array(
        'default' => get_template_directory_uri() . '/img/logo.jpg',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'custom_logo', array(
        'label' => __('Логотип', 'textdomain'),
        'section' => 'my_custom_section',
        'settings' => 'custom_logo',
    )));

    $wp_customize->add_setting('important_remember', array(
        'default' => 'Как важно помнить<br>тех, кому мы обязаны жизнью...<br>Как важно знать<br>тех, кто подарил нам Победу!',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('important_remember', array(
        'label' => __('Информация о проекте', 'textdomain'),
        'section' => 'my_custom_section',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('project_info', array(
        'default' => '© Проект Томского физико-технического лицея<br>Если вы нашли ошибку или хотите связаться с модераторами, пишите на адрес - tftl308@ya.ru',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('project_info', array(
        'label' => __('Подвал', 'textdomain'),
        'section' => 'my_custom_section',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('additional_setting', array(
        'default' => '© Проект ТФТЛ <br>Связь с модерацией - tftl308@ya.ru',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('additional_setting', array(
        'label' => __('Подвал (сокращенный)', 'textdomain'),
        'section' => 'my_custom_section',
        'type' => 'textarea',
    ));
}

add_action('customize_register', 'my_theme_customize_register');
