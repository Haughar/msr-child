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






<?php 

get_footer();

?>