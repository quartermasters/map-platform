<?php
/**
 * MAP Terminal - Advanced Scraping Dashboard v2.0
 * Bloomberg-style Market Intelligence Dashboard
 */

// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'run_scraper':
            require_once 'scraper_production.php';
            $scraper = new MAPProductionScraper();
            $result = $scraper->scrapeAll();
            echo json_encode($result);
            exit;
            
        case 'get_data':
            require_once 'scraper_production.php';
            $scraper = new MAPProductionScraper();
            $data = $scraper->getData();
            echo json_encode($data);
            exit;
            
        case 'get_stats':
            require_once 'scraper_production.php';
            $scraper = new MAPProductionScraper();
            $stats = $scraper->getStats();
            echo json_encode($stats);
            exit;
    }
}

// Load scraper data
require_once 'scraper_production.php';
$scraper = new MAPProductionScraper();
$scrapedData = $scraper->getData();
$stats = $scraper->getStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAP Terminal - Market Intelligence Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #0d1117;
            --bg-secondary: #161b22;
            --bg-tertiary: #21262d;
            --orange-primary: #ff6b35;
            --text-primary: #e6edf3;
            --text-secondary: #7d8590;
            --text-muted: #6e7681;
            --border-primary: #30363d;
            --border-secondary: #21262d;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --font-mono: 'Source Code Pro', 'Consolas', monospace;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        .dashboard-header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-primary);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--orange-primary);
            font-family: var(--font-mono);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .dashboard-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: var(--font-mono);
        }

        .btn-primary {
            background: var(--orange-primary);
            color: var(--bg-primary);
        }

        .btn-primary:hover {
            background: #ff8659;
            box-shadow: 0 0 15px rgba(255, 107, 53, 0.3);
        }

        .btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-primary);
        }

        .btn-secondary:hover {
            border-color: var(--orange-primary);
            box-shadow: 0 0 10px rgba(255, 107, 53, 0.1);
        }

        .dashboard-content {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: 6px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--orange-primary);
            transform: translateY(-2px);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--orange-primary);
            font-family: var(--font-mono);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .data-panel {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: 6px;
            margin-bottom: 30px;
        }

        .panel-header {
            background: var(--bg-tertiary);
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            font-family: var(--font-mono);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .panel-content {
            padding: 25px;
        }

        .data-item {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-primary);
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .data-item:hover {
            border-color: var(--orange-primary);
            box-shadow: 0 0 10px rgba(255, 107, 53, 0.1);
        }

        .item-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .item-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            flex: 1;
            line-height: 1.4;
        }

        .item-score {
            background: var(--orange-primary);
            color: var(--bg-primary);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 15px;
            font-family: var(--font-mono);
        }

        .item-meta {
            display: flex;
            gap: 20px;
            font-size: 12px;
            color: var(--text-secondary);
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .category-tag {
            background: var(--bg-primary);
            color: var(--orange-primary);
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(13, 17, 23, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loading-content {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: 6px;
            padding: 30px;
            text-align: center;
            min-width: 300px;
        }

        .spinner {
            border: 3px solid var(--border-primary);
            border-top: 3px solid var(--orange-primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 48px;
            color: var(--orange-primary);
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .logs-panel {
            background: var(--bg-primary);
            border: 1px solid var(--border-secondary);
            border-radius: 4px;
            padding: 20px;
            font-family: var(--font-mono);
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .dashboard-controls {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .item-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .item-score {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <div>Running Market Intelligence Scraper...</div>
            <div style="font-size: 12px; margin-top: 10px; color: var(--text-secondary);">
                This may take 30-60 seconds
            </div>
        </div>
    </div>

    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-chart-line"></i>
            MAP Market Intelligence Dashboard
        </h1>
        <div class="dashboard-controls">
            <button class="btn btn-primary" id="runScraper">
                <i class="fas fa-sync"></i> Run Scraper
            </button>
            <button class="btn btn-secondary" id="refreshData">
                <i class="fas fa-reload"></i> Refresh
            </button>
            <button class="btn btn-secondary" id="viewStats">
                <i class="fas fa-chart-bar"></i> Analytics
            </button>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value" id="totalItems"><?php echo count($scrapedData); ?></div>
                <div class="stat-label">Total Items</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="avgRelevance">
                    <?php echo $stats ? number_format($stats['average_relevance'] * 100, 1) . '%' : '0%'; ?>
                </div>
                <div class="stat-label">Avg Relevance</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="lastRun">
                    <?php echo $stats ? date('H:i', strtotime($stats['last_run'])) : 'Never'; ?>
                </div>
                <div class="stat-label">Last Run</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="execTime">
                    <?php echo $stats ? $stats['execution_time'] . 's' : '0s'; ?>
                </div>
                <div class="stat-label">Exec Time</div>
            </div>
        </div>

        <!-- Scraped Data Panel -->
        <div class="data-panel">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i class="fas fa-newspaper"></i>
                    Latest Market Intelligence
                </h2>
                <div style="font-size: 12px; color: var(--text-secondary);">
                    <?php echo count($scrapedData); ?> items • Updated <?php echo $stats ? date('M d, H:i', strtotime($stats['last_run'])) : 'Never'; ?>
                </div>
            </div>
            <div class="panel-content">
                <div id="dataContainer">
                    <?php if (empty($scrapedData)): ?>
                        <div class="empty-state">
                            <i class="fas fa-database"></i>
                            <h3>No Data Available</h3>
                            <p>Run the scraper to collect market intelligence data</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($scrapedData as $item): ?>
                            <div class="data-item">
                                <div class="item-header">
                                    <div class="item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                    <div class="item-score"><?php echo number_format($item['relevance_score'] * 100); ?>%</div>
                                </div>
                                <div class="item-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-tag"></i>
                                        <span class="category-tag"><?php echo ucfirst(str_replace('_', ' ', $item['category'])); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-globe"></i>
                                        <span><?php echo explode(' - ', $item['source'])[0]; ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?php echo date('M d, H:i', $item['timestamp']); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-font"></i>
                                        <span><?php echo $item['word_count']; ?> words</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dashboard JavaScript functionality
        document.addEventListener('DOMContentLoaded', function() {
            const runScraperBtn = document.getElementById('runScraper');
            const refreshDataBtn = document.getElementById('refreshData');
            const loadingOverlay = document.getElementById('loadingOverlay');

            runScraperBtn.addEventListener('click', function() {
                runScraper();
            });

            refreshDataBtn.addEventListener('click', function() {
                refreshData();
            });

            function runScraper() {
                showLoading();
                
                fetch('dashboard_v2.php?action=run_scraper')
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showNotification('Scraping completed successfully! Collected ' + data.items_count + ' items.', 'success');
                            setTimeout(refreshData, 1000);
                        } else {
                            showNotification('Scraping failed: ' + data.error, 'error');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showNotification('Request failed: ' + error.message, 'error');
                    });
            }

            function refreshData() {
                fetch('dashboard_v2.php?action=get_data')
                    .then(response => response.json())
                    .then(data => {
                        updateDataDisplay(data);
                        return fetch('dashboard_v2.php?action=get_stats');
                    })
                    .then(response => response.json())
                    .then(stats => {
                        updateStatsDisplay(stats);
                        showNotification('Data refreshed successfully', 'success');
                    })
                    .catch(error => {
                        showNotification('Refresh failed: ' + error.message, 'error');
                    });
            }

            function updateDataDisplay(data) {
                const container = document.getElementById('dataContainer');
                
                if (!data || data.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-database"></i>
                            <h3>No Data Available</h3>
                            <p>Run the scraper to collect market intelligence data</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = data.map(item => `
                    <div class="data-item">
                        <div class="item-header">
                            <div class="item-title">${escapeHtml(item.title)}</div>
                            <div class="item-score">${Math.round(item.relevance_score * 100)}%</div>
                        </div>
                        <div class="item-meta">
                            <div class="meta-item">
                                <i class="fas fa-tag"></i>
                                <span class="category-tag">${item.category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-globe"></i>
                                <span>${item.source.split(' - ')[0]}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>${new Date(item.timestamp * 1000).toLocaleString()}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-font"></i>
                                <span>${item.word_count} words</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            function updateStatsDisplay(stats) {
                if (!stats) return;
                
                document.getElementById('totalItems').textContent = stats.items_collected || 0;
                document.getElementById('avgRelevance').textContent = Math.round((stats.average_relevance || 0) * 100) + '%';
                document.getElementById('lastRun').textContent = new Date(stats.last_run).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                document.getElementById('execTime').textContent = (stats.execution_time || 0) + 's';
            }

            function showLoading() {
                loadingOverlay.style.display = 'flex';
                runScraperBtn.disabled = true;
            }

            function hideLoading() {
                loadingOverlay.style.display = 'none';
                runScraperBtn.disabled = false;
            }

            function showNotification(message, type) {
                // Simple notification system
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${type === 'success' ? 'var(--success)' : 'var(--danger)'};
                    color: white;
                    padding: 15px 20px;
                    border-radius: 4px;
                    font-size: 14px;
                    z-index: 1001;
                    max-width: 400px;
                `;
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 5000);
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        });
    </script>
</body>
</html>