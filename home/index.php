<?php
include 'config.php';

function getEntityData($entities) {
    global $endpoint, $host, $token;

    $results = [];
    foreach ($entities as $entity_id => $fields) {
        $url = $endpoint . "/states/" . $entity_id;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"],
        ]);

        $response = curl_exec($curl);
        if ($response === false) {
            return ['server_status' => 0, 'error' => curl_error($curl)]; // 加入状态码 0 直接返回错误
        }

        curl_close($curl);

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['server_status' => 0, 'error' => 'Invalid JSON response']; // 加入状态码 0 直接返回错误
        }

        // 提取指定的字段
        $filteredData = [];
        foreach ($fields as $field) {
            if (strpos($field, '.') !== false) {
                // 处理嵌套字段，包括 attributes 下的特定键
                $keys = explode('.', $field);
                if ($keys[0] === 'attributes' && isset($data['attributes'])) {
                    $attributeKey = implode('.', array_slice($keys, 1));

                    // 判断是否是 entity_picture 并动态拼接 $host
                    if ($attributeKey === "entity_picture" && isset($data['attributes'][$attributeKey])) {
                        $filteredData[$field] = str_starts_with($data['attributes'][$attributeKey], $host)
                            ? $data['attributes'][$attributeKey]
                            : $host . $data['attributes'][$attributeKey];
                    } else {
                        $filteredData[$field] = $data['attributes'][$attributeKey] ?? null;
                    }
                } else {
                    // 处理一般的嵌套字段
                    $value = $data;
                    foreach ($keys as $key) {
                        if (isset($value[$key])) {
                            $value = $value[$key];
                        } else {
                            $value = null;
                            break;
                        }
                    }
                    $filteredData[$field] = $value;
                }
            } else {
                // 处理一级字段
                $filteredData[$field] = $data[$field] ?? null;
            }
        }

        $results[$entity_id] = $filteredData;
    }

    return ['server_status' => 1, 'data' => $results]; // 加入成功状态码。完整数据被包装在 data 键里
}

$entities = [
    "light.headlight" => ["state"],
    "light.left_side_lights" => ["state", "attributes.light.brightness", "attributes.light.color"],
    "light.right_side_lights" => ["state", "attributes.light.brightness", "attributes.light.color_temperature"],
    "media_player.mars_homepod_right" => ["state", "attributes.media_title", "attributes.media_artist", "attributes.media_album_name", "attributes.app_name", "attributes.entity_picture"],
    "switch.electric_blanket" => ["state"],
    "sensor.room_temperature" => ["state"],
    "sensor.room_humidity" => ["state"],
    "weather.forecast_home" => ["state", "attributes.temperature", "attributes.humidity", "attributes.wind_speed"]
];

header('Content-Type: application/json; charset=UTF-8');
$result = getEntityData($entities);
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);