<?php

$functions_dir = get_template_directory().'/lib/';

// Global functions (Make WP Headless)
require_once( $functions_dir.'custom-wp.php' );
require_once( $functions_dir.'disable-comments.php' );
require_once( $functions_dir.'disable-posts-and-pages.php' );


function tribus_register_challenge_cpt() {
    $labels = array(
        'name'               => _x( 'Challenges', 'post type general name' ),
        'singular_name'      => _x( 'Challenge', 'post type singular name' ),
        'menu_name'          => _x( 'Challenges', 'admin menu' ),
        'name_admin_bar'     => _x( 'Challenge', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'challenge' ),
        'add_new_item'       => __( 'Add New Challenge' ),
        'new_item'           => __( 'New Challenge' ),
        'edit_item'          => __( 'Edit Challenge' ),
        'view_item'          => __( 'View Challenge' ),
        'all_items'          => __( 'All Challenges' ),
        'search_items'       => __( 'Search Challenges' ),
        'not_found'          => __( 'No challenges found.' ),
        'not_found_in_trash' => __( 'No challenges found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'challenge' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' ),
    );

    register_post_type( 'challenge', $args );
}
add_action( 'init', 'tribus_register_challenge_cpt' );

function tribus_add_challenge_meta_boxes() {
    add_meta_box(
        'tribus_challenge_details',
        'Challenge Details',
        'tribus_challenge_details_callback',
        'challenge',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tribus_add_challenge_meta_boxes' );

function tribus_challenge_details_callback( $post ) {
    $test_input = get_post_meta( $post->ID, '_tribus_test_input', true );
    $expected_output = get_post_meta( $post->ID, '_tribus_expected_output', true );

    ?>
    <p>
        <label for="tribus_test_input">Test Input (Comma-separated):</label>
        <input type="text" id="tribus_test_input" name="tribus_test_input" value="<?php echo esc_attr( $test_input ); ?>" />
    </p>
    <p>
        <label for="tribus_expected_output">Expected Output:</label>
        <input type="text" id="tribus_expected_output" name="tribus_expected_output" value="<?php echo esc_attr( $expected_output ); ?>" />
    </p>
    <?php
}

function tribus_save_challenge_meta( $post_id ) {
    if ( isset( $_POST['tribus_test_input'] ) ) {
        update_post_meta( $post_id, '_tribus_test_input', sanitize_text_field( $_POST['tribus_test_input'] ) );
    }
    if ( isset( $_POST['tribus_expected_output'] ) ) {
        update_post_meta( $post_id, '_tribus_expected_output', sanitize_text_field( $_POST['tribus_expected_output'] ) );
    }
}
add_action( 'save_post', 'tribus_save_challenge_meta' );

