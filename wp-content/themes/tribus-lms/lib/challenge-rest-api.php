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
