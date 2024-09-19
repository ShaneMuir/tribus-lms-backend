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

// LMS Stuff
require_once( $functions_dir.'challenge-cpt.php' );
require_once( $functions_dir.'challenge-meta.php' );
require_once( $functions_dir.'challenge-rest-api.php' );
require_once( $functions_dir.'custom-code-editor.php' );
require_once( $functions_dir.'custom-get-progress.php' );
require_once( $functions_dir.'custom-register.php' );
require_once( $functions_dir.'custom-update-progress.php' );
require_once( $functions_dir.'custom-cors.php' );
