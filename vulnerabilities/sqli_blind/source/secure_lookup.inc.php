<?php

// Hardened: parameterised existence check shared by the low / medium / high levels.
// Malformed input never reaches the database and is answered with a plain 200,
// so neither the response body nor the status code leaks anything about the query.
function dvwaSqliBlindSecureHtml( $raw_id ) {
	global $db;

	$id = is_string( $raw_id ) || is_int( $raw_id )
		? filter_var( $raw_id, FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) )
		: false;

	$exists = false;
	if( $id !== false ) {
		$data = $db->prepare( 'SELECT 1 FROM users WHERE user_id = (:id) LIMIT 1;' );
		$data->bindValue( ':id', $id, PDO::PARAM_INT );
		$data->execute();
		$exists = ( $data->fetchColumn() !== false );
	}

	if( $exists ) {
		return '<pre>User ID exists in the database.</pre>';
	}

	if( $id !== false ) {
		// A well-formed id that is not in the database
		http_response_code( 404 );
	}
	return '<pre>User ID is MISSING from the database.</pre>';
}

?>
