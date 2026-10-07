<?php

// Hardened: only scripts served by this origin may run; no inline code, no third-party hosts, no reusable nonce
$headerCSP = "Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'; base-uri 'self';";

header($headerCSP);

?>
<?php
if (isset ($_POST['include'])) {
	// Hardened: whatever is submitted is rendered as text, never as markup or a script source
	$page[ 'body' ] .= "
	<p>" . htmlspecialchars( is_string( $_POST['include'] ) ? $_POST['include'] : '', ENT_QUOTES, 'UTF-8' ) . "</p>
";
}
$page[ 'body' ] .= '
<form name="csp" method="POST">
	<p>The Content Security Policy only allows scripts hosted on this server. Enter some text and it will be displayed back safely.</p>
	<input size="50" type="text" name="include" value="" id="include" />
	<input type="submit" value="Include" />
	<p>1+2+3+4+5=<span id="answer"></span></p>
	<input type="button" id="solve" value="Solve the sum" />
</form>

<script src="source/impossible.js"></script>
';

