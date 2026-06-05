@extends('layouts.app')

@section('content')
<div style="padding:24px;">

  {{-- HEADER + TABS --}}
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <a href="{{ route('home') }}" style="width:36px;height:36px;background:var(--blue);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(37,99,235,.3);text-decoration:none;color:white;font-size:18px;flex-shrink:0;" title="{{ __('common.back') }}">←</a>
      <span style="font-size:20px;font-weight:800;color:var(--dark);">{{ __('borrow.borrowed_books') }}</span>
    </div>
    <div class="borrow-tabs">
      <a href="{{ route('borrow') }}" class="borrow-tab active">{{ __('borrow.borrowed_books') }}</a>
      <a href="{{ route('lent') }}" class="borrow-tab">{{ __('borrow.lent_books') }}</a>
    </div>
  </div>

  {{-- STATS --}}
  <div class="borrow-stats">
    <div class="borrow-stat">
      <div class="borrow-stat-left">
        <div class="borrow-stat-label">{{ __('borrow.books_read') }}</div>
        <div class="borrow-stat-value">{{ $stats['books_read'] }}</div>
      </div>
      <div class="borrow-stat-icon" style="background:#EFF6FF;color:#2563EB;">
        <img src="{{ asset('images/solar_book-broken.png') }}" alt="Books Read" style="width:24px;height:24px;object-fit:contain;">
      </div>
    </div>
    <div class="borrow-stat">
      <div class="borrow-stat-left">
        <div class="borrow-stat-label">{{ __('borrow.on_read') }}</div>
        <div class="borrow-stat-value">{{ $stats['on_read'] }}</div>
      </div>
      <div class="borrow-stat-icon" style="background:#FFF7ED;">
        <img src="{{ asset('images/Group 207.png') }}" alt="On Read" style="width:24px;height:24px;object-fit:contain;">
      </div>
    </div>
    <div class="borrow-stat">
      <div class="borrow-stat-left">
        <div class="borrow-stat-label">{{ __('borrow.pending_request') }}</div>
        <div class="borrow-stat-value">{{ $stats['pending'] }}</div>
      </div>
      <div class="borrow-stat-icon" style="background:#ECFDF5;">
        <img src="{{ asset('images/Group 206.png') }}" alt="Pending" style="width:24px;height:24px;object-fit:contain;">
      </div>
    </div>
    <div class="borrow-stat">
      <div class="borrow-stat-left">
        <div class="borrow-stat-label">{{ __('borrow.trust_score') }}</div>
        <div class="borrow-stat-value">{{ number_format($stats['trust_score'], 1) }}</div>
      </div>
      <div class="borrow-stat-icon" style="background:#FEFCE8;">
        <img src="{{ asset('images/star.png') }}" alt="Trust Score" style="width:24px;height:24px;object-fit:contain;">
      </div>
    </div>
  </div>

  {{-- ON READ --}}
  <div class="bsection-title">{{ __('borrow.on_read') }}</div>
  <div class="borrow-cards" id="section-onread">
    @php $onReadItems = array_filter($items, fn($item) => $item['status'] == 'onread'); @endphp
    @forelse($onReadItems as $item)
      @include('partials.borrow-card', ['item' => $item, 'showReturnDate' => true])
    @empty
    <p class="borrow-section-empty">{{ __('home.no_books_available') }}</p>
    @endforelse
  </div>

  {{-- PENDING APPLICATION --}}
  <div class="bsection-title">{{ __('borrow.pending_application') }}</div>
  <div class="borrow-cards" id="section-appeal">
    @php $appealItems = array_filter($items, fn($item) => $item['status'] == 'appeal'); @endphp
    @forelse($appealItems as $item)
      @include('partials.borrow-card', ['item' => $item, 'showReturnDate' => false])
    @empty
    <p class="borrow-section-empty">{{ __('home.no_books_available') }}</p>
    @endforelse
  </div>

  {{-- FINISHED READING --}}
  <div class="bsection-title">{{ __('borrow.finished_reading') }}</div>
  <div class="borrow-cards" id="section-finish">
    @php $finishItems = array_filter($items, fn($item) => $item['status'] == 'finish'); @endphp
    @forelse($finishItems as $item)
      @include('partials.borrow-card', ['item' => $item, 'showReturnDate' => true, 'showReview' => true])
    @empty
    <p class="borrow-section-empty">{{ __('home.no_books_available') }}</p>
    @endforelse
  </div>

</div>


@endsection
