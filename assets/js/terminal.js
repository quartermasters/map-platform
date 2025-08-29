/**
 * MAP Terminal - Interactive JavaScript
 * Bloomberg-style Medicinal & Aromatic Plants Intelligence Platform
 */

class MAPTerminal {
    constructor() {
        this.currentSection = 'overview';
        this.charts = {};
        this.chartData = this.initChartData();
        this.init();
    }

    init() {
        this.initNavigation();
        this.initClock();
        this.initCounters();
        this.initPanelControls();
        this.initDataTables();
        this.initAnalytics();
        this.initInteractiveCharts();
    }

    initChartData() {
        return {
            marketGrowth: {
                labels: ['2020', '2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033', '2034'],
                data: [298.5, 323.2, 349.8, 378.6, 410.3, 444.7, 481.9, 522.1, 565.5, 612.4, 663.0, 717.6, 776.4, 839.8, 890.7],
                projectedStart: 4 // Index where projection starts
            },
            regionalData: {
                labels: ['Europe', 'Asia-Pacific', 'North America', 'Others'],
                data: [44.55, 32.1, 18.3, 5.05],
                colors: ['#ff6b35', '#28a745', '#17a2b8', '#ffc107'],
                growth: [7.8, 9.2, 8.5, 6.9]
            },
            segmentData: {
                labels: ['Raw Materials', 'Extracts', 'Essential Oils', 'Finished Products'],
                data: [185.6, 98.4, 67.2, 59.1],
                growth: [8.3, 7.9, 8.1, 7.6]
            }
        };
    }

    // Navigation System
    initNavigation() {
        const navItems = document.querySelectorAll('.nav-item');
        const contentSections = document.querySelectorAll('.content-section');

        navItems.forEach(item => {
            item.addEventListener('click', () => {
                const sectionId = item.getAttribute('data-section');
                this.navigateToSection(sectionId, navItems, contentSections);
            });
        });
    }

    navigateToSection(sectionId, navItems, contentSections) {
        // Remove active states
        navItems.forEach(item => item.classList.remove('active'));
        contentSections.forEach(section => section.classList.remove('active'));

        // Add active states
        const targetNavItem = document.querySelector(`[data-section="${sectionId}"]`);
        const targetSection = document.getElementById(sectionId);

        if (targetNavItem && targetSection) {
            targetNavItem.classList.add('active');
            targetSection.classList.add('active');
            this.currentSection = sectionId;

            // Trigger section-specific animations
            this.animateSection(targetSection);
        }
    }

    animateSection(section) {
        // Reset and trigger animations for metric cards
        const metricCards = section.querySelectorAll('.metric-card');
        metricCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.animation = 'none';
            setTimeout(() => {
                card.style.animation = 'fadeIn 0.5s ease forwards';
            }, 10);
        });

        // Animate data tables
        const dataRows = section.querySelectorAll('.data-table tbody tr');
        dataRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.style.animation = 'none';
            setTimeout(() => {
                row.style.animation = 'fadeIn 0.3s ease forwards';
            }, 10);
        });

        // Animate progress bars
        const progressBars = section.querySelectorAll('.growth-progress, .meter-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 300);
        });
    }

    // Live Clock
    initClock() {
        const clockElement = document.getElementById('terminal-clock');
        if (clockElement) {
            this.updateClock(clockElement);
            setInterval(() => this.updateClock(clockElement), 1000);
        }
    }

    updateClock(clockElement) {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour12: false,
            timeZoneName: 'short'
        });
        clockElement.textContent = timeString;
    }

    // Counter Animations
    initCounters() {
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe metric values and trajectory values
        document.querySelectorAll('.metric-value, .trajectory-value, .gauge-value').forEach(el => {
            observer.observe(el);
        });
    }

    animateCounter(element) {
        const text = element.textContent;
        const numberMatch = text.match(/[\d,.]+/);
        
        if (numberMatch) {
            const targetNumber = parseFloat(numberMatch[0].replace(/,/g, ''));
            const prefix = text.substring(0, text.indexOf(numberMatch[0]));
            const suffix = text.substring(text.indexOf(numberMatch[0]) + numberMatch[0].length);
            
            let currentNumber = 0;
            const increment = targetNumber / 50;
            const duration = 2000;
            const stepTime = duration / 50;
            
            const timer = setInterval(() => {
                currentNumber += increment;
                if (currentNumber >= targetNumber) {
                    currentNumber = targetNumber;
                    clearInterval(timer);
                }
                
                let displayNumber = currentNumber.toFixed(1);
                if (targetNumber >= 1000) {
                    displayNumber = this.formatLargeNumber(currentNumber);
                }
                
                element.textContent = prefix + displayNumber + suffix;
            }, stepTime);
        }
    }

    formatLargeNumber(num) {
        if (num >= 1000000000) {
            return (num / 1000000000).toFixed(1) + 'B';
        } else if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toFixed(1);
    }

    // Panel Controls
    initPanelControls() {
        const panelButtons = document.querySelectorAll('.panel-btn');
        
        panelButtons.forEach(button => {
            button.addEventListener('click', () => {
                const timeframe = button.getAttribute('data-timeframe');
                this.handleTimeframeChange(button, timeframe);
            });
        });
    }

    handleTimeframeChange(clickedButton, timeframe) {
        // Remove active state from siblings
        const siblings = clickedButton.parentElement.querySelectorAll('.panel-btn');
        siblings.forEach(btn => btn.classList.remove('active'));
        
        // Add active state to clicked button
        clickedButton.classList.add('active');
        
        // Update data based on timeframe (placeholder functionality)
        this.updateChartData(timeframe);
    }

    updateChartData(timeframe) {
        // Placeholder for chart data updates
        // In a real implementation, this would fetch new data and update visualizations
        console.log(`Updating chart data for timeframe: ${timeframe}`);
        
        // Simulate data loading
        const loadingElements = document.querySelectorAll('.trajectory-value');
        loadingElements.forEach(el => {
            el.style.opacity = '0.5';
            setTimeout(() => {
                el.style.opacity = '1';
            }, 500);
        });
    }

    // Data Table Functionality
    initDataTables() {
        const tables = document.querySelectorAll('.data-table');
        
        tables.forEach(table => {
            const headers = table.querySelectorAll('th');
            headers.forEach((header, index) => {
                header.addEventListener('click', () => {
                    this.sortTable(table, index);
                });
                header.style.cursor = 'pointer';
                header.title = 'Click to sort';
            });
        });

        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.transform = 'translateX(4px)';
                row.style.borderLeft = '3px solid var(--orange-primary)';
            });
            
            row.addEventListener('mouseleave', () => {
                row.style.transform = 'translateX(0)';
                row.style.borderLeft = 'none';
            });
        });
    }

    sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        // Determine sort direction
        const isAscending = tbody.getAttribute('data-sort-direction') !== 'asc';
        tbody.setAttribute('data-sort-direction', isAscending ? 'asc' : 'desc');
        
        // Sort rows
        rows.sort((a, b) => {
            const aText = a.cells[columnIndex].textContent.trim();
            const bText = b.cells[columnIndex].textContent.trim();
            
            // Try to parse as numbers
            const aNum = parseFloat(aText.replace(/[^0-9.-]/g, ''));
            const bNum = parseFloat(bText.replace(/[^0-9.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return isAscending ? aNum - bNum : bNum - aNum;
            } else {
                return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
            }
        });
        
        // Reappend sorted rows
        rows.forEach(row => tbody.appendChild(row));
        
        // Add visual feedback
        this.animateSortedTable(tbody);
    }

    animateSortedTable(tbody) {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.style.animation = 'none';
            setTimeout(() => {
                row.style.animation = `fadeIn 0.3s ease forwards`;
                row.style.animationDelay = `${index * 0.03}s`;
            }, 10);
        });
    }

    // Analytics Dashboard
    initAnalytics() {
        this.startAnalyticsUpdates();
        this.initTooltips();
    }

    startAnalyticsUpdates() {
        // Simulate real-time updates for analytics cards
        setInterval(() => {
            this.updateAnalyticsValues();
        }, 10000); // Update every 10 seconds
    }

    updateAnalyticsValues() {
        const stabilityMeter = document.querySelector('.meter-bar');
        if (stabilityMeter) {
            // Simulate small fluctuations in stability
            const currentWidth = parseFloat(stabilityMeter.style.width) || 65;
            const fluctuation = (Math.random() - 0.5) * 2; // ±1%
            const newWidth = Math.max(60, Math.min(70, currentWidth + fluctuation));
            
            stabilityMeter.style.width = `${newWidth}%`;
            
            // Update the text
            const textElement = stabilityMeter.parentElement.querySelector('span');
            if (textElement) {
                textElement.textContent = `${Math.round(newWidth)}% STABLE`;
            }
        }

        // Update market status indicator
        const statusIndicator = document.querySelector('.status-indicator');
        if (statusIndicator && Math.random() > 0.8) { // 20% chance to flicker
            statusIndicator.style.opacity = '0.3';
            setTimeout(() => {
                statusIndicator.style.opacity = '1';
            }, 200);
        }
    }

    // Tooltip System
    initTooltips() {
        const tooltipElements = document.querySelectorAll('[title]');
        
        tooltipElements.forEach(element => {
            let tooltipDiv;
            
            element.addEventListener('mouseenter', (e) => {
                const title = element.getAttribute('title');
                if (title) {
                    element.setAttribute('data-original-title', title);
                    element.removeAttribute('title');
                    
                    tooltipDiv = document.createElement('div');
                    tooltipDiv.className = 'custom-tooltip';
                    tooltipDiv.textContent = title;
                    tooltipDiv.style.cssText = `
                        position: absolute;
                        background: var(--bg-tertiary);
                        border: 1px solid var(--border-color);
                        color: var(--text-primary);
                        padding: 8px 12px;
                        border-radius: 4px;
                        font-size: 12px;
                        z-index: 10000;
                        pointer-events: none;
                        box-shadow: var(--shadow-md);
                    `;
                    
                    document.body.appendChild(tooltipDiv);
                    this.positionTooltip(e, tooltipDiv);
                }
            });
            
            element.addEventListener('mousemove', (e) => {
                if (tooltipDiv) {
                    this.positionTooltip(e, tooltipDiv);
                }
            });
            
            element.addEventListener('mouseleave', () => {
                if (tooltipDiv) {
                    document.body.removeChild(tooltipDiv);
                    tooltipDiv = null;
                }
                
                const originalTitle = element.getAttribute('data-original-title');
                if (originalTitle) {
                    element.setAttribute('title', originalTitle);
                    element.removeAttribute('data-original-title');
                }
            });
        });
    }

    positionTooltip(event, tooltip) {
        const x = event.clientX + 10;
        const y = event.clientY - tooltip.offsetHeight - 10;
        
        tooltip.style.left = `${x}px`;
        tooltip.style.top = `${y}px`;
        
        // Keep tooltip within viewport
        const rect = tooltip.getBoundingClientRect();
        if (rect.right > window.innerWidth) {
            tooltip.style.left = `${event.clientX - tooltip.offsetWidth - 10}px`;
        }
        if (rect.top < 0) {
            tooltip.style.top = `${event.clientY + 10}px`;
        }
    }

    // Keyboard Navigation
    initKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            if (e.altKey) {
                const number = parseInt(e.key);
                if (number >= 1 && number <= 6) {
                    const sections = ['overview', 'markets', 'species', 'supply', 'outlook', 'analytics'];
                    const sectionId = sections[number - 1];
                    
                    const navItems = document.querySelectorAll('.nav-item');
                    const contentSections = document.querySelectorAll('.content-section');
                    
                    this.navigateToSection(sectionId, navItems, contentSections);
                    e.preventDefault();
                }
            }
        });
    }

    // Search Functionality
    initSearch() {
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Search market data...';
        searchInput.className = 'terminal-search';
        searchInput.style.cssText = `
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 8px 12px;
            border-radius: 4px;
            font-family: var(--font-mono);
            font-size: 12px;
            width: 200px;
        `;
        
        // Add search to header
        const headerRight = document.querySelector('.header-right');
        if (headerRight) {
            headerRight.appendChild(searchInput);
            
            searchInput.addEventListener('input', (e) => {
                this.performSearch(e.target.value);
            });
        }
    }

    performSearch(query) {
        if (query.length < 2) return;
        
        // Simple search implementation
        const searchableElements = document.querySelectorAll('.species-name, .region-name, .summary-point span');
        
        searchableElements.forEach(element => {
            const text = element.textContent.toLowerCase();
            const matches = text.includes(query.toLowerCase());
            
            if (matches) {
                element.style.backgroundColor = 'rgba(255, 107, 53, 0.2)';
                element.style.borderRadius = '2px';
            } else {
                element.style.backgroundColor = '';
            }
        });
    }

    // Interactive Charts System with enhanced webserver compatibility
    initInteractiveCharts() {
        console.log('Initializing interactive charts...');
        
        // Use Promise-based approach for better error handling
        this.waitForChartJS()
            .then(() => {
                console.log('Chart.js loaded successfully, version:', Chart.version);
                return this.initializeAllCharts();
            })
            .catch((error) => {
                console.error('Chart initialization failed:', error);
                this.loadFallbackCharts();
            });
    }

    waitForChartJS() {
        return new Promise((resolve, reject) => {
            // Check if Chart.js is already available
            if (typeof Chart !== 'undefined') {
                resolve();
                return;
            }

            let attempts = 0;
            const maxAttempts = 30; // 15 seconds total
            const interval = 500; // Check every 500ms

            const checkChart = () => {
                attempts++;
                console.log(`Chart.js loading check ${attempts}/${maxAttempts}...`);

                if (typeof Chart !== 'undefined') {
                    resolve();
                    return;
                }

                if (attempts >= maxAttempts) {
                    reject(new Error(`Chart.js failed to load after ${maxAttempts * interval / 1000} seconds`));
                    return;
                }

                setTimeout(checkChart, interval);
            };

            // Start checking
            setTimeout(checkChart, 100); // Initial delay
        });
    }

    initializeAllCharts() {
        return new Promise((resolve, reject) => {
            try {
                console.log('Setting up Chart.js defaults...');
                this.setupChartDefaults();
                
                // Initialize charts sequentially to avoid conflicts
                const chartInitializers = [
                    () => this.createGrowthTrendChart(),
                    () => this.createRegionalDistributionChart(), 
                    () => this.createGrowthComparisonChart(),
                    () => this.createSegmentPerformanceChart()
                ];

                let currentChart = 0;
                const initNext = () => {
                    if (currentChart < chartInitializers.length) {
                        console.log(`Initializing chart ${currentChart + 1}/${chartInitializers.length}...`);
                        try {
                            chartInitializers[currentChart]();
                            currentChart++;
                            setTimeout(initNext, 200); // Staggered initialization
                        } catch (error) {
                            console.error(`Error initializing chart ${currentChart + 1}:`, error);
                            currentChart++;
                            setTimeout(initNext, 200);
                        }
                    } else {
                        console.log('All charts initialized successfully');
                        setTimeout(() => this.initChartControls(), 100);
                        setTimeout(() => this.startRealTimeUpdates(), 200);
                        resolve();
                    }
                };

                initNext();
                
            } catch (error) {
                reject(error);
            }
        });
    }

    loadFallbackCharts() {
        console.log('Loading fallback chart system...');
        
        // First try to use backup-charts.js if available
        if (typeof BackupCharts !== 'undefined') {
            console.log('Using BackupCharts fallback system');
            BackupCharts.createSimpleCharts();
        } else {
            console.log('Creating inline fallback charts');
            this.createInlineFallbackCharts();
        }
    }

    createInlineFallbackCharts() {
        // Create simple fallback displays when both Chart.js and BackupCharts fail
        const chartContainers = {
            'growthTrendChart': 'Market Growth Trend',
            'regionalDistributionChart': 'Regional Distribution', 
            'growthComparisonChart': 'Growth Comparison',
            'segmentPerformanceChart': 'Segment Performance'
        };

        Object.entries(chartContainers).forEach(([canvasId, title]) => {
            const canvas = document.getElementById(canvasId);
            if (canvas && canvas.parentElement) {
                canvas.parentElement.innerHTML = `
                    <div class="inline-fallback-chart">
                        <div class="fallback-title">${title}</div>
                        <div class="fallback-message">
                            <i class="fas fa-chart-line"></i>
                            <p>Chart data temporarily unavailable</p>
                            <small>Please refresh to try again</small>
                        </div>
                    </div>
                `;
            }
        });
    }

    showChartError() {
        // Show error message in chart containers
        const chartContainers = document.querySelectorAll('.chart-wrapper');
        chartContainers.forEach(container => {
            container.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: center; height: 200px; color: #ff6b35; text-align: center; font-size: 12px;">
                    <div>
                        <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                        Chart.js Loading Error<br>
                        <small>Please refresh the page</small>
                    </div>
                </div>
            `;
        });
    }

    setupChartDefaults() {
        Chart.defaults.color = '#e6edf3';
        Chart.defaults.borderColor = '#30363d';
        Chart.defaults.backgroundColor = '#21262d';
        Chart.defaults.font.family = 'Source Code Pro, Monaco, Menlo, monospace';
        Chart.defaults.font.size = 11;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 15;
    }

    createGrowthTrendChart() {
        const ctx = document.getElementById('growthTrendChart');
        if (!ctx) {
            console.warn('Growth trend chart canvas not found');
            return;
        }

        // Hide loading indicator and show canvas
        const wrapper = ctx.parentElement;
        const loader = wrapper.querySelector('.chart-loading');
        if (loader) loader.style.display = 'none';
        ctx.style.display = 'block';

        try {
            const data = this.chartData.marketGrowth;
            
            this.charts.growthTrend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Historical Data',
                            data: [298.5, 323.2, 349.8, 378.6, 410.3, null, null, null, null, null, null, null, null, null, null],
                            borderColor: '#ff6b35',
                            backgroundColor: 'rgba(255, 107, 53, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ff6b35',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5
                        },
                        {
                            label: 'Projected Growth',
                            data: [null, null, null, null, 410.3, 444.7, 481.9, 522.1, 565.5, 612.4, 663.0, 717.6, 776.4, 839.8, 890.7],
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255, 193, 7, 0.1)',
                            borderWidth: 3,
                            borderDash: [10, 5],
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffc107',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#e6edf3',
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: '#21262d',
                            borderColor: '#ff6b35',
                            borderWidth: 1,
                            titleColor: '#e6edf3',
                            bodyColor: '#e6edf3',
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: $${context.parsed.y}B`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#30363d'
                            },
                            ticks: {
                                color: '#7d8590'
                            }
                        },
                        y: {
                            grid: {
                                color: '#30363d'
                            },
                            ticks: {
                                color: '#7d8590',
                                callback: function(value) {
                                    return '$' + value + 'B';
                                }
                            }
                        }
                    }
                }
            });
            console.log('Growth trend chart created successfully');
        } catch (error) {
            console.error('Error creating growth trend chart:', error);
        }
    }

    createRegionalDistributionChart() {
        const ctx = document.getElementById('regionalDistributionChart');
        if (!ctx) {
            console.warn('Regional distribution chart canvas not found');
            return;
        }

        // Hide loading indicator and show canvas
        const wrapper = ctx.parentElement;
        const loader = wrapper.querySelector('.chart-loading');
        if (loader) loader.style.display = 'none';
        ctx.style.display = 'block';

        try {
            this.charts.regionalDistribution = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Europe', 'Asia-Pacific', 'North America', 'Others'],
                    datasets: [{
                        data: [44.55, 32.1, 18.3, 5.05],
                        backgroundColor: ['#ff6b35', '#28a745', '#17a2b8', '#ffc107'],
                        borderColor: '#0d1117',
                        borderWidth: 3,
                        hoverBorderWidth: 5,
                        hoverBorderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e6edf3',
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: '#21262d',
                            borderColor: '#ff6b35',
                            borderWidth: 1,
                            titleColor: '#e6edf3',
                            bodyColor: '#e6edf3',
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed}%`;
                                }
                            }
                        }
                    }
                }
            });
            console.log('Regional distribution chart created successfully');
        } catch (error) {
            console.error('Error creating regional distribution chart:', error);
        }
    }

    createGrowthComparisonChart() {
        const ctx = document.getElementById('growthComparisonChart');
        if (!ctx) {
            console.warn('Growth comparison chart canvas not found');
            return;
        }

        // Hide loading indicator and show canvas
        const wrapper = ctx.parentElement;
        const loader = wrapper.querySelector('.chart-loading');
        if (loader) loader.style.display = 'none';
        ctx.style.display = 'block';

        try {
            this.charts.growthComparison = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Europe', 'Asia-Pacific', 'North America', 'Others'],
                    datasets: [{
                        label: 'Growth Rate (%)',
                        data: [7.8, 9.2, 8.5, 6.9],
                        backgroundColor: ['#ff6b3580', '#28a74580', '#17a2b880', '#ffc10780'],
                        borderColor: ['#ff6b35', '#28a745', '#17a2b8', '#ffc107'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#21262d',
                            borderColor: '#ff6b35',
                            borderWidth: 1,
                            titleColor: '#e6edf3',
                            bodyColor: '#e6edf3',
                            callbacks: {
                                label: function(context) {
                                    return `Growth Rate: ${context.parsed.y}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#7d8590'
                            }
                        },
                        y: {
                            grid: {
                                color: '#30363d'
                            },
                            ticks: {
                                color: '#7d8590',
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
            console.log('Growth comparison chart created successfully');
        } catch (error) {
            console.error('Error creating growth comparison chart:', error);
        }
    }

    createSegmentPerformanceChart() {
        const ctx = document.getElementById('segmentPerformanceChart');
        if (!ctx) {
            console.warn('Segment performance chart canvas not found');
            return;
        }

        // Hide loading indicator and show canvas
        const wrapper = ctx.parentElement;
        const loader = wrapper.querySelector('.chart-loading');
        if (loader) loader.style.display = 'none';
        ctx.style.display = 'block';

        try {
            this.charts.segmentPerformance = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Raw Materials', 'Extracts', 'Essential Oils', 'Finished Products'],
                    datasets: [
                        {
                            label: 'Market Size ($B)',
                            data: [185.6, 98.4, 67.2, 59.1],
                            borderColor: '#ff6b35',
                            backgroundColor: 'rgba(255, 107, 53, 0.2)',
                            pointBackgroundColor: '#ff6b35',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            borderWidth: 2
                        },
                        {
                            label: 'Growth Rate (x20)',
                            data: [166, 158, 162, 152], // 8.3*20, 7.9*20, 8.1*20, 7.6*20
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.2)',
                            pointBackgroundColor: '#28a745',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#e6edf3'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#21262d',
                            borderColor: '#ff6b35',
                            borderWidth: 1,
                            titleColor: '#e6edf3',
                            bodyColor: '#e6edf3',
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return `Market Size: $${context.parsed.r}B`;
                                    } else {
                                        return `Growth Rate: ${(context.parsed.r / 20).toFixed(1)}%`;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        r: {
                            grid: {
                                color: '#30363d'
                            },
                            pointLabels: {
                                color: '#7d8590'
                            },
                            ticks: {
                                display: false
                            }
                        }
                    }
                }
            });
            console.log('Segment performance chart created successfully');
        } catch (error) {
            console.error('Error creating segment performance chart:', error);
        }
    }

    initChartControls() {
        const chartButtons = document.querySelectorAll('[data-chart-period]');
        
        chartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const period = button.getAttribute('data-chart-period');
                this.updateChartsForPeriod(period);
                
                // Update active button
                chartButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    }

    updateChartsForPeriod(period) {
        // Simulate different data for different periods
        let dataMultiplier = 1;
        let labelSuffix = '';
        
        switch(period) {
            case 'ytd':
                dataMultiplier = 0.8;
                labelSuffix = ' (YTD)';
                break;
            case '1y':
                dataMultiplier = 0.9;
                labelSuffix = ' (1Y)';
                break;
            case '5y':
                dataMultiplier = 1.2;
                labelSuffix = ' (5Y)';
                break;
        }
        
        // Update chart value displays
        document.getElementById('growth-value').textContent = `${(8.1 * dataMultiplier).toFixed(1)}% CAGR${labelSuffix}`;
    }

    startRealTimeUpdates() {
        setInterval(() => {
            this.simulateRealTimeData();
        }, 8000); // Update every 8 seconds
    }

    simulateRealTimeData() {
        // Update live indicators with pulse animation
        const indicators = document.querySelectorAll('.chart-value');
        indicators.forEach(indicator => {
            indicator.style.animation = 'none';
            setTimeout(() => {
                indicator.style.animation = 'dataPointPulse 1s ease';
            }, 10);
        });
    }
}

// Initialize the terminal when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const terminal = new MAPTerminal();
    
    // Add some additional enhancements
    terminal.initKeyboardNavigation();
    terminal.initSearch();
    
    // Welcome message in console
    console.log(`
    ╔═══════════════════════════════════════════════════════════════════════════════════╗
    ║                                MAP TERMINAL                                       ║
    ║                   Medicinal & Aromatic Plants Intelligence                       ║
    ║                                                                                   ║
    ║  Navigation: Alt + 1-6 for quick section switching                              ║
    ║  Features: Live clock, interactive charts, sortable tables                      ║
    ║  Market Data: $410.3B → $890.7B projection (8.1% CAGR)                        ║
    ╚═══════════════════════════════════════════════════════════════════════════════════╝
    `);
});

// Export for potential module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MAPTerminal;
}