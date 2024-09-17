<?php

//TODO: Think about how we can make the test cases input / output better. More dynamic.
//TODO: Add fields for difficulty and score since we want to keep track of users progress and rankings.
//TODO: Add JWT RESI Auth...
//TODO: Add custom DB table or CPT for keep track of users progress and rank.
//TODO: Create REST endpoints for completing challenges, check if challenges are complete, updating user score/rank and so on
//TODO: Style up the backend
//TODO: Make WP compliant

$functions_dir = get_template_directory().'/lib/';

// Global functions (Make WP Headless)
require_once( $functions_dir.'custom-wp.php' );
require_once( $functions_dir.'disable-comments.php' );
require_once( $functions_dir.'disable-posts-and-pages.php' );

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
        'supports'           => array( 'title', 'editor' ),
    );

    register_post_type( 'challenge', $args );
}
add_action( 'init', 'tribus_register_challenge_cpt' );

// Add Meta Boxes for Starter Code and Test Cases
function tribus_add_challenge_meta_boxes(): void
{
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

function tribus_challenge_details_callback( $post ): void
{
    // Retrieve stored values
    $starter_code = get_post_meta( $post->ID, '_tribus_starter_code', true );
    $test_cases = get_post_meta( $post->ID, '_tribus_test_cases', true );
    $test_cases = is_array( $test_cases ) ? $test_cases : array( array( 'input' => '', 'output' => '' ) );

    ?>
    <!-- Starter Code Field -->
    <p>
        <label for="tribus_starter_code">Starter Code:</label>
        <textarea id="tribus_starter_code" name="tribus_starter_code" rows="8" style="width: 100%;"><?php echo esc_textarea( $starter_code ); ?></textarea>
    </p>

    <!-- Test Cases Repeater -->
    <h4>Test Cases:</h4>
    <div id="tribus-test-cases">
        <?php foreach ( $test_cases as $index => $test_case ) : ?>
            <div class="tribus-test-case" style="margin-bottom: 1rem;">
                <label>Test Input:</label>
                <input type="text" name="tribus_test_cases[<?php echo $index; ?>][input]" value="<?php echo esc_attr( $test_case['input'] ); ?>" style="width: 45%; margin-right: 5%;">
                <label>Expected Output:</label>
                <input type="text" name="tribus_test_cases[<?php echo $index; ?>][output]" value="<?php echo esc_attr( $test_case['output'] ); ?>" style="width: 45%;">
                <button type="button" class="button remove-test-case" style="color: red; margin-left: 5px;">Remove</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button add-test-case">Add Test Case</button>

    <script>
        (function($){
            $(document).ready(function() {
                var testCaseIndex = <?php echo count( $test_cases ); ?>;

                // Add new test case
                $('.add-test-case').on('click', function() {
                    var newTestCase = '<div class="tribus-test-case" style="margin-bottom: 1rem;">' +
                        '<label>Test Input:</label>' +
                        '<input type="text" name="tribus_test_cases[' + testCaseIndex + '][input]" value="" style="width: 45%; margin-right: 5%;">' +
                        '<label>Expected Output:</label>' +
                        '<input type="text" name="tribus_test_cases[' + testCaseIndex + '][output]" value="" style="width: 45%;">' +
                        '<button type="button" class="button remove-test-case" style="color: red; margin-left: 5px;">Remove</button>' +
                        '</div>';
                    $('#tribus-test-cases').append(newTestCase);
                    testCaseIndex++;
                });

                // Remove test case
                $(document).on('click', '.remove-test-case', function() {
                    $(this).parent().remove();
                });
            });
        })(jQuery);
    </script>
    <?php
}

// Save Meta Box Values
function tribus_save_challenge_meta( $post_id ): void
{
    if ( isset( $_POST['tribus_starter_code'] ) ) {
        update_post_meta( $post_id, '_tribus_starter_code', $_POST['tribus_starter_code'] );
    }

    if ( isset( $_POST['tribus_test_cases'] ) ) {
        $test_cases = array_map( function( $test_case ) {
            return array(
                'input'  => $test_case['input'],
                'output' => $test_case['output']
            );
        }, $_POST['tribus_test_cases'] );
        update_post_meta( $post_id, '_tribus_test_cases', $test_cases );
    }
}
add_action( 'save_post', 'tribus_save_challenge_meta' );

// Use code editor to make it easier to write the starter challenge code
function tribus_enqueue_code_editor(): void
{
    // Enqueue the code editor styles and scripts
    wp_enqueue_code_editor( array( 'type' => 'text/x-php' ) );
    wp_enqueue_script( 'tribus_code_editor_init', get_template_directory_uri() . '/js/code-editor-init.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'tribus_enqueue_code_editor' );

function tribus_register_challenge_meta_rest_fields(): void
{
    // Register starter_code meta field for the REST API
    register_meta('post', '_tribus_starter_code', array(
        'type'         => 'string',
        'description'  => 'Starter code for the challenge',
        'single'       => true,
        'show_in_rest' => true, // Enable it for the REST API
    ));

    // Register test_cases meta field for the REST API
    register_meta('post', '_tribus_test_cases', array(
        'type'         => 'array',
        'description'  => 'Test cases for the challenge',
        'single'       => true,
        'show_in_rest' => array(
            'schema' => array(
                'type'       => 'array',
                'items'      => array(
                    'type'       => 'object',
                    'properties' => array(
                        'input'  => array(
                            'type' => 'string',
                        ),
                        'output' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
            ),
            'get_callback' => 'tribus_get_test_cases', // Custom function to retrieve the test cases TO IMPLEMENT LATER
            'update_callback' => 'tribus_update_test_cases', // Custom function to update test cases TO IMPLEMENT LATER
        ),
    ));
}

add_action('rest_api_init', 'tribus_register_challenge_meta_rest_fields');
