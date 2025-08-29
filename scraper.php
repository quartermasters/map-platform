<?php
// Simple web scraper for medicinal plant market news

function fetch_html($url) {
    $options = [
        "http" => ["header" => "User-Agent: MAPBot/1.0\r\n"]
    ];
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

function extract_titles($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query("//h2 | //h3 | ////title");
    $titles = [];
    foreach ($nodes as $node) {
        $titles[] = trim($node->textContent);
    }
    return $titles;
}

// Example: Scrape Google News for 'medicinal plants'
$url = "https://news.google.com/search?q=medicinal+aromatic+plants";
$html = fetch_html($url);
$titles = extract_titles($html);
file_put_contents("scraped_titles.json", json_encode($titles));

?>