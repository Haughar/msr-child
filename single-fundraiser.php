<?php

if (isset($_SERVER['HTTP_REFERER'])) {
	console_log($_SERVER['HTTP_REFERER']);
}

get_header(); ?>

<main id="main">

<?php if (have_posts()) : while (have_posts()) : the_post();
	$post_id = $post->ID; 

	if ($post->post_author == get_current_user_id()) { ?>
		<button onclick="window.location.href='<?php echo home_url() . '/edit-fundraiser?post_id=' . $post_id ?>'">Manage</button>
	<?php } ?>
	

<div class="left">

	<?php if ( has_post_thumbnail() ) {
		the_post_thumbnail( array(500,500) );
	}?>

	<h2>Story</h2>
	<p><?php the_content(); ?></p>

	<h2>Updates</h2>
	<p>Not sure what this is going to be?</p>

	<h2>Comments(<?php comments_number('0', '1', '%'); ?>)</h2>
	<?php $comments = get_comments(array('post_id' => $post->ID, 'type' => 'comment', 'status' => 'approve')); ?>
	<ol class="commentlist">
		<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
	</ol>
	<div>
		<?php comment_form(array('title_reply' => __( 'Leave a Comment', 'textdomain' ), 'comment_notes_after' => ''), $post->ID); ?>
	</div>

	<h2>Contributors</h2>
	<?php $comments = get_comments(array('post_id' => $post->ID, 'type' => 'contribution', 'status' => 'approve')); ?>
	<ol class="commentlist">
		<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
	</ol>

</div>

<div class="right">

	<h1><?php the_title(); ?></h1>
	<?php if (get_post_meta($id, 'fundraiser-tagline', true)) { ?>
	<h3><?php echo wpautop(get_post_meta($id, 'fundraiser-tagline', true)); ?></h3>
	<?php } ?>

	<p><?php echo the_author_meta('user_login'); ?></p>

	<?php if (get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
	<p><?php echo wpautop(get_post_meta($id, 'fundraiser-amount-raised', true)); ?></p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-goal', true)) { ?>
	<p><?php echo wpautop(get_post_meta($id, 'fundraiser-goal', true)); ?></p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-goal', true) && get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
	<p><?php echo get_percentage_to_goal(floatval(get_post_meta($id, 'fundraiser-amount-raised', true)), floatval(get_post_meta($id, 'fundraiser-goal', true))); ?>%</p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-end', true)) { ?>
	<p><?php echo get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)); ?>&nbsp;day(s) left</p>
	<?php } ?>

	<?php echo do_shortcode("[Wow-Modal-Windows id=1]"); ?>
	<button id='wow-modal-id-1'>Contribute Now</button>

	<div class="sharing">
		<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" title="Share on Facebook." target="_blank"><?php echo facebook_svg(); ?></a>
		<a href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?>" title="Tweet this!" target="_blank"><?php echo twitter_svg(); ?></a>
		<a id="copy-link" class="tooltip">
			<?php echo twitter_svg(); ?>
			<p id="copy-success" class="tooltiptext">Copied!</p>
		</a>
	</div>

	<h3>Feed</h3>
	<?php $comments = get_comments(array('post_id' => $post->ID)); ?>
	<ol class="commentlist">
		<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
	</ol>


</div>

<script type="text/javascript">

	document.getElementById("copy-link").addEventListener("click", function(event){
	  event.preventDefault();
	  var aux = document.createElement("input");
	  aux.setAttribute("value", "<?php echo get_permalink($post_id); ?>");
	  document.body.appendChild(aux);
	  aux.select();
	  try {
	  	document.execCommand("copy");
	  	var tooltip = document.getElementById("copy-success");
	  	tooltip.classList.add("success");
	  	setTimeout(function() {
	  		tooltip.classList.remove("success");
	  	}, 1000);
	  } catch (e){
	  	// notify that it didnt work
	  }
	  
	  document.body.removeChild(aux);
	});


</script>


<?php
endwhile;
endif; ?>
</main>
<?php
get_footer();

?>