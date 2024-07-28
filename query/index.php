<?php
$status_file = '../status.txt';

// 检查状态文件是否存在
if (!file_exists($status_file)) {
    echo 'Internal error.';
    exit;
}

// 读取状态文件的内容
$status = file_get_contents($status_file);

// 根据文件内容返回相应的响应
if ($status === 'sleeping') {
    echo '0';
} elseif ($status === 'awake') {
    echo '1';
} else {
    echo 'Internal error.';
    exit;
}