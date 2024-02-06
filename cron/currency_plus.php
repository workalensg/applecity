<?php
require_once('../config.php');

$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);

file_get_contents(HTTPS_SERVER."index.php?route=wgi/currency_plus&type=all", false, stream_context_create($arrContextOptions));
?>
