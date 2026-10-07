<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: DOM Based Cross Site Scripting (XSS)' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'xss_d';
$page[ 'help_button' ]   = 'xss_d';
$page[ 'source_button' ] = 'xss_d';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		break;
}

// Hardened for the CTF: every level runs the secure implementation
$vulnerabilityFile = 'impossible.php';

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/xss_d/source/{$vulnerabilityFile}";

$page[ 'body' ] = <<<EOF
<div class="body_padded">
	<h1>Vulnerability: DOM Based Cross Site Scripting (XSS)</h1>

	<div class="vulnerable_code_area">
 
 		<p>Please choose a language:</p>

		<form name="XSS" method="GET">
			<select name="default">
				<option value="English">English</option>
				<option value="French">French</option>
				<option value="Spanish">Spanish</option>
				<option value="German">German</option>
			</select>
			<input type="submit" value="Select" />
		</form>
		<script>
			// Hardened: the URL value is only used to pick an allow-listed option, never written into the DOM
			(function () {
				var lang = new URLSearchParams(window.location.search).get("default");
				var allowed = ["English", "French", "Spanish", "German"];
				if (allowed.indexOf(lang) >= 0) {
					document.querySelector('select[name="default"]').value = lang;
				}
			})();
		</script>
	</div>
EOF;

$page[ 'body' ] .= "
	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/xss/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/DOM_Based_XSS' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.acunetix.com/blog/articles/dom-xss-explained/' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
