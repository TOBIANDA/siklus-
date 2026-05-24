import ChatManager from './chat.js';

/**
 * Add message to UI
 */
function addMessageToUI(message, type) {
  const messageBody = document.querySelector('.inbox-detail-body');
  if (!messageBody) return;

  const messageRow = document.createElement('div');
  messageRow.className = `chat-bubble-row ${type === 'self' ? 'self' : ''}`;

  const bubbleClass = type === 'self' ? 'sent' : 'recv';
  const timestamp = new Date(message.created_at).toLocaleTimeString([], {
    hour: '2-digit',
    minute: '2-digit'
  });

  messageRow.innerHTML = `
    <div>
      <div class="chat-bubble ${bubbleClass}">
        ${escapeHtml(message.content)}
      </div>
      <div class="chat-time ${type === 'self' ? 'r' : ''}">
        ${timestamp}
      </div>
    </div>
  `;

  messageBody.appendChild(messageRow);
  messageBody.scrollTop = messageBody.scrollHeight;
}

/**
 * Initialize chat functionality on messages page
 */
export function initializeChat(currentUserId, recipientId) {
  // Connect to WebSocket
  ChatManager.connect(currentUserId);

  // Get DOM elements
  const messageInput = document.querySelector('.inbox-input');
  const sendButton = document.querySelector('.inbox-send-btn');
  const messageBody = document.querySelector('.inbox-detail-body');

  if (!messageInput || !sendButton || !messageBody) {
    console.error('Chat elements not found');
    return;
  }

  // Send message on button click
  sendButton.addEventListener('click', (e) => {
    e.preventDefault();
    sendMessage();
  });

  // Send message on Enter key
  messageInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  // Typing indicator
  let typingTimeout;
  messageInput.addEventListener('input', () => {
    ChatManager.emitTyping(recipientId);
    
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
      ChatManager.stopTyping(recipientId);
    }, 1000);
  });

  function sendMessage() {
    const content = messageInput.value.trim();
    if (!content) return;

    // Disable send button during sending
    sendButton.disabled = true;
    messageInput.disabled = true;

    // Send via API (for persistence)
    fetch('/api/messages/send', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
      },
      body: JSON.stringify({
        recipient_id: recipientId,
        content: content
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Clear input
        messageInput.value = '';

        // Add message to UI
        addMessageToUI(data.message, 'self');

        // Send via WebSocket for real-time
        ChatManager.sendMessage(recipientId, content);
      } else {
        showError(data.message || 'Failed to send message');
      }
    })
    .catch(err => {
      console.error('Error sending message:', err);
      showError('Failed to send message. Please try again.');
    })
    .finally(() => {
      sendButton.disabled = false;
      messageInput.disabled = false;
      messageInput.focus();
    });
  }

  // Listen for incoming messages
  ChatManager.on('messageReceived', (data) => {
    if (data.senderId === recipientId) {
      addMessageToUI({
        id: data.messageId,
        content: data.content,
        sender_id: data.senderId,
        created_at: new Date(data.timestamp).toISOString()
      }, 'received');
      
      // Mark as read
      ChatManager.markAsRead(data.messageId, data.senderId);
    }
  });

  // Listen for typing indicators
  ChatManager.on('userTyping', (data) => {
    if (data.userId === recipientId) {
      const typingIndicator = document.querySelector('.typing-indicator');
      if (!data.isTyping && typingIndicator) {
        typingIndicator.remove();
      } else if (data.isTyping && !typingIndicator) {
        addTypingIndicator();
      }
    }
  });

  // Listen for connection status
  ChatManager.on('connectionEstablished', () => {
    console.log('Chat connected');
    removeConnectionWarning();
  });

  ChatManager.on('connectionLost', () => {
    console.log('Chat disconnected');
    showConnectionWarning();
  });
}

/**
 * Add typing indicator
 */
function addTypingIndicator() {
  const messageBody = document.querySelector('.inbox-detail-body');
  if (!messageBody) return;

  const typingDiv = document.createElement('div');
  typingDiv.className = 'chat-bubble-row typing-indicator';
  typingDiv.innerHTML = `
    <div>
      <div class="chat-bubble recv" style="display: flex; gap: 4px;">
        <span style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: typing 1.4s infinite;"></span>
        <span style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: typing 1.4s infinite; animation-delay: 0.2s;"></span>
        <span style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: typing 1.4s infinite; animation-delay: 0.4s;"></span>
      </div>
    </div>
  `;
  messageBody.appendChild(typingDiv);
  messageBody.scrollTop = messageBody.scrollHeight;
}

/**
 * Show error message
 */
function showError(message) {
  const alert = document.createElement('div');
  alert.className = 'alert-error';
  alert.style.cssText = `
    background: #FEE2E2;
    color: #991B1B;
    padding: 10px 16px;
    border-radius: 10px;
    margin: 12px 20px 0;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  `;
  alert.innerHTML = `<span>❌</span> <span>${message}</span>`;
  
  const detailArea = document.querySelector('.inbox-detail');
  if (detailArea) {
    detailArea.insertBefore(alert, detailArea.firstChild);
    setTimeout(() => alert.remove(), 5000);
  }
}

/**
 * Show connection warning
 */
function showConnectionWarning() {
  const alert = document.createElement('div');
  alert.className = 'alert-warning';
  alert.style.cssText = `
    background: #FEF3C7;
    color: #92400E;
    padding: 10px 16px;
    border-radius: 10px;
    margin: 12px 20px 0;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  `;
  alert.innerHTML = `<span>⚠️</span> <span>Chat sedang terputus, mencoba reconnect...</span>`;
  
  const detailArea = document.querySelector('.inbox-detail');
  if (detailArea) {
    detailArea.insertBefore(alert, detailArea.firstChild);
  }
}

/**
 * Remove connection warning
 */
function removeConnectionWarning() {
  const alert = document.querySelector('.alert-warning');
  if (alert) alert.remove();
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

/**
 * Load message history
 */
export async function loadMessageHistory(recipientId) {
  try {
    const response = await fetch(`/api/messages/${recipientId}/history`);
    const data = await response.json();
    
    if (data.success && data.messages.length > 0) {
      const messageBody = document.querySelector('.inbox-detail-body');
      if (!messageBody) return;

      // Clear existing chat bubbles (keep only request card)
      const chatBubbles = messageBody.querySelectorAll('.chat-bubble-row');
      chatBubbles.forEach(bubble => bubble.remove());

      // Add messages from history
      data.messages.forEach(msg => {
        addMessageToUI(msg, msg.sender_id === getCurrentUserId() ? 'self' : 'received');
      });
    }
  } catch (err) {
    console.error('Error loading message history:', err);
  }
}

/**
 * Get current user ID from DOM
 */
function getCurrentUserId() {
  // Assuming user ID is stored in a data attribute or meta tag
  return document.querySelector('meta[name="user-id"]')?.content || 
         window.currentUserId || 
         document.querySelector('[data-current-user-id]')?.getAttribute('data-current-user-id');
}
