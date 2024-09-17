<?php
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
