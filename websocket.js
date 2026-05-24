import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';
import dotenv from 'dotenv';

dotenv.config();

const app = express();
const server = http.createServer(app);

app.use(cors());
app.use(express.json());

const io = new Server(server, {
  cors: {
    origin: process.env.VITE_APP_URL || 'http://localhost:5173',
    methods: ['GET', 'POST'],
    credentials: true
  },
  transports: ['websocket', 'polling']
});

// Store active users: { userId: socketId }
const activeUsers = new Map();

// Socket.IO connection handling
io.on('connection', (socket) => {
  console.log(`User connected: ${socket.id}`);

  // User joins their personal room
  socket.on('user-login', (userId) => {
    activeUsers.set(userId, socket.id);
    socket.join(`user-${userId}`);
    console.log(`User ${userId} joined room user-${userId}`);
    
    // Broadcast user is online
    io.emit('user-online', {
      userId: userId,
      socketId: socket.id,
      timestamp: new Date()
    });
  });

  // Send message with real-time broadcast
  socket.on('send-message', (data) => {
    const { senderId, recipientId, content, messageId, borrowRequestId } = data;
    
    console.log(`Message from ${senderId} to ${recipientId}: ${content}`);
    
    // Send to recipient's room
    io.to(`user-${recipientId}`).emit('receive-message', {
      messageId: messageId,
      senderId: senderId,
      recipientId: recipientId,
      content: content,
      borrowRequestId: borrowRequestId,
      timestamp: new Date(),
      read: false
    });
    
    // Confirm to sender
    socket.emit('message-sent', {
      messageId: messageId,
      senderId: senderId,
      timestamp: new Date()
    });
  });

  // Mark message as read
  socket.on('mark-read', (data) => {
    const { messageId, senderId, recipientId } = data;
    
    io.to(`user-${senderId}`).emit('message-read', {
      messageId: messageId,
      readAt: new Date()
    });
  });

  // User typing indicator
  socket.on('typing', (data) => {
    const { senderId, recipientId } = data;
    io.to(`user-${recipientId}`).emit('user-typing', {
      userId: senderId
    });
  });

  socket.on('stop-typing', (data) => {
    const { senderId, recipientId } = data;
    io.to(`user-${recipientId}`).emit('user-stop-typing', {
      userId: senderId
    });
  });

  // User logout
  socket.on('user-logout', (userId) => {
    activeUsers.delete(userId);
    socket.leave(`user-${userId}`);
    io.emit('user-offline', {
      userId: userId,
      timestamp: new Date()
    });
    console.log(`User ${userId} logged out`);
  });

  socket.on('disconnect', () => {
    // Find and remove user
    for (let [userId, socketId] of activeUsers.entries()) {
      if (socketId === socket.id) {
        activeUsers.delete(userId);
        io.emit('user-offline', {
          userId: userId,
          timestamp: new Date()
        });
        console.log(`User ${userId} disconnected`);
        break;
      }
    }
  });
});

const PORT = process.env.WEBSOCKET_PORT || 3000;
server.listen(PORT, () => {
  console.log(`WebSocket server running on port ${PORT}`);
});
