<?php
function custom_update_user_progress_endpoint(): void
{
    register_rest_route('custom/v1', '/progress', array(
        'methods' => 'POST',
        'callback' => 'custom_update_user_progress',
        'permission_callback' => function () {
            return is_user_logged_in(); // Ensure the user is logged in
        }
    ));
}
add_action('rest_api_init', 'custom_update_user_progress_endpoint');

function custom_update_user_progress(WP_REST_Request $request): WP_Error|WP_REST_Response
{
    $user_id = get_current_user_id();
    $challenge_id = $request->get_param('challenge_id');

    if (empty($challenge_id)) {
        return new WP_Error('missing_data', 'Challenge ID is required', array('status' => 400));
    }

    // Get the current list of completed challenges
    $completed_challenges = get_user_meta($user_id, 'completed_challenges', true);
    $current_score = get_user_meta($user_id, 'score', true); // Get users score
    $score = get_post_meta($challenge_id, '_tribus_score', true); // Get the challenge score

    // Ensure it's an array
    if (!is_array($completed_challenges)) {
        $completed_challenges = array();
    }

    // Check if the challenge is already completed
    if (in_array($challenge_id, $completed_challenges)) {
        return new WP_REST_Response(array(
            'message' => 'This challenge has already been completed.',
            'completed_challenges' => $completed_challenges,
        ), 200); // Return with 200 status
    }

    // Add the new challenge ID to the array
    $completed_challenges[] = $challenge_id;
    update_user_meta($user_id, 'completed_challenges', $completed_challenges);

    $current_score = empty($current_score) ? 0 : intval($current_score);
    $new_score = $current_score + $score;

    update_user_meta($user_id, 'score', $new_score);

    return new WP_REST_Response(array(
        'message' => 'Challenge marked as completed',
        'completed_challenges' => $completed_challenges,
        'score' => $score,
        'new_score' => $new_score,
    ), 200);
}


/**
 * Exmaple JS Method:
 *
 * async function markChallengeAsCompleted(challengeId) {
 * try {
 * const response = await axios.post('https://tribus-lms.test/wp-json/custom/v1/progress', {
 * challenge_id: challengeId,
 * });
 * console.log(response.data);
 * } catch (error) {
 * console.error('Error marking challenge as completed:', error);
 * }
 * }
 */