<?php
// Disable features of posts and pages without removing the post types completely
add_action('admin_menu', 'disable_default_post_page_supports');
function disable_default_post_page_supports() {
    remove_menu_page('edit.php'); // Removes the 'Posts' menu
    remove_menu_page('edit.php?post_type=page'); // Removes the 'Pages' menu
}

add_action('init', 'remove_post_page_features', 10);
function remove_post_page_features() {
    remove_post_type_support('post', 'editor'); // Remove editor from posts
    remove_post_type_support('page', 'editor'); // Remove editor from pages
}

// Disable post-related actions and filters
add_action('wp_loaded', 'disable_post_features');
function disable_post_features() {
    // Redirect any direct access to posts or post archives
    if (is_admin() && (isset($_GET['post_type']) && $_GET['post_type'] == 'post')) {
        wp_redirect(admin_url());
        exit;
    }
}

// Disable page-related actions and filters
add_action('wp_loaded', 'disable_page_features');
function disable_page_features() {
    // Redirect any direct access to pages or page archives
    if (is_admin() && (isset($_GET['post_type']) && $_GET['post_type'] == 'page')) {
        wp_redirect(admin_url());
        exit;
    }
}

// Remove "New Post" and "New Page" from the admin bar
add_action('admin_bar_menu', 'remove_new_post_page_admin_bar_links', 999);
function remove_new_post_page_admin_bar_links($wp_admin_bar) {
    $wp_admin_bar->remove_node('new-post'); // Removes the "New Post" button
    $wp_admin_bar->remove_node('new-page'); // Removes the "New Page" button
}

// Remove the Quick Draft widget
add_action('wp_dashboard_setup', 'remove_quick_draft_widget', 999);
function remove_quick_draft_widget() {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}

// Disable REST API for posts and pages
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/posts'])) {
        unset($endpoints['/wp/v2/posts']);
    }
    if (isset($endpoints['/wp/v2/pages'])) {
        unset($endpoints['/wp/v2/pages']);
    }
    return $endpoints;
});

// Disable Gutenberg editor for posts and pages
add_filter('use_block_editor_for_post_type', function($can_edit, $post_type) {
    if ($post_type === 'post' || $post_type === 'page') {
        return false;
    }
    return $can_edit;
}, 10, 2);
