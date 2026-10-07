<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/brute/source/secure_login.inc.php';

if( isset( $_GET[ 'Login' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );
}

if( !isset( $html ) ) $html = '';
// Hardened: prepared statements + account lockout + failure delay
$html .= dvwaBruteForceSecureLogin( $_GET );

// Generate Anti-CSRF token
generateSessionToken();

?>
