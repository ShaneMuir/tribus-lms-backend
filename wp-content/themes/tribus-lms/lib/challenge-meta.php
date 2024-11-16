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
    $test_cases   = get_post_meta( $post->ID, '_tribus_test_cases', true );
    $test_cases   = is_array( $test_cases ) ? $test_cases : array( array( 'input' => '', 'output' => '' ) );
    $difficulty   = get_post_meta( $post->ID, '_tribus_difficulty', true );

    ?>
    <!-- Starter Code Field -->
    <p>
        <label for="tribus_starter_code">Starter Code:</label>
        <textarea id="tribus_starter_code" name="tribus_starter_code" rows="8" style="width: 100%;"><?php echo esc_textarea( $starter_code ); ?></textarea>
    </p>

    <h4>Test Cases:</h4>
    <div id="tribus-test-cases">
        <?php foreach ( $test_cases as $index => $test_case ) : ?>
            <div class="tribus-test-case" style="margin-bottom: 1rem;">
                <label>Test Input:</label>
                <textarea id="tribus_test_cases_input_<?php echo $index; ?>" name="tribus_test_cases[<?php echo $index; ?>][input]" rows="4" style="width: 45%; margin-right: 5%;"><?php echo esc_textarea( $test_case['input'] ); ?></textarea>

                <label>Expected Output:</label>
                <textarea id="tribus_test_cases_output_<?php echo $index; ?>" name="tribus_test_cases[<?php echo $index; ?>][output]" rows="4" style="width: 45%;"><?php echo esc_textarea( $test_case['output'] ); ?></textarea>

                <button type="button" class="button remove-test-case" style="color: red; margin-left: 5px;">Remove</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button add-test-case">Add Test Case</button>

    <script>
        jQuery( document ).ready( function( $ ) {
            var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
            editorSettings.codemirror = _.extend(
                {},
                editorSettings.codemirror,
                {
                    indentUnit: 4,
                    tabSize: 4,
                    mode: 'application/x-httpd-php'
                }
            );

            var testCaseIndex = <?php echo count( $test_cases ); ?>;
            $('.add-test-case').on('click', function() {
                var newTestCaseHTML = `
                <div class="tribus-test-case" style="margin-bottom: 1rem;">
                    <label>Test Input:</label>
                    <textarea id="tribus_test_cases_input_${testCaseIndex}" name="tribus_test_cases[${testCaseIndex}][input]" rows="4" style="width: 45%; margin-right: 5%;"></textarea>

                    <label>Expected Output:</label>
                    <textarea id="tribus_test_cases_output_${testCaseIndex}" name="tribus_test_cases[${testCaseIndex}][output]" rows="4" style="width: 45%;"></textarea>

                    <button type="button" class="button remove-test-case" style="color: red; margin-left: 5px;">Remove</button>
                </div>`;
                $('#tribus-test-cases').append(newTestCaseHTML);

                wp.codeEditor.initialize( $( `#tribus_test_cases_input_${testCaseIndex}` ), editorSettings );
                wp.codeEditor.initialize( $( `#tribus_test_cases_output_${testCaseIndex}` ), editorSettings );

                testCaseIndex++;
            });

            $(document).on('click', '.remove-test-case', function() {
                $(this).parent().remove();
            });
        });
    </script>


    <!-- Difficulty Field -->
    <p>
        <label for="tribus_difficulty">Difficulty:</label>
        <select id="tribus_difficulty" name="tribus_difficulty">
            <option value="" <?php selected($difficulty, ''); ?>>Select Difficulty</option>
            <option value="easy" <?php selected($difficulty, 'easy'); ?>>Easy</option>
            <option value="medium" <?php selected($difficulty, 'medium'); ?>>Medium</option>
            <option value="hard" <?php selected($difficulty, 'hard'); ?>>Hard</option>
        </select>
    </p>
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

    // Save difficulty
    if (isset($_POST['tribus_difficulty'])) {
        $difficulty = sanitize_text_field($_POST['tribus_difficulty']);
        update_post_meta($post_id, '_tribus_difficulty', $difficulty);

        // Determine score based on difficulty
        $score = 0;
        switch ($difficulty) {
            case 'easy':
                $score = 50;
                break;
            case 'medium':
                $score = 100;
                break;
            case 'hard':
                $score = 150;
                break;
        }
        update_post_meta($post_id, '_tribus_score', $score);
    }
}
add_action( 'save_post', 'tribus_save_challenge_meta' );
