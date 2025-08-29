/**
 * Google Ads Configuration for MAP Terminal
 * Bloomberg Terminal Style Financial Platform
 */

// Google AdSense Configuration
const AdsConfig = {
    // Your actual Google AdSense Publisher ID
    publisherId: 'ca-pub-5504723671860633',
    
    // Ad Slot IDs (Replace with actual slot IDs from AdSense)
    slots: {
        headerLeaderboard: 'XXXXXXXXX',      // 728x90
        sidebarRectangle: 'XXXXXXXXX',       // 300x250
        overviewBanner: 'XXXXXXXXX',         // 320x100
        marketsBanner: 'XXXXXXXXX',          // 320x100
        speciesBanner: 'XXXXXXXXX',          // 320x100
        supplyBanner: 'XXXXXXXXX',           // 320x100
        outlookBanner: 'XXXXXXXXX',          // 320x100
        footerLeaderboard: 'XXXXXXXXX',      // 728x90
        floatingRectangle: 'XXXXXXXXX'       // 300x250
    },

    // Ad Performance Settings
    settings: {
        lazyLoad: true,                      // Enable lazy loading
        floatingAdDelay: 30000,             // 30 seconds delay for floating ad
        enableFloatingAd: true,             // Show floating ad
        respectDoNotTrack: true,            // Respect user privacy settings
        maxAdsPerPage: 8                    // Maximum ads per page
    },

    // Bloomberg Terminal Style Ad Labels
    labels: {
        sponsored: 'SPONSORED',
        advertisement: 'ADVERTISEMENT', 
        ad: 'AD'
    }
};

// Initialize Google AdSense
function initializeGoogleAds() {
    console.log('Initializing Google Ads for MAP Terminal...');
    
    // Check if AdSense script is loaded
    if (typeof adsbygoogle === 'undefined') {
        console.warn('Google AdSense script not loaded');
        return;
    }

    // Update all ad containers with proper configuration
    updateAdSlots();
    
    // Initialize responsive ad behavior
    initResponsiveAds();
    
    console.log('Google Ads initialized successfully');
}

// Update ad slots with configuration
function updateAdSlots() {
    const adMappings = {
        'ad-header-728x90': AdsConfig.slots.headerLeaderboard,
        'ad-sidebar-300x250': AdsConfig.slots.sidebarRectangle,
        'ad-overview-320x100': AdsConfig.slots.overviewBanner,
        'ad-markets-320x100': AdsConfig.slots.marketsBanner,
        'ad-species-320x100': AdsConfig.slots.speciesBanner,
        'ad-supply-320x100': AdsConfig.slots.supplyBanner,
        'ad-outlook-320x100': AdsConfig.slots.outlookBanner,
        'ad-footer-728x90': AdsConfig.slots.footerLeaderboard,
        'ad-floating-300x250': AdsConfig.slots.floatingRectangle
    };

    // Update data attributes for each ad
    Object.entries(adMappings).forEach(([containerId, slotId]) => {
        const container = document.getElementById(containerId);
        if (container) {
            const adElement = container.querySelector('.adsbygoogle');
            if (adElement) {
                adElement.setAttribute('data-ad-client', AdsConfig.publisherId);
                adElement.setAttribute('data-ad-slot', slotId);
            }
        }
    });
}

// Responsive ad behavior for different screen sizes
function initResponsiveAds() {
    const mediaQuery = window.matchMedia('(max-width: 768px)');
    
    function handleScreenChange(e) {
        const sidebar = document.querySelector('.ads-sidebar');
        const floatingAd = document.getElementById('floating-ad');
        
        if (e.matches) {
            // Mobile view - move sidebar ads to content flow
            if (sidebar) {
                sidebar.style.position = 'static';
                sidebar.style.width = '100%';
            }
            // Hide floating ad on mobile if screen is too small
            if (window.innerWidth < 480 && floatingAd) {
                floatingAd.style.display = 'none';
            }
        } else {
            // Desktop view - restore sidebar position
            if (sidebar) {
                sidebar.style.position = 'fixed';
                sidebar.style.width = '320px';
            }
        }
    }
    
    // Initial check
    handleScreenChange(mediaQuery);
    
    // Listen for changes
    mediaQuery.addListener(handleScreenChange);
}

// Bloomberg Terminal Ad Performance Analytics
const AdAnalytics = {
    trackAdView: function(adId) {
        console.log(`Ad viewed: ${adId}`);
        // Add your analytics tracking here
    },
    
    trackAdClick: function(adId) {
        console.log(`Ad clicked: ${adId}`);
        // Add your analytics tracking here
    },
    
    trackRevenue: function(amount, adId) {
        console.log(`Ad revenue: $${amount} from ${adId}`);
        // Add your revenue tracking here
    }
};

// Export configuration for global use
if (typeof window !== 'undefined') {
    window.AdsConfig = AdsConfig;
    window.AdAnalytics = AdAnalytics;
    window.initializeGoogleAds = initializeGoogleAds;
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Delay initialization to ensure AdSense script is loaded
    setTimeout(initializeGoogleAds, 1000);
});