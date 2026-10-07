<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	// Hardened: cryptographically random, unpredictable session identifier
	$cookie_value = bin2hex(random_bytes(32));
	$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
	setcookie("dvwaSession", $cookie_value, array('expires' => time() + 3600, 'path' => '/vulnerabilities/weak_id/', 'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax'));
}
?>
