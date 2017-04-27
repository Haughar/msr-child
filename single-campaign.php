<?php
/**
 * Template Name: Full Page
  *
 * @author Michael Nguyen
 * @since 1.0.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post();
	global $post_id; ?>
	
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
			
		
		<div id="primary" class="content-area-full">
			<main id="main" class="site-main" role="main">
<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
				<p><?php echo wpautop(get_post_meta($id, 'campaign-start', true)); ?> to <?php echo wpautop(get_post_meta($id, 'campaign-end', true)); ?></p>
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
	
<?php endwhile; // end of the loop. 

$args = array(
    'post_type' => 'fundraiser',
    'meta_key' => 'fundraiser-campaign',
    'meta_value' => $post_id
);

$post_query = new WP_Query($args);
if($post_query->have_posts() ) {
  while($post_query->have_posts() ) {
    $post_query->the_post();
    $post = get_post();
    $id = $post->ID; ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('post-grid'); ?>>
	
		<?php if ( has_post_thumbnail() ) { 
		/** Normal container for posts with thumbnail */ ?>
		
		<?php $image_thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'msr-post-grid-thumb');?>
		
		<a class="entry-thumb" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" style="background-image:url( <?php echo $image_thumb_src[0]; ?> );">
			<div class="entry-thumb-icon"></div>
		</a>

		<div class="entry-text-content">
			
		<?php } else {
		/** Added class for adjusting post size without thumbnail */ ?>
		
		<div class="entry-text-content no-thumb">
		
		<?php } ?>
			
			<header class="entry-header">
				<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		
				<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php the_time('M j, Y') ?> 
				</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->
		
			<?php if(has_tag('feature', null)){ ?>
				<div class="feature-content"><span>Feature</span></div>
			<?php } ?>

			<div class="entry-content">
				<?php echo the_excerpt(); ?>
			</div><!-- .entry-content -->
		</div>
		<div class="entry-more">
			
			<?php if ( has_post_format( 'video' )) { ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"> Watch Video</a>
			<?php } else { ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo __('Read More', 'msr') ?></a>
			<?php } ?>
		</div>
		
	</article><!-- #post-## -->

<?php  }
}
?>

<?php get_footer(); ?>
