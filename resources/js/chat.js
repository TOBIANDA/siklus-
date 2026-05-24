import { io } from 'socket.io-client';

// WebSocket connection manager
class ChatManager {
  constructor() {
    this.socket = null;
    this.currentUserId = null;
    this.currentRecipientId = null;
    this.listeners = {
      messageReceived: [],
      messageRead: [],
      userTyping: [],
      userOnline: [],
      userOffline: [],
      messageSent: [],
      connectionEstablished: [],
      connectionLost: []
    };
  }

  /**
   * Initialize WebSocket connection
   */
  connect(userId) {
    const wsUrl = import.meta.env.VITE_WEBSOCKET_URL || 'http://localhost:3000';
    
    this.socket = io(wsUrl, {
      reconnection: true,
      reconnectionDelay: 1000,
      reconnectionDelayMax: 5000,
      reconnectionAttempts: 5,
      transports: ['websocket', 'polling']
    });

    this.currentUserId = userId;

    // Connection event
    this.socket.on('connect', () => {
      console.log('WebSocket connected');
      this.emit('connectionEstablished');
      
      // Notify server that user is logged in
      this.socket.emit('user-login', userId);
    });

    // Receive message
    this.socket.on('receive-message', (data) => {
      console.log('Message received:', data);
      this.emit('messageReceived', data);
    });

    // Message read confirmation
    this.socket.on('message-read', (data) => {
      console.log('Message read:', data);
      this.emit('messageRead', data);
    });

    // User typing
    this.socket.on('user-typing', (data) => {
      this.emit('userTyping', data);
    });

    // User stop typing
    this.socket.on('user-stop-typing', (data) => {
      this.emit('userTyping', { ...data, isTyping: false });
    });

    // User online
    this.socket.on('user-online', (data) => {
      console.log('User online:', data);
      this.emit('userOnline', data);
    });

    // User offline
    this.socket.on('user-offline', (data) => {
      console.log('User offline:', data);
      this.emit('userOffline', data);
    });

    // Message sent confirmation
    this.socket.on('message-sent', (data) => {
      console.log('Message sent confirmation:', data);
      this.emit('messageSent', data);
    });

    // Disconnection
    this.socket.on('disconnect', () => {
      console.log('WebSocket disconnected');
      this.emit('connectionLost');
    });

    // Error handling
    this.socket.on('error', (error) => {
      console.error('WebSocket error:', error);
    });
  }

  /**
   * Send message
   */
  sendMessage(recipientId, content, borrowRequestId = null) {
    if (!this.socket || !this.socket.connected) {
      console.error('WebSocket not connected');
      return false;
    }

    const messageId = `msg_${Date.now()}_${Math.random()}`;
    
    this.socket.emit('send-message', {
      senderId: this.currentUserId,
      recipientId: recipientId,
      content: content,
      messageId: messageId,
      borrowRequestId: borrowRequestId,
      timestamp: new Date()
    });

    return messageId;
  }

  /**
   * Mark message as read
   */
  markAsRead(messageId, senderId) {
    if (!this.socket || !this.socket.connected) {
      console.error('WebSocket not connected');
      return;
    }

    this.socket.emit('mark-read', {
      messageId: messageId,
      senderId: senderId,
      recipientId: this.currentUserId
    });
  }

  /**
   * Emit typing indicator
   */
  emitTyping(recipientId) {
    if (!this.socket || !this.socket.connected) return;

    this.socket.emit('typing', {
      senderId: this.currentUserId,
      recipientId: recipientId
    });
  }

  /**
   * Stop typing indicator
   */
  stopTyping(recipientId) {
    if (!this.socket || !this.socket.connected) return;

    this.socket.emit('stop-typing', {
      senderId: this.currentUserId,
      recipientId: recipientId
    });
  }

  /**
   * Subscribe to event
   */
  on(event, callback) {
    if (this.listeners[event]) {
      this.listeners[event].push(callback);
    }
  }

  /**
   * Unsubscribe from event
   */
  off(event, callback) {
    if (this.listeners[event]) {
      this.listeners[event] = this.listeners[event].filter(cb => cb !== callback);
    }
  }

  /**
   * Emit event to listeners
   */
  emit(event, data) {
    if (this.listeners[event]) {
      this.listeners[event].forEach(callback => callback(data));
    }
  }

  /**
   * Disconnect
   */
  disconnect() {
    if (this.socket) {
      this.socket.emit('user-logout', this.currentUserId);
      this.socket.disconnect();
      this.socket = null;
    }
  }

  /**
   * Check if connected
   */
  isConnected() {
    return this.socket && this.socket.connected;
  }
}

// Export singleton instance
export default new ChatManager();
