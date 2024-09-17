<?php
function custom_get_user_progress_endpoint(): void
{
    register_rest_route('custom/v1', '/progress', array(
        'methods' => 'GET',
        'callback' => 'custom_get_user_progress',
    ));
}
add_action('rest_api_init', 'custom_get_user_progress_endpoint');

function custom_get_user_progress(WP_REST_Request $request): WP_REST_Response
{
    $user_id = get_current_user_id();
    $completed_challenges = get_user_meta($user_id, 'completed_challenges', true);

    if (!is_array($completed_challenges)) {
        $completed_challenges = array();
    }

    return new WP_REST_Response(array('completed_challenges' => $completed_challenges), 200);
}
