<?php
include 'secret.php';
$status_file = '../status.txt';

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    echo 'Invalid secret.';
    exit;
}

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status == '1') {
        if (file_put_contents($status_file, 'awake') !== false) {
            echo 'ok';
        } else {
            echo 'Error writing to file.';
        }
    } elseif ($status == '0') {
        if (file_put_contents($status_file, 'sleeping') !== false) {
            echo 'ok';
        } else {
            echo 'Error writing to file.';
        }
    } else {
        echo 'Bad request.';
    }
    exit;
} else {
    echo 'Bad request.';
}