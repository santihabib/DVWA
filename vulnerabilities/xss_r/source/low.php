<?php

header( "X-XSS-Protection: 0" );

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
	// Hardened: HTML-encode the value before reflecting it
	$name = htmlspecialchars( (string) $_GET[ 'name' ], ENT_QUOTES, 'UTF-8' );

	// Feedback for end user
	$html .= "<pre>Hello {$name}</pre>";
}

?>
