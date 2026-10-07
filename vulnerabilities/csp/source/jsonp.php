<?php
header("Content-Type: application/json; charset=UTF-8");

// Hardened: the callback name is fixed, never taken from the request
$outp = array ("answer" => "15");

echo "solveSum (".json_encode($outp).")";
?>
