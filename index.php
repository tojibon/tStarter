<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package trstarter
 */

get_header(); ?>
<div class="row">
	<div class="col-md-8 col-sm-12">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php if (have_posts()) : ?>

					<?php if (is_home() && !is_front_page()) : ?>
						<header>
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
					<?php endif; ?>

					<?php while (have_posts()) : the_post(); ?>

						<?php
						get_template_part('partials/content', get_post_format());
						?>

					<?php endwhile; ?>

					<?php the_posts_navigation(); ?>

				<?php else : ?>

					<?php get_template_part('partials/content', 'none'); ?>

				<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>
	<div class="col-md-4 col-sm-12 sidebar-wrap">
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>
