<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package trstarter
 */

get_header(); ?>
<div class="row">
    <div class="col-md-9 col-sm-12">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php if (have_posts()) : ?>

                    <header class="page-header">
                        <?php
                        the_archive_title('<h1 class="page-title">', '</h1>');
                        the_archive_description('<div class="taxonomy-description">', '</div>');
                        ?>
                    </header>

                    <?php while (have_posts()) : the_post(); ?>

                        <?php
                        get_template_part('partials/content', get_post_format());
                        ?>

                    <?php endwhile; ?>

                    <?php the_posts_navigation(); ?>

                <?php else : ?>

                    <?php get_template_part('partials/content', 'none'); ?>

                <?php endif; ?>

            </main>
        </div>
    </div>
    <div class="col-md-3 col-sm-12 sidebar-wrap">
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>
