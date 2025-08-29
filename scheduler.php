<?php
/**
 * MAP Terminal - Automated Scheduling System
 * Handles automated scraping with configurable intervals
 */

class MAPScheduler {
    
    private $configFile = 'scheduler_config.json';
    private $lockFile = 'scheduler.lock';
    private $logFile = 'scheduler_log.txt';
    private $config;
    
    public function __construct() {
        $this->loadConfig();
    }
    
    private function loadConfig() {
        $defaultConfig = [
            'enabled' => false,
            'interval_hours' => 4,
            'max_execution_time' => 300, // 5 minutes
            'retry_attempts' => 3,
            'retry_delay' => 300, // 5 minutes
            'quiet_hours' => [
                'start' => '23:00',
                'end' => '06:00'
            ],
            'email_notifications' => [
                'enabled' => false,
                'email' => '',
                'on_success' => false,
                'on_failure' => true
            ],
            'webhook_notifications' => [
                'enabled' => false,
                'url' => '',
                'on_success' => false,
                'on_failure' => true
            ],
            'last_run' => null,
            'next_run' => null
        ];
        
        if (file_exists($this->configFile)) {
            $config = json_decode(file_get_contents($this->configFile), true);
            $this->config = array_merge($defaultConfig, $config ?: []);
        } else {
            $this->config = $defaultConfig;
            $this->saveConfig();
        }
    }
    
    private function saveConfig() {
        file_put_contents($this->configFile, json_encode($this->config, JSON_PRETTY_PRINT));
    }
    
    /**
     * Check if scraping should run now
     */
    public function shouldRun() {
        if (!$this->config['enabled']) {
            return false;
        }
        
        // Check if already running
        if ($this->isRunning()) {
            return false;
        }
        
        // Check quiet hours
        if ($this->isQuietHours()) {
            return false;
        }
        
        // Check if enough time has passed since last run
        if ($this->config['last_run']) {
            $timeSinceLastRun = time() - $this->config['last_run'];
            $requiredInterval = $this->config['interval_hours'] * 3600;
            
            if ($timeSinceLastRun < $requiredInterval) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Run the scheduled scraping
     */
    public function run() {
        if (!$this->shouldRun()) {
            return [
                'success' => false,
                'reason' => 'Conditions not met for scheduled run'
            ];
        }
        
        $this->log("Starting scheduled scraping session");
        $this->createLockFile();
        
        try {
            $startTime = time();
            
            // Load scraper
            require_once 'scraper_production.php';
            $scraper = new MAPProductionScraper();
            
            $attempts = 0;
            $success = false;
            $result = null;
            
            while ($attempts < $this->config['retry_attempts'] && !$success) {
                $attempts++;
                $this->log("Scraping attempt $attempts of {$this->config['retry_attempts']}");
                
                $result = $scraper->scrapeAll();
                
                if ($result['success']) {
                    $success = true;
                    $this->log("Scraping successful: {$result['items_count']} items collected");
                } else {
                    $this->log("Scraping failed: {$result['error']}");
                    
                    if ($attempts < $this->config['retry_attempts']) {
                        $this->log("Waiting {$this->config['retry_delay']} seconds before retry");
                        sleep($this->config['retry_delay']);
                    }
                }
            }
            
            // Update config
            $this->config['last_run'] = time();
            $this->config['next_run'] = time() + ($this->config['interval_hours'] * 3600);
            $this->saveConfig();
            
            // Send notifications
            if ($success) {
                $this->sendNotification('success', $result);
            } else {
                $this->sendNotification('failure', $result);
            }
            
            $this->removeLockFile();
            
            return [
                'success' => $success,
                'attempts' => $attempts,
                'result' => $result,
                'execution_time' => time() - $startTime
            ];
            
        } catch (Exception $e) {
            $this->log("ERROR: " . $e->getMessage());
            $this->removeLockFile();
            
            $this->sendNotification('error', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'execution_time' => time() - $startTime
            ];
        }
    }
    
    /**
     * Check if scheduler is currently running
     */
    private function isRunning() {
        if (!file_exists($this->lockFile)) {
            return false;
        }
        
        $lockTime = (int)file_get_contents($this->lockFile);
        
        // Check if lock is stale (older than max execution time)
        if (time() - $lockTime > $this->config['max_execution_time']) {
            $this->removeLockFile();
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if we're in quiet hours
     */
    private function isQuietHours() {
        if (!$this->config['quiet_hours']['start'] || !$this->config['quiet_hours']['end']) {
            return false;
        }
        
        $now = date('H:i');
        $start = $this->config['quiet_hours']['start'];
        $end = $this->config['quiet_hours']['end'];
        
        // Handle overnight quiet hours (e.g., 23:00 to 06:00)
        if ($start > $end) {
            return ($now >= $start || $now <= $end);
        } else {
            return ($now >= $start && $now <= $end);
        }
    }
    
    private function createLockFile() {
        file_put_contents($this->lockFile, time());
    }
    
    private function removeLockFile() {
        if (file_exists($this->lockFile)) {
            unlink($this->lockFile);
        }
    }
    
    /**
     * Send notifications based on configuration
     */
    private function sendNotification($type, $data) {
        $shouldSend = false;
        
        switch ($type) {
            case 'success':
                $shouldSend = $this->config['email_notifications']['on_success'] || 
                             $this->config['webhook_notifications']['on_success'];
                break;
            case 'failure':
            case 'error':
                $shouldSend = $this->config['email_notifications']['on_failure'] || 
                             $this->config['webhook_notifications']['on_failure'];
                break;
        }
        
        if (!$shouldSend) {
            return;
        }
        
        $message = $this->buildNotificationMessage($type, $data);
        
        // Send email notification
        if ($this->config['email_notifications']['enabled'] && $this->config['email_notifications']['email']) {
            $this->sendEmailNotification($type, $message);
        }
        
        // Send webhook notification
        if ($this->config['webhook_notifications']['enabled'] && $this->config['webhook_notifications']['url']) {
            $this->sendWebhookNotification($type, $message, $data);
        }
    }
    
    private function buildNotificationMessage($type, $data) {
        $timestamp = date('Y-m-d H:i:s');
        
        switch ($type) {
            case 'success':
                return "MAP Terminal Scraping Success\n\n" .
                       "Time: $timestamp\n" .
                       "Items Collected: {$data['items_count']}\n" .
                       "Execution Time: {$data['execution_time']}s\n" .
                       "Average Relevance: " . round($data['data'] ? array_sum(array_column($data['data'], 'relevance_score')) / count($data['data']) : 0, 3);
                       
            case 'failure':
                return "MAP Terminal Scraping Failed\n\n" .
                       "Time: $timestamp\n" .
                       "Error: {$data['error']}\n" .
                       "Execution Time: {$data['execution_time']}s";
                       
            case 'error':
                return "MAP Terminal Scheduler Error\n\n" .
                       "Time: $timestamp\n" .
                       "Error: {$data['error']}";
                       
            default:
                return "MAP Terminal Notification\n\nTime: $timestamp";
        }
    }
    
    private function sendEmailNotification($type, $message) {
        $to = $this->config['email_notifications']['email'];
        $subject = "MAP Terminal - Scraping " . ucfirst($type);
        
        $headers = [
            'From: noreply@medicinalplants.site',
            'Reply-To: noreply@medicinalplants.site',
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        mail($to, $subject, $message, implode("\r\n", $headers));
        $this->log("Email notification sent to $to");
    }
    
    private function sendWebhookNotification($type, $message, $data) {
        $url = $this->config['webhook_notifications']['url'];
        
        $payload = [
            'type' => $type,
            'message' => $message,
            'data' => $data,
            'timestamp' => time()
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($payload)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        $this->log("Webhook notification sent to $url");
    }
    
    /**
     * Get scheduler status
     */
    public function getStatus() {
        return [
            'enabled' => $this->config['enabled'],
            'running' => $this->isRunning(),
            'quiet_hours' => $this->isQuietHours(),
            'last_run' => $this->config['last_run'],
            'next_run' => $this->config['next_run'],
            'should_run' => $this->shouldRun(),
            'config' => $this->config
        ];
    }
    
    /**
     * Update scheduler configuration
     */
    public function updateConfig($newConfig) {
        $this->config = array_merge($this->config, $newConfig);
        $this->saveConfig();
        
        $this->log("Configuration updated");
        
        return true;
    }
    
    /**
     * Enable/disable scheduler
     */
    public function setEnabled($enabled) {
        $this->config['enabled'] = (bool)$enabled;
        $this->saveConfig();
        
        $this->log("Scheduler " . ($enabled ? 'enabled' : 'disabled'));
        
        return true;
    }
    
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [SCHEDULER] $message" . PHP_EOL;
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Also output to console if running in CLI
        if (php_sapi_name() === 'cli') {
            echo $logEntry;
        }
    }
}

// CLI execution for cron jobs
if (php_sapi_name() === 'cli') {
    $scheduler = new MAPScheduler();
    $result = $scheduler->run();
    
    echo "=== SCHEDULED SCRAPING RESULT ===\n";
    echo "Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
    echo "Execution Time: " . ($result['execution_time'] ?? 0) . "s\n";
    
    if (isset($result['attempts'])) {
        echo "Attempts: " . $result['attempts'] . "\n";
    }
    
    if (isset($result['result']['items_count'])) {
        echo "Items Collected: " . $result['result']['items_count'] . "\n";
    }
    
    if (!$result['success']) {
        echo "Error: " . ($result['error'] ?? $result['reason'] ?? 'Unknown') . "\n";
        exit(1);
    }
    
    exit(0);
}

// Web interface for configuration
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    $scheduler = new MAPScheduler();
    
    switch ($_GET['action']) {
        case 'status':
            echo json_encode($scheduler->getStatus());
            break;
            
        case 'enable':
            $enabled = $_GET['enabled'] === 'true';
            echo json_encode(['success' => $scheduler->setEnabled($enabled)]);
            break;
            
        case 'run_now':
            $result = $scheduler->run();
            echo json_encode($result);
            break;
            
        case 'update_config':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                echo json_encode(['success' => $scheduler->updateConfig($input)]);
            }
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    
    exit;
}
?>