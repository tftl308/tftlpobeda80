<?php get_header('single'); ?>

<style>
    body {
        height: initial;
    }

    .main-section {
        height: initial;
        min-height: 100vh;
    }

    .main-wrapper {
        height: initial;
        min-height: calc(100vh - 160px);
    }

    body.customize-support .main-wrapper {
        min-height: calc(100vh - 192px);
    }

    @media (max-width: 782px), (max-height: 610px) {

        .main-wrapper {
            min-height: calc(100vh - 55px);
        }

        body.customize-support .main-wrapper {
            min-height: calc(100vh - 101px);
        }

    }

    .project-content-wrapper {
        flex: initial;
    }

    .project-content {
        position: initial;
        top: initial;
        left: initial;
        width: initial;
        height: initial;
        max-height: initial;
        overflow: initial;
    }
</style>

<a class="header-home-btn" href="<?php echo home_url(); ?>" onclick="$(this).children('span, i').css({transform: 'translateX(-200px)'});">
    <i class="far fa-arrow-left"></i>
    <span>На главную</span>
</a>

<div class="project-wrapper">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <div class="project-title">
            <div class="project-title-bg" <?php if(has_post_thumbnail()) echo 'style="background-image: url(\''.get_the_post_thumbnail_url().'\')"'; ?>></div>
            <div class="project-title-name"><?php the_title(); ?></div>
        </div>
        <div class="project-content-wrapper">
            <div class="project-content content">
                <?php the_content(); ?>
            </div>
        </div>

    <?php endwhile; endif; ?>
</div>

    <script>
        // fix images
        $('.content img').each((index, elem) => {
            $(elem).attr('src', $(elem).attr('srcset').split(', ')[$(elem).attr('srcset').split(', ').length-1].split(' ')[0]);
        });

        $('.content img').not('.blocks-gallery-grid img').each((index, elem) => {
            new Viewer(elem, {navbar: false});
            $(elem).css({cursor: 'pointer'});
        });
        $('.blocks-gallery-grid').each((index, elem) => {
            new Viewer(elem);
            $(elem).find('img').css({cursor: 'pointer'});
        });
    </script>

<?php get_footer(); ?>