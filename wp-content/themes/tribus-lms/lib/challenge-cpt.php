<?php

// Register Custom Post Type for Challenges
function tribus_register_challenge_cpt(): void
{
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
        'supports'           => array( 'title', 'editor', 'custom-fields' ),
    );

    register_post_type( 'challenge', $args );
}
add_action( 'init', 'tribus_register_challenge_cpt' );
