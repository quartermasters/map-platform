<?php
// openai_analyzer.php
// Module for interacting with the OpenAI API.

// Include the configuration file to access the API key
require_once __DIR__ . '/config.php';

function callOpenAI($prompt, $model = 'gpt-3.5-turbo', $max_tokens = 500, $temperature = 0.7) {
    $api_key = OPENAI_API_KEY;
    $url = 'https://api.openai.com/v1/chat/completions';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ];

    $data = [
        'model' => $model,
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => $max_tokens,
        'temperature' => $temperature,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['error' => 'cURL Error: ' . $error];
    }

    $decoded_response = json_decode($response, true);

    if ($http_code !== 200) {
        return ['error' => 'API Error: ' . ($decoded_response['error']['message'] ?? 'Unknown error'), 'http_code' => $http_code];
    }

    return $decoded_response;
}

// --- Test Functionality ---
if (isset($_GET['test_openai'])) {
    $test_prompt = "What is the capital of France?";
    echo "<h2>Testing OpenAI API...</h2>";
    echo "<p>Prompt: " . htmlspecialchars($test_prompt) . "</p>";
    
    $openai_response = callOpenAI($test_prompt);
    
    if (isset($openai_response['error'])) {
        echo "<p style='color: red;'>Error: " . htmlspecialchars($openai_response['error']) . "</p>";
        if (isset($openai_response['http_code'])) {
            echo "<p style='color: red;'>HTTP Code: " . htmlspecialchars($openai_response['http_code']) . "</p>";
        }
    } else {
        echo "<p>Response:</p>";
        echo "<pre>" . htmlspecialchars(json_encode($openai_response, JSON_PRETTY_PRINT)) . "</pre>";
        if (isset($openai_response['choices'][0]['message']['content'])) {
            echo "<h3>Generated Content:</h3>";
            echo "<p>" . htmlspecialchars($openai_response['choices'][0]['message']['content']) . "</p>";
        }
    }
    exit; // Stop execution after test
}

?>