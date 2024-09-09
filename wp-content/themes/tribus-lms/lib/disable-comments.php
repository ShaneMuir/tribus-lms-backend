<?php
/**
 * Plugin Name: Disable Comments
 * Description: Completely disables all comment and trackback functionality on the entire site,
 * including related admin menu items and widgets.
 */

/**
 * Disable support for comments and trackbacks in post types.
 */
function df_disable_comments_post_types_support(): void
{
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'df_disable_comments_post_types_support');

/**
 * Close comments on the front-end.
 *
 * @return false Always returns false to ensure comments are closed.
 */
function df_disable_comments_status(): bool
{
    return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

/**
 * Hide existing comments.
 *
 * @param array $comments An array of comments.
 * @return array An empty array, hiding all comments.
 */
function df_disable_comments_hide_existing_comments(array $comments): array
{
    return array();
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

/**
 * Remove comments page from the admin menu.
 */
function df_disable_comments_admin_menu(): void
{
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

/**
 * Redirect any user trying to access comments page.
 */
function df_disable_comments_admin_menu_redirect(): void
{
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

/**
 * Remove comments-related widgets.
 */
function df_disable_comments_remove_widgets(): void
{
    unregister_widget('WP_Widget_Recent_Comments');
}
add_action('widgets_init', 'df_disable_comments_remove_widgets');

/**
 * Remove comments links from admin bar.
 */
function df_disable_comments_admin_bar(): void
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'df_disable_comments_admin_bar');
