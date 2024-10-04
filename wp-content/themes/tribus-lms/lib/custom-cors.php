<?php
function add_cors_http_header(): void
{
//    header("Access-Control-Allow-Origin: *"); // Replace '*' with your frontend URL for better security
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
}
add_action('rest_api_init', 'add_cors_http_header');

function handle_options_request(): void
{
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//        header("Access-Control-Allow-Origin: *"); // Replace '*' with your frontend URL for better security
        header("Access-Control-Allow-Origin: http://localhost:5173");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        exit;
    }
}
add_action('init', 'handle_options_request');