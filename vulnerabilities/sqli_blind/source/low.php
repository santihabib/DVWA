<?php

require_once __DIR__ . '/secure_lookup.inc.php';

if( isset( $_GET[ 'Submit' ] ) ) {
	$html .= dvwaSqliBlindSecureHtml( isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : '' );
}

?>
