<!-- Contoh implementasi chat di Blade template -->
<!-- resources/views/components/chat-window.blade.php -->

<div id="chat-window" class="chat-window">
  <!-- Header -->
  <div class="chat-header">
    <div class="chat-user-info">
      <img :src="`/images/avatars/${otherUser.id}.jpg`" :alt="otherUser.name" class="chat-avatar" />
      <div class="chat-user-details">
        <h3>{{ otherUser.name }}</h3>
        <small :class="otherUserOnline ? 'online' : 'offline'">
          {{ otherUserOnline ? 'Online' : `Last seen ${lastSeen}` }}
        </small>
      </div>
    </div>
    <div class="chat-actions">
      <button @click="toggleInfo" class="btn-info">ℹ️</button>
    </div>
  </div>

  <!-- Messages Container -->
  <div class="chat-messages" ref="messagesContainer">
    <!-- Loading state -->
    <div v-if="loadingMessages" class="loading-spinner">
      <p>Loading messages...</p>
    </div>

    <!-- Messages -->
    <div 
      v-for="message in messages" 
      :key="message.id"
      class="message-wrapper"
      :class="message.sender_id === currentUserId ? 'sent' : 'received'"
    >
      <div class="message-bubble">
        <p class="message-text">{{ message.content }}</p>
        <div class="message-footer">
          <span class="message-time">{{ formatTime(message.created_at) }}</span>
          <span v-if="message.sender_id === currentUserId" class="message-status">
            <span v-if="!message.read_at" class="status-pending">✓</span>
            <span v-else class="status-read">✓✓</span>
          </span>
        </div>
      </div>
    </div>

    <!-- Typing indicator -->
    <div v-if="otherUserTyping" class="message-wrapper received">
      <div class="message-bubble typing">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>

  <!-- Message Input -->
  <form @submit.prevent="sendMessage" class="chat-input-form">
    <div class="input-wrapper">
      <input 
        v-model="messageText"
        @keydown.enter="sendMessage"
        @input="handleTyping"
        type="text"
        placeholder="Type a message..."
        class="message-input"
        :disabled="sendingMessage || !ChatManager.isConnected()"
      />
      <button 
        type="submit"
        class="btn-send"
        :disabled="!messageText.trim() || sendingMessage || !ChatManager.isConnected()"
      >
        <span v-if="!sendingMessage">Send</span>
        <span v-else class="spinner-small"></span>
      </button>
    </div>
    <small v-if="!ChatManager.isConnected()" class="text-danger">
      ⚠️ Connecting to chat server...
    </small>
  </form>
</div>

<style scoped>
.chat-window {
  display: flex;
  flex-direction: column;
  height: 100%;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

/* Header */
.chat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-bottom: 2px solid #e0e0e0;
}

.chat-user-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.chat-avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid white;
}

.chat-user-details h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
}

.chat-user-details small {
  display: block;
  opacity: 0.8;
  font-size: 0.85rem;
}

.chat-user-details small.online {
  color: #4caf50;
  font-weight: 500;
}

.chat-user-details small.offline {
  color: #999;
}

.chat-actions {
  display: flex;
  gap: 10px;
}

.btn-info {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1.1rem;
  transition: background 0.2s;
}

.btn-info:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Messages Container */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  background: #f5f5f5;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.message-wrapper {
  display: flex;
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.message-wrapper.sent {
  justify-content: flex-end;
}

.message-wrapper.received {
  justify-content: flex-start;
}

.message-bubble {
  max-width: 70%;
  padding: 12px 16px;
  border-radius: 18px;
  word-wrap: break-word;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.message-wrapper.sent .message-bubble {
  background: #007bff;
  color: white;
  border-bottom-right-radius: 4px;
}

.message-wrapper.received .message-bubble {
  background: white;
  color: #333;
  border-bottom-left-radius: 4px;
}

.message-text {
  margin: 0;
  line-height: 1.4;
}

.message-footer {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-top: 5px;
  font-size: 0.75rem;
}

.message-time {
  opacity: 0.7;
}

.message-status {
  color: #0084ff;
  font-weight: bold;
}

.status-pending {
  opacity: 0.5;
}

/* Typing indicator */
.message-bubble.typing {
  display: flex;
  gap: 4px;
  padding: 12px 16px;
  background: #e0e0e0;
}

.message-bubble.typing span {
  width: 8px;
  height: 8px;
  background: #999;
  border-radius: 50%;
  animation: typing 1.4s infinite;
}

.message-bubble.typing span:nth-child(1) {
  animation-delay: 0s;
}

.message-bubble.typing span:nth-child(2) {
  animation-delay: 0.2s;
}

.message-bubble.typing span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes typing {
  0%, 60%, 100% {
    opacity: 0.5;
    transform: translateY(0);
  }
  30% {
    opacity: 1;
    transform: translateY(-10px);
  }
}

.loading-spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

/* Input Form */
.chat-input-form {
  padding: 15px 20px;
  background: white;
  border-top: 1px solid #e0e0e0;
}

.input-wrapper {
  display: flex;
  gap: 10px;
  margin-bottom: 5px;
}

.message-input {
  flex: 1;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 25px;
  font-size: 0.95rem;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.message-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.message-input:disabled {
  background: #f5f5f5;
  cursor: not-allowed;
}

.btn-send {
  padding: 12px 25px;
  background: #667eea;
  color: white;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.2s, transform 0.1s;
}

.btn-send:hover:not(:disabled) {
  background: #5568d3;
  transform: translateY(-1px);
}

.btn-send:active:not(:disabled) {
  transform: translateY(0);
}

.btn-send:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.spinner-small {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Warning messages */
.text-danger {
  color: #dc3545;
  font-size: 0.85rem;
  display: block;
  margin-top: 5px;
}

/* Scrollbar styling */
.chat-messages::-webkit-scrollbar {
  width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
  background: transparent;
}

.chat-messages::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
  background: #999;
}

/* Responsive */
@media (max-width: 768px) {
  .message-bubble {
    max-width: 85%;
  }

  .chat-header {
    padding: 12px 15px;
  }

  .chat-messages {
    padding: 15px;
  }

  .chat-input-form {
    padding: 12px 15px;
  }
}
</style>

<script>
import ChatManager from '../../resources/js/chat.js';

export default {
  props: {
    recipientId: {
      type: Number,
      required: true
    },
    currentUserId: {
      type: Number,
      required: true
    },
    otherUser: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      ChatManager: ChatManager,
      messages: [],
      messageText: '',
      sendingMessage: false,
      loadingMessages: true,
      otherUserTyping: false,
      otherUserOnline: false,
      lastSeen: 'recently',
      typingTimeout: null
    };
  },
  methods: {
    loadMessageHistory() {
      this.loadingMessages = true;
      fetch(`/api/messages/${this.recipientId}/history`, {
        headers: {
          'Authorization': `Bearer ${window.csrfToken}`
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          this.messages = data.messages;
          this.loadingMessages = false;
          this.scrollToBottom();
        }
      })
      .catch(err => {
        console.error('Failed to load messages:', err);
        this.loadingMessages = false;
      });
    },
    sendMessage() {
      if (!this.messageText.trim() || this.sendingMessage) return;

      this.sendingMessage = true;
      const content = this.messageText;
      const messageId = `msg_${Date.now()}`;

      // Send via HTTP API for persistence
      fetch('/api/messages/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify({
          recipient_id: this.recipientId,
          content: content
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Add to local messages
          this.messages.push(data.message);
          this.messageText = '';
          this.scrollToBottom();

          // Send via WebSocket for real-time delivery
          ChatManager.sendMessage(this.recipientId, content);
        }
      })
      .catch(err => {
        console.error('Failed to send message:', err);
        this.$emit('error', 'Failed to send message');
      })
      .finally(() => {
        this.sendingMessage = false;
        this.stopTyping();
      });
    },
    handleTyping() {
      ChatManager.emitTyping(this.recipientId);

      if (this.typingTimeout) clearTimeout(this.typingTimeout);
      this.typingTimeout = setTimeout(() => {
        ChatManager.stopTyping(this.recipientId);
      }, 1000);
    },
    stopTyping() {
      if (this.typingTimeout) clearTimeout(this.typingTimeout);
      ChatManager.stopTyping(this.recipientId);
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.messagesContainer;
        if (container) {
          container.scrollTop = container.scrollHeight;
        }
      });
    },
    formatTime(timestamp) {
      const date = new Date(timestamp);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
    toggleInfo() {
      this.$emit('toggle-info');
    }
  },
  mounted() {
    // Connect chat manager
    ChatManager.connect(this.currentUserId);
    this.loadMessageHistory();

    // Listen for incoming messages
    ChatManager.on('messageReceived', (data) => {
      if (data.senderId === this.recipientId) {
        this.messages.push({
          id: data.messageId,
          sender_id: data.senderId,
          recipient_id: data.recipientId,
          content: data.content,
          read_at: null,
          created_at: new Date(data.timestamp).toISOString()
        });
        // Mark as read
        ChatManager.markAsRead(data.messageId, data.senderId);
        this.scrollToBottom();
      }
    });

    // Listen for typing indicators
    ChatManager.on('userTyping', (data) => {
      if (data.userId === this.recipientId) {
        this.otherUserTyping = !data.isTyping;
      }
    });

    // Listen for user online status
    ChatManager.on('userOnline', (data) => {
      if (data.userId === this.recipientId) {
        this.otherUserOnline = true;
      }
    });

    ChatManager.on('userOffline', (data) => {
      if (data.userId === this.recipientId) {
        this.otherUserOnline = false;
        this.lastSeen = 'just now';
      }
    });

    // Listen for message read confirmation
    ChatManager.on('messageRead', (data) => {
      const msg = this.messages.find(m => m.id == data.messageId);
      if (msg) {
        msg.read_at = data.readAt;
      }
    });
  },
  beforeUnmount() {
    ChatManager.disconnect();
    if (this.typingTimeout) clearTimeout(this.typingTimeout);
  }
};
</script>
