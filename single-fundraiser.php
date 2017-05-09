<?php

if (isset($_SERVER['HTTP_REFERER'])) {
	console_log($_SERVER['HTTP_REFERER']);
}

get_header(); ?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<main id="main" class="fundraising">

<?php if (have_posts()) : while (have_posts()) : the_post();
	$post_id = $post->ID;

	if ( has_post_thumbnail() ) {
		the_post_thumbnail( array(500,500) );
	}?>

	
	<h1><?php the_title(); ?></h1>
	<?php if ($post->post_author == get_current_user_id()) { ?>
		<button onclick="window.location.href='<?php echo home_url() . '/edit-fundraiser?post_id=' . $post_id ?>'" class="manage-btn">Manage</button>
	<?php } ?>

	<div class="main-content">

		<div class="author">
			<div>
				<p><?php echo the_author_meta('user_login'); ?></p>
			</div>

			<div class="sharing">
				<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" title="Share on Facebook." target="_blank"><?php echo facebook_svg(); ?></a>
				<a href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?>" title="Tweet this!" target="_blank"><?php echo twitter_svg(); ?></a>
				<a id="copy-link" class="tooltip">
					<?php echo copy_svg(); ?>
					<p id="copy-success" class="tooltiptext">Copied!</p>
				</a>
			</div>
		</div>

		<div class="description"><?php echo the_content(); ?></div>	

		<h2>Updates and Comments</h2>
		<?php $comments = get_comments(array('post_id' => $post->ID, 'type' => 'comment', 'status' => 'approve')); ?>
		<ol class="commentlist">
			<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
		</ol>
		<div>
			<?php comment_form(array('title_reply' => __( 'Leave a Comment', 'textdomain' ), 'comment_notes_after' => ''), $post->ID); ?>
		</div>

	</div>

	<div class="contribution-content">

		<div class="card">

			<p class="progress-summary"><strong>$63,000</strong> USD raised by 322 contributors</p>

			<div id="campaign-progress" class="progress-bar"></div>

			<?php if (get_post_meta($id, 'fundraiser-goal', true)) { ?>
				<p class="progress-percent"><?php echo get_percentage_to_goal("20000", get_post_meta($id, 'fundraiser-goal', true)); ?>% of $<?php echo get_post_meta($id, 'fundraiser-goal', true); ?></p>
			<?php } ?>

			<?php if (get_post_meta($id, 'fundraiser-end', true)) { ?>
				<p class="time-left"><?php echo get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)); ?>&nbsp;day(s) left</p>
			<?php } ?>

			<?php echo do_shortcode("[Wow-Modal-Windows id=1]"); ?>
			<div class="btn-wrapper">
				<button id='wow-modal-id-1'>Contribute</button>
			</div>
		</div>

		<div class="contributors">
			<h2>Contributors</h2>
			<?php $comments = get_comments(array('post_id' => $post->ID, 'type' => 'contribution', 'status' => 'approve')); ?>
			<ol class="commentlist">
				<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
			</ol>
		</div>
	</div>

<?php
endwhile;
endif; ?>
</main>

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

	$( function() {
		$( "#campaign-progress" ).progressbar({
		  value: <?php echo get_percentage_to_goal("1000", get_post_meta($id, 'fundraiser-goal', true)); ?>
		});
	});


</script>
<?php
get_footer();

?>