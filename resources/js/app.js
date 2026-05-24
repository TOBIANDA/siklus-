import './bootstrap';
import { initializeChat, loadMessageHistory } from './chat-handler.js';

// Initialize chat if on messages page
document.addEventListener('DOMContentLoaded', async () => {
  if (window.chatConfig) {
    const { currentUserId, recipientId } = window.chatConfig;
    
    // Initialize chat
    initializeChat(currentUserId, recipientId);
    
    // Load message history
    await loadMessageHistory(recipientId);
  }
});
