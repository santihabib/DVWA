<?php

require_once __DIR__ . '/secure_lookup.inc.php';

if( isset( $_POST[ 'Submit' ] ) ) {
	$html .= dvwaSqliBlindSecureHtml( isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '' );
}

?>
