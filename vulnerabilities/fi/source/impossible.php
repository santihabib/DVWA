<?php

// The page we wish to display
$file = isset( $_GET[ 'page' ] ) && is_string( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : 'include.php';

// Only allow include.php or file{1..3}.php
$configFileNames = [
    'include.php',
    'file1.php',
    'file2.php',
    'file3.php',
];

if( !in_array( $file, $configFileNames, true ) ) {
    // This isn't the page we want!
    $page[ 'body' ] .= "<p>ERROR: File not found!</p>";
    $file = 'include.php';
}

?>
