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

get_header();

?>
<!-- Make call to stripe for user info -->
<?php get_customer_contributions($user_id); ?>


<!-- Make call to stripe for fundraiser info -->



<!-- Make call to get list all fundraiser  -->
<?php get_fundraiser_list($user_id); ?>




<?php 
get_footer();
?>