<?php
/**
 * MAP Terminal - Advanced Web Scraper v2.0
 * Production-Ready Medicinal & Aromatic Plants Market Intelligence System
 * 
 * Features:
 * - No DOM extension dependency
 * - Advanced HTML parsing
 * - Error handling and recovery
 * - Rate limiting and throttling
 * - Content filtering and relevance scoring
 * - Multiple data sources
 * - Logging and monitoring
 */

class MAPScraper {
    
    private $config;
    private $logFile;
    private $dataFile;
    private $statsFile;
    private $userAgents;
    private $keywords;
    
    public function __construct() {
        $this->initializeConfig();
        $this->initializeFiles();
        $this->initializeUserAgents();
        $this->initializeKeywords();
    }
    
    private function initializeConfig() {
        $this->config = [
            'request_delay' => 2, // seconds between requests
            'max_retries' => 3,
            'timeout' => 30,
            'max_titles_per_source' => 20,
            'min_title_length' => 10,
            'relevance_threshold' => 0.3,
            'enable_logging' => true,
            'enable_rate_limiting' => true
        ];
    }
    
    private function initializeFiles() {
        $this->logFile = 'scraper_log.txt';
        $this->dataFile = 'scraped_titles.json';
        $this->statsFile = 'scraper_stats.json';
    }
    
    private function initializeUserAgents() {
        $this->userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'MAPBot/2.0 (+https://medicinalplants.site/bot)'
        ];
    }
    
    private function initializeKeywords() {
        $this->keywords = [
            'primary' => ['medicinal plants', 'aromatic plants', 'herbal medicine', 'plant medicine', 'botanical', 'phytotherapy'],
            'secondary' => ['herbs', 'natural medicine', 'traditional medicine', 'plant extract', 'essential oils', 'nutraceutical'],
            'industry' => ['market', 'industry', 'business', 'revenue', 'growth', 'investment', 'demand', 'supply'],
            'negative' => ['cooking', 'recipe', 'gardening', 'landscaping', 'decoration']
        ];
    }
    
    /**
     * Main scraping orchestrator
     */
    public function scrapeAll() {
        $this->log("=== Starting MAP Terminal Scraping Session ===");
        $startTime = microtime(true);
        
        try {
            $allData = [];
            $sources = $this->getDataSources();
            
            foreach ($sources as $source) {
                $this->log("Scraping source: {$source['name']}");
                
                if ($this->config['enable_rate_limiting']) {
                    sleep($this->config['request_delay']);
                }
                
                $sourceData = $this->scrapeSource($source);
                if (!empty($sourceData)) {
                    $allData = array_merge($allData, $sourceData);
                }
            }
            
            // Process and enhance data
            $processedData = $this->processScrapedData($allData);
            
            // Save data
            $this->saveData($processedData);
            
            // Update statistics
            $this->updateStats($processedData, microtime(true) - $startTime);
            
            $this->log("Scraping completed successfully. Items collected: " . count($processedData));
            
            return [
                'success' => true,
                'items_count' => count($processedData),
                'execution_time' => microtime(true) - $startTime,
                'data' => $processedData
            ];
            
        } catch (Exception $e) {
            $this->log("ERROR: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'execution_time' => microtime(true) - $startTime
            ];
        }
    }
    
    /**
     * Get configured data sources
     */
    private function getDataSources() {
        return [
            [
                'name' => 'Google News - Medicinal Plants',
                'url' => 'https://news.google.com/search?q=medicinal+plants+market',
                'type' => 'google_news'
            ],
            [
                'name' => 'Google News - Herbal Medicine',
                'url' => 'https://news.google.com/search?q=herbal+medicine+industry',
                'type' => 'google_news'
            ],
            [
                'name' => 'Google News - Aromatic Plants',
                'url' => 'https://news.google.com/search?q=aromatic+plants+business',
                'type' => 'google_news'
            ]
        ];
    }
    
    /**
     * Scrape individual source
     */
    private function scrapeSource($source) {
        $attempt = 0;
        $maxAttempts = $this->config['max_retries'];
        
        while ($attempt < $maxAttempts) {
            try {
                $html = $this->fetchHTML($source['url']);
                
                if ($html === false) {
                    throw new Exception("Failed to fetch HTML from {$source['url']}");
                }
                
                $titles = $this->extractTitles($html, $source['type']);
                $this->log("Extracted " . count($titles) . " titles from {$source['name']}");
                
                return array_map(function($title) use ($source) {
                    return [
                        'title' => $title,
                        'source' => $source['name'],
                        'url' => $source['url'],
                        'timestamp' => time(),
                        'relevance_score' => $this->calculateRelevance($title)
                    ];
                }, $titles);
                
            } catch (Exception $e) {
                $attempt++;
                $this->log("Attempt $attempt failed for {$source['name']}: " . $e->getMessage());
                
                if ($attempt < $maxAttempts) {
                    sleep($this->config['request_delay'] * $attempt); // Exponential backoff
                }
            }
        }
        
        return [];
    }
    
    /**
     * Fetch HTML with robust error handling
     */
    private function fetchHTML($url) {
        $userAgent = $this->userAgents[array_rand($this->userAgents)];
        
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: $userAgent\r\n" .
                           "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
                           "Accept-Language: en-US,en;q=0.5\r\n" .
                           "Accept-Encoding: gzip, deflate\r\n" .
                           "Connection: keep-alive\r\n" .
                           "Upgrade-Insecure-Requests: 1\r\n",
                'timeout' => $this->config['timeout'],
                'ignore_errors' => true
            ]
        ]);
        
        $html = @file_get_contents($url, false, $context);
        
        if ($html === false) {
            $error = error_get_last();
            throw new Exception("HTTP request failed: " . ($error['message'] ?? 'Unknown error'));
        }
        
        return $html;
    }
    
    /**
     * Advanced HTML title extraction without DOM extension
     */
    private function extractTitles($html, $sourceType = 'generic') {
        $titles = [];
        
        // Multiple parsing strategies
        $patterns = [
            // News article titles
            '/<h[1-6][^>]*>([^<]+)<\/h[1-6]>/i',
            '/<a[^>]*title="([^"]+)"/i',
            '/<title[^>]*>([^<]+)<\/title>/i',
            // Google News specific
            '/<article[^>]*>.*?<h[1-6][^>]*>([^<]+)<\/h[1-6]>.*?<\/article>/is',
            // Generic content
            '/<div[^>]*class="[^"]*title[^"]*"[^>]*>([^<]+)<\/div>/i',
            '/<span[^>]*class="[^"]*headline[^"]*"[^>]*>([^<]+)<\/span>/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $title = $this->cleanTitle($match[1]);
                    if ($this->isValidTitle($title)) {
                        $titles[] = $title;
                    }
                }
            }
        }
        
        // Remove duplicates and limit results
        $titles = array_unique($titles);
        return array_slice($titles, 0, $this->config['max_titles_per_source']);
    }
    
    /**
     * Clean and normalize extracted titles
     */
    private function cleanTitle($title) {
        // Decode HTML entities
        $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove extra whitespace
        $title = trim(preg_replace('/\s+/', ' ', $title));
        
        // Remove common noise
        $noise = ['- Google News', ' | News', ' - News', ' News', '...'];
        foreach ($noise as $pattern) {
            $title = str_replace($pattern, '', $title);
        }
        
        return trim($title);
    }
    
    /**
     * Validate title quality and relevance
     */
    private function isValidTitle($title) {
        // Length check
        if (strlen($title) < $this->config['min_title_length']) {
            return false;
        }
        
        // Skip generic titles
        $generic = ['Search', 'News', 'Home', 'Index', 'Topics'];
        if (in_array($title, $generic)) {
            return false;
        }
        
        // Check for negative keywords
        $titleLower = strtolower($title);
        foreach ($this->keywords['negative'] as $negative) {
            if (strpos($titleLower, $negative) !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Calculate relevance score for content prioritization
     */
    private function calculateRelevance($title) {
        $score = 0;
        $titleLower = strtolower($title);
        
        // Primary keywords (high weight)
        foreach ($this->keywords['primary'] as $keyword) {
            if (strpos($titleLower, $keyword) !== false) {
                $score += 1.0;
            }
        }
        
        // Secondary keywords (medium weight)
        foreach ($this->keywords['secondary'] as $keyword) {
            if (strpos($titleLower, $keyword) !== false) {
                $score += 0.6;
            }
        }
        
        // Industry keywords (low weight)
        foreach ($this->keywords['industry'] as $keyword) {
            if (strpos($titleLower, $keyword) !== false) {
                $score += 0.3;
            }
        }
        
        // Normalize score
        return min($score / 3.0, 1.0);
    }
    
    /**
     * Process and enhance scraped data
     */
    private function processScrapedData($rawData) {
        // Filter by relevance
        $filtered = array_filter($rawData, function($item) {
            return $item['relevance_score'] >= $this->config['relevance_threshold'];
        });
        
        // Sort by relevance
        usort($filtered, function($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });
        
        // Add additional metadata
        foreach ($filtered as &$item) {
            $item['date_scraped'] = date('Y-m-d H:i:s');
            $item['category'] = $this->categorizeTitle($item['title']);
            $item['word_count'] = str_word_count($item['title']);
        }
        
        return $filtered;
    }
    
    /**
     * Categorize content by type
     */
    private function categorizeTitle($title) {
        $titleLower = strtolower($title);
        
        if (strpos($titleLower, 'market') !== false || strpos($titleLower, 'industry') !== false) {
            return 'market_analysis';
        }
        if (strpos($titleLower, 'research') !== false || strpos($titleLower, 'study') !== false) {
            return 'research';
        }
        if (strpos($titleLower, 'investment') !== false || strpos($titleLower, 'funding') !== false) {
            return 'investment';
        }
        if (strpos($titleLower, 'regulation') !== false || strpos($titleLower, 'policy') !== false) {
            return 'regulatory';
        }
        
        return 'general';
    }
    
    /**
     * Save processed data to JSON file
     */
    private function saveData($data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if ($jsonData === false) {
            throw new Exception('Failed to encode data as JSON');
        }
        
        if (file_put_contents($this->dataFile, $jsonData) === false) {
            throw new Exception('Failed to save data to file');
        }
        
        $this->log("Data saved to {$this->dataFile}");
    }
    
    /**
     * Update scraping statistics
     */
    private function updateStats($data, $executionTime) {
        $stats = [
            'last_run' => date('Y-m-d H:i:s'),
            'execution_time' => round($executionTime, 2),
            'items_collected' => count($data),
            'average_relevance' => $this->calculateAverageRelevance($data),
            'categories' => $this->getCategoryDistribution($data),
            'sources' => $this->getSourceDistribution($data)
        ];
        
        file_put_contents($this->statsFile, json_encode($stats, JSON_PRETTY_PRINT));
        $this->log("Statistics updated");
    }
    
    private function calculateAverageRelevance($data) {
        if (empty($data)) return 0;
        
        $total = array_sum(array_column($data, 'relevance_score'));
        return round($total / count($data), 3);
    }
    
    private function getCategoryDistribution($data) {
        $categories = [];
        foreach ($data as $item) {
            $category = $item['category'];
            $categories[$category] = ($categories[$category] ?? 0) + 1;
        }
        return $categories;
    }
    
    private function getSourceDistribution($data) {
        $sources = [];
        foreach ($data as $item) {
            $source = $item['source'];
            $sources[$source] = ($sources[$source] ?? 0) + 1;
        }
        return $sources;
    }
    
    /**
     * Logging system
     */
    private function log($message) {
        if (!$this->config['enable_logging']) return;
        
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] $message" . PHP_EOL;
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Also output to console if running in CLI
        if (php_sapi_name() === 'cli') {
            echo $logEntry;
        }
    }
    
    /**
     * Get current statistics
     */
    public function getStats() {
        if (file_exists($this->statsFile)) {
            return json_decode(file_get_contents($this->statsFile), true);
        }
        return null;
    }
    
    /**
     * Get scraped data
     */
    public function getData() {
        if (file_exists($this->dataFile)) {
            return json_decode(file_get_contents($this->dataFile), true);
        }
        return [];
    }
}

// CLI execution
if (php_sapi_name() === 'cli' || !empty($_GET['run'])) {
    $scraper = new MAPScraper();
    $result = $scraper->scrapeAll();
    
    if (php_sapi_name() === 'cli') {
        echo "\n=== SCRAPING RESULT ===\n";
        echo "Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
        echo "Items: " . ($result['items_count'] ?? 0) . "\n";
        echo "Time: " . round($result['execution_time'], 2) . "s\n";
        
        if (!$result['success']) {
            echo "Error: " . $result['error'] . "\n";
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
?>