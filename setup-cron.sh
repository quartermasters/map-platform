#!/bin/bash

# MAP Terminal - Automated Scraping Setup Script
# This script sets up cron jobs for automated market intelligence scraping

echo "=== MAP Terminal Automation Setup ==="
echo ""

# Get the current directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
PHP_PATH="/usr/bin/php"

# Check if PHP exists
if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Please install PHP first."
    exit 1
fi

PHP_PATH=$(which php)
echo "✅ PHP found at: $PHP_PATH"

# Check if scheduler.php exists
if [ ! -f "$SCRIPT_DIR/scheduler.php" ]; then
    echo "❌ scheduler.php not found in $SCRIPT_DIR"
    exit 1
fi

echo "✅ Scheduler script found"

# Show current crontab
echo ""
echo "Current crontab entries:"
crontab -l 2>/dev/null | grep -v "MAP Terminal" || echo "No existing MAP Terminal entries found"

echo ""
echo "Available scheduling options:"
echo "1. Every 4 hours (recommended for production)"
echo "2. Every 2 hours (high frequency)"  
echo "3. Every 6 hours (low frequency)"
echo "4. Every 12 hours (minimal updates)"
echo "5. Custom schedule"
echo "6. Remove existing MAP Terminal cron jobs"
echo "0. Cancel"

read -p "Choose an option (0-6): " choice

case $choice in
    1)
        CRON_SCHEDULE="0 */4 * * *"
        DESCRIPTION="every 4 hours"
        ;;
    2)
        CRON_SCHEDULE="0 */2 * * *"
        DESCRIPTION="every 2 hours"
        ;;
    3)
        CRON_SCHEDULE="0 */6 * * *"
        DESCRIPTION="every 6 hours"
        ;;
    4)
        CRON_SCHEDULE="0 */12 * * *"
        DESCRIPTION="every 12 hours"
        ;;
    5)
        read -p "Enter custom cron schedule (e.g., '0 8,16 * * *' for 8 AM and 4 PM): " CRON_SCHEDULE
        DESCRIPTION="custom schedule: $CRON_SCHEDULE"
        ;;
    6)
        echo "Removing existing MAP Terminal cron jobs..."
        crontab -l 2>/dev/null | grep -v "MAP Terminal" | crontab -
        echo "✅ MAP Terminal cron jobs removed"
        exit 0
        ;;
    0)
        echo "Setup cancelled"
        exit 0
        ;;
    *)
        echo "❌ Invalid option"
        exit 1
        ;;
esac

# Create the cron job entry
CRON_COMMAND="$PHP_PATH $SCRIPT_DIR/scheduler.php"
CRON_ENTRY="$CRON_SCHEDULE $CRON_COMMAND # MAP Terminal Automated Scraping"

# Add logging
LOG_FILE="$SCRIPT_DIR/cron_output.log"
CRON_ENTRY_WITH_LOG="$CRON_SCHEDULE $CRON_COMMAND >> $LOG_FILE 2>&1 # MAP Terminal Automated Scraping"

echo ""
echo "Cron job to be added:"
echo "$CRON_ENTRY_WITH_LOG"
echo "This will run $DESCRIPTION"

read -p "Proceed with installation? (y/N): " confirm
if [[ ! $confirm =~ ^[Yy]$ ]]; then
    echo "Setup cancelled"
    exit 0
fi

# Remove any existing MAP Terminal entries and add the new one
(crontab -l 2>/dev/null | grep -v "MAP Terminal"; echo "$CRON_ENTRY_WITH_LOG") | crontab -

if [ $? -eq 0 ]; then
    echo "✅ Cron job installed successfully!"
    echo ""
    echo "Configuration:"
    echo "- Schedule: $DESCRIPTION"
    echo "- Command: $CRON_COMMAND" 
    echo "- Log file: $LOG_FILE"
    echo ""
    echo "To verify installation:"
    echo "  crontab -l | grep 'MAP Terminal'"
    echo ""
    echo "To view logs:"
    echo "  tail -f $LOG_FILE"
    echo ""
    echo "To enable/disable scheduling, visit your dashboard at:"
    echo "  http://your-domain.com/dashboard_v2.php"
    echo ""
    echo "⚠️  Remember to enable the scheduler in the dashboard configuration!"
else
    echo "❌ Failed to install cron job"
    exit 1
fi

# Create initial log file
touch "$LOG_FILE"
chmod 644 "$LOG_FILE"

# Test the scheduler
echo ""
read -p "Test the scheduler now? (y/N): " test_now
if [[ $test_now =~ ^[Yy]$ ]]; then
    echo "Testing scheduler..."
    $PHP_PATH "$SCRIPT_DIR/scheduler.php"
    
    if [ $? -eq 0 ]; then
        echo "✅ Scheduler test completed successfully"
    else
        echo "⚠️  Scheduler test completed with warnings (check logs)"
    fi
fi

echo ""
echo "Setup complete! 🚀"
echo ""
echo "Next steps:"
echo "1. Visit your dashboard to enable scheduling"
echo "2. Configure notification settings if desired"  
echo "3. Monitor the log file for automated runs"
echo ""
echo "Dashboard URL: http://your-domain.com/dashboard_v2.php"
echo "Log monitoring: tail -f $LOG_FILE"