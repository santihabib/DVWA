<?php

require_once __DIR__ . '/secure_lookup.inc.php';

if( isset( $_SESSION[ 'id' ] ) ) {
	$html .= dvwaSqliSecureLookup( $_SESSION[ 'id' ] );
}

?>
