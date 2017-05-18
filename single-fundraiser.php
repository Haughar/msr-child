<?php

if (isset($_SERVER['HTTP_REFERER'])) {
	console_log($_SERVER['HTTP_REFERER']);
}

get_header(); ?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<main id="main" class="fundraising">

<?php if (have_posts()) : while (have_posts()) : the_post();
	$post_id = $post->ID;

	$object = get_fundraiser_stripe_info($post_id);

	if ( has_post_thumbnail() ) { ?>
		<div class="main-image"><?php the_post_thumbnail( array(500,500) ); ?></div>
	<?php }?>

	
	<div>
		<h1><?php the_title(); ?></h1>
		<?php if ($post->post_author == get_current_user_id()) { ?>
			<button onclick="window.location.href='<?php echo home_url() . '/edit-fundraiser?post_id=' . $post_id ?>'" class="manage-btn">Manage</button>
		<?php } ?>
	</div>

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
			<div class="comment-wrapper">
				<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
			</div>
		</ol>
		<button id="expand-comments" class="hide">Show more comments</button>
		<button id="leave-comment">Leave a comment</button>
		<div class="comment-form">
			<?php comment_form(array('title_reply' => __( 'Leave a Comment', 'textdomain' ), 'comment_notes_after' => ''), $post->ID); ?>
		</div>

	</div>

	<div class="contribution-content">
		<div class="card">
			<p class="progress-summary"><strong>$<?php echo number_format($object['total'], 0, '.', ','); ?></strong> USD raised by <?php echo number_format(count($object['contribution-data']), 0, '.', ','); ?> contributions</p>

			<div id="campaign-progress" class="progress-bar"></div>

			<?php if (get_post_meta($id, 'fundraiser-goal', true)) { ?>
				<p class="progress-percent"><?php echo get_percentage_to_goal($object['total'], get_post_meta($id, 'fundraiser-goal', true)); ?>% of $<?php echo number_format(get_post_meta($id, 'fundraiser-goal', true), 0, '.', ','); ?></p>
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
			<?php if(count($object['contribution-data']) > 0) { 
				$index = 0; ?>
				<h2>Contributions(<?php echo count($object['contribution-data']); ?>)</h2>
				<div class="contribution-constraint">
					<div class="contribution-wrapper">
						<?php foreach($object['contribution-data'] as $contribution) { ?>
							<p class="<?php if ($index > 3) { echo 'hide'; } ?>"><?php echo $contribution["metadata"]["customer_name"]; ?><span>$<?php echo number_format($contribution['amount']/100, 0, '.', ','); ?></span></p>
						<?php $index++;
						} ?>
					</div>
				</div>
				<?php if($index > 3) { ?>
					<button id="expand-contributions">See More Contributions</button>
				<?php } ?>
			<?php } else { ?>
				<p>There have not been any contributions yet. Be the first!</p>
			<?php } ?>
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
		  value: <?php echo get_percentage_to_goal($object['total'], get_post_meta($id, 'fundraiser-goal', true)); ?>
		});

		var $sumCommentHeight = 0;
		$('ol.commentlist li:lt(3)').each(function() {
			$sumCommentHeight += $(this).outerHeight();
		});
		$('.commentlist').css('height', $sumCommentHeight + "px");
		if($('.commentlist').outerHeight() < $('.comment-wrapper').outerHeight()) {
			$("#expand-comments").removeClass("hide");
		}
	});

	$('#expand-contributions').click(function() {
		var beginningHeight = $('.contribution-wrapper').outerHeight();
		$('.contribution-constraint').css('overflow-y', 'hidden')
		$('.contribution-constraint').css('height', beginningHeight + 24 + "px");
		$('.contribution-constraint p').removeClass('hide');
		var height = $('.contribution-wrapper').outerHeight();
	    $('.contribution-constraint').animate({height: height + 20 + "px"}, 500, function() {
	    	$('#expand-contributions').hide("fade", {}, 300);
	    });
	});

	$('#expand-comments').click(function() {
		var height = $('.comment-wrapper').outerHeight();
	    $('.commentlist').animate({height: height + "px"}, 500, function() {
	    	$('#expand-comments').hide("fade", {}, 300);
	    });
	});

	$('#leave-comment').click(function() {
		$('.comment-form').slideToggle("fast");
	});


</script>
<?php
get_footer();

?>