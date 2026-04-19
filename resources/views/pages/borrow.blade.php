@extends('layouts.app')

@section('content')
<div style="padding:24px;">

  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div class="borrow-tabs">
      <a href="{{ route('borrow') }}" class="borrow-tab active" style="text-decoration:none;">Borrowed Books</a>
      <a href="{{ route('lent') }}" class="borrow-tab" style="text-decoration:none;">Lent Books</a>
    </div>
    <a href="{{ route('borrow.add') }}" class="add-btn" style="text-decoration:none;">+</a>
  </div>

  <div class="borrow-stats">
    <div class="borrow-stat">
      <div class="si"><img src="{{ asset('images/icon_closed_book.png') }}" alt="" style="width:28px;height:28px;object-fit:contain;"></div>
      <div class="sl">8 Books Read</div>
    </div>
    <div class="borrow-stat">
      <div class="si"><img src="{{ asset('images/icon_books_open.png') }}" alt="" style="width:28px;height:28px;object-fit:contain;"></div>
      <div class="sl">3 On Read</div>
    </div>
    <div class="borrow-stat">
      <div class="si"><img src="{{ asset('images/icon_clipboard.png') }}" alt="" style="width:28px;height:28px;object-fit:contain;"></div>
      <div class="sl">5 Pending</div>
    </div>
    <div class="borrow-stat">
      <div class="si"><img src="{{ asset('images/icon_star.png') }}" alt="" style="width:28px;height:28px;object-fit:contain;"></div>
      <div class="sl">4.8 / 5.0 Rating</div>
    </div>
  </div>

  <div class="bsection-title">On Read</div>
  <div class="borrow-cards">
    @foreach($items as $item)
      @if($item['status'] == 'onread')
        <div class="borrow-card">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.outerHTML='&#128218;'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">&#128197; {{ $item['borrow_date'] }}</div>
            <div class="date-row">&#128198; {{ $item['return_date'] }}</div>
            <div class="msg-lender">
              <div class="lender-i">
                <div class="lender-av">
                    <img src="{{ asset('images/' . $item['lender_avatar']) }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                </div>
                <a href="{{ route('messages.show', $item['lender_id']) }}" style="color:var(--gray);text-decoration:none;">Message {{ $item['lender_name'] }}</a>
              </div>
              <span style="font-size:16px;color:var(--gray);cursor:pointer">&#9992;</span>
            </div>
          </div>
          <span class="status-b s-onread">On Read</span>
        </div>
      @endif
    @endforeach
  </div>

  <div class="bsection-title">Pending Application</div>
  <div class="borrow-cards">
    @foreach($items as $item)
      @if($item['status'] == 'appeal')
        <div class="borrow-card">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.outerHTML='&#128218;'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">&#128197; {{ $item['borrow_date'] }}</div>
            <div class="msg-lender">
              <div class="lender-i">
                <div class="lender-av">
                    <img src="{{ asset('images/' . $item['lender_avatar']) }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                </div>
                <a href="{{ route('messages.show', $item['lender_id']) }}" style="color:var(--gray);text-decoration:none;">Message {{ $item['lender_name'] }}</a>
              </div>
              <span style="font-size:16px;color:var(--gray);cursor:pointer">&#9992;</span>
            </div>
          </div>
          <span class="status-b s-appeal">Appealed</span>
        </div>
      @endif
    @endforeach
  </div>

  <div class="bsection-title">Finished Reading</div>
  <div class="borrow-cards">
    @foreach($items as $item)
      @if($item['status'] == 'finish')
        <div class="borrow-card">
          <div class="bc-cover">
             <img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.outerHTML='&#128218;'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">&#128197; {{ $item['borrow_date'] }}</div>
            <div class="date-row">&#128198; {{ $item['return_date'] }}</div>
            <div class="msg-lender">
              <div class="lender-i">
                <div class="lender-av"></div>
                <a href="{{ route('messages.show', $item['lender_id']) }}" style="color:var(--gray);text-decoration:none;">Message {{ $item['lender_name'] }}</a>
              </div>
              <span style="font-size:16px;color:var(--gray);cursor:pointer">&#9992;</span>
            </div>
          </div>
          <span class="status-b s-finish">Finished</span>
        </div>
      @endif
    @endforeach
  </div>

</div>
@endsection