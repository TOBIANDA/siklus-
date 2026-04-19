@extends('layouts.app')

@section('content')
<div style="padding:24px;height:calc(100vh - 61px);">
  <div class="messages-layout">

    <!-- Message List Sidebar -->
    <div class="msg-sidebar">
      <h2>Messages</h2>
      @foreach($conversations as $conv)
      <a href="{{ route('messages.show', $conv['id']) }}"
         class="msg-item {{ $activeConversation && $activeConversation['id'] == $conv['id'] ? 'active' : '' }}"
         onclick="setMsgActive(this)">
        <div class="msg-av">
          <img src="{{ asset('images/' . $conv['avatar']) }}" alt="{{ $conv['name'] }}"
               style="width:100%;height:100%;object-fit:cover;border-radius:50%;" onerror="this.style.display='none'">
          &#128100;
          @if($conv['online'])
          <div class="online-dot"></div>
          @endif
        </div>
        <div class="msg-info">
          <div class="msg-name">
            {{ $conv['name'] }}
            @if($conv['online'])
              <span class="live-badge">LIVE</span>
            @else
              <span class="time-badge">{{ $conv['time'] }}</span>
            @endif
          </div>
          <div class="msg-preview">{{ $conv['preview'] }}</div>
        </div>
      </a>
      @endforeach
    </div>

    <!-- Chat Area -->
    @if($activeConversation)
    <div class="chat-area">
      <div class="chat-header">
        <div class="chat-header-left">
          <div class="msg-av" style="width:40px;height:40px;font-size:18px;">
            <img src="{{ asset('images/' . $activeConversation['avatar']) }}" alt="{{ $activeConversation['name'] }}"
                 style="width:100%;height:100%;object-fit:cover;border-radius:50%;" onerror="this.style.display='none'">
            @if($activeConversation['online'])
            <div class="online-dot"></div>
            @endif
          </div>
          <div>
            <div class="chat-name">{{ $activeConversation['name'] }}</div>
            <div class="chat-status">
              @if($activeConversation['online'])
                ONLINE &middot; <span style="color:var(--gray)">Reputation: {{ $activeConversation['reputation'] }}</span>
              @else
                OFFLINE
              @endif
            </div>
          </div>
        </div>
        <div class="chat-actions">
          <button class="icon-btn">&#128222;</button>
          <button class="icon-btn">&#8505;</button>
          <button class="icon-btn">&#8942;</button>
        </div>
      </div>

      <div class="chat-messages">
        @foreach($messages as $message)
          @if(isset($message['date_label']))
          <div class="chat-date">{{ $message['date_label'] }}</div>
          @else
          <div class="msg-row {{ $message['self'] ? 'self' : '' }}">
            @if(!$message['self'])
            <div class="msg-av" style="width:32px;height:32px;font-size:14px;">
              <img src="{{ asset('images/' . $activeConversation['avatar']) }}" alt=""
                   style="width:100%;height:100%;object-fit:cover;border-radius:50%;" onerror="this.style.display='none'">
            </div>
            @endif
            <div>
              @if(isset($message['shared_book']))
              <div class="share-card">
                <div class="share-thumb">&#128736;<br>{{ $message['shared_book'] }}</div>
                <div class="share-info">{{ $message['shared_book'] }}</div>
              </div>
              @else
              <div class="bubble {{ $message['self'] ? 'sent' : 'recv' }}">{{ $message['text'] }}</div>
              @if(isset($message['time']))
              <span class="msg-time {{ $message['self'] ? 'r' : '' }}">{{ $message['time'] }}</span>
              @endif
              @endif
            </div>
          </div>
          @endif
        @endforeach
      </div>

      <div class="chat-input-area">
        <form action="{{ route('messages.send', $activeConversation['id']) }}" method="POST" style="display:flex;align-items:center;gap:10px;flex:1;">
          @csrf
          <button type="button" class="act-btn">&#10133;</button>
          <button type="button" class="act-btn">&#128247;</button>
          <input class="chat-input" type="text" name="message" placeholder="Write your message...">
          <button type="button" class="act-btn">&#128522;</button>
          <button type="submit" class="send-btn">&#9658;</button>
        </form>
      </div>
      <div class="secured">Secured by Siklus Community Protocol</div>
    </div>
    @endif

  </div>
</div>
@endsection
