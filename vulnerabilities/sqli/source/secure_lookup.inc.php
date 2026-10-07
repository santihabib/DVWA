<?php

// Hardened: parameterised lookup shared by the low / medium / high levels
function dvwaSqliSecureLookup( $id ) {
	global $db;
	$html = '';

	if( !is_scalar( $id ) || !is_numeric( $id ) ) {
		return '<pre>ERROR: invalid user ID.</pre>';
	}
	$id = (int) $id;

	$data = $db->prepare( 'SELECT first_name, last_name FROM users WHERE user_id = (:id) LIMIT 1;' );
	$data->bindParam( ':id', $id, PDO::PARAM_INT );
	$data->execute();

	while( $row = $data->fetch() ) {
		$first = htmlspecialchars( $row[ 'first_name' ], ENT_QUOTES, 'UTF-8' );
		$last  = htmlspecialchars( $row[ 'last_name' ], ENT_QUOTES, 'UTF-8' );
		$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
	}

	return $html;
}

?>
