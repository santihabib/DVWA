<?php

require_once __DIR__ . '/secure_lookup.inc.php';

if( isset( $_REQUEST[ 'Submit' ] ) ) {
	$html .= dvwaSqliSecureLookup( isset( $_REQUEST[ 'id' ] ) ? $_REQUEST[ 'id' ] : '' );
}

?>
