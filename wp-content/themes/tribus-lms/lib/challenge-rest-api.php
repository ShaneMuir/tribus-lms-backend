<?php
function tribus_register_challenge_meta_rest_fields(): void
{
    // Register starter_code meta field for the REST API
    register_meta('post', '_tribus_starter_code', array(
        'type'         => 'string',
        'description'  => 'Starter code for the challenge',
        'single'       => true,
        'show_in_rest' => true, // Enable it for the REST API
    ));

    // Register the test input and output.
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
                        'input'  => array( 'type' => 'string' ),
                        'output' => array( 'type' => 'string' ),
                    ),
                ),
            ),
        ),
    ));

    // Register difficulty meta field for the REST API
    register_meta('post', '_tribus_difficulty', array(
        'type'         => 'string',
        'description'  => 'Difficulty level of the challenge',
        'single'       => true,
        'show_in_rest' => true, // Enable it for the REST API
    ));

    // Register score meta field for the REST API
    register_meta('post', '_tribus_score', array(
        'type'         => 'integer',
        'description'  => 'Score for the challenge based on difficulty',
        'single'       => true,
        'show_in_rest' => true, // Enable it for the REST API
    ));
}

add_action('rest_api_init', 'tribus_register_challenge_meta_rest_fields');
