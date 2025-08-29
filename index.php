<?php
// MAP Terminal - Bloomberg Style Research Platform
// Medicinal & Aromatic Plants Market Analysis

// Market data configuration
$market_data = [
    'current_market_size' => 410.3,
    'projected_market_size' => 890.7,
    'cagr' => 8.1,
    'herbal_medicine_current' => 233.08,
    'herbal_medicine_projected' => 437.0,
    'herbal_cagr' => 8.23,
    'market_status' => 'ACTIVE',
    'last_updated' => date('Y-m-d H:i:s T')
];

$key_metrics = [
    ['label' => 'MAP Market Size', 'value' => '$410.3B', 'change' => '+8.1%', 'status' => 'up'],
    ['label' => 'Herbal Medicine', 'value' => '$233.08B', 'change' => '+8.23%', 'status' => 'up'],
    ['label' => 'Raw Materials', 'value' => '$185.6B', 'change' => '+8.3%', 'status' => 'up'],
    ['label' => 'Europe Market Share', 'value' => '44.55%', 'change' => 'LEAD', 'status' => 'neutral'],
];

$species_data = [
    ['name' => 'Ginseng (Panax spp.)', 'market_position' => 'Premium', 'growth' => 'High', 'cultivation_time' => '7-10 years'],
    ['name' => 'Echinacea purpurea', 'market_position' => 'Strong', 'growth' => 'High', 'cultivation_time' => '1-2 years'],
    ['name' => 'Turmeric (Curcuma longa)', 'market_position' => 'Stable', 'growth' => 'Moderate', 'cultivation_time' => '8-12 months'],
    ['name' => 'Aloe Vera', 'market_position' => 'Diversified', 'growth' => 'Steady', 'cultivation_time' => '2-3 years']
];

$regional_data = [
    ['region' => 'Europe', 'market_share' => 44.55, 'growth_rate' => 7.8, 'key_markets' => 'Germany, UK, France'],
    ['region' => 'Asia-Pacific', 'market_share' => 32.1, 'growth_rate' => 9.2, 'key_markets' => 'India, China, Japan'],
    ['region' => 'North America', 'market_share' => 18.3, 'growth_rate' => 8.5, 'key_markets' => 'USA, Canada'],
    ['region' => 'Others', 'market_share' => 5.05, 'growth_rate' => 6.9, 'key_markets' => 'Latin America, MEA']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicinal and Aromatic Plants Platform by Quartermasters</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Source+Code+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bloomberg.css">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5504723671860633"
         crossorigin="anonymous"></script>
    
    <!-- Chart.js for Interactive Analytics - Enhanced webserver loading -->
    <script>
        console.log('Loading Chart.js with webserver optimizations...');
        
        // Function to load script dynamically with better error handling
        function loadChartJS(url, callback) {
            const script = document.createElement('script');
            script.src = url;
            script.onload = callback;
            script.onerror = function() {
                console.warn('Failed to load Chart.js from:', url);
                callback(false);
            };
            script.crossOrigin = 'anonymous'; // Handle CORS issues
            document.head.appendChild(script);
        }

        // Progressive loading with multiple CDN attempts
        function attemptChartJSLoad() {
            const cdns = [
                'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js',
                'https://unpkg.com/chart.js@3.9.1/dist/chart.min.js'
            ];

            let cdnIndex = 0;

            function tryNextCDN(success) {
                if (success || typeof Chart !== 'undefined') {
                    console.log('Chart.js loaded successfully from CDN:', cdns[cdnIndex - 1] || 'unknown');
                    return;
                }

                if (cdnIndex < cdns.length) {
                    console.log(`Attempting to load Chart.js from CDN ${cdnIndex + 1}/${cdns.length}`);
                    loadChartJS(cdns[cdnIndex], tryNextCDN);
                    cdnIndex++;
                } else {
                    console.warn('All Chart.js CDN attempts failed, relying on fallback systems');
                }
            }

            // Start loading
            tryNextCDN(false);
        }

        // Start loading process
        attemptChartJSLoad();
    </script>
</head>
<body class="terminal-body">
    <!-- Terminal Header -->
    <header class="terminal-header">
        <div class="header-left">
            <div class="terminal-logo">
                <i class="fas fa-chart-line"></i>
                <span class="logo-text">MAP TERMINAL</span>
            </div>
            <div class="market-status">
                <span class="status-indicator <?php echo strtolower($market_data['market_status']); ?>"></span>
                <span class="status-text">MARKET <?php echo $market_data['market_status']; ?></span>
            </div>
        </div>
        <div class="header-center">
            <h1 class="main-title">MEDICINAL AND AROMATIC PLANTS PLATFORM BY QUARTERMASTERS</h1>
        </div>
        <div class="header-right">
            <div class="live-clock">
                <i class="fas fa-clock"></i>
                <span id="terminal-clock"><?php echo date('H:i:s T'); ?></span>
            </div>
            <div class="last-updated">
                LAST UPDATE: <?php echo date('M d, Y H:i', strtotime($market_data['last_updated'])); ?>
            </div>
        </div>
    </header>

    <!-- Header Leaderboard Ad -->
    <div class="ad-container ad-header-leaderboard">
        <div class="ad-label">SPONSORED</div>
        <div class="ad-placeholder" id="ad-header-728x90">
            <!-- Google Ad 728x90 Leaderboard -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:728px;height:90px"
                 data-ad-client="ca-pub-5504723671860633"
                 data-ad-slot="XXXXXXXXX"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>

    <!-- Main Container -->
    <div class="terminal-container">
        <!-- Navigation Sidebar -->
        <nav class="terminal-nav">
            <ul class="nav-menu">
                <li class="nav-item active" data-section="overview">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>OVERVIEW</span>
                </li>
                <li class="nav-item" data-section="markets">
                    <i class="fas fa-globe-americas"></i>
                    <span>MARKETS</span>
                </li>
                <li class="nav-item" data-section="species">
                    <i class="fas fa-seedling"></i>
                    <span>SPECIES</span>
                </li>
                <li class="nav-item" data-section="supply">
                    <i class="fas fa-truck"></i>
                    <span>SUPPLY CHAIN</span>
                </li>
                <li class="nav-item" data-section="outlook">
                    <i class="fas fa-crystal-ball"></i>
                    <span>OUTLOOK</span>
                </li>
                <li class="nav-item" data-section="analytics">
                    <i class="fas fa-chart-bar"></i>
                    <span>ANALYTICS</span>
                </li>
            </ul>
        </nav>

        <!-- Right Sidebar Ads -->
        <aside class="ads-sidebar">
            <div class="ad-container ad-medium-rectangle">
                <div class="ad-label">ADVERTISEMENT</div>
                <div class="ad-placeholder" id="ad-sidebar-300x250">
                    <!-- Google Ad 300x250 Medium Rectangle -->
                            <ins class="adsbygoogle"
                         style="display:inline-block;width:300px;height:250px"
                         data-ad-client="ca-pub-5504723671860633"
                         data-ad-slot="XXXXXXXXX"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="terminal-main">
            <!-- Overview Section -->
            <section id="overview" class="content-section active">
                <!-- Key Metrics Cards -->
                <div class="metrics-grid">
                    <?php foreach ($key_metrics as $metric): ?>
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label"><?php echo $metric['label']; ?></span>
                            <span class="metric-status status-<?php echo $metric['status']; ?>">
                                <?php if ($metric['status'] === 'up'): ?>
                                    <i class="fas fa-arrow-up"></i>
                                <?php elseif ($metric['status'] === 'down'): ?>
                                    <i class="fas fa-arrow-down"></i>
                                <?php else: ?>
                                    <i class="fas fa-minus"></i>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="metric-value"><?php echo $metric['value']; ?></div>
                        <div class="metric-change change-<?php echo $metric['status']; ?>"><?php echo $metric['change']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Ad Break: Overview Section -->
                <div class="ad-container ad-panel-break">
                    <div class="ad-label">SPONSORED</div>
                    <div class="ad-placeholder" id="ad-overview-320x100">
                        <!-- Google Ad 320x100 Large Mobile Banner -->
                                    <ins class="adsbygoogle"
                             style="display:inline-block;width:320px;height:100px"
                             data-ad-client="ca-pub-5504723671860633"
                             data-ad-slot="XXXXXXXXX"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>

                <!-- Market Overview Chart -->
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">MARKET TRAJECTORY (2024-2034)</h2>
                        <div class="panel-controls">
                            <button class="panel-btn active" data-timeframe="10y">10Y</button>
                            <button class="panel-btn" data-timeframe="5y">5Y</button>
                            <button class="panel-btn" data-timeframe="1y">1Y</button>
                        </div>
                    </div>
                    <div class="panel-content">
                        <div class="trajectory-display">
                            <div class="trajectory-item">
                                <span class="trajectory-label">CURRENT VALUATION</span>
                                <span class="trajectory-value">$<?php echo number_format($market_data['current_market_size'], 1); ?>B</span>
                            </div>
                            <div class="trajectory-arrow">
                                <i class="fas fa-arrow-right"></i>
                                <span class="cagr-display"><?php echo $market_data['cagr']; ?>% CAGR</span>
                            </div>
                            <div class="trajectory-item">
                                <span class="trajectory-label">2034 PROJECTION</span>
                                <span class="trajectory-value projected">$<?php echo number_format($market_data['projected_market_size'], 1); ?>B</span>
                            </div>
                        </div>
                        <div class="growth-bar">
                            <div class="growth-progress" style="width: <?php echo ($market_data['current_market_size'] / $market_data['projected_market_size']) * 100; ?>%"></div>
                        </div>
                    </div>
                </div>

                <!-- Executive Summary Panel -->
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">EXECUTIVE INTELLIGENCE</h2>
                    </div>
                    <div class="panel-content">
                        <div class="intelligence-summary">
                            <div class="summary-point">
                                <i class="fas fa-bullseye"></i>
                                <span>Global medicinal plant market experiencing profound resurgence driven by aging population and natural health preferences</span>
                            </div>
                            <div class="summary-point">
                                <i class="fas fa-trending-up"></i>
                                <span>Market valued at $410.3B (2024) → $890.7B projection (2034) with 8.1% CAGR growth trajectory</span>
                            </div>
                            <div class="summary-point">
                                <i class="fas fa-balance-scale"></i>
                                <span>Critical tension between rising demand and over-exploitation risks requiring cultivation focus</span>
                            </div>
                            <div class="summary-point">
                                <i class="fas fa-microscope"></i>
                                <span>Success requires technology integration, quality assurance (GACP/GMP), and collaborative farming models</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Markets Section -->
            <section id="markets" class="content-section">
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">REGIONAL MARKET ANALYSIS</h2>
                    </div>
                    <div class="panel-content">
                        <div class="market-table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>REGION</th>
                                        <th>MARKET SHARE</th>
                                        <th>GROWTH RATE</th>
                                        <th>KEY MARKETS</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($regional_data as $region): ?>
                                    <tr>
                                        <td class="region-name"><?php echo $region['region']; ?></td>
                                        <td class="market-share"><?php echo $region['market_share']; ?>%</td>
                                        <td class="growth-rate positive">+<?php echo $region['growth_rate']; ?>%</td>
                                        <td class="key-markets"><?php echo $region['key_markets']; ?></td>
                                        <td class="status-cell">
                                            <?php if ($region['growth_rate'] >= 8.5): ?>
                                                <span class="status-badge high">HIGH</span>
                                            <?php elseif ($region['growth_rate'] >= 7.5): ?>
                                                <span class="status-badge medium">MED</span>
                                            <?php else: ?>
                                                <span class="status-badge low">LOW</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Ad Break: Markets Section -->
                <div class="ad-container ad-panel-break">
                    <div class="ad-label">SPONSORED</div>
                    <div class="ad-placeholder" id="ad-markets-320x100">
                        <!-- Google Ad 320x100 Large Mobile Banner -->
                                    <ins class="adsbygoogle"
                             style="display:inline-block;width:320px;height:100px"
                             data-ad-client="ca-pub-5504723671860633"
                             data-ad-slot="XXXXXXXXX"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>

                <!-- Interactive Analytics Dashboard -->
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">LIVE MARKET ANALYTICS</h2>
                        <div class="panel-controls">
                            <button class="panel-btn active" data-chart-period="ytd">YTD</button>
                            <button class="panel-btn" data-chart-period="1y">1Y</button>
                            <button class="panel-btn" data-chart-period="5y">5Y</button>
                        </div>
                    </div>
                    <div class="panel-content">
                        <div class="analytics-grid">
                            <!-- Market Growth Trend Chart -->
                            <div class="chart-container">
                                <div class="chart-header">
                                    <h4>MARKET GROWTH TRAJECTORY</h4>
                                    <span class="chart-value" id="growth-value">8.1% CAGR</span>
                                </div>
                                <div class="chart-wrapper">
                                    <div class="chart-loading">Loading chart data...</div>
                                    <canvas id="growthTrendChart" style="display: none;"></canvas>
                                </div>
                            </div>

                            <!-- Regional Distribution Chart -->
                            <div class="chart-container">
                                <div class="chart-header">
                                    <h4>REGIONAL MARKET SHARE</h4>
                                    <span class="chart-value" id="regional-leader">Europe Leading</span>
                                </div>
                                <div class="chart-wrapper">
                                    <div class="chart-loading">Loading chart data...</div>
                                    <canvas id="regionalDistributionChart" style="display: none;"></canvas>
                                </div>
                            </div>

                            <!-- Growth Rate Comparison -->
                            <div class="chart-container">
                                <div class="chart-header">
                                    <h4>GROWTH RATE BY REGION</h4>
                                    <span class="chart-value" id="fastest-growth">Asia-Pacific +9.2%</span>
                                </div>
                                <div class="chart-wrapper">
                                    <div class="chart-loading">Loading chart data...</div>
                                    <canvas id="growthComparisonChart" style="display: none;"></canvas>
                                </div>
                            </div>

                            <!-- Market Segments Performance -->
                            <div class="chart-container">
                                <div class="chart-header">
                                    <h4>SEGMENT PERFORMANCE</h4>
                                    <span class="chart-value" id="top-segment">Raw Materials +8.3%</span>
                                </div>
                                <div class="chart-wrapper">
                                    <div class="chart-loading">Loading chart data...</div>
                                    <canvas id="segmentPerformanceChart" style="display: none;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Market Dynamics Panel -->
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">MARKET DRIVERS & TRENDS</h2>
                    </div>
                    <div class="panel-content">
                        <div class="drivers-grid">
                            <div class="driver-card">
                                <div class="driver-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3>DEMOGRAPHIC SHIFT</h3>
                                <p>Aging global population + Gen Z/Millennial preference for natural remedies driving sustained demand growth</p>
                            </div>
                            <div class="driver-card">
                                <div class="driver-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h3>PREVENTIVE HEALTHCARE</h3>
                                <p>Rising chronic disease prevalence leading consumers toward alternative/complementary long-term therapies</p>
                            </div>
                            <div class="driver-card">
                                <div class="driver-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <h3>CLEAN LABEL TREND</h3>
                                <p>Strong push toward transparent sourcing, synthetic-free products, and targeted health formulations</p>
                            </div>
                            <div class="driver-card">
                                <div class="driver-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h3>E-COMMERCE EXPANSION</h3>
                                <p>Digital platforms breaking geographical barriers, enabling direct brand-consumer connections globally</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Species Section -->
            <section id="species" class="content-section">
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">STRATEGIC SPECIES INTELLIGENCE</h2>
                    </div>
                    <div class="panel-content">
                        <div class="species-table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>SPECIES</th>
                                        <th>MARKET POSITION</th>
                                        <th>GROWTH OUTLOOK</th>
                                        <th>CULTIVATION TIME</th>
                                        <th>INVESTMENT GRADE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($species_data as $species): ?>
                                    <tr>
                                        <td class="species-name"><?php echo $species['name']; ?></td>
                                        <td class="market-position">
                                            <?php 
                                            $position_class = strtolower(str_replace(' ', '-', $species['market_position']));
                                            echo '<span class="position-badge ' . $position_class . '">' . $species['market_position'] . '</span>';
                                            ?>
                                        </td>
                                        <td class="growth-outlook">
                                            <?php 
                                            $growth_class = strtolower($species['growth']);
                                            $growth_icon = $species['growth'] === 'High' ? 'fa-arrow-up' : ($species['growth'] === 'Moderate' ? 'fa-arrow-right' : 'fa-minus');
                                            echo '<i class="fas ' . $growth_icon . ' ' . $growth_class . '"></i> ' . $species['growth'];
                                            ?>
                                        </td>
                                        <td class="cultivation-time"><?php echo $species['cultivation_time']; ?></td>
                                        <td class="investment-grade">
                                            <?php 
                                            if ($species['market_position'] === 'Premium' && $species['growth'] === 'High') {
                                                echo '<span class="grade-badge a">A+</span>';
                                            } elseif ($species['growth'] === 'High') {
                                                echo '<span class="grade-badge b">A</span>';
                                            } else {
                                                echo '<span class="grade-badge c">B+</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Ad Break: Species Section -->
                <div class="ad-container ad-panel-break">
                    <div class="ad-label">SPONSORED</div>
                    <div class="ad-placeholder" id="ad-species-320x100">
                        <!-- Google Ad 320x100 Large Mobile Banner -->
                                    <ins class="adsbygoogle"
                             style="display:inline-block;width:320px;height:100px"
                             data-ad-client="ca-pub-5504723671860633"
                             data-ad-slot="XXXXXXXXX"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>

                <!-- Commercial Applications Panel -->
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">VALUE CHAIN APPLICATIONS</h2>
                    </div>
                    <div class="panel-content">
                        <div class="applications-grid">
                            <div class="application-card">
                                <div class="app-header">
                                    <i class="fas fa-pills"></i>
                                    <h3>PHARMACEUTICALS</h3>
                                    <span class="market-share-badge">39%</span>
                                </div>
                                <p>40+ % of Western drugs derived from traditional medicinal plants. Modern examples: paclitaxel (yew tree), artemisinin (Artemisia annua)</p>
                            </div>
                            <div class="application-card">
                                <div class="app-header">
                                    <i class="fas fa-dumbbell"></i>
                                    <h3>NUTRACEUTICALS</h3>
                                    <span class="market-share-badge">HIGH</span>
                                </div>
                                <p>Rapidly expanding hybrid nutrition-medicine segment focused on preventive health, immunity boosting, and chronic condition management</p>
                            </div>
                            <div class="application-card">
                                <div class="app-header">
                                    <i class="fas fa-spa"></i>
                                    <h3>COSMECEUTICALS</h3>
                                    <span class="market-share-badge">GROW</span>
                                </div>
                                <p>Natural beauty products utilizing plant bioactive components (Neem, Aloe Vera, Henna) for enhanced appearance without synthetic side effects</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Supply Chain Section -->
            <section id="supply" class="content-section">
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">SUPPLY CHAIN RISK ANALYSIS</h2>
                    </div>
                    <div class="panel-content">
                        <div class="risk-indicators">
                            <div class="risk-item">
                                <div class="risk-status critical">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>CRITICAL</span>
                                </div>
                                <div class="risk-details">
                                    <h4>WILD HARVESTING DEPLETION</h4>
                                    <p>Over-exploitation threatening biodiversity and long-term supply stability. Shift to cultivation required.</p>
                                </div>
                            </div>
                            <div class="risk-item">
                                <div class="risk-status high">
                                    <i class="fas fa-thermometer-half"></i>
                                    <span>HIGH</span>
                                </div>
                                <div class="risk-details">
                                    <h4>TEMPERATURE-SENSITIVE LOGISTICS</h4>
                                    <p>$35B annual losses in pharmaceutical industry due to temperature-controlled logistics failures.</p>
                                </div>
                            </div>
                            <div class="risk-item">
                                <div class="risk-status medium">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span>MEDIUM</span>
                                </div>
                                <div class="risk-details">
                                    <h4>REGULATORY FRAGMENTATION</h4>
                                    <p>Complex, fragmented regulatory environment creating barriers especially for smaller producers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ad Break: Supply Chain Section -->
                <div class="ad-container ad-panel-break">
                    <div class="ad-label">SPONSORED</div>
                    <div class="ad-placeholder" id="ad-supply-320x100">
                        <!-- Google Ad 320x100 Large Mobile Banner -->
                                    <ins class="adsbygoogle"
                             style="display:inline-block;width:320px;height:100px"
                             data-ad-client="ca-pub-5504723671860633"
                             data-ad-slot="XXXXXXXXX"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>

                <!-- Quality Standards Panel -->
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">QUALITY ASSURANCE FRAMEWORKS</h2>
                    </div>
                    <div class="panel-content">
                        <div class="standards-comparison">
                            <div class="standard-card">
                                <h3>GACP</h3>
                                <span class="standard-subtitle">Good Agricultural & Collection Practices</span>
                                <ul>
                                    <li>Raw material cultivation & harvesting</li>
                                    <li>Site selection, soil quality control</li>
                                    <li>Contamination prevention at source</li>
                                    <li>Voluntary but market-demanded</li>
                                </ul>
                            </div>
                            <div class="standard-divider">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="standard-card">
                                <h3>GMP</h3>
                                <span class="standard-subtitle">Good Manufacturing Practices</span>
                                <ul>
                                    <li>Processing & manufacturing of products</li>
                                    <li>Raw material testing, process validation</li>
                                    <li>Sanitation, traceability systems</li>
                                    <li>Mandatory for market credibility</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Outlook Section -->
            <section id="outlook" class="content-section">
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">STRATEGIC RECOMMENDATIONS</h2>
                    </div>
                    <div class="panel-content">
                        <div class="recommendations-grid">
                            <div class="recommendation-card stakeholder-large">
                                <h3><i class="fas fa-building"></i> LARGE COMPANIES & INVESTORS</h3>
                                <ul>
                                    <li>Shift focus from short-term profit to long-term sustainability</li>
                                    <li>Forge equitable partnerships with smallholder farmers</li>
                                    <li>Invest in capacity-building programs for GACP/GMP compliance</li>
                                    <li>Build secure, traceable supply chains</li>
                                </ul>
                            </div>
                            <div class="recommendation-card stakeholder-cultivator">
                                <h3><i class="fas fa-seedling"></i> CULTIVATORS & NEW ENTRANTS</h3>
                                <ul>
                                    <li>Strategic focus on quality over quantity</li>
                                    <li>Identify market niches (culinary herbs, specialized extracts)</li>
                                    <li>Explore cooperative models for market access</li>
                                    <li>View quality assurance as strategic investment</li>
                                </ul>
                            </div>
                            <div class="recommendation-card stakeholder-tech">
                                <h3><i class="fas fa-microchip"></i> TECHNOLOGY INTEGRATION</h3>
                                <ul>
                                    <li>Adopt precision agriculture technologies</li>
                                    <li>Implement IoT sensors for crop monitoring</li>
                                    <li>Utilize plant biotechnology for enhancement</li>
                                    <li>Bridge traditional knowledge with modern science</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ad Break: Outlook Section -->
                <div class="ad-container ad-panel-break">
                    <div class="ad-label">SPONSORED</div>
                    <div class="ad-placeholder" id="ad-outlook-320x100">
                        <!-- Google Ad 320x100 Large Mobile Banner -->
                                    <ins class="adsbygoogle"
                             style="display:inline-block;width:320px;height:100px"
                             data-ad-client="ca-pub-5504723671860633"
                             data-ad-slot="XXXXXXXXX"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>

                <!-- Future Outlook Panel -->
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">INNOVATION FRONTIER</h2>
                    </div>
                    <div class="panel-content">
                        <div class="innovation-timeline">
                            <div class="timeline-item current">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>2024-2025</h4>
                                    <p>Digital health platforms & e-commerce expansion breaking geographical barriers</p>
                                </div>
                            </div>
                            <div class="timeline-item near">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>2026-2028</h4>
                                    <p>Precision agriculture adoption for optimized resource allocation and consistent quality</p>
                                </div>
                            </div>
                            <div class="timeline-item future">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>2029-2034</h4>
                                    <p>Advanced biotechnology for controlled compound production and species conservation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Analytics Section -->
            <section id="analytics" class="content-section">
                <div class="panel-container">
                    <div class="panel-header">
                        <h2 class="panel-title">LIVE MARKET ANALYTICS</h2>
                    </div>
                    <div class="panel-content">
                        <div class="analytics-dashboard">
                            <div class="analytics-card">
                                <h4>MARKET MOMENTUM</h4>
                                <div class="momentum-gauge">
                                    <div class="gauge-value">8.1%</div>
                                    <div class="gauge-label">CAGR Growth</div>
                                </div>
                            </div>
                            <div class="analytics-card">
                                <h4>INVESTMENT FLOW</h4>
                                <div class="flow-indicator positive">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>HIGH INFLOW</span>
                                </div>
                            </div>
                            <div class="analytics-card">
                                <h4>REGULATORY CLIMATE</h4>
                                <div class="climate-status">
                                    <span class="status-dot amber"></span>
                                    <span>EVOLVING</span>
                                </div>
                            </div>
                            <div class="analytics-card">
                                <h4>SUPPLY STABILITY</h4>
                                <div class="stability-meter">
                                    <div class="meter-bar" style="width: 65%"></div>
                                    <span>65% STABLE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Footer Banner Ad -->
    <div class="ad-container ad-footer-banner">
        <div class="ad-label">SPONSORED</div>
        <div class="ad-placeholder" id="ad-footer-728x90">
            <!-- Google Ad 728x90 Leaderboard -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:728px;height:90px"
                 data-ad-client="ca-pub-5504723671860633"
                 data-ad-slot="XXXXXXXXX"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>

    <!-- Floating Ad (Sticky) -->
    <div class="ad-container ad-floating" id="floating-ad">
        <button class="ad-close-btn" onclick="closeFloatingAd()">&times;</button>
        <div class="ad-label">AD</div>
        <div class="ad-placeholder" id="ad-floating-300x250">
            <!-- Google Ad 300x250 Medium Rectangle -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:300px;height:250px"
                 data-ad-client="ca-pub-5504723671860633"
                 data-ad-slot="XXXXXXXXX"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>

    <!-- Terminal Footer -->
    <footer class="terminal-footer">
        <div class="footer-content">
            <div class="footer-left">
                <span>MAP TERMINAL © 2025 Quartermasters FZC</span>
                <span class="separator">|</span>
                <span>Medicinal & Aromatic Plants Intelligence Platform</span>
            </div>
            <div class="footer-right">
                <span>All Rights Reserved | Developed by Quartermasters FZC</span>
            </div>
        </div>
    </footer>

    <!-- JavaScript - Loaded in proper order for webserver compatibility -->
    <script>
        // Enhanced Chart.js loading detection with debug logging
        console.log('Starting Chart.js detection...');
        let chartLoadCheckInterval;
        let chartLoadAttempts = 0;
        const maxChartLoadAttempts = 30; // 15 seconds
        
        function checkChartLoading() {
            chartLoadAttempts++;
            console.log(`Chart.js check attempt ${chartLoadAttempts}/${maxChartLoadAttempts}...`);
            
            if (typeof Chart !== 'undefined') {
                console.log('Chart.js loaded successfully, version:', Chart.version);
                clearInterval(chartLoadCheckInterval);
                initializeTerminal();
            } else if (chartLoadAttempts >= maxChartLoadAttempts) {
                console.warn('Chart.js failed to load after 15 seconds, proceeding with terminal initialization');
                clearInterval(chartLoadCheckInterval);
                initializeTerminal();
            }
        }
        
        function initializeTerminal() {
            console.log('Initializing MAP Terminal...');
            // Initialize terminal after DOM and Chart.js are ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    new MAPTerminal();
                });
            } else {
                new MAPTerminal();
            }
        }
        
        // Start checking for Chart.js after a brief delay
        setTimeout(() => {
            chartLoadCheckInterval = setInterval(checkChartLoading, 500);
        }, 100);

        // Google Ads Integration Functions
        function closeFloatingAd() {
            const floatingAd = document.getElementById('floating-ad');
            if (floatingAd) {
                floatingAd.style.display = 'none';
                localStorage.setItem('floatingAdClosed', 'true');
            }
        }

        function showFloatingAd() {
            const floatingAd = document.getElementById('floating-ad');
            const adClosed = localStorage.getItem('floatingAdClosed');
            
            if (floatingAd && !adClosed) {
                floatingAd.classList.add('show');
            }
        }

        // Show floating ad after 30 seconds of page load
        setTimeout(showFloatingAd, 30000);

        // Reset floating ad closure on session change (optional)
        window.addEventListener('beforeunload', function() {
            localStorage.removeItem('floatingAdClosed');
        });

        // AdSense optimization - Lazy load ads below the fold
        function initLazyAds() {
            const adsContainers = document.querySelectorAll('.ad-container');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const adContainer = entry.target;
                        // Trigger ad loading when container comes into view
                        if (typeof adsbygoogle !== 'undefined') {
                            try {
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            } catch (e) {
                                console.log('AdSense error:', e);
                            }
                        }
                        observer.unobserve(adContainer);
                    }
                });
            }, { 
                rootMargin: '100px',
                threshold: 0.1 
            });

            adsContainers.forEach(container => {
                observer.observe(container);
            });
        }

        // Initialize lazy ad loading when DOM is ready
        document.addEventListener('DOMContentLoaded', initLazyAds);
    </script>
    
    <!-- Google Ads Configuration -->
    <script src="ads-config.js"></script>
    
    <!-- Backup Charts System -->
    <script src="backup-charts.js"></script>
    
    <!-- Main Terminal JavaScript -->
    <script src="assets/js/terminal.js"></script>
</body>
</html>