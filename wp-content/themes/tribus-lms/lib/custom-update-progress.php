<?php
function custom_update_user_progress_endpoint(): void
{
    register_rest_route('custom/v1', '/progress', array(
        'methods' => 'POST',
        'callback' => 'custom_update_user_progress',
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

    // Ensure it's an array
    if (!is_array($completed_challenges)) {
        $completed_challenges = array();
    }

    // Add the new challenge ID to the array if not already present
    if (!in_array($challenge_id, $completed_challenges)) {
        $completed_challenges[] = $challenge_id;
        update_user_meta($user_id, 'completed_challenges', $completed_challenges);
    }

    return new WP_REST_Response(array('message' => 'Challenge marked as completed', 'completed_challenges' => $completed_challenges), 200);
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