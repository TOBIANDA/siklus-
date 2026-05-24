@echo off
echo.
echo === Chat Setup Verification ===
echo.

REM Check 1: WebSocket server running
echo 1. Checking WebSocket server on port 3000...
netstat -ano | find ":3000" >nul
if %ERRORLEVEL% EQU 0 (
    echo    ✓ WebSocket server is running
) else (
    echo    ✗ WebSocket server NOT running - start with: npm run websocket
)

REM Check 2: Vite dev server running
echo.
echo 2. Checking Vite dev server on port 5173/5174...
netstat -ano | find ":5173" >nul
if %ERRORLEVEL% EQU 0 (
    echo    ✓ Vite dev server is running on port 5173
) else (
    netstat -ano | find ":5174" >nul
    if %ERRORLEVEL% EQU 0 (
        echo    ✓ Vite dev server is running on port 5174
    ) else (
        echo    ✗ Vite dev server NOT running - start with: npm run dev
    )
)

REM Check 3: Files created
echo.
echo 3. Checking required files...
if exist "resources\js\chat.js" (
    echo    ✓ resources\js\chat.js exists
) else (
    echo    ✗ resources\js\chat.js NOT found
)

if exist "resources\js\chat-handler.js" (
    echo    ✓ resources\js\chat-handler.js exists
) else (
    echo    ✗ resources\js\chat-handler.js NOT found
)

if exist "websocket.js" (
    echo    ✓ websocket.js exists
) else (
    echo    ✗ websocket.js NOT found
)

if exist "WEBSOCKET_CHAT_GUIDE.md" (
    echo    ✓ WEBSOCKET_CHAT_GUIDE.md exists
) else (
    echo    ✗ WEBSOCKET_CHAT_GUIDE.md NOT found
)

REM Check 4: Environment variables
echo.
echo 4. Checking .env configuration...
findstr /C:"VITE_WEBSOCKET_URL" .env >nul
if %ERRORLEVEL% EQU 0 (
    echo    ✓ VITE_WEBSOCKET_URL is set in .env
) else (
    echo    ✗ VITE_WEBSOCKET_URL not found in .env
)

echo.
echo === Setup Complete ===
echo.
echo Next steps:
echo 1. Make sure all 3 services are running:
echo    - WebSocket: npm run websocket (Terminal 1)
echo    - Vite: npm run dev (Terminal 2)
echo    - Laravel: php artisan serve (Terminal 3)
echo.
echo 2. Open http://localhost:8000/messages in your browser
echo 3. Try sending a message from the chat input
echo.
echo If chat doesn't work:
echo 1. Check browser console (F12) for errors
echo 2. Open DevTools and check: window.chatConfig
echo 3. Check WebSocket in Network tab for socket.io connection
echo.
pause
