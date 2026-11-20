
<?php

// Очищення введених користувачем даних
function cleanInput($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// Збереження відповідей в JSON
function saveSurvey($data) {

    $directory = __DIR__ . '/../survey';

    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    $filename = date('Y-m-d_H-i-s') . '_' .
                bin2hex(random_bytes(4)) . '.json';

    $filepath = $directory . '/' . $filename;

    $json = json_encode(
        $data,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    return file_put_contents($filepath, $json) !== false;
}
