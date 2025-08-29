<?php
// Test scraper with fallback HTML parsing for testing
// Note: This is for testing purposes only

function fetch_html($url) {
    $options = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: MAPBot/1.0\r\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"
        ]
    ];
    $context = stream_context_create($options);
    return @file_get_contents($url, false, $context);
}

function extract_titles_basic($html) {
    $titles = [];
    
    // Basic regex patterns for title extraction
    $patterns = [
        '/<title[^>]*>([^<]+)<\/title>/i',
        '/<h2[^>]*>([^<]+)<\/h2>/i', 
        '/<h3[^>]*>([^<]+)<\/h3>/i',
        '/<h1[^>]*>([^<]+)<\/h1>/i'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match_all($pattern, $html, $matches)) {
            foreach ($matches[1] as $match) {
                $clean_title = html_entity_decode(strip_tags(trim($match)));
                if (!empty($clean_title) && strlen($clean_title) > 5) {
                    $titles[] = $clean_title;
                }
            }
        }
    }
    
    return array_unique($titles);
}

// Test function
function test_scraper() {
    echo "=== MAP Terminal Scraping System Test ===\n\n";
    
    // Test 1: Check URL accessibility
    echo "1. Testing URL accessibility...\n";
    $test_url = "https://httpbin.org/html";  // Safe test URL
    $html = fetch_html($test_url);
    
    if ($html === false) {
        echo "   ❌ FAILED: Cannot fetch HTML content\n";
        echo "   Error: HTTP request failed\n";
        return false;
    } else {
        echo "   ✅ PASSED: HTML fetch successful (" . strlen($html) . " bytes)\n";
    }
    
    // Test 2: HTML parsing
    echo "\n2. Testing HTML parsing...\n";
    $titles = extract_titles_basic($html);
    
    if (empty($titles)) {
        echo "   ❌ FAILED: No titles extracted\n";
        return false;
    } else {
        echo "   ✅ PASSED: Extracted " . count($titles) . " titles\n";
        echo "   Sample titles:\n";
        foreach (array_slice($titles, 0, 3) as $title) {
            echo "   - " . substr($title, 0, 80) . "...\n";
        }
    }
    
    // Test 3: JSON serialization
    echo "\n3. Testing JSON serialization...\n";
    $json_data = json_encode($titles, JSON_PRETTY_PRINT);
    
    if ($json_data === false) {
        echo "   ❌ FAILED: JSON encoding failed\n";
        return false;
    } else {
        echo "   ✅ PASSED: JSON encoding successful\n";
    }
    
    // Test 4: File writing
    echo "\n4. Testing file writing...\n";
    $result = file_put_contents("test_scraped_data.json", $json_data);
    
    if ($result === false) {
        echo "   ❌ FAILED: Cannot write to file\n";
        return false;
    } else {
        echo "   ✅ PASSED: File written successfully ($result bytes)\n";
    }
    
    echo "\n=== Test Summary ===\n";
    echo "✅ All core scraping components functional\n";
    echo "📁 Test data saved to: test_scraped_data.json\n";
    
    return true;
}

// Test the actual scraping target (with error handling)
function test_google_news() {
    echo "\n=== Testing Google News Target ===\n";
    $url = "https://news.google.com/search?q=medicinal+aromatic+plants";
    
    echo "Target URL: $url\n";
    
    $html = fetch_html($url);
    
    if ($html === false) {
        echo "❌ FAILED: Cannot access Google News\n";
        echo "   Possible reasons:\n";
        echo "   - Network connectivity issues\n";
        echo "   - Google blocking automated requests\n";
        echo "   - User-Agent restrictions\n";
        return false;
    }
    
    echo "✅ PASSED: Google News accessible (" . strlen($html) . " bytes)\n";
    
    $titles = extract_titles_basic($html);
    echo "Extracted titles count: " . count($titles) . "\n";
    
    if (count($titles) > 0) {
        echo "Sample extracted content:\n";
        foreach (array_slice($titles, 0, 5) as $i => $title) {
            echo sprintf("  %d. %s\n", $i + 1, substr($title, 0, 100));
        }
    }
    
    return true;
}

// Run tests
if (php_sapi_name() === 'cli') {
    echo "PHP Scraper System Test Report\n";
    echo str_repeat("=", 50) . "\n";
    echo "PHP Version: " . PHP_VERSION . "\n";
    echo "Memory Limit: " . ini_get('memory_limit') . "\n";
    echo "Allow URL fopen: " . (ini_get('allow_url_fopen') ? 'Yes' : 'No') . "\n";
    echo str_repeat("=", 50) . "\n\n";
    
    $basic_test = test_scraper();
    
    if ($basic_test) {
        test_google_news();
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
}
?>