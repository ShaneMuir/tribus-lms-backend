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
    var starterCodeEditor = wp.codeEditor.initialize( $( '#tribus_starter_code' ), editorSettings );

    $( '[id^=tribus_test_cases_input_]' ).each( function() {
        wp.codeEditor.initialize( $( this ), editorSettings );
    });
    $( '[id^=tribus_test_cases_output_]' ).each( function() {
        wp.codeEditor.initialize( $( this ), editorSettings );
    });
});
