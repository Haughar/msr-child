<?php
/**
 * Template Name: No Sidebar
 *
 * Login Page Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	
	<?php 
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it. ?>
		
		<?php $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'msr-post-hero', true );  

		if( $image_url && isset ( $image_url[0] ) && $image_url[1] >= 1000 ) {
		?>
		
			<div class="article-featured-image" style="background-image: url('<?php echo $image_url[0]; ?>');">
				<div class="featured-overlay"></div>
			</div>
			
		<?php } ?>	
	<?php } ?>
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
	
		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || get_comments_number() ) : ?>
			<div class="article-comments">
				<?php comments_template(); ?>
			</div>
		<?php endif; ?>
	
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
