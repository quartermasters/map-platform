#!/bin/bash

echo "============================================"
echo "    MAP Terminal Development Server"
echo "============================================"
echo ""

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "ERROR: PHP not found in PATH"
    echo ""
    echo "Please install PHP:"
    echo "Ubuntu/Debian: sudo apt install php"
    echo "macOS: brew install php"
    echo "CentOS/RHEL: sudo yum install php"
    echo ""
    exit 1
fi

echo "Starting PHP development server..."
echo ""
echo "Your MAP Terminal will be available at:"
echo "> http://localhost:8000"
echo ""
echo "Press Ctrl+C to stop the server"
echo "============================================"
echo ""

# Start PHP server in current directory
php -S localhost:8000