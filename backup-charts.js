/**
 * Backup Chart System - Pure CSS/HTML Charts
 * Used when Chart.js fails to load on webserver
 */

class BackupCharts {
    static createSimpleCharts() {
        console.log('Creating backup CSS charts...');
        
        // Growth Trend Chart (Simple Line)
        this.createGrowthTrendBackup();
        
        // Regional Distribution (Simple Bars)
        this.createRegionalDistributionBackup();
        
        // Growth Comparison (Simple Progress Bars)
        this.createGrowthComparisonBackup();
        
        // Segment Performance (Simple Radar-style)
        this.createSegmentPerformanceBackup();
    }

    static createGrowthTrendBackup() {
        const container = document.getElementById('growthTrendChart')?.parentElement;
        if (!container) return;
        
        container.innerHTML = `
            <div class="backup-chart">
                <div class="backup-chart-title">Market Growth (2020-2034)</div>
                <div class="line-chart">
                    <div class="line-point" style="left: 0%; bottom: 20%;" data-value="298.5B"></div>
                    <div class="line-point" style="left: 14%; bottom: 30%;" data-value="323.2B"></div>
                    <div class="line-point" style="left: 28%; bottom: 40%;" data-value="349.8B"></div>
                    <div class="line-point" style="left: 42%; bottom: 50%;" data-value="378.6B"></div>
                    <div class="line-point active" style="left: 56%; bottom: 60%;" data-value="410.3B"></div>
                    <div class="line-point projected" style="left: 70%; bottom: 70%;" data-value="522.1B"></div>
                    <div class="line-point projected" style="left: 84%; bottom: 80%;" data-value="663.0B"></div>
                    <div class="line-point projected" style="left: 100%; bottom: 90%;" data-value="890.7B"></div>
                    <div class="growth-line"></div>
                </div>
                <div class="chart-legend">
                    <span class="legend-item">
                        <span class="legend-dot historical"></span>Historical
                    </span>
                    <span class="legend-item">
                        <span class="legend-dot projected"></span>Projected
                    </span>
                </div>
            </div>
        `;
    }

    static createRegionalDistributionBackup() {
        const container = document.getElementById('regionalDistributionChart')?.parentElement;
        if (!container) return;
        
        container.innerHTML = `
            <div class="backup-chart">
                <div class="backup-chart-title">Regional Market Share</div>
                <div class="pie-chart">
                    <div class="pie-slice" style="--percentage: 44.55; --color: #ff6b35;" data-label="Europe"></div>
                    <div class="pie-slice" style="--percentage: 32.1; --color: #28a745;" data-label="Asia-Pacific"></div>
                    <div class="pie-slice" style="--percentage: 18.3; --color: #17a2b8;" data-label="N. America"></div>
                    <div class="pie-slice" style="--percentage: 5.05; --color: #ffc107;" data-label="Others"></div>
                </div>
                <div class="pie-labels">
                    <div class="pie-label"><span style="background: #ff6b35;"></span>Europe 44.6%</div>
                    <div class="pie-label"><span style="background: #28a745;"></span>Asia-Pacific 32.1%</div>
                    <div class="pie-label"><span style="background: #17a2b8;"></span>N. America 18.3%</div>
                    <div class="pie-label"><span style="background: #ffc107;"></span>Others 5.1%</div>
                </div>
            </div>
        `;
    }

    static createGrowthComparisonBackup() {
        const container = document.getElementById('growthComparisonChart')?.parentElement;
        if (!container) return;
        
        container.innerHTML = `
            <div class="backup-chart">
                <div class="backup-chart-title">Growth Rate by Region</div>
                <div class="bar-chart">
                    <div class="bar-item">
                        <div class="bar-label">Europe</div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: 78%; background: #ff6b35;"></div>
                        </div>
                        <div class="bar-value">7.8%</div>
                    </div>
                    <div class="bar-item">
                        <div class="bar-label">Asia-Pacific</div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: 92%; background: #28a745;"></div>
                        </div>
                        <div class="bar-value">9.2%</div>
                    </div>
                    <div class="bar-item">
                        <div class="bar-label">N. America</div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: 85%; background: #17a2b8;"></div>
                        </div>
                        <div class="bar-value">8.5%</div>
                    </div>
                    <div class="bar-item">
                        <div class="bar-label">Others</div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: 69%; background: #ffc107;"></div>
                        </div>
                        <div class="bar-value">6.9%</div>
                    </div>
                </div>
            </div>
        `;
    }

    static createSegmentPerformanceBackup() {
        const container = document.getElementById('segmentPerformanceChart')?.parentElement;
        if (!container) return;
        
        container.innerHTML = `
            <div class="backup-chart">
                <div class="backup-chart-title">Segment Performance</div>
                <div class="radar-chart">
                    <div class="radar-segment">
                        <div class="segment-name">Raw Materials</div>
                        <div class="segment-bars">
                            <div class="segment-bar size" style="width: 90%;">$185.6B</div>
                            <div class="segment-bar growth" style="width: 83%;">8.3%</div>
                        </div>
                    </div>
                    <div class="radar-segment">
                        <div class="segment-name">Extracts</div>
                        <div class="segment-bars">
                            <div class="segment-bar size" style="width: 48%;">$98.4B</div>
                            <div class="segment-bar growth" style="width: 79%;">7.9%</div>
                        </div>
                    </div>
                    <div class="radar-segment">
                        <div class="segment-name">Essential Oils</div>
                        <div class="segment-bars">
                            <div class="segment-bar size" style="width: 32%;">$67.2B</div>
                            <div class="segment-bar growth" style="width: 81%;">8.1%</div>
                        </div>
                    </div>
                    <div class="radar-segment">
                        <div class="segment-name">Finished Products</div>
                        <div class="segment-bars">
                            <div class="segment-bar size" style="width: 28%;">$59.1B</div>
                            <div class="segment-bar growth" style="width: 76%;">7.6%</div>
                        </div>
                    </div>
                </div>
                <div class="radar-legend">
                    <span><span class="legend-bar size"></span>Market Size</span>
                    <span><span class="legend-bar growth"></span>Growth Rate</span>
                </div>
            </div>
        `;
    }
}

// Auto-initialize backup charts if Chart.js fails
setTimeout(() => {
    if (typeof Chart === 'undefined') {
        console.log('Chart.js not available, using backup charts');
        BackupCharts.createSimpleCharts();
    }
}, 3000);