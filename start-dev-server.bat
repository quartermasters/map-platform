@echo off
echo ============================================
echo    MAP Terminal Development Server
echo ============================================
echo.

:: Check if PHP is available
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP not found in PATH
    echo.
    echo Please install PHP or add it to your PATH:
    echo https://windows.php.net/download/
    echo.
    pause
    exit /b 1
)

echo Starting PHP development server...
echo.
echo Your MAP Terminal will be available at:
echo ^> http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo ============================================
echo.

:: Start PHP server in current directory
php -S localhost:8000

pause