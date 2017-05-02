<?php
/**
 * Template Name: User Profile
 *
 * User Profile Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */

$user_id = get_current_user_id();

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	
	<div class="article-body clearfix  flex-content">
			
		
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->
			<?php get_template_part( 'content', 'page' ); ?>
			</main><!-- #main -->
		</div><!-- #primary -->
		
	</div>
	<?php get_fundraiser_list($user_id); ?>

<?php endwhile; // end of the loop. ?>


	

<?php get_footer(); ?>
