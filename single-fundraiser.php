<?php
if (isset($_SERVER['HTTP_REFERER'])) {
	console_log($_SERVER['HTTP_REFERER']);
}

get_header(); ?>
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
				<div class="author-pic">
					<?php if(get_user_meta($post->post_author, "user-profile-picture", true)) { ?>
						<img src="<?php echo get_user_meta($post->post_author, "user-profile-picture", true); ?>">
					<?php } else {
						echo get_avatar($post->post_author, 168);
					} ?>
				</div>
				<p><?php echo the_author_meta('nickname'); ?></p>
			</div>

			<div class="sharing">
				<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" title="Share on Facebook." target="_blank"><?php echo facebook_svg(); ?></a>
				<a href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?>" title="Tweet this!" target="_blank"><?php echo twitter_svg(); ?></a>
				<a id="copy-link" class="msr-tooltip">
					<?php echo copy_svg(); ?>
					<p id="copy-success" class="msr-tooltiptext">Copied!</p>
				</a>
			</div>
		</div>

		<div class="description"><?php echo the_content(); ?></div>

		<?php if (is_user_logged_in()){
			$no_comments = "No comments yet, leave the first one!";
		} else {
			$no_comments = "No comments yet. Sign in and leave the first one!";
		} ?>

		<h2><?php echo comments_number($no_comments, "Comments (1)", "Comments (%)"); ?></h2>
		<?php if(is_user_logged_in()) { ?>
			<div class="comment-form">
				<div class="user-comment-pic">
					<?php if(get_user_meta(get_current_user_id(), "user-profile-picture", true)) { ?>
						<img src="<?php echo get_user_meta(get_current_user_id(), "user-profile-picture", true); ?>">
					<?php } else {
						echo get_avatar(get_current_user_id(), 168);
					} 
					$current_user = wp_get_current_user(); ?>
					<p><?php echo $current_user->user_login; ?></p>
				</div>
				<?php comment_form(array('comment_notes_after' => '', 'label_submit' => __( 'Post', 'textdomain' )), $post->ID); ?>
			</div>
		<?php } ?>
		<?php $comments = get_comments(array('post_id' => $post->ID, 'type' => 'comment', 'status' => 'approve')); ?>
		<ol class="commentlist">
			<div class="comment-wrapper">
				<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
			</div>
		</ol>
		<button id="expand-comments" class="wide-btn hide">Show more comments</button>

	</div>

	<div class="contribution-content">
		<div class="card">
			<p class="progress-summary"><strong>$<?php echo number_format($object['total'], 0, '.', ','); ?></strong> USD raised by <?php echo number_format(count($object['contribution-data']), 0, '.', ','); ?> contributions</p>

			<div id="campaign-progress" class="progress-bar"></div>

			<?php if (get_post_meta($id, 'fundraiserGoal', true)) { ?>
				<p class="progress-percent"><?php echo get_percentage_to_goal($object['total'], get_post_meta($id, 'fundraiserGoal', true)); ?>% of $<?php echo number_format(get_post_meta($id, 'fundraiserGoal', true), 0, '.', ','); ?></p>
			<?php } ?>

			<?php if (get_post_meta($id, 'fundraiserEnd', true)) { ?>
				<p class="time-left">
					<?php $days_left = get_fundraising_days_left(get_post_meta($id, 'fundraiserEnd', true));
						if ($days_left > 1) {
							echo $days_left . "&nbsp;days left";
						} else if ($days_left == 1) {
							echo $days_left . "&nbsp;day left";
						} else if ($days_left == 0){
							echo "Ending tonight";
						} else if ($days_left < 0) {
							echo "Ended";
						}
					?>
				</p>
			<?php } ?>
			<div class="btn-wrapper">
				<!-- If user isn't signed in target will be login-modal otherwise contribute-modal -->
				<button data-toggle="modal" data-target="#contribute-modal" <?php if (isset($days_left) && $days_left < 0) echo "disabled"; ?>>Contribute</button>
			</div>
		</div>

		<div class="contributors">
			<?php if(count($object['contribution-data']) > 0) { 
				$index = 0; ?>
				<h2>Contributions (<?php echo count($object['contribution-data']); ?>)</h2>
				<div class="contribution-constraint">
					<div class="contribution-wrapper">
						<?php foreach($object['contribution-data'] as $contribution) { 
							if ($contribution['metadata']['anonymous'] == "true" || $contribution['metadata']['customer_name'] == "") { 
								$display = "Anonymous";
							} else {
								$display = $contribution['metadata']['customer_name'];
							} ?>

							<p class="<?php if ($index > 3) { echo 'hide'; } ?>"><?php echo $display; ?><span>$<?php echo number_format($contribution['amount']/100, 0, '.', ','); ?></span><br><span class="date"><?php echo date('m/d/y', $contribution["created"]); ?></p>
						<?php $index++;
						} ?>
					</div>
				</div>
				<?php if($index > 3) { ?>
					<button id="expand-contributions" class="wide-btn">See More Contributions</button>
				<?php } ?>
			<?php } else { ?>
				<p>There have not been any contributions yet. Be the first!</p>
			<?php } ?>
		</div>
	</div>

	<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="myModalLabel">Contribute</h5>
				</div>
				<div class="modal-body">
					<div class="login-left">
						<p>Sign up or Log in. You'll be able to contribute faster and keep track of your contributions.</p>
						<?php echo do_shortcode('[ultimatemember form_id=8951]'); ?>
					</div>
					<div class="login-right">
						<p>Continue without Signing In. You don't need an account to contribute</p>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#contribute-modal">Continue as Guest</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="contribute-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="myModalLabel">Contribute</h5>
				</div>
				<div class="modal-body">
					<?php echo do_shortcode('[payment]'); ?>
				</div>
			</div>
		</div>
	</div>

<?php
endwhile;
endif; ?>
</main>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
		  value: <?php echo get_percentage_to_goal($object['total'], get_post_meta($id, 'fundraiserGoal', true)); ?>
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

	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip(); 
	});

	document.body.classList.remove("has-featured-image");

	$('#contribute-modal').on('hidden.bs.modal', function () {
		// Only if form is hidden
		if($('#stripe-payment-form').css('display') == 'none') {
	    	location.reload();
		}
	});
</script>
<?php
get_footer();

?>