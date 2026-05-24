@echo off
setlocal enabledelayedexpansion

echo.
echo ========================================
echo   SIKLUS CHAT - Development Server
echo ========================================
echo.

REM Check if Node modules are installed
if not exist "node_modules" (
    echo Installing dependencies...
    call npm install
    echo.
)

REM Start all services in separate windows
echo Starting services...
echo.

REM WebSocket Server
echo [1/3] Starting WebSocket server on port 3000...
start "WebSocket Server" cmd /k "npm run websocket"
timeout /t 2 /nobreak

REM Vite Dev Server
echo [2/3] Starting Vite dev server on port 5173...
start "Vite Dev Server" cmd /k "npm run dev"
timeout /t 2 /nobreak

REM Laravel Server
echo [3/3] Starting Laravel server on port 8000...
start "Laravel Server" cmd /k "php artisan serve"
timeout /t 2 /nobreak

echo.
echo ========================================
echo.
echo Services started:
echo   - WebSocket: http://localhost:3000
echo   - Frontend:  http://localhost:5173 (auto-reload)
echo   - API:       http://localhost:8000
echo.
echo Open in browser: http://localhost:8000/messages
echo.
echo To stop all services: Close all opened windows
echo.
echo ========================================
echo.
pause
