<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = isset( $_REQUEST[ 'ip' ] ) && is_string( $_REQUEST[ 'ip' ] ) ? trim( $_REQUEST[ 'ip' ] ) : '';

	// Hardened: only a syntactically valid IP address is accepted, and it is passed as a single quoted argument
	if( filter_var( $target, FILTER_VALIDATE_IP ) !== false ) {
		$arg = escapeshellarg( $target );

		// Determine OS and execute the ping command.
		if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
			// Windows
			$cmd = shell_exec( 'ping  ' . $arg );
		}
		else {
			// *nix
			$cmd = shell_exec( 'ping  -c 4 ' . $arg );
		}

		// Feedback for the end user
		$html .= "<pre>" . htmlspecialchars( (string) $cmd, ENT_QUOTES, 'UTF-8' ) . "</pre>";
	}
	else {
		$html .= '<pre>ERROR: You have entered an invalid IP.</pre>';
	}
}

?>
