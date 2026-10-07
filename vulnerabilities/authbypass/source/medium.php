<?php

// Hardened: only the admin user is allowed to access this page, at every security level
if( dvwaCurrentUser() !== 'admin' ) {
	http_response_code( 403 );
	print 'Unauthorised';
	exit;
}

?>
