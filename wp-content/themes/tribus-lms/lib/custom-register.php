<?php

// Function to generate JWT token
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

// Custom Register REST API endpoint
function custom_register_user_endpoint(): void
{
    register_rest_route('custom/v1', 'register', array(
        'methods' => 'POST',
        'callback' => 'custom_register_user',
    ));
}
add_action('rest_api_init', 'custom_register_user_endpoint');

function custom_register_user(WP_REST_Request $request): WP_Error|WP_REST_Response
{
    $username = sanitize_text_field($request->get_param('username'));
    $email = sanitize_email($request->get_param('email'));
    $password = $request->get_param('password');

    if (empty($username) || empty($email) || empty($password)) {
        return new WP_Error('missing_data', 'Username, email, and password are required', array('status' => 400));
    }

    // Check if user already exists
    if (username_exists($username) || email_exists($email)) {
        return new WP_Error('user_exists', 'User already exists', array('status' => 400));
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        return new WP_Error('user_creation_failed', 'Failed to create user', array('status' => 500));
    }

    // Add custom meta fields
    update_user_meta($user_id, 'progress', 0);
    update_user_meta($user_id, 'score', 0);
    update_user_meta($user_id, 'completed_challenges', array());

    // Get the user score
    $user_score = get_user_meta($user_id, 'score', true);

    // Generate JWT token
    $token = generate_jwt_token($user_id);

    // Prepare user data to return
    $user_data = [
        'id' => $user_id,
        'username' => $username,
        'email' => $email,
        'score' => $user_score,
    ];

    return new WP_REST_Response(array(
        'message' => 'User created successfully',
        'token' => $token,
        'user' => $user_data
    ), 200);
}

function generate_jwt_token($user_id): string
{
    // Define your JWT secret key and algorithm
    $algorithm = 'HS256'; // The algorithm you are using

    // Create the token payload
    $payload = [
        'iat' => time(), // Issued at
        'exp' => time() + (60 * 60), // Expiration time (1 hour)
        'data' => array(
            'user' => array(
                'id' => $user_id
            )
        ),
        'iss' => get_bloginfo('url'),
    ];

    // Generate the JWT token
    return JWT::encode($payload, JWT_AUTH_SECRET_KEY, $algorithm);
}

function ensure_custom_meta_for_users($user_id): void
{
    // Check and add 'progress' meta
    if (get_user_meta($user_id, 'progress', true) === '') {
        update_user_meta($user_id, 'progress', []);
    }

    // Check and add 'score' meta
    if (get_user_meta($user_id, 'score', true) === '') {
        update_user_meta($user_id, 'score', 0);
    }

    // Check and add 'completed_challenges' meta
    if (get_user_meta($user_id, 'completed_challenges', true) === '') {
        update_user_meta($user_id, 'completed_challenges', []);
    }
}

// Hook to user registration or login to ensure meta is present
add_action('user_register', 'ensure_custom_meta_for_users');
add_action('wp_login', function($user_login, $user) {
    ensure_custom_meta_for_users($user->ID);
}, 10, 2);

function register_user_meta_fields(): void
{
    register_rest_field('user', 'progress', array(
        'get_callback' => function($user) {
            return get_user_meta($user['id'], 'progress', true);
        },
        'update_callback' => null,
        'schema' => array(
            'description' => 'Progress of the user',
            'type' => 'array',
            'context' => array('view', 'edit'),
        ),
    ));

    register_rest_field('user', 'completed_challenges', array(
        'get_callback' => function($user) {
            return get_user_meta($user['id'], 'completed_challenges', true);
        },
        'update_callback' => null,
        'schema' => array(
            'description' => 'Completed challenges of the user',
            'type' => 'array',
            'context' => array('view', 'edit'),
        ),
    ));


    register_rest_field('user', 'score', array(
        'get_callback' => function($user) {
            return get_user_meta($user['id'], 'score', true);
        },
        'update_callback' => null,
        'schema' => array(
            'description' => 'Score of the user',
            'type' => 'integer',
            'context' => array('view', 'edit'),
        ),
    ));
}

add_action('rest_api_init', 'register_user_meta_fields');
