<?php
// Use code editor to make it easier to write the starter challenge code
function tribus_enqueue_code_editor(): void
{
    // Enqueue the code editor styles and scripts
    wp_enqueue_code_editor( array( 'type' => 'text/x-php' ) );
    wp_enqueue_script( 'tribus_code_editor_init', get_template_directory_uri() . '/js/code-editor-init.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'tribus_enqueue_code_editor' );
