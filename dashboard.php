<?php 

/**
 * Template Name: Dashboard
 *
 * Dashboard Template.
 *
 * @author Ali Haugh
 * @since 1.0.0
 */

$user_id = get_current_user_id();
$json_object = get_customer_contributions($user_id);

get_header();

?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<main id="main">

	<div id="tabs">
	    <ul>
			<li id="prof-pic" class="prof-pic no-bullet">
				<?php if(get_user_meta($user_id, "user-profile-picture", true)) { ?>
					<img src="<?php echo get_user_meta($user_id, "user-profile-picture", true); ?>">
				<?php } else {
					echo get_avatar($user_id, 168);
				} ?>
				<span>Edit</span>
				<form id="upload-pic-form" name="upload-pic-form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-ajax.php'); ?>">
					<input type="file" name="pic-upload" id="pic-upload" class="no-bullet pic-upload" accept="image/*">
					<?php wp_nonce_field( 'prof-pic-upload-action', 'prof-pic-upload-nonce' ); ?>
					<input name="action" value="change_profile_picture" type="hidden">
					<input name="userID" value="<?php echo $user_id; ?>" type="hidden">
					<input type="button" id="submit-prof-pic" name="submit" class="pic-upload" data-toggle="modal" data-target="#upload-progress">
				</form>
			</li>
    		<li class="dashb-username no-bullet"> <?php $user = get_userdata(get_current_user_id());
			$name = $user->first_name . " " . $user->last_name;
			echo $name; ?> </li>
	    </ul>
	</div>
</main>





<script type="text/javascript">

	// $(document).ready(function() {
	// 	window.scrollTo(0,0);
	// });

	$('#tabs')
	    .tabs()
	    .addClass('ui-tabs-vertical ui-helper-clearfix');

 //  	var hash = $.trim( window.location.hash);
 //    if (hash) $('#tabs a[href$="'+hash+'"]').trigger('click');
	
	// $("#cancel-sub").on("click", function () {
	// 	if($("#cancel-sub").is(':checked')) {
	// 		$("#save-btn").attr("disabled", false);
	// 		$("#save-btn").removeClass("disabled-btn");
	// 		$("#save-btn").addClass("blck-btn-contribute");
	// 	} else {
	// 		$("#save-btn").attr("disabled", true);
	// 		$("#save-btn").addClass("disabled-btn");
	// 		$("#save-btn").removeClass("blck-btn-contribute");
	// 	}
	// });

	// document.getElementById("totalText").textContent = "Total Raised: $" + $("#totalRaised").val();

	document.getElementById('prof-pic').onclick = function() {
    	document.getElementById('pic-upload').click();
	};

	$("#pic-upload").change(function() {
		$("#submit-prof-pic").click();
	});

	$('#confirm-cancel').click(function() {
		$('#cancel-recurring').submit();
	});

 	var progressbar = $('.progress-bar');

    $("#submit-prof-pic").click(function(){
		$("#upload-pic-form").ajaxForm( {
	  		beforeSend: function() {
				$(".progress").css("display","block");
				progressbar.width('0%');
				progressbar.text('0%');
            },
	    	uploadProgress: function (event, position, total, percentComplete) {
	        	progressbar.width(percentComplete + '%');
	        	progressbar.text(percentComplete + '%');
	     	}
		}).submit();
    });
</script>

 <?php 
get_footer();
?>