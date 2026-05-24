#!/bin/bash

# Simple test to verify WebSocket chat setup

echo "=== Chat Setup Verification ==="
echo ""

# Check 1: WebSocket server running
echo "1. Checking WebSocket server on port 3000..."
if netstat -ano | grep -q ":3000"; then
  echo "   ✓ WebSocket server is running"
else
  echo "   ✗ WebSocket server NOT running - start with: npm run websocket"
fi

# Check 2: Vite dev server running
echo ""
echo "2. Checking Vite dev server on port 5173/5174..."
if netstat -ano | grep -E ":5173|:5174" | grep -q "LISTENING"; then
  echo "   ✓ Vite dev server is running"
else
  echo "   ✗ Vite dev server NOT running - start with: npm run dev"
fi

# Check 3: Files created
echo ""
echo "3. Checking required files..."
files=(
  "resources/js/chat.js"
  "resources/js/chat-handler.js"
  "websocket.js"
  "WEBSOCKET_CHAT_GUIDE.md"
)

for file in "${files[@]}"; do
  if [ -f "$file" ]; then
    echo "   ✓ $file exists"
  else
    echo "   ✗ $file NOT found"
  fi
done

# Check 4: Environment variables
echo ""
echo "4. Checking .env configuration..."
if grep -q "VITE_WEBSOCKET_URL" .env; then
  echo "   ✓ VITE_WEBSOCKET_URL is set in .env"
else
  echo "   ✗ VITE_WEBSOCKET_URL not found in .env"
fi

echo ""
echo "=== Setup Complete ==="
echo ""
echo "Next steps:"
echo "1. Make sure all 3 services are running:"
echo "   - WebSocket: npm run websocket"
echo "   - Vite: npm run dev"
echo "   - Laravel: php artisan serve (in another terminal)"
echo ""
echo "2. Open http://localhost:8000/messages in your browser"
echo "3. Try sending a message from the chat input"
echo ""
echo "If chat doesn't work:"
echo "1. Check browser console (F12) for errors"
echo "2. Check WebSocket connection: window.chatConfig in console"
echo "3. Run: npm run audit to check for vulnerabilities"
echo ""
