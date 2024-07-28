<?php
$status_file = '../status.txt';

if (!file_exists($status_file)) {
    echo 'Internal error.';
    exit;
}

$status = file_get_contents($status_file);

if ($status === 'sleeping') {
    echo '0';
} elseif ($status === 'awake') {
    echo '1';
} else {
    echo 'Internal error.';
    exit;
}