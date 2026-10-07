<?php

// Hardened: parameterised existence check shared by the low / medium / high levels
function dvwaSqliBlindSecureExists( $id ) {
	global $db;

	if( !is_scalar( $id ) || !is_numeric( $id ) ) {
		return false;
	}
	$id = (int) $id;

	$data = $db->prepare( 'SELECT first_name, last_name FROM users WHERE user_id = (:id) LIMIT 1;' );
	$data->bindParam( ':id', $id, PDO::PARAM_INT );
	$data->execute();

	return $data->rowCount() > 0;
}

function dvwaSqliBlindSecureHtml( $id ) {
	if( dvwaSqliBlindSecureExists( $id ) ) {
		return '<pre>User ID exists in the database.</pre>';
	}
	header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );
	return '<pre>User ID is MISSING from the database.</pre>';
}

?>
