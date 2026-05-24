# Real-Time Chat dengan WebSocket

Dokumentasi setup dan penggunaan real-time chat menggunakan Socket.IO dan WebSocket.

## Arsitektur

```
Frontend (Vue/JS) 
  ↓ WebSocket
Node.js Server (Socket.IO)
  ↓ HTTP API
Laravel Backend
  ↓ Database
Database (Messages table)
```

## Setup

### 1. Install Dependencies

```bash
npm install
```

Dependencies yang sudah terinstall:
- `socket.io` - WebSocket server
- `socket.io-client` - WebSocket client
- `express` - HTTP server
- `cors` - Cross-Origin Resource Sharing

### 2. Konfigurasi Environment

Tambahkan variabel berikut di file `.env`:

```env
# WebSocket Configuration
WEBSOCKET_PORT=3000
VITE_WEBSOCKET_URL=http://localhost:3000
```

### 3. Jalankan WebSocket Server

**Terminal 1: WebSocket Server**
```bash
npm run websocket
```

**Terminal 2: Vite Development**
```bash
npm run dev
```

**Terminal 3: Laravel**
```bash
php artisan serve
```

**Atau jalankan semua sekaligus:**
```bash
npm run dev-all
```

## Penggunaan di Frontend

### Inisialisasi Chat Manager

```javascript
import ChatManager from './resources/js/chat.js';

// Connect ke WebSocket server
ChatManager.connect(userId);
```

### Mengirim Pesan

```javascript
ChatManager.sendMessage(
  recipientId,
  messageContent,
  borrowRequestId // optional
);
```

### Menerima Pesan Real-Time

```javascript
ChatManager.on('messageReceived', (message) => {
  console.log('Pesan diterima:', message);
  // Update UI dengan pesan baru
});
```

### Typing Indicator

```javascript
// Emit typing indicator
ChatManager.emitTyping(recipientId);

// Listen to typing
ChatManager.on('userTyping', (data) => {
  if (data.isTyping) {
    console.log(`User ${data.userId} is typing...`);
  }
});

// Stop typing
ChatManager.stopTyping(recipientId);
```

### Mark Message as Read

```javascript
ChatManager.markAsRead(messageId, senderId);

ChatManager.on('messageRead', (data) => {
  console.log(`Message ${data.messageId} telah dibaca`);
});
```

### User Online/Offline Status

```javascript
ChatManager.on('userOnline', (data) => {
  console.log(`User ${data.userId} is online`);
});

ChatManager.on('userOffline', (data) => {
  console.log(`User ${data.userId} is offline`);
});
```

### Disconnect

```javascript
ChatManager.disconnect();
```

## API Endpoints

### 1. Send Message

**POST** `/api/messages/send`

```json
{
  "recipient_id": 2,
  "content": "Hello, this is a message",
  "borrow_request_id": null
}
```

Response:
```json
{
  "success": true,
  "message": {
    "id": 1,
    "sender_id": 1,
    "recipient_id": 2,
    "content": "Hello, this is a message",
    "read_at": null,
    "created_at": "2026-05-14T10:30:00.000000Z",
    "updated_at": "2026-05-14T10:30:00.000000Z",
    "sender": {...},
    "recipient": {...}
  }
}
```

### 2. Get Message History

**GET** `/api/messages/{recipientId}/history`

Response:
```json
{
  "success": true,
  "messages": [
    {
      "id": 1,
      "sender_id": 1,
      "recipient_id": 2,
      "content": "Hello",
      "read_at": "2026-05-14T10:35:00.000000Z",
      "created_at": "2026-05-14T10:30:00.000000Z",
      "sender": {...},
      "recipient": {...}
    }
  ]
}
```

### 3. Mark Message as Read

**PATCH** `/api/messages/{messageId}/read`

Response:
```json
{
  "success": true,
  "message": {
    "id": 1,
    "sender_id": 1,
    "recipient_id": 2,
    "content": "Hello",
    "read_at": "2026-05-14T10:35:00.000000Z",
    "created_at": "2026-05-14T10:30:00.000000Z"
  }
}
```

## WebSocket Events

### Client to Server

- `user-login` - User login notification
  ```javascript
  { userId: 1 }
  ```

- `send-message` - Send message
  ```javascript
  {
    senderId: 1,
    recipientId: 2,
    content: "Hello",
    messageId: "msg_...",
    borrowRequestId: null
  }
  ```

- `mark-read` - Mark message as read
  ```javascript
  {
    messageId: 1,
    senderId: 1,
    recipientId: 2
  }
  ```

- `typing` - User typing indicator
  ```javascript
  {
    senderId: 1,
    recipientId: 2
  }
  ```

- `stop-typing` - Stop typing indicator
  ```javascript
  {
    senderId: 1,
    recipientId: 2
  }
  ```

- `user-logout` - User logout
  ```javascript
  { userId: 1 }
  ```

### Server to Client

- `receive-message` - Message received
  ```javascript
  {
    messageId: "msg_...",
    senderId: 1,
    recipientId: 2,
    content: "Hello",
    borrowRequestId: null,
    timestamp: Date,
    read: false
  }
  ```

- `message-sent` - Confirm message sent
  ```javascript
  {
    messageId: "msg_...",
    senderId: 1,
    timestamp: Date
  }
  ```

- `message-read` - Message marked as read
  ```javascript
  {
    messageId: 1,
    readAt: Date
  }
  ```

- `user-typing` - User is typing
  ```javascript
  { userId: 1 }
  ```

- `user-stop-typing` - User stopped typing
  ```javascript
  { userId: 1, isTyping: false }
  ```

- `user-online` - User online
  ```javascript
  {
    userId: 1,
    socketId: "...",
    timestamp: Date
  }
  ```

- `user-offline` - User offline
  ```javascript
  {
    userId: 1,
    timestamp: Date
  }
  ```

## Contoh Implementasi di Vue Component

```vue
<template>
  <div class="chat-container">
    <!-- Message List -->
    <div class="messages" ref="messageList">
      <div v-for="msg in messages" :key="msg.id" class="message" :class="msg.senderId === currentUserId ? 'sent' : 'received'">
        <p>{{ msg.content }}</p>
        <span class="timestamp">{{ formatTime(msg.created_at) }}</span>
        <span v-if="msg.read_at" class="read-indicator">✓✓</span>
      </div>
      <div v-if="otherUserTyping" class="typing-indicator">
        <span></span><span></span><span></span>
      </div>
    </div>

    <!-- Typing Indicator -->
    <div v-if="otherUserTyping" class="typing-text">User sedang mengetik...</div>

    <!-- Input Form -->
    <form @submit.prevent="sendMessage" class="message-form">
      <input 
        v-model="messageText"
        @input="onTyping"
        type="text" 
        placeholder="Ketik pesan..." 
        class="message-input"
      />
      <button type="submit" class="send-button">Kirim</button>
    </form>
  </div>
</template>

<script>
import ChatManager from '../../resources/js/chat.js';

export default {
  props: {
    recipientId: Number,
    currentUserId: Number
  },
  data() {
    return {
      messages: [],
      messageText: '',
      otherUserTyping: false,
      typingTimeout: null
    };
  },
  methods: {
    sendMessage() {
      if (!this.messageText.trim()) return;

      // Send via API untuk persistence
      fetch('/api/messages/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          recipient_id: this.recipientId,
          content: this.messageText
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Send via WebSocket untuk real-time
          ChatManager.sendMessage(this.recipientId, this.messageText);
          this.messageText = '';
          this.stopTyping();
        }
      });
    },
    onTyping() {
      ChatManager.emitTyping(this.recipientId);
      
      // Clear previous timeout
      if (this.typingTimeout) clearTimeout(this.typingTimeout);
      
      // Set new timeout to stop typing after 1 second of inactivity
      this.typingTimeout = setTimeout(() => {
        ChatManager.stopTyping(this.recipientId);
      }, 1000);
    },
    stopTyping() {
      if (this.typingTimeout) clearTimeout(this.typingTimeout);
      ChatManager.stopTyping(this.recipientId);
    },
    formatTime(date) {
      return new Date(date).toLocaleTimeString();
    },
    loadMessageHistory() {
      fetch(`/api/messages/${this.recipientId}/history`)
        .then(res => res.json())
        .then(data => {
          this.messages = data.messages;
          this.scrollToBottom();
        });
    },
    scrollToBottom() {
      this.$nextTick(() => {
        this.$refs.messageList.scrollTop = this.$refs.messageList.scrollHeight;
      });
    }
  },
  mounted() {
    ChatManager.connect(this.currentUserId);
    this.loadMessageHistory();

    // Real-time message received
    ChatManager.on('messageReceived', (data) => {
      if (data.senderId === this.recipientId) {
        this.messages.push(data);
        ChatManager.markAsRead(data.messageId, data.senderId);
        this.scrollToBottom();
      }
    });

    // Typing indicator
    ChatManager.on('userTyping', (data) => {
      if (data.userId === this.recipientId) {
        this.otherUserTyping = !data.isTyping;
      }
    });
  },
  beforeUnmount() {
    ChatManager.disconnect();
  }
};
</script>

<style scoped>
.chat-container {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.messages {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  background: #f5f5f5;
}

.message {
  margin-bottom: 15px;
  padding: 10px 15px;
  border-radius: 10px;
  max-width: 70%;
  word-wrap: break-word;
}

.message.sent {
  background: #007bff;
  color: white;
  margin-left: auto;
  text-align: right;
}

.message.received {
  background: white;
  color: black;
}

.timestamp {
  font-size: 0.8em;
  opacity: 0.7;
  margin-left: 10px;
}

.read-indicator {
  color: #0084ff;
  margin-left: 5px;
}

.typing-text {
  padding: 10px 20px;
  font-style: italic;
  color: #999;
}

.typing-indicator span {
  display: inline-block;
  width: 8px;
  height: 8px;
  background: #999;
  border-radius: 50%;
  margin: 0 2px;
  animation: bounce 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
  animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes bounce {
  0%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
}

.message-form {
  display: flex;
  gap: 10px;
  padding: 20px;
  background: white;
  border-top: 1px solid #ddd;
}

.message-input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1em;
}

.send-button {
  padding: 10px 20px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.send-button:hover {
  background: #0056b3;
}
</style>
```

## Troubleshooting

### WebSocket tidak connect

1. Pastikan WebSocket server sudah berjalan: `npm run websocket`
2. Cek port 3000 tidak blocked
3. Cek CORS configuration di `websocket.js`
4. Check browser console untuk error messages

### Pesan tidak terkirim

1. Pastikan API endpoint `/api/messages/send` berfungsi
2. Cek CSRF token di form
3. Verify recipient_id valid di database

### Typing indicator tidak muncul

1. Pastikan `emitTyping()` dipanggil saat user mengetik
2. Cek di DevTools apakah WebSocket event terkirim
3. Pastikan `onTyping` handler terdaftar dengan benar

## Security Considerations

1. **CSRF Protection**: Semua POST requests harus include X-CSRF-TOKEN
2. **Authentication**: Pastikan user authenticated sebelum allow message
3. **Authorization**: Validasi user hanya bisa send ke recipient yang valid
4. **Message Validation**: Validate message content di server (max length, etc)
5. **Rate Limiting**: Pertimbangkan add rate limiting untuk prevent spam

## Performance Tips

1. Load message history di lazy scroll
2. Implement message pagination (tidak load semua history sekaligus)
3. Add message caching di frontend
4. Use WebSocket compression untuk reduce bandwidth
5. Add connection pooling untuk database

## Production Deployment

1. Use production Socket.IO transports (hanya websocket, tidak polling)
2. Deploy Node.js server ke production server
3. Setup nginx reverse proxy untuk WebSocket
4. Add SSL/TLS untuk secure connection (WSS)
5. Monitor WebSocket connections dan server performance
6. Setup logging dan error tracking
7. Use Redis adapter untuk multi-server deployment

```nginx
# Nginx configuration untuk WebSocket
upstream websocket {
    server localhost:3000;
}

server {
    listen 80;
    server_name yourdomain.com;

    location /socket.io {
        proxy_pass http://websocket;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

## Next Steps

1. Implement message history pagination
2. Add message search functionality
3. Add file/image sharing
4. Add group chat support
5. Add message reactions/emoji
6. Add call notifications
7. Add message encryption
