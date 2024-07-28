<?php
include 'secret.php';
$status_file = '../status.txt'; // 相对路径

// 检查密码
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    echo 'Invalid secret.';
    exit;
}

// 检查并处理请求参数
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status == '1') {
        if (file_put_contents($status_file, 'awake') !== false) {
            echo 'ok'; // 状态更新成功
        } else {
            echo 'Error writing to file.';
        }
    } elseif ($status == '0') {
        if (file_put_contents($status_file, 'sleeping') !== false) {
            echo 'ok'; // 状态更新成功
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