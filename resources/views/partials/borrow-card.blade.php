@php
  $statusClass = match($item['status']) {
    'appeal'   => 's-appeal',
    'approved' => 's-approved',
    'onread'   => 's-onread',
    default    => 's-finish',
  };
  $statusLabel = $item['statusLabel'] ?? match($item['status']) {
    'appeal'   => __('borrow.appealed'),
    'approved' => __('borrow.approved'),
    'onread'   => __('borrow.on_read'),
    default    => __('borrow.finished'),
  };
@endphp
<div class="borrow-card" data-request-id="{{ $item['id'] }}" data-status="{{ $item['status'] }}">
  <div class="bc-cover">
    <img src="{{ asset('images/' . $item['cover']) }}" alt="{{ $item['title'] }}"
         onerror="this.outerHTML='📚'">
  </div>
  <div class="bc-info">
    <div class="bc-title">{{ $item['title'] }}</div>
    <div class="bc-author">{{ $item['author'] }}</div>
    <div class="date-row">🕐 {{ $item['borrow_date'] }}</div>
    @if(!empty($showReturnDate) && !empty($item['return_date']))
    <div class="date-row">📅 {{ $item['return_date'] }}</div>
    @endif
    <div class="msg-lender">
      <div class="lender-i">
        <div class="lender-av">
          <img src="{{ asset('images/' . ($item['lender_avatar'] ?? 'avatar_user.png')) }}" alt="{{ $item['lender_name'] }}"
               onerror="this.style.display='none'">
        </div>
        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['lender_name'] }}</span>
      </div>
      @if(!empty($showReview))
      <button type="button" class="bc-review-btn">{{ __('borrow.review') }}</button>
      @else
      <a href="{{ route('messages.show', $item['lender_id']) }}" class="bc-message-btn">{{ __('borrow.message') }}</a>
      @endif
      <a href="{{ route('messages.show', $item['lender_id']) }}" class="lender-msg-btn" title="{{ __('borrow.message') }}">✈</a>
    </div>
  </div>
  {{-- Status badge (read-only for borrower) --}}
  <span class="status-b {{ $statusClass }}">{{ $statusLabel }}</span>
</div>
