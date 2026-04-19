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

    <div class="bsection-title">On Read</div>
    <div class="borrow-cards">
        @foreach($items as $item)
            @if($item['status'] == 'onread')
            <div class="borrow-card">
                <div class="bc-cover">
                    <img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;">
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
                <div class="bc-cover"><img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;"></div>
                <div class="bc-info">
                    <div class="bc-title">{{ $item['title'] }}</div>
                    <div class="bc-author">{{ $item['author'] }}</div>
                    <div class="date-row">&#128197; {{ $item['borrow_date'] }}</div>
                    <div class="msg-lender">
                        <div class="lender-i">
                            <a href="{{ route('messages.show', $item['lender_id']) }}" style="color:var(--gray);text-decoration:none;">Message {{ $item['lender_name'] }}</a>
                        </div>
                    </div>
                </div>
                <span class="status-b s-appeal">Appealed</span>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection