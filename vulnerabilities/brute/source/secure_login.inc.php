<?php

/*
 * Shared hardened login used by the low / medium / high Brute Force levels.
 *
 * Mitigations:
 *  - Parameterised queries (PDO prepared statements): no SQL injection in
 *    the username / password fields, so an attacker cannot bypass the
 *    password check with ' OR 1=1 style payloads.
 *  - Per-account lockout: after $total_failed_login wrong attempts the
 *    account is locked for $lockout_time minutes, so an online brute force
 *    of the password space is no longer feasible.
 *  - Constant failure response + delay: the error message is identical for
 *    a bad user, bad password or locked account (no user enumeration), and
 *    every failure is slowed down.
 *  - Output encoding of the username echoed back on success.
 */
function dvwaBruteForceSecureLogin( $input ) {
	global $db;
	$html = '';

	if( !isset( $input[ 'Login' ] ) || !isset( $input[ 'username' ] ) || !isset( $input[ 'password' ] ) ) {
		return $html;
	}

	$user = (string) $input[ 'username' ];
	$pass = md5( (string) $input[ 'password' ] );

	// Lockout policy
	$total_failed_login = 3;
	$lockout_time       = 15; // minutes
	$account_locked     = false;

	// Check whether the account is currently locked out
	$data = $db->prepare( 'SELECT failed_login, last_login FROM users WHERE user = (:user) LIMIT 1;' );
	$data->bindParam( ':user', $user, PDO::PARAM_STR );
	$data->execute();
	$row = $data->fetch();

	if( $row && ( (int) $row[ 'failed_login' ] >= $total_failed_login ) ) {
		$last_login = strtotime( (string) $row[ 'last_login' ] );
		$timeout    = $last_login + ( $lockout_time * 60 );
		if( time() < $timeout ) {
			$account_locked = true;
		}
	}

	// Verify the credentials with a parameterised query
	$data = $db->prepare( 'SELECT * FROM users WHERE user = (:user) AND password = (:password) LIMIT 1;' );
	$data->bindParam( ':user', $user, PDO::PARAM_STR );
	$data->bindParam( ':password', $pass, PDO::PARAM_STR );
	$data->execute();
	$row = $data->fetch();

	if( $row && !$account_locked ) {
		$avatar       = htmlspecialchars( (string) $row[ 'avatar' ], ENT_QUOTES, 'UTF-8' );
		$safe_user    = htmlspecialchars( $user, ENT_QUOTES, 'UTF-8' );
		$failed_login = (int) $row[ 'failed_login' ];

		// Login successful
		$html .= "<p>Welcome to the password protected area <em>{$safe_user}</em></p>";
		$html .= "<img src=\"{$avatar}\" />";

		if( $failed_login >= $total_failed_login ) {
			$html .= "<p><em>Warning</em>: Someone might have been brute forcing your account.</p>";
		}

		// Reset the bad login counter
		$data = $db->prepare( 'UPDATE users SET failed_login = 0 WHERE user = (:user) LIMIT 1;' );
		$data->bindParam( ':user', $user, PDO::PARAM_STR );
		$data->execute();
	}
	else {
		// Login failed (wrong credentials, unknown user or locked account): same response for all
		sleep( 1 );
		$html .= "<pre><br />Username and/or password incorrect.<br /><br/>Alternatively, the account has been locked because of too many failed logins.<br />If this is the case, <em>please try again in {$lockout_time} minutes</em>.</pre>";

		// Increase the bad login counter (no-op for unknown users)
		$data = $db->prepare( 'UPDATE users SET failed_login = (failed_login + 1) WHERE user = (:user) LIMIT 1;' );
		$data->bindParam( ':user', $user, PDO::PARAM_STR );
		$data->execute();
	}

	// Record the time of this attempt
	$data = $db->prepare( 'UPDATE users SET last_login = now() WHERE user = (:user) LIMIT 1;' );
	$data->bindParam( ':user', $user, PDO::PARAM_STR );
	$data->execute();

	return $html;
}

?>
