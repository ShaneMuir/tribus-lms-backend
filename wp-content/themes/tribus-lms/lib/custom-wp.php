<?php

/**
 *  Removes the WordPress Logo From Admin
 */
function remove_wp_logo_from_admin() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render', 'remove_wp_logo_from_admin', 0 );

/**
 *  Removes the WordPress footer and add a custom one
 */
function remove_footer_admin() {
    ?> &copy; <?php echo date('Y') . ' <a class="site-info" href="'.home_url().'">'.get_bloginfo('name').'</a>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

/**
 *  Remove the WordPress Version from the admin footer
 */
function remove_wordpress_version_from_footer() {
    remove_filter( 'update_footer', 'core_update_footer' );
}
add_action( 'admin_menu', 'remove_wordpress_version_from_footer' );
