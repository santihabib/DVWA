<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/brute/source/secure_login.inc.php';

if( !isset( $html ) ) $html = '';
// Hardened: prepared statements + account lockout + failure delay
$html .= dvwaBruteForceSecureLogin( $_GET );

?>
